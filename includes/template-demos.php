<?php
declare(strict_types=1);

/** @return array<int, string> */
function ld_template_preset_map(): array
{
    return [
        1  => 'driving_school',
        2  => 'restaurant',
        3  => 'medical_clinic',
        4  => 'restaurant',
        5  => 'dentist',
        6  => 'auto_service',
        7  => 'beauty_salon',
        8  => 'driving_school',
        9  => 'taxi',
        10 => 'law_office',
    ];
}

function ld_template_preview_active(): bool
{
    return isset($GLOBALS['ld_template_preview_id']) && (int) $GLOBALS['ld_template_preview_id'] >= 1;
}

function ld_set_template_preview(int $templateId): void
{
    $GLOBALS['ld_template_preview_id'] = max(1, min(10, $templateId));
}

function ld_effective_settings(): array
{
    static $cache = [];
    $previewId = (int) ($GLOBALS['ld_template_preview_id'] ?? 0);
    if ($previewId >= 1 && $previewId <= 10) {
        global $lang;
        $cacheKey = $previewId . ':' . $lang;
        if (!isset($cache[$cacheKey])) {
            $cache[$cacheKey] = ld_build_template_preview_settings($previewId, $lang);
        }
        return $cache[$cacheKey];
    }
    return ld_settings();
}

/** Demo business name per preset × country (all 7 langs). */
function ld_demo_business_names(string $presetId): array
{
    $c = ld_lang_countries();
    return match ($presetId) {
        'taxi' => [
            'no' => 'Oslo Taxi', 'sv' => 'Stockholm Taxi', 'pl' => 'Taxi Warszawa',
            'en' => 'London Taxi', 'lt' => 'Vilniaus Taksi', 'uk' => 'Таксі Київ', 'ru' => 'Такси Москва',
        ],
        'driving_school' => [
            'no' => 'Oslo Trafikkskole', 'sv' => 'Stockholm Trafikskola', 'pl' => 'Szkoła Jazdy Warszawa',
            'en' => 'London Driving School', 'lt' => 'Vilniaus Vairavimo Mokykla', 'uk' => 'Автошкола Київ', 'ru' => 'Автошкола Москва',
        ],
        'restaurant' => [
            'no' => 'Oslo Smak', 'sv' => 'Stockholm Smak', 'pl' => 'Smak Warszawa',
            'en' => 'London Taste', 'lt' => 'Vilniaus Skonis', 'uk' => 'Смак Київ', 'ru' => 'Вкус Москва',
        ],
        'dentist' => [
            'no' => 'Oslo SmileLine', 'sv' => 'Stockholm SmileLine', 'pl' => 'SmileLine Warszawa',
            'en' => 'London SmileLine', 'lt' => 'SmileLine Vilnius', 'uk' => 'SmileLine Київ', 'ru' => 'SmileLine Москва',
        ],
        'auto_service' => [
            'no' => 'Oslo CarFix', 'sv' => 'Stockholm CarFix', 'pl' => 'CarFix Warszawa',
            'en' => 'London CarFix', 'lt' => 'CarFix Vilnius', 'uk' => 'CarFix Київ', 'ru' => 'CarFix Москва',
        ],
        'beauty_salon' => [
            'no' => 'Oslo Glow Studio', 'sv' => 'Stockholm Glow Studio', 'pl' => 'Glow Studio Warszawa',
            'en' => 'London Glow Studio', 'lt' => 'Glow Studio Vilnius', 'uk' => 'Glow Studio Київ', 'ru' => 'Glow Studio Москва',
        ],
        'medical_clinic' => [
            'no' => 'Oslo MedKlinikk', 'sv' => 'Stockholm MedKlinik', 'pl' => 'MedKlinika Warszawa',
            'en' => 'London MedClinic', 'lt' => 'MedKlinika Vilnius', 'uk' => 'МедКлініка Київ', 'ru' => 'МедКлиника Москва',
        ],
        'law_office' => [
            'no' => 'Oslo Advokatene', 'sv' => 'Stockholm Advokatbyrå', 'pl' => 'Kancelaria Warszawa',
            'en' => 'London Legal Partners', 'lt' => 'Teisininkai Vilnius', 'uk' => 'Юридична Київ', 'ru' => 'ЮрКонсалт Москва',
        ],
        default => [
            'no' => 'Oslo Business', 'sv' => 'Stockholm Business', 'pl' => 'Biznes Warszawa',
            'en' => 'London Business', 'lt' => 'Verslas Vilnius', 'uk' => 'Бізнес Київ', 'ru' => 'Бизнес Москва',
        ],
    };
}

function ld_demo_business_taglines(string $presetId): array
{
    return match ($presetId) {
        'taxi' => [
            'no' => 'Bestill taxi i Oslo — fast pris, 24/7, flyplass og by',
            'sv' => 'Boka taxi i Stockholm — fast pris, dygnet runt, flygplats och stad',
            'pl' => 'Taxi w Warszawie — zamów online, stała cena, lotnisko i miasto 24/7',
            'en' => 'London taxi — book online, fixed fares, airport & city 24/7',
            'lt' => 'Taksi Vilniuje — užsakymas online, fiksuotas tarifas, oro uostas 24/7',
            'uk' => 'Таксі в Києві — замовлення online, фіксований тариф, аеропорт 24/7',
            'ru' => 'Такси в Москве — заказ online, фиксированный тариф, аэропорт 24/7',
        ],
        'driving_school' => ld_default_settings()['business']['tagline'],
        'restaurant' => [
            'no' => 'Nordisk mat i Oslo — reservasjon, takeaway og selskap',
            'sv' => 'Nordisk mat i Stockholm — bokning, takeaway och event',
            'pl' => 'Kuchnia w Warszawie — rezerwacja, na wynos i bankiety',
            'en' => 'Fine dining in London — reservations, takeaway and events',
            'lt' => 'Virtuvė Vilniuje — rezervacija, išsinešimui ir banketai',
            'uk' => 'Ресторан у Києві — бронювання, доставка та банкети',
            'ru' => 'Ресторан в Москве — бронирование, доставка и банкеты',
        ],
        'dentist' => [
            'no' => 'Tannlege i Oslo — implantater, bleking, barne-tannlege',
            'sv' => 'Tandläkare i Stockholm — implantat, blekning, barntandvård',
            'pl' => 'Stomatolog w Warszawie — implanty, wybielanie, dzieci',
            'en' => 'Dentistry in London — implants, whitening, kids care',
            'lt' => 'Stomatologija Vilniuje — implantai, balinimas, vaikams',
            'uk' => 'Стоматологія в Києві — імпланти, відбілювання, дітям',
            'ru' => 'Стоматология в Москве — импланты, отбеливание, детям',
        ],
        'auto_service' => [
            'no' => 'Bilservice i Oslo — diagnostikk, oljeskift, bremser, dekk',
            'sv' => 'Bilservice i Stockholm — diagnostik, oljebyte, bromsar, däck',
            'pl' => 'Warsztat w Warszawie — diagnostyka, olej, hamulce, opony',
            'en' => 'Car service in London — diagnostics, oil, brakes, tyres',
            'lt' => 'Autoservisas Vilniuje — diagnostika, tepalai, stabdžiai, padangos',
            'uk' => 'Автосервіс у Києві — діагностика, масло, гальма, шини',
            'ru' => 'Автосервис в Москве — диагностика, масло, тормоза, шины',
        ],
        'beauty_salon' => [
            'no' => 'Frisør og skjønnhet i Oslo — klipp, negler, hudpleie',
            'sv' => 'Skönhet i Stockholm — klippning, naglar, hudvård',
            'pl' => 'Salon urody w Warszawie — fryzjer, paznokcie, kosmetyka',
            'en' => 'Beauty in London — hair, nails, skincare and massage',
            'lt' => 'Grožis Vilniuje — kirpimas, manikiūras, kosmetologija',
            'uk' => 'Салон краси в Києві — стрижка, манікюр, косметологія',
            'ru' => 'Салон красоты в Москве — стрижка, маникюр, косметология',
        ],
        'medical_clinic' => [
            'no' => 'Legeklinikk i Oslo — konsultasjon, analyse, ultralyd',
            'sv' => 'Vårdcentral i Stockholm — konsultation, prover, ultraljud',
            'pl' => 'Klinika w Warszawie — konsultacja, badania, USG',
            'en' => 'Medical clinic in London — GP, tests, ultrasound',
            'lt' => 'Klinika Vilniuje — konsultacija, tyrimai, echoskopija',
            'uk' => 'Клініка в Києві — консультація, аналізи, УЗД',
            'ru' => 'Клиника в Москве — консультация, анализы, УЗИ',
        ],
        'law_office' => [
            'no' => 'Advokat i Oslo — familie, arbeid, kontrakter og selskap',
            'sv' => 'Advokat i Stockholm — familj, arbete, avtal och bolag',
            'pl' => 'Kancelaria w Warszawie — rodzina, praca, umowy, spółki',
            'en' => 'Law firm in London — family, employment, contracts',
            'lt' => 'Advokatai Vilniuje — šeima, darbas, sutartys, įmonės',
            'uk' => 'Юристи в Києві — сімейне, трудове, договори, бізнес',
            'ru' => 'Юристы в Москве — семейное, трудовое, договоры, бизнес',
        ],
        default => ld_preset_country_seo_descriptions(ld_demo_business_names($presetId), ['en' => 'Local business services']),
    };
}

function ld_demo_seo_titles(string $presetId, array $names): array
{
    $out = [];
    foreach (ld_lang_countries() as $code => $country) {
        $name = ld_pick($names, $code);
        $city = (string) $country['city'];
        $out[$code] = match ($presetId) {
            'taxi' => match ($code) {
                'no' => "{$name} — bestill taxi {$city} | Flyplass 24/7",
                'sv' => "{$name} — boka taxi {$city} | Flygplats 24/7",
                'pl' => "{$name} — zamów taxi {$city} | Lotnisko 24/7",
                'en' => "{$name} — book taxi {$city} | Airport 24/7",
                'lt' => "{$name} — užsakyti taksi {$city} | Oro uostas 24/7",
                'uk' => "{$name} — замовити таксі {$city} | Аеропорт 24/7",
                'ru' => "{$name} — заказать такси {$city} | Аэропорт 24/7",
                default => "{$name} {$city} — taxi booking",
            },
            'driving_school' => ld_driving_seo_defaults()['title'][$code] ?? "{$name} {$city}",
            default => ld_preset_country_seo_titles($names)[$code],
        };
    }
    return $out;
}

function ld_demo_seo_descriptions(string $presetId, array $names): array
{
    $taglines = ld_demo_business_taglines($presetId);
    $out = [];
    foreach (ld_lang_countries() as $code => $country) {
        $tag = ld_pick($taglines, $code);
        if ($tag !== '') {
            $out[$code] = $tag . ' ' . match ($code) {
                'no' => 'Vi ringer tilbake innen 15 min.',
                'sv' => 'Vi ringer tillbaka inom 15 min.',
                'pl' => 'Oddzwonimy w 15 min.',
                'lt' => 'Perskambinsime per 15 min.',
                'uk' => 'Передзвонимо за 15 хв.',
                'ru' => 'Перезвоним за 15 мин.',
                default => 'We call back within 15 min.',
            };
            continue;
        }
        $out[$code] = ld_preset_country_seo_descriptions($names, $taglines)[$code];
    }
    return $out;
}

function ld_demo_seo_keywords(string $presetId, array $names): array
{
    $out = [];
    foreach (ld_lang_countries() as $code => $country) {
        $name = ld_pick($names, $code);
        $city = (string) $country['city'];
        $countryName = (string) $country['country'];
        $out[$code] = match ($presetId) {
            'taxi' => match ($code) {
                'no' => "taxi {$city}, bestill taxi, flyplass, {$countryName}",
                'sv' => "taxi {$city}, boka taxi, flygplats, {$countryName}",
                'pl' => "taxi {$city}, zamów taxi, lotnisko, {$countryName}",
                'en' => "taxi {$city}, book taxi, airport, {$countryName}",
                'lt' => "taksi {$city}, užsakyti taksi, oro uostas, {$countryName}",
                'uk' => "таксі {$city}, замовити таксі, аеропорт, {$countryName}",
                'ru' => "такси {$city}, заказ такси, аэропорт, {$countryName}",
                default => "taxi, {$city}, {$countryName}",
            },
            'driving_school' => ld_driving_seo_defaults()['keywords'][$code] ?? ld_preset_country_seo_keywords($names)[$code],
            default => ld_preset_country_seo_keywords($names)[$code],
        };
    }
    return $out;
}

function ld_localize_preview_settings(array $settings, string $presetId): array
{
    $names = ld_demo_business_names($presetId);
    $taglines = ld_demo_business_taglines($presetId);
    $country = ld_lang_countries();

    $settings['business']['name'] = $names;
    $settings['business']['tagline'] = $taglines;
    $settings['business']['city'] = ld_country_field('city_full');
    $settings['business']['address'] = ld_country_field('address');
    global $lang;
    $settings['business']['phone'] = (string) ld_lang_country($lang)['phone'];

    if (!empty($settings['hero']['visual_sub']) && is_array($settings['hero']['visual_sub'])) {
        $settings['hero']['visual_sub'] = ld_country_field('city');
    }

    $settings['seo']['title'] = ld_demo_seo_titles($presetId, $names);
    $settings['seo']['description'] = ld_demo_seo_descriptions($presetId, $names);
    $settings['seo']['keywords'] = ld_demo_seo_keywords($presetId, $names);

    $settings['integrations']['faktura']['country_id'] = (string) ld_lang_country($lang)['country_id'];

    return $settings;
}

function ld_build_template_preview_settings(int $templateId, string $lang): array
{
    $presetId = ld_template_preset_map()[$templateId] ?? 'driving_school';
    $preset = ld_business_preset($presetId);
    if ($preset === null) {
        $settings = ld_default_settings();
        $settings['active_template'] = $templateId;
        return ld_localize_preview_settings($settings, $presetId);
    }

    $settings = ld_default_settings();
    $patch = $preset['settings'] ?? [];
    foreach (['business', 'hero', 'sections', 'stats', 'services', 'team', 'faq', 'reviews', 'google', 'seo', 'blocks', 'ai', 'design', 'legal'] as $key) {
        if (isset($patch[$key])) {
            $settings[$key] = $patch[$key];
        }
    }
    if (isset($patch['currency'])) {
        $settings['currency'] = $patch['currency'];
    }
    $settings['active_template'] = $templateId;
    $settings['business_preset'] = $presetId;

    return ld_localize_preview_settings($settings, $presetId);
}

function ld_template_demo_name(int $templateId, string $lang): string
{
    $presetId = ld_template_preset_map()[$templateId] ?? 'driving_school';
    return ld_pick(ld_demo_business_names($presetId), $lang);
}

/** @return list<array{id:int,label:string,url:string,business:string}> */
function ld_template_cross_links(string $lang, int $currentId = 0): array
{
    $names = ld_template_names($lang);
    $out = [];
    for ($i = 1; $i <= 10; $i++) {
        if ($i === $currentId) {
            continue;
        }
        $out[] = [
            'id' => $i,
            'label' => ($names[$i] ?? '#' . $i),
            'business' => ld_template_demo_name($i, $lang),
            'url' => ld_url('template.php', ['t' => $i]),
        ];
    }
    return $out;
}