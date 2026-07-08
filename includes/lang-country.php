<?php
declare(strict_types=1);

/**
 * Each UI language maps to its primary country market (local SEO + Schema.org).
 *
 * @return array<string, array<string, mixed>>
 */
function ld_lang_countries(): array
{
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }

    $cache = [
        'no' => [
            'country_code' => 'NO',
            'country_id' => 'no',
            'country' => 'Norge',
            'city' => 'Oslo',
            'city_full' => 'Oslo, Norge',
            'address' => 'Karl Johans gate 15, 0154 Oslo',
            'phone' => '+47 22 12 34 56',
            'currency' => 'kr',
            'currency_iso' => 'NOK',
            'price_range' => 'kr kr',
            'lat' => 59.9139,
            'lng' => 10.7522,
            'exam_authority' => 'Statens vegvesen',
            'maps_query' => 'Karl Johans gate 15, 0154 Oslo, Norway',
        ],
        'sv' => [
            'country_code' => 'SE',
            'country_id' => 'se',
            'country' => 'Sverige',
            'city' => 'Stockholm',
            'city_full' => 'Stockholm, Sverige',
            'address' => 'Drottninggatan 12, 111 51 Stockholm',
            'phone' => '+46 8 12 34 56',
            'currency' => 'kr',
            'currency_iso' => 'SEK',
            'price_range' => 'kr kr',
            'lat' => 59.3293,
            'lng' => 18.0686,
            'exam_authority' => 'Transportstyrelsen',
            'maps_query' => 'Drottninggatan 12, 111 51 Stockholm, Sweden',
        ],
        'pl' => [
            'country_code' => 'PL',
            'country_id' => 'pl',
            'country' => 'Polska',
            'city' => 'Warszawa',
            'city_full' => 'Warszawa, Polska',
            'address' => 'ul. Marszałkowska 10, 00-590 Warszawa',
            'phone' => '+48 22 123 45 67',
            'currency' => 'zł',
            'currency_iso' => 'PLN',
            'price_range' => 'zł zł',
            'lat' => 52.2297,
            'lng' => 21.0122,
            'exam_authority' => 'WORD',
            'maps_query' => 'ul. Marszałkowska 10, 00-590 Warszawa, Poland',
        ],
        'en' => [
            'country_code' => 'GB',
            'country_id' => 'gb',
            'country' => 'United Kingdom',
            'city' => 'London',
            'city_full' => 'London, United Kingdom',
            'address' => '221B Baker Street, London NW1 6XE',
            'phone' => '+44 20 7946 0958',
            'currency' => '£',
            'currency_iso' => 'GBP',
            'price_range' => '££',
            'lat' => 51.5238,
            'lng' => -0.1585,
            'exam_authority' => 'DVSA',
            'maps_query' => '221B Baker Street, London NW1 6XE, UK',
        ],
        'lt' => [
            'country_code' => 'LT',
            'country_id' => 'lt',
            'country' => 'Lietuva',
            'city' => 'Vilnius',
            'city_full' => 'Vilnius, Lietuva',
            'address' => 'Konstitucijos pr. 12, LT-09308 Vilnius',
            'phone' => '+370 612 345 67',
            'currency' => '€',
            'currency_iso' => 'EUR',
            'price_range' => '€€',
            'lat' => 54.6997,
            'lng' => 25.2797,
            'exam_authority' => 'Regitra',
            'maps_query' => 'Konstitucijos pr. 12, LT-09308 Vilnius, Lithuania',
        ],
        'uk' => [
            'country_code' => 'UA',
            'country_id' => 'ua',
            'country' => 'Україна',
            'city' => 'Київ',
            'city_full' => 'Київ, Україна',
            'address' => 'вул. Хрещатик 22, 01001 Київ',
            'phone' => '+380 44 123 45 67',
            'currency' => '₴',
            'currency_iso' => 'UAH',
            'price_range' => '₴₴',
            'lat' => 50.4501,
            'lng' => 30.5234,
            'exam_authority' => 'Сервісний центр МВС',
            'maps_query' => 'вул. Хрещатик 22, 01001 Kyiv, Ukraine',
        ],
        'ru' => [
            'country_code' => 'RU',
            'country_id' => 'ru',
            'country' => 'Россия',
            'city' => 'Москва',
            'city_full' => 'Москва, Россия',
            'address' => 'ул. Тверская 12, 125009 Москва',
            'phone' => '+7 495 123 45 67',
            'currency' => '₽',
            'currency_iso' => 'RUB',
            'price_range' => '₽₽',
            'lat' => 55.7558,
            'lng' => 37.6173,
            'exam_authority' => 'ГИБДД',
            'maps_query' => 'ул. Тверская 12, 125009 Moscow, Russia',
        ],
    ];

    return $cache;
}

function ld_lang_country(string $lang): array
{
    $countries = ld_lang_countries();
    return $countries[$lang] ?? $countries['no'];
}

function ld_lang_country_code(string $lang): string
{
    return (string) ld_lang_country($lang)['country_code'];
}

function ld_lang_country_city(string $lang): string
{
    return (string) ld_lang_country($lang)['city'];
}

/**
 * Build per-lang array from country field (e.g. all cities for business.city).
 *
 * @return array{no:string,sv:string,pl:string,en:string,lt:string,uk:string,ru:string}
 */
function ld_country_field(string $field): array
{
    $out = [];
    foreach (ld_lang_countries() as $code => $row) {
        $out[$code] = (string) ($row[$field] ?? '');
    }
    return $out;
}

/**
 * SEO city check: match short city name inside title/description.
 */
function ld_seo_contains_city(string $blob, string $lang, array $business): bool
{
    $blob = mb_strtolower($blob);
    $cityShort = mb_strtolower(ld_lang_country_city($lang));
    if ($cityShort !== '' && str_contains($blob, $cityShort)) {
        return true;
    }
    $cityFull = mb_strtolower(trim((string) ($business['city'][$lang] ?? '')));
    if ($cityFull !== '' && str_contains($blob, $cityFull)) {
        return true;
    }
    $country = mb_strtolower((string) ld_lang_country($lang)['country']);
    return $country !== '' && str_contains($blob, $country);
}

/**
 * Default driving-school SEO meta per country + language.
 *
 * @return array{title:array,description:array,keywords:array}
 */
/**
 * @param array<string, string|array<string, string>> $name
 * @return array{no:string,sv:string,pl:string,en:string,lt:string,uk:string,ru:string}
 */
function ld_preset_country_seo_titles(array $name): array
{
    $suffix = [
        'no' => '— bestill time',
        'sv' => '— boka online',
        'pl' => '— rezerwacja online',
        'en' => '— book online',
        'lt' => '— užsiregistruokite',
        'uk' => '— запис онлайн',
        'ru' => '— запись онлайн',
    ];
    $out = [];
    foreach (ld_lang_countries() as $code => $country) {
        $biz = ld_pick($name, $code);
        $city = (string) $country['city'];
        $out[$code] = trim($biz . ' ' . $city . ' ' . ($suffix[$code] ?? '— book online'));
    }
    return $out;
}

/**
 * @param array<string, string|array<string, string>> $name
 * @param array<string, string|array<string, string>> $tagline
 * @return array{no:string,sv:string,pl:string,en:string,lt:string,uk:string,ru:string}
 */
function ld_preset_country_seo_descriptions(array $name, array $tagline): array
{
    $out = [];
    foreach (ld_lang_countries() as $code => $country) {
        $localTagline = ld_pick($tagline, $code);
        if ($localTagline !== '') {
            $out[$code] = $localTagline;
            continue;
        }
        $city = (string) $country['city'];
        $countryName = (string) $country['country'];
        $biz = ld_pick($name, $code);
        $out[$code] = match ($code) {
            'no' => "Profesjonelle tjenester i {$city}, {$countryName}. {$biz} — vi ringer tilbake innen 15 min.",
            'sv' => "Professionella tjänster i {$city}, {$countryName}. {$biz} — vi ringer inom 15 min.",
            'pl' => "Profesjonalne usługi w {$city}, {$countryName}. {$biz} — oddzwonimy w 15 min.",
            'lt' => "Profesionalios paslaugos {$city}, {$countryName}. {$biz} — perskambinsime per 15 min.",
            'uk' => "Професійні послуги в {$city}, {$countryName}. {$biz} — передзвонимо за 15 хв.",
            'ru' => "Профессиональные услуги в {$city}, {$countryName}. {$biz} — перезвоним за 15 мин.",
            default => "Professional services in {$city}, {$countryName}. {$biz} — we call back within 15 min.",
        };
    }
    return $out;
}

/**
 * @param array<string, string|array<string, string>> $name
 * @return array{no:string,sv:string,pl:string,en:string,lt:string,uk:string,ru:string}
 */
function ld_preset_country_seo_keywords(array $name): array
{
    $out = [];
    foreach (ld_lang_countries() as $code => $country) {
        $biz = ld_pick($name, $code);
        $city = (string) $country['city'];
        $countryName = (string) $country['country'];
        $out[$code] = match ($code) {
            'no' => "{$biz}, {$city}, {$countryName}, tjenester, bestilling",
            'sv' => "{$biz}, {$city}, {$countryName}, tjänster, bokning",
            'pl' => "{$biz}, {$city}, {$countryName}, usługi, rezerwacja",
            'lt' => "{$biz}, {$city}, {$countryName}, paslaugos, užrašymas",
            'uk' => "{$biz}, {$city}, {$countryName}, послуги, запис",
            'ru' => "{$biz}, {$city}, {$countryName}, услуги, запись",
            default => "{$biz}, {$city}, {$countryName}, services, booking",
        };
    }
    return $out;
}

function ld_driving_seo_defaults(): array
{
    return [
        'title' => [
            'no' => 'Oslo Trafikkskole — klasse B | Forberedelse til oppkjøring',
            'sv' => 'Stockholm Trafikskola — körkort B | Körkortsutbildning',
            'pl' => 'Szkoła Jazdy Warszawa — kat. B | Kurs na prawo jazdy',
            'en' => 'London Driving School — Category B | UK driving lessons',
            'lt' => 'Vilniaus vairavimo mokykla — B kategorija | Regitra',
            'uk' => 'Автошкола Київ — категорія B | Підготовка до іспиту',
            'ru' => 'Автошкола Москва — категория B | Подготовка к экзамену',
        ],
        'description' => [
            'no' => 'Profesjonell trafikkskole i Oslo: klasse B, teori og intensivkurs. Statens vegvesen-forberedelse. Vi ringer tilbake innen 15 min.',
            'sv' => 'Professionell trafikskola i Stockholm: körkort B, teori och intensivkurs. Förberedelse för Transportstyrelsen. Vi ringer inom 15 min.',
            'pl' => 'Profesjonalna szkoła jazdy w Warszawie: kat. B, teoria i kurs intensywny. Przygotowanie do WORD. Oddzwonimy w 15 min.',
            'en' => 'Professional driving school in London: Category B, theory and intensive courses. DVSA exam prep. We call you back within 15 min.',
            'lt' => 'Profesionali vairavimo mokykla Vilniuje: B kategorija, teorija, intensyvūs kursai. Regitra pasiruošimas. Perskambinsime per 15 min.',
            'uk' => 'Автошкола в Києві: категорія B, теорія, інтенсив. Підготовка до іспиту МВС. Передзвонимо за 15 хв.',
            'ru' => 'Автошкола в Москве: категория B, теория, интенсив. Подготовка к экзамену ГИБДД. Перезвоним за 15 мин.',
        ],
        'keywords' => [
            'no' => 'trafikkskole Oslo, klasse B, førerkort, Statens vegvesen, kjøretimer, Norge',
            'sv' => 'trafikskola Stockholm, körkort B, körlektioner, Transportstyrelsen, Sverige',
            'pl' => 'szkoła jazdy Warszawa, kat. B, prawo jazdy, WORD, lekcje jazdy, Polska',
            'en' => 'driving school London, category B, DVSA, driving lessons, UK',
            'lt' => 'vairavimo mokykla Vilnius, B kategorija, Regitra, vairavimo kursai, Lietuva',
            'uk' => 'автошкола Київ, категорія B, курси водіння, іспит МВС, Україна',
            'ru' => 'автошкола Москва, категория B, курсы вождения, ГИБДД, Россия',
        ],
    ];
}