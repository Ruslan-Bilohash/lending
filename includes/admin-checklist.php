<?php
declare(strict_types=1);

/**
 * @return list<string> Human-readable language labels that are empty.
 */
function ld_admin_checklist_empty_lang_labels(array $values): array
{
    $missing = [];
    foreach (ld_lang_labels() as $code => $label) {
        if (trim((string) ($values[$code] ?? '')) === '') {
            $missing[] = (string) $label;
        }
    }

    return $missing;
}

/**
 * @param array<int, string> $parts
 */
function ld_admin_checklist_format_list(array $parts): string
{
    return implode(', ', $parts);
}

/**
 * @return list<array{key: string, done: bool, label: string, hint: string, missing: list<string>, link: string, anchor: string}>
 */
function ld_admin_content_checklist_items(): array
{
    global $ta;
    $settings = ld_settings();
    $business = $settings['business'] ?? [];
    $hero = $settings['hero'] ?? [];
    $services = $settings['services'] ?? [];
    $team = $settings['team'] ?? [];
    $faq = $settings['faq'] ?? [];
    $seo = $settings['seo'] ?? [];
    $google = $settings['google'] ?? [];

    $t = static fn(string $k, string $fb = ''): string => (string) ($ta[$k] ?? $fb);

    $nameMissing = ld_admin_checklist_empty_lang_labels($business['name'] ?? []);
    $nameOk = $nameMissing === [];

    $phone = trim((string) ($business['phone'] ?? ''));
    $phoneOk = $phone !== '';
    $phoneMissing = $phoneOk ? [] : [$t('checklist_missing_phone')];

    $serviceOk = false;
    $serviceMissing = [$t('checklist_missing_services')];
    foreach ($services as $svc) {
        $names = $svc['name'] ?? [];
        $price = trim((string) ($svc['price'] ?? ''));
        if (!is_array($names)) {
            continue;
        }
        $hasName = false;
        foreach ($names as $n) {
            if (trim((string) $n) !== '') {
                $hasName = true;
                break;
            }
        }
        if ($hasName && $price !== '') {
            $serviceOk = true;
            $serviceMissing = [];
            break;
        }
        if ($hasName && $price === '') {
            $serviceMissing = [$t('checklist_missing_service_price')];
        }
    }

    $heroMissingLangs = ld_admin_checklist_empty_lang_labels($hero['cta'] ?? []);
    $heroOk = $heroMissingLangs === [];
    $heroMissing = $heroOk ? [] : [
        str_replace('{langs}', ld_admin_checklist_format_list($heroMissingLangs), $t('checklist_missing_hero')),
    ];

    $teamOk = false;
    $teamMissing = [$t('checklist_missing_team')];
    foreach ($team as $member) {
        $name = $member['name'] ?? [];
        if (!is_array($name)) {
            continue;
        }
        foreach ($name as $n) {
            if (trim((string) $n) !== '') {
                $teamOk = true;
                $teamMissing = [];
                break 2;
            }
        }
    }

    $faqCount = count($faq);
    $faqOk = $faqCount >= 3;
    $faqMissing = $faqOk ? [] : [
        str_replace(['{have}', '{need}'], [(string) $faqCount, (string) max(0, 3 - $faqCount)], $t('checklist_missing_faq')),
    ];

    $seoMissingLangs = [];
    foreach (ld_lang_labels() as $code => $label) {
        $title = trim((string) ($seo['title'][$code] ?? ''));
        $desc = trim((string) ($seo['description'][$code] ?? ''));
        if ($title === '' || $desc === '') {
            $seoMissingLangs[] = (string) $label;
        }
    }
    $seoOk = $seoMissingLangs === [];
    $seoMissing = $seoOk ? [] : [
        str_replace('{langs}', ld_admin_checklist_format_list($seoMissingLangs), $t('checklist_missing_seo_langs')),
    ];

    $hasEmbed = trim((string) ($google['maps_embed'] ?? '')) !== '';
    $addrEmptyLangs = ld_admin_checklist_empty_lang_labels($business['address'] ?? []);
    $langTotal = count(ld_lang_labels());
    $hasAddress = $langTotal > 0 && count($addrEmptyLangs) < $langTotal;
    $mapsOk = $hasEmbed || $hasAddress;
    $mapsMissing = [];
    if (!$mapsOk) {
        if (!$hasEmbed) {
            $mapsMissing[] = $t('checklist_missing_maps_embed');
        }
        if (!$hasAddress) {
            $mapsMissing[] = $t('checklist_missing_maps_address');
        }
    }

    $businessMissing = $nameOk ? [] : [
        str_replace('{langs}', ld_admin_checklist_format_list($nameMissing), $t('checklist_missing_name_langs')),
    ];

    return [
        [
            'key' => 'business',
            'done' => $nameOk,
            'label' => $t('checklist_item_business'),
            'hint' => $t('checklist_hint_business'),
            'missing' => $businessMissing,
            'link' => ld_admin_url('content.php'),
            'anchor' => '#adm-business',
        ],
        [
            'key' => 'phone',
            'done' => $phoneOk,
            'label' => $t('checklist_item_phone'),
            'hint' => $t('checklist_hint_phone'),
            'missing' => $phoneMissing,
            'link' => ld_admin_url('content.php'),
            'anchor' => '#adm-business',
        ],
        [
            'key' => 'services',
            'done' => $serviceOk,
            'label' => $t('checklist_item_services'),
            'hint' => $t('checklist_hint_services'),
            'missing' => $serviceMissing,
            'link' => ld_admin_url('content.php'),
            'anchor' => '#adm-services',
        ],
        [
            'key' => 'hero',
            'done' => $heroOk,
            'label' => $t('checklist_item_hero'),
            'hint' => $t('checklist_hint_hero'),
            'missing' => $heroMissing,
            'link' => ld_admin_url('content.php'),
            'anchor' => '#adm-hero',
        ],
        [
            'key' => 'team',
            'done' => $teamOk,
            'label' => $t('checklist_item_team'),
            'hint' => $t('checklist_hint_team'),
            'missing' => $teamMissing,
            'link' => ld_admin_url('content.php'),
            'anchor' => '#adm-team',
        ],
        [
            'key' => 'faq',
            'done' => $faqOk,
            'label' => $t('checklist_item_faq'),
            'hint' => $t('checklist_hint_faq'),
            'missing' => $faqMissing,
            'link' => ld_admin_url('content.php'),
            'anchor' => '#adm-faq',
        ],
        [
            'key' => 'seo',
            'done' => $seoOk,
            'label' => $t('checklist_item_seo'),
            'hint' => $t('checklist_hint_seo'),
            'missing' => $seoMissing,
            'link' => ld_admin_url('seo.php'),
            'anchor' => '',
        ],
        [
            'key' => 'maps',
            'done' => $mapsOk,
            'label' => $t('checklist_item_maps'),
            'hint' => $t('checklist_hint_maps'),
            'missing' => $mapsMissing,
            'link' => ld_admin_url('content.php'),
            'anchor' => '#adm-google',
        ],
    ];
}

/**
 * @return list<string>
 */
function ld_admin_seo_tip_lines(): array
{
    global $ta;
    $keys = [
        'seo_tip_title_len',
        'seo_tip_desc_len',
        'seo_tip_keywords',
        'seo_tip_lang',
        'seo_tip_og',
        'seo_tip_ai',
        'seo_tip_local',
        'seo_tip_mobile',
    ];
    $out = [];
    foreach ($keys as $k) {
        if (!empty($ta[$k])) {
            $out[] = (string) $ta[$k];
        }
    }

    return $out;
}

function ld_admin_checklist_progress(array $items): array
{
    $total = count($items);
    $done = count(array_filter($items, static fn(array $i): bool => !empty($i['done'])));

    return ['done' => $done, 'total' => $total, 'pct' => $total > 0 ? (int) round($done / $total * 100) : 0];
}

/**
 * @return list<array{key: string, done: bool, label: string, hint: string, missing: list<string>, link: string, anchor: string}>
 */
function ld_admin_checklist_pending(array $items): array
{
    return array_values(array_filter($items, static fn(array $i): bool => empty($i['done'])));
}