# Fix mojibake: UTF-8 misread as Latin-1 then saved as UTF-8
$ErrorActionPreference = 'Stop'
$svPath = Join-Path (Split-Path $PSScriptRoot -Parent) 'lang\sv.php'
$bytes = [IO.File]::ReadAllText($svPath, [Text.Encoding]::UTF8)
$latin1 = [Text.Encoding]::GetEncoding('ISO-8859-1')
$fixed = [Text.Encoding]::UTF8.GetString($latin1.GetBytes($bytes))
$utf8NoBom = New-Object System.Text.UTF8Encoding $false
[IO.File]::WriteAllText($svPath, $fixed, $utf8NoBom)
Write-Host 'Encoding fixed'