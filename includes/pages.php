<?php
declare(strict_types=1);

function ld_pages_file(): string
{
    return ld_data_path('pages.json');
}

function ld_load_pages(): array
{
    return ld_load_json(ld_pages_file(), []);
}

function ld_save_pages(array $items): bool
{
    return ld_save_json(ld_pages_file(), $items);
}

function ld_get_page(string $id): ?array
{
    foreach (ld_load_pages() as $row) {
        if (($row['id'] ?? '') === $id) {
            return $row;
        }
    }
    return null;
}

function ld_get_page_by_slug(string $slug): ?array
{
    $slug = ld_page_slugify($slug);
    foreach (ld_load_pages() as $row) {
        if (($row['slug'] ?? '') === $slug) {
            return $row;
        }
    }
    return null;
}

function ld_pages_published(): array
{
    $items = array_values(array_filter(
        ld_load_pages(),
        static fn(array $p): bool => ($p['status'] ?? '') === 'published'
    ));
    usort($items, static function (array $a, array $b): int {
        $oa = (int) ($a['sort_order'] ?? 0);
        $ob = (int) ($b['sort_order'] ?? 0);
        if ($oa !== $ob) {
            return $oa <=> $ob;
        }
        return strcmp((string) ($a['title']['no'] ?? $a['title']['en'] ?? ''), (string) ($b['title']['no'] ?? $b['title']['en'] ?? ''));
    });
    return $items;
}

function ld_pages_footer(): array
{
    return array_values(array_filter(
        ld_pages_published(),
        static fn(array $p): bool => !empty($p['show_in_footer'])
    ));
}

function ld_page_localize(array $row, string $lang): array
{
    foreach (['title', 'body', 'seo_title', 'seo_description', 'seo_keywords'] as $key) {
        if (isset($row[$key]) && is_array($row[$key])) {
            $row[$key] = ld_pick($row[$key], $lang);
        }
    }
    return $row;
}

function ld_page_slugify(string $text): string
{
    $text = mb_strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9\s-]/u', '', $text) ?? '';
    $text = preg_replace('/[\s-]+/', '-', $text) ?? '';
    return trim($text, '-') ?: 'page';
}

function ld_page_url(string $slug, ?string $langCode = null): string
{
    $qs = ['slug' => ld_page_slugify($slug)];
    $langCode = $langCode ?? ($GLOBALS['lang'] ?? 'no');
    if ($langCode !== 'no') {
        $qs['lang'] = $langCode;
    }
    return ld_url('page.php', $qs);
}

function ld_privacy_url(): string
{
    $legal = ld_settings()['legal'] ?? ld_default_settings()['legal'];
    $external = trim((string) ($legal['privacy_url'] ?? ''));
    if ($external !== '' && preg_match('#^https?://#i', $external)) {
        return $external;
    }
    $slug = trim((string) ($legal['privacy_slug'] ?? 'privacy'));
    if ($slug === '') {
        $slug = 'privacy';
    }
    return ld_page_url($slug);
}

/** @return array<string, string> */
function ld_pages_i18n_from_post(array $input, string $prefix): array
{
    $out = [];
    foreach (ld_langs_codes() as $code) {
        $key = $prefix . '_' . $code;
        if (isset($input[$key])) {
            $out[$code] = trim((string) $input[$key]);
        }
    }
    return $out;
}

function ld_pages_upsert(array $input): array
{
    $items = ld_load_pages();
    $id = trim((string) ($input['id'] ?? ''));
    $isNew = $id === '';
    if ($isNew) {
        $id = 'page-' . date('Ymd') . '-' . substr(bin2hex(random_bytes(3)), 0, 6);
    }

    $titleNo = trim((string) ($input['title_no'] ?? ''));
    $slug = trim((string) ($input['slug'] ?? ''));
    if ($slug === '' && $titleNo !== '') {
        $slug = ld_page_slugify($titleNo);
    }
    $slug = ld_page_slugify($slug);

    $existing = null;
    if (!$isNew) {
        foreach ($items as $existingRow) {
            if (($existingRow['id'] ?? '') === $id) {
                $existing = $existingRow;
                break;
            }
        }
    }

    foreach ($items as $other) {
        if (($other['id'] ?? '') !== $id && ($other['slug'] ?? '') === $slug) {
            return ['ok' => false, 'error' => 'slug_taken'];
        }
    }

    $row = [
        'id' => $id,
        'slug' => $slug,
        'status' => in_array(($input['status'] ?? 'draft'), ['draft', 'published'], true) ? $input['status'] : 'draft',
        'show_in_footer' => !empty($input['show_in_footer']),
        'sort_order' => (int) ($input['sort_order'] ?? 0),
        'is_system' => !empty($existing['is_system']),
        'title' => ld_pages_i18n_from_post($input, 'title'),
        'body' => ld_pages_i18n_from_post($input, 'body'),
        'seo_title' => ld_pages_i18n_from_post($input, 'seo_title'),
        'seo_description' => ld_pages_i18n_from_post($input, 'seo_description'),
        'seo_keywords' => ld_pages_i18n_from_post($input, 'seo_keywords'),
        'updated_at' => date('c'),
    ];

    if ($isNew) {
        $row['created_at'] = date('c');
        array_unshift($items, $row);
    } else {
        $found = false;
        foreach ($items as $i => $item) {
            if (($item['id'] ?? '') === $id) {
                $row['created_at'] = $item['created_at'] ?? date('c');
                $row['is_system'] = !empty($item['is_system']);
                $items[$i] = $row;
                $found = true;
                break;
            }
        }
        if (!$found) {
            $row['created_at'] = date('c');
            array_unshift($items, $row);
        }
    }

    ld_save_pages($items);
    return ['ok' => true, 'id' => $id];
}

function ld_pages_delete(string $id): bool
{
    $items = ld_load_pages();
    $before = count($items);
    $items = array_values(array_filter($items, static function (array $p) use ($id): bool {
        if (($p['id'] ?? '') !== $id) {
            return true;
        }
        return empty($p['is_system']);
    }));
    return $before !== count($items) && ld_save_pages($items);
}

function ld_ensure_pages(): void
{
    if (!is_file(ld_pages_file())) {
        ld_save_json(ld_pages_file(), ld_default_pages());
    }
}

/** @return list<array<string, mixed>> */
function ld_default_pages(): array
{
    return [
        [
            'id' => 'page-privacy',
            'slug' => 'privacy',
            'status' => 'published',
            'show_in_footer' => true,
            'sort_order' => 1,
            'is_system' => true,
            'created_at' => date('c'),
            'updated_at' => date('c'),
            'title' => [
                'no' => 'Personvernerklæring',
                'sv' => 'Integritetspolicy',
                'pl' => 'Polityka prywatności',
                'en' => 'Privacy policy',
                'lt' => 'Privatumo politika',
                'uk' => 'Політика конфіденційності',
                'ru' => 'Политика конфиденциальности',
            ],
            'body' => [
                'no' => "Vi behandler personopplysninger i tråd med GDPR og norsk personvernlovgivning.\n\n• Kontaktskjema: navn, telefon og e-post brukes kun til å svare på henvendelsen.\n• Lagring: demo-data i Business Landing CMS — ikke delt med tredjeparter.\n• Dine rettigheter: innsyn, retting og sletting — kontakt oss på e-post.\n• Informasjonskapsler: kun nødvendige for språkvalg og demo.",
                'sv' => "Vi behandlar personuppgifter enligt GDPR.\n\n• Kontaktformulär: namn, telefon och e-post används endast för att svara.\n• Lagring: demodata i Business Landing CMS.\n• Dina rättigheter: insyn, rättelse och radering.",
                'pl' => "Przetwarzamy dane osobowe zgodnie z RODO.\n\n• Formularz kontaktowy: imię, telefon i e-mail służą wyłącznie do odpowiedzi.\n• Przechowywanie: dane demo w Business Landing CMS.\n• Twoje prawa: dostęp, sprostowanie i usunięcie danych.",
                'en' => "We process personal data in line with GDPR.\n\n• Contact form: name, phone and email are used only to respond to your enquiry.\n• Storage: demo data in Business Landing CMS — not shared with third parties.\n• Your rights: access, correction and deletion — contact us by email.\n• Cookies: essential only for language preference and demo.",
                'lt' => "Tvarkome asmens duomenis pagal BDAR.\n\n• Kontaktų forma: vardas, telefonas ir el. paštas naudojami tik atsakymui.\n• Saugojimas: demo duomenys Business Landing CMS.\n• Jūsų teisės: prieiga, ištaisymas ir ištrynimas.",
                'uk' => "Обробляємо персональні дані відповідно до GDPR.\n\n• Контактна форма: ім'я, телефон і e-mail лише для відповіді.\n• Зберігання: демо-дані в Business Landing CMS.\n• Ваші права: доступ, виправлення та видалення.",
                'ru' => "Обрабатываем персональные данные в соответствии с GDPR.\n\n• Контактная форма: имя, телефон и e-mail только для ответа.\n• Хранение: демо-данные в Business Landing CMS.\n• Ваши права: доступ, исправление и удаление.",
            ],
            'seo_title' => [],
            'seo_description' => [],
            'seo_keywords' => [],
        ],
        [
            'id' => 'page-terms',
            'slug' => 'terms',
            'status' => 'published',
            'show_in_footer' => true,
            'sort_order' => 2,
            'is_system' => false,
            'created_at' => date('c'),
            'updated_at' => date('c'),
            'title' => [
                'no' => 'Vilkår for bruk',
                'sv' => 'Användarvillkor',
                'pl' => 'Warunki korzystania',
                'en' => 'Terms of use',
                'lt' => 'Naudojimo sąlygos',
                'uk' => 'Умови використання',
                'ru' => 'Условия использования',
            ],
            'body' => [
                'no' => "Dette er en demonstrasjonsside for Business Landing CMS.\n\nInnholdet er eksempeldata. Ingen reelle tjenester tilbys. Ved live lansering erstattes teksten med dine egne vilkår.",
                'sv' => "Detta är en demosida för Business Landing CMS.\n\nInnehållet är exempeldata. Inga riktiga tjänster erbjuds.",
                'pl' => "To strona demonstracyjna Business Landing CMS.\n\nTreść jest przykładowa. Nie oferujemy prawdziwych usług.",
                'en' => "This is a demonstration site for Business Landing CMS.\n\nContent is sample data. No real services are offered. Replace with your own terms before going live.",
                'lt' => 'Tai Business Landing CMS demonstracinė svetainė. Turinys yra pavyzdinis.',
                'uk' => 'Це демо-сайт Business Landing CMS. Контент є зразковим.',
                'ru' => 'Это демо-сайт Business Landing CMS. Контент является примером.',
            ],
            'seo_title' => [],
            'seo_description' => [],
            'seo_keywords' => [],
        ],
    ];
}