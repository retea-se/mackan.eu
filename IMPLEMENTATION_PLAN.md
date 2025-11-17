# Implementationsplan - Ny landningssida (v2-mockup-1-clean)

## 📋 Nulägesanalys

### Befintlig struktur (fungerar bra, behåll):
- **`config/tools.php`** - Centraliserad verktygsdata (array med title, href, icon, desc)
- **`index.php`** - Läser in tools.php, sorterar alfabetiskt, loopar och renderar
- **`includes/layout-start.php`** - Header, meta-tags, CSS-import
- **`css/main.css`** - Importerar alla block-CSS:er inkl. menykort.css
- **`css/blocks/menykort.css`** - BEM-struktur för nuvarande grid-layout
- **`css/variables.css`** - CSS-variabler för färger och typsnitt

### Vad som saknas för ny design:
1. **Kategori-fält** i `config/tools.php`
2. **Featured-flagga** för att markera featured tools
3. **Ny CSS** för hero-sektion, kategoriserad layout, temaväxling
4. **Ny PHP-logik** i index.php för kategorisering och featured
5. **JavaScript** för temaväxling och lista/rutnät-toggle
6. **Uppdaterad layout** med sticky nav och ny struktur

---

## 🎯 Implementationsplan

### Steg 1: Uppdatera config/tools.php
**Vad:** Lägg till `category` och `featured` fält till varje verktyg

**Före:**
```php
[
    'title' => 'Bildkonverterare',
    'href' => '/tools/bildconverter/index.php',
    'icon' => 'fa-image',
    'desc' => 'Konvertera bilder mellan WEBP, PNG och JPEG...',
]
```

**Efter:**
```php
[
    'title' => 'Bildkonverterare',
    'href' => '/tools/bildconverter/index.php',
    'icon' => 'fa-image',
    'desc' => 'Konvertera bilder mellan WEBP, PNG och JPEG...',
    'category' => 'konvertering', // NYTT
    'featured' => true, // NYTT (valfritt, default false)
]
```

**Kategorier:**
- `konvertering` - Konvertering & Format
- `generatorer` - Generatorer
- `geo` - Geo & Koordinater
- `sakerhet` - Säkerhet & Delning
- `ovrigt` - Övrigt

**Uppgift:**
- Gå igenom alla 26 verktyg och tilldela kategori
- Markera 3 verktyg som featured (t.ex. QR, Bildkonverterare, Lösenordsgenerator)

---

### Steg 2: Skapa ny CSS-fil för landningssidan
**Vad:** Skapa `css/blocks/landningssida.css` baserad på v2-mockup-1-clean.html

**Innehåll:**
- CSS-variabler för ljust/mörkt tema (`:root[data-theme="light/dark"]`)
- Navigation (sticky, med plats för temaknapp)
- Hero-sektion (titel, beskrivning, featured cards)
- Kategori-sektioner (header med ikoner, grid)
- Tool cards (grid-vy och list-vy)
- View toggle-knappar
- Footer
- Responsiva breakpoints

**Lägg till i `css/main.css`:**
```css
@import url('blocks/landningssida.css');
```

---

### Steg 3: Uppdatera index.php
**Vad:** Ny struktur för kategoriserad rendering med featured-sektion

**Ny logik:**
1. Läs in tools.php
2. Filtrera ut featured tools (där `featured === true`)
3. Gruppera resterande verktyg per kategori
4. Rendera hero med featured tools först
5. Loopa genom kategorier och rendera verktyg per kategori

**Pseudo-kod:**
```php
<?php
$tools = include __DIR__ . '/config/tools.php';

// Filtrera featured
$featured = array_filter($tools, fn($t) => $t['featured'] ?? false);

// Gruppera per kategori
$categories = [
    'konvertering' => ['title' => 'Konvertering & Format', 'icon' => 'fa-arrows-rotate'],
    'generatorer' => ['title' => 'Generatorer', 'icon' => 'fa-wand-magic-sparkles'],
    'geo' => ['title' => 'Geo & Koordinater', 'icon' => 'fa-map'],
    'sakerhet' => ['title' => 'Säkerhet & Delning', 'icon' => 'fa-shield'],
    'ovrigt' => ['title' => 'Övrigt', 'icon' => 'fa-toolbox'],
];

$toolsByCategory = [];
foreach ($tools as $tool) {
    $cat = $tool['category'] ?? 'ovrigt';
    $toolsByCategory[$cat][] = $tool;
}
?>

<!-- Rendera hero med featured -->
<!-- Loopa genom kategorier -->
```

---

### Steg 4: Skapa tema-växling JS
**Vad:** Skapa `js/theme-toggle.js` för mörkt/ljust tema

**Funktionalitet:**
- Läs localStorage för sparat tema
- Sätt `data-theme` attribut på `<html>`
- Toggle mellan `light` och `dark`
- Uppdatera knapptext och ikon (🌙 → ☀️)
- Spara val i localStorage

**Exempel:**
```javascript
function toggleTheme() {
  const html = document.documentElement;
  const current = html.getAttribute('data-theme');
  const newTheme = current === 'light' ? 'dark' : 'light';
  html.setAttribute('data-theme', newTheme);
  localStorage.setItem('theme', newTheme);
  updateThemeButton(newTheme);
}

// Load saved theme on page load
const savedTheme = localStorage.getItem('theme') || 'light';
document.documentElement.setAttribute('data-theme', savedTheme);
```

---

### Steg 5: Skapa view-toggle JS
**Vad:** Skapa `js/view-toggle.js` för lista/rutnät-växling

**Funktionalitet:**
- Toggle CSS-klass `.list-view` på `.tools-grid`
- Aktivera/deaktivera toggle-knappar (`.active`)
- Varje kategori har sin egen toggle (inte globalt)
- Valfritt: Spara val per kategori i localStorage

**Exempel:**
```javascript
function setView(button, view) {
  const parent = button.closest('.category-header');
  const grid = parent.nextElementSibling;
  const buttons = parent.querySelectorAll('.view-toggle button');

  buttons.forEach(btn => btn.classList.remove('active'));
  button.classList.add('active');

  if (view === 'list') {
    grid.classList.add('list-view');
  } else {
    grid.classList.remove('list-view');
  }
}
```

---

### Steg 6: Uppdatera includes/layout-start.php
**Vad:** Lägg till nya script-taggar och data-theme attribut

**Ändringar:**
1. Lägg till `data-theme="light"` på `<html>` tag
2. Inkludera nya JS-filer innan `</body>`
3. Eventuellt lägg till ny CSP för inline scripts (om nödvändigt)

**I layout-start.php:**
```php
<html lang="sv" data-theme="light">
```

**I layout-end.php (eller nytt includes/layout-end.php):**
```php
<script src="/js/theme-toggle.js"></script>
<script src="/js/view-toggle.js"></script>
</body>
</html>
```

---

### Steg 7: Uppdatera header.php (om nödvändigt)
**Vad:** Lägg till temaväxlings-knapp i headern

**Alternativ 1:** Lägg till knapp direkt i index.php (inom nav)
**Alternativ 2:** Gör header.php dynamisk med tema-knapp

**Exempel (inom nav i index.php):**
```php
<nav>
  <div class="nav-content">
    <div class="logo">mackan.eu</div>
    <button class="theme-toggle" onclick="toggleTheme()">
      <i class="fas fa-moon"></i>
      <span id="theme-text">Mörkt</span>
    </button>
  </div>
</nav>
```

---

### Steg 8: Ta bort/bevara gamla menykort.css
**Beslut:** Behåll `menykort.css` för bakåtkompatibilitet med andra sidor

**Åtgärd:**
- Skapa ny `landningssida.css` specifikt för index.php
- Låt andra verktyg/sidor fortsätta använda `menykort.css`
- Eventuellt lägg till `body.landing-page` wrapper för specifik styling

---

## 📁 Filstruktur efter implementering

```
mackan_eu/
├── config/
│   └── tools.php ← UPPDATERA: Lägg till category + featured
├── css/
│   ├── main.css ← UPPDATERA: Importera landningssida.css
│   ├── variables.css ← KAN UPPDATERA: Nya dark mode variabler
│   └── blocks/
│       ├── menykort.css ← BEHÅLL (för andra sidor)
│       └── landningssida.css ← NY FIL
├── js/
│   ├── theme-toggle.js ← NY FIL
│   └── view-toggle.js ← NY FIL
├── includes/
│   ├── layout-start.php ← UPPDATERA: data-theme på <html>
│   └── layout-end.php ← UPPDATERA/SKAPA: Inkludera JS
└── index.php ← UPPDATERA: Ny struktur med kategorier
```

---

## ✅ Checklista

### 1. config/tools.php
- [ ] Lägg till `category` för alla 26 verktyg
- [ ] Lägg till `featured: true` för 3 verktyg
- [ ] Testa att filen returnerar korrekt array

### 2. CSS
- [ ] Skapa `css/blocks/landningssida.css`
- [ ] Kopiera relevanta styles från v2-mockup-1-clean.html
- [ ] Lägg till `:root[data-theme="light"]` och `[data-theme="dark"]`
- [ ] Importera i `css/main.css`
- [ ] Testa responsivitet (mobil, tablet, desktop)

### 3. JavaScript
- [ ] Skapa `js/theme-toggle.js`
- [ ] Implementera localStorage-hantering
- [ ] Skapa `js/view-toggle.js`
- [ ] Testa att toggle fungerar per kategori

### 4. PHP
- [ ] Uppdatera `index.php` med ny struktur
- [ ] Filtrera featured tools
- [ ] Gruppera verktyg per kategori
- [ ] Rendera hero-sektion
- [ ] Rendera kategori-sektioner med headers
- [ ] Uppdatera `includes/layout-start.php` med `data-theme`
- [ ] Inkludera JS-filer i `layout-end.php`

### 5. Navigation
- [ ] Lägg till temaväxlings-knapp i nav
- [ ] Gör nav sticky
- [ ] Testa på alla skärmstorlekar

### 6. Testning
- [ ] Testa alla featured tools visas korrekt
- [ ] Testa alla kategorier renderas
- [ ] Testa temaväxling (ljust/mörkt)
- [ ] Testa lista/rutnät-toggle per kategori
- [ ] Testa på mobil, tablet, desktop
- [ ] Testa dark mode sparas i localStorage
- [ ] Testa att alla 26 verktyg visas
- [ ] Testa alla länkar fungerar

### 7. Optimering
- [ ] Minifiera CSS (valfritt)
- [ ] Minifiera JS (valfritt)
- [ ] Optimera bilder för featured cards (om några läggs till)
- [ ] Testa Lighthouse score
- [ ] Testa page load speed

---

## 🚀 Prioriterad ordning

1. **Uppdatera config/tools.php** (10 min)
2. **Skapa landningssida.css** (30-45 min)
3. **Skapa theme-toggle.js och view-toggle.js** (15 min)
4. **Uppdatera index.php** (30 min)
5. **Uppdatera layout-start/end.php** (10 min)
6. **Testning och bugfixar** (30 min)

**Total uppskattad tid:** 2-3 timmar

---

## 🔄 Backward Compatibility

### Bibehåll:
- `css/blocks/menykort.css` - För andra sidor som använder meny-kort
- Gamla verktyg-länkar fungerar exakt som förut
- Inget verktyg påverkas, bara index.php ändras

### Försiktighet:
- Testa att `main.css` inte får konflikter mellan `menykort.css` och `landningssida.css`
- Använd specifika selektorer för landningssidan (t.ex. `.landing-page .tool-card`)

---

## 📝 Kategorimappning (förslag)

| Verktyg | Kategori | Featured? |
|---------|----------|-----------|
| Bildkonverterare | konvertering | ✅ Ja |
| CSV till JSON | konvertering | |
| CSS till JSON | konvertering | |
| JSON Converter | konvertering | |
| QR-kodgenerator | generatorer | ✅ Ja |
| QR-kodverkstad | generatorer | |
| QR-kodgenerator (v1) | generatorer | |
| Lösenordsgenerator | generatorer | ✅ Ja |
| Persontestdata | generatorer | |
| Telefonnummergenerator | generatorer | |
| Test-ID | generatorer | |
| GeoParser & Plotter | geo | |
| Koordinatkonverterare | geo | |
| Koordinater Impex | geo | |
| Skyddad delning | sakerhet | |
| Kortlänk | sakerhet | |
| Addy | sakerhet | |
| Text till tal | ovrigt | |
| Timer & klocka | ovrigt | |
| Flow Builder | ovrigt | |
| Stötta | ovrigt | |
| Bolagsverket | ovrigt | |
| Aptus | ovrigt | |
| RKA-kalkylator | ovrigt | |
| PTS Diarium | ovrigt | |

---

## 🎨 Designbeslut

### Färgpalett (från v2-mockup-1-clean):
```css
/* Light mode */
--bg-primary: #ffffff
--bg-secondary: #f9fafb
--bg-tertiary: #f3f4f6
--text-primary: #1f2937
--text-secondary: #6b7280
--border-color: #e5e7eb

/* Dark mode */
--bg-primary: #181a1b
--bg-secondary: #1f2124
--bg-tertiary: #2a2d30
--text-primary: #f2f2f2
--text-secondary: #9ca3af
--border-color: #374151
```

### Typografi:
- Font: Inter (fallback till system fonts)
- H1: 3rem, weight 800, letter-spacing -0.03em
- Tool title: 0.95rem, weight 600
- Tool desc: 0.8rem, color secondary

### Spacing:
- Container max-width: 1400px
- Padding: 2rem (desktop), 1rem (mobil)
- Gap mellan kort: 1rem
- Featured card padding: 2rem

---

## 💡 Tips för framtiden

### Lägg till nya verktyg:
1. Öppna `config/tools.php`
2. Lägg till ny array med:
   - `title` (obligatorisk)
   - `href` (obligatorisk)
   - `icon` (FontAwesome class)
   - `desc` (kort beskrivning)
   - `category` (en av: konvertering, generatorer, geo, sakerhet, ovrigt)
   - `featured` (true/false, default false)
3. Spara - klart!

### Ändra featured tools:
1. Öppna `config/tools.php`
2. Sätt `'featured' => true` på önskade verktyg (max 3 rekommenderas)
3. Ta bort eller sätt `'featured' => false` på gamla featured
4. Spara - klart!

### Lägga till ny kategori:
1. Öppna `index.php`
2. Lägg till ny kategori i `$categories`-arrayen
3. Sätt title och icon
4. Uppdatera verktyg i `config/tools.php` med den nya kategorin
5. Klart!

---

## ⚠️ Potentiella problem

### Problem 1: CSS-konflikter
**Risk:** `menykort.css` och `landningssida.css` kan krocka
**Lösning:** Använd specifika klasser (`.landing-page` wrapper) eller namnge om klasser

### Problem 2: LocalStorage inte stödjs
**Risk:** Gamla browsers kanske inte stödjer localStorage
**Lösning:** Lägg till try/catch runt localStorage-anrop

### Problem 3: Featured tools saknas
**Risk:** Ingen featured-flagga = tom hero
**Lösning:** Ha en fallback som visar de 3 första verktygen om inga featured finns

### Problem 4: Verktyg utan kategori
**Risk:** Verktyg försvinner om kategori saknas
**Lösning:** Default till 'ovrigt' om category saknas

---

## 🎯 Framtida förbättringar (nice-to-have)

- [ ] Sökfunktion för verktyg
- [ ] Filtrera verktyg per kategori (klicka på kategori-chips)
- [ ] Alfabetisk sortering per kategori (eller custom ordning)
- [ ] Animationer vid scroll (fade-in effekter)
- [ ] "Nyligen använda" baserat på localStorage
- [ ] Stjärnmarkering av favorit-verktyg
- [ ] Dela-knappar för enskilda verktyg
- [ ] Analytics för mest använda verktyg

---

**Skapad:** 2025-11-17
**Status:** Planering klar, redo för implementering
**Uppskattad tid:** 2-3 timmar
**Svårighetsgrad:** Medel
