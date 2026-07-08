# Business Landing CMS

Универсальный **конструктор лендингов на PHP** для любого локального бизнеса — стоматолог, автошкола, салон красоты, юридическая фирма, отель и 18 отраслевых пресетов. Многоязычный фронтенд и админ (7 языков), AI автозаполнение, SEO-чеклисты, страницы услуг, sitemap, лиды и счета Faktura. Портфолио-проект [Руслана Билогаша](https://bilohash.com/) · **экосистема BILOHASH**.

**Версия:** 1.6.6 · **Readme:** [EN](README.md) · [NO](README-no.md) · [SV](README-sv.md) · [PL](README-pl.md) · [LT](README-lt.md) · [UA](README-uk.md) · [RU](README-ru.md)

![PHP](https://img.shields.io/badge/PHP-8%2B-777BB4?logo=php&logoColor=white)
![Version](https://img.shields.io/badge/version-1.6.6-blue)
![License](https://img.shields.io/badge/license-Proprietary-red)
![i18n](https://img.shields.io/badge/languages-7-green)
![Demo](https://img.shields.io/badge/demo-30%20days-orange)

---

## Важно — 30-дневное демо · собственность экосистемы

> **Этот репозиторий GitHub — демо / портфолио-копия Business Landing CMS.**
> Он принадлежит **экосистеме BILOHASH** и **не** является бесплатной коммерческой лицензией.
>
> - **30-дневное** самостоятельно размещённое демо (когда предоставляется) — один домен на план  
> - Последняя версия и поддержка: https://bilohash.com/lending/ · https://bilohash.com/ecosystem/join.php  
> - Подробности: [DEMO.md](DEMO-ru.md) · [LICENSE](LICENSE-ru.md)

**Коммерческое использование без письменного согласия запрещено.**

---

## Живое демо

| Ресурс | URL |
|----------|-----|
| **Hub** | https://bilohash.com/lending/ |
| **Живой лендинг** | https://bilohash.com/lending/template.php |
| **Админ-панель** | https://bilohash.com/lending/admin/ |
| **Карта сайта** | https://bilohash.com/lending/sitemap.xml |
| **Конфиденциальность (пример)** | https://bilohash.com/lending/page.php?slug=privacy |
| **Присоединиться к экосистеме** | https://bilohash.com/ecosystem/join.php |
| **Кабинет клиента** | https://bilohash.com/ecosystem/cabinet.php |

**Вход в админ (демо):** `demo` / `bilolending2026`

---

## Возможности

- **18 отраслевых пресетов** — стоматолог, автошкола, отель, аптека, барбершоп и др.
- **10 дизайн-шаблонов** + премиум-тема для автошколы
- **7 языков** — NO, SV, PL, EN, LT, UA, RU (фронтенд + админ)
- **AI-агент** — автозаполнение контента, SEO, новостей; чат-виджет на лендинге
- **Страницы услуг** — конфиденциальность, условия, свои страницы + ссылки в футере
- **Sitemap.xml** — hub, лендинг, страницы с hreflang
- **Согласие на конфиденциальность** — обязательный чекбокс в форме контакта
- **SEO** — master checklist (35), Google checklist (22), Schema.org
- **Лиды, студенты, счета** — мост к Faktura Creator
- **JSON-хранилище** — база данных не нужна на демо bilohash.com

---

## Технологический стек

- PHP 8+ (без фреймворка)
- JSON-файлы в `data/`
- Модульная i18n `lang/*.php`
- Apache `.htaccess`, динамический sitemap, опциональный reCAPTCHA

---

## Требования

- PHP 8.0+
- Apache `mod_rewrite` (или аналог nginx)
- Записываемые `data/` и `uploads/`

---

## Быстрая установка

```bash
git clone https://github.com/Ruslan-Bilohash/lending.git
cd lending
# Укажите vhost или подпапку на корень проекта, напр. /lending/
```

Откройте `/lending/` в браузере. Язык по умолчанию: **норвежский (no)**.

---

## Структура проекта

```
lending/
├── index.php          # Маркетинговый hub
├── template.php       # Активный бизнес-лендинг
├── page.php           # Страницы услуг (конфиденциальность, условия, …)
├── sitemap.php        # Динамический sitemap.xml
├── admin/             # Полный CMS-админ
├── lang/              # 7 языковых файлов
├── includes/          # Основные PHP-модули
├── data/              # JSON-настройки, лиды, страницы, новости
└── assets/            # CSS, JS
```

---

## Журнал изменений

См. [CHANGELOG.md](CHANGELOG.md) — последняя **v1.6.6** (демо шаблонов, SEO по странам, перекрёстные ссылки).

---

## Автор и лицензия

**Ruslan Bilohash** · [bilohash.com](https://bilohash.com/) · rbilohash@gmail.com

[DEMO-ru.md](DEMO-ru.md) · [LICENSE-no.md](LICENSE-no.md) · [LICENSE-sv.md](LICENSE-sv.md) · [LICENSE-pl.md](LICENSE-pl.md) · [LICENSE-lt.md](LICENSE-lt.md) · [LICENSE-uk.md](LICENSE-uk.md) · [LICENSE-ru.md](LICENSE-ru.md)