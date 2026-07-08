<?php
declare(strict_types=1);

/**
 * Build full landing settings from per-business config (tailored SEO, team, FAQ, AI).
 *
 * @param array<string, mixed> $cfg
 * @return array<string, mixed>
 */
function ld_preset_build(array $cfg): array
{
    $name = $cfg['name'];
    $tagline = $cfg['tagline'];
    $heroIcon = (string) ($cfg['hero_icon'] ?? 'fa-store');
    $cta = $cfg['cta'];
    $template = (int) ($cfg['template'] ?? 1);
    $ogImage = (string) ($cfg['og_image'] ?? '');
    $aiPrompt = (string) ($cfg['ai_prompt'] ?? 'Business assistant for {business_name} in {city}. Language: {lang}. Plain text only.');
    $bizEn = ld_pick($name, 'en');
    $slug = strtolower(preg_replace('/[^a-z0-9]+/', '', $bizEn) ?? 'business');

    $services = [];
    foreach ((array) ($cfg['services'] ?? []) as $i => $row) {
        $services[] = [
            'icon' => (string) ($row['icon'] ?? 'fa-star'),
            'name' => $row['name'],
            'desc' => $row['desc'] ?? ld_pi('Profesionali paslauga su garantija.', 'Професійна послуга з гарантією.', 'Professional service with guarantee.'),
            'price' => (string) ($row['price'] ?? ''),
            'badge' => $row['badge'] ?? ($i === 0 ? ld_pi('Populiaru', 'Популярно', 'Popular') : null),
        ];
    }

    $defaultSections = [
        'services' => ['title' => $cfg['section_services_title'] ?? ld_pi('Paslaugos', 'Послуги', 'Services'), 'lead' => $cfg['section_services_lead'] ?? ld_pi('Ką siūlome.', 'Що пропонуємо.', 'What we offer.')],
        'team' => ['title' => $cfg['section_team_title'] ?? ld_pi('Komanda', 'Команда', 'Team'), 'lead' => $cfg['section_team_lead'] ?? ld_pi('Profesionalai 7 kalbomis.', 'Професіонали 7 мовами.', 'Professionals in 7 languages.', null, 'Profesjonelle på 7 språk.', 'Proffs på 7 språk.', 'Profesjonaliści w 7 językach.')],
        'faq' => ['title' => $cfg['section_faq_title'] ?? ld_pi('DUK', 'FAQ', 'FAQ')],
        'contact' => ['title' => $cfg['section_contact_title'] ?? ld_pi('Kontaktai', 'Контакт', 'Contact'), 'lead' => $cfg['section_contact_lead'] ?? ld_pi('Užpildykite formą — susisieksime per 15 min.', 'Заповніть форму — зв\'яжемося за 15 хв.', 'Fill the form — we reply within 15 min.')],
        'reviews' => ['title' => $cfg['section_reviews_title'] ?? ld_pi('Atsiliepimai', 'Відгуки', 'Reviews'), 'lead' => $cfg['section_reviews_lead'] ?? ld_pi('Google Maps atsiliepimai.', 'Відгуки Google Maps.', 'Google Maps reviews.')],
        'map' => ['title' => $cfg['section_map_title'] ?? ld_pi('Adresas', 'Адреса', 'Location')],
        'features' => ['title' => $cfg['section_features_title'] ?? ld_pi('Kodėl mes', 'Чому ми', 'Why us')],
        'gallery' => ['title' => $cfg['section_gallery_title'] ?? ld_pi('Galerija', 'Галерея', 'Gallery')],
    ];

    $team = $cfg['team'] ?? [
        ['name' => 'Marius Petraitis', 'role' => ld_pi('Vadovas', 'Керівник', 'Manager'), 'years' => '10', 'initials' => 'MP'],
        ['name' => 'Olena Koval', 'role' => ld_pi('Specialistė (UA/LT)', 'Спеціаліст (UA/LT)', 'Specialist (UA/LT)'), 'years' => '7', 'initials' => 'OK'],
    ];

    $faq = $cfg['faq'] ?? [
        ['q' => ld_pi('Kaip užsiregistruoti?', 'Як записатися?', 'How to book?'), 'a' => ld_pi('Forma svetainėje arba telefonu.', 'Форма на сайті або телефон.', 'Use the form or call us.')],
        ['q' => ld_pi('Kokios kalbos?', 'Які мови?', 'Which languages?'), 'a' => ld_pi('NO, SV, PL, EN, LT, UA, RU.', 'NO, SV, PL, EN, LT, UA, RU.', 'NO, SV, PL, EN, LT, UA, RU.', null, 'NO, SV, PL, EN, LT, UA, RU.', 'NO, SV, PL, EN, LT, UA, RU.', 'NO, SV, PL, EN, LT, UA, RU.')],
        ['q' => ld_pi('Kokios darbo valandos?', 'Години роботи?', 'Opening hours?'), 'a' => ld_pi('Pr–Pn 9:00–18:00.', 'Пн–Пт 9:00–18:00.', 'Mon–Fri 9:00–18:00.')],
    ];

    $reviews = $cfg['reviews'] ?? [
        ['author' => 'Rasa K.', 'rating' => '5', 'date' => '2025-11', 'text' => ld_pi('Puiki paslauga — rekomenduoju!', 'Чудовий сервіс — рекомендую!', 'Great service — highly recommend!')],
        ['author' => 'Dmytro V.', 'rating' => '5', 'date' => '2025-09', 'text' => ld_pi('Kalba ukrainiečių — labai patogu.', 'Говорять українською — дуже зручно.', 'They speak Ukrainian — very convenient.')],
    ];

    $stats = $cfg['stats'] ?? [
        ['value' => '1 000+', 'label' => ld_pi('Klientų', 'Клієнтів', 'Clients')],
        ['value' => '4.8', 'label' => ld_pi('Google reitingas', 'Рейтинг Google', 'Google rating')],
        ['value' => '10+', 'label' => ld_pi('Metų patirties', 'Років досвіду', 'Years experience')],
        ['value' => '15 min', 'label' => ld_pi('Atsakymas', 'Відповідь', 'Response time')],
    ];

    $features = $cfg['features'] ?? [
        ['icon' => 'fa-check', 'title' => ld_pi('Kokybė', 'Якість', 'Quality'), 'desc' => ld_pi('Patikimi specialistai.', 'Надійні фахівці.', 'Trusted specialists.')],
        ['icon' => 'fa-language', 'title' => ld_pi('NO · SV · PL · EN', 'NO · SV · PL · EN', 'NO · SV · PL · EN', null, 'NO · SV · PL · EN', 'NO · SV · PL · EN', 'NO · SV · PL · EN'), 'desc' => ld_pi('Pilnas daugiakalbis aptarnavimas — 7 kalbos.', 'Повний багатомовний сервіс — 7 мов.', 'Full multilingual service — 7 languages.', null, 'Full flerspråklig service — 7 språk.', 'Full flerspråkig service — 7 språk.', 'Pełna obsługa wielojęzyczna — 7 języków.')],
        ['icon' => 'fa-bolt', 'title' => ld_pi('Greitas atsakymas', 'Швидка відповідь', 'Fast response'), 'desc' => ld_pi('Skambiname tą pačią dieną.', 'Дзвонимо того ж дня.', 'We call back the same day.')],
    ];

    $gallery = $cfg['gallery'] ?? [['url' => $ogImage, 'caption' => $name]];
    $heroImage = (string) ($cfg['hero_image'] ?? str_replace(['w=1200', 'h=630'], ['w=900', 'h=600'], $ogImage));

    $seoTitle = $cfg['seo_title'] ?? ld_preset_country_seo_titles($name);
    $seoDesc = $cfg['seo_description'] ?? ld_preset_country_seo_descriptions($name, $tagline);
    $seoKw = $cfg['seo_keywords'] ?? ld_preset_country_seo_keywords($name);

    return [
        'currency' => (string) ($cfg['currency'] ?? 'kr'),
        'business' => [
            'name' => $name,
            'tagline' => $tagline,
            'city' => $cfg['city'] ?? ld_country_field('city_full'),
            'address' => $cfg['address'] ?? ld_country_field('address'),
            'phone' => (string) ($cfg['phone'] ?? '+47 22 12 34 56'),
            'email' => (string) ($cfg['email'] ?? 'info@' . ($slug ?: 'business') . '.demo'),
            'hours' => $cfg['hours'] ?? ld_pi('Pr–Pn 9:00–18:00 · Št 10:00–14:00', 'Пн–Пт 9:00–18:00 · Сб 10:00–14:00', 'Mon–Fri 9:00–18:00 · Sat 10:00–14:00', null, 'Man–fre 9:00–18:00 · lør 10:00–14:00', 'Mån–fre 9:00–18:00 · lör 10:00–14:00', 'Pon–pt 9:00–18:00 · sob 10:00–14:00'),
        ],
        'hero' => [
            'cta' => $cta,
            'cta2' => $cfg['cta2'] ?? ld_pi('Paslaugos', 'Послуги', 'Services'),
            'visual_icon' => $heroIcon,
            'visual_label' => $cfg['visual_label'] ?? $name,
            'visual_sub' => $cfg['visual_sub'] ?? ld_country_field('city'),
        ],
        'sections' => $defaultSections,
        'stats' => $stats,
        'services' => $services,
        'team' => $team,
        'faq' => $faq,
        'reviews' => $reviews,
        'google' => [
            'maps_embed' => '',
            'maps_link' => '',
            'reviews_url' => (string) ($cfg['reviews_url'] ?? 'https://www.google.com/maps/search/?api=1&query=Karl+Johans+gate+15+Oslo'),
            'rating' => (string) ($cfg['google_rating'] ?? '4.8'),
            'review_count' => (string) ($cfg['google_review_count'] ?? '96'),
        ],
        'seo' => [
            'title' => $seoTitle,
            'description' => $seoDesc,
            'keywords' => $seoKw,
            'og_image' => $ogImage,
        ],
        'blocks' => [
            'hero_image' => $heroImage,
            'gallery' => $gallery,
            'features' => $features,
            'cta' => [
                'enabled' => true,
                'title' => $cfg['cta_title'] ?? ld_pi('Reikia pagalbos?', 'Потрібна допомога?', 'Need help?'),
                'lead' => $cfg['cta_lead'] ?? ld_pi('Paskambinkite — atsakysime šiandien.', 'Зателефонуйте — відповімо сьогодні.', 'Call us — we reply today.'),
                'phone' => '',
            ],
        ],
        'ai' => [
            'welcome' => $cfg['ai_welcome'] ?? ld_pi(
                'Labas! Padėsiu užsiregistruoti ir atsakysiu apie paslaugas bei kainas.',
                'Привіт! Допоможу записатися та відповім про послуги й ціни.',
                'Hi! I help with booking and answer questions about services and prices.',
                null,
                'Hei! Jeg hjelper deg med bestilling og svarer på spørsmål om tjenester og priser.',
                'Hej! Jag hjälper dig boka och svarar på frågor om tjänster och priser.',
                'Cześć! Pomogę z rezerwacją i odpowiem na pytania o usługi i ceny.'
            ),
            'system_prompt' => $aiPrompt,
        ],
    ];
}

/** @return array{id:string,icon:string,template:int,brief:string,label:array,desc:array,settings:array} */
function ld_preset_wrap(string $id, string $icon, int $template, string $brief, array $label, array $desc, array $settings): array
{
    return [
        'id' => $id,
        'icon' => $icon,
        'template' => $template,
        'brief' => $brief,
        'label' => $label,
        'desc' => $desc,
        'settings' => $settings,
    ];
}

/** @deprecated Use ld_preset_build() — kept for internal shorthand */
function ld_preset_build_simple(
    array $name,
    array $tagline,
    string $heroIcon,
    array $cta,
    array $serviceRows,
    string $ogImage,
    int $template,
    string $aiPrompt
): array {
    $services = [];
    foreach ($serviceRows as $row) {
        $services[] = [
            'icon' => $row['icon'],
            'name' => $row['name'],
            'price' => $row['price'],
        ];
    }

    return ld_preset_build([
        'name' => $name,
        'tagline' => $tagline,
        'hero_icon' => $heroIcon,
        'cta' => $cta,
        'services' => $services,
        'og_image' => $ogImage,
        'template' => $template,
        'ai_prompt' => $aiPrompt,
    ]);
}