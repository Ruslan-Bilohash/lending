<?php
declare(strict_types=1);

/** @return array<string, array{icon:string,template:int,brief:string,label:array,desc:array,build:array}> */
function ld_presets_catalog_data(): array
{
    static $data = null;
    if ($data !== null) {
        return $data;
    }

    $faqBook = static fn(string $phone = '+47 22 12 34 56'): array => [
        ['q' => ld_pi('Kaip užsiregistruoti?', 'Як записатися?', 'How to book?', null, 'Hvordan bestille?', 'Hur bokar man?', 'Jak się zapisać?'), 'a' => ld_pi('Forma svetainėje arba tel. ' . $phone, 'Форма на сайті або тел. ' . $phone, 'Use the form or call ' . $phone, null, 'Bruk skjemaet eller ring ' . $phone, 'Använd formuläret eller ring ' . $phone, 'Formularz na stronie lub tel. ' . $phone)],
        ['q' => ld_pi('Kokios kalbos?', 'Які мови?', 'Languages?', null, 'Hvilke språk?', 'Vilka språk?', 'Jakie języki?'), 'a' => ld_pi('NO, SV, PL, EN, LT, UA, RU.', 'NO, SV, PL, EN, LT, UA, RU.', 'NO, SV, PL, EN, LT, UA, RU.', null, 'NO, SV, PL, EN, LT, UA, RU.', 'NO, SV, PL, EN, LT, UA, RU.', 'NO, SV, PL, EN, LT, UA, RU.')],
        ['q' => ld_pi('Kur esate?', 'Де ви знаходитесь?', 'Where are you?', null, 'Hvor holder dere til?', 'Var finns ni?', 'Gdzie jesteście?'), 'a' => ld_pi('Oslo, Karl Johans gate 15.', 'Осло, Karl Johans gate 15.', 'Oslo, Karl Johans gate 15.', null, 'Oslo, Karl Johans gate 15.', 'Oslo, Karl Johans gate 15.', 'Oslo, Karl Johans gate 15.')],
    ];

    $data = [
        'auto_service' => [
            'icon' => 'fa-wrench', 'template' => 6,
            'brief' => 'Autoservisas Vilniuje: diagnostika, tepalai, stabdžiai, padangos. LT/UA/RU/EN.',
            'label' => ld_pi('Autoservisas', 'Автосервіс', 'Auto service'),
            'desc' => ld_pi('Diagnostika, remontas, padangos', 'Діагностика, ремонт, шини', 'Diagnostics, repair, tyres'),
            'build' => [
                'name' => ld_pi('CarFix Autoservisas', 'CarFix Автосервіс', 'CarFix Auto Service'),
                'tagline' => ld_pi('Diagnostika · tepalai · stabdžiai · padangos — greitai ir sąžiningai', 'Діагностика · масло · гальма · шини — швидко й чесно', 'Diagnostics · oil · brakes · tyres — fast & fair'),
                'hero_icon' => 'fa-wrench', 'cta' => ld_pi('Užsakyti vizitą', 'Записатися', 'Book service'), 'cta2' => ld_pi('Kainos', 'Ціни', 'Prices'),
                'phone' => '+370 612 44556', 'email' => 'info@carfix.demo', 'template' => 6,
                'og_image' => 'https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?w=1200&h=630&fit=crop',
                'ai_prompt' => 'Auto service assistant for {business_name} in {city}. Help with booking, diagnostics, oil change, brakes, tyres, prices, same-day slots. Language: {lang}. Plain text only.',
                'section_services_title' => ld_pi('Autoserviso paslaugos', 'Послуги автосервісу', 'Auto service menu'),
                'services' => [
                    ['icon' => 'fa-microchip', 'name' => ld_pi('Kompiuterinė diagnostika', 'Комп\'ютерна діагностика', 'Computer diagnostics'), 'desc' => ld_pi('Visoms markėms per 30 min.', 'Для всіх марок за 30 хв.', 'All brands in 30 min.'), 'price' => '35'],
                    ['icon' => 'fa-oil-can', 'name' => ld_pi('Tepalų keitimas', 'Заміна масла', 'Oil change'), 'desc' => ld_pi('Originalūs filtrai ir alyva.', 'Оригінальні фільтри та олива.', 'OEM filters and oil.'), 'price' => '49'],
                    ['icon' => 'fa-circle-stop', 'name' => ld_pi('Stabdžių sistema', 'Гальмівна система', 'Brake service'), 'desc' => ld_pi('Kaladėlių keitimas ir skysčio papildymas.', 'Заміна колодок і рідини.', 'Pads and fluid service.'), 'price' => '79'],
                    ['icon' => 'fa-circle', 'name' => ld_pi('Padangų montavimas', 'Шиномонтаж', 'Tyre fitting'), 'desc' => ld_pi('Sezoninis balansavimas ir saugojimas.', 'Сезонний баланс і зберігання.', 'Seasonal balance & storage.'), 'price' => '25'],
                ],
                'team' => [
                    ['name' => 'Tomas Žukauskas', 'role' => ld_pi('Autoserviso vadovas', 'Керівник автосервісу', 'Service manager'), 'years' => '12', 'initials' => 'TŽ'],
                    ['name' => 'Andrii Melnyk', 'role' => ld_pi('Diagnostikas (UA/LT)', 'Діагност (UA/LT)', 'Diagnostics (UA/LT)'), 'years' => '8', 'initials' => 'AM'],
                ],
                'faq' => [
                    ['q' => ld_pi('Kiek trunka diagnostika?', 'Скільки триває діагностика?', 'How long is diagnostics?'), 'a' => ld_pi('Standartinė diagnostika — 30–45 min.', 'Стандартна діагностика — 30–45 хв.', 'Standard diagnostics — 30–45 min.')],
                    ['q' => ld_pi('Ar darote sezonišką padangų keitimą?', 'Чи робите сезонну заміну шин?', 'Seasonal tyre change?'), 'a' => ld_pi('Taip — montavimas, balansavimas ir saugojimas.', 'Так — монтаж, баланс і зберігання.', 'Yes — fitting, balance and storage.')],
                    ['q' => ld_pi('Ar suteikiate garantiją?', 'Чи даєте гарантію?', 'Warranty?'), 'a' => ld_pi('Taip — 6 mėn. darbams ir detalėms.', 'Так — 6 міс. на роботи та деталі.', 'Yes — 6 months on labour and parts.')],
                ],
                'stats' => [
                    ['value' => '8 500+', 'label' => ld_pi('Remontų', 'Ремонтів', 'Repairs')],
                    ['value' => '4.9', 'label' => ld_pi('Google reitingas', 'Рейтинг Google', 'Google rating')],
                    ['value' => '45 min', 'label' => ld_pi('Diagnostika', 'Діагностика', 'Diagnostics')],
                    ['value' => '12 m.', 'label' => ld_pi('Patirtis', 'Досвід', 'Experience')],
                ],
            ],
        ],
        'beauty_salon' => [
            'icon' => 'fa-spa', 'template' => 7,
            'brief' => 'Grožio salonas: kirpimas, manikiūras, kosmetologija, masažas. LT/UA/RU/EN.',
            'label' => ld_pi('Grožio salonas', 'Салон краси', 'Beauty salon'),
            'desc' => ld_pi('Kirpimas, manikiūras, kosmetologija', 'Стрижка, манікюр, косметологія', 'Hair, nails, cosmetology'),
            'build' => [
                'name' => ld_pi('Glow Studio', 'Glow Studio', 'Glow Studio'),
                'tagline' => ld_pi('Kirpimas · manikiūras · kosmetologija · masažas — jūsų grožio erdvė', 'Стрижка · манікюр · косметологія · масаж — простір краси', 'Hair · nails · cosmetology · massage — your beauty space'),
                'hero_icon' => 'fa-spa', 'cta' => ld_pi('Rezervuoti', 'Забронювати', 'Reserve'), 'phone' => '+370 698 22110', 'email' => 'hello@glowstudio.demo', 'template' => 7,
                'og_image' => 'https://images.unsplash.com/photo-1560066984-138d7434eb37?w=1200&h=630&fit=crop',
                'ai_prompt' => 'Beauty salon assistant for {business_name} in {city}. Help with booking hair, nails, facials, massage, prices and stylists. Language: {lang}. Plain text only.',
                'services' => [
                    ['icon' => 'fa-scissors', 'name' => ld_pi('Kirpimas ir dažymas', 'Стрижка та фарбування', 'Cut & colour'), 'desc' => ld_pi('Stilistės konsultacija įskaičiuota.', 'Консультація стиліста включена.', 'Stylist consultation included.'), 'price' => '45'],
                    ['icon' => 'fa-hand-sparkles', 'name' => ld_pi('Manikiūras', 'Манікюр', 'Manicure'), 'desc' => ld_pi('Gelinis lakavimas iki 3 savaičių.', 'Гель-лак до 3 тижнів.', 'Gel polish up to 3 weeks.'), 'price' => '28'],
                    ['icon' => 'fa-face-smile', 'name' => ld_pi('Veido procedūros', 'Процедури для обличчя', 'Facial treatments'), 'desc' => ld_pi('Profesionali kosmetologija ir rūpinimasis oda.', 'Професійна косметологія.', 'Professional skincare.'), 'price' => '55'],
                    ['icon' => 'fa-hot-tub-person', 'name' => ld_pi('Masažas', 'Масаж', 'Massage'), 'desc' => ld_pi('Atpalaiduojantis arba sportinis masažas.', 'Розслаблюючий або спортивний масаж.', 'Relaxing or sports massage.'), 'price' => '40'],
                ],
                'team' => [
                    ['name' => 'Gabija Laurinaitė', 'role' => ld_pi('Stilistė', 'Стиліст', 'Hair stylist'), 'years' => '9', 'initials' => 'GL'],
                    ['name' => 'Yulia Petrenko', 'role' => ld_pi('Kosmetologė (UA/LT)', 'Косметолог (UA/LT)', 'Cosmetologist (UA/LT)'), 'years' => '6', 'initials' => 'YP'],
                ],
                'faq' => [
                    ['q' => ld_pi('Ar reikia depozito rezervacijai?', 'Чи потрібен депозит?', 'Deposit required?'), 'a' => ld_pi('Ne — rezervacija nemokama.', 'Ні — бронювання безкоштовне.', 'No — booking is free.')],
                    ['q' => ld_pi('Kiek trunka vizitas?', 'Скільки триває візит?', 'Visit duration?'), 'a' => ld_pi('Manikiūras ~60 min, kirpimas ~90 min.', 'Манікюр ~60 хв, стрижка ~90 хв.', 'Manicure ~60 min, haircut ~90 min.')],
                    ['q' => ld_pi('Ar naudojate hipoalerginius produktus?', 'Гіпоалергенні продукти?', 'Hypoallergenic products?'), 'a' => ld_pi('Taip — turime jautriai odai.', 'Так — для чутливої шкіри.', 'Yes — for sensitive skin.')],
                ],
            ],
        ],
        'restaurant' => [
            'icon' => 'fa-utensils', 'template' => 2,
            'brief' => 'Restoranas Vilniuje: lietuviška virtuvė, rezervacijos, banketai. LT/UA/RU/EN.',
            'label' => ld_pi('Restoranas', 'Ресторан', 'Restaurant'),
            'desc' => ld_pi('Rezervacijos, banketai, lietuviška virtuvė', 'Бронювання, банкети, литовська кухня', 'Reservations, events, Lithuanian cuisine'),
            'build' => [
                'name' => ld_pi('Taste Vilnius', 'Taste Vilnius', 'Taste Vilnius'),
                'tagline' => ld_pi('Lietuviška virtuvė · degustacijos · banketai — senamiestyje', 'Литовська кухня · дегустації · банкети — Старе місто', 'Lithuanian cuisine · tastings · events — old town'),
                'hero_icon' => 'fa-utensils', 'cta' => ld_pi('Rezervuoti stalą', 'Забронювати стіл', 'Reserve a table'), 'phone' => '+370 5 212 3344', 'email' => 'book@tastevilnius.demo', 'template' => 2,
                'address' => ld_pi('Pilies g. 16, LT-01123 Vilnius', 'вул. Пілеc 16, LT-01123 Вільнюс', 'Pilies st. 16, LT-01123 Vilnius'),
                'og_image' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=1200&h=630&fit=crop',
                'ai_prompt' => 'Restaurant assistant for {business_name} in {city}. Help with table reservations, menu, tasting menu, events, dietary options, hours. Language: {lang}. Plain text only.',
                'services' => [
                    ['icon' => 'fa-bowl-food', 'name' => ld_pi('Degustacinis meniu', 'Дегустаційне меню', 'Tasting menu'), 'desc' => ld_pi('5 patiekalų autorinė programa.', '5 страв авторського меню.', '5-course chef menu.'), 'price' => '38'],
                    ['icon' => 'fa-champagne-glasses', 'name' => ld_pi('Banketai', 'Банкети', 'Private events'), 'desc' => ld_pi('Iki 80 svečių — individualus meniu.', 'До 80 гостей — індивідуальне меню.', 'Up to 80 guests — custom menu.'), 'price' => '450'],
                    ['icon' => 'fa-mug-hot', 'name' => ld_pi('Verslo pietūs', 'Бізнес-ланч', 'Business lunch'), 'desc' => ld_pi('Pr–Pn 11:30–15:00.', 'Пн–Пт 11:30–15:00.', 'Mon–Fri 11:30–15:00.'), 'price' => '14'],
                    ['icon' => 'fa-wine-glass', 'name' => ld_pi('Vyno degustacija', 'Дегустація вина', 'Wine tasting'), 'desc' => ld_pi('Lietuviški ir europiniai vynai.', 'Литовські та європейські вина.', 'Local and European wines.'), 'price' => '22'],
                ],
                'faq' => [
                    ['q' => ld_pi('Ar turite vegetariškų patiekalų?', 'Є вегетаріанські страви?', 'Vegetarian options?'), 'a' => ld_pi('Taip — pažymėta meniu kortelėje.', 'Так — позначено в меню.', 'Yes — marked on the menu.')],
                    ['q' => ld_pi('Kaip rezervuoti banketą?', 'Як забронювати банкет?', 'How to book an event?'), 'a' => ld_pi('Skambinkite arba užpildykite formą — paruošime pasiūlymą.', 'Телефонуйте або форма — надішлемо пропозицію.', 'Call or use the form — we send a quote.')],
                    ['q' => ld_pi('Ar yra vaikų kėdutės?', 'Є дитячі стільчики?', 'High chairs?'), 'a' => ld_pi('Taip — praneškite rezervuojant.', 'Так — повідомте при бронюванні.', 'Yes — mention when booking.')],
                ],
            ],
        ],
        'fitness_gym' => [
            'icon' => 'fa-dumbbell', 'template' => 3,
            'brief' => 'Fitneso klubas: abonementai, grupės, personalinis treneris. LT/UA/RU/EN.',
            'label' => ld_pi('Fitneso klubas', 'Фітнес-клуб', 'Fitness gym'),
            'desc' => ld_pi('Abonementai, grupės, PT', 'Абонементи, групи, PT', 'Memberships, classes, PT'),
            'build' => [
                'name' => ld_pi('FitZone Klubas', 'FitZone Клуб', 'FitZone Gym'),
                'tagline' => ld_pi('Treniruokliai · grupės · PT — 24/7 prieiga', 'Тренажери · групи · PT — доступ 24/7', 'Equipment · classes · PT — 24/7 access'),
                'hero_icon' => 'fa-dumbbell', 'cta' => ld_pi('Gauti abonementą', 'Оформити абонемент', 'Get membership'), 'phone' => '+370 655 88990', 'email' => 'join@fitzone.demo', 'template' => 3,
                'hours' => ld_pi('24/7 nariams', '24/7 для членів', '24/7 for members'),
                'og_image' => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=1200&h=630&fit=crop',
                'ai_prompt' => 'Fitness gym assistant for {business_name} in {city}. Help with memberships, group classes, personal training, trial visit, prices. Language: {lang}. Plain text only.',
                'services' => [
                    ['icon' => 'fa-id-card', 'name' => ld_pi('Mėnesio abonementas', 'Місячний абонемент', 'Monthly pass'), 'desc' => ld_pi('Visi treniruokliai ir grupės.', 'Усі тренажери та групи.', 'All equipment and classes.'), 'price' => '39'],
                    ['icon' => 'fa-users', 'name' => ld_pi('Grupiniai užsiėmimai', 'Групові заняття', 'Group classes'), 'desc' => ld_pi('YOGA, HIIT, pilates — dienoraštis online.', 'YOGA, HIIT, пілатес — розклад online.', 'YOGA, HIIT, pilates — schedule online.'), 'price' => '8'],
                    ['icon' => 'fa-person-running', 'name' => ld_pi('Personalinis treneris', 'Персональний тренер', 'Personal training'), 'desc' => ld_pi('Individualus planas ir mitybos patarimai.', 'Індивідуальний план і харчування.', 'Custom plan and nutrition tips.'), 'price' => '35'],
                    ['icon' => 'fa-spa', 'name' => ld_pi('Sauna ir atsistatymas', 'Сауна та відновлення', 'Sauna & recovery'), 'desc' => ld_pi('Įskaičiuota į Premium abonementą.', 'Включено в Premium.', 'Included in Premium membership.'), 'price' => '0'],
                ],
            ],
        ],
        'law_office' => [
            'icon' => 'fa-scale-balanced', 'template' => 10,
            'brief' => 'Advokatų kontora: verslo teisė, migracija, NT. LT/UA/RU/EN.',
            'label' => ld_pi('Advokatų kontora', 'Адвокатська контора', 'Law office'),
            'desc' => ld_pi('Verslo teisė, migracija, NT', 'Бізнес-право, міграція, нерухомість', 'Business law, migration, real estate'),
            'build' => [
                'name' => ld_pi('LexPro Teisė', 'LexPro Право', 'LexPro Law'),
                'tagline' => ld_pi('Verslo teisė · migracija · sutartys — patikima konsultacija', 'Бізнес-право · міграція · договори — надійна консультація', 'Business law · migration · contracts — trusted advice'),
                'hero_icon' => 'fa-scale-balanced', 'cta' => ld_pi('Konsultacija', 'Консультація', 'Consultation'), 'phone' => '+370 5 231 7788', 'email' => 'office@lexpro.demo', 'template' => 10,
                'og_image' => 'https://images.unsplash.com/photo-1589829545855-d10d557cf95f?w=1200&h=630&fit=crop',
                'ai_prompt' => 'Law office assistant for {business_name} in {city}. Help book consultation for business law, migration, contracts, real estate. No legal advice — schedule only. Language: {lang}. Plain text only.',
                'services' => [
                    ['icon' => 'fa-briefcase', 'name' => ld_pi('Verslo teisė', 'Бізнес-право', 'Business law'), 'desc' => ld_pi('Įmonių steigimas, sutartys, ginčai.', 'Реєстрація фірм, договори, спори.', 'Company setup, contracts, disputes.'), 'price' => '120'],
                    ['icon' => 'fa-passport', 'name' => ld_pi('Migracijos bylos', 'Міграційні справи', 'Migration cases'), 'desc' => ld_pi('Leidimai gyventi, pilietybė, darbo vizos.', 'Дозволи, громадянство, візи.', 'Residence permits, citizenship, work visas.'), 'price' => '150'],
                    ['icon' => 'fa-house', 'name' => ld_pi('Nekilnojamasis turtas', 'Нерухомість', 'Real estate'), 'desc' => ld_pi('Pirkimo-pardavimo sutartys ir due diligence.', 'Договори купівлі-продажу.', 'Purchase agreements and due diligence.'), 'price' => '95'],
                    ['icon' => 'fa-file-contract', 'name' => ld_pi('Sutarčių rengimas', 'Складання договорів', 'Contract drafting'), 'desc' => ld_pi('LT/UA/EN kalbomis.', 'Мовами LT/UA/EN.', 'In LT/UA/EN languages.'), 'price' => '80'],
                ],
                'team' => [
                    ['name' => 'Adv. Mantas Jurgilas', 'role' => ld_pi('Verslo teisė', 'Бізнес-право', 'Business law'), 'years' => '14', 'initials' => 'MJ'],
                    ['name' => 'Adv. Oksana Shevchenko', 'role' => ld_pi('Migracija (UA/LT)', 'Міграція (UA/LT)', 'Migration (UA/LT)'), 'years' => '11', 'initials' => 'OS'],
                ],
            ],
        ],
        'medical_clinic' => [
            'icon' => 'fa-stethoscope', 'template' => 8,
            'brief' => 'Medicinos klinika: šeimos gydytojas, tyrimai, UZD, vakcinacija. LT/UA/RU/EN.',
            'label' => ld_pi('Medicinos klinika', 'Медична клініка', 'Medical clinic'),
            'desc' => ld_pi('Šeimos gydytojas, tyrimai, užrašymas', 'Сімейний лікар, аналізи, запис', 'Family doctor, tests, booking'),
            'build' => [
                'name' => ld_pi('MedCare Klinika', 'MedCare Клініка', 'MedCare Clinic'),
                'tagline' => ld_pi('Šeimos medicina · tyrimai · prevencija — be eilių', 'Сімейна медицина · аналізи · профілактика — без черг', 'Family medicine · tests · prevention — no queues'),
                'hero_icon' => 'fa-stethoscope', 'cta' => ld_pi('Užsiregistruoti', 'Записатися', 'Book visit'), 'phone' => '+370 5 244 5566', 'email' => 'info@medcare.demo', 'template' => 8,
                'og_image' => 'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?w=1200&h=630&fit=crop',
                'ai_prompt' => 'Medical clinic assistant for {business_name} in {city}. Help with doctor appointments, blood tests, ultrasound, vaccination, prices. Not a diagnosis tool. Language: {lang}. Plain text only.',
                'services' => [
                    ['icon' => 'fa-user-doctor', 'name' => ld_pi('Šeimos gydytojas', 'Сімейний лікар', 'Family doctor'), 'desc' => ld_pi('Pirminė apžiūra ir siuntimai.', 'Первинний огляд і направлення.', 'Primary care and referrals.'), 'price' => '35'],
                    ['icon' => 'fa-vial', 'name' => ld_pi('Kraujo tyrimai', 'Аналіз крові', 'Blood tests'), 'desc' => ld_pi('Platus tyrimų paketų pasirinkimas.', 'Широкий вибір пакетів.', 'Wide test panel options.'), 'price' => '28'],
                    ['icon' => 'fa-x-ray', 'name' => ld_pi('Ultragarsas', 'УЗД', 'Ultrasound'), 'desc' => ld_pi('Skydliaukės, pilvo, ginekologinis UZD.', 'Щитоподібної, черевної, гінекологічне.', 'Thyroid, abdominal, gyn ultrasound.'), 'price' => '45'],
                    ['icon' => 'fa-syringe', 'name' => ld_pi('Vakcinacija', 'Вакцинація', 'Vaccination'), 'desc' => ld_pi('Suaugusiųjų ir vaikų skiepai.', 'Щеплення дорослих і дітей.', 'Adult and child vaccines.'), 'price' => '25'],
                ],
            ],
        ],
        'real_estate' => [
            'icon' => 'fa-house-chimney', 'template' => 4,
            'brief' => 'Nekilnojamojo turto agentūra Vilniuje: pardavimas, nuoma, investicijos. LT/UA/RU/EN.',
            'label' => ld_pi('Nekilnojamasis turtas', 'Нерухомість', 'Real estate'),
            'desc' => ld_pi('Butai, namai, nuoma, investicijos', 'Квартири, будинки, оренда', 'Apartments, houses, rentals'),
            'build' => [
                'name' => ld_pi('HomeKey NT', 'HomeKey Нерухомість', 'HomeKey Realty'),
                'tagline' => ld_pi('Butai · namai · nuoma · investicijos — patikimas brokeris Vilniuje', 'Квартири · будинки · оренда · інвестиції — брокер у Вільнюсі', 'Apartments · homes · rent · investment — trusted broker in Vilnius'),
                'hero_icon' => 'fa-house-chimney', 'cta' => ld_pi('Rasti būstą', 'Знайти житло', 'Find property'), 'phone' => '+370 612 99001', 'email' => 'info@homekey.demo', 'template' => 4,
                'og_image' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=1200&h=630&fit=crop',
                'ai_prompt' => 'Real estate assistant for {business_name} in {city}. Help with buying, selling, renting apartments and houses, viewings, mortgage basics. Language: {lang}. Plain text only.',
                'services' => [
                    ['icon' => 'fa-building', 'name' => ld_pi('Butų pardavimas', 'Продаж квартир', 'Apartment sales'), 'desc' => ld_pi('Nauji ir antriniai butai Vilniuje.', 'Нові та вторинні квартири.', 'New and resale apartments.'), 'price' => '0'],
                    ['icon' => 'fa-key', 'name' => ld_pi('Nuoma', 'Оренда', 'Rentals'), 'desc' => ld_pi('Ilgi ir trumpi nuomos terminai.', 'Довга та коротка оренда.', 'Long and short-term rent.'), 'price' => '0'],
                    ['icon' => 'fa-chart-line', 'name' => ld_pi('Investicijos', 'Інвестиції', 'Investment'), 'desc' => ld_pi('ROI analizė ir portfelio valdymas.', 'Аналіз ROI та портфель.', 'ROI analysis and portfolio.'), 'price' => '199'],
                    ['icon' => 'fa-camera', 'name' => ld_pi('Foto ir 3D turas', 'Фото та 3D тур', 'Photo & 3D tour'), 'desc' => ld_pi('Profesionalus pateikimas skelbimams.', 'Професійна подача оголошень.', 'Pro listing presentation.'), 'price' => '89'],
                ],
            ],
        ],
        'accounting' => [
            'icon' => 'fa-calculator', 'template' => 10,
            'brief' => 'Buhalterija ir apskaita: MB, UAB, sąskaitos, deklaracijos. LT/UA/RU/EN.',
            'label' => ld_pi('Buhalterija', 'Бухгалтерія', 'Accounting'),
            'desc' => ld_pi('MB, UAB, sąskaitos, deklaracijos', 'ФОП, ТОВ, рахунки, декларації', 'SMB accounting & tax'),
            'build' => [
                'name' => ld_pi('Balanso Buhalterija', 'Balanso Бухгалтерія', 'Balanso Accounting'),
                'tagline' => ld_pi('MB · UAB · sąskaitos · VMI — be rūpesčių', 'ФОП · ТОВ · рахунки · податки — без турбот', 'SMB · invoices · tax filings — worry-free'),
                'hero_icon' => 'fa-calculator', 'cta' => ld_pi('Konsultacija', 'Консультація', 'Consultation'), 'phone' => '+370 5 275 1122', 'email' => 'hello@balanso.demo', 'template' => 10,
                'og_image' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=1200&h=630&fit=crop',
                'ai_prompt' => 'Accounting firm assistant for {business_name} in {city}. Help with bookkeeping, invoices, tax declarations, company setup MB/UAB. Language: {lang}. Plain text only.',
                'services' => [
                    ['icon' => 'fa-file-invoice', 'name' => ld_pi('MB apskaita', 'Облік ФОП', 'Sole trader books'), 'desc' => ld_pi('Mėnesinė apskaita ir ataskaitos.', 'Щомісячний облік і звіти.', 'Monthly books and reports.'), 'price' => '79'],
                    ['icon' => 'fa-building', 'name' => ld_pi('UAB apskaita', 'Облік ТОВ', 'Ltd accounting'), 'desc' => ld_pi('Pilna buhalterija ir darbo užmokestis.', 'Повний облік і зарплата.', 'Full accounting and payroll.'), 'price' => '149'],
                    ['icon' => 'fa-receipt', 'name' => ld_pi('Sąskaitų išrašymas', 'Виставлення рахунків', 'Invoicing'), 'desc' => ld_pi('Sąskaitos LT/EN su PVM.', 'Рахунки LT/EN з ПДВ.', 'LT/EN invoices with VAT.'), 'price' => '29'],
                    ['icon' => 'fa-landmark', 'name' => ld_pi('VMI deklaracijos', 'Податкові декларації', 'Tax filings'), 'desc' => ld_pi('Laiku pateiktos deklaracijos.', 'Вчасні декларації.', 'On-time tax submissions.'), 'price' => '45'],
                ],
            ],
        ],
        'cleaning' => [
            'icon' => 'fa-broom', 'template' => 6,
            'brief' => 'Valymo paslaugos: butai, biurai, generalinis valymas. LT/UA/RU/EN.',
            'label' => ld_pi('Valymo paslaugos', 'Клінінг', 'Cleaning service'),
            'desc' => ld_pi('Butai, biurai, generalinis valymas', 'Квартири, офіси, генеральне прибирання', 'Homes, offices, deep clean'),
            'build' => [
                'name' => ld_pi('SparkClean', 'SparkClean', 'SparkClean'),
                'tagline' => ld_pi('Butai · biurai · generalinis valymas — ekologiška ir greita', 'Квартири · офіси · генеральне — екологічно й швидко', 'Homes · offices · deep clean — eco-friendly & fast'),
                'hero_icon' => 'fa-broom', 'cta' => ld_pi('Užsakyti valymą', 'Замовити прибирання', 'Book cleaning'), 'phone' => '+370 633 44567', 'email' => 'order@sparkclean.demo', 'template' => 6,
                'og_image' => 'https://images.unsplash.com/photo-1581578731548-c64695cc6952?w=1200&h=630&fit=crop',
                'ai_prompt' => 'Cleaning service assistant for {business_name} in {city}. Help book apartment, office, deep cleaning, prices, eco products. Language: {lang}. Plain text only.',
                'services' => [
                    ['icon' => 'fa-house', 'name' => ld_pi('Buto valymas', 'Прибирання квартири', 'Home cleaning'), 'desc' => ld_pi('Standartinis arba giluminis valymas.', 'Стандартне або глибоке.', 'Standard or deep clean.'), 'price' => '49'],
                    ['icon' => 'fa-building', 'name' => ld_pi('Biuro valymas', 'Офісне прибирання', 'Office cleaning'), 'desc' => ld_pi('Kasdienis arba savaitinis grafikas.', 'Щоденний або тижневий графік.', 'Daily or weekly schedule.'), 'price' => '89'],
                    ['icon' => 'fa-spray-can-sparkles', 'name' => ld_pi('Generalinis valymas', 'Генеральне прибирання', 'Deep cleaning'), 'desc' => ld_pi('Po remonto arba sezoniškai.', 'Після ремонту або сезонно.', 'Post-renovation or seasonal.'), 'price' => '120'],
                    ['icon' => 'fa-window-maximize', 'name' => ld_pi('Langų valymas', 'Миття вікон', 'Window cleaning'), 'desc' => ld_pi('Vidus ir laukas (iki 3 aukšto).', 'Всередині та зовні (до 3 поверху).', 'Inside and outside (up to 3rd floor).'), 'price' => '35'],
                ],
            ],
        ],
        'veterinary' => [
            'icon' => 'fa-paw', 'template' => 5,
            'brief' => 'Veterinarijos klinika: šunys, katės, skiepai, chirurgija. LT/UA/RU/EN.',
            'label' => ld_pi('Veterinarija', 'Ветеринарія', 'Veterinary clinic'),
            'desc' => ld_pi('Šunys, katės, skiepai, chirurgija', 'Собаки, коти, щеплення, хірургія', 'Dogs, cats, vaccines, surgery'),
            'build' => [
                'name' => ld_pi('PetCare Vet', 'PetCare Вет', 'PetCare Vet'),
                'tagline' => ld_pi('Šunys · katės · skiepai · chirurgija — rūpestinga veterinarija', 'Собаки · коти · щеплення · хірургія — турботлива ветеринарія', 'Dogs · cats · vaccines · surgery — caring veterinary care'),
                'hero_icon' => 'fa-paw', 'cta' => ld_pi('Užsiregistruoti', 'Записатися', 'Book visit'), 'phone' => '+370 612 33445', 'email' => 'care@petcare.demo', 'template' => 5,
                'og_image' => 'https://images.unsplash.com/photo-1628009368231-7bb7cfcb0def?w=1200&h=630&fit=crop',
                'ai_prompt' => 'Veterinary clinic assistant for {business_name} in {city}. Help with pet appointments, vaccines, surgery info, emergency, prices. Not a diagnosis tool. Language: {lang}. Plain text only.',
                'services' => [
                    ['icon' => 'fa-stethoscope', 'name' => ld_pi('Apžiūra', 'Огляд', 'Check-up'), 'desc' => ld_pi('Bendrinė sveikatos apžiūra.', 'Загальний огляд здоров\'я.', 'General health exam.'), 'price' => '32'],
                    ['icon' => 'fa-syringe', 'name' => ld_pi('Skiepai', 'Щеплення', 'Vaccination'), 'desc' => ld_pi('Šunų ir kačių vakcinacija.', 'Вакцинація собак і котів.', 'Dog and cat vaccines.'), 'price' => '18'],
                    ['icon' => 'fa-scissors', 'name' => ld_pi('Chirurgija', 'Хірургія', 'Surgery'), 'desc' => ld_pi('Planinės operacijos su monitoringu.', 'Планові операції з моніторингом.', 'Planned ops with monitoring.'), 'price' => '150'],
                    ['icon' => 'fa-tooth', 'name' => ld_pi('Dantų higiena', 'Чистка зубів', 'Dental care'), 'desc' => ld_pi('Ultragarsinis valymas gyvūnams.', 'Ультразвукове чищення.', 'Ultrasonic dental clean.'), 'price' => '65'],
                ],
            ],
        ],
        'photography' => [
            'icon' => 'fa-camera', 'template' => 7,
            'brief' => 'Fotografas: vestuvės, portretai, renginiai, komercinė fotografija. LT/UA/RU/EN.',
            'label' => ld_pi('Fotografija', 'Фотографія', 'Photography'),
            'desc' => ld_pi('Vestuvės, portretai, renginiai', 'Весілля, портрети, події', 'Weddings, portraits, events'),
            'build' => [
                'name' => ld_pi('LensArt Studio', 'LensArt Studio', 'LensArt Studio'),
                'tagline' => ld_pi('Vestuvės · portretai · renginiai — gyvos emocijos kadre', 'Весілля · портрети · події — живі емоції в кадрі', 'Weddings · portraits · events — real emotions captured'),
                'hero_icon' => 'fa-camera', 'cta' => ld_pi('Rezervuoti datą', 'Забронювати дату', 'Reserve date'), 'phone' => '+370 686 11223', 'email' => 'hello@lensart.demo', 'template' => 7,
                'og_image' => 'https://images.unsplash.com/photo-1452587925148-ce544e77e70d?w=1200&h=630&fit=crop',
                'ai_prompt' => 'Photography studio assistant for {business_name} in {city}. Help with wedding, portrait, event packages, availability, delivery time. Language: {lang}. Plain text only.',
                'services' => [
                    ['icon' => 'fa-heart', 'name' => ld_pi('Vestuvės', 'Весілля', 'Weddings'), 'desc' => ld_pi('Pilna diena + online galerija.', 'Повний день + онлайн галерея.', 'Full day + online gallery.'), 'price' => '890'],
                    ['icon' => 'fa-user', 'name' => ld_pi('Portretas', 'Портрет', 'Portrait'), 'desc' => ld_pi('Studija arba lokacija, retušas įskaičiuotas.', 'Студія або локація, ретуш включена.', 'Studio or location, retouch included.'), 'price' => '120'],
                    ['icon' => 'fa-calendar', 'name' => ld_pi('Renginiai', 'Події', 'Events'), 'desc' => ld_pi('Konferencijos, koncertai, corporate.', 'Конференції, корпоративи.', 'Conferences, corporate events.'), 'price' => '350'],
                    ['icon' => 'fa-bag-shopping', 'name' => ld_pi('Komercinė fotografija', 'Комерційна зйомка', 'Commercial'), 'desc' => ld_pi('Produktai, interjerai, reklama.', 'Продукти, інтер\'єри, реклама.', 'Products, interiors, ads.'), 'price' => '280'],
                ],
            ],
        ],
        'construction' => [
            'icon' => 'fa-helmet-safety', 'template' => 9,
            'brief' => 'Statybos ir remontas: butų remontas, projektavimas, apdaila. LT/UA/RU/EN.',
            'label' => ld_pi('Statyba / remontas', 'Будівництво / ремонт', 'Construction'),
            'desc' => ld_pi('Butų remontas, apdaila, projektai', 'Ремонт квартир, оздоблення', 'Renovation, finishing'),
            'build' => [
                'name' => ld_pi('BuildPro Remontas', 'BuildPro Ремонт', 'BuildPro Renovation'),
                'tagline' => ld_pi('Butų remontas · apdaila · projektai — nuo idėjos iki raktų', 'Ремонт квартир · оздоблення · проєкти — від ідеї до ключів', 'Apartment renovation · finishing · turnkey projects'),
                'hero_icon' => 'fa-helmet-safety', 'cta' => ld_pi('Gauti sąmatą', 'Отримати кошторис', 'Get quote'), 'phone' => '+370 612 77800', 'email' => 'info@buildpro.demo', 'template' => 9,
                'og_image' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=1200&h=630&fit=crop',
                'ai_prompt' => 'Construction/renovation assistant for {business_name} in {city}. Help with apartment renovation quotes, timelines, materials, warranties. Language: {lang}. Plain text only.',
                'services' => [
                    ['icon' => 'fa-paint-roller', 'name' => ld_pi('Kosmetinis remontas', 'Косметичний ремонт', 'Cosmetic renovation'), 'desc' => ld_pi('Dažymas, grindys, plytelės.', 'Фарбування, підлога, плитка.', 'Paint, floors, tiles.'), 'price' => '0'],
                    ['icon' => 'fa-hammer', 'name' => ld_pi('Kapitalinis remontas', 'Капітальний ремонт', 'Full renovation'), 'desc' => ld_pi('Vamzdynai, elektra, pertvaros.', 'Труби, електрика, перегородки.', 'Plumbing, electric, walls.'), 'price' => '0'],
                    ['icon' => 'fa-ruler-combined', 'name' => ld_pi('Projektavimas', 'Проєктування', 'Design plan'), 'desc' => ld_pi('3D vizualizacija ir sąmata.', '3D візуалізація та кошторис.', '3D visual and estimate.'), 'price' => '199'],
                    ['icon' => 'fa-key', 'name' => ld_pi('Po raktų', 'Під ключ', 'Turnkey'), 'desc' => ld_pi('Pilnas ciklas su garantija 2 m.', 'Повний цикл з гарантією 2 роки.', 'Full cycle with 2-year warranty.'), 'price' => '0'],
                ],
            ],
        ],
        'hotel' => [
            'icon' => 'fa-hotel', 'template' => 2,
            'brief' => 'Butiko viešbutis Vilniuje: kambariai, pusryčiai, renginiai. LT/UA/RU/EN.',
            'label' => ld_pi('Viešbutis', 'Готель', 'Hotel'),
            'desc' => ld_pi('Kambariai, pusryčiai, senamiestis', 'Номери, сніданки, Старе місто', 'Rooms, breakfast, old town'),
            'build' => [
                'name' => ld_pi('Boutique Vilnius Hotel', 'Boutique Vilnius Hotel', 'Boutique Vilnius Hotel'),
                'tagline' => ld_pi('Butiko kambariai · pusryčiai · senamiestis — jaukus poilsis', 'Бутик-номери · сніданки · Старе місто — затишний відпочинок', 'Boutique rooms · breakfast · old town — cozy stay'),
                'hero_icon' => 'fa-hotel', 'cta' => ld_pi('Rezervuoti kambarį', 'Забронювати номер', 'Book a room'), 'phone' => '+370 5 212 8899', 'email' => 'stay@boutiquevilnius.demo', 'template' => 2,
                'address' => ld_pi('Didžioji g. 12, LT-01128 Vilnius', 'вул. Діджіожі 12, LT-01128 Вільнюс', 'Didžioji st. 12, LT-01128 Vilnius'),
                'og_image' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1200&h=630&fit=crop',
                'ai_prompt' => 'Boutique hotel assistant for {business_name} in {city}. Help with room booking, breakfast, parking, check-in/out, events. Language: {lang}. Plain text only.',
                'services' => [
                    ['icon' => 'fa-bed', 'name' => ld_pi('Standartinis kambarys', 'Стандартний номер', 'Standard room'), 'desc' => ld_pi('Duše, Wi-Fi, pusryčiai.', 'Душ, Wi-Fi, сніданок.', 'Shower, Wi-Fi, breakfast.'), 'price' => '79'],
                    ['icon' => 'fa-star', 'name' => ld_pi('Deluxe kambarys', 'Deluxe номер', 'Deluxe room'), 'desc' => ld_pi('Senamiestio vaizdas, mini bar.', 'Вид на Старе місто, міні-бар.', 'Old town view, mini bar.'), 'price' => '109'],
                    ['icon' => 'fa-mug-hot', 'name' => ld_pi('Pusryčiai', 'Сніданок', 'Breakfast'), 'desc' => ld_pi('Lietuviški ir continental.', 'Литовські та continental.', 'Lithuanian and continental.'), 'price' => '12'],
                    ['icon' => 'fa-car', 'name' => ld_pi('Parkavimas', 'Парковка', 'Parking'), 'desc' => ld_pi('Saugoma stovėjimo vieta.', 'Охоронювана парковка.', 'Secure parking spot.'), 'price' => '10'],
                ],
            ],
        ],
        'kindergarten' => [
            'icon' => 'fa-child', 'template' => 3,
            'brief' => 'Privatus darželis: ikimokyklinis ugdymas, bilingvizmas LT/EN. LT/UA/RU/EN.',
            'label' => ld_pi('Darželis', 'Дитсадок', 'Kindergarten'),
            'desc' => ld_pi('Ikimokyklinis ugdymas, bilingvizmas', 'Дошкільна освіта, білінгвізм', 'Preschool, bilingual'),
            'build' => [
                'name' => ld_pi('Saulutė Darželis', 'Saulutė Дитсадок', 'Saulutė Kindergarten'),
                'tagline' => ld_pi('Ikimokyklinis ugdymas · bilingvizmas · saugi aplinka', 'Дошкільна освіта · білінгвізм · безпечне середовище', 'Preschool · bilingual · safe environment'),
                'hero_icon' => 'fa-child', 'cta' => ld_pi('Registruoti vaiką', 'Записати дитину', 'Enroll child'), 'phone' => '+370 5 244 3300', 'email' => 'info@saulute.demo', 'template' => 3,
                'og_image' => 'https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?w=1200&h=630&fit=crop',
                'ai_prompt' => 'Kindergarten assistant for {business_name} in {city}. Help parents with enrollment, age groups, bilingual program, fees, visit. Language: {lang}. Plain text only.',
                'services' => [
                    ['icon' => 'fa-child', 'name' => ld_pi('Grupė 2–3 m.', 'Група 2–3 р.', 'Group 2–3 yrs'), 'desc' => ld_pi('Rūpestinga adaptacija ir žaidimai.', 'М\'яка адаптація та ігри.', 'Gentle adaptation and play.'), 'price' => '320'],
                    ['icon' => 'fa-children', 'name' => ld_pi('Grupė 4–6 m.', 'Група 4–6 р.', 'Group 4–6 yrs'), 'desc' => ld_pi('Pasiruošimas mokyklai LT/EN.', 'Підготовка до школи LT/EN.', 'School prep LT/EN.'), 'price' => '340'],
                    ['icon' => 'fa-language', 'name' => ld_pi('Bilingvizmas', 'Білінгвізм', 'Bilingual'), 'desc' => ld_pi('LT ir EN kasdien.', 'LT та EN щодня.', 'LT and EN daily.'), 'price' => '0'],
                    ['icon' => 'fa-apple-whole', 'name' => ld_pi('Maitinimas', 'Харчування', 'Meals'), 'desc' => ld_pi('Sveiki pusryčiai, pietūs, užkandžiai.', 'Здорові сніданки, обіди, перекуси.', 'Healthy meals included.'), 'price' => '0'],
                ],
            ],
        ],
        'pharmacy' => [
            'icon' => 'fa-pills', 'template' => 8,
            'brief' => 'Vaistinė: receptiniai, OTC, konsultacijos, pristatymas. LT/UA/RU/EN.',
            'label' => ld_pi('Vaistinė', 'Аптека', 'Pharmacy'),
            'desc' => ld_pi('Vaistai, konsultacijos, pristatymas', 'Ліки, консультації, доставка', 'Medicines, advice, delivery'),
            'build' => [
                'name' => ld_pi('HealthPlus Vaistinė', 'HealthPlus Аптека', 'HealthPlus Pharmacy'),
                'tagline' => ld_pi('Vaistai · konsultacijos · pristatymas — šalia jūsų namų', 'Ліки · консультації · доставка — поруч із домом', 'Medicines · advice · delivery — near your home'),
                'hero_icon' => 'fa-pills', 'cta' => ld_pi('Rezervuoti vaistus', 'Замовити ліки', 'Reserve medicines'), 'phone' => '+370 5 233 4455', 'email' => 'info@healthplus.demo', 'template' => 8,
                'og_image' => 'https://images.unsplash.com/photo-1576602976047-174e57a47881?w=1200&h=630&fit=crop',
                'ai_prompt' => 'Pharmacy assistant for {business_name} in {city}. Help with medicine availability, reservations, delivery, pharmacist consultation. Not medical advice. Language: {lang}. Plain text only.',
                'services' => [
                    ['icon' => 'fa-pills', 'name' => ld_pi('OTC vaistai', 'OTC ліки', 'OTC medicines'), 'desc' => ld_pi('Platus pasirinkimas be recepto.', 'Широкий вибір без рецепта.', 'Wide OTC selection.'), 'price' => '0'],
                    ['icon' => 'fa-file-prescription', 'name' => ld_pi('Receptiniai', 'Рецептурні', 'Prescription'), 'desc' => ld_pi('Rezervacija telefonu arba online.', 'Резерв по телефону або online.', 'Reserve by phone or online.'), 'price' => '0'],
                    ['icon' => 'fa-truck', 'name' => ld_pi('Pristatymas', 'Доставка', 'Delivery'), 'desc' => ld_pi('Vilniuje per 2 val.', 'По Вільнюсу за 2 год.', 'Vilnius delivery in 2h.'), 'price' => '5'],
                    ['icon' => 'fa-user-nurse', 'name' => ld_pi('Farmacijos konsultacija', 'Консультація фармацевта', 'Pharmacist advice'), 'desc' => ld_pi('Nemokama trumpa konsultacija.', 'Безкоштовна коротка консультація.', 'Free brief consultation.'), 'price' => '0'],
                ],
            ],
        ],
        'barbershop' => [
            'icon' => 'fa-scissors', 'template' => 1,
            'brief' => 'Barber shop vyrams: kirpimas, barzda, vaikų kirpimas. LT/UA/RU/EN.',
            'label' => ld_pi('Barber shop', 'Барбершоп', 'Barbershop'),
            'desc' => ld_pi('Kirpimas, barzda, vaikams', 'Стрижка, борода, дітям', 'Cuts, beard, kids'),
            'build' => [
                'name' => ld_pi('SharpCut Barber', 'SharpCut Barber', 'SharpCut Barber'),
                'tagline' => ld_pi('Kirpimas · barzda · vaikams — klasikinis barber stilius', 'Стрижка · борода · дітям — класичний барбер стиль', 'Cuts · beard · kids — classic barber style'),
                'hero_icon' => 'fa-scissors', 'cta' => ld_pi('Rezervuoti', 'Забронювати', 'Book now'), 'phone' => '+370 699 22110', 'email' => 'book@sharpcut.demo', 'template' => 1,
                'og_image' => 'https://images.unsplash.com/photo-1503951914875-462162b0f8d0?w=1200&h=630&fit=crop',
                'ai_prompt' => 'Barbershop assistant for {business_name} in {city}. Help with haircut, beard trim, kids cut booking, barber choice, prices. Language: {lang}. Plain text only.',
                'services' => [
                    ['icon' => 'fa-scissors', 'name' => ld_pi('Vyriškas kirpimas', 'Чоловіча стрижка', 'Men\'s haircut'), 'desc' => ld_pi('Klasikinis arba fade.', 'Класична або fade.', 'Classic or fade cut.'), 'price' => '22'],
                    ['icon' => 'fa-face-smile-beam', 'name' => ld_pi('Barzdos formavimas', 'Оформлення бороди', 'Beard trim'), 'desc' => ld_pi('Karšto rankšluosčio ritualas.', 'Ритуал гарячого рушника.', 'Hot towel ritual.'), 'price' => '18'],
                    ['icon' => 'fa-child', 'name' => ld_pi('Vaikų kirpimas', 'Дитяча стрижка', 'Kids cut'), 'desc' => ld_pi('Iki 12 m. — greita ir švelni.', 'До 12 р. — швидко й м\'яко.', 'Under 12 — quick and gentle.'), 'price' => '16'],
                    ['icon' => 'fa-spray-can', 'name' => ld_pi('Kirpimas + barzda', 'Стрижка + борода', 'Cut + beard'), 'desc' => ld_pi('Pilnas komplektas.', 'Повний комплект.', 'Full combo package.'), 'price' => '35'],
                ],
            ],
        ],
    ];

    return $data;
}

function ld_preset_from_catalog(string $id): array
{
    $all = ld_presets_catalog_data();
    if (!isset($all[$id])) {
        throw new InvalidArgumentException('Unknown preset: ' . $id);
    }
    $row = $all[$id];

    return ld_preset_wrap($id, $row['icon'], $row['template'], $row['brief'], $row['label'], $row['desc'], ld_preset_build($row['build']));
}

function ld_preset_auto_service(): array { return ld_preset_from_catalog('auto_service'); }
function ld_preset_beauty_salon(): array { return ld_preset_from_catalog('beauty_salon'); }
function ld_preset_restaurant(): array { return ld_preset_from_catalog('restaurant'); }
function ld_preset_fitness_gym(): array { return ld_preset_from_catalog('fitness_gym'); }
function ld_preset_law_office(): array { return ld_preset_from_catalog('law_office'); }
function ld_preset_medical_clinic(): array { return ld_preset_from_catalog('medical_clinic'); }
function ld_preset_real_estate(): array { return ld_preset_from_catalog('real_estate'); }
function ld_preset_accounting(): array { return ld_preset_from_catalog('accounting'); }
function ld_preset_cleaning(): array { return ld_preset_from_catalog('cleaning'); }
function ld_preset_veterinary(): array { return ld_preset_from_catalog('veterinary'); }
function ld_preset_photography(): array { return ld_preset_from_catalog('photography'); }
function ld_preset_construction(): array { return ld_preset_from_catalog('construction'); }
function ld_preset_hotel(): array { return ld_preset_from_catalog('hotel'); }
function ld_preset_kindergarten(): array { return ld_preset_from_catalog('kindergarten'); }
function ld_preset_pharmacy(): array { return ld_preset_from_catalog('pharmacy'); }
function ld_preset_barbershop(): array { return ld_preset_from_catalog('barbershop'); }