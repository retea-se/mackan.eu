# Förbättringsförslag för Tools-mappen

**Datum**: 2025-11-13
**Analys av**: Alla verktyg under `/tools/`
**Status**: Analys komplett - **INGEN KOD ÄNDRAD** (enligt begäran)

---

## 📊 Översikt

Totalt **22 verktyg** analyserade. Flera områden har identifierats för förbättring.

---

## 🎯 1. KONSISTENS & STANDARDISERING

### Problem:

- **Olika CSS-strukturer**: Vissa verktyg använder ny BEM-struktur (`.form`, `.knapp`), andra gamla klasser (`.container`, `.button`)
- **Olika JavaScript-patterns**: ES6 modules vs. vanilla JS, olika felhantering
- **Olika formulärstrukturer**: Inkonsistent användning av labels, placeholders, validering

### Exempel:

- `tools/converter/index.php` - Använder egna klasser (`.converter-container`, `.converter-tabs`)
- `tools/tts/index.php` - Använder gamla klasser (`.title`, `.subtitle`) och saknar `metaDescription`
- `tools/addy/index.php` - Saknar `metaDescription`
- `tools/rka/index.php` - Använder inline-stilar och gamla strukturer

### Förslag:

1. **Standardisera alla verktyg** till ny BEM-struktur (`.form`, `.knapp`, `.tabell`)
2. **Skapa gemensam JavaScript-bas** för vanliga funktioner (validering, felhantering, export)
3. **Enhetlig formulärstruktur** - Alla formulär ska följa samma mönster
4. **Migrera gamla verktyg** till ny struktur gradvis

---

## 🔍 2. SEO & META-TAGGAR

### Problem:

- **Vissa verktyg saknar `metaDescription`**: `addy`, `tts`, `rka/index.php`
- **Inkonsistent användning av strukturerad data** (JSON-LD): Endast `qr_v3` och `koordinat` har det
- **Saknade `keywords`**: De flesta verktyg saknar keywords
- **Saknade `canonical`**: De flesta verktyg saknar canonical URLs

### Exempel:

```php
// tools/addy/index.php - Saknar metaDescription
$title = 'AnonAddy Address Generator';
// Saknar: $metaDescription, $keywords, $canonical

// tools/tts/index.php - Saknar metaDescription
$title = 'Text-to-Speech';
// Saknar: $metaDescription, $keywords, $canonical
```

### Förslag:

1. **Lägg till `metaDescription`** för alla verktyg som saknar det
2. **Lägg till `keywords`** för alla verktyg (för SEO)
3. **Lägg till `canonical`** för alla verktyg (för SEO)
4. **Lägg till strukturerad data** (JSON-LD) för alla verktyg (för rich snippets)
5. **Standardisera meta-taggar** - Skapa en mall för meta-taggar

---

## 🛡️ 3. SÄKERHET & VALIDERING

### Problem:

- **Inkonsistent input-validering**: Vissa verktyg validerar input, andra inte
- **Olika metoder för input-sanering**: Vissa använder `htmlspecialchars()`, andra inte
- **Saknad CSRF-skydd**: De flesta verktyg saknar CSRF-tokens
- **Saknad rate limiting**: Endast `kortlank` har rate limiting

### Exempel:

```php
// tools/rka/index.php - Ingen validering av POST-data
$rating = $_POST['rating'] ?? 100; // Ingen validering!

// tools/converter/utilities.js - Använder eval() (säkerhetsrisk!)
result = JSON.stringify(eval("(" + input + ")"), null, 2);
```

### Förslag:

1. **Standardisera input-validering** - Skapa gemensam valideringsfunktion
2. **Lägg till CSRF-skydd** för alla formulär som hanterar POST-data
3. **Lägg till rate limiting** för alla API-endpoints
4. **Ta bort `eval()`** från `tools/converter/utilities.js` (säkerhetsrisk!)
5. **Sanera all output** med `htmlspecialchars()` konsekvent

---

## 🎨 4. ANVÄNDARUPPLEVELSE (UX)

### Problem:

- **Saknade loading-indikatorer**: Vissa verktyg visar ingen feedback under laddning
- **Inkonsistent felhantering**: Olika sätt att visa fel (alert, console, in-page)
- **Saknade bekräftelsemeddelanden**: Vissa verktyg ger ingen feedback vid framgång
- **Olika export-funktionalitet**: Vissa verktyg har export, andra inte

### Exempel:

```javascript
// tools/pts/script.js - Använder alert() för fel
alert(`Kunde inte hämta ärenden: ${err.message}`);

// tools/testdata/script.js - Ingen loading-indikator
generateBtn.addEventListener('click', async () => {
  // Ingen feedback under laddning!
  const [baseRes, pnrRes] = await Promise.all([...]);
});
```

### Förslag:

1. **Lägg till loading-indikatorer** för alla asynkrona operationer
2. **Standardisera felhantering** - Använd `.toast` för felmeddelanden istället för `alert()`
3. **Lägg till bekräftelsemeddelanden** för alla operationer (t.ex. "Kopierad!", "Exporterad!")
4. **Lägg till export-funktionalitet** för verktyg som saknar det (t.ex. `addy`, `tts`)
5. **Förbättra tillgänglighet** - Lägg till ARIA-labels och keyboard-navigation

---

## 📱 5. RESPONSIVITET & TILLGÅNGLIGHET

### Problem:

- **Inkonsistent responsivitet**: Vissa verktyg är inte testade för mobil
- **Saknade ARIA-labels**: Vissa verktyg saknar ARIA-labels för tillgänglighet
- **Inline-stilar**: Vissa verktyg använder inline-stilar istället för CSS-klasser
- **Saknad keyboard-navigation**: Vissa verktyg är inte keyboard-navigerbara

### Exempel:

```php
// tools/rka/index.php - Inline-stilar
$class = $gamma<=1.2 ? 'kort--gron' : ($gamma<=1.6 ? 'kort--gul' : 'kort--rod');

// tools/stotta/script.js - Inline-stilar
warning.style.marginRight = '5px';
```

### Förslag:

1. **Testa alla verktyg för mobil** - Säkerställ att alla verktyg fungerar på mobil
2. **Lägg till ARIA-labels** för alla interaktiva element
3. **Ta bort inline-stilar** - Använd CSS-klasser istället
4. **Förbättra keyboard-navigation** - Säkerställ att alla verktyg är keyboard-navigerbara
5. **Förbättra färgkontrast** - Säkerställ att alla verktyg uppfyller WCAG-riktlinjer

---

## ⚡ 6. PRESTANDA & OPTIMERING

### Problem:

- **Saknad lazy loading**: Vissa verktyg laddar allt direkt
- **Saknad code splitting**: Vissa verktyg laddar onödiga bibliotek
- **Saknad caching**: Vissa verktyg gör onödiga API-anrop
- **Stora JavaScript-filer**: Vissa verktyg har stora JS-filer som kan optimeras

### Exempel:

```html
<!-- tools/qr_v3/index.php - Laddar flera CDN-bibliotek -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/docx@8.5.0/build/index.js"></script>
```

### Förslag:

1. **Implementera lazy loading** för stora bibliotek (t.ex. Leaflet, QR-code)
2. **Optimera JavaScript-filer** - Minifiera och bundle JS-filer
3. **Implementera caching** för API-anrop (t.ex. `bolagsverket`, `pts`)
4. **Använd CDN-fallback** - Lägg till fallback för CDN-bibliotek
5. **Optimera bilder** - Komprimera och använd moderna bildformat (WebP)

---

## 🔧 7. KOD-DUPLICERING & UNDERHÅLLBARHET

### Problem:

- **Duplicerad kod**: Liknande kod upprepas mellan verktyg
- **Saknad gemensam bas**: Inga gemensamma funktioner för vanliga uppgifter
- **Inkonsistent felhantering**: Olika sätt att hantera fel
- **Saknad dokumentation**: Vissa verktyg saknar kommentarer

### Exempel:

```javascript
// tools/pts/script.js - Duplicerad felhantering
catch (err) {
  console.error('[v8] ❌ Fel vid hämtning:', err);
  alert(`Kunde inte hämta ärenden: ${err.message}`);
}

// tools/bolagsverket/script.js - Liknande felhantering
catch (err) {
  console.error('Fel vid hämtning:', err);
  alert(`Kunde inte hämta data: ${err.message}`);
}
```

### Förslag:

1. **Skapa gemensam JavaScript-bas** - `includes/tools-common.js` med vanliga funktioner
2. **Skapa gemensam PHP-bas** - `includes/tools-common.php` med vanliga funktioner
3. **Standardisera felhantering** - Skapa gemensam felhanteringsfunktion
4. **Lägg till kommentarer** - Dokumentera komplexa funktioner
5. **Refaktorisera duplicerad kod** - Flytta gemensam kod till gemensamma filer

---

## 📚 8. DOKUMENTATION & README

### Problem:

- **Saknade readme-filer**: Vissa verktyg saknar readme (men det har vi fixat nu!)
- **Inkonsistent dokumentation**: Olika kvalitet på readme-filer
- **Saknad API-dokumentation**: Vissa verktyg har API-endpoints utan dokumentation
- **Saknad användningsguide**: Vissa verktyg saknar användningsguide

### Exempel:

- `tools/addy/index.php` - Saknar readme (men det har vi fixat nu!)
- `tools/tts/index.php` - Saknar readme (men det har vi fixat nu!)
- `tools/qr_v3/index.php` - Saknar readme (men det har vi fixat nu!)

### Förslag:

1. **Lägg till readme för alla verktyg** som saknar det (✅ redan fixat!)
2. **Standardisera readme-struktur** - Använd `mall_readme.php` som mall
3. **Lägg till API-dokumentation** för verktyg med API-endpoints (t.ex. `kortlank`, `bolagsverket`)
4. **Lägg till användningsguide** för komplexa verktyg (t.ex. `rka`, `koordinat`)
5. **Lägg till exempel** i readme-filer för att visa hur verktygen används

---

## 🎯 9. SPECIFIKA VERKTYGSPROBLEM

### `tools/converter/utilities.js`

- **Problem**: Använder `eval()` (säkerhetsrisk!)
- **Förslag**: Ersätt `eval()` med `JSON.parse()` eller säker parser

### `tools/tts/index.php`

- **Problem**: Använder gamla klasser (`.title`, `.subtitle`), saknar `metaDescription`
- **Förslag**: Migrera till ny BEM-struktur, lägg till `metaDescription`

### `tools/addy/index.php`

- **Problem**: Saknar `metaDescription`, saknar export-funktionalitet
- **Förslag**: Lägg till `metaDescription`, lägg till export-funktionalitet

### `tools/rka/index.php`

- **Problem**: Använder inline-stilar, saknar `metaDescription`, komplex struktur
- **Förslag**: Migrera till ny BEM-struktur, lägg till `metaDescription`, refaktorisera

### `tools/dsu/`

- **Problem**: Verktyget verkar vara ett separat projekt (React, Node.js)
- **Förslag**: Överväg att flytta till separat repository eller dokumentera som separat projekt

### `tools/timer/`

- **Problem**: Verktyget är en extern länk (`https://mackan.eu/timer`) istället för ett verktyg i mappen
- **Förslag**: Överväg att flytta timer till `tools/timer/` eller dokumentera som extern länk

---

## 🚀 10. PRIORITERING

### Hög prioritet (Säkerhet & Kritiska buggar):

1. **Ta bort `eval()` från `tools/converter/utilities.js`** (säkerhetsrisk!)
2. **Lägg till input-validering** för alla verktyg som hanterar POST-data
3. **Lägg till CSRF-skydd** för alla formulär

### Medel prioritet (UX & SEO):

1. **Lägg till `metaDescription`** för alla verktyg som saknar det
2. **Lägg till loading-indikatorer** för alla asynkrona operationer
3. **Standardisera felhantering** - Använd `.toast` istället för `alert()`
4. **Lägg till strukturerad data** (JSON-LD) för alla verktyg

### Låg prioritet (Optimering & Underhåll):

1. **Migrera gamla verktyg** till ny BEM-struktur
2. **Skapa gemensam JavaScript-bas** för vanliga funktioner
3. **Optimera JavaScript-filer** - Minifiera och bundle
4. **Lägg till API-dokumentation** för verktyg med API-endpoints

---

## 📋 SAMMANFATTNING

### Totalt antal förbättringar identifierade:

- **Säkerhet**: 5 förbättringar
- **SEO**: 5 förbättringar
- **UX**: 5 förbättringar
- **Kodkvalitet**: 5 förbättringar
- **Prestanda**: 5 förbättringar
- **Dokumentation**: 5 förbättringar

### Totalt: **30 förbättringar** identifierade

### Nästa steg:

1. **Diskutera prioriteter** med teamet
2. **Skapa issues** för varje förbättring
3. **Implementera förbättringar** gradvis
4. **Testa förbättringar** i produktion

---

## 💡 REKOMMENDATIONER

### Kortsiktigt (1-2 veckor):

1. Ta bort `eval()` från `tools/converter/utilities.js`
2. Lägg till `metaDescription` för alla verktyg som saknar det
3. Lägg till input-validering för alla verktyg som hanterar POST-data

### Medelsiktigt (1-2 månader):

1. Standardisera felhantering - Använd `.toast` istället för `alert()`
2. Lägg till loading-indikatorer för alla asynkrona operationer
3. Migrera gamla verktyg till ny BEM-struktur

### Långsiktigt (3-6 månader):

1. Skapa gemensam JavaScript-bas för vanliga funktioner
2. Optimera JavaScript-filer - Minifiera och bundle
3. Lägg till API-dokumentation för verktyg med API-endpoints

---

**Status**: Analys klar - **INGEN KOD ÄNDRAD** (enligt begäran)
**Nästa steg**: Diskutera prioriteter och implementera förbättringar gradvis
