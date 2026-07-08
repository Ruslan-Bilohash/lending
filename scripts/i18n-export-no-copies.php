<?php
declare(strict_types=1);

function flatten(array $a, string $p = ''): array
{
    $out = [];
    foreach ($a as $k => $v) {
        $path = $p === '' ? (string) $k : $p . '.' . $k;
        if (is_array($v)) {
            $out = array_merge($out, flatten($v, $path));
        } else {
            $out[$path] = (string) $v;
        }
    }
    return $out;
}

$dir = dirname(__DIR__) . '/lang';
$en = flatten(require $dir . '/en.php');
$no = flatten(require $dir . '/no.php');

foreach (['sv', 'pl'] as $lang) {
    $loc = flatten(require $dir . '/' . $lang . '.php');
    $need = [];
    foreach ($loc as $k => $v) {
        if (!isset($no[$k], $en[$k])) {
            continue;
        }
        if ($no[$k] === $en[$k]) {
            continue;
        }
        if ($loc[$k] === $no[$k]) {
            $need[$k] = ['en' => $en[$k], 'no' => $no[$k], 'current' => $loc[$k]];
        }
    }
    file_put_contents(
        dirname(__DIR__) . '/scripts/i18n-fix-' . $lang . '.json',
        json_encode($need, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
    );
    echo $lang . ': ' . count($need) . " keys to fix\n";
}