$utf8 = New-Object System.Text.UTF8Encoding $false
$bytes = [IO.File]::ReadAllBytes('C:\bilohash\lending\lang\sv.php')
$bom = ($bytes.Length -ge 3 -and $bytes[0] -eq 0xEF -and $bytes[1] -eq 0xBB -and $bytes[2] -eq 0xBF)
$t = $utf8.GetString($bytes)
Write-Host "BOM: $bom"
Write-Host $t.Substring(0, [Math]::Min(250, $t.Length))