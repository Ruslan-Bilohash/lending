<?php
declare(strict_types=1);

require_once __DIR__ . '/business-presets-build.php';
require_once __DIR__ . '/business-presets-catalog.php';

/** @return array{no:string,sv:string,pl:string,lt:string,uk:string,en:string,ru:string} */
function ld_pi(
    string $lt,
    string $uk,
    string $en,
    ?string $ru = null,
    ?string $no = null,
    ?string $sv = null,
    ?string $pl = null
): array {
    $ru = $ru ?? $en;
    return [
        'no' => $no ?? $en,
        'sv' => $sv ?? $en,
        'pl' => $pl ?? $en,
        'lt' => $lt,
        'uk' => $uk,
        'en' => $en,
        'ru' => $ru,
    ];
}

function ld_business_preset_id(): string
{
    return (string) (ld_settings()['business_preset'] ?? 'driving_school');
}

function ld_business_presets(): array
{
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }

    $presets = [
        'dentist' => ld_preset_dentist(),
        'driving_school' => ld_preset_driving_school(),
        'auto_service' => ld_preset_auto_service(),
        'beauty_salon' => ld_preset_beauty_salon(),
        'restaurant' => ld_preset_restaurant(),
        'fitness_gym' => ld_preset_fitness_gym(),
        'law_office' => ld_preset_law_office(),
        'medical_clinic' => ld_preset_medical_clinic(),
        'real_estate' => ld_preset_real_estate(),
        'accounting' => ld_preset_accounting(),
        'cleaning' => ld_preset_cleaning(),
        'veterinary' => ld_preset_veterinary(),
        'photography' => ld_preset_photography(),
        'construction' => ld_preset_construction(),
        'hotel' => ld_preset_hotel(),
        'kindergarten' => ld_preset_kindergarten(),
        'pharmacy' => ld_preset_pharmacy(),
        'barbershop' => ld_preset_barbershop(),
    ];

    $cache = $presets;
    return $presets;
}

function ld_business_preset(string $id): ?array
{
    return ld_business_presets()[$id] ?? null;
}

function ld_apply_business_preset(string $id): array
{
    $preset = ld_business_preset($id);
    if ($preset === null) {
        return ['ok' => false, 'error' => 'unknown_preset'];
    }

    $settings = ld_settings();
    $keepAiKey = trim((string) ($settings['ai']['api_key'] ?? ''));
    $keepRecaptcha = $settings['recaptcha'] ?? [];
    $keepIntegrations = $settings['integrations'] ?? [];

    $patch = $preset['settings'] ?? [];
    foreach (['business', 'hero', 'sections', 'stats', 'services', 'team', 'faq', 'reviews', 'google', 'seo', 'blocks'] as $key) {
        if (isset($patch[$key])) {
            $settings[$key] = $patch[$key];
        }
    }

    if (isset($patch['currency'])) {
        $settings['currency'] = $patch['currency'];
    }

    $settings['active_template'] = (int) ($preset['template'] ?? $settings['active_template'] ?? 1);
    $settings['business_preset'] = $id;

    if (!empty($patch['ai']) && is_array($patch['ai'])) {
        $settings['ai'] = array_replace_recursive($settings['ai'] ?? [], $patch['ai']);
    }
    if ($keepAiKey !== '') {
        $settings['ai']['api_key'] = $keepAiKey;
    }

    $settings['recaptcha'] = array_replace_recursive($settings['recaptcha'] ?? [], $keepRecaptcha);
    $settings['integrations'] = array_replace_recursive($settings['integrations'] ?? [], $keepIntegrations);

    $ok = ld_save_settings($settings);
    return ['ok' => $ok, 'preset' => $id, 'template' => $settings['active_template']];
}

function ld_preset_dentist(): array
{
    return [
        'id' => 'dentist',
        'icon' => 'fa-tooth',
        'template' => 5,
        'brief' => 'Stomatologijos klinika Vilniuje: implantai, balinimas, vaikų stomatologija, skubi pagalba. LT/UA/EN.',
        'label' => ld_pi('Stomatologija', 'Стоматологія', 'Dentistry'),
        'desc' => ld_pi('Įrašymas pas dantų gydytoją, implantai, higiena', 'Запис до стоматолога, імплантація, гігієна', 'Dentist booking, implants, hygiene'),
        'settings' => [
            'currency' => '€',
            'business' => [
                'name' => ld_pi('SmileLine Stomatologija', 'SmileLine Стоматологія', 'SmileLine Dentistry'),
                'tagline' => ld_pi(
                    'Implantai · balinimas · vaikų stomatologija — be skausmo, 3 kalbomis',
                    'Імплантація · відбілювання · дитяча стоматологія — без болю, 3 мови',
                    'Implants · whitening · kids dentistry — painless, 3 languages'
                ),
                'city' => ld_pi('Vilnius, Lietuva', 'Вільнюс, Литва', 'Vilnius, Lithuania'),
                'address' => ld_pi('Gedimino pr. 28, LT-01104 Vilnius', 'пр. Гедиміно 28, LT-01104 Вільнюс', 'Gedimino av. 28, LT-01104 Vilnius'),
                'phone' => '+370 612 77889',
                'email' => 'info@smileline.demo',
                'hours' => ld_pi('Pr–Pn 8:00–20:00 · Št 9:00–15:00', 'Пн–Пт 8:00–20:00 · Сб 9:00–15:00', 'Mon–Fri 8:00–20:00 · Sat 9:00–15:00'),
            ],
            'hero' => [
                'cta' => ld_pi('Užsiregistruoti', 'Записатися', 'Book appointment'),
                'cta2' => ld_pi('Paslaugos', 'Послуги', 'Services'),
                'visual_icon' => 'fa-tooth',
                'visual_label' => ld_pi('Be skausmo', 'Без болю', 'Painless'),
                'visual_sub' => ld_pi('Vilnius', 'Вільнюс', 'Vilnius'),
            ],
            'sections' => [
                'services' => ['title' => ld_pi('Stomatologijos paslaugos', 'Стоматологічні послуги', 'Dental services'), 'lead' => ld_pi('Visapusiška burnos priežiūra šeimai.', 'Комплексний догляд за зубами для сім\'ї.', 'Complete dental care for your family.')],
                'team' => ['title' => ld_pi('Gydytojai', 'Лікарі', 'Our dentists'), 'lead' => ld_pi('Patyrę specialistai — LT, UA, EN.', 'Досвідчені лікарі — LT, UA, EN.', 'Experienced specialists — LT, UA, EN.')],
                'faq' => ['title' => ld_pi('Dažniausi klausimai', 'Часті питання', 'FAQ')],
                'contact' => ['title' => ld_pi('Užrašymas', 'Запис', 'Book a visit'), 'lead' => ld_pi('Užpildykite formą — paskambinsime per 15 min.', 'Заповніть форму — передзвонимо за 15 хв.', 'Fill the form — we call back within 15 min.')],
                'reviews' => ['title' => ld_pi('Pacientų atsiliepimai', 'Відгуки пацієнтів', 'Patient reviews'), 'lead' => ld_pi('Google Maps atsiliepimai.', 'Відгуки в Google Maps.', 'Google Maps reviews.')],
                'map' => ['title' => ld_pi('Klinikos adresas', 'Адреса клініки', 'Clinic location')],
                'features' => ['title' => ld_pi('Kodėl SmileLine', 'Чому SmileLine', 'Why SmileLine')],
                'gallery' => ['title' => ld_pi('Klinika', 'Клініка', 'Our clinic')],
            ],
            'stats' => [
                ['value' => '12 000+', 'label' => ld_pi('Laimingų pacientų', 'Задоволених пацієнтів', 'Happy patients')],
                ['value' => '15', 'label' => ld_pi('Metų patirties', 'Років досвіду', 'Years experience')],
                ['value' => '4.9', 'label' => ld_pi('Google reitingas', 'Рейтинг Google', 'Google rating')],
                ['value' => '24/7', 'label' => ld_pi('Skubi pagalba', 'Термінова допомога', 'Emergency care')],
            ],
            'services' => [
                ['icon' => 'fa-tooth', 'name' => ld_pi('Profesionali higiena', 'Професійна гігієна', 'Professional hygiene'), 'desc' => ld_pi('Ultragarsinis valymas ir fluoravimas.', 'Ультразвукове чищення та фторування.', 'Ultrasonic cleaning and fluoride treatment.'), 'price' => '65', 'badge' => ld_pi('Populiaru', 'Популярно', 'Popular')],
                ['icon' => 'fa-sun', 'name' => ld_pi('Balinimas', 'Відбілювання', 'Whitening'), 'desc' => ld_pi('LED balinimas — rezultatas per 1 vizitą.', 'LED-відбілювання — результат за 1 візит.', 'LED whitening — results in one visit.'), 'price' => '180', 'badge' => null],
                ['icon' => 'fa-screwdriver-wrench', 'name' => ld_pi('Implantai', 'Імплантація', 'Dental implants'), 'desc' => ld_pi('Titano implantai su garantija 10 m.', 'Титанові імпланти з гарантією 10 років.', 'Titanium implants with 10-year warranty.'), 'price' => '890', 'badge' => ld_pi('Premium', 'Преміум', 'Premium')],
                ['icon' => 'fa-child', 'name' => ld_pi('Vaikų stomatologija', 'Дитяча стоматологія', 'Kids dentistry'), 'desc' => ld_pi('Švelni priežiūra vaikams nuo 3 metų.', 'М\'який догляд для дітей від 3 років.', 'Gentle care for children from age 3.'), 'price' => '45', 'badge' => null],
            ],
            'team' => [
                ['name' => 'Dr. Eglė Vaitkutė', 'role' => ld_pi('Implantologė', 'Імплантолог', 'Implantologist'), 'years' => '12', 'initials' => 'EV'],
                ['name' => 'Dr. Olena Moroz', 'role' => ld_pi('Terapeutė (UA/LT)', 'Терапевт (UA/LT)', 'General dentist (UA/LT)'), 'years' => '9', 'initials' => 'OM'],
                ['name' => 'Dr. James Cole', 'role' => ld_pi('Chirurgas (EN)', 'Хірург (EN)', 'Surgeon (EN)'), 'years' => '7', 'initials' => 'JC'],
            ],
            'faq' => [
                ['q' => ld_pi('Ar skausminga implantacija?', 'Чи болісна імплантація?', 'Is implant surgery painful?'), 'a' => ld_pi('Naudojame vietinę nejautrą — procedūra be skausmo.', 'Локальна анестезія — процедура без болю.', 'Local anesthesia — painless procedure.')],
                ['q' => ld_pi('Kaip užsiregistruoti?', 'Як записатися?', 'How to book?'), 'a' => ld_pi('Forma svetainėje arba telefonu +370 612 77889.', 'Форма на сайті або телефон +370 612 77889.', 'Use the form or call +370 612 77889.')],
                ['q' => ld_pi('Ar priimate vaikus?', 'Чи приймаєте дітей?', 'Do you treat children?'), 'a' => ld_pi('Taip — vaikų kabinetas su žaidimų zona.', 'Так — дитячий кабінет із ігровою зоною.', 'Yes — kids room with play area.')],
            ],
            'reviews' => [
                ['author' => 'Inga M.', 'rating' => '5', 'date' => '2025-11', 'text' => ld_pi('Implantas be skausmo — rekomenduoju Dr. Eglę!', 'Імплант без болю — рекомендую Dr. Eglę!', 'Painless implant — recommend Dr. Eglė!')],
                ['author' => 'Andrii K.', 'rating' => '5', 'date' => '2025-08', 'text' => ld_pi('Gydytoja kalba ukrainiečių — labai patogu.', 'Лікар говорить українською — дуже зручно.', 'Doctor speaks Ukrainian — very convenient.')],
            ],
            'google' => ['maps_embed' => '', 'maps_link' => '', 'reviews_url' => 'https://www.google.com/maps/search/?api=1&query=Gedimino+pr.+28+Vilnius', 'rating' => '4.9', 'review_count' => '214'],
            'seo' => [
                'title' => ld_pi('SmileLine stomatologija Vilniuje — implantai, užrašymas', 'SmileLine стоматологія Вільнюс — імпланти, запис', 'SmileLine dentistry Vilnius — implants, booking'),
                'description' => ld_pi('Stomatologijos klinika Vilniuje: implantai, balinimas, vaikų gydymas. Užsiregistruokite online.', 'Стоматологія у Вільнюсі: імпланти, відбілювання, дитяче лікування. Запис онлайн.', 'Dental clinic in Vilnius: implants, whitening, kids care. Book online.'),
                'keywords' => ld_pi('stomatologija, implantai, dantų gydytojas, Vilnius, užrašymas', 'стоматологія, імпланти, стоматолог, Вільнюс, запис', 'dentist, implants, dental clinic, Vilnius, booking'),
                'og_image' => 'https://images.unsplash.com/photo-1629909613654-28e377c37b09?w=1200&h=630&fit=crop',
            ],
            'blocks' => [
                'hero_image' => 'https://images.unsplash.com/photo-1629909613654-28e377c37b09?w=900&h=600&fit=crop',
                'gallery' => [
                    ['url' => 'https://images.unsplash.com/photo-1606811841689-23dfddce3e95?w=800&h=600&fit=crop', 'caption' => ld_pi('Kabinetas', 'Кабінет', 'Treatment room')],
                    ['url' => 'https://images.unsplash.com/photo-1588776814546-1ffcf47267a5?w=800&h=600&fit=crop', 'caption' => ld_pi('Reception', 'Рецепція', 'Reception')],
                ],
                'features' => [
                    ['icon' => 'fa-syringe', 'title' => ld_pi('Be skausmo', 'Без болю', 'Painless'), 'desc' => ld_pi('Moderni nejautra ir švelnūs metodai.', 'Сучасна анестезія та м\'які методи.', 'Modern anesthesia and gentle methods.')],
                    ['icon' => 'fa-language', 'title' => ld_pi('LT · UA · EN', 'LT · UA · EN', 'LT · UA · EN'), 'desc' => ld_pi('Komanda kalba 3 kalbomis.', 'Команда говорить 3 мовами.', 'Team speaks 3 languages.')],
                    ['icon' => 'fa-calendar-check', 'title' => ld_pi('Online užrašymas', 'Онлайн запис', 'Online booking'), 'desc' => ld_pi('Registracija per 1 minutę.', 'Запис за 1 хвилину.', 'Register in 1 minute.')],
                ],
                'cta' => ['enabled' => true, 'title' => ld_pi('Skubus dantų skausmas?', 'Гострий зубний біль?', 'Toothache emergency?'), 'lead' => ld_pi('Skambinkite — priimsime šiandien.', 'Телефонуйте — приймемо сьогодні.', 'Call us — same-day appointment.'), 'phone' => ''],
            ],
            'ai' => [
                'welcome' => ld_pi('Labas! Padėsiu užsiregistruoti pas dantų gydytoją ir atsakysiu apie kainas.', 'Привіт! Допоможу записатися до стоматолога та відповім про ціни.', 'Hi! I can help you book a dentist appointment and answer about prices.'),
                'system_prompt' => 'You are a dental clinic assistant for {business_name} in {city}. Help with appointment booking, services (hygiene, whitening, implants, kids), prices and hours. Language: {lang}. Plain text only.',
            ],
        ],
    ];
}

function ld_preset_driving_school(): array
{
    $defaults = ld_default_settings();
    return [
        'id' => 'driving_school',
        'icon' => 'fa-car-side',
        'template' => 8,
        'brief' => 'Vairavimo mokykla Vilniuje: B, BE kategorijos, teorija, intensyvus kursas. LT/UA/RU/EN.',
        'label' => ld_pi('Vairavimo mokykla', 'Автошкола', 'Driving school'),
        'desc' => ld_pi('B ir BE kategorijos, Regitra pasiruošimas', 'Категорія B і BE, підготовка до Regitra', 'Category B & BE, Regitra prep'),
        'settings' => [
            'currency' => $defaults['currency'],
            'business' => $defaults['business'],
            'hero' => $defaults['hero'],
            'sections' => $defaults['sections'],
            'stats' => $defaults['stats'],
            'services' => $defaults['services'],
            'team' => $defaults['team'],
            'faq' => $defaults['faq'],
            'reviews' => $defaults['reviews'],
            'google' => $defaults['google'],
            'seo' => $defaults['seo'],
            'blocks' => $defaults['blocks'],
            'ai' => [
                'welcome' => $defaults['ai']['welcome'],
                'system_prompt' => $defaults['ai']['system_prompt'],
            ],
        ],
    ];
}

function ld_recommended_template_for_preset(string $presetId): int
{
    $preset = ld_business_preset($presetId);
    return (int) ($preset['template'] ?? 1);
}