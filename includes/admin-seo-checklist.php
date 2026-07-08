<?php
declare(strict_types=1);

require_once __DIR__ . '/admin-checklist.php';

/**
 * @return list<string>
 */
function ld_admin_seo_langs_missing_title(array $seo): array
{
    $missing = [];
    foreach (ld_lang_labels() as $code => $label) {
        if (trim((string) ($seo['title'][$code] ?? '')) === '') {
            $missing[] = (string) $label;
        }
    }

    return $missing;
}

/**
 * @return array{short: list<string>, long: list<string>}
 */
function ld_admin_seo_langs_title_length_issues(array $seo): array
{
    $short = [];
    $long = [];
    foreach (ld_lang_labels() as $code => $label) {
        $title = trim((string) ($seo['title'][$code] ?? ''));
        if ($title === '') {
            continue;
        }
        $len = mb_strlen($title);
        if ($len < 30) {
            $short[] = (string) $label . ' (' . $len . ')';
        } elseif ($len > 65) {
            $long[] = (string) $label . ' (' . $len . ')';
        }
    }

    return ['short' => $short, 'long' => $long];
}

/**
 * @return list<string>
 */
function ld_admin_seo_langs_missing_description(array $seo): array
{
    $missing = [];
    foreach (ld_lang_labels() as $code => $label) {
        if (trim((string) ($seo['description'][$code] ?? '')) === '') {
            $missing[] = (string) $label;
        }
    }

    return $missing;
}

/**
 * @return array{short: list<string>, long: list<string>}
 */
function ld_admin_seo_langs_desc_length_issues(array $seo): array
{
    $short = [];
    $long = [];
    foreach (ld_lang_labels() as $code => $label) {
        $desc = trim((string) ($seo['description'][$code] ?? ''));
        if ($desc === '') {
            continue;
        }
        $len = mb_strlen($desc);
        if ($len < 120) {
            $short[] = (string) $label . ' (' . $len . ')';
        } elseif ($len > 165) {
            $long[] = (string) $label . ' (' . $len . ')';
        }
    }

    return ['short' => $short, 'long' => $long];
}

/**
 * @return list<string>
 */
function ld_admin_seo_langs_missing_keywords(array $seo): array
{
    return ld_admin_checklist_empty_lang_labels($seo['keywords'] ?? []);
}

/**
 * @return list<string>
 */
function ld_admin_seo_langs_name_not_in_title(array $seo, array $business): array
{
    $missing = [];
    foreach (ld_lang_labels() as $code => $label) {
        $name = trim((string) ($business['name'][$code] ?? ''));
        $title = trim((string) ($seo['title'][$code] ?? ''));
        if ($name === '' || $title === '') {
            continue;
        }
        if (!str_contains(mb_strtolower($title), mb_strtolower($name))) {
            $missing[] = (string) $label;
        }
    }

    return $missing;
}

/**
 * @return list<string>
 */
function ld_admin_seo_langs_missing_city(array $seo, array $business): array
{
    $missing = [];
    foreach (ld_lang_labels() as $code => $label) {
        $city = trim((string) ($business['city'][$code] ?? ''));
        if ($city === '') {
            continue;
        }
        $title = trim((string) ($seo['title'][$code] ?? ''));
        $desc = trim((string) ($seo['description'][$code] ?? ''));
        $blob = mb_strtolower($title . ' ' . $desc);
        if (!str_contains($blob, mb_strtolower($city))) {
            $missing[] = (string) $label;
        }
    }

    return $missing;
}

/**
 * @return list<string>
 */
function ld_admin_seo_langs_incomplete_meta(array $seo): array
{
    $missing = [];
    foreach (ld_lang_labels() as $code => $label) {
        $title = trim((string) ($seo['title'][$code] ?? ''));
        $desc = trim((string) ($seo['description'][$code] ?? ''));
        $kw = trim((string) ($seo['keywords'][$code] ?? ''));
        if ($title === '' || $desc === '' || $kw === '') {
            $missing[] = (string) $label;
        }
    }

    return $missing;
}

/**
 * @return list<array{key: string, done: bool, label: string, missing: list<string>, link: string, anchor: string, target: string}>
 */
function ld_admin_seo_checklist_items(): array
{
    global $ta;
    $settings = ld_settings();
    $seo = $settings['seo'] ?? [];
    $business = $settings['business'] ?? [];
    $hero = $settings['hero'] ?? [];
    $services = $settings['services'] ?? [];
    $faq = $settings['faq'] ?? [];
    $google = $settings['google'] ?? [];
    $sections = $settings['sections'] ?? [];
    $reviews = $settings['reviews'] ?? [];
    $blocks = $settings['blocks'] ?? [];

    $t = static fn(string $k, string $fb = ''): string => (string) ($ta[$k] ?? $fb);
    $seoUrl = ld_admin_url('seo.php');
    $contentUrl = ld_admin_url('content.php');
    $newsUrl = ld_admin_url('news.php');

    $titleMissing = ld_admin_seo_langs_missing_title($seo);
    $titleAllOk = $titleMissing === [];

    $titleLen = ld_admin_seo_langs_title_length_issues($seo);
    $titleLenOk = $titleLen['short'] === [] && $titleLen['long'] === [];
    $titleLenMissing = [];
    if ($titleLen['short'] !== []) {
        $titleLenMissing[] = str_replace('{langs}', ld_admin_checklist_format_list($titleLen['short']), $t('seo_check_missing_title_short'));
    }
    if ($titleLen['long'] !== []) {
        $titleLenMissing[] = str_replace('{langs}', ld_admin_checklist_format_list($titleLen['long']), $t('seo_check_missing_title_long'));
    }

    $descMissing = ld_admin_seo_langs_missing_description($seo);
    $descAllOk = $descMissing === [];

    $descLen = ld_admin_seo_langs_desc_length_issues($seo);
    $descLenOk = $descLen['short'] === [] && $descLen['long'] === [];
    $descLenMissing = [];
    if ($descLen['short'] !== []) {
        $descLenMissing[] = str_replace('{langs}', ld_admin_checklist_format_list($descLen['short']), $t('seo_check_missing_desc_short'));
    }
    if ($descLen['long'] !== []) {
        $descLenMissing[] = str_replace('{langs}', ld_admin_checklist_format_list($descLen['long']), $t('seo_check_missing_desc_long'));
    }

    $kwMissing = ld_admin_seo_langs_missing_keywords($seo);
    $kwOk = $kwMissing === [];

    $nameTitleMissing = ld_admin_seo_langs_name_not_in_title($seo, $business);
    $hasAnyName = false;
    foreach (ld_lang_labels() as $code => $_) {
        if (trim((string) ($business['name'][$code] ?? '')) !== '') {
            $hasAnyName = true;
            break;
        }
    }
    $nameTitleOk = !$hasAnyName || $nameTitleMissing === [];

    $cityMissingLangs = ld_admin_seo_langs_missing_city($seo, $business);
    $hasAnyCity = false;
    foreach (ld_lang_labels() as $code => $_) {
        if (trim((string) ($business['city'][$code] ?? '')) !== '') {
            $hasAnyCity = true;
            break;
        }
    }
    $cityOk = !$hasAnyCity || $cityMissingLangs === [];

    $ogImage = trim((string) ($seo['og_image'] ?? ''));
    $heroImage = trim((string) ($blocks['hero_image'] ?? ''));
    $shareImageOk = $ogImage !== '' || $heroImage !== '';
    $shareImageMissing = [];
    if ($ogImage === '') {
        $shareImageMissing[] = $t('seo_check_missing_og');
    }
    if ($heroImage === '') {
        $shareImageMissing[] = $t('seo_check_missing_hero_image');
    }

    $phone = trim((string) ($business['phone'] ?? ''));
    $phoneOk = $phone !== '';

    $email = trim((string) ($business['email'] ?? ''));
    $emailOk = $email !== '';

    $hasEmbed = trim((string) ($google['maps_embed'] ?? '')) !== '';
    $addrEmptyLangs = ld_admin_checklist_empty_lang_labels($business['address'] ?? []);
    $langTotal = count(ld_lang_labels());
    $hasAddress = $langTotal > 0 && count($addrEmptyLangs) < $langTotal;
    $locationOk = $hasEmbed || $hasAddress;
    $locationMissing = [];
    if (!$hasEmbed) {
        $locationMissing[] = $t('seo_check_missing_maps_embed');
    }
    if (!$hasAddress) {
        $locationMissing[] = $t('seo_check_missing_address');
    }

    $mapsLink = trim((string) ($google['maps_link'] ?? ''));
    $mapsLinkOk = $mapsLink !== '';

    $reviewsUrl = trim((string) ($google['reviews_url'] ?? ''));
    $reviewsUrlOk = $reviewsUrl !== '';

    $rating = trim((string) ($google['rating'] ?? ''));
    $reviewCount = trim((string) ($google['review_count'] ?? ''));
    $ratingOk = $rating !== '' && $reviewCount !== '';
    $ratingMissing = [];
    if ($rating === '') {
        $ratingMissing[] = $t('seo_check_missing_rating_value');
    }
    if ($reviewCount === '') {
        $ratingMissing[] = $t('seo_check_missing_review_count');
    }

    $faqCount = count($faq);
    $faqOk = $faqCount >= 3;
    $faqMissing = $faqOk ? [] : [
        str_replace(['{have}', '{need}'], [(string) $faqCount, (string) max(0, 3 - $faqCount)], $t('seo_check_missing_faq')),
    ];

    $serviceOk = false;
    $serviceMissing = [$t('seo_check_missing_services')];
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
            $serviceMissing = [$t('seo_check_missing_service_price')];
        }
    }

    $sectionMissingLangs = [];
    foreach (['services', 'faq'] as $secKey) {
        $titles = $sections[$secKey]['title'] ?? [];
        foreach (ld_admin_checklist_empty_lang_labels(is_array($titles) ? $titles : []) as $langLabel) {
            $sectionMissingLangs[$langLabel] = true;
        }
    }
    $sectionMissing = array_keys($sectionMissingLangs);
    $sectionOk = $sectionMissing === [];

    $taglineMissing = ld_admin_checklist_empty_lang_labels($business['tagline'] ?? []);
    $taglineOk = $taglineMissing === [];

    $heroMissing = ld_admin_checklist_empty_lang_labels($hero['cta'] ?? []);
    $heroOk = $heroMissing === [];

    $testimonialOk = false;
    $testimonialMissing = [$t('seo_check_missing_testimonials')];
    foreach ($reviews as $row) {
        $author = trim((string) ($row['author'] ?? ''));
        $texts = $row['text'] ?? [];
        $hasText = false;
        if (is_array($texts)) {
            foreach ($texts as $txt) {
                if (trim((string) $txt) !== '') {
                    $hasText = true;
                    break;
                }
            }
        }
        if ($author !== '' && $hasText) {
            $testimonialOk = true;
            $testimonialMissing = [];
            break;
        }
    }

    $newsPublished = count(array_filter(
        function_exists('ld_load_news') ? ld_load_news() : [],
        static fn(array $n): bool => ($n['status'] ?? '') === 'published'
    ));
    $newsOk = $newsPublished >= 1;
    $newsMissing = $newsOk ? [] : [$t('seo_check_missing_news')];

    $hreflangMissing = ld_admin_seo_langs_incomplete_meta($seo);
    $hreflangOk = $hreflangMissing === [];

    return [
        [
            'key' => 'title_all',
            'done' => $titleAllOk,
            'label' => $t('seo_check_item_title_all'),
            'missing' => $titleAllOk ? [] : [str_replace('{langs}', ld_admin_checklist_format_list($titleMissing), $t('seo_check_missing_title_langs'))],
            'link' => $seoUrl,
            'anchor' => '',
            'target' => 'seo',
        ],
        [
            'key' => 'title_len',
            'done' => $titleLenOk,
            'label' => $t('seo_check_item_title_len'),
            'missing' => $titleLenMissing,
            'link' => $seoUrl,
            'anchor' => '',
            'target' => 'seo',
        ],
        [
            'key' => 'desc_all',
            'done' => $descAllOk,
            'label' => $t('seo_check_item_desc_all'),
            'missing' => $descAllOk ? [] : [str_replace('{langs}', ld_admin_checklist_format_list($descMissing), $t('seo_check_missing_desc_langs'))],
            'link' => $seoUrl,
            'anchor' => '',
            'target' => 'seo',
        ],
        [
            'key' => 'desc_len',
            'done' => $descLenOk,
            'label' => $t('seo_check_item_desc_len'),
            'missing' => $descLenMissing,
            'link' => $seoUrl,
            'anchor' => '',
            'target' => 'seo',
        ],
        [
            'key' => 'keywords',
            'done' => $kwOk,
            'label' => $t('seo_check_item_keywords'),
            'missing' => $kwOk ? [] : [str_replace('{langs}', ld_admin_checklist_format_list($kwMissing), $t('seo_check_missing_keywords_langs'))],
            'link' => $seoUrl,
            'anchor' => '',
            'target' => 'seo',
        ],
        [
            'key' => 'name_title',
            'done' => $nameTitleOk,
            'label' => $t('seo_check_item_name_in_title'),
            'missing' => $nameTitleOk ? [] : [str_replace('{langs}', ld_admin_checklist_format_list($nameTitleMissing), $t('seo_check_missing_name_title_langs'))],
            'link' => $seoUrl,
            'anchor' => '',
            'target' => 'seo',
        ],
        [
            'key' => 'city_local',
            'done' => $cityOk,
            'label' => $t('seo_check_item_city_local'),
            'missing' => $cityOk ? [] : [str_replace('{langs}', ld_admin_checklist_format_list($cityMissingLangs), $t('seo_check_missing_city_langs'))],
            'link' => $seoUrl,
            'anchor' => '',
            'target' => 'seo',
        ],
        [
            'key' => 'share_image',
            'done' => $shareImageOk,
            'label' => $t('seo_check_item_share_image'),
            'missing' => $shareImageOk ? [] : $shareImageMissing,
            'link' => $ogImage === '' ? $seoUrl : ld_admin_url('blocks.php'),
            'anchor' => $ogImage === '' ? '' : '#adm-hero-image',
            'target' => $ogImage === '' ? 'seo' : 'blocks',
        ],
        [
            'key' => 'hreflang',
            'done' => $hreflangOk,
            'label' => $t('seo_check_item_hreflang'),
            'missing' => $hreflangOk ? [] : [str_replace('{langs}', ld_admin_checklist_format_list($hreflangMissing), $t('seo_check_missing_hreflang'))],
            'link' => $seoUrl,
            'anchor' => '',
            'target' => 'seo',
        ],
        [
            'key' => 'phone',
            'done' => $phoneOk,
            'label' => $t('seo_check_item_phone'),
            'missing' => $phoneOk ? [] : [$t('seo_check_missing_phone')],
            'link' => $contentUrl,
            'anchor' => '#adm-business',
            'target' => 'content',
        ],
        [
            'key' => 'email',
            'done' => $emailOk,
            'label' => $t('seo_check_item_email'),
            'missing' => $emailOk ? [] : [$t('seo_check_missing_email')],
            'link' => $contentUrl,
            'anchor' => '#adm-business',
            'target' => 'content',
        ],
        [
            'key' => 'location',
            'done' => $locationOk,
            'label' => $t('seo_check_item_location'),
            'missing' => $locationOk ? [] : $locationMissing,
            'link' => $contentUrl,
            'anchor' => '#adm-google',
            'target' => 'content',
        ],
        [
            'key' => 'maps_link',
            'done' => $mapsLinkOk,
            'label' => $t('seo_check_item_maps_link'),
            'missing' => $mapsLinkOk ? [] : [$t('seo_check_missing_maps_link')],
            'link' => $contentUrl,
            'anchor' => '#adm-google',
            'target' => 'content',
        ],
        [
            'key' => 'reviews_url',
            'done' => $reviewsUrlOk,
            'label' => $t('seo_check_item_reviews_url'),
            'missing' => $reviewsUrlOk ? [] : [$t('seo_check_missing_reviews_url')],
            'link' => $contentUrl,
            'anchor' => '#adm-google',
            'target' => 'content',
        ],
        [
            'key' => 'rating',
            'done' => $ratingOk,
            'label' => $t('seo_check_item_rating'),
            'missing' => $ratingOk ? [] : $ratingMissing,
            'link' => $contentUrl,
            'anchor' => '#adm-google',
            'target' => 'content',
        ],
        [
            'key' => 'tagline',
            'done' => $taglineOk,
            'label' => $t('seo_check_item_tagline'),
            'missing' => $taglineOk ? [] : [str_replace('{langs}', ld_admin_checklist_format_list($taglineMissing), $t('seo_check_missing_tagline_langs'))],
            'link' => $contentUrl,
            'anchor' => '#adm-business',
            'target' => 'content',
        ],
        [
            'key' => 'hero_cta',
            'done' => $heroOk,
            'label' => $t('seo_check_item_hero_cta'),
            'missing' => $heroOk ? [] : [str_replace('{langs}', ld_admin_checklist_format_list($heroMissing), $t('seo_check_missing_hero_cta'))],
            'link' => $contentUrl,
            'anchor' => '#adm-hero',
            'target' => 'content',
        ],
        [
            'key' => 'sections',
            'done' => $sectionOk,
            'label' => $t('seo_check_item_sections'),
            'missing' => $sectionOk ? [] : [str_replace('{langs}', ld_admin_checklist_format_list($sectionMissing), $t('seo_check_missing_sections_langs'))],
            'link' => $contentUrl,
            'anchor' => '#adm-sections',
            'target' => 'content',
        ],
        [
            'key' => 'services',
            'done' => $serviceOk,
            'label' => $t('seo_check_item_services'),
            'missing' => $serviceMissing,
            'link' => $contentUrl,
            'anchor' => '#adm-services',
            'target' => 'content',
        ],
        [
            'key' => 'faq',
            'done' => $faqOk,
            'label' => $t('seo_check_item_faq'),
            'missing' => $faqMissing,
            'link' => $contentUrl,
            'anchor' => '#adm-faq',
            'target' => 'content',
        ],
        [
            'key' => 'testimonials',
            'done' => $testimonialOk,
            'label' => $t('seo_check_item_testimonials'),
            'missing' => $testimonialMissing,
            'link' => $contentUrl,
            'anchor' => '#adm-google',
            'target' => 'content',
        ],
        [
            'key' => 'news',
            'done' => $newsOk,
            'label' => $t('seo_check_item_news'),
            'missing' => $newsMissing,
            'link' => $newsUrl,
            'anchor' => '',
            'target' => 'news',
        ],
    ];
}