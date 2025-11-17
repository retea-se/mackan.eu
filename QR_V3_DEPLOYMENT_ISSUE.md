# QR v3 Deployment Issue - Server-Side Problem

**Problem:** `tools/qr_v3/script.js` returnerar 404 trots att filen finns i git och på GitHub.

**Datum:** 2025-11-17
**Status:** 🔴 OLÖST - Kräver server-side åtgärd

---

## Symptom

### På production:
```bash
$ curl -I https://mackan.eu/tools/qr_v3/script.js
HTTP/1.1 404 Not Found
Content-Type: text/html  # ← Returnerar HTML istället för JavaScript
```

### På GitHub:
```bash
$ curl -I https://raw.githubusercontent.com/retea-se/mackan.eu/main/tools/qr_v3/script.js
HTTP/1.1 200 OK
Content-Length: 16321  # ← Filen finns och är 16.3KB
```

### Användarupplevelse:
```javascript
// Browser console:
Failed to load resource: the server responded with a status of 404 ()
Refused to execute script from 'https://mackan.eu/tools/qr_v3/script.js'
because its MIME type ('text/html') is not executable
```

**Resultat:** QR v3 knappar gör ingenting eftersom JavaScript inte laddas.

---

## Troubleshooting Genomfört

### ✅ Steg 1: Verifiera filen finns lokalt
```bash
$ ls -la tools/qr_v3/
-rw-r--r-- 1 marcu 197609  5650 Nov 13 22:02 index.php
-rw-r--r-- 1 marcu 197609 16678 Nov 13 18:29 script.js
```
**Status:** ✅ Båda filerna finns

### ✅ Steg 2: Verifiera filen är i git
```bash
$ git ls-files tools/qr_v3/
tools/qr_v3/index.php
tools/qr_v3/script.js

$ git ls-tree HEAD tools/qr_v3/
100644 blob a3a3f79... tools/qr_v3/index.php
100644 blob 78c6159... tools/qr_v3/script.js
```
**Status:** ✅ Båda filerna trackade i git

### ✅ Steg 3: Verifiera .gitignore inte blockerar
```bash
$ cat .gitignore | grep qr_v3
# (ingen match)
```
**Status:** ✅ Inte i .gitignore

### ✅ Steg 4: Verifiera filen finns på GitHub
```bash
$ curl -I https://raw.githubusercontent.com/retea-se/mackan.eu/main/tools/qr_v3/script.js
HTTP/1.1 200 OK
Content-Length: 16321
```
**Status:** ✅ Filen finns på GitHub (16.3KB)

### ✅ Steg 5: Verifiera index.php når production
```bash
$ curl -I https://mackan.eu/tools/qr_v3/
HTTP/1.1 200 OK
Content-Type: text/html; charset=UTF-8
```
**Status:** ✅ index.php fungerar

### ❌ Steg 6: Verifiera script.js når production
```bash
$ curl -I https://mackan.eu/tools/qr_v3/script.js
HTTP/1.1 404 Not Found
Content-Type: text/html
```
**Status:** ❌ script.js returnerar 404

### ✅ Steg 7: Committa och pusha (3 gånger)
```bash
# Commit 1: 9be4a1b - "Fix: QR v3 komplett saknade filer"
# Commit 2: 0f0198e - "Deploy: Lägg till koordinat/ och timer/"
# Commit 3: e921851 - "Deploy: Force re-deploy av QR v3"

$ git push
To https://github.com/retea-se/mackan.eu.git
   0f0198e..e921851  main -> main
```
**Status:** ✅ Pushade 3 gånger, väntade 30s-90s mellan varje

### ❌ Steg 8: Verifiera efter varje push
Efter varje push (och väntetid):
```bash
$ curl -I https://mackan.eu/tools/qr_v3/script.js
HTTP/1.1 404 Not Found  # Fortfarande 404
```
**Status:** ❌ Ingen förändring

---

## Mönster Identifierat

### Filer som når production: ✅
- `tools/qr_v3/index.php` - ✅ 200 OK
- `tools/koordinat/*.php` - ✅ 200 OK
- `tools/koordinat/*.js` - ✅ (ej testat men koordinat fungerar)
- `tools/tfngen/` - ✅ 200 OK
- `tools/passwordgenerator/` - ✅ 200 OK

### Filer som INTE når production: ❌
- `tools/qr_v3/script.js` - ❌ 404
- `tools/timer/` (hela mappen) - ❌ 404

### Hypotes:
**Nya JavaScript-filer och mappar deploygas inte.**

Möjliga orsaker:
1. Deployment script filtrerar bort `.js` filer i vissa mappar
2. Deployment script skippar nya mappar (som `timer/`)
3. File permissions sätts fel för JavaScript-filer
4. `.htaccess` blockerar vissa file patterns

---

## Jämförelse: Fungerande vs Trasiga

### Koordinat (Fungerar):
- Committades i samma push som timer
- Innehåller både `.php` och `.js` filer
- **Fungerar på production**

### Timer (Fungerar inte):
- Committades i samma push som koordinat
- Innehåller `.html` och `.js` filer
- **404 på hela mappen**

### QR v3 (Fungerar delvis):
- index.php: ✅ Fungerar
- script.js: ❌ 404

**Pattern:** PHP-filer deploygas, JavaScript/HTML-filer deploygas inte (ibland).

---

## Möjliga Orsaker

### 1. Deployment Script Filter
```bash
# Exempel på problematiskt rsync/deployment:
rsync --exclude '*.js' --exclude '*.html' ...
# eller
rsync --include '*.php' --exclude '*' ...
```

### 2. .htaccess Regel
```apache
# Blockera JavaScript från vissa mappar?
<FilesMatch "\.(js)$">
  Require all denied
</FilesMatch>
```

### 3. File Permissions
```bash
# PHP-filer får 644, JavaScript får 600?
chmod 644 *.php
chmod 600 *.js  # ← Inte läsbar för webserver
```

### 4. CDN/CloudFlare Cache
```
CloudFlare cachat 404:an från första försöket
Cache time: 24h
Purge needed: Manuell cache clear
```

### 5. GitHub Webhook Problem
```
Webhook triggar inte för vissa file types
Eller webhook failar tyst
```

---

## Vad Fungerar Inte

### ❌ Fler commits
Testade 3 olika commits - ingen förändring.

### ❌ Vänta på propagering
Väntade totalt 90+ sekunder efter varje push - ingen förändring.

### ❌ Dummy file trigger
Skapade `.deploy-trigger` för att trigga deployment - ingen förändring.

---

## Vad Som Behövs

### 🔧 Server-Side Åtgärder Krävs:

1. **SSH Access till production server:**
   ```bash
   ssh user@mackan.eu
   cd /path/to/production/tools/qr_v3/
   ls -la script.js
   cat script.js | head -5
   ```

2. **Deployment Logs:**
   ```bash
   tail -100 /var/log/deployment.log
   # eller
   journalctl -u deployment-webhook -n 100
   ```

3. **Check .htaccess:**
   ```bash
   cat /path/to/production/.htaccess | grep -i javascript
   cat /path/to/production/tools/.htaccess | grep -i js
   ```

4. **File Permissions:**
   ```bash
   ls -la /path/to/production/tools/qr_v3/
   # Förväntat: -rw-r--r-- (644)
   # Om 600 eller 640: Fix med chmod 644
   ```

5. **Deployment Script:**
   ```bash
   cat /path/to/deployment/script.sh
   # Leta efter rsync filters, file type restrictions
   ```

6. **CloudFlare Cache Purge:**
   ```bash
   # Via CloudFlare dashboard eller API:
   curl -X POST "https://api.cloudflare.com/client/v4/zones/{zone_id}/purge_cache" \
     -H "Authorization: Bearer {api_token}" \
     -d '{"files":["https://mackan.eu/tools/qr_v3/script.js"]}'
   ```

---

## Alternativa Lösningar (Om Deployment Inte Fixas)

### Workaround 1: Inline JavaScript
Flytta `script.js` innehåll till `<script>` tag i `index.php`:
```php
<!-- index.php -->
<script>
<?php include 'script.js'; ?>
</script>
```

### Workaround 2: CDN Fallback
```html
<script src="script.js"></script>
<script>
  // Om script.js failar, ladda från GitHub
  if (typeof initQRGenerator === 'undefined') {
    const script = document.createElement('script');
    script.src = 'https://raw.githubusercontent.com/retea-se/mackan.eu/main/tools/qr_v3/script.js';
    document.head.appendChild(script);
  }
</script>
```

### Workaround 3: Rename Extension
```bash
# Testa om .php fungerar men .js inte:
mv script.js script.js.php
# I index.php:
<script src="script.js.php"></script>
```

---

## Timeline

**2025-11-17 10:00** - Användare rapporterar QR v3 knappar fungerar inte
**2025-11-17 10:15** - Upptäckt: script.js returnerar 404
**2025-11-17 10:20** - Upptäckt: `tools/qr_v3/` var i .gitignore
**2025-11-17 10:25** - Borttaget från .gitignore, adderat filer
**2025-11-17 10:30** - Commit 1: "Fix: QR v3 komplett saknade filer"
**2025-11-17 10:35** - Testat: Fortfarande 404
**2025-11-17 11:00** - Commit 2: Koordinat + Timer deployment
**2025-11-17 11:05** - Testat: Fortfarande 404
**2025-11-17 11:45** - Commit 3: Force re-deploy trigger
**2025-11-17 11:50** - Testat: Fortfarande 404
**2025-11-17 12:00** - Slutsats: Server-side problem, kräver manuell åtgärd

**Total tid investerad:** 2 timmar
**Status:** Olöst, väntar på server access

---

## Git Commits

```bash
9be4a1b - Fix: QR v3 komplett saknade filer - 404 fix
0f0198e - Deploy: Lägg till koordinat/ och timer/ verktyg
e921851 - Deploy: Force re-deploy av QR v3 - Trigger deployment hook
```

**Alla pushade till production, ingen har löst problemet.**

---

## Nästa Steg

### Omedelbart:
1. **Kontakta server admin** för SSH access
2. **Check deployment logs** för error messages
3. **Verify file exists på server:** `/path/to/production/tools/qr_v3/script.js`

### Om filen saknas på server:
- Deployment pipeline skippar filen
- Fix deployment script
- Re-deploy

### Om filen finns men 404:
- Check .htaccess rules
- Check file permissions (ska vara 644)
- Check server configuration

### Om filen finns och har rätt permissions:
- CloudFlare cache issue
- Manual cache purge required
- Vänta 24h för auto-purge

---

## Workaround Implementation

Medan vi väntar på server-side fix, här är en snabb workaround:

```php
<!-- tools/qr_v3/index.php -->
<!-- Längst ner före </body> -->

<script>
// Inline fallback om script.js failar
(function() {
  setTimeout(function() {
    if (typeof document.querySelector('[data-type]') !== 'undefined' &&
        !document.querySelector('[data-type]').onclick) {
      // Script.js laddades inte, använd inline version
      <?php
      if (file_exists(__DIR__ . '/script.js')) {
        echo file_get_contents(__DIR__ . '/script.js');
      }
      ?>
    }
  }, 500);
})();
</script>
```

Detta läser `script.js` från disk och inline:ar det direkt i HTML om det inte laddas externt.

---

## Slutsats

**QR v3 deployment issue är ett server-side problem som inte kan lösas via git commits.**

**Kräver:**
- Server SSH access
- Deployment logs
- Manual file verification
- Möjligen cache purge

**Workaround möjlig:** Inline JavaScript i `index.php`

**Status:** 🔴 Blockerad, väntar på server-side åtgärd
