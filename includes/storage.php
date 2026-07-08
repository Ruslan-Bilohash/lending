<?php
declare(strict_types=1);

function ld_data_path(string $file): string
{
    return __DIR__ . '/../data/' . $file;
}

function ld_settings_file(): string
{
    return ld_data_path('settings.json');
}

function ld_leads_file(): string
{
    return ld_data_path('leads.json');
}

function ld_langs_codes(): array
{
    return function_exists('ld_langs') ? array_keys(ld_langs()) : ['no', 'sv', 'pl', 'en', 'lt', 'uk', 'ru'];
}

function ld_default_settings(): array
{
    return [
        'active_template' => 8,
        'business_preset' => 'driving_school',
        'meta' => [
            'driving_premium_v' => 3,
        ],
        'currency' => 'kr',
        'category' => 'business',
        'business' => [
            'name' => [
                'no' => 'Oslo Trafikkskole',
                'sv' => 'Stockholm Trafikskola',
                'pl' => 'Szkoła Jazdy Warszawa',
                'lt' => 'Vilniaus Vairavimo Mokykla',
                'uk' => 'Автошкола Київ',
                'en' => 'London Driving School',
                'ru' => 'Автошкола Москва',
            ],
            'tagline' => [
                'no' => 'Klasse B · teori · intensivkurs — i Oslo siden 2012',
                'sv' => 'Körkort B · teori · intensivkurs — i Stockholm sedan 2012',
                'pl' => 'Kategoria B · teoria · kurs intensywny — w Warszawie od 2012',
                'lt' => 'B kategorija · teorija · intensyvus kursas — Vilniuje nuo 2012 m.',
                'uk' => 'Категорія B · теорія · інтенсив — у Києві з 2012 року',
                'en' => 'Category B · theory · intensive — in London since 2012',
                'ru' => 'Категория B · теория · интенсив — в Москве с 2012 года',
            ],
            'city' => ld_country_field('city_full'),
            'address' => ld_country_field('address'),
            'phone' => '+47 22 12 34 56',
            'email' => 'info@oslo-driving.demo',
            'hours' => [
                'no' => 'Man–fre 9:00–19:00 · lør 10:00–15:00',
                'sv' => 'Mån–fre 9:00–19:00 · lör 10:00–15:00',
                'pl' => 'Pon–pt 9:00–19:00 · sob 10:00–15:00',
                'lt' => 'Pr–Pn 9:00–19:00 · Št 10:00–15:00',
                'uk' => 'Пн–Пт 9:00–19:00 · Сб 10:00–15:00',
                'en' => 'Mon–Fri 9:00–19:00 · Sat 10:00–15:00',
                'ru' => 'Пн–Пт 9:00–19:00 · Сб 10:00–15:00',
            ],
        ],
        'hero' => [
            'cta' => [
                'no' => 'Be om tilbakeringing',
                'sv' => 'Begär återuppringning',
                'pl' => 'Poproś o oddzwonienie',
                'lt' => 'Užsisakyti skambutį',
                'uk' => 'Замовити дзвінок',
                'ru' => 'Заказать звонок',
                'en' => 'Request a callback',
            ],
            'cta2' => [
                'no' => 'Kurs og priser',
                'sv' => 'Kurser och priser',
                'pl' => 'Kursy i ceny',
                'lt' => 'Kursai ir kainos',
                'uk' => 'Курси та ціни',
                'ru' => 'Курсы и цены',
                'en' => 'Courses & prices',
            ],
            'visual_icon' => 'fa-car-side',
            'visual_label' => [
                'no' => 'Klasse B',
                'sv' => 'Klass B',
                'pl' => 'Kat. B',
                'lt' => 'B kategorija',
                'uk' => 'Категорія B',
                'en' => 'Category B',
                'ru' => 'Категория B',
            ],
            'visual_sub' => ld_country_field('city'),
        ],
        'sections' => [
            'services' => [
                'title' => [
                    'no' => 'Våre tjenester', 'sv' => 'Våra tjänster', 'pl' => 'Nasze usługi',
                    'lt' => 'Mūsų paslaugos', 'uk' => 'Наші послуги', 'en' => 'Our services', 'ru' => 'Наши услуги',
                ],
                'lead' => [
                    'no' => 'Klasse B, teori og intensivkurs i Oslo — forberedelse til Statens vegvesen.',
                    'sv' => 'Körkort B, teori och intensivkurs i Stockholm — förberedelse för Transportstyrelsen.',
                    'pl' => 'Kat. B, teoria i kurs intensywny w Warszawie — przygotowanie do WORD.',
                    'lt' => 'B, BE, teorija ir intensyvūs kursai Vilniuje — Regitra pasiruošimas.',
                    'uk' => 'B, BE, теорія та інтенсив у Києві — підготовка до іспиту МВС.',
                    'en' => 'Category B, theory and intensive courses in London — DVSA exam prep.',
                    'ru' => 'Категория B, теория и интенсив в Москве — подготовка к ГИБДД.',
                ],
            ],
            'team' => [
                'title' => [
                    'no' => 'Vårt team', 'sv' => 'Vårt team', 'pl' => 'Nasz zespół',
                    'lt' => 'Komanda', 'uk' => 'Команда', 'en' => 'Our team', 'ru' => 'Команда',
                ],
                'lead' => [
                    'no' => 'Erfarne instruktører i Oslo — norsk, engelsk og flere språk.',
                    'sv' => 'Erfarna instruktörer i Stockholm — svenska, engelska och fler språk.',
                    'pl' => 'Doświadczeni instruktorzy w Warszawie — polski, angielski i więcej.',
                    'lt' => 'Patyrę specialistai Vilniuje — lietuvių, ukrainiečių ir anglų kalbomis.',
                    'uk' => 'Досвідчена команда в Києві — українська, англійська та інші мови.',
                    'en' => 'Experienced instructors in London — English and multilingual support.',
                    'ru' => 'Опытные инструкторы в Москве — русский, английский и другие языки.',
                ],
            ],
            'faq' => [
                'title' => [
                    'no' => 'Ofte stilte spørsmål', 'sv' => 'Vanliga frågor', 'pl' => 'Najczęstsze pytania',
                    'lt' => 'Dažniausiai užduodami klausimai', 'uk' => 'Часті питання',
                    'en' => 'Frequently asked questions', 'ru' => 'Частые вопросы',
                ],
            ],
            'contact' => [
                'title' => [
                    'no' => 'Kontakt oss', 'sv' => 'Kontakta oss', 'pl' => 'Kontakt',
                    'lt' => 'Kontaktai', 'uk' => 'Контакт', 'en' => 'Contact us', 'ru' => 'Контакт',
                ],
                'lead' => [
                    'no' => 'Legg igjen telefon — vi ringer tilbake innen 15 min. Gratis rådgivning.',
                    'sv' => 'Lämna telefon — vi ringer tillbaka inom 15 min. Gratis rådgivning.',
                    'pl' => 'Zostaw telefon — oddzwonimy w 15 min. Bezpłatna konsultacja.',
                    'lt' => 'Palikite telefoną — perskambinsime per 15 min. Registracija nemokama.',
                    'uk' => 'Залиште телефон — передзвонимо за 15 хв. Консультація безкоштовна.',
                    'ru' => 'Оставьте телефон — перезвоним за 15 мин. Консультация бесплатна.',
                    'en' => 'Leave your number — we call back within 15 min. Free consultation.',
                ],
            ],
            'reviews' => [
                'title' => [
                    'no' => 'Google-anmeldelser', 'sv' => 'Google-omdömen', 'pl' => 'Opinie Google',
                    'lt' => 'Google atsiliepimai', 'uk' => 'Відгуки Google', 'en' => 'Google reviews', 'ru' => 'Отзывы Google',
                ],
                'lead' => [
                    'no' => 'Hva kundene sier om oss på Google Maps i Oslo.',
                    'sv' => 'Vad kunderna säger om oss på Google Maps i Stockholm.',
                    'pl' => 'Co klienci mówią o nas w Google Maps w Warszawie.',
                    'lt' => 'Ką klientai sako apie mus Google Maps Vilniuje.',
                    'uk' => 'Що клієнти кажуть про нас у Google Maps у Києві.',
                    'en' => 'What clients say about us on Google Maps in London.',
                    'ru' => 'Что клиенты говорят о нас в Google Maps в Москве.',
                ],
            ],
            'map' => [
                'title' => [
                    'no' => 'Finn oss', 'sv' => 'Hitta oss', 'pl' => 'Jak nas znaleźć',
                    'lt' => 'Kaip mus rasti', 'uk' => 'Як нас знайти', 'en' => 'Find us on the map', 'ru' => 'Как нас найти',
                ],
            ],
            'features' => [
                'title' => [
                    'no' => 'Hvorfor velge oss', 'sv' => 'Varför välja oss', 'pl' => 'Dlaczego my',
                    'lt' => 'Kodėl mes', 'uk' => 'Чому ми', 'en' => 'Why choose us', 'ru' => 'Почему мы',
                ],
            ],
            'gallery' => [
                'title' => [
                    'no' => 'Galleri', 'sv' => 'Galleri', 'pl' => 'Galeria',
                    'lt' => 'Galerija', 'uk' => 'Галерея', 'en' => 'Gallery', 'ru' => 'Галерея',
                ],
            ],
        ],
        'google' => [
            'maps_embed' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1999.5!2d10.738!3d59.913!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46416e61b267d039%3A0x7e92605fdfe1daa6!2sKarl%20Johans%20gate%2015%2C%200154%20Oslo!5e0!3m2!1sno!2sno!4v1700000000000!5m2!1sno!2sno',
            'maps_link' => 'https://www.google.com/maps/search/?api=1&query=Karl+Johans+gate+15+0154+Oslo',
            'reviews_url' => 'https://www.google.com/maps/search/?api=1&query=Karl+Johans+gate+15+0154+Oslo',
            'rating' => '4.9',
            'review_count' => '127',
        ],
        'reviews' => [
            [
                'author' => 'Erik N.',
                'rating' => '5',
                'date' => '2025-09',
                'text' => [
                    'no' => 'Beste trafikkskole i Oslo — besto oppkjøring på første forsøk!',
                    'sv' => 'Bästa trafikskolan i Stockholm — klarade uppkörningen på första försöket!',
                    'pl' => 'Najlepsza szkoła jazdy w Warszawie — zdałem egzamin za pierwszym razem!',
                    'lt' => 'Puiki mokykla Vilniuje — išlaikiau B kategoriją iš pirmo karto.',
                    'uk' => 'Чудова автошкола в Києві — склала категорію B з першого разу.',
                    'en' => 'Great driving school in London — passed first time!',
                    'ru' => 'Отличная автошкола в Москве — сдал с первого раза!',
                ],
            ],
            [
                'author' => 'Marta K.',
                'rating' => '5',
                'date' => '2025-06',
                'text' => [
                    'no' => 'Intensivkurs på 2 uker — alt tydelig, ingen stress. Anbefales!',
                    'sv' => 'Intensivkurs på 2 veckor — tydligt och stressfritt. Rekommenderas!',
                    'pl' => 'Kurs intensywny w 2 tygodnie — wszystko jasne, bez stresu. Polecam!',
                    'lt' => 'Intensyvus kursas per 2 savaites — viskas aiškiai. Rekomenduoju!',
                    'uk' => 'Інтенсив за 2 тижні — усе зрозуміло, без стресу. Рекомендую!',
                    'en' => 'Intensive in 2 weeks — clear and stress-free. Highly recommend!',
                    'ru' => 'Интенсив за 2 недели — всё понятно, без стресса. Рекомендую!',
                ],
            ],
            [
                'author' => 'Alex W.',
                'rating' => '5',
                'date' => '2025-03',
                'text' => [
                    'no' => 'Teori på norsk, praksis i Oslo — perfekt for nyankomne.',
                    'sv' => 'Teori på svenska, körning i Stockholm — perfekt för nyinflyttade.',
                    'pl' => 'Teoria po polsku, jazdy w Warszawie — idealne dla nowych mieszkańców.',
                    'lt' => 'Teorija lietuviškai, praktika Vilniuje — puiku expat.',
                    'uk' => 'Теорія українською, практика в Києві — ідеально для новачків.',
                    'en' => 'Theory in English, practice in London — perfect for expats.',
                    'ru' => 'Теория на русском, практика в Москве — идеально для новичков.',
                ],
            ],
        ],
        'stats' => [
            ['value' => '4 200+', 'label' => [
                'no' => 'Beståtte prøver', 'sv' => 'Godkända prov', 'pl' => 'Zdane egzaminy',
                'lt' => 'Išlaikyti egzaminai', 'uk' => 'Складені іспити', 'en' => 'Exams passed', 'ru' => 'Сданные экзамены',
            ]],
            ['value' => '98%', 'label' => [
                'no' => 'Teori første forsøk', 'sv' => 'Teori på första försöket', 'pl' => 'Teoria za pierwszym razem',
                'lt' => 'Pirmo bandymo teorija', 'uk' => 'Теорія з першого разу', 'en' => 'Theory first try', 'ru' => 'Теория с первого раза',
            ]],
            ['value' => '14', 'label' => [
                'no' => 'Instruktører', 'sv' => 'Instruktörer', 'pl' => 'Instruktorzy',
                'lt' => 'Specialistų', 'uk' => 'Спеціалістів', 'en' => 'Team members', 'ru' => 'Специалистов',
            ]],
            ['value' => '4.9', 'label' => [
                'no' => 'Google-vurdering', 'sv' => 'Google-betyg', 'pl' => 'Ocena Google',
                'lt' => 'Google įvertinimas', 'uk' => 'Рейтинг Google', 'en' => 'Google rating', 'ru' => 'Рейтинг Google',
            ]],
        ],
        'services' => [
            [
                'icon' => 'fa-car-side',
                'name' => [
                    'no' => 'Klasse B (personbil)', 'sv' => 'Körkort B (personbil)', 'pl' => 'Kat. B (samochód osobowy)',
                    'lt' => 'B kategorija (automobilis)', 'uk' => 'Категорія B (автомобіль)', 'en' => 'Category B (car)', 'ru' => 'Категория B (легковой)',
                ],
                'desc' => [
                    'no' => '40 teori + 30 kjøretimer i Oslo. Forberedelse til Statens vegvesen.',
                    'sv' => '40 teori + 30 körlektioner i Stockholm. Förberedelse för Transportstyrelsen.',
                    'pl' => '40 godz. teorii + 30 jazd w Warszawie. Przygotowanie do WORD.',
                    'lt' => '40 teorijos + 30 praktikos Vilniuje. Regitra pasiruošimas.',
                    'uk' => '40 год. теорії + 30 практики в Києві. Підготовка до іспиту МВС.',
                    'en' => '40 theory + 30 lessons in London. DVSA exam preparation.',
                    'ru' => '40 ч. теории + 30 практики в Москве. Подготовка к ГИБДД.',
                ],
                'price' => '890',
                'badge' => [
                    'no' => 'Mest populær', 'sv' => 'Mest populär', 'pl' => 'Najpopularniejszy',
                    'lt' => 'Populiariausias', 'uk' => 'Популярний', 'en' => 'Most popular', 'ru' => 'Популярный',
                ],
            ],
            [
                'icon' => 'fa-trailer',
                'name' => [
                    'no' => 'Klasse BE (tilhenger)', 'sv' => 'Körkort BE (släp)', 'pl' => 'Kat. BE (przyczepa)',
                    'lt' => 'BE kategorija (priekaba)', 'uk' => 'Категорія BE (причіп)', 'en' => 'Category BE (trailer)', 'ru' => 'Категория BE (прицеп)',
                ],
                'desc' => [
                    'no' => '5-dagers intensivkurs med klasse B-grunnlag i Oslo.',
                    'sv' => '5-dagars intensivkurs med B-behörighet i Stockholm.',
                    'pl' => '5-dniowy kurs intensywny z kat. B w Warszawie.',
                    'lt' => '5 dienų intensyvus kursas su B kategorijos baze Vilniuje.',
                    'uk' => '5-денний інтенсив з базою B у Києві.',
                    'en' => '5-day intensive with Category B base in London.',
                    'ru' => '5-дневный интенсив с базой B в Москве.',
                ],
                'price' => '320',
                'badge' => null,
            ],
            [
                'icon' => 'fa-book-open',
                'name' => [
                    'no' => 'Teorikurs', 'sv' => 'Teorikurs', 'pl' => 'Kurs teorii',
                    'lt' => 'Teorijos kursas', 'uk' => 'Курс теорії', 'en' => 'Theory course', 'ru' => 'Курс теории',
                ],
                'desc' => [
                    'no' => 'Online + klasserom. Øvelsesprøver for Statens vegvesen.',
                    'sv' => 'Online + klassrum. Övningsprov för Transportstyrelsen.',
                    'pl' => 'Online + sala. Testy próbne WORD.',
                    'lt' => 'Online + auditorija. Regitra testų simuliatorius.',
                    'uk' => 'Онлайн + аудиторія. Симулятор тестів МВС.',
                    'en' => 'Online + classroom. DVSA practice tests.',
                    'ru' => 'Онлайн + аудитория. Тренировочные тесты ГИБДД.',
                ],
                'price' => '180',
                'badge' => null,
            ],
            [
                'icon' => 'fa-bolt',
                'name' => [
                    'no' => 'Intensiv B (2 uker)', 'sv' => 'Intensiv B (2 veckor)', 'pl' => 'Intensywny B (2 tyg.)',
                    'lt' => 'Intensyvus B (2 sav.)', 'uk' => 'Інтенсив B (2 тиж.)', 'en' => 'Intensive B (2 wks)', 'ru' => 'Интенсив B (2 нед.)',
                ],
                'desc' => [
                    'no' => 'Daglig praksis i Oslo. Ideelt for studenter og nyankomne.',
                    'sv' => 'Daglig övning i Stockholm. Perfekt för studenter.',
                    'pl' => 'Codzienna jazda w Warszawie. Idealny dla studentów.',
                    'lt' => 'Kasdienė praktika Vilniuje. Idealu studentams.',
                    'uk' => 'Щоденна практика в Києві. Ідеально для студентів.',
                    'en' => 'Daily practice in London. Ideal for students and expats.',
                    'ru' => 'Ежедневная практика в Москве. Идеально для студентов.',
                ],
                'price' => '1 050',
                'badge' => [
                    'no' => 'Rask start', 'sv' => 'Snabb start', 'pl' => 'Szybki start',
                    'lt' => 'Greitas startas', 'uk' => 'Швидкий старт', 'en' => 'Fast track', 'ru' => 'Быстрый старт',
                ],
            ],
        ],
        'team' => [
            ['name' => 'Tomas Žukauskas', 'role' => ['lt' => 'Vyriausiasis instruktorius', 'uk' => 'Головний інструктор', 'en' => 'Lead instructor'], 'years' => '12', 'initials' => 'TŽ'],
            ['name' => 'Olena Koval', 'role' => ['lt' => 'Instruktorė (LT/UA/EN)', 'uk' => 'Інструктор (LT/UA/EN)', 'en' => 'Instructor (LT/UA/EN)'], 'years' => '8', 'initials' => 'OK'],
            ['name' => 'Mantas Petraitis', 'role' => ['lt' => 'Miesto vairavimas', 'uk' => 'Міське водіння', 'en' => 'City driving'], 'years' => '6', 'initials' => 'MP'],
            ['name' => 'Ieva Stankevičienė', 'role' => ['lt' => 'Teorijos dėstytoja', 'uk' => 'Викладач теорії', 'en' => 'Theory teacher'], 'years' => '10', 'initials' => 'IS'],
        ],
        'faq' => [
            [
                'q' => [
                    'no' => 'Hvor lang tid tar klasse B-kurset?', 'sv' => 'Hur lång tid tar B-kursen?', 'pl' => 'Ile trwa kurs kat. B?',
                    'lt' => 'Kiek trunka B kategorijos kursas?', 'uk' => 'Скільки триває курс категорії B?', 'en' => 'How long is the Category B course?', 'ru' => 'Сколько длится курс категории B?',
                ],
                'a' => [
                    'no' => 'Standard — 6–8 uker. Intensiv — 2 uker i Oslo.', 'sv' => 'Standard — 6–8 veckor. Intensiv — 2 veckor i Stockholm.', 'pl' => 'Standard — 6–8 tygodni. Intensywny — 2 tygodnie w Warszawie.',
                    'lt' => 'Standartinis — 6–8 savaitės. Intensyvus — 2 savaitės Vilniuje.', 'uk' => 'Стандарт — 6–8 тижнів. Інтенсив — 2 тижні в Києві.', 'en' => 'Standard — 6–8 weeks. Intensive — 2 weeks in London.', 'ru' => 'Стандарт — 6–8 недель. Интенсив — 2 недели в Москве.',
                ],
            ],
            [
                'q' => [
                    'no' => 'Hvilke språk tilbyr dere?', 'sv' => 'Vilka språk erbjuder ni?', 'pl' => 'W jakich językach prowadzicie kursy?',
                    'lt' => 'Kokiomis kalbomis mokote?', 'uk' => 'Якими мовами ведете навчання?', 'en' => 'Which languages do you offer?', 'ru' => 'На каких языках ведётся обучение?',
                ],
                'a' => [
                    'no' => 'Norsk, engelsk og flere språk — instruktører i Oslo.', 'sv' => 'Svenska, engelska och fler — instruktörer i Stockholm.', 'pl' => 'Polski, angielski i więcej — instruktorzy w Warszawie.',
                    'lt' => 'Lietuvių, ukrainiečių ir anglų — specialistai Vilniuje.', 'uk' => 'Українська, англійська та інші — інструктори в Києві.', 'en' => 'English and multilingual support in London.', 'ru' => 'Русский, английский и другие — инструкторы в Москве.',
                ],
            ],
            [
                'q' => [
                    'no' => 'Hvor foregår praksisen i Oslo?', 'sv' => 'Var sker övningskörningen i Stockholm?', 'pl' => 'Gdzie odbywają się jazdy w Warszawie?',
                    'lt' => 'Kur vyksta praktika Vilniuje?', 'uk' => 'Де проходить практика в Києві?', 'en' => 'Where is practice held in London?', 'ru' => 'Где проходит практика в Москве?',
                ],
                'a' => [
                    'no' => 'Start Karl Johans gate — deretter by og Statens vegvesen-ruter.', 'sv' => 'Start Drottninggatan — sedan stad och Transportstyrelsen-rutter.', 'pl' => 'Start Marszałkowska — potem miasto i trasy WORD.',
                    'lt' => 'Start Konstitucijos pr. — miesto ir Regitra maršrutai.', 'uk' => 'Старт Хрещатик — міські маршрути та іспит МВС.', 'en' => 'Start Baker Street — city and DVSA test routes.', 'ru' => 'Старт Тверская — городские маршруты и экзамен ГИБДД.',
                ],
            ],
            [
                'q' => [
                    'no' => 'Trenger jeg egen bil?', 'sv' => 'Behöver jag egen bil?', 'pl' => 'Czy potrzebuję własnego auta?',
                    'lt' => 'Ar reikia savo automobilio?', 'uk' => 'Чи потрібен свій автомобіль?', 'en' => 'Do I need my own car?', 'ru' => 'Нужен ли свой автомобиль?',
                ],
                'a' => [
                    'no' => 'Nei — vi bruker opplæringsbiler med dobbelt kontroll.', 'sv' => 'Nej — vi använder övningsbilar med dubbel pedaler.', 'pl' => 'Nie — używamy samochodów szkoleniowych z podwójnymi pedałami.',
                    'lt' => 'Ne — mokymo automobiliai su dvigubomis pedalėmis.', 'uk' => 'Ні — навчальні авто з подвійним керуванням.', 'en' => 'No — we use dual-control training cars.', 'ru' => 'Нет — учебные авто с двойным управлением.',
                ],
            ],
        ],
        'seo' => array_merge(ld_driving_seo_defaults(), [
            'og_image' => 'https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?w=1200&h=630&fit=crop&q=85',
        ]),
        'blocks' => [
            'hero_image' => 'https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?w=900&h=600&fit=crop',
            'gallery' => [
                ['url' => 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?w=800&h=600&fit=crop', 'caption' => ['lt' => 'Mokymo automobiliai', 'uk' => 'Навчальні авто', 'en' => 'Training cars']],
                ['url' => 'https://images.unsplash.com/photo-1502877338535-766e1452684a?w=800&h=600&fit=crop', 'caption' => ['lt' => 'Praktika mieste', 'uk' => 'Практика в місті', 'en' => 'City practice']],
                ['url' => 'https://images.unsplash.com/photo-1517048676732-d65bc937f952?w=800&h=600&fit=crop', 'caption' => ['lt' => 'Teorijos auditorija', 'uk' => 'Аудиторія теорії', 'en' => 'Theory classroom']],
            ],
            'features' => [
                ['icon' => 'fa-certificate', 'title' => ['lt' => '98% sėkmė teorijoje', 'uk' => '98% успіх теорії', 'ru' => '98% успех теории', 'en' => '98% theory pass rate'], 'desc' => ['lt' => 'Regitra simuliatorius ir patyrę dėstytojai.', 'uk' => 'Симулятор Regitra та досвідчені викладачі.', 'ru' => 'Симулятор Regitra и опытные преподаватели.', 'en' => 'Regitra simulator and experienced teachers.']],
                ['icon' => 'fa-language', 'title' => ['no' => 'NO · SV · PL · EN', 'sv' => 'NO · SV · PL · EN', 'pl' => 'NO · SV · PL · EN', 'lt' => 'NO · SV · PL · EN', 'uk' => 'NO · SV · PL · EN', 'ru' => 'NO · SV · PL · EN', 'en' => 'NO · SV · PL · EN'], 'desc' => ['no' => 'Instruktører og teori på 7 språk.', 'sv' => 'Instruktörer och teori på 7 språk.', 'pl' => 'Instruktorzy i teoria w 7 językach.', 'lt' => 'Instruktoriai ir teorija septyniomis kalbomis.', 'uk' => 'Інструктори та теорія сімома мовами.', 'ru' => 'Инструкторы и теория на семи языках.', 'en' => 'Instructors and theory in seven languages.']],
                ['icon' => 'fa-calendar-check', 'title' => ['lt' => 'Lankstus grafikas', 'uk' => 'Гнучкий графік', 'ru' => 'Гибкий график', 'en' => 'Flexible schedule'], 'desc' => ['lt' => 'Vakarai, savaitgaliai ir intensyvūs kursai.', 'uk' => 'Вечори, вихідні та інтенсиви.', 'ru' => 'Вечера, выходные и интенсивы.', 'en' => 'Evenings, weekends and intensive groups.']],
                ['icon' => 'fa-car-side', 'title' => ['lt' => 'Šiuolaikiški auto', 'uk' => 'Сучасні авто', 'ru' => 'Современные авто', 'en' => 'Modern training cars'], 'desc' => ['lt' => 'Dvigubos pedalės, kondicionierius, saugumas.', 'uk' => 'Подвійне керування, кондиціонер.', 'ru' => 'Двойное управление, кондиционер.', 'en' => 'Dual controls, A/C, safety first.']],
                ['icon' => 'fa-phone-volume', 'title' => ['lt' => 'Perskambiname', 'uk' => 'Передзвонюємо', 'ru' => 'Перезвоним', 'en' => 'We call you back'], 'desc' => ['lt' => 'Užpildykite formą — skambiname per 15 min.', 'uk' => 'Залиште номер — передзвонимо за 15 хв.', 'ru' => 'Оставьте номер — перезвоним за 15 мин.', 'en' => 'Submit the form — callback within 15 min.']],
                ['icon' => 'fa-map-location-dot', 'title' => [
                    'no' => 'Oslo sentrum', 'sv' => 'Stockholm centrum', 'pl' => 'Centrum Warszawy',
                    'lt' => 'Vilniaus centras', 'uk' => 'Центр Києва', 'en' => 'Central London', 'ru' => 'Центр Москвы',
                ], 'desc' => [
                    'no' => 'Karl Johans gate — lett tilgjengelig fra hele byen.', 'sv' => 'Drottninggatan — lätt att nå från hela staden.', 'pl' => 'Marszałkowska — dogodny dojazd z całego miasta.',
                    'lt' => 'Konstitucijos pr. — patogu iš visų rajonų.', 'uk' => 'Хрещатик — зручно з усіх районів.', 'en' => 'Baker Street — easy access city-wide.', 'ru' => 'Тверская — удобно из всех районов.',
                ]],
            ],
            'process' => [
                'enabled' => true,
                'steps' => [
                    ['icon' => 'fa-file-signature', 'title' => ['lt' => 'Registracija', 'uk' => 'Реєстрація', 'ru' => 'Регистрация', 'en' => 'Sign up'], 'desc' => ['lt' => 'Užpildykite formą arba paskambinkite — patarsime dėl kurso.', 'uk' => 'Заповніть форму — підберемо курс.', 'ru' => 'Заполните форму — подберём курс.', 'en' => 'Fill the form — we advise on the right course.']],
                    ['icon' => 'fa-book-open', 'title' => ['lt' => 'Teorija', 'uk' => 'Теорія', 'ru' => 'Теория', 'en' => 'Theory'], 'desc' => ['lt' => 'Online + auditorija, Regitra testų simuliatorius.', 'uk' => 'Онлайн + аудиторія, симулятор Regitra.', 'ru' => 'Онлайн + аудитория, симулятор Regitra.', 'en' => 'Online + classroom, Regitra test simulator.']],
                    ['icon' => 'fa-road', 'title' => ['lt' => 'Praktika', 'uk' => 'Практика', 'ru' => 'Практика', 'en' => 'Practice'], 'desc' => ['lt' => 'Miesto ir Regitra maršrutai su patyrusiais instruktoriais.', 'uk' => 'Місто та маршрути Regitra з інструкторами.', 'ru' => 'Город и маршруты Regitra с инструкторами.', 'en' => 'City and Regitra routes with expert instructors.']],
                    ['icon' => 'fa-trophy', 'title' => ['lt' => 'Egzaminas', 'uk' => 'Іспит', 'ru' => 'Экзамен', 'en' => 'Exam'], 'desc' => ['lt' => 'Pasiruošimas Regitra teorijai ir praktikai — 98% sėkmė.', 'uk' => 'Підготовка до Regitra — 98% успіху.', 'ru' => 'Подготовка к Regitra — 98% успеха.', 'en' => 'Regitra theory & driving prep — 98% pass rate.']],
                ],
            ],
            'links' => [
                ['icon' => 'fab fa-facebook', 'url' => 'https://facebook.com/', 'label' => ['lt' => 'Facebook', 'uk' => 'Facebook', 'en' => 'Facebook']],
                ['icon' => 'fab fa-instagram', 'url' => 'https://instagram.com/', 'label' => ['lt' => 'Instagram', 'uk' => 'Instagram', 'en' => 'Instagram']],
                ['icon' => 'fab fa-google', 'url' => 'https://www.google.com/maps/search/?api=1&query=Karl+Johans+gate+15+Oslo', 'label' => [
                    'no' => 'Google Maps', 'sv' => 'Google Maps', 'pl' => 'Google Maps',
                    'lt' => 'Google Maps', 'uk' => 'Google Maps', 'en' => 'Google Maps', 'ru' => 'Google Maps',
                ]],
            ],
            'cta' => [
                'enabled' => true,
                'title' => ['lt' => 'Norite, kad perskambintume?', 'uk' => 'Хочете, щоб ми передзвонили?', 'ru' => 'Хотите, чтобы мы перезвонили?', 'en' => 'Want us to call you back?'],
                'lead' => ['lt' => 'Palikite telefoną — paskambinsime per 15 minučių. Konsultacija nemokama.', 'uk' => 'Залиште номер — передзвонимо за 15 хвилин. Консультація безкоштовна.', 'ru' => 'Оставьте номер — перезвоним за 15 минут. Консультация бесплатна.', 'en' => 'Leave your number — we call within 15 minutes. Free consultation.'],
                'phone' => '+47 22 12 34 56',
            ],
            'video' => [
                'enabled' => true,
                'url' => 'https://www.youtube.com/watch?v=LXb3EKWsInQ',
                'title' => ['lt' => 'Kaip atrodo mokymas', 'uk' => 'Як виглядає навчання', 'ru' => 'Как проходит обучение', 'en' => 'See how we train'],
            ],
            'partners' => [
                ['name' => 'Statens vegvesen', 'logo' => ''],
                ['name' => 'Google', 'logo' => 'https://www.gstatic.com/images/branding/product/1x/googleg_48dp.png'],
            ],
            'promo' => [
                'enabled' => true,
                'badge' => ['lt' => 'Nemokama konsultacija', 'uk' => 'Безкоштовна консультація', 'ru' => 'Бесплатная консультация', 'en' => 'Free consultation'],
                'title' => ['lt' => 'Perskambinsime per 15 min', 'uk' => 'Передзвонимо за 15 хв', 'ru' => 'Перезвоним за 15 мин', 'en' => 'We call you back in 15 min'],
                'text' => ['lt' => 'Užpildykite formą — patarsime dėl B, BE ar intensyvaus kurso.', 'uk' => 'Заповніть форму — підкажемо щодо B, BE чи інтенсиву.', 'ru' => 'Заполните форму — подскажем по B, BE или интенсиву.', 'en' => 'Submit the form — we advise on B, BE or intensive courses.'],
            ],
        ],
        'design' => [
            'accent' => '',
            'button_style' => 'rounded',
            'font_scale' => '100',
            'hero_style' => 'default',
            'sections' => [
                'stats' => true,
                'services' => true,
                'features' => true,
                'gallery' => true,
                'video' => true,
                'partners' => true,
                'promo' => true,
                'team' => true,
                'reviews' => true,
                'map' => true,
                'faq' => true,
                'contact' => true,
            ],
        ],
        'legal' => [
            'consent_required' => true,
            'privacy_slug' => 'privacy',
            'privacy_url' => '',
            'consent' => [
                'no' => 'Jeg godtar personvernerklæringen og behandling av mine opplysninger.',
                'sv' => 'Jag godkänner integritetspolicyn och behandling av mina uppgifter.',
                'pl' => 'Akceptuję politykę prywatności i przetwarzanie moich danych.',
                'lt' => 'Sutinku su privatumo politika ir duomenų tvarkymu.',
                'uk' => 'Погоджуюсь з політикою конфіденційності та обробкою даних.',
                'en' => 'I agree to the privacy policy and data processing.',
                'ru' => 'Согласен с политикой конфиденциальности и обработкой данных.',
            ],
        ],
        'recaptcha' => [
            'enabled' => false,
            'site_key' => '',
            'secret_key' => '',
        ],
        'ai' => [
            'enabled' => true,
            'fill_enabled' => true,
            'provider' => 'openai',
            'api_key' => '',
            'api_base' => 'https://api.openai.com/v1',
            'model' => 'gpt-4o-mini',
            'admin_model' => 'gpt-4o',
            'welcome' => [
                'lt' => 'Labas! Esu AI asistentas. Klauskite apie kursus, kainas ar registraciją.',
                'uk' => 'Привіт! Я AI-асистент. Запитуйте про курси, ціни чи запис.',
                'en' => 'Hi! I am the AI assistant. Ask about courses, prices or enrollment.',
            ],
            'system_prompt' => 'You are a helpful assistant for {business_name} ({city}). Answer briefly about services, prices, schedule and contact. Language: {lang}. Plain text only.',
        ],
        'integrations' => [
            'faktura' => [
                'enabled' => true,
                'country_id' => 'no',
                'auto_invoice' => false,
                'print_design' => 'classic-blue',
                'print_format' => 'a4',
            ],
        ],
        'notifications' => [
            'leads_enabled' => true,
            'leads_email' => '',
        ],
    ];
}

function ld_normalize_settings(array $saved, array $defaults): array
{
    if (!empty($saved['school']) && empty($saved['business'])) {
        $saved['business'] = $saved['school'];
    }
    unset($saved['school']);

    $settings = array_replace_recursive($defaults, $saved);

    foreach (['stats', 'services', 'team', 'faq', 'reviews'] as $listKey) {
        if (empty($settings[$listKey]) || !is_array($settings[$listKey])) {
            $settings[$listKey] = $defaults[$listKey];
        }
    }

    if (empty($settings['google']) || !is_array($settings['google'])) {
        $settings['google'] = $defaults['google'];
    } else {
        $settings['google'] = array_replace($defaults['google'], $settings['google']);
        foreach ($defaults['google'] as $gKey => $gVal) {
            if (($settings['google'][$gKey] ?? '') === '') {
                $settings['google'][$gKey] = $gVal;
            }
        }
    }

    foreach (['reviews', 'map'] as $sectionKey) {
        if (empty($settings['sections'][$sectionKey]['title']) && !empty($defaults['sections'][$sectionKey])) {
            $settings['sections'][$sectionKey] = array_replace_recursive(
                $defaults['sections'][$sectionKey],
                $settings['sections'][$sectionKey] ?? []
            );
        }
    }

    foreach (['seo', 'blocks', 'legal', 'recaptcha', 'ai', 'integrations', 'notifications', 'design'] as $cfgKey) {
        if (empty($settings[$cfgKey]) || !is_array($settings[$cfgKey])) {
            $settings[$cfgKey] = $defaults[$cfgKey] ?? [];
        } else {
            $settings[$cfgKey] = array_replace_recursive($defaults[$cfgKey] ?? [], $settings[$cfgKey]);
        }
    }

    if (empty($settings['blocks']['gallery'])) {
        $settings['blocks']['gallery'] = $defaults['blocks']['gallery'] ?? [];
    }
    if (empty($settings['blocks']['features'])) {
        $settings['blocks']['features'] = $defaults['blocks']['features'] ?? [];
    }

    return $settings;
}

function ld_load_json(string $path, array $fallback): array
{
    if (!is_file($path)) {
        return $fallback;
    }
    $raw = file_get_contents($path);
    if ($raw === false || $raw === '') {
        return $fallback;
    }
    $data = json_decode($raw, true);
    return is_array($data) ? $data : $fallback;
}

function ld_save_json(string $path, array $data): bool
{
    $dir = dirname($path);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    if ($json === false) {
        return false;
    }
    return file_put_contents($path, $json . "\n", LOCK_EX) !== false;
}

function ld_ensure_settings(): void
{
    $file = ld_settings_file();
    if (!is_file($file)) {
        ld_save_json($file, ld_default_settings());
    }
}

function ld_ensure_leads(): void
{
    $file = ld_leads_file();
    if (!is_file($file)) {
        ld_save_json($file, []);
    }
}

function ld_migrate_driving_premium(): void
{
    $file = ld_settings_file();
    if (!is_file($file)) {
        return;
    }
    $saved = ld_load_json($file, []);
    if (($saved['business_preset'] ?? 'driving_school') !== 'driving_school') {
        return;
    }
    $version = (int) ($saved['meta']['driving_premium_v'] ?? 0);
    if ($version >= 4) {
        return;
    }
    $defaults = ld_default_settings();
    $merged = $saved;
    foreach (['hero', 'sections', 'seo', 'google', 'blocks', 'stats', 'services', 'team', 'faq', 'reviews', 'design', 'business', 'notifications'] as $key) {
        if (isset($defaults[$key])) {
            $merged[$key] = array_replace_recursive($merged[$key] ?? [], $defaults[$key]);
        }
    }
    $merged['active_template'] = (int) ($defaults['active_template'] ?? 8);
    $merged['meta']['driving_premium_v'] = 4;
    ld_save_json($file, $merged);
}

function ld_bootstrap_data(): void
{
    ld_ensure_settings();
    ld_migrate_driving_premium();
    ld_ensure_leads();
    ld_ensure_news();
    ld_ensure_pages();
    ld_ensure_demo_invoices();
    ld_ensure_students();
    ld_seed_admin_demo();
}

function ld_settings(): array
{
    $defaults = ld_default_settings();
    $saved = ld_load_json(ld_settings_file(), []);
    return ld_normalize_settings($saved, $defaults);
}

function ld_save_settings(array $settings): bool
{
    return ld_save_json(ld_settings_file(), $settings);
}

function ld_active_template(): int
{
    $id = (int) (ld_settings()['active_template'] ?? 1);
    return max(1, min(10, $id));
}

function ld_load_leads(): array
{
    return ld_load_json(ld_leads_file(), []);
}

function ld_save_leads(array $leads): bool
{
    return ld_save_json(ld_leads_file(), $leads);
}

function ld_add_lead(array $lead): bool
{
    $leads = ld_load_leads();
    $lead['id'] = 'ld-' . date('Ymd-His') . '-' . substr(bin2hex(random_bytes(4)), 0, 8);
    $lead['created_at'] = date('c');
    $lead['status'] = $lead['status'] ?? 'new';
    array_unshift($leads, $lead);
    if (count($leads) > 500) {
        $leads = array_slice($leads, 0, 500);
    }
    return ld_save_leads($leads);
}

function ld_update_lead(string $id, array $patch): bool
{
    $leads = ld_load_leads();
    $found = false;
    foreach ($leads as $i => $lead) {
        if (($lead['id'] ?? '') === $id) {
            $leads[$i] = array_merge($lead, $patch);
            $found = true;
            break;
        }
    }
    return $found && ld_save_leads($leads);
}

function ld_get_lead(string $id): ?array
{
    foreach (ld_load_leads() as $lead) {
        if (($lead['id'] ?? '') === $id) {
            return $lead;
        }
    }
    return null;
}