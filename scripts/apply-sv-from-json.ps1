# Apply Swedish translations from JSON map to sv.php (UTF-8 no BOM)
param(
    [string]$MapFile = '',
    [switch]$AllMaps
)

$ErrorActionPreference = 'Stop'
$dir = Join-Path (Split-Path $PSScriptRoot -Parent) 'lang'
$svPath = Join-Path $dir 'sv.php'

function Load-TranslationMap([string]$Path) {
    $map = @{}
    $obj = Get-Content $Path -Raw -Encoding UTF8 | ConvertFrom-Json
    $obj.PSObject.Properties | ForEach-Object { $map[$_.Name] = [string]$_.Value }
    return $map
}

$translations = @{}
if ($AllMaps) {
    foreach ($f in @('sv-translations.json', 'sv-cognate-fixes.json')) {
        $p = Join-Path $PSScriptRoot $f
        if (Test-Path $p) {
            $m = Load-TranslationMap $p
            foreach ($k in $m.Keys) { $translations[$k] = $m[$k] }
        }
    }
} else {
    if (-not $MapFile) { $MapFile = Join-Path $PSScriptRoot 'sv-translations.json' }
    $translations = Load-TranslationMap $MapFile
}

function Get-PhpLeafLines([string]$Content) {
    $Content = $Content -replace '^\xEF\xBB\xBF', ''
    $items = [System.Collections.Generic.List[object]]::new()
    $stack = [System.Collections.Generic.List[string]]::new()
    $lineNo = 0
    foreach ($rawLine in ($Content -split "`r?`n", [StringSplitOptions]::None)) {
        $lineNo++
        $line = $rawLine.Trim()
        if ($line -eq '' -or $line.StartsWith('//')) { continue }
        $opensInline = $false
        if ($line -match "['`"]([^'`"]+)['`"]\s*=>\s*'((?:\\'|[^'])*)'") {
            $keyOnLine = $Matches[1]
            $val = $Matches[2] -replace "\\'", "'"
            $path = if ($stack.Count -gt 0) { ($stack.ToArray() -join '.') + '.' + $keyOnLine } else { $keyOnLine }
            $items.Add([PSCustomObject]@{ Line = $lineNo; Path = $path; Key = $keyOnLine; Value = $val; Raw = $rawLine }) | Out-Null
        } elseif ($line -match "['`"]([^'`"]+)['`"]\s*=>") {
            $keyOnLine = $Matches[1]
            $after = $line.Substring($line.IndexOf('=>') + 2).Trim()
            if ($after.StartsWith('[')) {
                $stack.Add($keyOnLine) | Out-Null
                $opensInline = $true
            }
        }
        $closes = ([regex]::Matches($line, '\]')).Count
        if ($opensInline) { $closes = [Math]::Max(0, $closes - 1) }
        for ($i = 0; $i -lt $closes; $i++) {
            if ($stack.Count -gt 0) { $stack.RemoveAt($stack.Count - 1) }
        }
    }
    return $items
}

function Escape-PhpSingle([string]$s) {
    return ($s -replace "'", "\'")
}

$utf8NoBom = New-Object System.Text.UTF8Encoding $false
$rawBytes = [IO.File]::ReadAllBytes($svPath)
if ($rawBytes.Length -ge 3 -and $rawBytes[0] -eq 0xEF -and $rawBytes[1] -eq 0xBB -and $rawBytes[2] -eq 0xBF) {
    $rawBytes = $rawBytes[3..($rawBytes.Length - 1)]
}
$content = $utf8NoBom.GetString($rawBytes)
$content = $content -replace '^\xEF\xBB\xBF', ''
$lines = [System.Collections.Generic.List[string]]::new()
$lines.AddRange(($content -split "`r?`n", [StringSplitOptions]::None))
$leafLines = Get-PhpLeafLines $content

$applied = 0
$notFound = [System.Collections.Generic.List[string]]::new()

foreach ($key in $translations.Keys) {
    $match = $leafLines | Where-Object { $_.Path -eq $key } | Select-Object -First 1
    if (-not $match) {
        $leaf = if ($key -match '\.') { $key.Substring($key.LastIndexOf('.') + 1) } else { $key }
        $candidates = @($leafLines | Where-Object { $_.Key -eq $leaf })
        if ($key -notmatch '\.' -and $candidates.Count -ge 1) {
            $match = $candidates[0]
        } elseif ($candidates.Count -eq 1) {
            $match = $candidates[0]
        }
    }
    if (-not $match) {
        $notFound.Add($key) | Out-Null
        continue
    }
    $newVal = Escape-PhpSingle $translations[$key]
    $idx = $match.Line - 1
    $oldLine = $lines[$idx]
    if ($oldLine -notmatch "=>") { continue }
    $newLine = [regex]::Replace($oldLine, "('(?:\\'|[^'])*')(\s*,?\s*)$", "'$newVal'`$2", 1)
    if ($newLine -ne $oldLine) {
        $lines[$idx] = $newLine
        $applied++
    }
}

$out = ($lines -join "`n")
if (-not $out.EndsWith("`n")) { $out += "`n" }
[IO.File]::WriteAllBytes($svPath, $utf8NoBom.GetBytes($out))

Write-Host "Applied $applied fixes"
if ($notFound.Count -gt 0) {
    Write-Host "Not found ($($notFound.Count)):"
    $notFound | Select-Object -First 20 | ForEach-Object { Write-Host "  $_" }
}