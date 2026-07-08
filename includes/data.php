<?php
declare(strict_types=1);

function ld_business(): array
{
    return ld_effective_settings()['business'] ?? ld_default_settings()['business'];
}

function ld_school(): array
{
    return ld_business();
}

function ld_hero(): array
{
    return ld_effective_settings()['hero'] ?? ld_default_settings()['hero'];
}

function ld_sections(): array
{
    return ld_effective_settings()['sections'] ?? ld_default_settings()['sections'];
}

function ld_currency(): string
{
    return (string) (ld_effective_settings()['currency'] ?? '€');
}

function ld_localize_list(array $items, string $lang, array $i18nKeys): array
{
    $out = [];
    foreach ($items as $item) {
        if (!is_array($item)) {
            continue;
        }
        $row = $item;
        foreach ($i18nKeys as $key) {
            if (isset($row[$key]) && is_array($row[$key])) {
                $row[$key] = ld_pick($row[$key], $lang);
            }
        }
        $out[] = $row;
    }
    return $out;
}

function ld_stats_for(string $lang): array
{
    $items = ld_effective_settings()['stats'] ?? [];
    return ld_localize_list($items, $lang, ['label']);
}

function ld_services(string $lang): array
{
    $items = ld_effective_settings()['services'] ?? [];
    $list = ld_localize_list($items, $lang, ['name', 'desc', 'badge']);
    foreach ($list as &$row) {
        if (($row['badge'] ?? '') === '') {
            $row['badge'] = null;
        }
    }
    unset($row);
    return $list;
}

function ld_team(string $lang): array
{
    $items = ld_effective_settings()['team'] ?? [];
    return ld_localize_list($items, $lang, ['role']);
}

function ld_faq(string $lang): array
{
    $items = ld_effective_settings()['faq'] ?? [];
    return ld_localize_list($items, $lang, ['q', 'a']);
}

function ld_courses(string $lang): array
{
    return ld_services($lang);
}

function ld_instructors(string $lang): array
{
    return ld_team($lang);
}

function ld_google(): array
{
    return ld_effective_settings()['google'] ?? ld_default_settings()['google'];
}

function ld_reviews(string $lang): array
{
    $items = ld_effective_settings()['reviews'] ?? [];
    return ld_localize_list($items, $lang, ['text']);
}

function ld_business_address_query(?string $langCode = null): string
{
    global $lang;
    $resolved = $langCode ?? $lang ?? 'no';
    $business = ld_business();
    foreach (array_unique([$resolved, 'no', 'en', 'lt', 'uk', 'sv', 'pl', 'ru']) as $code) {
        $address = ld_pick($business['address'] ?? [], $code);
        if ($address !== '') {
            return $address;
        }
    }
    $country = ld_lang_country($resolved);
    return (string) ($country['maps_query'] ?? '');
}

function ld_maps_embed_src(): string
{
    $query = ld_business_address_query();
    if ($query !== '') {
        return 'https://www.google.com/maps?q=' . rawurlencode($query) . '&output=embed';
    }
    $google = ld_google();
    $embed = ld_extract_iframe_src((string) ($google['maps_embed'] ?? ''));
    if ($embed !== '' && ld_is_google_maps_embed($embed)) {
        return $embed;
    }
    return '';
}

function ld_maps_link(): string
{
    $query = ld_business_address_query();
    if ($query !== '') {
        return 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($query);
    }
    $google = ld_google();
    $link = trim((string) ($google['maps_link'] ?? ''));
    if ($link !== '' && filter_var($link, FILTER_VALIDATE_URL)) {
        return $link;
    }
    return '';
}

function ld_google_reviews_url(): string
{
    $link = ld_maps_link();
    if ($link !== '') {
        return $link;
    }
    $google = ld_google();
    $url = trim((string) ($google['reviews_url'] ?? ''));
    if ($url !== '' && filter_var($url, FILTER_VALIDATE_URL)) {
        return $url;
    }
    return '';
}

function ld_has_map(): bool
{
    return ld_maps_embed_src() !== '';
}

function ld_has_reviews(): bool
{
    $items = ld_effective_settings()['reviews'] ?? [];
    if (is_array($items) && $items !== []) {
        return true;
    }
    $google = ld_google();
    return trim((string) ($google['rating'] ?? '')) !== '';
}

function ld_section_text(string $key, string $field, string $lang, string $fallback = ''): string
{
    $sections = ld_sections();
    $row = $sections[$key][$field] ?? null;
    if (is_array($row)) {
        $text = ld_pick($row, $lang);
        if ($text !== '') {
            return $text;
        }
    }
    return $fallback;
}

function ld_template_names(string $lang): array
{
    $names = [
        1  => ['no' => 'Klassisk blå', 'sv' => 'Klassisk blå', 'pl' => 'Klasyczny niebieski', 'lt' => 'Klasikinis mėlynas', 'uk' => 'Класичний синій', 'ru' => 'Классический синий', 'en' => 'Classic Blue'],
        2  => ['no' => 'Solnedgang kjøring', 'sv' => 'Solnedgångskörning', 'pl' => 'Zachód słońca', 'lt' => 'Saulėlydžio važiavimas', 'uk' => 'Захід сонця', 'ru' => 'Закат солнца', 'en' => 'Sunset Drive'],
        3  => ['no' => 'Skog trygg', 'sv' => 'Skog säker', 'pl' => 'Leśne bezpieczeństwo', 'lt' => 'Miško saugumas', 'uk' => 'Лісова безпека', 'ru' => 'Лесная безопасность', 'en' => 'Forest Safe'],
        4  => ['no' => 'Natt moderne', 'sv' => 'Natt modern', 'pl' => 'Nocny modern', 'lt' => 'Naktinis modernus', 'uk' => 'Нічний модерн', 'ru' => 'Ночной модерн', 'en' => 'Night Modern'],
        5  => ['no' => 'Minimal lys', 'sv' => 'Minimal ljus', 'pl' => 'Minimal jasny', 'lt' => 'Minimalus šviesus', 'uk' => 'Мінімал світлий', 'ru' => 'Минимал светлый', 'en' => 'Minimal Light'],
        6  => ['no' => 'Racing rød', 'sv' => 'Racing röd', 'pl' => 'Wyścigowy czerwony', 'lt' => 'Lenktynių raudonas', 'uk' => 'Гоночний червоний', 'ru' => 'Гоночный красный', 'en' => 'Racing Red'],
        7  => ['no' => 'Premium lilla', 'sv' => 'Premium lila', 'pl' => 'Premium fiolet', 'lt' => 'Premium violetinis', 'uk' => 'Преміум фіолет', 'ru' => 'Премиум фиолет', 'en' => 'Premium Purple'],
        8  => ['no' => 'Teal urban', 'sv' => 'Teal urban', 'pl' => 'Miejski teal', 'lt' => 'Miesto žydras', 'uk' => 'Міський бірюза', 'ru' => 'Городская бирюза', 'en' => 'Teal Urban'],
        9  => ['no' => 'Taxi gul', 'sv' => 'Taxi gul', 'pl' => 'Taxi żółty', 'lt' => 'Taksi geltonas', 'uk' => 'Таксі жовтий', 'ru' => 'Такси жёлтый', 'en' => 'Taxi Yellow'],
        10 => ['no' => 'Skifer corporate', 'sv' => 'Skiffer corporate', 'pl' => 'Korporacyjny szary', 'lt' => 'Korporacinis pilkas', 'uk' => 'Корпоративний сірий', 'ru' => 'Корпоративный серый', 'en' => 'Slate Corporate'],
    ];
    $out = [];
    foreach ($names as $id => $row) {
        $out[$id] = ld_pick($row, $lang);
    }
    return $out;
}