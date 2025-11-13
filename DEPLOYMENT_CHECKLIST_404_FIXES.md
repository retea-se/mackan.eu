# Deployment Checklist - 404-fel åtgärd

## 🔴 Filer som ger 404 men finns lokalt

### Admin-sidor
1. **`/admin/pro-analytics.php`**
   - ✅ Filen finns lokalt: `admin/pro-analytics.php`
   - ❌ Ger 404 på servern
   - **Åtgärd**: Verifiera att filen är deployad på servern

2. **`/admin/security-monitor.php`**
   - ✅ Filen finns lokalt: `admin/security-monitor.php`
   - ❌ Ger 404 på servern
   - **Åtgärd**: Verifiera att filen är deployad på servern

3. **`/admin/geo-country.php`**
   - ⚠️ Ger 400 (Bad Request) istället för 404
   - **Åtgärd**: Kontrollera om filen finns och om den har syntaxfel

### Koordinat-verktyg
4. **`/tools/koordinat/index.php`**
   - ✅ Filen finns lokalt: `tools/koordinat/index.php`
   - ❌ Ger 404 på servern
   - **Notera**: Kommentaren i filen säger `// tools/koordinat/public/index.php` men filen ligger i `tools/koordinat/`
   - **Åtgärd**:
     - Verifiera deployment
     - Kontrollera om det finns en `public/`-mapp på servern som krävs

5. **`/tools/koordinat/impex.php`**
   - ✅ Filen finns lokalt: `tools/koordinat/impex.php`
   - ❌ Ger 404 på servern
   - **Notera**: Kommentaren säger `// tools/koordinat/public/impex.php`
   - **Åtgärd**: Samma som ovan

6. **`/tools/koordinat/impex_map.php`**
   - ✅ Filen finns lokalt: `tools/koordinat/impex_map.php`
   - ❌ Ger 404 på servern
   - **Åtgärd**: Verifiera deployment

### QR-verktyg
7. **`/tools/qr_v3/index.php`**
   - ✅ Filen finns lokalt: `tools/qr_v3/index.php`
   - ❌ Ger 404 på servern
   - **Åtgärd**: Verifiera deployment

## 📋 Verifieringssteg

### 1. Kontrollera filstruktur på servern
```bash
# SSH till servern och kontrollera:
ls -la /path/to/site/admin/pro-analytics.php
ls -la /path/to/site/admin/security-monitor.php
ls -la /path/to/site/tools/koordinat/index.php
ls -la /path/to/site/tools/koordinat/impex.php
ls -la /path/to/site/tools/koordinat/impex_map.php
ls -la /path/to/site/tools/qr_v3/index.php
```

### 2. Kontrollera filrättigheter
```bash
# Filerna bör ha läsrättigheter för webbservern
chmod 644 admin/pro-analytics.php
chmod 644 admin/security-monitor.php
chmod 644 tools/koordinat/*.php
chmod 644 tools/qr_v3/index.php
```

### 3. Kontrollera .htaccess-blockeringar
- Ingen .htaccess i `tools/koordinat/` mappen
- Root `.htaccess` verkar inte blockera dessa filer
- Kontrollera om det finns server-specifika regler

### 4. Kontrollera PHP-syntaxfel
```bash
php -l admin/pro-analytics.php
php -l admin/security-monitor.php
php -l tools/koordinat/index.php
php -l tools/koordinat/impex.php
php -l tools/koordinat/impex_map.php
php -l tools/qr_v3/index.php
```

### 5. Kontrollera include-sökvägar
Alla filer använder korrekta relativsökvägar:
- `tools/koordinat/index.php` → `include '../../includes/layout-start.php'` ✅
- `tools/koordinat/impex.php` → `include '../../includes/layout-start.php'` ✅
- `tools/koordinat/impex_map.php` → `include '../../includes/layout-start.php'` ✅

## 🔍 Ytterligare undersökningar

### Möjliga orsaker till 404:
1. **Filer inte deployade** - Mest troligt
2. **Felaktig filstruktur på servern** - Kanske finns `public/`-mapp?
3. **Server-konfiguration** - Kanske blockeras av webbserver-inställningar
4. **Routing-regler** - Kanske finns URL-rewriting som kräver annan struktur

### Rekommenderade åtgärder:
1. ✅ Verifiera att alla filer är deployade via FTP/SSH
2. ✅ Kontrollera serverloggar för dessa URL:er
3. ✅ Testa direkt via SSH om filerna är läsbara
4. ✅ Kontrollera om det finns en `public/`-mapp som krävs
5. ✅ Uppdatera kommentarer i filerna om de flyttats från `public/`

## 📝 Fil-lista för deployment

Filer som behöver verifieras på servern:
```
admin/pro-analytics.php
admin/security-monitor.php
admin/geo-country.php (kontrollera 400-fel)
tools/koordinat/index.php
tools/koordinat/impex.php
tools/koordinat/impex_map.php
tools/qr_v3/index.php
```

## 🛠️ Snabbfix - Uppdatera kommentarer

Om filerna faktiskt ligger i rätt mapp (inte i `public/`), bör kommentarerna uppdateras:

```php
// Ändra från:
// tools/koordinat/public/index.php

// Till:
// tools/koordinat/index.php
```


