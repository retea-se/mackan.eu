# 🎨 UX: Lägg till loading-indikatorer för asynkrona operationer

## 🎨 UX-problem - Saknade loading-indikatorer

### Problem
Flera verktyg visar ingen feedback till användaren under asynkrona operationer (t.ex. API-anrop, datagenerering). Detta ger dålig användarupplevelse eftersom användaren inte vet om något händer.

### Verktyg som saknar loading-indikatorer

#### 1. Testdata (`tools/testdata/`)
- **Fil**: `tools/testdata/script.js`
- **Rad**: 87-143
- **Problem**: Ingen loading-indikator när personer genereras
- **Kod**:
  ```javascript
  generateBtn.addEventListener('click', async () => {
    // Ingen feedback under laddning!
    const [baseRes, pnrRes] = await Promise.all([
      fetch('generate.php'),
      fetch('generatePerson.php')
    ]);
    // ...
  });
  ```

#### 2. PTS Diarium (`tools/pts/`)
- **Fil**: `tools/pts/script.js`
- **Rad**: 25-57
- **Problem**: Ingen loading-indikator när ärenden hämtas
- **Kod**:
  ```javascript
  async function hamtaDiarium(start, end) {
    // Ingen feedback under laddning!
    const res = await fetch(url);
    // ...
  }
  ```

#### 3. Bolagsverket (`tools/bolagsverket/`)
- **Fil**: `tools/bolagsverket/script.js`
- **Rad**: 10-31
- **Problem**: Ingen loading-indikator när token hämtas
- **Kod**:
  ```javascript
  form.addEventListener('submit', async (e) => {
    // Ingen feedback under laddning!
    const response = await fetch('get_token.php');
    // ...
  });
  ```

#### 4. Converter (`tools/converter/`)
- **Filer**:
  - `tools/converter/csvtojson.js` - Ingen loading-indikator
  - `tools/converter/validator.js` - Ingen loading-indikator
  - `tools/converter/fixer.js` - Ingen loading-indikator

#### 5. Koordinat (`tools/koordinat/`)
- **Fil**: `tools/koordinat/map.js` (eller liknande)
- **Problem**: Ingen loading-indikator när koordinater konverteras eller kartan laddas

### Lösning
1. **Skapa gemensam loading-funktion** i `includes/tools-common.js`
2. **Lägg till loading-indikatorer** för alla asynkrona operationer
3. **Använd CSS-spinner** som redan finns i projektet
4. **Disable knappar** under laddning för att förhindra dubbelklick

### Exempel på korrekt implementation
```javascript
// Skapa loading-funktion
function showLoading(button, text = 'Laddar...') {
  button.disabled = true;
  button.dataset.originalText = button.textContent;
  button.innerHTML = `<span class="spinner"></span> ${text}`;
}

function hideLoading(button) {
  button.disabled = false;
  button.textContent = button.dataset.originalText || button.textContent;
}

// Användning
generateBtn.addEventListener('click', async () => {
  showLoading(generateBtn, 'Genererar personer...');

  try {
    const [baseRes, pnrRes] = await Promise.all([
      fetch('generate.php'),
      fetch('generatePerson.php')
    ]);
    // ... hantera resultat
  } catch (err) {
    showToast(`Fel: ${err.message}`, 'error');
  } finally {
    hideLoading(generateBtn);
  }
});
```

### Verktyg att uppdatera
1. ✅ `tools/testdata/script.js` - Lägg till loading-indikator för persongenerering
2. ✅ `tools/pts/script.js` - Lägg till loading-indikator för ärendehämtning
3. ✅ `tools/bolagsverket/script.js` - Lägg till loading-indikator för tokenhämtning
4. ✅ `tools/converter/csvtojson.js` - Lägg till loading-indikator för konvertering
5. ✅ `tools/converter/validator.js` - Lägg till loading-indikator för validering
6. ✅ `tools/converter/fixer.js` - Lägg till loading-indikator för reparation
7. ✅ `tools/koordinat/map.js` - Lägg till loading-indikator för kartladdning
8. ✅ `tools/bolagsverket/getdata.js` - Lägg till loading-indikator för datahämtning

### Ytterligare förbättringar
- Lägg till progress-indikatorer för långa operationer (t.ex. batch-generering)
- Lägg till skeleton-loaders för bättre UX
- Standardisera alla loading-indikatorer - Använd samma format

### Prioritet
**MEDEL** - UX-förbättring som bör göras

### Relaterade issues
- Standardisera felhantering - Ersätt alert() med toast

### Labels
- `enhancement`
- `ux`
- `medium-priority`
- `tools`

---
**Analysdatum**: 2025-11-13
**Kategori**: UX
**Status**: 🟡 Medel prioritet

