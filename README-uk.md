# Business Landing CMS

Універсальний **конструктор лендінгів на PHP** для будь-якого локального бізнесу — стоматолог, автошкола, салон краси, юридична фірма, готель і 18 галузевих пресетів. Багатомовний фронтенд і адмін (7 мов), AI автозаповнення, SEO-чеклісти, сторінки послуг, sitemap, ліди та рахунки Faktura. Портфоліо-проєкт [Руслана Білогаша](https://bilohash.com/) · **екосистема BILOHASH**.

**Версія:** 1.6.6 · **Readme:** [EN](README.md) · [NO](README-no.md) · [SV](README-sv.md) · [PL](README-pl.md) · [LT](README-lt.md) · [UA](README-uk.md) · [RU](README-ru.md)

![PHP](https://img.shields.io/badge/PHP-8%2B-777BB4?logo=php&logoColor=white)
![Version](https://img.shields.io/badge/version-1.6.6-blue)
![License](https://img.shields.io/badge/license-Proprietary-red)
![i18n](https://img.shields.io/badge/languages-7-green)
![Demo](https://img.shields.io/badge/demo-30%20days-orange)

---

## Важливо — 30-денна демо · власність екосистеми

> **Цей репозиторій GitHub — демо / портфоліо-копія Business Landing CMS.**
> Він належить **екосистемі BILOHASH** і **не** є безкоштовною комерційною ліцензією.
>
> - **30-денна** самостійно розміщена демо (коли надається) — один домен на план  
> - Остання версія та підтримка: https://bilohash.com/lending/ · https://bilohash.com/ecosystem/join.php  
> - Деталі: [DEMO.md](DEMO-uk.md) · [LICENSE](LICENSE-uk.md)

**Комерційне використання без письмової згоди заборонено.**

---

## Жива демо

| Ресурс | URL |
|----------|-----|
| **Hub** | https://bilohash.com/lending/ |
| **Живий лендінг** | https://bilohash.com/lending/template.php |
| **Адмін-панель** | https://bilohash.com/lending/admin/ |
| **Карта сайту** | https://bilohash.com/lending/sitemap.xml |
| **Конфіденційність (приклад)** | https://bilohash.com/lending/page.php?slug=privacy |
| **Приєднатися до екосистеми** | https://bilohash.com/ecosystem/join.php |
| **Кабінет клієнта** | https://bilohash.com/ecosystem/cabinet.php |

**Вхід в адмін (демо):** `demo` / `bilolending2026`

---

## Можливості

- **18 галузевих пресетів** — стоматолог, автошкола, готель, аптека, барбершоп тощо
- **10 дизайн-шаблонів** + преміум-тема для автошколи
- **7 мов** — NO, SV, PL, EN, LT, UA, RU (фронтенд + адмін)
- **AI-агент** — автозаповнення контенту, SEO, новин; чат-віджет на лендінгу
- **Сторінки послуг** — конфіденційність, умови, власні сторінки + посилання в футері
- **Sitemap.xml** — hub, лендінг, сторінки з hreflang
- **Згода на конфіденційність** — обов'язковий чекбокс у формі контакту
- **SEO** — master checklist (35), Google checklist (22), Schema.org
- **Ліди, студенти, рахунки** — міст до Faktura Creator
- **JSON-сховище** — база даних не потрібна на демо bilohash.com

---

## Технологічний стек

- PHP 8+ (без фреймворку)
- JSON-файли в `data/`
- Модульна i18n `lang/*.php`
- Apache `.htaccess`, динамічний sitemap, опційний reCAPTCHA

---

## Вимоги

- PHP 8.0+
- Apache `mod_rewrite` (або аналог nginx)
- Записувані `data/` та `uploads/`

---

## Швидка установка

```bash
git clone https://github.com/Ruslan-Bilohash/lending.git
cd lending
# Вкажіть vhost або підпапку на корінь проєкту, напр. /lending/
```

Відкрийте `/lending/` у браузері. Мова за замовчуванням: **норвезька (no)**.

---

## Структура проєкту

```
lending/
├── index.php          # Маркетинговий hub
├── template.php       # Активний бізнес-лендінг
├── page.php           # Сторінки послуг (конфіденційність, умови, …)
├── sitemap.php        # Динамічний sitemap.xml
├── admin/             # Повний CMS-адмін
├── lang/              # 7 мовних файлів
├── includes/          # Основні PHP-модулі
├── data/              # JSON-налаштування, ліди, сторінки, новини
└── assets/            # CSS, JS
```

---

## Журнал змін

Див. [CHANGELOG.md](CHANGELOG.md) — остання **v1.6.6** (демо шаблонів, SEO країни, перехресні посилання).

---

## Автор і ліцензія

**Ruslan Bilohash** · [bilohash.com](https://bilohash.com/) · rbilohash@gmail.com

[DEMO-uk.md](DEMO-uk.md) · [LICENSE-no.md](LICENSE-no.md) · [LICENSE-sv.md](LICENSE-sv.md) · [LICENSE-pl.md](LICENSE-pl.md) · [LICENSE-lt.md](LICENSE-lt.md) · [LICENSE-uk.md](LICENSE-uk.md) · [LICENSE-ru.md](LICENSE-ru.md)