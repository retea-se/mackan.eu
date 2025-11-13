# 🔧 KODKVALITET: Migrera gamla verktyg till BEM-struktur

## 🔧 Kodkvalitet - Inkonsistent CSS-struktur

### Problem
Flera verktyg använder gamla CSS-klasser (`.container`, `.button`, `.title`) istället för nya BEM-strukturen (`.form`, `.knapp`, `.rubrik`). Detta gör koden inkonsistent och svår att underhålla.

### Verktyg som använder gamla klasser

#### 1. Text-to-Speech (`tools/tts/`)
- **Fil**: `tools/tts/index.php`
- **Problem**: Använder `.title`, `.subtitle` istället för `.rubrik`, `.text--lead`
- **Rad**: 9-14
- **Kod**:
  ```php
  <h1 class="title"><!-- TODO: osäker konvertering: title -->
    <?= $title ?>
  </h1>
  <p class="subtitle"><!-- TODO: osäker konvertering: subtitle"><?= $subtitle ?></p>
  ```

#### 2. Converter (`tools/converter/`)
- **Fil**: `tools/converter/index.php`
- **Problem**: Använder egna klasser (`.converter-container`, `.converter-tabs`) istället för BEM
- **Rad**: 7-38
- **Kod**:
  ```php
  <div class="converter-container">
    <div class="converter-header">
      <h1>🔄 Data Converter</h1>
    </div>
    <div class="converter-tabs">
      <!-- ... -->
    </div>
  </div>
  ```

#### 3. RKA-kalkylator (`tools/rka/`)
- **Fil**: `tools/rka/index.php`
- **Problem**: Använder inline-stilar och gamla strukturer
- **Rad**: 50
- **Kod**:
  ```php
  $class = $gamma<=1.2 ? 'kort--gron' : ($gamma<=1.6 ? 'kort--gul' : 'kort--rod');
  ```

#### 4. Stötta (`tools/stotta/`)
- **Fil**: `tools/stotta/script.js`
- **Problem**: Använder inline-stilar
- **Rad**: 100
- **Kod**:
  ```javascript
  warning.style.marginRight = '5px';
  ```

#### 5. Skyddad delning (`tools/skyddad/`)
- **Fil**: `tools/skyddad/todo.php`
- **Problem**: Använder gamla klasser (`.container`, `.card`, `.title`)
- **Rad**: 10-16
- **Kod**:
  ```php
  <main class="container">
    <h1 class="title">
      <?= $title ?>
    </h1>
    <article class="card readme">
      <!-- ... -->
    </article>
  </main>
  ```

### Lösning
1. **Migrera alla verktyg** till ny BEM-struktur
2. **Använd CSS-klasser** istället för inline-stilar
3. **Följ mallen** i `tools/mall_verktyg.php`
4. **Uppdatera JavaScript** för att använda CSS-klasser istället för inline-stilar

### Exempel på korrekt implementation
```php
<!-- Före (gamla klasser) -->
<h1 class="title"><?= $title ?></h1>
<p class="subtitle"><?= $subtitle ?></p>

<!-- Efter (BEM-struktur) -->
<h1 class="rubrik rubrik--sektion"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h1>
<p class="text--lead"><?= htmlspecialchars($subtitle, ENT_QUOTES, 'UTF-8') ?></p>
```

### Verktyg att uppdatera
1. ✅ `tools/tts/index.php` - Migrera till BEM-struktur
2. ✅ `tools/converter/index.php` - Migrera till BEM-struktur
3. ✅ `tools/rka/index.php` - Ta bort inline-stilar, använd CSS-klasser
4. ✅ `tools/stotta/script.js` - Ta bort inline-stilar, använd CSS-klasser
5. ✅ `tools/skyddad/todo.php` - Migrera till BEM-struktur
6. ✅ `tools/skyddad/index.php` - Migrera till BEM-struktur (om inte redan gjort)
7. ✅ `tools/skyddad/dela.php` - Migrera till BEM-struktur (om inte redan gjort)
8. ✅ `tools/skyddad/visa.php` - Migrera till BEM-struktur (om inte redan gjort)

### Ytterligare förbättringar
- Standardisera alla formulär - Använd samma struktur
- Standardisera alla tabeller - Använd samma struktur
- Standardisera alla knappar - Använd samma struktur
- Ta bort alla inline-stilar - Använd CSS-klasser

### Prioritet
**LÅG** - Kodkvalitetsförbättring som kan göras gradvis

### Relaterade issues
- Skapa gemensam JavaScript-bas för vanliga funktioner

### Labels
- `enhancement`
- `refactoring`
- `low-priority`
- `tools`

---
**Analysdatum**: 2025-11-13
**Kategori**: Kodkvalitet
**Status**: 🟢 Låg prioritet

