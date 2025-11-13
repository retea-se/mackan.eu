# 🛡️ SÄKERHET: Lägg till input-validering för verktyg med POST-data

## 🔒 Säkerhetsproblem - Input-validering

### Problem
Flera verktyg hanterar POST-data utan korrekt validering, vilket kan leda till säkerhetsproblem som SQL-injection, XSS eller data manipulation.

### Verktyg som påverkas

#### 1. RKA-kalkylator (`tools/rka/`)
- **Fil**: `tools/rka/index.php`
- **Problem**: Ingen validering av POST-data (rating, load, days, fuel, profileData)
- **Rad**: 8-33
- **Exempel**:
  ```php
  $rating = $_POST['rating'] ?? 100; // Ingen validering!
  $days = $_POST['days'] ?? ''; // Ingen validering!
  ```

#### 2. RKA Avancerad (`tools/rka/`)
- **Filer**:
  - `tools/rka/a2.php` - Inga valideringar
  - `tools/rka/avancerad.php` - Inga valideringar
  - `tools/rka/provkorning.php` - Inga valideringar

#### 3. Kortlänk (`tools/kortlank/`)
- **Fil**: `tools/kortlank/api/shorten.php`
- **Status**: ✅ Har delvis validering (URL-validering finns)
- **Problem**: Saknar validering av custom_alias, description, password

#### 4. Skyddad delning (`tools/skyddad/`)
- **Filer**:
  - `tools/skyddad/dela-handler.php` - Begränsad validering
  - `tools/skyddad/visa-handler.php` - Begränsad validering
  - `tools/skyddad/skapa.php` - Begränsad validering

### Lösning
1. **Skapa gemensam valideringsfunktion** i `includes/tools-validator.php`
2. **Validera alla POST-parametrar** enligt typ (numeric, string, email, URL, etc.)
3. **Använd filter_var()** för validering
4. **Lägg till whitelist-validering** för enum-värden (t.ex. fuel: DIESEL, HVO100, ECOPAR)

### Exempel på validering
```php
// Skapa valideringsfunktion
function validateInput($value, $type, $options = []) {
  switch ($type) {
    case 'numeric':
      return is_numeric($value) && $value >= ($options['min'] ?? 0);
    case 'string':
      $length = strlen($value);
      return $length >= ($options['min'] ?? 0) &&
             $length <= ($options['max'] ?? 1000);
    case 'enum':
      return in_array($value, $options['allowed'] ?? []);
    // ... fler typer
  }
}

// Användning
$rating = validateInput($_POST['rating'] ?? 100, 'numeric', ['min' => 1, 'max' => 10000])
  ? (float)$_POST['rating']
  : 100;
```

### Verktyg att uppdatera
1. ✅ `tools/rka/index.php` - Lägg till validering för rating, load, days, fuel, profileData
2. ✅ `tools/rka/a2.php` - Lägg till validering för alla POST-parametrar
3. ✅ `tools/rka/avancerad.php` - Lägg till validering för alla POST-parametrar
4. ✅ `tools/rka/provkorning.php` - Lägg till validering för alla POST-parametrar
5. ✅ `tools/kortlank/api/shorten.php` - Förbättra validering av custom_alias, description
6. ✅ `tools/skyddad/dela-handler.php` - Förbättra validering
7. ✅ `tools/skyddad/visa-handler.php` - Förbättra validering
8. ✅ `tools/skyddad/skapa.php` - Förbättra validering

### Prioritet
**HÖG** - Säkerhetsproblem som måste åtgärdas

### Relaterade issues
- CSRF-skydd (kommer i separat issue)

### Labels
- `bug`
- `security`
- `high-priority`
- `tools`

---
**Analysdatum**: 2025-11-13
**Kategori**: Säkerhet
**Status**: 🔴 Hög prioritet

