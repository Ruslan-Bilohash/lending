# Audit lang/*.php completeness vs en.php — target 100% for uk, lt.
param([int]$FailUnder = 100)

$ErrorActionPreference = 'Stop'
$root = Split-Path $PSScriptRoot -Parent
$dir = Join-Path $root 'lang'
$langs = @('no', 'sv', 'pl', 'uk', 'lt', 'ru')

function Get-PhpLeafKeys([string]$Content) {
    $Content = $Content -replace '^\xEF\xBB\xBF', ''
    $keys = [System.Collections.Generic.List[string]]::new()
    $stack = [System.Collections.Generic.List[string]]::new()
    foreach ($rawLine in ($Content -split "`r?`n")) {
        $line = $rawLine.Trim()
        if ($line -eq '' -or $line.StartsWith('//')) { continue }
        $opensInline = $false
        if ($line -match "['`"]([^'`"]+)['`"]\s*=>") {
            $keyOnLine = $Matches[1]
            $after = $line.Substring($line.IndexOf('=>') + 2).Trim()
            if ($after.StartsWith('[')) {
                $stack.Add($keyOnLine) | Out-Null
                $opensInline = $true
            } elseif ($after -notmatch '^\s*require\b' -and $after -notmatch '^\s*array_replace') {
                $path = if ($stack.Count -gt 0) { ($stack.ToArray() -join '.') + '.' + $keyOnLine } else { $keyOnLine }
                $keys.Add($path) | Out-Null
            }
        }
        $closes = ([regex]::Matches($line, '\]')).Count
        if ($opensInline) { $closes = [Math]::Max(0, $closes - 1) }
        for ($i = 0; $i -lt $closes; $i++) {
            if ($stack.Count -gt 0) { $stack.RemoveAt($stack.Count - 1) }
        }
    }
    return @($keys | Select-Object -Unique)
}

$en = Join-Path $dir 'en.php'
$baseKeys = Get-PhpLeafKeys ([IO.File]::ReadAllText($en))
Write-Host "=== lang (base=$($baseKeys.Count)) ==="
$hasFail = $false
foreach ($lang in $langs) {
    $f = Join-Path $dir "$lang.php"
    $lk = Get-PhpLeafKeys ([IO.File]::ReadAllText($f))
    $missing = @($baseKeys | Where-Object { $_ -notin $lk })
    $pct = [math]::Round((($baseKeys.Count - $missing.Count) / [math]::Max(1, $baseKeys.Count)) * 100, 1)
    $status = if ($pct -ge $FailUnder) { 'OK' } else { 'FAIL'; $hasFail = $true }
    Write-Host "  $lang : $pct% ($status) - $($missing.Count) missing"
    if ($missing.Count -gt 0) {
        $missing | ForEach-Object { Write-Host "    $_" }
    }
}
if ($hasFail) { exit 1 }
exit 0