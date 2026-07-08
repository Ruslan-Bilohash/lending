<?php
declare(strict_types=1);

function ld_demo_seed_version(): int
{
    return 2;
}

function ld_seed_admin_demo(): void
{
    ld_ensure_news();
    ld_ensure_demo_invoices();
    ld_ensure_leads();
    ld_ensure_students();

    $settings = ld_settings();
    $version = (int) ($settings['meta']['admin_demo_v'] ?? 0);
    $target = ld_demo_seed_version();
    if ($version >= $target) {
        return;
    }

    if ($version < 1) {
        if (ld_load_leads() === []) {
            ld_save_leads(ld_demo_leads());
        }
        if (ld_load_demo_invoices() === []) {
            ld_save_demo_invoices(ld_demo_invoices_data());
        }
        if (ld_load_news() === []) {
            ld_save_news(ld_demo_news_data());
        }
    }
    if ($version < 2 && ld_load_students() === []) {
        ld_save_students(ld_demo_students_data());
    }

    $settings['meta']['admin_demo_v'] = $target;
    ld_save_settings($settings);
}

/** @return list<array<string, mixed>> */
function ld_demo_students_data(): array
{
    $rows = [
        ['name' => 'Jonas Petraitis', 'phone' => '+370 612 11101', 'email' => 'jonas@demo.lt', 'course' => 'B kategorija (automobilis)', 'monthly_price' => 120.00, 'status' => 'active'],
        ['name' => 'Olena Koval', 'phone' => '+370 698 22202', 'email' => 'olena@demo.ua', 'course' => 'BE kategorija (priekaba)', 'monthly_price' => 150.00, 'status' => 'active'],
        ['name' => 'Marius Kazlauskas', 'phone' => '+370 655 33303', 'course' => 'Teorijos kursas', 'monthly_price' => 45.00, 'status' => 'active'],
        ['name' => 'Anna Smirnova', 'phone' => '+370 611 44404', 'email' => 'anna@demo.ru', 'course' => 'B kategorija (automobilis)', 'monthly_price' => 120.00, 'status' => 'paused'],
        ['name' => 'Tomas Balčiūnas', 'phone' => '+370 699 55505', 'course' => 'Intensyvus B (2 sav.)', 'monthly_price' => 280.00, 'status' => 'active'],
        ['name' => 'Iryna Melnyk', 'phone' => '+370 620 66606', 'email' => 'iryna@demo.ua', 'course' => 'B kategorija (automobilis)', 'monthly_price' => 120.00, 'status' => 'active'],
    ];
    $out = [];
    foreach ($rows as $i => $row) {
        $id = 'demo-stu-' . str_pad((string) ($i + 1), 3, '0', STR_PAD_LEFT);
        $row['id'] = $id;
        $row['started_at'] = date('Y-m-d', strtotime('-' . ($i + 1) . ' months'));
        $row['created_at'] = date('c');
        $row['updated_at'] = date('c');
        $row['last_invoice_no'] = '';
        $row['last_invoice_url'] = '';
        $row['last_invoiced_at'] = '';
        $row['last_billing_month'] = '';
        $out[] = $row;
    }
    return $out;
}

/** @return list<array<string, mixed>> */
function ld_demo_leads(): array
{
    $base = new DateTimeImmutable('today');
    $rows = [
        ['name' => 'Jonas Petraitis', 'phone' => '+370 612 11101', 'email' => 'jonas@demo.lt', 'service' => 'B kategorija', 'status' => 'new'],
        ['name' => 'Olena Koval', 'phone' => '+370 698 22202', 'email' => 'olena@demo.ua', 'service' => 'BE kategorija', 'status' => 'callback'],
        ['name' => 'Marius Kazlauskas', 'phone' => '+370 655 33303', 'service' => 'Intensyvus kursas', 'status' => 'invoiced', 'invoice_no' => 'LD-2026-004', 'invoice_url' => '#'],
        ['name' => 'Anna Smirnova', 'phone' => '+370 611 44404', 'email' => 'anna@demo.ru', 'service' => 'Teorija online', 'status' => 'new'],
        ['name' => 'Tomas Balčiūnas', 'phone' => '+370 699 55505', 'service' => 'B kategorija', 'status' => 'callback'],
        ['name' => 'Iryna Melnyk', 'phone' => '+370 620 66606', 'service' => 'BE kategorija', 'status' => 'new'],
        ['name' => 'Paulius Urbonas', 'phone' => '+370 633 77707', 'email' => 'paulius@demo.lt', 'service' => 'B kategorija', 'status' => 'invoiced', 'invoice_no' => 'LD-2026-005', 'invoice_url' => '#'],
        ['name' => 'Eglė Jankauskė', 'phone' => '+370 644 88808', 'service' => 'Intensyvus kursas', 'status' => 'new'],
        ['name' => 'Dmytro Shevchenko', 'phone' => '+370 677 99909', 'email' => 'dmytro@demo.ua', 'service' => 'B kategorija', 'status' => 'callback'],
        ['name' => 'Laura Vaitkutė', 'phone' => '+370 688 00010', 'service' => 'Teorija online', 'status' => 'new'],
    ];
    $offsets = [49, 42, 35, 28, 21, 14, 10, 7, 3, 1];
    $out = [];
    foreach ($rows as $i => $row) {
        $days = $offsets[$i] ?? 1;
        $row['id'] = 'demo-lead-' . str_pad((string) ($i + 1), 3, '0', STR_PAD_LEFT);
        $row['created_at'] = $base->modify('-' . $days . ' days')->format('c');
        $out[] = $row;
    }
    return $out;
}

/** @return list<array<string, mixed>> */
function ld_demo_invoices_data(): array
{
    return [
        ['id' => 'inv-demo-001', 'invoice_no' => 'LD-2026-001', 'buyer_name' => 'Jonas Petraitis', 'service' => 'B kategorija — pilnas kursas', 'amount' => 450.00, 'currency' => 'EUR', 'status' => 'paid', 'created_at' => '2026-01-12'],
        ['id' => 'inv-demo-002', 'invoice_no' => 'LD-2026-002', 'buyer_name' => 'Olena Koval', 'service' => 'BE kategorija', 'amount' => 680.00, 'currency' => 'EUR', 'status' => 'issued', 'created_at' => '2026-02-03'],
        ['id' => 'inv-demo-003', 'invoice_no' => 'LD-2026-003', 'buyer_name' => 'Marius Kazlauskas', 'service' => 'Intensyvus kursas', 'amount' => 520.00, 'currency' => 'EUR', 'status' => 'paid', 'created_at' => '2026-02-18'],
        ['id' => 'inv-demo-004', 'invoice_no' => 'LD-2026-004', 'buyer_name' => 'Anna Smirnova', 'service' => 'Teorija online + praktika', 'amount' => 380.00, 'currency' => 'EUR', 'status' => 'issued', 'created_at' => '2026-03-05'],
        ['id' => 'inv-demo-005', 'invoice_no' => 'LD-2026-005', 'buyer_name' => 'Paulius Urbonas', 'service' => 'B kategorija', 'amount' => 450.00, 'currency' => 'EUR', 'status' => 'paid', 'created_at' => '2026-04-10'],
        ['id' => 'inv-demo-006', 'invoice_no' => 'LD-2026-006', 'buyer_name' => 'Eglė Jankauskė', 'service' => 'BE kategorija — intensyvas', 'amount' => 1050.00, 'currency' => 'EUR', 'status' => 'issued', 'created_at' => '2026-05-22'],
    ];
}

/** @return list<array<string, mixed>> */
function ld_demo_news_data(): array
{
    $articles = [
        [
            'slug' => 'naujas-intensyvus-kursas',
            'published_at' => '2026-06-15',
            'seo_score' => 88,
            'lt' => [
                'title' => 'Naujas intensyvus B kategorijos kursas — startas liepos 1 d.',
                'excerpt' => '4 savaitės, teorija online ir praktika Vilniuje. Registracija atidaryta — vietų skaičius ribotas.',
                'body' => "Vilniaus Vairavimo Mokykla pradeda naują intensyvų B kategorijos kursą liepos 1 d.\n\nPrograma apima teorijos pamokas online, 30 praktinių valandų su instruktoriais ir pasiruošimą Regitra egzaminui. Užsiregistruokite per formą svetainėje — perskambinsime per 15 minučių.",
                'seo_title' => 'Intensyvus B kursas Vilniuje | Vilniaus Vairavimo Mokykla',
                'seo_description' => 'Naujas intensyvus B kategorijos kursas Vilniuje nuo liepos 1 d. Teorija online, praktika, Regitra pasiruošimas. Registruokitės dabar.',
                'seo_keywords' => 'intensyvus kursas, B kategorija, Vilnius, vairavimo mokykla, Regitra',
            ],
            'uk' => [
                'title' => 'Новий інтенсивний курс категорії B — старт 1 липня',
                'excerpt' => '4 тижні, теорія online і практика у Вільнюсі. Реєстрація відкрита — місць обмежено.',
                'body' => "Вільнюська автошкола запускає інтенсивний курс категорії B з 1 липня.\n\nПрограма включає онлайн-теорію, 30 годин практики з інструкторами та підготовку до іспиту Regitra. Залиште заявку — передзвонимо за 15 хвилин.",
                'seo_title' => 'Інтенсив категорія B Вільнюс | Вільнюська автошкола',
                'seo_description' => 'Новий інтенсивний курс категорії B у Вільнюсі з 1 липня. Теорія online, практика, підготовка до Regitra. Запис онлайн.',
                'seo_keywords' => 'інтенсив, категорія B, Вільнюс, автошкола, Regitra',
            ],
            'ru' => [
                'title' => 'Новый интенсивный курс категории B — старт 1 июля',
                'excerpt' => '4 недели, теория online и практика в Вильнюсе. Регистрация открыта — мест ограничено.',
                'body' => "Вильнюсская автошкола запускает интенсивный курс категории B с 1 июля.\n\nПрограмма включает онлайн-теорию, 30 часов практики с инструкторами и подготовку к экзамену Regitra. Оставьте заявку — перезвоним за 15 минут.",
                'seo_title' => 'Интенсив категория B Вильнюс | Вильнюсская автошкола',
                'seo_description' => 'Новый интенсивный курс категории B в Вильнюсе с 1 июля. Теория online, практика, подготовка к Regitra. Запись онлайн.',
                'seo_keywords' => 'интенсив, категория B, Вильнюс, автошкола, Regitra',
            ],
            'en' => [
                'title' => 'New intensive Category B course — starts July 1',
                'excerpt' => '4 weeks, online theory and practice in Vilnius. Registration open — limited seats.',
                'body' => "Vilnius Driving School launches a new intensive Category B course on July 1.\n\nThe programme includes online theory, 30 hours of practical lessons with instructors, and Regitra exam preparation. Submit the form — we call you back within 15 minutes.",
                'seo_title' => 'Intensive Category B Vilnius | Vilnius Driving School',
                'seo_description' => 'New intensive Category B course in Vilnius from July 1. Online theory, practice, Regitra prep. Enroll online today.',
                'seo_keywords' => 'intensive course, category B, Vilnius, driving school, Regitra',
            ],
        ],
        [
            'slug' => 'be-kategorija-naujiena',
            'published_at' => '2026-05-28',
            'seo_score' => 82,
            'lt' => [
                'title' => 'BE kategorija: nauji laikai ir automatinės pavarų dėžės',
                'excerpt' => 'Pradedame BE kursus su automatu ir mechanika — pasirinkite patogų grafiką.',
                'body' => "Nuo gegužės BE kategorijos kursai vyksta ir rytinėmis, ir vakarinėmis grupėmis.\n\nGalite rinktis automatinę ar mechaninę pavarų dėžę. Konsultacija nemokama — užpildykite formą svetainėje.",
                'seo_title' => 'BE kategorija Vilniuje — nauji laikai | Vairavimo mokykla',
                'seo_description' => 'BE kategorijos kursai Vilniuje: automatas ir mechanika, rytinės ir vakarinės grupės. Nemokama konsultacija online.',
                'seo_keywords' => 'BE kategorija, automatas, Vilnius, vairavimo mokykla',
            ],
            'uk' => [
                'title' => 'Категорія BE: новий розклад та автоматичні коробки',
                'excerpt' => 'Запускаємо курси BE з автоматом і механікою — оберіть зручний графік.',
                'body' => "З травня курси категорії BE проходять у ранкових і вечірніх групах.\n\nМожна обрати автомат або механіку. Безкоштовна консультація — заповніть форму на сайті.",
                'seo_title' => 'Категорія BE Вільнюс — новий розклад | Автошкола',
                'seo_description' => 'Курси категорії BE у Вільнюсі: автомат і механіка, ранкові та вечірні групи. Безкоштовна консультація.',
                'seo_keywords' => 'категорія BE, автомат, Вільнюс, автошкола',
            ],
            'ru' => [
                'title' => 'Категория BE: новое расписание и автоматические коробки',
                'excerpt' => 'Запускаем курсы BE с автоматом и механикой — выберите удобный график.',
                'body' => "С мая курсы категории BE проходят в утренних и вечерних группах.\n\nМожно выбрать автомат или механику. Бесплатная консультация — заполните форму на сайте.",
                'seo_title' => 'Категория BE Вильнюс — новое расписание | Автошкола',
                'seo_description' => 'Курсы категории BE в Вильнюсе: автомат и механика, утренние и вечерние группы. Бесплатная консультация.',
                'seo_keywords' => 'категория BE, автомат, Вильнюс, автошкола',
            ],
            'en' => [
                'title' => 'Category BE: new schedule and automatic gearboxes',
                'excerpt' => 'BE courses with automatic and manual — pick a schedule that suits you.',
                'body' => "From May, Category BE courses run in morning and evening groups.\n\nChoose automatic or manual transmission. Free consultation — fill in the form on our site.",
                'seo_title' => 'Category BE Vilnius — new schedule | Driving School',
                'seo_description' => 'Category BE courses in Vilnius: automatic and manual, morning and evening groups. Free consultation online.',
                'seo_keywords' => 'category BE, automatic, Vilnius, driving school',
            ],
        ],
        [
            'slug' => 'regitra-pasiruosimas',
            'published_at' => '2026-04-20',
            'seo_score' => 91,
            'lt' => [
                'title' => '95% mokinių išlaiko Regitra egzaminą iš pirmo karto',
                'excerpt' => 'Pasidaliname patarimais, kaip pasiruošti teorijos ir praktikos egzaminams.',
                'body' => "Mūsų mokykla didžiuojasi aukštu Regitra egzaminų išlaikymo rodikliu.\n\nSiūlome bandomuosius testus, individualias konsultacijas ir papildomas praktikos valandas prieš egzaminą.",
                'seo_title' => 'Regitra pasiruošimas Vilniuje | 95% sėkmė — Vairavimo mokykla',
                'seo_description' => 'Kaip pasiruošti Regitra egzaminui Vilniuje: bandomieji testai, konsultacijos, papildoma praktika. 95% išlaiko iš pirmo karto.',
                'seo_keywords' => 'Regitra, egzaminas, vairavimo mokykla, Vilnius, teorija',
            ],
            'uk' => [
                'title' => '95% учнів здають іспит Regitra з першого разу',
                'excerpt' => 'Ділимося порадами з підготовки до теоретичного та практичного іспитів.',
                'body' => "Наша школа пишається високим відсотком здачі іспитів Regitra.\n\nПропонуємо пробні тести, індивідуальні консультації та додаткові години практики перед іспитом.",
                'seo_title' => 'Підготовка до Regitra Вільнюс | 95% успіх — Автошкола',
                'seo_description' => 'Як підготуватися до іспиту Regitra у Вільнюсі: пробні тести, консультації, додаткова практика. 95% здають з першого разу.',
                'seo_keywords' => 'Regitra, іспит, автошкола, Вільнюс, теорія',
            ],
            'ru' => [
                'title' => '95% учеников сдают экзамен Regitra с первого раза',
                'excerpt' => 'Делимся советами по подготовке к теоретическому и практическому экзаменам.',
                'body' => "Наша школа гордится высоким процентом сдачи экзаменов Regitra.\n\nПредлагаем пробные тесты, индивидуальные консультации и дополнительные часы практики перед экзаменом.",
                'seo_title' => 'Подготовка к Regitra Вильнюс | 95% успех — Автошкола',
                'seo_description' => 'Как подготовиться к экзамену Regitra в Вильнюсе: пробные тесты, консультации, дополнительная практика. 95% сдают с первого раза.',
                'seo_keywords' => 'Regitra, экзамен, автошкола, Вильнюс, теория',
            ],
            'en' => [
                'title' => '95% of students pass the Regitra exam first time',
                'excerpt' => 'Tips for theory and practical exam preparation from our instructors.',
                'body' => "Our school is proud of a high Regitra pass rate.\n\nWe offer mock tests, one-to-one consultations and extra practice hours before your exam.",
                'seo_title' => 'Regitra exam prep Vilnius | 95% pass rate — Driving School',
                'seo_description' => 'How to prepare for the Regitra exam in Vilnius: mock tests, consultations, extra practice. 95% pass first time.',
                'seo_keywords' => 'Regitra, exam, driving school, Vilnius, theory',
            ],
        ],
        [
            'slug' => 'vasaros-akcija',
            'published_at' => '2026-03-10',
            'seo_score' => 76,
            'lt' => [
                'title' => 'Vasaros akcija: -10% B kategorijos kursui',
                'excerpt' => 'Registruokitės iki birželio 30 d. ir sutaupykite. Akcija galioja naujoms registracijoms.',
                'body' => "Vasaros sezonui skiriame 10% nuolaidą visiems naujiems B kategorijos kursams.\n\nNuolaida taikoma registracijoms iki birželio 30 d. Kainos ir detalės — skiltyje Paslaugos.",
                'seo_title' => 'Vasaros akcija B kursas -10% | Vilniaus Vairavimo Mokykla',
                'seo_description' => '10% nuolaida B kategorijos kursui Vilniuje. Registracija iki birželio 30 d. Nemokama konsultacija ir skambutis per 15 min.',
                'seo_keywords' => 'akcija, B kategorija, nuolaida, Vilnius, vairavimo mokykla',
            ],
            'uk' => [
                'title' => 'Літня акція: -10% на курс категорії B',
                'excerpt' => 'Зареєструйтесь до 30 червня та заощадьте. Акція для нових записів.',
                'body' => "На літній сезон — знижка 10% на всі нові курси категорії B.\n\nДіє для реєстрацій до 30 червня. Ціни та деталі — у розділі Послуги.",
                'seo_title' => 'Літня акція категорія B -10% | Вільнюська автошкола',
                'seo_description' => 'Знижка 10% на курс категорії B у Вільнюсі. Реєстрація до 30 червня. Безкоштовна консультація.',
                'seo_keywords' => 'акція, категорія B, знижка, Вільнюс, автошкола',
            ],
            'ru' => [
                'title' => 'Летняя акция: -10% на курс категории B',
                'excerpt' => 'Зарегистрируйтесь до 30 июня и сэкономьте. Акция для новых записей.',
                'body' => "На летний сезон — скидка 10% на все новые курсы категории B.\n\nДействует для регистраций до 30 июня. Цены и детали — в разделе Услуги.",
                'seo_title' => 'Летняя акция категория B -10% | Вильнюсская автошкола',
                'seo_description' => 'Скидка 10% на курс категории B в Вильнюсе. Регистрация до 30 июня. Бесплатная консультация.',
                'seo_keywords' => 'акция, категория B, скидка, Вильнюс, автошкола',
            ],
            'en' => [
                'title' => 'Summer promo: 10% off Category B course',
                'excerpt' => 'Register by June 30 and save. Valid for new enrollments only.',
                'body' => "Summer season — 10% off all new Category B courses.\n\nValid for registrations until June 30. Prices and details in the Services section.",
                'seo_title' => 'Summer promo Category B 10% off | Vilnius Driving School',
                'seo_description' => '10% off Category B course in Vilnius. Register by June 30. Free consultation and callback within 15 minutes.',
                'seo_keywords' => 'promo, category B, discount, Vilnius, driving school',
            ],
        ],
        [
            'slug' => 'naujas-instruktorius',
            'published_at' => '2026-02-05',
            'seo_score' => 72,
            'lt' => [
                'title' => 'Komandoje — naujas instruktorius Andrius (LT / EN)',
                'excerpt' => 'Patyręs instruktorius prisijungia prie BE ir B kursų. Užrašymai atviri.',
                'body' => "Džiaugiamės pranešdami, kad prie komandos prisijungė instruktorius Andrius — 12 metų patirtis, mokymas lietuvių ir anglų kalbomis.\n\nGalite užsisakyti pamokas per kontaktų formą.",
                'seo_title' => 'Naujas instruktorius Vilniuje | Vairavimo mokykla',
                'seo_description' => 'Prie Vilniaus vairavimo mokyklos komandos prisijungė instruktorius Andrius. B ir BE kursai LT/EN. Užrašymai online.',
                'seo_keywords' => 'instruktorius, vairavimo mokykla, Vilnius, B kategorija',
            ],
            'uk' => [
                'title' => 'У команді — новий інструктор Andrius (LT / EN)',
                'excerpt' => 'Досвідчений інструктор приєднується до курсів BE та B. Запис відкритий.',
                'body' => "Раді повідомити, що до команди приєднався інструктор Andrius — 12 років досвіду, навчання литовською та англійською.\n\nЗапишіться на уроки через контактну форму.",
                'seo_title' => 'Новий інструктор Вільнюс | Автошкола',
                'seo_description' => 'До команди Вільнюської автошколи приєднався інструктор Andrius. Курси B і BE LT/EN. Запис online.',
                'seo_keywords' => 'інструктор, автошкола, Вільнюс, категорія B',
            ],
            'ru' => [
                'title' => 'В команде — новый инструктор Andrius (LT / EN)',
                'excerpt' => 'Опытный инструктор присоединяется к курсам BE и B. Запись открыта.',
                'body' => "Рады сообщить, что к команде присоединился инструктор Andrius — 12 лет опыта, обучение на литовском и английском.\n\nЗапишитесь на уроки через контактную форму.",
                'seo_title' => 'Новый инструктор Вильнюс | Автошкола',
                'seo_description' => 'К команде Вильнюсской автошколы присоединился инструктор Andrius. Курсы B и BE LT/EN. Запись online.',
                'seo_keywords' => 'инструктор, автошкола, Вильнюс, категория B',
            ],
            'en' => [
                'title' => 'Meet our new instructor Andrius (LT / EN)',
                'excerpt' => 'Experienced instructor joins BE and B courses. Bookings open.',
                'body' => "We're pleased to welcome instructor Andrius to the team — 12 years of experience, teaching in Lithuanian and English.\n\nBook lessons via the contact form.",
                'seo_title' => 'New instructor Vilnius | Driving School',
                'seo_description' => 'Instructor Andrius joins Vilnius Driving School. Category B and BE courses in LT/EN. Book online.',
                'seo_keywords' => 'instructor, driving school, Vilnius, category B',
            ],
        ],
    ];

    $out = [];
    foreach ($articles as $i => $art) {
        $id = 'news-demo-' . str_pad((string) ($i + 1), 3, '0', STR_PAD_LEFT);
        $row = [
            'id' => $id,
            'slug' => $art['slug'],
            'status' => 'published',
            'published_at' => $art['published_at'],
            'image' => '',
            'seo_score' => $art['seo_score'],
            'created_at' => $art['published_at'] . 'T10:00:00+02:00',
            'updated_at' => date('c'),
            'title' => [], 'excerpt' => [], 'body' => [],
            'seo_title' => [], 'seo_description' => [], 'seo_keywords' => [],
        ];
        foreach (['lt', 'uk', 'ru', 'en'] as $code) {
            $loc = $art[$code] ?? [];
            foreach (['title', 'excerpt', 'body', 'seo_title', 'seo_description', 'seo_keywords'] as $field) {
                $row[$field][$code] = $loc[$field] ?? '';
            }
        }
        $out[] = $row;
    }
    return $out;
}