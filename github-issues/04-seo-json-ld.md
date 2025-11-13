# 🔍 SEO: Lägg till strukturerad data (JSON-LD) för alla verktyg

## 📊 SEO-problem - Saknad strukturerad data

### Problem
De flesta verktyg saknar strukturerad data (JSON-LD) vilket förhindrar rich snippets i sökmotorer och försämrar SEO.

### Verktyg som HAR strukturerad data (referens)
- ✅ `tools/qr_v3/index.php` - Har JSON-LD (WebApplication)
- ✅ `tools/koordinat/index.php` - Har JSON-LD (WebApplication)
- ✅ `tools/index.php` - Har omfattande JSON-LD (ItemList, FAQ)

### Verktyg som SAKNAR strukturerad data

#### 1. Addy (`tools/addy/`)
- **Fil**: `tools/addy/index.php`
- **Status**: ❌ Saknar JSON-LD

#### 2. Aptus (`tools/aptus/`)
- **Fil**: `tools/aptus/index.php`
- **Status**: ❌ Saknar JSON-LD

#### 3. Bolagsverket (`tools/bolagsverket/`)
- **Fil**: `tools/bolagsverket/index.php`
- **Status**: ❌ Saknar JSON-LD (trots att den har metaDescription)

#### 4. CSS till JSON (`tools/css2json/`)
- **Fil**: `tools/css2json/index.php`
- **Status**: ❌ Saknar JSON-LD

#### 5. CSV till JSON (`tools/csv2json/`)
- **Fil**: `tools/csv2json/index.php`
- **Status**: ❌ Saknar JSON-LD

#### 6. Converter (`tools/converter/`)
- **Fil**: `tools/converter/index.php`
- **Status**: ❌ Saknar JSON-LD

#### 7. Kortlänk (`tools/kortlank/`)
- **Fil**: `tools/kortlank/skapa-lank.php`
- **Status**: ❌ Saknar JSON-LD

#### 8. Lösenordsgenerator (`tools/passwordgenerator/`)
- **Fil**: `tools/passwordgenerator/index.php`
- **Status**: ❌ Saknar JSON-LD

#### 9. PTS Diarium (`tools/pts/`)
- **Fil**: `tools/pts/index.php`
- **Status**: ❌ Saknar JSON-LD (trots att den har metaDescription)

#### 10. QR-kodgenerator v2 (`tools/qr_v2/`)
- **Fil**: `tools/qr_v2/index.php`
- **Status**: ❌ Saknar JSON-LD

#### 11. RKA-kalkylator (`tools/rka/`)
- **Fil**: `tools/rka/index.php`
- **Status**: ❌ Saknar JSON-LD
- **Notera**: `tools/rka/a2.php` har JSON-LD, men `index.php` saknar det

#### 12. Skyddad delning (`tools/skyddad/`)
- **Fil**: `tools/skyddad/index.php`
- **Status**: ❌ Saknar JSON-LD

#### 13. Stötta (`tools/stotta/`)
- **Fil**: `tools/stotta/index.php`
- **Status**: ❌ Saknar JSON-LD

#### 14. Telefonnummergenerator (`tools/tfngen/`)
- **Fil**: `tools/tfngen/index.php`
- **Status**: ❌ Saknar JSON-LD

#### 15. Testdata (`tools/testdata/`)
- **Fil**: `tools/testdata/index.php`
- **Status**: ❌ Saknar JSON-LD

#### 16. Test-ID (`tools/testid/`)
- **Fil**: `tools/testid/index.php`
- **Status**: ❌ Saknar JSON-LD

#### 17. Text-to-Speech (`tools/tts/`)
- **Fil**: `tools/tts/index.php`
- **Status**: ❌ Saknar JSON-LD

### Lösning
Lägg till JSON-LD strukturerad data för alla verktyg. Använd mönstret från `tools/qr_v3/index.php` eller `tools/koordinat/index.php`.

### Exempel på korrekt implementation
```php
<!-- Strukturerad data för sökmotorer -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebApplication",
  "name": "AnonAddy Address Generator",
  "description": "<?= htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8') ?>",
  "url": "https://mackan.eu/tools/addy/",
  "applicationCategory": "UtilityApplication",
  "operatingSystem": "Web Browser",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "SEK"
  },
  "featureList": [
    "Generera AnonAddy-adresser",
    "Säkra e-postadresser",
    "Spam-skydd"
  ],
  "author": {
    "@type": "Organization",
    "name": "Mackan.eu"
  }
}
</script>
```

### Verktyg att uppdatera
1. ✅ `tools/addy/index.php`
2. ✅ `tools/aptus/index.php`
3. ✅ `tools/bolagsverket/index.php`
4. ✅ `tools/css2json/index.php`
5. ✅ `tools/csv2json/index.php`
6. ✅ `tools/converter/index.php`
7. ✅ `tools/kortlank/skapa-lank.php`
8. ✅ `tools/passwordgenerator/index.php`
9. ✅ `tools/pts/index.php`
10. ✅ `tools/qr_v2/index.php`
11. ✅ `tools/rka/index.php`
12. ✅ `tools/skyddad/index.php`
13. ✅ `tools/stotta/index.php`
14. ✅ `tools/tfngen/index.php`
15. ✅ `tools/testdata/index.php`
16. ✅ `tools/testid/index.php`
17. ✅ `tools/tts/index.php`

### Ytterligare förbättringar
- Lägg till FAQ-schema för komplexa verktyg (t.ex. RKA, koordinat)
- Lägg till HowTo-schema för verktyg med steg-för-steg-instruktioner
- Lägg till BreadcrumbList för bättre navigering

### Prioritet
**MEDEL** - SEO-förbättring som bör göras

### Relaterade issues
- Lägg till metaDescription för verktyg som saknar det

### Labels
- `enhancement`
- `seo`
- `medium-priority`
- `tools`

---
**Analysdatum**: 2025-11-13
**Kategori**: SEO
**Status**: 🟡 Medel prioritet

