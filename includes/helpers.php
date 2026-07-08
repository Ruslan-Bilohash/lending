<?php
declare(strict_types=1);

function ld_h(string $s): string
{
    return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function ld_asset(string $path): string
{
    global $base_path;
    return rtrim($base_path, '/') . '/assets/' . ltrim($path, '/');
}

function ld_absolute_url(string $path = '', array $qs = []): string
{
    global $site_url;
    $rel = ld_url($path, $qs);
    if (str_starts_with($rel, 'http://') || str_starts_with($rel, 'https://')) {
        return $rel;
    }
    $host = $_SERVER['HTTP_HOST'] ?? parse_url((string) $site_url, PHP_URL_HOST) ?: 'localhost';
    $protocol = (
        (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')
    ) ? 'https' : 'http';
    if (str_starts_with($rel, '/')) {
        return $protocol . '://' . $host . $rel;
    }
    return rtrim((string) $site_url, '/') . '/' . ltrim($rel, '/');
}

function ld_url(string $path = '', array $qs = []): string
{
    global $base_path, $lang;
    $url = rtrim($base_path, '/') . '/' . ltrim($path, '/');
    if ($lang !== 'no' && !isset($qs['lang'])) {
        $qs['lang'] = ld_lang_public_code($lang);
    }
    return $url . ($qs ? '?' . http_build_query($qs) : '');
}

function ld_lang_url(string $code): string
{
    $uri = $_SERVER['REQUEST_URI'] ?? '/lending/';
    $parts = parse_url($uri);
    parse_str($parts['query'] ?? '', $q);
    $code = ld_normalize_lang($code);
    if ($code === 'no') {
        unset($q['lang']);
    } else {
        $q['lang'] = ld_lang_public_code($code);
    }
    $path = $parts['path'] ?? '/lending/';
    return $path . ($q ? '?' . http_build_query($q) : '');
}

function ld_pick(array $row, string $lang, string $fallback = 'no'): string
{
    foreach (array_unique([$lang, $fallback, 'no', 'en', 'sv', 'pl', 'ru', 'uk', 'lt']) as $code) {
        if (!empty($row[$code])) {
            return (string) $row[$code];
        }
    }
    return '';
}

function ld_lang_labels(): array
{
    $labels = [];
    foreach (ld_langs() as $code => $meta) {
        $labels[$code] = $meta['label'];
    }
    return $labels;
}

function ld_template_id(): int
{
    if (!empty($_GET['t']) || !empty($_GET['template'])) {
        $id = (int) ($_GET['t'] ?? $_GET['template'] ?? 1);
        return max(1, min(10, $id));
    }
    return ld_active_template();
}

function ld_extract_iframe_src(string $input): string
{
    $input = trim($input);
    if ($input === '') {
        return '';
    }
    if (preg_match('/<iframe[^>]+src=["\']([^"\']+)["\']/i', $input, $m)) {
        return trim($m[1]);
    }
    return $input;
}

function ld_is_google_maps_embed(string $url): bool
{
    $url = trim($url);
    if ($url === '' || !filter_var($url, FILTER_VALIDATE_URL)) {
        return false;
    }
    $host = strtolower((string) parse_url($url, PHP_URL_HOST));
    return in_array($host, ['www.google.com', 'google.com', 'maps.google.com', 'maps.google.lt'], true)
        && str_contains($url, '/maps');
}

function ld_render_stars(int $rating, int $max = 5): string
{
    $rating = max(0, min($max, $rating));
    $html = '<span class="ld-stars" aria-label="' . $rating . '/' . $max . '">';
    for ($i = 1; $i <= $max; $i++) {
        $class = $i <= $rating ? 'fas fa-star' : 'far fa-star';
        $html .= '<i class="' . $class . '" aria-hidden="true"></i>';
    }
    $html .= '</span>';
    return $html;
}

function ld_is_driving_preset(): bool
{
    return (ld_settings()['business_preset'] ?? 'driving_school') === 'driving_school';
}

function ld_block_process(string $lang): array
{
    $process = ld_blocks()['process'] ?? [];
    if (empty($process['enabled'])) {
        return [];
    }

    return ld_localize_list($process['steps'] ?? [], $lang, ['title', 'desc']);
}

function ld_templates_meta(): array
{
    return [
        1  => ['slug' => 'classic-blue',   'layout' => 'center', 'icon' => 'fa-road'],
        2  => ['slug' => 'sunset-drive',   'layout' => 'split',  'icon' => 'fa-car'],
        3  => ['slug' => 'forest-safe',    'layout' => 'stats',  'icon' => 'fa-shield-halved'],
        4  => ['slug' => 'night-modern',   'layout' => 'split',  'icon' => 'fa-moon'],
        5  => ['slug' => 'minimal-light',  'layout' => 'center', 'icon' => 'fa-feather'],
        6  => ['slug' => 'racing-red',     'layout' => 'stats',  'icon' => 'fa-flag-checkered'],
        7  => ['slug' => 'premium-purple', 'layout' => 'center', 'icon' => 'fa-gem'],
        8  => ['slug' => 'teal-urban',     'layout' => 'split',  'icon' => 'fa-city'],
        9  => ['slug' => 'taxi-yellow',    'layout' => 'stats',  'icon' => 'fa-taxi'],
        10 => ['slug' => 'slate-corporate','layout' => 'center', 'icon' => 'fa-building'],
    ];
}