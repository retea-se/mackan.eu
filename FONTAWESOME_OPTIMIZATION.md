# FontAwesome Optimization - mackan.eu

**Datum:** 2025-11-17
**Problem:** FontAwesome CDN render-blocking (1200ms)
**Lösning:** Preload + async loading

---

## Problem

Lighthouse-test visade att FontAwesome var den största performance-flaskhalsen:

```
Render-blocking: 1200ms
Size: 112KB (compressed 81KB)
Impact: Blockerar First Contentful Paint (FCP) och Largest Contentful Paint (LCP)
```

---

## Lösning 1: Preload + Async (Implementerad)

### Tidigare kod (blocking):
```html
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
```

### Ny kod (non-blocking):
```html
<!-- FontAwesome - async loading med fallback -->
<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"></noscript>
```

### Hur det fungerar:
1. **`rel="preload"`** - Börjar ladda CSS i bakgrunden med hög prioritet
2. **`as="style"`** - Specificerar att det är en stylesheet
3. **`onload="this.onload=null;this.rel='stylesheet'"`** - När laddad, konvertera till stylesheet
4. **`<noscript>`** - Fallback för användare utan JavaScript

### Fördelar:
- ✅ Eliminerar render-blocking
- ✅ Behåller CDN-fördelar (global cache, uppdateringar)
- ✅ Ingen extra hosting-kostnad
- ✅ Enkel implementation

### Nackdelar:
- ⚠️ Ikoner kan flasha (FOIT - Flash of Invisible Text)
- ⚠️ Fortfarande 112KB nedladdning

### Förväntad förbättring:
- **Render-blocking:** 1200ms → 0ms ✅
- **FCP:** Förbättring med ~0.5-1.0s
- **LCP:** Förbättring med ~0.5-1.0s
- **Performance score:** +8-12 poäng

---

## Alternativ Lösning: Self-hosting med Subset

### För framtida optimering:

Vi använder endast 34 ikoner av FontAwesome's 2000+. Self-hosting med subset skulle reducera storleken dramatiskt.

### Icons i användning:
```
fa-address-book, fa-arrows-rotate, fa-circle-info, fa-clock,
fa-compass, fa-diagram-project, fa-envelope, fa-file-csv,
fa-file-lines, fa-gears, fa-grip, fa-house, fa-id-badge,
fa-id-card, fa-image, fa-industry, fa-key, fa-link, fa-list,
fa-map, fa-map-location-dot, fa-moon, fa-phone, fa-qrcode,
fa-right-left, fa-rocket, fa-shield-halved, fa-star, fa-sun,
fa-toolbox, fa-volume-high, fa-wand-magic-sparkles
```

### Steg för subset:
1. Använd [FontAwesome Subsetter](https://github.com/omacranger/fontawesome-subset) eller IcoMoon
2. Generera custom font med endast dessa 34 ikoner
3. Host lokalt i `/fonts/fontawesome-subset.woff2`
4. Inkludera inline CSS för critical icons

### Förväntad storlek:
- **Nuvarande:** 112KB (full FontAwesome)
- **Med subset:** ~8-12KB (endast 34 ikoner) 📉 90% reduction

### Implementationsexempel:
```html
<!-- Critical icons inline (för snabb LCP) -->
<style>
  @font-face {
    font-family: 'FontAwesome-Subset';
    src: url('/fonts/fa-subset.woff2') format('woff2');
    font-display: swap;
  }
  .fas, .fa-solid { font-family: 'FontAwesome-Subset'; }
  /* Include icon mappings for critical icons */
</style>

<!-- Rest kan laddas async -->
<link rel="preload" href="/fonts/fa-subset.woff2" as="font" type="font/woff2" crossorigin>
```

---

## Accessibility Fix

### Button Labels
Samtidigt fixade vi accessibility för view toggle-knappar:

**Tidigare:**
```html
<button onclick="setView(this, 'grid')">
  <i class="fas fa-grip"></i>
</button>
```

**Nu:**
```html
<button onclick="setView(this, 'grid')" aria-label="Visa som rutnät" title="Visa som rutnät">
  <i class="fas fa-grip" aria-hidden="true"></i>
</button>
```

### Förbättringar:
- ✅ **aria-label:** Screenreaders kan annonsera knappens syfte
- ✅ **title:** Tooltip för mouse-användare
- ✅ **aria-hidden="true"** på ikoner: Ikoner är dekorativa, inte content

---

## Resultat

### Innan:
- Performance: 82/100
- Accessibility: 87/100
- Render-blocking: 1200ms FontAwesome
- Button-name: FAIL

### Efter (förväntad):
- Performance: **90-94/100** 🎯
- Accessibility: **94-100/100** ✅
- Render-blocking: **~0ms**
- Button-name: **PASS**

---

## Deployment

### Filer ändrade:
1. `includes/layout-start.php` - FontAwesome preload implementation
2. `index.php` - Accessibility labels på buttons

### Deploy:
```bash
git add includes/layout-start.php index.php
git commit -m "Perf: FontAwesome async + button accessibility"
git push origin main
"C:\Windows\System32\OpenSSH\ssh.exe" -i "C:\Users\marcu\.ssh\id_rsa_pollify" mackaneu@omega.hostup.se "cd ~/public_html && git pull origin main"
```

---

## Monitoring

### Verifiera efter deployment:
1. **Lighthouse test:**
   ```bash
   npx @lhci/cli@latest collect --url="https://mackan.eu/"
   ```

2. **Accessibility validator:**
   - Testa view toggle-knappar med screenreader
   - Verifiera aria-labels fungerar

3. **Visual regression:**
   - Kontrollera att ikoner laddas korrekt
   - Kolla för FOIT (flash of invisible text)

---

## Nästa Steg

Om ytterligare optimering behövs:

1. **Implementera FontAwesome subset** (90% size reduction)
2. **Inline critical icons** (LCP optimization)
3. **Service Worker** (offline caching)
4. **Preload critical fonts** (eliminate FOIT)

---

## Referenser

- [FontAwesome async loading guide](https://github.com/FortAwesome/Font-Awesome/issues/11360)
- [Filament Group's loadCSS](https://github.com/filamentgroup/loadCSS)
- [FontAwesome Subsetter](https://github.com/omacranger/fontawesome-subset)
- [WCAG 2.1 Button labeling](https://www.w3.org/WAI/WCAG21/Understanding/label-in-name.html)

---

**Sammanfattning:** Vi har eliminerat FontAwesome render-blocking och fixat button accessibility. Nästa steg för ytterligare optimering är subset-implementation.
