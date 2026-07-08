<?php
declare(strict_types=1);

function ld_post_trim(string $key, string $default = ''): string
{
    return trim((string) ($_POST[$key] ?? $default));
}

function ld_post_i18n(string $prefix): array
{
    $out = [];
    foreach (ld_langs_codes() as $code) {
        $out[$code] = ld_post_trim($prefix . '_' . $code);
    }
    return $out;
}

function ld_post_i18n_optional(string $prefix): ?array
{
    $row = ld_post_i18n($prefix);
    foreach ($row as $value) {
        if ($value !== '') {
            return $row;
        }
    }
    return null;
}

function ld_row_has_value(array $row, array $keys): bool
{
    foreach ($keys as $key) {
        $value = $row[$key] ?? '';
        if (is_array($value)) {
            foreach ($value as $part) {
                if (trim((string) $part) !== '') {
                    return true;
                }
            }
        } elseif (trim((string) $value) !== '') {
            return true;
        }
    }
    return false;
}

function ld_post_stats(): array
{
    $raw = $_POST['stats'] ?? [];
    if (!is_array($raw)) {
        return [];
    }
    $out = [];
    foreach ($raw as $row) {
        if (!is_array($row)) {
            continue;
        }
        $item = [
            'value' => trim((string) ($row['value'] ?? '')),
            'label' => [],
        ];
        foreach (ld_langs_codes() as $code) {
            $item['label'][$code] = trim((string) ($row['label_' . $code] ?? ''));
        }
        if (ld_row_has_value($item, ['value', 'label'])) {
            $out[] = $item;
        }
    }
    return $out;
}

function ld_post_services(): array
{
    $raw = $_POST['services'] ?? [];
    if (!is_array($raw)) {
        return [];
    }
    $out = [];
    foreach ($raw as $row) {
        if (!is_array($row)) {
            continue;
        }
        $item = [
            'icon' => trim((string) ($row['icon'] ?? 'fa-star')),
            'name' => [],
            'desc' => [],
            'price' => trim((string) ($row['price'] ?? '')),
            'badge' => [],
        ];
        foreach (ld_langs_codes() as $code) {
            $item['name'][$code] = trim((string) ($row['name_' . $code] ?? ''));
            $item['desc'][$code] = trim((string) ($row['desc_' . $code] ?? ''));
            $item['badge'][$code] = trim((string) ($row['badge_' . $code] ?? ''));
        }
        $item['badge'] = ld_row_has_value(['badge' => $item['badge']], ['badge']) ? $item['badge'] : null;
        if (ld_row_has_value($item, ['name', 'desc', 'price'])) {
            $out[] = $item;
        }
    }
    return $out;
}

function ld_post_team(): array
{
    $raw = $_POST['team'] ?? [];
    if (!is_array($raw)) {
        return [];
    }
    $out = [];
    foreach ($raw as $row) {
        if (!is_array($row)) {
            continue;
        }
        $item = [
            'name' => trim((string) ($row['name'] ?? '')),
            'initials' => trim((string) ($row['initials'] ?? '')),
            'years' => trim((string) ($row['years'] ?? '')),
            'role' => [],
        ];
        foreach (ld_langs_codes() as $code) {
            $item['role'][$code] = trim((string) ($row['role_' . $code] ?? ''));
        }
        if (ld_row_has_value($item, ['name', 'role'])) {
            if ($item['initials'] === '' && $item['name'] !== '') {
                $parts = preg_split('/\s+/', $item['name']) ?: [];
                $initials = '';
                foreach ($parts as $part) {
                    $initials .= mb_strtoupper(mb_substr($part, 0, 1));
                }
                $item['initials'] = mb_substr($initials, 0, 3);
            }
            $out[] = $item;
        }
    }
    return $out;
}

function ld_post_faq(): array
{
    $raw = $_POST['faq'] ?? [];
    if (!is_array($raw)) {
        return [];
    }
    $out = [];
    foreach ($raw as $row) {
        if (!is_array($row)) {
            continue;
        }
        $item = ['q' => [], 'a' => []];
        foreach (ld_langs_codes() as $code) {
            $item['q'][$code] = trim((string) ($row['q_' . $code] ?? ''));
            $item['a'][$code] = trim((string) ($row['a_' . $code] ?? ''));
        }
        if (ld_row_has_value($item, ['q', 'a'])) {
            $out[] = $item;
        }
    }
    return $out;
}

function ld_post_reviews(): array
{
    $raw = $_POST['reviews'] ?? [];
    if (!is_array($raw)) {
        return [];
    }
    $out = [];
    foreach ($raw as $row) {
        if (!is_array($row)) {
            continue;
        }
        $item = [
            'author' => trim((string) ($row['author'] ?? '')),
            'rating' => max(1, min(5, (int) ($row['rating'] ?? 5))),
            'date' => trim((string) ($row['date'] ?? '')),
            'text' => [],
        ];
        foreach (ld_langs_codes() as $code) {
            $item['text'][$code] = trim((string) ($row['text_' . $code] ?? ''));
        }
        if (ld_row_has_value($item, ['author', 'text'])) {
            $out[] = $item;
        }
    }
    return $out;
}

function ld_parse_content_post(): array
{
    $settings = ld_settings();
    $business = $settings['business'] ?? [];

    foreach (ld_langs_codes() as $code) {
        $business['name'][$code] = ld_post_trim('business_name_' . $code, $business['name'][$code] ?? '');
        $business['tagline'][$code] = ld_post_trim('business_tagline_' . $code, $business['tagline'][$code] ?? '');
        $business['city'][$code] = ld_post_trim('business_city_' . $code, $business['city'][$code] ?? '');
        $business['address'][$code] = ld_post_trim('business_address_' . $code, $business['address'][$code] ?? '');
        $business['hours'][$code] = ld_post_trim('business_hours_' . $code, $business['hours'][$code] ?? '');
    }
    $business['phone'] = ld_post_trim('business_phone', $business['phone'] ?? '');
    $business['email'] = ld_post_trim('business_email', $business['email'] ?? '');

    $hero = $settings['hero'] ?? [];
    $hero['cta'] = ld_post_i18n('hero_cta');
    $hero['cta2'] = ld_post_i18n('hero_cta2');
    $hero['visual_icon'] = ld_post_trim('hero_visual_icon', $hero['visual_icon'] ?? 'fa-star');
    $hero['visual_label'] = ld_post_i18n('hero_visual_label');
    $hero['visual_sub'] = ld_post_i18n('hero_visual_sub');

    $sections = $settings['sections'] ?? [];
    foreach (['services', 'team', 'faq', 'contact', 'reviews'] as $sectionKey) {
        if (!isset($sections[$sectionKey])) {
            $sections[$sectionKey] = [];
        }
        if ($sectionKey === 'faq') {
            $sections[$sectionKey]['title'] = ld_post_i18n('section_faq_title');
        } else {
            $sections[$sectionKey]['title'] = ld_post_i18n('section_' . $sectionKey . '_title');
            $sections[$sectionKey]['lead'] = ld_post_i18n('section_' . $sectionKey . '_lead');
        }
    }
    if (!isset($sections['map'])) {
        $sections['map'] = [];
    }
    $sections['map']['title'] = ld_post_i18n('section_map_title');

    $google = $settings['google'] ?? [];
    $google['maps_embed'] = ld_extract_iframe_src(ld_post_trim('google_maps_embed', $google['maps_embed'] ?? ''));
    $google['maps_link'] = ld_post_trim('google_maps_link', $google['maps_link'] ?? '');
    $google['reviews_url'] = ld_post_trim('google_reviews_url', $google['reviews_url'] ?? '');
    $google['rating'] = ld_post_trim('google_rating', $google['rating'] ?? '');
    $google['review_count'] = ld_post_trim('google_review_count', $google['review_count'] ?? '');

    $settings['business'] = $business;
    $settings['hero'] = $hero;
    $settings['sections'] = $sections;
    $settings['currency'] = ld_post_trim('currency', $settings['currency'] ?? '€');
    $settings['active_template'] = max(1, min(10, (int) ld_post_trim('active_template', (string) ($settings['active_template'] ?? 1))));
    $settings['stats'] = ld_post_stats();
    $settings['services'] = ld_post_services();
    $settings['team'] = ld_post_team();
    $settings['faq'] = ld_post_faq();
    $settings['google'] = $google;
    $settings['reviews'] = ld_post_reviews();

    return $settings;
}