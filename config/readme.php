<!-- config/readme.php - v3 -->
<!--
📘 Dokumentation: Struktur & utvecklingsprinciper för mackan.eu

📁 MAPPSTRUKTUR

/                 → Webbplatsens rot (innehåller index.php)
/css/             → Alla stilmallar i blocks-format (importeras via main.css)
/js/              → Globala skript (temaväxling, export, import, tippy)
/config/          → Delade datafiler (t.ex. tools.php) och denna dokumentation
/includes/        → PHP-moduler: header, footer, layout-start/end m.m.
/tools/           → Varje verktyg har en egen undermapp (ex: /tools/testid/)
/blocks/          → En CSS-fil per komponent (BEM-struktur), importeras i main.css

🎨 CSS-struktur
- Alla komponenter följer BEM-konvention (block__element--modifier)
- CSS delas upp i separata filer under `/blocks/`:
  - En fil per komponent (kort, knapp, toast, osv.)
  - Importeras samlat via `main.css`
- Alla färger, mått och typsnitt styrs via `variables.css`
- `theme.css` hanterar mörkt/ljust tema via `[data-theme]`

📂 /css/
|
|├─ main.css         → Samlad importfil (endast @import)
|├─ reset.css        → Återställer browserstandard
|├─ variables.css    → CSS-variabler för färger, typsnitt, spacing
|├─ theme.css        → Temastöd (dark/light)
|├─ layout.css       → Sido- och gridstruktur
|└─ blocks/          → Alla komponentbaserade CSS-filer (nedan)

📂 /css/blocks/
|
|├─ falt.css         → Inputs, textarea, select
|├─ form.css         → Formulärgrupper, verktygsrader
|├─ ikon.css         → Ikonknappar, hjälpikoner
|├─ knapp.css        → Knappar, ikonknappar, tillstånd
|├─ kort.css         → Kortlayout och innehåll
|├─ layout.css       → Layoutcontainrar, sektioner
|├─ menykort.css     → Menykort för startsida
|├─ rubrik.css       → Rubrikkomponenter
|├─ sidfot.css       → Sidfotsdesign
|├─ tabell.css       → Tabellutseende, wrapper, mobil
|├─ tema.css         → Temaväxlingseffekter
|├─ toast.css        → Meddelandefält
|├─ utilities.css    → .utils--*, spacing, textcenter, dolda
|├─ verktygsinfo.css → Infofält under resultat
|├─ losenord.css     → Layout för lösenordsgenerering
|├─ diagram.css      → Canvas-container för t.ex. charts

🧪 CSS-hierarki

1. **reset.css** → återställ stil
2. **variables.css** → definierar alla tokens
3. **theme.css** → mörkt/ljust tema
4. **layout.css** → struktur för sidhuvud, sektion, container
5. **blocks/** → en fil per komponent (knapp, tabell, toast, osv.)
6. **utilities.css** → små hjälpregler
7. **verktygsspecifik CSS** → endast vid behov

🧪 Exempel på BEM

```html
<form class="form__centrerad">
  <div class="form__grupp">
    <label class="falt__etikett">Namn</label>
    <input class="falt__input" />
    <p class="form__hint">Fyll i ditt fullständiga namn</p>
  </div>
  <div class="form__verktyg">
    <button class="knapp knapp--fara">Avbryt</button>
    <button class="knapp">OK</button>
  </div>
</form>
