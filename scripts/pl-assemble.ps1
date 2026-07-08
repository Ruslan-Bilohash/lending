$ErrorActionPreference = 'Stop'
$root = Split-Path $PSScriptRoot -Parent
$plPath = Join-Path $root 'lang\pl.php'
$ps1Path = Join-Path $PSScriptRoot 'pl-build-admin.ps1'

$plText = [IO.File]::ReadAllText($plPath, [Text.UTF8Encoding]::new($false))
$ps1Text = [IO.File]::ReadAllText($ps1Path, [Text.UTF8Encoding]::new($false))

if ($ps1Text -notmatch "\`$admin = @'([\s\S]+?)'@") { throw 'admin block not found' }
$adminBlock = $Matches[1].Trim("`n")

$headerEnd = $plText.IndexOf("'admin' => [")
if ($headerEnd -lt 0) { throw 'admin section not found in pl.php' }
$header = $plText.Substring(0, $headerEnd)

$header = $header.Replace("'contact'    => 'Kontakt',", "'contact'    => 'Kontakt z nami',")

$replacements = @(
    @("'phone' => 'Telefon',", "'phone' => 'Nr telefonu',"),
    @("'contact' => 'Kontakt',", "'contact' => 'Dane kontaktowe',"),
    @("'lead_phone' => 'Telefon',", "'lead_phone' => 'Nr telefonu',"),
    @("'student_phone' => 'Telefon',", "'student_phone' => 'Nr telefonu',"),
    @("'student_course' => 'Kurs',", "'student_course' => 'Nazwa kursu',"),
    @("'label_text' => 'Tekst',", "'label_text' => 'Treść pola',"),
    @("'invoice' => 'Faktura',", "'invoice' => 'Faktura PDF',"),
    @("'chart_eur' => 'NOK',", "'chart_eur' => 'Waluta NOK',"),
    @("'students_invoice_btn' => 'Faktura',", "'students_invoice_btn' => 'Wystaw fakturę',")
)
foreach ($pair in $replacements) {
    $adminBlock = $adminBlock.Replace($pair[0], $pair[1])
}

$out = $header + $adminBlock + "`n];`n"
$utf8 = New-Object System.Text.UTF8Encoding $false
[IO.File]::WriteAllText($plPath, $out, $utf8)
Write-Host "Wrote $plPath ($($out.Length) chars)"