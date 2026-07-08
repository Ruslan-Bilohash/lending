param([string]$Path)
$c = [IO.File]::ReadAllText($Path, [Text.Encoding]::UTF8)
$bom = New-Object System.Text.UTF8Encoding $true
[IO.File]::WriteAllText($Path, $c, $bom)
Write-Host "BOM added to $Path"