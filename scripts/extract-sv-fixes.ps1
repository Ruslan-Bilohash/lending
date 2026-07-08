# Extract sv keys that are NO copies needing Swedish translation
$ErrorActionPreference = 'Stop'
$dir = Join-Path (Split-Path $PSScriptRoot -Parent) 'lang'

function Get-PhpLeafMap([string]$Content) {
    $Content = $Content -replace '^\xEF\xBB\xBF', ''
    $map = @{}
    $stack = [System.Collections.Generic.List[string]]::new()
    foreach ($rawLine in ($Content -split "`r?`n")) {
        $line = $rawLine.Trim()
        if ($line -eq '' -or $line.StartsWith('//')) { continue }
        $opensInline = $false
        if ($line -match "['`"]([^'`"]+)['`"]\s*=>\s*'((?:\\'|[^'])*)'") {
            $keyOnLine = $Matches[1]
            $val = $Matches[2] -replace "\\'", "'"
            $path = if ($stack.Count -gt 0) { ($stack.ToArray() -join '.') + '.' + $keyOnLine } else { $keyOnLine }
            $map[$path] = $val
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
    return $map
}

$en = Get-PhpLeafMap ([IO.File]::ReadAllText((Join-Path $dir 'en.php')))
$no = Get-PhpLeafMap ([IO.File]::ReadAllText((Join-Path $dir 'no.php')))
$sv = Get-PhpLeafMap ([IO.File]::ReadAllText((Join-Path $dir 'sv.php')))

$fixes = @()
foreach ($k in ($sv.Keys | Sort-Object)) {
    if (-not $no.ContainsKey($k) -or -not $en.ContainsKey($k)) { continue }
    if ($no[$k] -eq $en[$k]) { continue }
    if ($sv[$k] -eq $no[$k]) {
        $fixes += [PSCustomObject]@{ key = $k; en = $en[$k]; no = $no[$k] }
    }
}

Write-Host "Total fixes needed: $($fixes.Count)"
$out = Join-Path $PSScriptRoot 'sv-fixes.json'
$fixes | ConvertTo-Json -Depth 3 | Set-Content -Path $out -Encoding UTF8
Write-Host "Written to $out"