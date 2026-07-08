# Business Landing CMS

Universell **PHP landingssidebygger** for enhver lokal bedrift — tannlege, trafikkskole, skjønnhetssalong, advokatfirma, hotell og 18 bransjeforhåndsinnstillinger. Flerspråklig frontend og admin (7 språk), AI auto-utfylling, SEO-sjekklister, tjenestesider, sitemap, leads og Faktura-fakturaer. Porteføljeprosjekt av [Ruslan Bilohash](https://bilohash.com/) · **BILOHASH-økosystemet**.

**Versjon:** 1.6.6 · **Readme:** [EN](README.md) · [NO](README-no.md) · [SV](README-sv.md) · [PL](README-pl.md) · [LT](README-lt.md) · [UA](README-uk.md) · [RU](README-ru.md)

![PHP](https://img.shields.io/badge/PHP-8%2B-777BB4?logo=php&logoColor=white)
![Version](https://img.shields.io/badge/version-1.6.6-blue)
![License](https://img.shields.io/badge/license-Proprietary-red)
![i18n](https://img.shields.io/badge/languages-7-green)
![Demo](https://img.shields.io/badge/demo-30%20days-orange)

---

## Viktig — 30-dagers demo · økosystemets eiendom

> **Dette GitHub-depotet er en demo / porteføljekopi av Business Landing CMS.**
> Det eies av **BILOHASH-økosystemet** og er **ikke** en gratis kommersiell lisens.
>
> - **30-dagers** selvhostet demo (når levert) — ett domene per plan  
> - Nyeste versjon og støtte: https://bilohash.com/lending/ · https://bilohash.com/ecosystem/join.php  
> - Detaljer: [DEMO.md](DEMO-no.md) · [LICENSE](LICENSE-no.md)

**Kommersiell bruk uten skriftlig samtykke er forbudt.**

---

## Live demo

| Ressurs | URL |
|----------|-----|
| **Hub** | https://bilohash.com/lending/ |
| **Live landingsside** | https://bilohash.com/lending/template.php |
| **Adminpanel** | https://bilohash.com/lending/admin/ |
| **Sitemap** | https://bilohash.com/lending/sitemap.xml |
| **Personvern (eksempel)** | https://bilohash.com/lending/page.php?slug=privacy |
| **Bli med i økosystemet** | https://bilohash.com/ecosystem/join.php |
| **Kundekabinett** | https://bilohash.com/ecosystem/cabinet.php |

**Admin-innlogging (demo):** `demo` / `bilolending2026`

---

## Funksjoner

- **18 bransjeforhåndsinnstillinger** — tannlege, trafikkskole, hotell, apotek, barbershop osv.
- **10 designmaler** + premium-tema for trafikkskole
- **7 språk** — NO, SV, PL, EN, LT, UA, RU (frontend + admin)
- **AI-agent** — auto-utfylling av innhold, SEO, nyheter; chat-widget på landingssiden
- **Tjenestesider** — personvern, vilkår, egendefinerte sider + bunntekstlenker
- **Sitemap.xml** — hub, landingsside, sider med hreflang
- **Personvernsamtykke** — obligatorisk avkrysningsboks på kontaktskjema
- **SEO** — master-sjekkliste (35), Google-sjekkliste (22), Schema.org
- **Leads, elever, fakturaer** — Faktura Creator-bro
- **JSON-lagring** — ingen database kreves på bilohash.com-demo

---

## Teknologistack

- PHP 8+ (uten rammeverk)
- JSON-filer i `data/`
- Modulær i18n `lang/*.php`
- Apache `.htaccess`, dynamisk sitemap, reCAPTCHA valgfritt

---

## Krav

- PHP 8.0+
- Apache `mod_rewrite` (eller nginx-ekvivalent)
- Skrivbar `data/` og `uploads/`

---

## Rask installasjon

```bash
git clone https://github.com/Ruslan-Bilohash/lending.git
cd lending
# Pek vhost eller undermappe til prosjektrot, f.eks. /lending/
```

Åpne `/lending/` i nettleseren. Standardspråk: **norsk (no)**.

---

## Prosjektstruktur

```
lending/
├── index.php          # Markedsføringshub
├── template.php       # Aktiv bedriftslandingsside
├── page.php           # Tjenestesider (personvern, vilkår, …)
├── sitemap.php        # Dynamisk sitemap.xml
├── admin/             # Full CMS-admin
├── lang/              # 7 språkfiler
├── includes/          # Kjernemoduler i PHP
├── data/              # JSON-innstillinger, leads, sider, nyheter
└── assets/            # CSS, JS
```

---

## Endringslogg

Se [CHANGELOG.md](CHANGELOG.md) — siste **v1.6.6** (mal-demoer, land-SEO, krysslenker).

---

## Forfatter og lisens

**Ruslan Bilohash** · [bilohash.com](https://bilohash.com/) · rbilohash@gmail.com

[DEMO-no.md](DEMO-no.md) · [LICENSE-no.md](LICENSE-no.md) · [LICENSE-sv.md](LICENSE-sv.md) · [LICENSE-pl.md](LICENSE-pl.md) · [LICENSE-lt.md](LICENSE-lt.md) · [LICENSE-uk.md](LICENSE-uk.md) · [LICENSE-ru.md](LICENSE-ru.md)