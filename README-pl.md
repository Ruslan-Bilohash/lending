# Business Landing CMS

Uniwersalny **kreator stron docelowych w PHP** dla każdej lokalnej firmy — dentysta, szkoła jazdy, salon urody, kancelaria prawna, hotel i 18 branżowych presetów. Wielojęzyczny frontend i panel admin (7 języków), auto-uzupełnianie AI, listy kontrolne SEO, strony usług, mapa witryny, leady i faktury Faktura. Projekt portfolio [Ruslana Bilohasha](https://bilohash.com/) · **ekosystem BILOHASH**.

**Wersja:** 1.6.0 · **Readme:** [EN](README.md) · [NO](README-no.md) · [SV](README-sv.md) · [PL](README-pl.md) · [LT](README-lt.md) · [UA](README-uk.md) · [RU](README-ru.md)

![PHP](https://img.shields.io/badge/PHP-8%2B-777BB4?logo=php&logoColor=white)
![Version](https://img.shields.io/badge/version-1.6.0-blue)
![License](https://img.shields.io/badge/license-Proprietary-red)
![i18n](https://img.shields.io/badge/languages-7-green)
![Demo](https://img.shields.io/badge/demo-30%20days-orange)

---

## Ważne — 30-dniowe demo · własność ekosystemu

> **To repozytorium GitHub to demo / kopia portfolio Business Landing CMS.**
> Należy do **ekosystemu BILOHASH** i **nie** jest darmową licencją komercyjną.
>
> - **30-dniowe** samodzielnie hostowane demo (gdy udostępnione) — jedna domena na plan  
> - Najnowsza wersja i wsparcie: https://bilohash.com/lending/ · https://bilohash.com/ecosystem/join.php  
> - Szczegóły: [DEMO.md](DEMO-pl.md) · [LICENSE](LICENSE-pl.md)

**Użycie komercyjne bez pisemnej zgody jest zabronione.**

---

## Demo na żywo

| Zasób | URL |
|----------|-----|
| **Hub** | https://bilohash.com/lending/ |
| **Strona docelowa** | https://bilohash.com/lending/template.php |
| **Panel admin** | https://bilohash.com/lending/admin/ |
| **Mapa witryny** | https://bilohash.com/lending/sitemap.xml |
| **Prywatność (przykład)** | https://bilohash.com/lending/page.php?slug=privacy |
| **Dołącz do ekosystemu** | https://bilohash.com/ecosystem/join.php |
| **Panel klienta** | https://bilohash.com/ecosystem/cabinet.php |

**Logowanie admina (demo):** `demo` / `bilolending2026`

---

## Funkcje

- **18 presetów branżowych** — dentysta, szkoła jazdy, hotel, apteka, barbershop itd.
- **10 szablonów designu** + motyw premium dla szkoły jazdy
- **7 języków** — NO, SV, PL, EN, LT, UA, RU (frontend + admin)
- **Agent AI** — auto-uzupełnianie treści, SEO, wiadomości; widget czatu na stronie
- **Strony usług** — prywatność, regulamin, własne strony + linki w stopce
- **Sitemap.xml** — hub, landing, strony z hreflang
- **Zgoda na prywatność** — wymagane pole wyboru w formularzu kontaktowym
- **SEO** — master checklist (35), Google checklist (22), Schema.org
- **Leady, kursanci, faktury** — most do Faktura Creator
- **Przechowywanie JSON** — brak bazy danych na demo bilohash.com

---

## Stos technologiczny

- PHP 8+ (bez frameworka)
- Pliki JSON w `data/`
- Modułowa i18n `lang/*.php`
- Apache `.htaccess`, dynamiczna mapa witryny, opcjonalny reCAPTCHA

---

## Wymagania

- PHP 8.0+
- Apache `mod_rewrite` (lub odpowiednik nginx)
- Zapisywalne `data/` i `uploads/`

---

## Szybka instalacja

```bash
git clone https://github.com/Ruslan-Bilohash/lending.git
cd lending
# Wskaż vhost lub podfolder na katalog główny projektu, np. /lending/
```

Otwórz `/lending/` w przeglądarce. Domyślny język: **norweski (no)**.

---

## Struktura projektu

```
lending/
├── index.php          # Hub marketingowy
├── template.php       # Aktywna strona biznesowa
├── page.php           # Strony usług (prywatność, regulamin, …)
├── sitemap.php        # Dynamiczna sitemap.xml
├── admin/             # Pełny panel CMS
├── lang/              # 7 plików językowych
├── includes/          # Główne moduły PHP
├── data/              # Ustawienia JSON, leady, strony, wiadomości
└── assets/            # CSS, JS
```

---

## Dziennik zmian

Zobacz [CHANGELOG.md](CHANGELOG.md) — najnowsza **v1.6.0** (dokumentacja, wydanie GitHub).

---

## Autor i licencja

**Ruslan Bilohash** · [bilohash.com](https://bilohash.com/) · rbilohash@gmail.com

[DEMO-pl.md](DEMO-pl.md) · [LICENSE-no.md](LICENSE-no.md) · [LICENSE-sv.md](LICENSE-sv.md) · [LICENSE-pl.md](LICENSE-pl.md) · [LICENSE-lt.md](LICENSE-lt.md) · [LICENSE-uk.md](LICENSE-uk.md) · [LICENSE-ru.md](LICENSE-ru.md)