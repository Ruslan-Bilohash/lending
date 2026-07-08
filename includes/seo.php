<?php
declare(strict_types=1);

function ld_seo(): array
{
    return ld_settings()['seo'] ?? ld_default_settings()['seo'];
}

function ld_seo_page_vars(string $lang, bool $is_landing, int $templateId = 1): array
{
    $seo = ld_seo();
    $business = ld_business();
    $names = ld_template_names($lang);
    $bizName = ld_pick($business['name'], $lang);
    $tagline = ld_pick($business['tagline'], $lang);

    global $t;
    $title = ld_pick($seo['title'] ?? [], $lang);
    if ($title === '') {
        $title = $is_landing
            ? (($names[$templateId] ?? 'Landing') . ' — ' . $bizName)
            : ($t['meta']['title'] ?? 'Lending CMS');
    }

    $description = ld_pick($seo['description'] ?? [], $lang);
    if ($description === '') {
        $description = $tagline !== '' ? $tagline : ($t['meta']['description'] ?? '');
    }

    $keywords = ld_pick($seo['keywords'] ?? [], $lang);
    $ogImage = trim((string) ($seo['og_image'] ?? ''));
    if ($ogImage === '') {
        $ogImage = trim((string) (ld_settings()['blocks']['hero_image'] ?? ''));
    }
    if ($ogImage === '') {
        $ogImage = 'https://bilohash.com/lending/assets/img/og-default.jpg';
    }

    global $site_url;
    $path = $is_landing ? ld_url('template.php', ['t' => $templateId]) : ld_url('index.php');
    $canonical = rtrim($site_url, '/') . '/' . ltrim(str_replace($GLOBALS['base_path'] ?? '', '', parse_url($path, PHP_URL_PATH) ?: ''), '/');
    if ($lang !== 'no') {
        $canonical .= (str_contains($canonical, '?') ? '&' : '?') . 'lang=' . $lang;
    }

    return [
        'title' => $title,
        'description' => $description,
        'keywords' => $keywords,
        'og_image' => $ogImage,
        'canonical' => $canonical,
        'site_name' => $bizName !== '' ? $bizName : (LD_SITE_NAME ?? 'Lending CMS'),
    ];
}

function ld_render_seo_head(array $vars, string $lang): void
{
    $ogLocale = match ($lang) {
        'no' => 'nb_NO',
        'sv' => 'sv_SE',
        'pl' => 'pl_PL',
        'uk' => 'uk_UA',
        'ru' => 'ru_RU',
        'lt' => 'lt_LT',
        default => 'en_US',
    };

    if (($vars['keywords'] ?? '') !== ''): ?>
    <meta name="keywords" content="<?= ld_h($vars['keywords']) ?>">
    <?php endif; ?>
    <link rel="canonical" href="<?= ld_h($vars['canonical']) ?>">
    <?php
    $ogPath = dirname(__DIR__, 2) . '/includes/bh-open-graph.php';
    if (is_file($ogPath)) {
        require_once $ogPath;
        bh_render_open_graph([
            'title' => $vars['title'],
            'description' => $vars['description'],
            'url' => $vars['canonical'],
            'image' => $vars['og_image'],
            'site_name' => $vars['site_name'],
            'type' => 'website',
            'locale' => $ogLocale,
            'locale_alternates' => array_values(array_filter([
                $lang !== 'en' ? 'en_US' : null,
                $lang !== 'lt' ? 'lt_LT' : null,
                $lang !== 'uk' ? 'uk_UA' : null,
                $lang !== 'ru' ? 'ru_RU' : null,
            ])),
            'image_alt' => $vars['title'],
        ]);
    }
}

function ld_schema_opening_hours(): array
{
    return ['Mo-Fr 09:00-19:00', 'Sa 10:00-15:00'];
}

function ld_render_schema(string $lang): void
{
    $business = ld_business();
    $google = ld_google();
    $services = ld_services($lang);
    $faq = ld_faq($lang);
    $name = ld_pick($business['name'], $lang);
    $address = ld_pick($business['address'], $lang);
    $city = ld_pick($business['city'], $lang);
    $tagline = ld_pick($business['tagline'], $lang);
    $seoDesc = ld_pick(ld_seo()['description'] ?? [], $lang) ?: $tagline;
    global $site_url;

    $pageUrl = rtrim((string) $site_url, '/') . '/template.php?t=' . ld_active_template();
    if ($lang !== 'no') {
        $pageUrl .= '&lang=' . $lang;
    }
    $ogImage = trim((string) (ld_seo()['og_image'] ?? ''));
    if ($ogImage === '') {
        $ogImage = trim((string) (ld_settings()['blocks']['hero_image'] ?? ''));
    }

    $bizType = ld_is_driving_preset() ? 'DrivingSchool' : 'LocalBusiness';
    $localId = $pageUrl . '#business';

    $businessNode = [
        '@type' => $bizType,
        '@id' => $localId,
        'name' => $name,
        'description' => $seoDesc,
        'url' => $pageUrl,
        'telephone' => $business['phone'] ?? '',
        'email' => $business['email'] ?? '',
        'image' => $ogImage !== '' ? [$ogImage] : [],
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => $address,
            'addressLocality' => 'Vilnius',
            'addressCountry' => 'LT',
        ],
        'geo' => [
            '@type' => 'GeoCoordinates',
            'latitude' => 54.6997,
            'longitude' => 25.2797,
        ],
        'areaServed' => [
            '@type' => 'City',
            'name' => 'Vilnius',
        ],
        'openingHoursSpecification' => [
            ['@type' => 'OpeningHoursSpecification', 'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'], 'opens' => '09:00', 'closes' => '19:00'],
            ['@type' => 'OpeningHoursSpecification', 'dayOfWeek' => 'Saturday', 'opens' => '10:00', 'closes' => '15:00'],
        ],
        'priceRange' => '€€',
        'sameAs' => array_values(array_filter([
            trim((string) ($google['reviews_url'] ?? '')),
            trim((string) ($google['maps_link'] ?? '')),
        ])),
    ];

    if (trim((string) ($google['rating'] ?? '')) !== '') {
        $businessNode['aggregateRating'] = [
            '@type' => 'AggregateRating',
            'ratingValue' => (string) $google['rating'],
            'reviewCount' => (string) ($google['review_count'] ?? '1'),
            'bestRating' => '5',
        ];
    }

    if ($services) {
        $businessNode['hasOfferCatalog'] = [
            '@type' => 'OfferCatalog',
            'name' => ld_section_text('services', 'title', $lang, 'Services'),
            'itemListElement' => array_map(static function (array $s) {
                $offer = [
                    '@type' => 'Offer',
                    'itemOffered' => [
                        '@type' => 'Service',
                        'name' => $s['name'] ?? '',
                        'description' => $s['desc'] ?? '',
                    ],
                ];
                if (($s['price'] ?? '') !== '') {
                    $offer['price'] = preg_replace('/\s+/', '', (string) $s['price']);
                    $offer['priceCurrency'] = ld_currency() === '€' ? 'EUR' : ld_currency();
                }
                return $offer;
            }, array_slice($services, 0, 8)),
        ];
    }

    $graph = [
        [
            '@type' => 'WebSite',
            '@id' => rtrim((string) $site_url, '/') . '/#website',
            'url' => rtrim((string) $site_url, '/') . '/',
            'name' => $name,
            'description' => $seoDesc,
            'inLanguage' => [$lang === 'uk' ? 'uk' : $lang],
            'publisher' => ['@id' => $localId],
        ],
        $businessNode,
        [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'BILOHASH', 'item' => 'https://bilohash.com/lending/'],
                ['@type' => 'ListItem', 'position' => 2, 'name' => $name, 'item' => $pageUrl],
            ],
        ],
    ];

    if ($faq) {
        $graph[] = [
            '@type' => 'FAQPage',
            'mainEntity' => array_map(static fn(array $item) => [
                '@type' => 'Question',
                'name' => $item['q'] ?? '',
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $item['a'] ?? ''],
            ], array_slice($faq, 0, 12)),
        ];
    }

    echo '<script type="application/ld+json">' . json_encode(
        ['@context' => 'https://schema.org', '@graph' => $graph],
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    ) . '</script>' . "\n";
}