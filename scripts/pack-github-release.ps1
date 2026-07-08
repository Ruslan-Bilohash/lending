# Pack Business Landing CMS demo zip for GitHub Release
param([string]$Version = (Get-Content (Join-Path $PSScriptRoot '..\VERSION') -Raw).Trim())

$ErrorActionPreference = 'Stop'
$root = Split-Path $PSScriptRoot -Parent
$zipName = "lending-demo-30d-v$Version.zip"
$zipPath = Join-Path $root $zipName
$staging = Join-Path $env:TEMP ('ld-release-' + [guid]::NewGuid().ToString('n'))

try {
    New-Item -ItemType Directory -Path $staging -Force | Out-Null
    $items = @(
        '.htaccess', 'config.php', 'init.php', 'index.php', 'index.html', 'template.php', 'page.php', 'contact.php', 'sitemap.php', 'robots.txt',
        'VERSION', 'CHANGELOG.md', 'LICENSE', 'DEMO.md',
        'LICENSE-no.md', 'LICENSE-sv.md', 'LICENSE-pl.md', 'LICENSE-lt.md', 'LICENSE-uk.md', 'LICENSE-ru.md',
        'README.md', 'README-no.md', 'README-sv.md', 'README-pl.md', 'README-lt.md', 'README-uk.md', 'README-ru.md',
        'DEMO-no.md', 'DEMO-sv.md', 'DEMO-pl.md', 'DEMO-lt.md', 'DEMO-uk.md', 'DEMO-ru.md',
        'includes', 'lang', 'assets', 'admin', 'api', 'data', 'uploads'
    )
    foreach ($item in $items) {
        $src = Join-Path $root $item
        if (-not (Test-Path $src)) { throw "Missing $src" }
        Copy-Item -Path $src -Destination (Join-Path $staging $item) -Recurse -Force
    }
    if (Test-Path $zipPath) { Remove-Item $zipPath -Force }
    Push-Location $staging
    try { & tar -a -c -f $zipPath * } finally { Pop-Location }
    $bytes = (Get-Item $zipPath).Length
    Write-Host "Packed $zipName ($bytes bytes) -> $zipPath"
} finally {
    if (Test-Path $staging) { Remove-Item $staging -Recurse -Force -ErrorAction SilentlyContinue }
}