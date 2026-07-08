# Business Landing CMS

Universali **PHP nukreipimo puslapių kūrimo priemonė** bet kuriam vietiniam verslui — odontologas, vairavimo mokykla, grožio salonas, advokatų kontora, viešbutis ir 18 pramonės šablonų. Daugiakalbis frontend ir admin (7 kalbos), AI automatinis užpildymas, SEO kontroliniai sąrašai, paslaugų puslapiai, svetainės žemėlapis, užklausos ir Faktura sąskaitos. Portfolio projektas [Ruslano Bilohasho](https://bilohash.com/) · **BILOHASH ekosistema**.

**Versija:** 1.6.0 · **Readme:** [EN](README.md) · [NO](README-no.md) · [SV](README-sv.md) · [PL](README-pl.md) · [LT](README-lt.md) · [UA](README-uk.md) · [RU](README-ru.md)

![PHP](https://img.shields.io/badge/PHP-8%2B-777BB4?logo=php&logoColor=white)
![Version](https://img.shields.io/badge/version-1.6.0-blue)
![License](https://img.shields.io/badge/license-Proprietary-red)
![i18n](https://img.shields.io/badge/languages-7-green)
![Demo](https://img.shields.io/badge/demo-30%20days-orange)

---

## Svarbu — 30 dienų demonstracija · ekosistemos nuosavybė

> **Šis GitHub saugykla yra Business Landing CMS demo / portfolio kopija.**
> Ji priklauso **BILOHASH ekosistemai** ir **nėra** nemokama komercinė licencija.
>
> - **30 dienų** savarankiškai talpinama demonstracija (kai pateikiama) — vienas domenas pagal planą  
> - Naujausia versija ir pagalba: https://bilohash.com/lending/ · https://bilohash.com/ecosystem/join.php  
> - Detalės: [DEMO.md](DEMO-lt.md) · [LICENSE](LICENSE-lt.md)

**Komercinis naudojimas be rašytinio sutikimo draudžiamas.**

---

## Gyva demonstracija

| Išteklius | URL |
|----------|-----|
| **Hub** | https://bilohash.com/lending/ |
| **Gyvas nukreipimo puslapis** | https://bilohash.com/lending/template.php |
| **Admin skydelis** | https://bilohash.com/lending/admin/ |
| **Svetainės žemėlapis** | https://bilohash.com/lending/sitemap.xml |
| **Privatumas (pavyzdys)** | https://bilohash.com/lending/page.php?slug=privacy |
| **Prisijungti prie ekosistemos** | https://bilohash.com/ecosystem/join.php |
| **Kliento kabinetas** | https://bilohash.com/ecosystem/cabinet.php |

**Admin prisijungimas (demo):** `demo` / `bilolending2026`

---

## Funkcijos

- **18 pramonės šablonų** — odontologas, vairavimo mokykla, viešbutis, vaistinė, kirpykla ir kt.
- **10 dizaino šablonų** + premium vairavimo mokyklos tema
- **7 kalbos** — NO, SV, PL, EN, LT, UA, RU (frontend + admin)
- **AI agentas** — automatinis turinio, SEO, naujienų užpildymas; pokalbių valdiklis puslapyje
- **Paslaugų puslapiai** — privatumas, sąlygos, pasirinktiniai puslapiai + poraštės nuorodos
- **Sitemap.xml** — hub, nukreipimo puslapis, puslapiai su hreflang
- **Privatumo sutikimas** — privalomas žymimasis langelis kontaktų formoje
- **SEO** — pagrindinis kontrolinis sąrašas (35), Google sąrašas (22), Schema.org
- **Užklausos, studentai, sąskaitos** — Faktura Creator tiltas
- **JSON saugykla** — nereikia duomenų bazės bilohash.com demonstracijoje

---

## Technologijų stekas

- PHP 8+ (be karkaso)
- JSON failai `data/`
- Modulinė i18n `lang/*.php`
- Apache `.htaccess`, dinaminis svetainės žemėlapis, neprivalomas reCAPTCHA

---

## Reikalavimai

- PHP 8.0+
- Apache `mod_rewrite` (arba nginx atitikmuo)
- Rašomos `data/` ir `uploads/`

---

## Greita instaliacija

```bash
git clone https://github.com/Ruslan-Bilohash/lending.git
cd lending
# Nukreipkite vhost arba poaplankį į projekto šaknį, pvz. /lending/
```

Atidarykite `/lending/` naršyklėje. Numatytoji kalba: **norvegų (no)**.

---

## Projekto struktūra

```
lending/
├── index.php          # Rinkodaros hub
├── template.php       # Aktyvus verslo nukreipimo puslapis
├── page.php           # Paslaugų puslapiai (privatumas, sąlygos, …)
├── sitemap.php        # Dinaminis sitemap.xml
├── admin/             # Pilnas CMS admin
├── lang/              # 7 kalbų failai
├── includes/          # Pagrindiniai PHP moduliai
├── data/              # JSON nustatymai, užklausos, puslapiai, naujienos
└── assets/            # CSS, JS
```

---

## Pakeitimų žurnalas

Žiūrėkite [CHANGELOG.md](CHANGELOG.md) — naujausia **v1.6.0** (dokumentacija, GitHub leidimas).

---

## Autorius ir licencija

**Ruslan Bilohash** · [bilohash.com](https://bilohash.com/) · rbilohash@gmail.com

[DEMO-lt.md](DEMO-lt.md) · [LICENSE-no.md](LICENSE-no.md) · [LICENSE-sv.md](LICENSE-sv.md) · [LICENSE-pl.md](LICENSE-pl.md) · [LICENSE-lt.md](LICENSE-lt.md) · [LICENSE-uk.md](LICENSE-uk.md) · [LICENSE-ru.md](LICENSE-ru.md)