#!/usr/bin/env python3
"""Extract sv keys that are NO copies needing Swedish translation."""
import re
import json
from pathlib import Path

LANG_DIR = Path(__file__).resolve().parent.parent / 'lang'

def parse_php_leaves(content: str) -> dict:
    content = content.lstrip('\ufeff')
    result = {}
    stack = []
    for raw_line in content.splitlines():
        line = raw_line.strip()
        if not line or line.startswith('//'):
            continue
        opens_inline = False
        m = re.match(r"""['"]([^'"]+)['"]\s*=>\s*'((?:\\'|[^'])*)'""", line)
        if m:
            key, val = m.group(1), m.group(2).replace("\\'", "'")
            path = '.'.join(stack + [key]) if stack else key
            result[path] = val
        elif re.match(r"""['"]([^'"]+)['"]\s*=>""", line):
            key = re.match(r"""['"]([^'"]+)['"]\s*=>""", line).group(1)
            after = line[line.index('=>') + 2:].strip()
            if after.startswith('['):
                stack.append(key)
                opens_inline = True
        closes = line.count(']')
        if opens_inline:
            closes = max(0, closes - 1)
        for _ in range(closes):
            if stack:
                stack.pop()
    return result

en = parse_php_leaves((LANG_DIR / 'en.php').read_text(encoding='utf-8'))
no = parse_php_leaves((LANG_DIR / 'no.php').read_text(encoding='utf-8'))
sv = parse_php_leaves((LANG_DIR / 'sv.php').read_text(encoding='utf-8'))

fixes = []
for k in sorted(sv.keys()):
    if k not in no or k not in en:
        continue
    if no[k] == en[k]:
        continue
    if sv[k] == no[k]:
        fixes.append({'key': k, 'en': en[k], 'no': no[k], 'sv': sv[k]})

print(f"Total fixes needed: {len(fixes)}")
out = LANG_DIR.parent / 'scripts' / 'sv-fixes.json'
out.write_text(json.dumps(fixes, ensure_ascii=False, indent=2), encoding='utf-8')
print(f"Written to {out}")