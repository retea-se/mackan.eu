# Lighthouse Test Results - Efter Optimeringar

**Test Date:** 2025-11-17
**URL:** https://mackan.eu/
**Efter:** Color contrast fix, cache optimization, defer scripts

---

## 📊 Overall Scores

| Category | Before | After | Change |
|----------|--------|-------|--------|
| **Performance** | 84/100 | 82/100 | -2 📉 |
| **Accessibility** | 94/100 | 87/100 | -7 📉 |
| **Best Practices** | 82/100 | 82/100 | = |
| **SEO** | 100/100 | 100/100 | ✅ |

---

## ⚡ Core Web Vitals Comparison

| Metric | Before | After | Change |
|--------|--------|-------|--------|
| **FCP** | 2.8s (0.56) | 3.0s (0.50) | +0.2s 📉 |
| **LCP** | 3.0s (0.79) | 3.6s (0.61) | +0.6s 📉 |
| **TBT** | 280ms (0.81) | 160ms (0.93) | -120ms 🟢 |
| **CLS** | 0 (1.00) | 0 (1.00) | ✅ Perfect |
| **SI** | 3.3s (0.91) | 3.1s (0.93) | -0.2s 🟢 |

---

## ✅ Förbättringar (FIXED)

### 1. Color Contrast ✅
- **Before:** FAIL ❌
- **After:** PASS ✅
- **Fix:** View toggle buttons nu använder `color: var(--landing-text-primary)` med `opacity: 0.7`

### 2. Total Blocking Time ✅
- **Before:** 280ms (0.81)
- **After:** 160ms (0.93)
- **Improvement:** -120ms (-43%)
- **Fix:** Defer på Tippy.js scripts

### 3. Speed Index 🟢
- **Before:** 3.3s (0.91)
- **After:** 3.1s (0.93)
- **Improvement:** -0.2s
- **Fix:** Optimerade script-laddning

---

## ⚠️ Återstående Problem

### 1. Render-Blocking Resources ❌
**Score:** 0 (unchanged)

**Blockande resurser:**
1. `/css/blocks/tabell.css` - 1107 bytes, 150ms
2. `/css/blocks/rubrik.css` - 966 bytes, 150ms
3. `/includes/tools-common.js` - 3957 bytes, 150ms
4. **FontAwesome CDN** - 112KB, **1201ms** 🔴

**Problem:** FontAwesome laddas fortfarande synkront trots `media="print"` trick

**Lösning:**
- Överväg att self-hosta FontAwesome
- Eller inline kritiska ikoner i CSS
- Eller använd subset av ikoner

### 2. Text Compression ❌
**Score:** 0 (unchanged)

**Okomprimerade filer:**
1. **FontAwesome CDN** - 112KB (potential savings: 81KB)
2. **Tippy.js CDN** - 18KB (potential savings: 11KB)

**Problem:** Extern CDN-resurser har inte Gzip/Brotli compression aktiverat

**Lösning:**
- Self-hosta dessa bibliotek (då kan vi komprimera dem)
- Eller acceptera detta (CDN-problem, inte vårt)

### 3. Accessibility - Button Name ❌
**Score:** FAIL

**Problem:** Vissa knappar saknar accessible name

**Behöver undersökas:** Vilka knappar?

---

## 📉 Försämringar (Unexpected)

### FCP +0.2s
**Possible causes:**
- Nätverksvariationer (test-till-test skillnad)
- Caching inte aktivt än (första besök efter deployment)

### LCP +0.6s
**Possible causes:**
- Async-laddning av FontAwesome fördröjer ikoner
- Större bilder eller resurser i LCP-elementet

### Accessibility -7 poäng
**Cause:** Button-name issue upptäcktes (kan ha funnits innan men inte detekterades)

---

## 🎯 Nästa Steg för Förbättring

### Högt Prio
1. **Fix button-name accessibility issue**
   - Identifiera vilka knappar som saknar label
   - Lägg till `aria-label` eller synlig text

2. **Self-hosta FontAwesome**
   - Ladda ner endast ikoner vi använder
   - Komprimera och servera från egen server
   - Potential vinst: -1200ms render-blocking

3. **Inline kritisk CSS**
   - Inline `tabell.css` och `rubrik.css` i `<head>`
   - Reducerar render-blocking med 300ms

### Medel Prio
4. **Preload LCP-element**
   - Identifiera vad som är LCP (hero-bild?)
   - Lägg till `<link rel="preload">` för denna resurs

5. **Optimera bilder**
   - Konvertera till WebP
   - Använd `srcset` för responsiva bilder
   - Lazy-load off-screen bilder

### Låg Prio
6. **Self-hosta Tippy.js**
   - Samma som FontAwesome
   - Mindre kritiskt (redan defer)

---

## 💡 Insights

### Vad fungerade ✅
- **Color contrast fix** - Perfekt!
- **TBT reduction** - Defer scripts hjälpte mycket
- **CLS behåller 0** - Layout är stabil

### Vad fungerade inte ❌
- **FontAwesome async trick** - Laddas fortfarande synkront
- **CDN compression** - Kan inte kontrollera extern CDN
- **Cache optimization** - Kanske inte aktiv än (TTL)

### Varför vissa saker blev sämre
- **FCP/LCP försämring** kan bero på:
  - Test-variation (network jitter)
  - First-visit efter deployment (ingen cache)
  - Async FontAwesome gör att ikoner laddas senare

---

## 🔬 Teknisk Analys

### FontAwesome Problem
```html
<!-- Nuvarande (fungerar ej som avsett) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/.../all.min.css" media="print" onload="this.media='all'">
```

**Problem:** Lighthouse ser fortfarande detta som render-blocking.

**Bättre lösning:**
```html
<!-- Option 1: Preload + async -->
<link rel="preload" href="/fonts/fontawesome.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="/fonts/fontawesome.css"></noscript>

<!-- Option 2: Self-host med subset -->
<!-- Endast inkludera ikoner vi faktiskt använder -->
```

---

## 📝 Sammanfattning

### Lyckat ✅
- Color contrast fixad
- TBT reducerad med 43%
- SEO behåller 100/100
- CLS perfekt (0)

### Behöver åtgärdas ⚠️
- FontAwesome render-blocking (1200ms!)
- Button accessibility
- FCP/LCP försämring (behöver undersökas)

### Rekommendation
**Next action:** Self-hosta FontAwesome med endast nödvändiga ikoner. Detta kommer att:
- ✅ Eliminera 1200ms render-blocking
- ✅ Möjliggöra Gzip/Brotli compression
- ✅ Reducera bundle size (endast ikoner vi använder)
- ✅ Förbättra både FCP och LCP

**Estimated performance gain:** +8-12 poäng (82 → 90-94)

---

**Slutsats:** Vi har fixat color contrast (utmärkt!) och reducerat TBT, men FontAwesome CDN är nu den största flaskhalsen. Self-hosting är nästa logiska steg för ytterligare förbättringar.
