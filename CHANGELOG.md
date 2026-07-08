# Business Landing CMS — Changelog

## 1.6.4 — 2026-07-08

**Deploy:** Hostinger → https://bilohash.com/lending/

- **Country SEO:** each language mapped to its country (NO→Oslo, SV→Stockholm, PL→Warsaw, EN→London, LT→Vilnius, UA→Kyiv, RU→Moscow)
- **SEO meta:** title, description, keywords per country in native language
- **Schema.org:** LocalBusiness address/geo/phone/currency per active language
- **Presets & AI fill:** country-aware SEO generation for all business presets
- **Migration:** `driving_premium_v` 4 — merges country-specific defaults

## 1.6.3 — 2026-07-08

**Deploy:** Hostinger → https://bilohash.com/lending/

- **Hotfix:** `lang/sv.php` — removed extra `],` causing HTTP 500 on Swedish locale

## 1.6.2 — 2026-07-08

**Deploy:** Hostinger → https://bilohash.com/lending/

- **i18n fix:** Swedish (`sv`) and Polish (`pl`) — removed 327+233 Norwegian copy-paste strings; real SV/PL translations
- **i18n audit:** quality check added (detects NO-copies, not just missing keys)
- **Ecosystem:** PL/SV footer labels in shared `ecosystem-i18n.php`

## 1.6.1 — 2026-07-08

**Deploy:** Hostinger → https://bilohash.com/lending/

- **Admin nav:** sidebar grouped into dropdown categories (content, CRM, design, SEO, help)
- **i18n:** nav group labels in 7 languages; badge for new leads

## 1.6.0 — 2026-07-08

**Deploy:** Hostinger → https://bilohash.com/lending/ · **GitHub:** https://github.com/Ruslan-Bilohash/lending

- **Docs:** README, LICENSE, DEMO in 7 languages (EN, NO, SV, PL, LT, UA, RU)
- **Demo terms:** 30-day trial, BILOHASH ecosystem ownership
- **Git:** public repository `Ruslan-Bilohash/lending`

## 1.5.0 — 2026-07-08

**Deploy:** Hostinger → https://bilohash.com/lending/

- **Sitemap:** dynamic `sitemap.xml` — hub, landing, service pages with hreflang (7 languages)
- **Service pages:** admin CRUD — multilingual title, body, SEO; footer links; public `page.php?slug=…`
- **Privacy:** default personvern/terms pages seeded; consent checkbox links to local privacy page
- **Integrations:** Privacy & consent settings — require consent, slug, external URL override, per-language text
- **robots.txt** + sitemap link in `<head>` and footer

## 1.4.0 — 2026-07-08

**Deploy:** Hostinger → https://bilohash.com/lending/

- **Norwegian business:** default demo driving school in Oslo (NOK/kr, +47, Statens vegvesen, Karl Johans gate)
- **Languages:** 7 locales — NO, SV, PL, EN, LT, UA, RU (frontend + admin tabs)
- **Default language:** Norwegian (`no`) — site and admin panel
- **i18n:** `lang/no.php`, `lang/sv.php`, `lang/pl.php` — 100% key coverage vs `en.php`
- **Presets:** `ld_pi()` extended with no/sv/pl; catalog FAQ and build defaults for Norway
- **Hub/marketing:** sublead, stats and SEO checklists updated to 7 languages

## 1.3.0 — 2026-07-08

**Deploy:** Hostinger → https://bilohash.com/lending/

- **Business presets:** 18 niches (was 8) — dentist, driving school + 16 catalog presets with tailored services, team, FAQ, stats, AI prompts and recommended templates
- **New presets:** real estate, accounting, cleaning, veterinary, photography, construction, hotel, kindergarten, pharmacy, barbershop
- **Preset engine:** `ld_preset_build()` + data-driven `business-presets-catalog.php` — optimized content per industry instead of generic filler
- **Master checklist:** 35 launch tasks on dashboard (content + Google SEO + setup)
- **SEO checklist:** 22 Google items with anchors to hero image, sections, meta fields
- **News:** multi-image gallery — upload, drag-drop reorder, URL fallback
- **Hero / OG image:** file picker + upload on Blocks and SEO (not URL-only)
- **Admin UI:** «Open SEO» checklist buttons keep primary button styling
- **Hub:** marketing copy updated to 18 business presets (EN / LT / UK / RU)
- **Admin:** Changelog page in sidebar — reads `CHANGELOG.md` + `VERSION`, version badge in footer
- **Hub:** BILOHASH ecosystem block on homepage with CTA to https://bilohash.com/ and related CMS demos
- **Hub:** ecosystem card «Demo» buttons link to https://bilohash.com/ecosystem/join.php (per current language)
- **Admin:** Help page (API guides) + Support & mail — inbox, BILOHASH messages, AI email drafts (4 languages)

## 1.2.1 — 2026-07-08

- **Students:** monthly fee per student, one-click monthly invoice (Faktura PDF or demo print)
- **Demo:** 6 sample students with prices 45–280 EUR/month

## 1.2.0 — 2026-07-08

- **Dashboard:** Chart.js graphs — weekly leads, status breakdown, monthly invoice revenue
- **Invoices:** unified list (demo + Faktura + leads), 6 demo invoices with amounts
- **News:** school news module — multilingual editor (LT / UA / RU / EN)
- **AI:** writing assistant for news articles (demo + API)
- **SEO:** per-article SEO analysis with score, tips and one-click apply
- **Demo data:** 10 leads, 6 invoices, 5 published news articles (auto-seed)
- **Landing:** public news block on driving school template #8
- **i18n:** Lithuanian default for site and admin panel
- **Demo disclaimer:** visible banner — no real services offered

## 1.1.0 — 2026-07-07

- Premium driving school preset (template #8, teal-urban)
- Schema.org `DrivingSchool`, OG card, mobile header
- Callback FAB, trust bar, process steps
- Lead email notifications in admin integrations
- Admin language dropdown (EN default → moved to LT in 1.2.0)

## 1.0.0 — 2026-07-01

- Initial Business Landing CMS demo
- 8 business presets, 10 design templates
- AI auto-fill, SEO analysis, Faktura invoices
- Leads inbox, blocks constructor, 4 languages
- Demo login: `demo` / `bilolending2026`