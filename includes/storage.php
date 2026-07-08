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
                'sv' => 'Oslo Trafikkskola',
                'pl' => 'Szkoła Jazdy Oslo',
                'lt' => 'Oslos Vairavimo Mokykla',
                'uk' => 'Автошкола Осло',
                'en' => 'Oslo Driving School',
                'ru' => 'Автошкола Осло',
            ],
            'tagline' => [
                'no' => 'Klasse B · teori · intensivkurs — i Oslo siden 2012',
                'sv' => 'Klass B · teori · intensivkurs — i Oslo sedan 2012',
                'pl' => 'Kategoria B · teoria · kurs intensywny — w Oslo od 2012',
                'lt' => 'B kategorija · teorija · intensyvus kursas — Oslė nuo 2012 m.',
                'uk' => 'Категорія B · теорія · інтенсив — в Осло з 2012 року',
                'en' => 'Category B · theory · intensive — in Oslo since 2012',
                'ru' => 'Категория B · теория · интенсив — в Осло с 2012 года',
            ],
            'city' => [
                'no' => 'Oslo, Norge',
                'sv' => 'Oslo, Norge',
                'pl' => 'Oslo, Norwegia',
                'lt' => 'Oslas, Norvegija',
                'uk' => 'Осло, Норвегія',
                'en' => 'Oslo, Norway',
                'ru' => 'Осло, Норвегия',
            ],
            'address' => [
                'no' => 'Karl Johans gate 15, 0154 Oslo',
                'sv' => 'Karl Johans gate 15, 0154 Oslo',
                'pl' => 'Karl Johans gate 15, 0154 Oslo',
                'lt' => 'Karl Johans gate 15, 0154 Oslo',
                'uk' => 'Karl Johans gate 15, 0154 Oslo',
                'en' => 'Karl Johans gate 15, 0154 Oslo',
                'ru' => 'Karl Johans gate 15, 0154 Oslo',
            ],
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
            'visual_sub' => [
                'no' => 'Oslo',
                'sv' => 'Oslo',
                'pl' => 'Oslo',
                'lt' => 'Oslas',
                'uk' => 'Осло',
                'en' => 'Oslo',
                'ru' => 'Осло',
            ],
        ],
        'sections' => [
            'services' => [
                'title' => [
                    'lt' => 'Mūsų paslaugos',
                    'uk' => 'Наші послуги',
                    'en' => 'Our services',
                ],
                'lead' => [
                    'lt' => 'B, BE, teorija ir intensyvūs kursai Vilniuje.',
                    'uk' => 'B, BE, теорія та інтенсив у Вільнюсі.',
                    'en' => 'B, BE, theory and intensive courses in Vilnius.',
                ],
            ],
            'team' => [
                'title' => [
                    'lt' => 'Komanda',
                    'uk' => 'Команда',
                    'en' => 'Our team',
                ],
                'lead' => [
                    'lt' => 'Patyrę specialistai — lietuvių, ukrainiečių ir anglų kalbomis.',
                    'uk' => 'Досвідчена команда — литовська, українська та англійська.',
                    'en' => 'Experienced team — Lithuanian, Ukrainian and English.',
                ],
            ],
            'faq' => [
                'title' => [
                    'lt' => 'Dažniausiai užduodami klausimai',
                    'uk' => 'Часті питання',
                    'en' => 'Frequently asked questions',
                ],
            ],
            'contact' => [
                'title' => [
                    'lt' => 'Kontaktai',
                    'uk' => 'Контакт',
                    'en' => 'Contact us',
                ],
                'lead' => [
                    'lt' => 'Palikite telefoną — perskambinsime per 15 min. Registracija ir konsultacija nemokama.',
                    'uk' => 'Залиште телефон — передзвонимо за 15 хв. Запис і консультація безкоштовні.',
                    'ru' => 'Оставьте телефон — перезвоним за 15 мин. Запись и консультация бесплатны.',
                    'en' => 'Leave your number — we call you back within 15 minutes. Enrollment advice is free.',
                ],
            ],
            'reviews' => [
                'title' => [
                    'lt' => 'Google atsiliepimai',
                    'uk' => 'Відгуки Google',
                    'en' => 'Google reviews',
                ],
                'lead' => [
                    'lt' => 'Ką sako mūsų klientai Google žemėlapiuose.',
                    'uk' => 'Що кажуть клієнти в Google Maps.',
                    'en' => 'What our clients say on Google Maps.',
                ],
            ],
            'map' => [
                'title' => [
                    'lt' => 'Kaip mus rasti',
                    'uk' => 'Як нас знайти',
                    'en' => 'Find us on the map',
                ],
            ],
            'features' => [
                'title' => [
                    'lt' => 'Kodėl mes',
                    'uk' => 'Чому ми',
                    'en' => 'Why choose us',
                ],
            ],
            'gallery' => [
                'title' => [
                    'lt' => 'Galerija',
                    'uk' => 'Галерея',
                    'en' => 'Gallery',
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
                'author' => 'Jonas Petraitis',
                'rating' => '5',
                'date' => '2025-09',
                'text' => [
                    'lt' => 'Puiki mokykla — išlaikiau B kategoriją iš pirmo karto. Instruktorė Olena kalba ukrainiečių kalba.',
                    'uk' => 'Чудова школа — склала категорію B з першого разу. Інструктор Олена говорить українською.',
                    'en' => 'Great school — passed Category B first try. Instructor Olena speaks Ukrainian.',
                ],
            ],
            [
                'author' => 'Marta K.',
                'rating' => '5',
                'date' => '2025-06',
                'text' => [
                    'lt' => 'Intensyvus kursas per 2 savaites — viskas aiškiai, be streso. Rekomenduoju!',
                    'uk' => 'Інтенсив за 2 тижні — усе зрозуміло, без стресу. Рекомендую!',
                    'en' => 'Intensive course in 2 weeks — clear and stress-free. Highly recommend!',
                ],
            ],
            [
                'author' => 'Alex W.',
                'rating' => '5',
                'date' => '2025-03',
                'text' => [
                    'lt' => 'Theory in English, practice in Vilnius — perfect for expats.',
                    'uk' => 'Теорія англійською, практика у Вільнюсі — ідеально для expat.',
                    'en' => 'Theory in English, practice in Vilnius — perfect for expats.',
                ],
            ],
        ],
        'stats' => [
            ['value' => '4 200+', 'label' => ['lt' => 'Išlaikyti egzaminai', 'uk' => 'Складені іспити', 'en' => 'Exams passed']],
            ['value' => '98%', 'label' => ['lt' => 'Pirmo bandymo teorija', 'uk' => 'Теорія з першого разу', 'en' => 'Theory first try']],
            ['value' => '14', 'label' => ['lt' => 'Specialistų', 'uk' => 'Спеціалістів', 'en' => 'Team members']],
            ['value' => '4.9', 'label' => ['lt' => 'Google įvertinimas', 'uk' => 'Рейтинг Google', 'en' => 'Google rating']],
        ],
        'services' => [
            [
                'icon' => 'fa-car-side',
                'name' => ['lt' => 'B kategorija (automobilis)', 'uk' => 'Категорія B (автомобіль)', 'en' => 'Category B (car)'],
                'desc' => [
                    'lt' => '40 teorijos + 30 praktikos pamokų. Vakariniai ir savaitgalio grafikai.',
                    'uk' => '40 теорії + 30 практики. Вечірні та вихідні групи.',
                    'en' => '40 theory + 30 driving lessons. Evening and weekend groups.',
                ],
                'price' => '890',
                'badge' => ['lt' => 'Populiariausias', 'uk' => 'Популярний', 'en' => 'Most popular'],
            ],
            [
                'icon' => 'fa-trailer',
                'name' => ['lt' => 'BE kategorija (priekaba)', 'uk' => 'Категорія BE (причіп)', 'en' => 'Category BE (trailer)'],
                'desc' => [
                    'lt' => '5 dienų intensyvus kursas su B kategorijos baze.',
                    'uk' => '5-денний інтенсив з базою категорії B.',
                    'en' => '5-day intensive with Category B base.',
                ],
                'price' => '320',
                'badge' => null,
            ],
            [
                'icon' => 'fa-book-open',
                'name' => ['lt' => 'Teorijos kursas', 'uk' => 'Курс теорії', 'en' => 'Theory course'],
                'desc' => [
                    'lt' => 'Online + auditorija. Regitra testų simuliatorius.',
                    'uk' => 'Онлайн + аудиторія. Симулятор тестів Regitra.',
                    'en' => 'Online + classroom. Regitra test simulator.',
                ],
                'price' => '180',
                'badge' => null,
            ],
            [
                'icon' => 'fa-bolt',
                'name' => ['lt' => 'Intensyvus B (2 sav.)', 'uk' => 'Інтенсив B (2 тиж.)', 'en' => 'Intensive B (2 wks)'],
                'desc' => [
                    'lt' => 'Kasdienė praktika. Idealu studentams ir expat.',
                    'uk' => 'Щоденна практика. Ідеально для студентів та expat.',
                    'en' => 'Daily practice. Ideal for students and expats.',
                ],
                'price' => '1 050',
                'badge' => ['lt' => 'Greitas startas', 'uk' => 'Швидкий старт', 'en' => 'Fast track'],
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
                'q' => ['lt' => 'Kiek trunka B kategorijos kursas?', 'uk' => 'Скільки триває курс категорії B?', 'en' => 'How long is the Category B course?'],
                'a' => ['lt' => 'Standartinis kursas — 6–8 savaitės. Intensyvus — 2 savaitės.', 'uk' => 'Стандарт — 6–8 тижнів. Інтенсив — 2 тижні.', 'en' => 'Standard — 6–8 weeks. Intensive — 2 weeks.'],
            ],
            [
                'q' => ['lt' => 'Ar galima mokytis anglų ar ukrainiečių kalba?', 'uk' => 'Чи можна навчатися англійською чи українською?', 'en' => 'Can I learn in English or Ukrainian?'],
                'a' => ['lt' => 'Taip — turime specialistus, kalbančius LT, UA ir EN.', 'uk' => 'Так — є спеціалісти LT, UA та EN.', 'en' => 'Yes — we have team members speaking LT, UA and EN.'],
            ],
            [
                'q' => ['lt' => 'Kur vyksta praktika Vilniuje?', 'uk' => 'Де проходить практика у Вільнюсі?', 'en' => 'Where is practice held in Vilnius?'],
                'a' => ['lt' => 'Pradžia Konstitucijos pr. — tada miesto ir Regitra maršrutai.', 'uk' => 'Старт пр. Конституції — далі місто та маршрути Regitra.', 'en' => 'Start at Konstitucijos av. — then city and Regitra routes.'],
            ],
            [
                'q' => ['lt' => 'Ar reikia savo automobilio?', 'uk' => 'Чи потрібен свій автомобіль?', 'en' => 'Do I need my own car?'],
                'a' => ['lt' => 'Ne — naudojame mokymo automobilius su dvigubomis pedalėmis.', 'uk' => 'Ні — навчальні авто з подвійним керуванням.', 'en' => 'No — we use dual-control training cars.'],
            ],
        ],
        'seo' => [
            'title' => [
                'no' => 'Oslo Trafikkskole — klasse B | Forberedelse til Statens vegvesen',
                'sv' => 'Oslo Trafikkskola — klass B | Förberedelse till Statens vegvesen',
                'pl' => 'Szkoła Jazdy Oslo — kat. B | Przygotowanie do Statens vegvesen',
                'lt' => 'Oslos vairavimo mokykla — B kategorija | Statens vegvesen pasiruošimas',
                'uk' => 'Автошкола Осло — категорія B | Підготовка до Statens vegvesen',
                'ru' => 'Автошкола Осло — категория B | Подготовка к Statens vegvesen',
                'en' => 'Oslo Driving School — Category B | Statens vegvesen exam prep',
            ],
            'description' => [
                'no' => 'Profesjonell trafikkskole i Oslo: klasse B, teori, intensivkurs. Vi ringer tilbake innen 15 min. NO / SV / PL / EN / LT / UA / RU.',
                'sv' => 'Professionell trafikkskola i Oslo: klass B, teori, intensivkurs. Vi ringer tillbaka inom 15 min. NO / SV / PL / EN / LT / UA / RU.',
                'pl' => 'Profesjonalna szkoła jazdy w Oslo: kat. B, teoria, kurs intensywny. Oddzwonimy w 15 min. NO / SV / PL / EN / LT / UA / RU.',
                'lt' => 'Profesionali vairavimo mokykla Oslė: B, teorija, intensyvūs kursai. Perskambinsime per 15 min. NO / SV / PL / EN / LT / UA / RU.',
                'uk' => 'Автошкола в Осло: B, теорія, інтенсив. Передзвонимо за 15 хв. NO / SV / PL / EN / LT / UA / RU.',
                'ru' => 'Автошкола в Осло: B, теория, интенсив. Перезвоним за 15 мин. NO / SV / PL / EN / LT / UA / RU.',
                'en' => 'Professional driving school in Oslo: Category B, theory, intensive courses. We call you back in 15 min. NO / SV / PL / EN / LT / UA / RU.',
            ],
            'keywords' => [
                'no' => 'trafikkskole Oslo, klasse B, førerkort, Statens vegvesen, kjøretimer',
                'sv' => 'trafikkskola Oslo, klass B, körkort, Statens vegvesen, körlektioner',
                'pl' => 'szkoła jazdy Oslo, kat. B, prawo jazdy, Statens vegvesen, lekcje jazdy',
                'lt' => 'vairavimo mokykla Oslo, B kategorija, Statens vegvesen, vairavimo kursai',
                'uk' => 'автошкола Осло, категорія B, Statens vegvesen, курси водіння',
                'ru' => 'автошкола Осло, категория B, Statens vegvesen, курсы вождения',
                'en' => 'driving school Oslo, category B, Statens vegvesen, driving lessons Norway',
            ],
            'og_image' => 'https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?w=1200&h=630&fit=crop&q=85',
        ],
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
                ['icon' => 'fa-map-location-dot', 'title' => ['lt' => 'Vilniaus centras', 'uk' => 'Центр Вільнюса', 'ru' => 'Центр Вильнюса', 'en' => 'Central Vilnius'], 'desc' => ['lt' => 'Konstitucijos pr. — patogu iš visų rajonų.', 'uk' => 'пр. Конституції — зручно з усіх районів.', 'ru' => 'пр. Конституции — удобно из всех районов.', 'en' => 'Konstitucijos av. — easy access city-wide.']],
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
                ['icon' => 'fab fa-google', 'url' => 'https://www.google.com/maps/search/?api=1&query=Konstitucijos+pr.+12+Vilnius', 'label' => ['lt' => 'Google Maps', 'uk' => 'Google Maps', 'en' => 'Google Maps']],
            ],
            'cta' => [
                'enabled' => true,
                'title' => ['lt' => 'Norite, kad perskambintume?', 'uk' => 'Хочете, щоб ми передзвонили?', 'ru' => 'Хотите, чтобы мы перезвонили?', 'en' => 'Want us to call you back?'],
                'lead' => ['lt' => 'Palikite telefoną — paskambinsime per 15 minučių. Konsultacija nemokama.', 'uk' => 'Залиште номер — передзвонимо за 15 хвилин. Консультація безкоштовна.', 'ru' => 'Оставьте номер — перезвоним за 15 минут. Консультация бесплатна.', 'en' => 'Leave your number — we call within 15 minutes. Free consultation.'],
                'phone' => '+37061234567',
            ],
            'video' => [
                'enabled' => true,
                'url' => 'https://www.youtube.com/watch?v=LXb3EKWsInQ',
                'title' => ['lt' => 'Kaip atrodo mokymas', 'uk' => 'Як виглядає навчання', 'ru' => 'Как проходит обучение', 'en' => 'See how we train'],
            ],
            'partners' => [
                ['name' => 'Regitra', 'logo' => ''],
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
                'country_id' => 'lt',
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
    if ($version >= 3) {
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
    $merged['meta']['driving_premium_v'] = 3;
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