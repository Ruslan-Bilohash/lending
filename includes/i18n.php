<?php
declare(strict_types=1);

define('LD_LANG_COOKIE', 'ld_lang');

$LD_LANGS = [
    'no' => ['label' => 'NO', 'name' => 'Norsk', 'flag' => '🇳🇴', 'locale' => 'nb-NO', 'html' => 'no'],
    'sv' => ['label' => 'SV', 'name' => 'Svenska', 'flag' => '🇸🇪', 'locale' => 'sv-SE', 'html' => 'sv'],
    'pl' => ['label' => 'PL', 'name' => 'Polski', 'flag' => '🇵🇱', 'locale' => 'pl-PL', 'html' => 'pl'],
    'en' => ['label' => 'EN', 'name' => 'English', 'flag' => '🇬🇧', 'locale' => 'en-GB', 'html' => 'en'],
    'lt' => ['label' => 'LT', 'name' => 'Lietuvių', 'flag' => '🇱🇹', 'locale' => 'lt-LT', 'html' => 'lt'],
    'uk' => ['label' => 'UA', 'name' => 'Українська', 'flag' => '🇺🇦', 'locale' => 'uk-UA', 'html' => 'uk'],
    'ru' => ['label' => 'RU', 'name' => 'Русский', 'flag' => '🇷🇺', 'locale' => 'ru-RU', 'html' => 'ru'],
];

function ld_langs(): array
{
    global $LD_LANGS;
    return $LD_LANGS;
}

/** @return array<string, string> */
function ld_lang_aliases(): array
{
    return ['ua' => 'uk', 'nb' => 'no'];
}

function ld_normalize_lang(string $code): string
{
    $code = strtolower(trim($code));
    return ld_lang_aliases()[$code] ?? $code;
}

/** URL/cookie-facing code (UA label → lang=ua, file storage stays uk). */
function ld_lang_public_code(string $code): string
{
    $code = ld_normalize_lang($code);
    return $code === 'uk' ? 'ua' : $code;
}

function ld_detect_lang(): string
{
    global $base_path, $LD_LANGS;
    $codes = array_keys($LD_LANGS);

    if (!empty($_GET['lang'])) {
        $chosen = ld_normalize_lang((string) $_GET['lang']);
        if (in_array($chosen, $codes, true)) {
            setcookie(LD_LANG_COOKIE, $chosen, [
                'expires'  => time() + 365 * 86400,
                'path'     => rtrim($base_path, '/') . '/' ?: '/',
                'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
                'samesite' => 'Lax',
            ]);
            return $chosen;
        }
    }
    if (!empty($_COOKIE[LD_LANG_COOKIE])) {
        $chosen = ld_normalize_lang((string) $_COOKIE[LD_LANG_COOKIE]);
        if (in_array($chosen, $codes, true)) {
            return $chosen;
        }
    }
    return 'no';
}

$lang      = ld_detect_lang();
$lang_meta = $LD_LANGS[$lang] ?? $LD_LANGS['no'];
$lang_file = __DIR__ . '/../lang/' . $lang . '.php';
if (!is_file($lang_file)) {
    $lang_file = __DIR__ . '/../lang/en.php';
}
$t = require $lang_file;

require_once dirname(__DIR__, 2) . '/includes/ecosystem-i18n.php';
$t = bh_apply_ecosystem_translations($t, $lang, 'lending');