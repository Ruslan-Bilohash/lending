# Business Landing CMS

Universell **PHP-landningssidebyggare** för alla lokala företag — tandläkare, trafikskola, skönhetssalong, advokatbyrå, hotell och 18 branschförinställningar. Flerspråkig frontend och admin (7 språk), AI auto-ifyllning, SEO-checklistor, tjänstesidor, sitemap, leads och Faktura-fakturor. Portföljprojekt av [Ruslan Bilohash](https://bilohash.com/) · **BILOHASH-ekosystemet**.

**Version:** 1.6.6 · **Readme:** [EN](README.md) · [NO](README-no.md) · [SV](README-sv.md) · [PL](README-pl.md) · [LT](README-lt.md) · [UA](README-uk.md) · [RU](README-ru.md)

![PHP](https://img.shields.io/badge/PHP-8%2B-777BB4?logo=php&logoColor=white)
![Version](https://img.shields.io/badge/version-1.6.6-blue)
![License](https://img.shields.io/badge/license-Proprietary-red)
![i18n](https://img.shields.io/badge/languages-7-green)
![Demo](https://img.shields.io/badge/demo-30%20days-orange)

---

## Viktigt — 30-dagars demo · ekosystemets egendom

> **Detta GitHub-arkiv är en demo / portföljekopia av Business Landing CMS.**
> Det ägs av **BILOHASH-ekosystemet** och är **inte** en gratis kommersiell licens.
>
> - **30-dagars** självhostad demo (när den tillhandahålls) — en domän per plan  
> - Senaste version och support: https://bilohash.com/lending/ · https://bilohash.com/ecosystem/join.php  
> - Detaljer: [DEMO.md](DEMO-sv.md) · [LICENSE](LICENSE-sv.md)

**Kommersiell användning utan skriftligt medgivande är förbjuden.**

---

## Live demo

| Resurs | URL |
|----------|-----|
| **Hub** | https://bilohash.com/lending/ |
| **Live landningssida** | https://bilohash.com/lending/template.php |
| **Adminpanel** | https://bilohash.com/lending/admin/ |
| **Sitemap** | https://bilohash.com/lending/sitemap.xml |
| **Integritet (exempel)** | https://bilohash.com/lending/page.php?slug=privacy |
| **Gå med i ekosystemet** | https://bilohash.com/ecosystem/join.php |
| **Kundkabinett** | https://bilohash.com/ecosystem/cabinet.php |

**Admin-inloggning (demo):** `demo` / `bilolending2026`

---

## Funktioner

- **18 branschförinställningar** — tandläkare, trafikskola, hotell, apotek, barbershop m.m.
- **10 designmallar** + premiumtema för trafikskola
- **7 språk** — NO, SV, PL, EN, LT, UA, RU (frontend + admin)
- **AI-agent** — auto-ifyllning av innehåll, SEO, nyheter; chattwidget på landningssidan
- **Tjänstesidor** — integritet, villkor, anpassade sidor + sidfotslänkar
- **Sitemap.xml** — hub, landningssida, sidor med hreflang
- **Integritetssamtycke** — obligatorisk kryssruta i kontaktformulär
- **SEO** — master-checklista (35), Google-checklista (22), Schema.org
- **Leads, elever, fakturor** — Faktura Creator-brygga
- **JSON-lagring** — ingen databas krävs på bilohash.com-demo

---

## Teknikstack

- PHP 8+ (utan ramverk)
- JSON-filer i `data/`
- Modulär i18n `lang/*.php`
- Apache `.htaccess`, dynamisk sitemap, reCAPTCHA valfritt

---

## Krav

- PHP 8.0+
- Apache `mod_rewrite` (eller nginx-motsvarighet)
- Skrivbar `data/` och `uploads/`

---

## Snabbinstallation

```bash
git clone https://github.com/Ruslan-Bilohash/lending.git
cd lending
# Peka vhost eller undermapp till projektrot, t.ex. /lending/
```

Öppna `/lending/` i webbläsaren. Standardspråk: **norska (no)**.

---

## Projektstruktur

```
lending/
├── index.php          # Marknadsföringshub
├── template.php       # Aktiv företagslandningssida
├── page.php           # Tjänstesidor (integritet, villkor, …)
├── sitemap.php        # Dynamisk sitemap.xml
├── admin/             # Full CMS-admin
├── lang/              # 7 språkfiler
├── includes/          # Kärnmoduler i PHP
├── data/              # JSON-inställningar, leads, sidor, nyheter
└── assets/            # CSS, JS
```

---

## Ändringslogg

Se [CHANGELOG.md](CHANGELOG.md) — senaste **v1.6.6** (malldemoer, land-SEO, korslänkar).

---

## Författare och licens

**Ruslan Bilohash** · [bilohash.com](https://bilohash.com/) · rbilohash@gmail.com

[DEMO-sv.md](DEMO-sv.md) · [LICENSE-no.md](LICENSE-no.md) · [LICENSE-sv.md](LICENSE-sv.md) · [LICENSE-pl.md](LICENSE-pl.md) · [LICENSE-lt.md](LICENSE-lt.md) · [LICENSE-uk.md](LICENSE-uk.md) · [LICENSE-ru.md](LICENSE-ru.md)