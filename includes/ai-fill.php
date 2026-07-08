<?php
declare(strict_types=1);

require_once __DIR__ . '/ai-providers.php';

function ld_ai_fill_system_prompt(): string
{
    return <<<'PROMPT'
You are a professional landing page copywriter and SEO specialist for BILOHASH Lending CMS.
Return ONLY valid JSON (no markdown) matching this exact structure:
{
  "business": {
    "name": {"lt":"","uk":"","ru":"","en":""},
    "tagline": {"lt":"","uk":"","ru":"","en":""},
    "city": {"lt":"","uk":"","ru":"","en":""},
    "address": {"lt":"","uk":"","ru":"","en":""},
    "hours": {"lt":"","uk":"","ru":"","en":""},
    "phone": "+370...",
    "email": "info@example.com"
  },
  "seo": {
    "title": {"lt":"","uk":"","ru":"","en":""},
    "description": {"lt":"","uk":"","ru":"","en":""},
    "keywords": {"lt":"","uk":"","ru":"","en":""}
  },
  "hero": {
    "cta": {"lt":"","uk":"","ru":"","en":""},
    "cta2": {"lt":"","uk":"","ru":"","en":""},
    "visual_icon": "fa-star",
    "visual_label": {"lt":"","uk":"","ru":"","en":""},
    "visual_sub": {"lt":"","uk":"","ru":"","en":""}
  },
  "sections": {
    "services": {"title":{"lt":"","uk":"","ru":"","en":""},"lead":{"lt":"","uk":"","ru":"","en":""}},
    "team": {"title":{"lt":"","uk":"","ru":"","en":""},"lead":{"lt":"","uk":"","ru":"","en":""}},
    "faq": {"title":{"lt":"","uk":"","ru":"","en":""}},
    "contact": {"title":{"lt":"","uk":"","ru":"","en":""},"lead":{"lt":"","uk":"","ru":"","en":""}},
    "reviews": {"title":{"lt":"","uk":"","ru":"","en":""},"lead":{"lt":"","uk":"","ru":"","en":""}}
  },
  "stats": [{"value":"98%","label":{"lt":"","uk":"","ru":"","en":""}}],
  "services": [{"icon":"fa-star","name":{"lt":"","uk":"","ru":"","en":""},"desc":{"lt":"","uk":"","ru":"","en":""},"price":"100","badge":null}],
  "team": [{"name":"","role":{"lt":"","uk":"","ru":"","en":""},"years":"5","initials":"AB"}],
  "faq": [{"q":{"lt":"","uk":"","ru":"","en":""},"a":{"lt":"","uk":"","ru":"","en":""}}],
  "reviews": [{"author":"","rating":"5","date":"2026-01","text":{"lt":"","uk":"","ru":"","en":""}}],
  "google": {"rating":"4.9","review_count":"50"},
  "blocks": {
    "cta": {"enabled":true,"title":{"lt":"","uk":"","ru":"","en":""},"lead":{"lt":"","uk":"","ru":"","en":""}},
    "features": [{"icon":"fa-check","title":{"lt":"","uk":"","ru":"","en":""},"desc":{"lt":"","uk":"","ru":"","en":""}}]
  },
  "active_template": 5,
  "business_preset": "dentist"
}
Rules: realistic local business data; SEO keywords optimized for Google per country; 3-4 services, 3-4 team, 4 faq, 3 reviews, 4 stats; ALL 7 languages NO/SV/PL/EN/LT/UA/RU — each language targets its country (NO→Norway/Oslo, SV→Sweden/Stockholm, PL→Poland/Warsaw, EN→UK/London, LT→Lithuania/Vilnius, UA→Ukraine/Kyiv, RU→Russia/Moscow); mention city+country in SEO title/description per language; prices as strings without currency symbol; badge null or short string per language object.
Pick active_template 1-10 matching business vibe: dentist/medical=5 or 8, driving/auto=1 or 6, beauty=7, restaurant=2, fitness=3, law/corporate=10.
Set business_preset to one of: dentist, driving_school, auto_service, beauty_salon, restaurant, fitness_gym, law_office, medical_clinic.
PROMPT;
}

function ld_ai_fill_from_brief(string $brief, array $scopes = ['all']): array
{
    $brief = trim($brief);
    if ($brief === '') {
        return ['ok' => false, 'error' => 'empty_brief', 'data' => null, 'demo' => true];
    }

    $ai = ld_ai();
    $fillAll = in_array('all', $scopes, true);

    if (empty($ai['fill_enabled']) && empty($ai['enabled'])) {
        return ['ok' => false, 'error' => 'ai_disabled', 'data' => null, 'demo' => true];
    }

    $apiKey = trim((string) ($ai['api_key'] ?? ''));
    if ($apiKey === '') {
        $data = ld_ai_fill_demo($brief);
        return ['ok' => true, 'error' => '', 'data' => $data, 'demo' => true];
    }

    $scopeNote = $fillAll ? 'Fill ALL sections.' : ('Fill only: ' . implode(', ', $scopes));
    $user = $scopeNote . "\n\nBusiness brief:\n" . $brief;

    $result = ld_ai_call_api(
        $ai,
        ld_ai_fill_system_prompt(),
        $user,
        6000,
        true
    );

    if (!$result['ok']) {
        $data = ld_ai_fill_demo($brief);
        return ['ok' => true, 'error' => $result['error'], 'data' => $data, 'demo' => true];
    }

    $raw = trim($result['text']);
    if (preg_match('/```(?:json)?\s*([\s\S]*?)```/i', $raw, $m)) {
        $raw = trim($m[1]);
    }
    $parsed = json_decode($raw, true);
    if (!is_array($parsed)) {
        return ['ok' => false, 'error' => 'invalid_json', 'data' => null, 'demo' => false];
    }

    return ['ok' => true, 'error' => '', 'data' => $parsed, 'demo' => false];
}

function ld_ai_fill_demo(string $brief): array
{
    $name = mb_substr($brief, 0, 60);
    $i18n = static fn(string $lt, string $uk, string $en, ?string $ru = null) => ld_pi($lt, $uk, $en, $ru);
    $bizName = [
        'no' => $name, 'sv' => $name, 'pl' => $name, 'en' => $name, 'lt' => $name, 'uk' => $name, 'ru' => $name,
    ];

    return [
        'business' => [
            'name' => $bizName,
            'tagline' => $i18n('Profesionalios paslaugos jūsų mieste', 'Професійні послуги у вашому місті', 'Professional services in your city'),
            'city' => ld_country_field('city_full'),
            'address' => ld_country_field('address'),
            'hours' => $i18n('Pr–Pn 9:00–18:00', 'Пн–Пт 9:00–18:00', 'Mon–Fri 9:00–18:00', 'Пн–Пт 9:00–18:00', 'Man–fre 9:00–18:00', 'Mån–fre 9:00–18:00', 'Pon–pt 9:00–18:00'),
            'phone' => '+47 22 12 34 56',
            'email' => 'info@business.demo',
        ],
        'seo' => [
            'title' => ld_preset_country_seo_titles($bizName),
            'description' => ld_preset_country_seo_descriptions($bizName, [
                'lt' => 'Užsiregistruokite online. ' . $brief,
                'uk' => 'Запишіться онлайн. ' . $brief,
                'en' => 'Register online. ' . $brief,
                'ru' => 'Запишитесь онлайн. ' . $brief,
                'no' => 'Bestill online. ' . $brief,
                'sv' => 'Boka online. ' . $brief,
                'pl' => 'Zarezerwuj online. ' . $brief,
            ]),
            'keywords' => ld_preset_country_seo_keywords($bizName),
        ],
        'hero' => [
            'cta' => $i18n('Susisiekti', 'Зв\'язатися', 'Contact'),
            'cta2' => $i18n('Paslaugos', 'Послуги', 'Services'),
            'visual_icon' => 'fa-store',
            'visual_label' => $bizName,
            'visual_sub' => ld_country_field('city'),
        ],
        'sections' => [
            'services' => ['title' => $i18n('Paslaugos', 'Послуги', 'Services'), 'lead' => $i18n('Ką siūlome', 'Що пропонуємо', 'What we offer')],
            'team' => ['title' => $i18n('Komanda', 'Команда', 'Team'), 'lead' => $i18n('Mūsų specialistai', 'Наші фахівці', 'Our experts')],
            'faq' => ['title' => $i18n('DUK', 'FAQ', 'FAQ')],
            'contact' => ['title' => $i18n('Kontaktai', 'Контакт', 'Contact'), 'lead' => $i18n('Rašykite arba skambinkite', 'Пишіть або телефонуйте', 'Write or call us')],
            'reviews' => ['title' => $i18n('Atsiliepimai', 'Відгуки', 'Reviews'), 'lead' => $i18n('Klientų nuomonės', 'Думки клієнтів', 'Client feedback')],
        ],
        'stats' => [
            ['value' => '500+', 'label' => $i18n('Klientų', 'Клієнтів', 'Clients')],
            ['value' => '4.9', 'label' => $i18n('Google reitingas', 'Рейтинг Google', 'Google rating')],
        ],
        'services' => [
            ['icon' => 'fa-star', 'name' => $i18n('Pagrindinė paslauga', 'Основна послуга', 'Main service'), 'desc' => $i18n($brief, $brief, $brief), 'price' => '99', 'badge' => null],
        ],
        'team' => [
            ['name' => 'Admin', 'role' => $i18n('Vadovas', 'Керівник', 'Manager'), 'years' => '5', 'initials' => 'AD'],
        ],
        'faq' => [
            ['q' => $i18n('Kaip užsiregistruoti?', 'Як записатися?', 'How to register?'), 'a' => $i18n('Užpildykite formą.', 'Заповніть форму.', 'Fill the form.')],
        ],
        'reviews' => [
            ['author' => 'Client', 'rating' => '5', 'date' => '2026-01', 'text' => $i18n('Puiku!', 'Чудово!', 'Great!')],
        ],
        'google' => ['rating' => '4.9', 'review_count' => '50'],
    ];
}

function ld_ai_apply_fill(array $generated, array $scopes = ['all']): array
{
    $settings = ld_settings();
    $fillAll = in_array('all', $scopes, true);

    if ($fillAll || in_array('business', $scopes, true)) {
        if (!empty($generated['business']) && is_array($generated['business'])) {
            $settings['business'] = array_replace_recursive($settings['business'] ?? [], $generated['business']);
        }
    }

    if ($fillAll || in_array('seo', $scopes, true)) {
        if (!empty($generated['seo']) && is_array($generated['seo'])) {
            $settings['seo'] = array_replace_recursive($settings['seo'] ?? [], $generated['seo']);
        }
    }

    if ($fillAll || in_array('content', $scopes, true)) {
        foreach (['hero', 'sections', 'stats', 'services', 'team', 'faq', 'reviews'] as $key) {
            if (!empty($generated[$key])) {
                $settings[$key] = $generated[$key];
            }
        }
        if (!empty($generated['google']) && is_array($generated['google'])) {
            $settings['google'] = array_replace_recursive($settings['google'] ?? [], $generated['google']);
        }
        if (!empty($generated['blocks']) && is_array($generated['blocks'])) {
            $settings['blocks'] = array_replace_recursive($settings['blocks'] ?? [], $generated['blocks']);
            if (!empty($settings['blocks']['gallery']) === false) {
                $settings['blocks']['gallery'] = ld_default_settings()['blocks']['gallery'] ?? [];
            }
        }
    }

    if ($fillAll && !empty($generated['active_template'])) {
        $tpl = max(1, min(10, (int) $generated['active_template']));
        $settings['active_template'] = $tpl;
    }
    if ($fillAll && !empty($generated['business_preset'])) {
        $presetId = (string) $generated['business_preset'];
        if (ld_business_preset($presetId) !== null) {
            $settings['business_preset'] = $presetId;
        }
    }

    $ok = ld_save_settings($settings);
    return ['ok' => $ok, 'settings' => $settings];
}