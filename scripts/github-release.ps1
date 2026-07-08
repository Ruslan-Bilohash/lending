# Create GitHub release for Business Landing CMS demo
param(
    [string]$Version = (Get-Content (Join-Path $PSScriptRoot '..\VERSION') -Raw).Trim(),
    [string]$ZipName = ''
)

$ErrorActionPreference = 'Stop'
$root = Split-Path $PSScriptRoot -Parent
if ($ZipName -eq '') { $ZipName = "lending-demo-30d-v$Version.zip" }
$zipPath = Join-Path $root $ZipName
$notesPath = Join-Path $root "RELEASE-v$Version.md"
if (-not (Test-Path $zipPath)) {
    & (Join-Path $PSScriptRoot 'pack-github-release.ps1') -Version $Version
}
if (-not (Test-Path $notesPath)) { throw "Missing $notesPath" }

$token = (("protocol=https`nhost=github.com`n`n" | git credential fill) -split "`n" | Where-Object { $_ -like 'password=*' }) -replace 'password=',''
if ($token -eq '') { throw 'GitHub token not found via git credential fill' }

$notes = [IO.File]::ReadAllText($notesPath, [Text.UTF8Encoding]::new($false))
$payload = [ordered]@{
    tag_name = "v$Version"
    name = "v$Version - template demos, country SEO, cross-links (30-day demo)"
    body = $notes
    draft = $false
    prerelease = $false
}
$bodyFile = Join-Path $root '_release-body.json'
$respFile = Join-Path $root '_release_resp.json'
$uploadFile = Join-Path $root '_upload_resp.json'
[IO.File]::WriteAllText($bodyFile, ($payload | ConvertTo-Json -Depth 5), [Text.UTF8Encoding]::new($false))

curl.exe -sS --max-time 90 -X POST `
    -H "Authorization: Bearer $token" `
    -H "Accept: application/vnd.github+json" `
    -H "Content-Type: application/json; charset=utf-8" `
    "https://api.github.com/repos/Ruslan-Bilohash/lending/releases" `
    --data-binary "@$bodyFile" -o $respFile -w "CREATE:%{http_code}`n"

$rel = Get-Content $respFile -Raw | ConvertFrom-Json
if (-not $rel.upload_url) {
    Write-Host (Get-Content $respFile -Raw)
    throw 'Release create failed'
}

$uploadUrl = ($rel.upload_url -replace '\{\?name,label\}', '') + "?name=$ZipName"
curl.exe -sS --max-time 300 -X POST `
    -H "Authorization: Bearer $token" `
    -H "Accept: application/vnd.github+json" `
    -H "Content-Type: application/zip" `
    --data-binary "@$zipPath" $uploadUrl -o $uploadFile -w "UPLOAD:%{http_code}`n"

$asset = Get-Content $uploadFile -Raw | ConvertFrom-Json
Write-Host "Release: $($rel.html_url)"
Write-Host "Asset: $($asset.browser_download_url) ($($asset.size) bytes)"