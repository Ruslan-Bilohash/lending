# Business Landing CMS

Universal **PHP landing page builder** for any local business — dentist, driving school, beauty salon, law firm, hotel and 18 industry presets. Multilingual frontend and admin (7 languages), AI auto-fill, SEO checklists, service pages, sitemap, leads and Faktura invoices. Portfolio project by [Ruslan Bilohash](https://bilohash.com/) · **BILOHASH ecosystem**.

**Version:** 1.6.6 · **Readme:** [EN](README.md) · [NO](README-no.md) · [SV](README-sv.md) · [PL](README-pl.md) · [LT](README-lt.md) · [UA](README-uk.md) · [RU](README-ru.md)

![PHP](https://img.shields.io/badge/PHP-8%2B-777BB4?logo=php&logoColor=white)
![Version](https://img.shields.io/badge/version-1.6.6-blue)
![License](https://img.shields.io/badge/license-Proprietary-red)
![i18n](https://img.shields.io/badge/languages-7-green)
![Demo](https://img.shields.io/badge/demo-30%20days-orange)

---

## Important — 30-day demo · ecosystem property

> **This GitHub repository is a demo / portfolio copy of Business Landing CMS.**
> It is owned by the **BILOHASH ecosystem** and is **not** a free commercial license.
>
> - **30-day** self-hosted demo (when provided) — one domain per plan  
> - Latest version & support: https://bilohash.com/lending/ · https://bilohash.com/ecosystem/join.php  
> - Details: [DEMO.md](DEMO.md) · [LICENSE](LICENSE)

**Commercial use without written consent is prohibited.**

---

## Live demo

| Resource | URL |
|----------|-----|
| **Hub** | https://bilohash.com/lending/ |
| **Live landing** | https://bilohash.com/lending/template.php |
| **Admin panel** | https://bilohash.com/lending/admin/ |
| **Sitemap** | https://bilohash.com/lending/sitemap.xml |
| **Privacy (example)** | https://bilohash.com/lending/page.php?slug=privacy |
| **Join ecosystem** | https://bilohash.com/ecosystem/join.php |
| **Customer cabinet** | https://bilohash.com/ecosystem/cabinet.php |

**Admin login (demo):** `demo` / `bilolending2026`

---

## Features

- **18 business presets** — dentist, driving school, hotel, pharmacy, barbershop, etc.
- **10 design templates** + driving-school premium theme
- **7 languages** — NO, SV, PL, EN, LT, UA, RU (frontend + admin)
- **AI agent** — auto-fill content, SEO, news; chat widget on landing
- **Service pages** — privacy, terms, custom pages + footer links
- **Sitemap.xml** — hub, landing, pages with hreflang
- **Privacy consent** — required checkbox on contact form
- **SEO** — master checklist (35), Google checklist (22), Schema.org
- **Leads, students, invoices** — Faktura Creator bridge
- **JSON storage** — no database required on bilohash.com demo

---

## Tech stack

- PHP 8+ (no framework)
- JSON files in `data/`
- Modular i18n `lang/*.php`
- Apache `.htaccess`, dynamic sitemap, reCAPTCHA optional

---

## Requirements

- PHP 8.0+
- Apache `mod_rewrite` (or nginx equivalent)
- Writable `data/` and `uploads/`

---

## Quick install

```bash
git clone https://github.com/Ruslan-Bilohash/lending.git
cd lending
# Point vhost or subfolder to project root, e.g. /lending/
```

Open `/lending/` in browser. Default language: **Norwegian (no)**.

---

## Project structure

```
lending/
├── index.php          # Marketing hub
├── template.php       # Active business landing
├── page.php           # Service pages (privacy, terms, …)
├── sitemap.php        # Dynamic sitemap.xml
├── admin/             # Full CMS admin
├── lang/              # 7 locale files
├── includes/          # Core PHP modules
├── data/              # JSON settings, leads, pages, news
└── assets/            # CSS, JS
```

---

## Changelog

See [CHANGELOG.md](CHANGELOG.md) — latest **v1.6.6** (template demos, country SEO, cross-links).

---

## Author & license

**Ruslan Bilohash** · [bilohash.com](https://bilohash.com/) · rbilohash@gmail.com

[DEMO.md](DEMO.md) · [LICENSE](LICENSE) · [LICENSE-no.md](LICENSE-no.md) · [LICENSE-sv.md](LICENSE-sv.md) · [LICENSE-pl.md](LICENSE-pl.md) · [LICENSE-lt.md](LICENSE-lt.md) · [LICENSE-uk.md](LICENSE-uk.md) · [LICENSE-ru.md](LICENSE-ru.md)