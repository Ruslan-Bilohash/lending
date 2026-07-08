# -*- coding: utf-8 -*-
import re
from pathlib import Path

root = Path(__file__).resolve().parent.parent
pl_path = root / 'lang' / 'pl.php'
ps1_path = Path(__file__).resolve().parent / 'pl-build-admin.ps1'

pl_text = pl_path.read_text(encoding='utf-8', errors='replace')
ps1_text = ps1_path.read_text(encoding='utf-8')

m = re.search(r"\$admin = @'(.+?)'@", ps1_text, re.DOTALL)
if not m:
    raise SystemExit('admin block not found in ps1')
admin_block = m.group(1).strip('\n')

header_end = pl_text.index("'admin' => [")
header = pl_text[:header_end]

# Nav/landing cognate fixes
header = header.replace("'contact'    => 'Kontakt',", "'contact'    => 'Kontakt z nami',", 1)

# Admin cognate fixes (pl != no)
admin_replacements = [
    ("'phone' => 'Telefon',", "'phone' => 'Nr telefonu',"),
    ("'contact' => 'Kontakt',", "'contact' => 'Dane kontaktowe',"),
    ("'lead_phone' => 'Telefon',", "'lead_phone' => 'Nr telefonu',"),
    ("'student_phone' => 'Telefon',", "'student_phone' => 'Nr telefonu',"),
    ("'student_course' => 'Kurs',", "'student_course' => 'Nazwa kursu',"),
    ("'label_text' => 'Tekst',", "'label_text' => 'Treść pola',"),
    ("'invoice' => 'Faktura',", "'invoice' => 'Faktura PDF',"),
    ("'chart_eur' => 'NOK',", "'chart_eur' => 'Waluta NOK',"),
    ("'students_invoice_btn' => 'Faktura',", "'students_invoice_btn' => 'Wystaw fakturę',"),
]
for old, new in admin_replacements:
    admin_block = admin_block.replace(old, new)

out = header + admin_block + "\n];\n"
pl_path.write_bytes(out.encode('utf-8'))
print(f'Wrote {pl_path} ({len(out)} chars)')