# 🔧 KODKVALITET: Skapa gemensam JavaScript-bas för vanliga funktioner

## 🔧 Kodkvalitet - Kod-duplicering

### Problem
Flera verktyg har duplicerad kod för vanliga funktioner som felhantering, validering, export, etc. Detta gör koden svår att underhålla och ökar risken för buggar.

### Duplicerad kod

#### 1. Felhantering
- **Filer**:
  - `tools/pts/script.js` - Rad 51-56
  - `tools/bolagsverket/script.js` - Rad 26-30
  - `tools/testdata/script.js` - Rad 140-142
- **Problem**: Liknande felhantering upprepas i flera filer
- **Kod**:
  ```javascript
  // tools/pts/script.js
  catch (err) {
    console.error('[v8] ❌ Fel vid hämtning:', err);
    alert(`Kunde inte hämta ärenden: ${err.message}`);
  }

  // tools/bolagsverket/script.js
  catch (error) {
    tokenOutput.textContent = 'Fel vid hämtning av token: ' + error.message;
    console.error('Fel vid hämtning av token:', error);
  }
  ```

#### 2. Export-funktionalitet
- **Filer**:
  - `tools/testdata/export.js`
  - `tools/testid/export.js`
  - `tools/pts/export.js`
  - `tools/passwordgenerator/export.js`
- **Problem**: Liknande export-funktioner upprepas i flera filer

#### 3. Validering
- **Filer**:
  - `tools/stotta/script.js` - Personnummer-validering
  - `tools/testdata/script.js` - Personnummer-validering
- **Problem**: Liknande valideringsfunktioner upprepas

#### 4. Loading-indikatorer
- **Problem**: Inga gemensamma funktioner för loading-indikatorer
- **Lösning**: Skapa gemensam funktion i `includes/tools-common.js`

### Lösning
1. **Skapa gemensam JavaScript-fil** `includes/tools-common.js` med vanliga funktioner
2. **Flytta duplicerad kod** till gemensamma funktioner
3. **Uppdatera alla verktyg** för att använda gemensamma funktioner
4. **Dokumentera funktioner** med JSDoc-kommentarer

### Exempel på gemensam fil
```javascript
// includes/tools-common.js

/**
 * Visar ett toast-meddelande till användaren
 * @param {string} message - Meddelandet att visa
 * @param {string} type - Typ av meddelande ('error', 'success', 'info')
 */
export function showToast(message, type = 'error') {
  const toast = document.createElement('div');
  toast.className = `toast toast--${type}`;
  toast.textContent = message;
  toast.setAttribute('role', 'alert');
  toast.setAttribute('aria-live', 'assertive');

  document.body.appendChild(toast);

  setTimeout(() => toast.classList.add('toast--visible'), 10);
  setTimeout(() => {
    toast.classList.remove('toast--visible');
    setTimeout(() => toast.remove(), 300);
  }, 5000);
}

/**
 * Visar en loading-indikator på en knapp
 * @param {HTMLButtonElement} button - Knappen att visa loading på
 * @param {string} text - Text att visa under laddning
 */
export function showLoading(button, text = 'Laddar...') {
  button.disabled = true;
  button.dataset.originalText = button.textContent;
  button.innerHTML = `<span class="spinner"></span> ${text}`;
}

/**
 * Döljer loading-indikatorn på en knapp
 * @param {HTMLButtonElement} button - Knappen att dölja loading på
 */
export function hideLoading(button) {
  button.disabled = false;
  button.textContent = button.dataset.originalText || button.textContent;
}

/**
 * Exporterar data till CSV
 * @param {Array} data - Data att exportera
 * @param {string} filename - Filnamn för exporten
 */
export function exportToCSV(data, filename = 'export.csv') {
  // ... implementation
}

/**
 * Exporterar data till JSON
 * @param {Array} data - Data att exportera
 * @param {string} filename - Filnamn för exporten
 */
export function exportToJSON(data, filename = 'export.json') {
  // ... implementation
}

/**
 * Validerar ett svenskt personnummer
 * @param {string} pnr - Personnummer att validera
 * @returns {boolean} - True om personnumret är giltigt
 */
export function validatePersonnummer(pnr) {
  // ... implementation
}
```

### Verktyg att uppdatera
1. ✅ `tools/pts/script.js` - Använd `showToast()` och `showLoading()` från common
2. ✅ `tools/bolagsverket/script.js` - Använd `showToast()` och `showLoading()` från common
3. ✅ `tools/testdata/script.js` - Använd `showToast()`, `showLoading()`, `exportToCSV()`, `exportToJSON()` från common
4. ✅ `tools/testid/script.js` - Använd `exportToCSV()`, `exportToJSON()` från common
5. ✅ `tools/passwordgenerator/export.js` - Använd `exportToCSV()`, `exportToJSON()` från common
6. ✅ `tools/stotta/script.js` - Använd `validatePersonnummer()` från common
7. ✅ `tools/testdata/script.js` - Använd `validatePersonnummer()` från common

### Ytterligare förbättringar
- Skapa gemensam PHP-fil `includes/tools-common.php` för server-side funktioner
- Standardisera alla API-anrop - Använd gemensam fetch-wrapper
- Standardisera alla formulärvalideringar - Använd gemensam valideringsfunktion

### Prioritet
**LÅG** - Kodkvalitetsförbättring som kan göras gradvis

### Relaterade issues
- Migrera gamla verktyg till BEM-struktur
- Standardisera felhantering - Ersätt alert() med toast

### Labels
- `enhancement`
- `refactoring`
- `low-priority`
- `tools`

---
**Analysdatum**: 2025-11-13
**Kategori**: Kodkvalitet
**Status**: 🟢 Låg prioritet

