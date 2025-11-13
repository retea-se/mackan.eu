# 🛡️ SÄKERHET: Ta bort eval() från converter/utilities.js

## 🚨 Säkerhetsproblem

### Problem
Filen `tools/converter/utilities.js` använder `eval()` i funktionen `runUtility()` vilket är en säkerhetsrisk. `eval()` kan köra godtycklig JavaScript-kod och öppnar för XSS-attacker.

### Specifik fil
- `tools/converter/utilities.js` (rad 53)

### Nuvarande kod
```javascript
case 'stringify':
  result = JSON.stringify(eval("(" + input + ")"), null, 2);
  break;
```

### Lösning
Ersätt `eval()` med en säker JSON-parser eller använd `JSON.parse()` med korrekt validering.

### Exempel på säker lösning
```javascript
case 'stringify':
  try {
    const parsed = JSON.parse(input);
    result = JSON.stringify(parsed, null, 2);
  } catch (e) {
    result = "❌ Fel: Ogiltig JSON - " + e.message;
  }
  break;
```

### Prioritet
**HÖG** - Säkerhetsrisk som måste åtgärdas omedelbart

### Relaterade filer
- `tools/converter/utilities.js`
- `tools/converter/index.php` (använder utilities.js)

### Labels
- `bug`
- `security`
- `high-priority`
- `tools`

---
**Analysdatum**: 2025-11-13
**Kategori**: Säkerhet
**Status**: 🔴 Kritiskt

