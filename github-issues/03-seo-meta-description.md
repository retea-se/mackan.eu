# 🔍 SEO: Lägg till metaDescription för verktyg som saknar det

## 📊 SEO-problem - Saknade metaDescription

### Problem
Flera verktyg saknar `metaDescription` vilket försämrar SEO och gör att sökmotorer inte kan visa korrekt beskrivning i sökresultat.

### Verktyg som saknar metaDescription

#### 1. Addy (`tools/addy/`)
- **Fil**: `tools/addy/index.php`
- **Status**: ❌ Saknar `$metaDescription`
- **Nuvarande**: Endast `$title = 'AnonAddy Address Generator';`

#### 2. Text-to-Speech (`tools/tts/`)
- **Fil**: `tools/tts/index.php`
- **Status**: ❌ Saknar `$metaDescription`
- **Nuvarande**: Endast `$title = 'Text-to-Speech';`
- **Extra**: Använder också gamla CSS-klasser (`.title`, `.subtitle`)

#### 3. RKA-kalkylator (`tools/rka/`)
- **Fil**: `tools/rka/index.php`
- **Status**: ❌ Saknar `$metaDescription`
- **Nuvarande**: Endast beräkningar, ingen metaDescription

### Verktyg som HAR metaDescription (referens)
- ✅ `tools/converter/index.php` - Har `$metaDescription`
- ✅ `tools/koordinat/index.php` - Har `$metaDescription`
- ✅ `tools/qr_v3/index.php` - Har `$metaDescription`
- ✅ `tools/bolagsverket/index.php` - Har `$metaDescription`
- ✅ `tools/pts/index.php` - Har `$metaDescription`

### Lösning
Lägg till `$metaDescription` för alla verktyg som saknar det. Följ mönstret från verktyg som redan har det.

### Exempel på korrekt implementation
```php
<?php
// tools/addy/index.php
$title = 'AnonAddy Address Generator';
$metaDescription = 'Skapa vidarebefordringsadresser för AnonAddy på sekunder. Generera säkra e-postadresser för att skydda din riktiga e-postadress från spam.';
$keywords = 'anonaddy, e-post, vidarebefordring, spam-skydd, säker e-post';
$canonical = 'https://mackan.eu/tools/addy/';
include '../../includes/layout-start.php';
?>
```

### Verktyg att uppdatera
1. ✅ `tools/addy/index.php` - Lägg till `$metaDescription`, `$keywords`, `$canonical`
2. ✅ `tools/tts/index.php` - Lägg till `$metaDescription`, `$keywords`, `$canonical`
3. ✅ `tools/rka/index.php` - Lägg till `$metaDescription`, `$keywords`, `$canonical`

### Ytterligare förbättringar
- Lägg till `$keywords` för alla verktyg (många saknar det)
- Lägg till `$canonical` för alla verktyg (många saknar det)
- Standardisera meta-taggar - Skapa en mall i `tools/mall_verktyg.php`

### Prioritet
**MEDEL** - SEO-förbättring som bör göras

### Relaterade issues
- Lägg till strukturerad data (JSON-LD) för alla verktyg

### Labels
- `enhancement`
- `seo`
- `medium-priority`
- `tools`

---
**Analysdatum**: 2025-11-13
**Kategori**: SEO
**Status**: 🟡 Medel prioritet

