# FTP Deployment - Setup Guide

## Enkel FTP-deployment utan SSH-krångel

Jag har bytt till FTP-deployment som är mycket enklare och inte kräver SSH-nycklar eller billing på GitHub.

---

## GitHub Secrets - Lägg in dessa 3 värden

Gå till: **https://github.com/retea-se/mackan-eu/settings/secrets/actions**

### 1. FTP_HOST
- **Name:** `FTP_HOST`
- **Value:** `omega.hostup.se` (eller din FTP-server hostname)

### 2. FTP_USER
- **Name:** `FTP_USER`
- **Value:** `mackaneu` (ditt FTP-användarnamn)

### 3. FTP_PASSWORD
- **Name:** `FTP_PASSWORD`
- **Value:** Ditt FTP-lösenord (hitta i din hosting control panel)

---

## Hur hittar jag FTP-uppgifterna?

### HostUp Hosting
1. Logga in på din hosting control panel
2. Leta efter **FTP Accounts** eller **FTP Manager**
3. Använd samma uppgifter som du använder för FTP-klient (FileZilla etc.)

**Vanliga FTP-servrar:**
- `omega.hostup.se`
- `ftp.mackan.eu`
- `ftp.yourdomain.com`

---

## Testa deployment

### Steg 1: Testa FTP-anslutning
1. Gå till: https://github.com/retea-se/mackan-eu/actions
2. Välj workflow: **"🔎 Test FTP Connection"**
3. Klicka **"Run workflow"** → välj branch **main**
4. Vänta på resultat

### Steg 2: Deploy till production
Om testet lyckas:
1. Gå till: https://github.com/retea-se/mackan-eu/actions
2. Välj workflow: **"🚀 Deploy to Production"**
3. Klicka **"Run workflow"** → välj branch **main**

---

## Automatisk deployment

Deployment sker nu **automatiskt** varje gång du pushar till `main`-branchen.

För att deploya manuellt → kör workflow enligt **Steg 2** ovan.

---

## Vad som deployeras

✅ Alla PHP-filer
✅ CSS/JS-filer
✅ Images och assets
✅ Config-filer

❌ **Exkluderas:**
- `.git` katalogen
- `node_modules`
- Test-filer
- GitHub workflow-filer
- Markdown-dokumentation

---

## Felsökning

### FTP-anslutning misslyckas
- Kontrollera att `FTP_HOST` är korrekt
- Verifiera användarnamn och lösenord
- Testa med FTP-klient (FileZilla) först

### Filerna hamnar fel
- Ändra `server-dir` i `.github/workflows/deploy.yml`
- Standard är `/public_html/`
- Vanliga alternativ: `/`, `/www/`, `/httpdocs/`

### Behöver du SSH istället?
Kontakta mig så hjälper jag dig sätta upp SSH-deployment igen när billing-problemet är löst.

---

## Support

**GitHub Actions körs gratis** för publika repos (ingen billing behövs för FTP-deployment).

Om något inte fungerar, kolla workflow-loggar:
https://github.com/retea-se/mackan-eu/actions
