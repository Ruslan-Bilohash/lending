<?php
declare(strict_types=1);

define('LD_ADMIN_USER', 'demo');
define('LD_ADMIN_PASS', 'bilolending2026');
define('LD_ADMIN_SESSION_KEY', 'ld_admin_logged');
define('LD_ADMIN_LANG_COOKIE', 'ld_admin_lang');
define('LD_ADMIN_LANG_DEFAULT', 'no');

function ld_admin_start(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function ld_admin_logged(): bool
{
    ld_admin_start();
    return !empty($_SESSION[LD_ADMIN_SESSION_KEY]);
}

function ld_admin_login(string $user, string $pass): bool
{
    if ($user === LD_ADMIN_USER && $pass === LD_ADMIN_PASS) {
        ld_admin_start();
        $_SESSION[LD_ADMIN_SESSION_KEY] = true;
        $_SESSION['ld_admin_user'] = $user;
        return true;
    }
    return false;
}

function ld_admin_logout(): void
{
    ld_admin_start();
    unset($_SESSION[LD_ADMIN_SESSION_KEY], $_SESSION['ld_admin_user']);
}

function ld_admin_require(): void
{
    if (!ld_admin_logged()) {
        header('Location: ' . ld_admin_url('login.php'), true, 302);
        exit;
    }
}

function ld_admin_url(string $path = '', array $qs = []): string
{
    return ld_url('admin/' . ltrim($path, '/'), $qs);
}

function ld_admin_detect_lang(): string
{
    global $base_path;
    $codes = array_keys(ld_langs());
    $default = LD_ADMIN_LANG_DEFAULT;

    if (!empty($_GET['lang']) && in_array($_GET['lang'], $codes, true)) {
        $chosen = $_GET['lang'];
        setcookie(LD_ADMIN_LANG_COOKIE, $chosen, [
            'expires'  => time() + 365 * 86400,
            'path'     => rtrim($base_path, '/') . '/admin/',
            'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
            'samesite' => 'Lax',
        ]);
        return $chosen;
    }
    if (!empty($_COOKIE[LD_ADMIN_LANG_COOKIE]) && in_array($_COOKIE[LD_ADMIN_LANG_COOKIE], $codes, true)) {
        return $_COOKIE[LD_ADMIN_LANG_COOKIE];
    }

    return $default;
}

function ld_admin_reload_i18n(): array
{
    $lang = ld_admin_detect_lang();
    global $LD_LANGS, $lang_meta, $lang;
    $lang_meta = $LD_LANGS[$lang] ?? $LD_LANGS[LD_ADMIN_LANG_DEFAULT];
    $lang_file = dirname(__DIR__) . '/lang/' . $lang . '.php';
    if (!is_file($lang_file)) {
        $lang_file = dirname(__DIR__) . '/lang/en.php';
    }
    $t = require $lang_file;
    if (function_exists('bh_apply_ecosystem_translations')) {
        $t = bh_apply_ecosystem_translations($t, $lang, 'lending');
    }

    return $t;
}

function ld_admin_t(string $key, string $fallback = ''): string
{
    global $ta;
    if (isset($ta[$key]) && (string) $ta[$key] !== '') {
        return (string) $ta[$key];
    }

    return $fallback;
}

/**
 * Strings for admin panel JavaScript (charts, AI, news).
 *
 * @return array<string, string>
 */
function ld_admin_i18n_js(): array
{
    $keys = [
        'brief_required',
        'error',
        'network_error',
        'generating',
        'saved',
        'ai_demo_article',
        'ai_article_ok',
        'seo_demo',
        'seo_ok',
        'seo_ai_ok',
        'chart_leads',
        'chart_eur',
    ];
    $out = [];
    foreach ($keys as $k) {
        if ($k === 'saved') {
            $out[$k] = ld_admin_t('ai_fill_saved', ld_admin_t('saved'));
            continue;
        }
        $out[$k] = ld_admin_t('js_' . $k);
    }

    return $out;
}

/**
 * @return array<string, string>
 */
function ld_admin_notify_js(): array
{
    $keys = [
        'saved',
        'deleted',
        'agent_thinking',
        'agent_analyzing',
        'agent_writing',
        'error',
        'network_error',
        'preset_applied',
        'invoice_ok',
        'design_applied',
        'seo_done',
        'ai_done',
    ];
    $out = [];
    foreach ($keys as $k) {
        $out[$k] = ld_admin_t('notify_' . $k);
    }

    return $out;
}

/**
 * @return array{type: string, message: string}|null
 */
function ld_admin_detect_flash(): ?array
{
    global $admin_page;

    if (isset($_GET['saved'])) {
        $page = (string) ($admin_page ?? '');
        $message = match ($page) {
            'news' => ld_admin_t('news_saved', ld_admin_t('notify_saved')),
            'students' => ld_admin_t('students_saved', ld_admin_t('notify_saved')),
            default => ld_admin_t('notify_saved', ld_admin_t('saved')),
        };

        return ['type' => 'success', 'message' => $message];
    }
    if (isset($_GET['deleted'])) {
        $page = (string) ($admin_page ?? '');
        $message = match ($page) {
            'news' => ld_admin_t('news_deleted', ld_admin_t('notify_deleted')),
            'students' => ld_admin_t('students_deleted', ld_admin_t('notify_deleted')),
            default => ld_admin_t('notify_deleted'),
        };

        return ['type' => 'success', 'message' => $message];
    }
    if (!empty($_GET['applied'])) {
        return ['type' => 'success', 'message' => ld_admin_t('notify_preset_applied', ld_admin_t('preset_applied'))];
    }
    if (isset($_GET['invoice']) && $_GET['invoice'] === 'ok') {
        return ['type' => 'success', 'message' => ld_admin_t('notify_invoice_ok', ld_admin_t('students_invoice_ok'))];
    }
    if (!empty($_GET['preset'])) {
        return ['type' => 'success', 'message' => ld_admin_t('notify_design_applied', ld_admin_t('design_preset_applied'))];
    }

    return null;
}

function ld_admin_lang_url(string $code): string
{
    $uri = $_SERVER['REQUEST_URI'] ?? '/lending/admin/';
    $parts = parse_url($uri);
    parse_str($parts['query'] ?? '', $q);
    $q['lang'] = $code;
    $path = $parts['path'] ?? '/lending/admin/';
    return $path . ($q ? '?' . http_build_query($q) : '');
}