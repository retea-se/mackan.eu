# 🎨 UX: Standardisera felhantering - Ersätt alert() med toast

## 🎨 UX-problem - Inkonsistent felhantering

### Problem
Flera verktyg använder `alert()` för felmeddelanden vilket ger dålig användarupplevelse. `alert()` blockerar användaren och ser inte professionellt ut. Verktyg bör använda toast-meddelanden istället.

### Verktyg som använder alert()

#### 1. PTS Diarium (`tools/pts/`)
- **Fil**: `tools/pts/script.js`
- **Rad**: 53
- **Kod**:
  ```javascript
  alert(`Kunde inte hämta ärenden: ${err.message}`);
  ```

#### 2. Bolagsverket (`tools/bolagsverket/`)
- **Fil**: `tools/bolagsverket/script.js`
- **Rad**: 27, 28
- **Kod**:
  ```javascript
  tokenOutput.textContent = 'Fel vid hämtning av token: ' + error.message;
  console.error('Fel vid hämtning av token:', error);
  ```
- **Notera**: Använder `textContent` istället för `alert()`, men saknar toast

#### 3. Testdata (`tools/testdata/`)
- **Fil**: `tools/testdata/script.js`
- **Status**: ⚠️ Använder `console.error()` men ingen visuell feedback till användaren

#### 4. Converter (`tools/converter/`)
- **Filer**:
  - `tools/converter/validator.js` - Använder `output.value = "❌ Fel: " + e.message;`
  - `tools/converter/fixer.js` - Använder `output.value = "❌ Kunde inte reparera: " + e.message;`
  - `tools/converter/utilities.js` - Använder `output.value = "❌ Fel: " + e.message;`

### Lösning
1. **Skapa gemensam toast-funktion** i `includes/tools-common.js`
2. **Ersätt alla `alert()`** med toast-meddelanden
3. **Använd `.toast` CSS-klassen** som redan finns i projektet
4. **Standardisera felmeddelanden** - Använd samma format för alla fel

### Exempel på korrekt implementation
```javascript
// Skapa toast-funktion
function showToast(message, type = 'error') {
  const toast = document.createElement('div');
  toast.className = `toast toast--${type}`;
  toast.textContent = message;
  toast.setAttribute('role', 'alert');
  toast.setAttribute('aria-live', 'assertive');

  document.body.appendChild(toast);

  // Visa toast
  setTimeout(() => toast.classList.add('toast--visible'), 10);

  // Dölj toast efter 5 sekunder
  setTimeout(() => {
    toast.classList.remove('toast--visible');
    setTimeout(() => toast.remove(), 300);
  }, 5000);
}

// Användning
try {
  // ... kod
} catch (err) {
  showToast(`Kunde inte hämta ärenden: ${err.message}`, 'error');
  console.error('Fel:', err);
}
```

### Verktyg att uppdatera
1. ✅ `tools/pts/script.js` - Ersätt `alert()` med toast
2. ✅ `tools/bolagsverket/script.js` - Lägg till toast för felmeddelanden
3. ✅ `tools/testdata/script.js` - Lägg till toast för felmeddelanden
4. ✅ `tools/converter/validator.js` - Förbättra felhantering med toast
5. ✅ `tools/converter/fixer.js` - Förbättra felhantering med toast
6. ✅ `tools/converter/utilities.js` - Förbättra felhantering med toast

### Ytterligare förbättringar
- Lägg till bekräftelsemeddelanden för lyckade operationer (t.ex. "Kopierad!", "Exporterad!")
- Lägg till loading-indikatorer för asynkrona operationer
- Standardisera alla felmeddelanden - Använd samma format

### Prioritet
**MEDEL** - UX-förbättring som bör göras

### Relaterade issues
- Lägg till loading-indikatorer för asynkrona operationer

### Labels
- `enhancement`
- `ux`
- `medium-priority`
- `tools`

---
**Analysdatum**: 2025-11-13
**Kategori**: UX
**Status**: 🟡 Medel prioritet

