# Business Landing CMS v1.6.6 — 30-day demo

**Live demo:** https://bilohash.com/lending/  
**Admin:** `demo` / `bilolending2026`

---

## License / Лицензия

**Proprietary · BILOHASH ecosystem · 30-day demo**

This GitHub release is a **portfolio / evaluation copy**, not a free commercial license.

| | |
|---|---|
| **Duration** | 30 calendar days on one domain per active BILOHASH plan |
| **Ownership** | Ruslan Bilohash / [BILOHASH](https://bilohash.com/) |
| **Commercial use** | Requires written consent |
| **Full product** | https://bilohash.com/lending/ · https://bilohash.com/ecosystem/join.php |

Read **[LICENSE](LICENSE)** (EN) and translated **[LICENSE-ru.md](LICENSE-ru.md)** · **[LICENSE-uk.md](LICENSE-uk.md)** · **[LICENSE-no.md](LICENSE-no.md)** · **[LICENSE-sv.md](LICENSE-sv.md)** · **[LICENSE-pl.md](LICENSE-pl.md)** · **[LICENSE-lt.md](LICENSE-lt.md)**  
Details: **[DEMO.md](DEMO.md)** (7 languages)

**Contact:** rbilohash@gmail.com

---

## What's new in v1.6.6

### Template demos × country × language
Each of **10 designs** shows the matching business niche in the visitor's language and country:
- #9 Taxi Yellow → «Такси Москва» (RU), «Oslo Taxi» (NO), «Vilniaus Taksi» (LT), …
- #1/#8 Driving school, #2/#4 Restaurant, #3 Clinic, #5 Dentist, #6 Auto service, #7 Beauty, #10 Law firm

### SEO
- Country-specific title, description, keywords per preset × lang
- Schema.org: `TaxiService`, `Restaurant`, `DrivingSchool`, `LegalService`, …
- Canonical URLs with `?t=N&lang=XX`
- Sitemap: all 10 templates × 7 languages

### Cross-links
- Hub gallery + landing footer link all 10 template demos
- JSON-LD `ItemList` for related templates

### i18n — 100%
- **731 keys** in NO, SV, PL, EN, LT, UA, RU (site + admin + frontend)
- New: `crosslinks_*`, `other_templates*`, `demo_banner_generic`

### Also in 1.6.5–1.6.4
- `lang=ua` alias fix for Ukrainian
- Country SEO map (NO→Oslo, LT→Vilnius, RU→Moscow, …)

---

## Install (self-hosted demo)

1. Download **`lending-demo-30d-v1.6.6.zip`** from this release
2. Extract to web root, e.g. `/lending/`
3. PHP 8.0+, Apache `mod_rewrite`, writable `data/` and `uploads/`
4. Open `/lending/` in browser (default language: Norwegian)

Or clone: `git clone https://github.com/Ruslan-Bilohash/lending.git` · tag `v1.6.6`

---

## Что нового (кратко)

- **10 шаблонов** — каждый показывает свой бизнес на языке страны (такси, автошкола, клиника, ресторан…)
- **SEO** под Oslo, Vilnius, Moscow, Kyiv, London, Warsaw, Stockholm
- **Перекрёстные ссылки** между всеми демо-лендингами
- **Переводы 100%** — сайт, админ, фронтенд (7 языков)
- **Лицензия:** 30-дневное демо экосистемы BILOHASH — см. LICENSE / LICENSE-ru.md