<?php
function flatten(array $a, string $p = ''): array
{
    $out = [];
    foreach ($a as $k => $v) {
        $path = $p === '' ? (string) $k : $p . '.' . $k;
        if (is_array($v)) {
            $out = array_merge($out, flatten($v, $path));
        } else {
            $out[] = $path;
        }
    }
    return $out;
}

$langs = ['lt', 'en', 'uk', 'ru'];
$maps = [];
foreach ($langs as $l) {
    $t = require dirname(__DIR__) . '/lang/' . $l . '.php';
    $maps[$l] = flatten($t);
    $maps[$l . '_admin'] = flatten($t['admin'] ?? [], 'admin');
}

$base = $maps['lt'];
$baseAdmin = $maps['lt_admin'];

foreach ($langs as $l) {
    if ($l === 'lt') {
        continue;
    }
    $missing = array_diff($base, $maps[$l]);
    $missingAdmin = array_diff($baseAdmin, $maps[$l . '_admin']);
    echo "\n=== $l: missing full (" . count($missing) . ") admin (" . count($missingAdmin) . ") ===\n";
    foreach ($missingAdmin as $m) {
        echo "  $m\n";
    }
    if ($missingAdmin === [] && $missing !== []) {
        $nonAdmin = array_filter($missing, static fn(string $k): bool => !str_starts_with($k, 'admin.'));
        if ($nonAdmin !== []) {
            echo "  (non-admin missing: " . count($nonAdmin) . ")\n";
            foreach (array_slice($nonAdmin, 0, 10) as $m) {
                echo "    $m\n";
            }
        }
    }
}