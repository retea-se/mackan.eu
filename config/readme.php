<!-- config/readme.php - v2 -->
<!--
📘 Dokumentation: Struktur & utvecklingsprinciper för mackan.eu

📁 MAPPSTRUKTUR

/                 → Webbplatsens rot (innehåller index.php)
/css/             → Alla globala stilmallar (theme, layout, tools etc.)
/js/              → Globala skript (temaväxling, navbar)
/config/          → Delade datafiler (t.ex. tools.php) och denna dokumentation
/includes/        → PHP-moduler: header, footer, layout-start/end m.m.
/tools/           → Varje verktyg har en egen undermapp (ex: /tools/addy/)

🎨 CSS
- Alla stilar utgår från CSS-variabler i variables.css
- theme.css hanterar light/dark via [data-theme]
- layout.css och components.css hanterar tabeller, kort, formulär m.m.
- tools.css används för formulär- och verktygskomponenter
- utilities.css för små layout-helpers (t.ex. .text-center)
- reset.css sätter nollställning av stilar
- typography.css definierar rubriker, textstorlek etc.
- navbar.css och footer.css sköter navigation och sidfot

/css/
|
|├─ reset.css        → Nollställer browserstilar (margin, padding, font)
|├─ variables.css    → Centrala CSS-variabler: färger, typsnitt, spacing
|├─ theme.css        → Temaväxling (dark/light) via [data-theme="dark"]
|├─ layout.css       → Containers, tabeller, formulär, struktur
|├─ typography.css   → Textstilar: rubriker, brödtext, länkar
|├─ components.css   → Kort, knappar, inputs, tabeller, toast
|├─ utilities.css    → Hjälpklasser: .mb-1, .text-center, .hidden
|├─ navbar.css       → Navigation, hamburgermeny, temaknapp
|└─ footer.css       → Sidfotens layout och stil

🛠️ CSS-hierarki

1. **Reset** → nollställer allt
2. **Variables** → definierar bas (färger, typsnitt)
3. **Theme** → skriver över `:root` via [data-theme="dark"]
4. **Layout** → struktur, tabeller, container
5. **Typography** → textutseende
6. **Components + Utilities** → byggblock
7. **Specifika CSS** → navbar, footer, tools etc.

🧪 Exempel på användning

<form class="form-group">
  <input type="text" class="input" placeholder="...">
  <textarea class="textarea"></textarea>
  <div class="horizontal-tools">
    <button class="button">OK</button>
    <button class="button secondary">Avbryt</button>
  </div>
</form>
<table class="table">...</table>

---

## 📆 Includes (PHP-komponenter)

/includes/
|
|├─ meta.php         → <head> med CSS/JS och titlar
|├─ header.php       → Logotyp och ev. sidtitel
|├─ nav.php          → Meny med hamburgare
|├─ footer.php       → Copyright, länk, ikon
|├─ title.php        → Automatisk <h1> via $title
|├─ layout-start.php → Inkluderar: meta, header, nav, title
|└─ layout-end.php   → Inkluderar: footer och avslutande </body></html>

## 🛠️ JavaScript

/js/
|
|├─ theme-toggle.js  → Hanterar temaväxling med localStorage
|├─ navbar.js        → Menyinteraktion för mobil/hamburgare
|└─ theme.js         → (valfri utökning för t.ex. systemteman)

## 🛠️ Verktygsstruktur

/tools/verktygsnamn/
|
|├─ index.php     → Formulär / resultatvy
|├─ script.js     → Verktygsspecifik JS
|└─ readme.php    → Info om verktygets funktion

Definieras centralt i `config/tools.php` som:
```php
return [
  ['title' => 'Addy', 'href' => '/tools/addy/index.php', 'icon' => 'fa-envelope'],
  ['title' => 'Aptus', 'href' => '/tools/aptus/index.php', 'icon' => 'fa-key']
];
```

Senast uppdaterad: 2025-06-08
-->
