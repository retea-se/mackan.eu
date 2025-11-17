# 🚀 Deployment Guide för Mackan.eu

## 📖 Innehållsförteckning

1. [Vad är GitHub Actions?](#vad-är-github-actions)
2. [Hur fungerar det?](#hur-fungerar-det)
3. [Setup Status](#setup-status)
4. [Steg-för-steg Setup](#steg-för-steg-setup)
5. [Testa Deployment](#testa-deployment)
6. [Felsökning](#felsökning)
7. [Alternativ: Manuell Deployment](#alternativ-manuell-deployment)

---

## 🎓 Vad är GitHub Actions?

**GitHub Actions** är ett automationssystem som körs i molnet. Tänk på det som en "robot" som automatiskt gör saker åt dig när du pushar kod till GitHub.

### Enkelt förklarat:

1. **Du gör ändringar** i din kod lokalt
2. **Du pushar** till GitHub (`git push origin main`)
3. **GitHub Actions "ser"** att du pushade
4. **Automatiskt** loggar den in på din server via SSH
5. **Automatiskt** hämtar den den nya koden (`git pull`)
6. **Klart!** Din webbplats är uppdaterad

**Före:** Du måste manuellt logga in på servern och köra `git pull`
**Efter:** Det händer automatiskt när du pushar! 🎉

---

## 🔄 Hur fungerar det?

### Visuellt flöde:

```
┌─────────────────┐
│  Du gör ändring │
│  i din kod      │
└────────┬────────┘
         │
         │ git push origin main
         ▼
┌─────────────────┐
│  GitHub tar     │
│  emot koden     │
└────────┬────────┘
         │
         │ GitHub Actions startar automatiskt
         ▼
┌─────────────────┐
│  GitHub Actions │
│  kör workflow   │
│  (.github/      │
│   workflows/    │
│   deploy.yml)   │
└────────┬────────┘
         │
         │ Använder secrets för att logga in
         ▼
┌─────────────────┐
│  SSH-anslutning │
│  till servern   │
│  (omega.hostup. │
│   se)           │
└────────┬────────┘
         │
         │ cd ~/public_html && git pull
         ▼
┌─────────────────┐
│  Koden är       │
│  uppdaterad!    │
│  ✅             │
└─────────────────┘
```

### Vad händer i detalj:

1. **Workflow-filen** (`.github/workflows/deploy.yml`) säger till GitHub Actions:

   - "När någon pushar till `main` branch, kör detta script"

2. **Scriptet** gör följande:

   - Loggar in på servern (`omega.hostup.se`) via SSH
   - Går till rätt mapp (`~/public_html`)
   - Kör `git pull origin main` för att hämta senaste koden
   - Klart!

3. **Secrets** är säkra variabler som lagras i GitHub:
   - `SSH_HOST` = Var servern finns (`omega.hostup.se`)
   - `SSH_USER` = Användarnamn (`mackaneu`)
   - `SSH_PRIVATE_KEY` = Nyckeln för att logga in (känslig!)
   - `DEPLOY_PATH` = Var på servern koden ska deployas (`~/public_html`)

---

## ✅ Setup Status

### Vad som är klart:

- ✅ **SSH-nyckel skapad** för GitHub Actions
- ✅ **SSH-nyckel lagd till på servern** (kan logga in)
- ✅ **Workflow-fil skapad** (`.github/workflows/deploy.yml`)
- ✅ **Alla 4 secrets lagda i GitHub:**
  - `SSH_HOST` = `omega.hostup.se`
  - `SSH_USER` = `mackaneu`
  - `SSH_PRIVATE_KEY` = (hela SSH-nyckeln)
  - `DEPLOY_PATH` = `~/public_html`

### ⚠️ Vad som behöver kontrolleras:

1. **DEPLOY_PATH måste vara en git repository**
   - Mappen `~/public_html` på servern måste vara en git repo
   - Om den inte är det, kommer deployment att misslyckas
   - Se [Felsökning](#felsökning) för hur du kontrollerar detta

---

## 📝 Steg-för-steg Setup

### Steg 1: Kontrollera att servern har git repository

**Varför?** GitHub Actions kör `git pull` i `~/public_html`, så mappen måste vara en git repository.

**Kontrollera:**

```powershell
# Logga in på servern och kolla
ssh mackaneu@omega.hostup.se "cd ~/public_html && git status"
```

**Om det säger "not a git repository":**

Du behöver antingen:

- **Alternativ A:** Klona repositoryt på servern:

  ```bash
  ssh mackaneu@omega.hostup.se
  cd ~/public_html
  git clone https://github.com/tempdump/mackan-eu.git .
  ```

- **Alternativ B:** Initiera git i mappen:
  ```bash
  ssh mackaneu@omega.hostup.se
  cd ~/public_html
  git init
  git remote add origin https://github.com/tempdump/mackan-eu.git
  git pull origin main
  ```

### Steg 2: Verifiera att secrets är korrekta

**Gå till:** https://github.com/tempdump/mackan-eu/settings/secrets/actions

**Kontrollera att du ser alla 4 secrets:**

- ✅ SSH_HOST
- ✅ SSH_USER
- ✅ SSH_PRIVATE_KEY
- ✅ DEPLOY_PATH

**Om något saknas:** Se [Steg 3](#steg-3-lägg-till-secrets-i-github) nedan.

### Steg 3: Lägg till Secrets i GitHub (om de saknas)

**Varför?** Secrets är säkra variabler som GitHub Actions använder för att logga in på servern. De lagras krypterat och kan inte ses av andra.

**Gå till:** https://github.com/tempdump/mackan-eu/settings/secrets/actions

**Klicka "New repository secret" för varje:**

#### Secret 1: SSH_HOST

- **Name:** `SSH_HOST`
- **Value:** `omega.hostup.se`
- **Vad är det?** Adressen till din server

#### Secret 2: SSH_USER

- **Name:** `SSH_USER`
- **Value:** `mackaneu`
- **Vad är det?** Användarnamnet för att logga in på servern

#### Secret 3: SSH_PRIVATE_KEY

- **Name:** `SSH_PRIVATE_KEY`
- **Value:** (Öppna filen `C:\Users\marcu\.ssh\id_ed25519_github_actions_mackan` och kopiera HELA innehållet)
- **Vad är det?** Den privata SSH-nyckeln som låter GitHub Actions logga in på servern
- **⚠️ VIKTIGT:** Kopiera ALLT, inklusive `-----BEGIN OPENSSH PRIVATE KEY-----` och `-----END OPENSSH PRIVATE KEY-----`

#### Secret 4: DEPLOY_PATH

- **Name:** `DEPLOY_PATH`
- **Value:** `/home/mackaneu/public_html`
- **Vad är det?** Var på servern din webbplats ligger (mappen som ska uppdateras)

### Steg 4: Verifiera Workflow-filen

**Filen ska finnas:** `.github/workflows/deploy.yml`

**Innehåll:** Se filen ovan. Den säger till GitHub Actions:

- "När någon pushar till `main`, logga in på servern och kör `git pull`"

**Om filen inte finns:** Skapa den enligt mallen ovan.

---

## 🧪 Testa Deployment

### Metod 1: Gör en liten ändring och pusha

1. **Gör en liten ändring:**

   ```bash
   echo "# Test deployment $(date)" >> README.md
   ```

2. **Commit och push:**

   ```bash
   git add README.md
   git commit -m "Test: GitHub Actions deployment"
   git push origin main
   ```

3. **Gå till Actions-sidan:**
   https://github.com/tempdump/mackan-eu/actions

4. **Vad ska hända:**

   - Du ska se en ny workflow-körning starta (gul cirkel)
   - Efter ~30 sekunder ska den bli grön (✅) om det fungerade
   - Klicka på körningen för att se loggar

5. **Kontrollera servern:**
   - Vänta ~1 minut
   - Testa din webbplats - ändringen ska synas!

### Metod 2: Kör manuellt från GitHub UI

1. **Gå till:** https://github.com/tempdump/mackan-eu/actions
2. **Klicka på workflow:** "🚀 Deploy to Production"
3. **Klicka "Run workflow"** (höger uppe)
4. **Välj branch:** `main`
5. **Klicka "Run workflow"**
6. **Vänta och se resultatet!**

---

## 🔧 Felsökning

### Viktigt: Använd absolut DEPLOY_PATH

- Sätt `DEPLOY_PATH` i GitHub Secrets till en absolut sökväg, t.ex. `/home/mackaneu/public_html` eller den exakta underkatalogen (t.ex. `/home/mackaneu/public_html/retea/key`).
- Undvik `~` i GitHub Actions; vår workflow expanderar `~`, men absolut sökväg är säkrast.

### Problem: "cd: no such file or directory"

**Orsak:** `DEPLOY_PATH` är felaktig - mappen finns inte på servern.

**Lösning:**

1. Logga in på servern och hitta rätt sökväg:
   ```bash
   ssh mackaneu@omega.hostup.se "pwd && ls -la"
   ```
2. Uppdatera `DEPLOY_PATH` secret i GitHub med rätt sökväg

### Problem: "not a git repository"

**Orsak:** Mappen `~/public_html` är inte en git repository.

**Lösning:**

1. Logga in på servern:
   ```bash
   ssh mackaneu@omega.hostup.se
   ```
2. Gå till mappen:
   ```bash
   cd ~/public_html
   ```
3. Initiera git (om mappen är tom):

   ```bash
   git init
   git remote add origin https://github.com/tempdump/mackan-eu.git
   git pull origin main
   ```

   ELLER klona repositoryt (om mappen är tom):

   ```bash
   cd ~
   rm -rf public_html  # ⚠️ BACKUP FÖRST om det finns filer!
   git clone https://github.com/tempdump/mackan-eu.git public_html
   ```

### Problem: "git pull failed" eller "Permission denied"

**Orsak:** Git remote är inte korrekt konfigurerad eller SSH-nyckel saknar rättigheter.

**Lösning:**

1. Kontrollera git remote:
   ```bash
   ssh mackaneu@omega.hostup.se "cd ~/public_html && git remote -v"
   ```
2. Om remote saknas eller är fel:
   ```bash
   ssh mackaneu@omega.hostup.se "cd ~/public_html && git remote set-url origin https://github.com/tempdump/mackan-eu.git"
   ```

### Problem: "SSH connection failed"

**Orsak:** SSH-nyckel eller credentials är felaktiga.

**Lösning:**

1. Kontrollera att `SSH_PRIVATE_KEY` secret innehåller HELA nyckeln (inklusive BEGIN/END rader)
2. Testa SSH-anslutning manuellt:
   ```powershell
   ssh -i C:\Users\marcu\.ssh\id_ed25519_github_actions_mackan mackaneu@omega.hostup.se
   ```

### Problem: Workflow körs inte alls

**Orsak:** Workflow-filen finns inte i `main` branch eller är felaktig.

**Lösning:**

1. Kontrollera att `.github/workflows/deploy.yml` finns i repositoryt
2. Kontrollera att filen är pushad till `main` branch:
   ```bash
   git branch
   git push origin main
   ```

### Diagnos-workflow

- Kör manuellt från GitHub Actions: "🔎 Diagnose SSH Deploy".
- Validerar SSH, `DEPLOY_PATH` och repo-status utan att göra deployment.

---

## 🔄 Alternativ: Manuell Deployment

Om GitHub Actions inte fungerar eller du föredrar manuell kontroll finns två alternativ:

### Metod 1: SSH-deployment via Git Push

**Status:** ⚠️ **Rekommenderad metod** (SSH-nycklar behöver konfigureras korrekt)

GitHub Actions är för närvarande inaktiverat. Istället används manuell deployment via SSH.

#### Förutsättningar

1. **SSH-nyckel konfigurerad:**
   - Nyckel: `~/.ssh/id_rsa` (standard RSA-nyckel)
   - Server: `mackan_eu@omega.hostup.se`
   - Katalog: `~/public_html`

2. **Git repository på servern:**
   - Remote: `https://github.com/retea-se/mackan.eu.git`
   - Branch: `main`

#### Deploy-process

1. **Gör ändringar lokalt och pusha till GitHub:**

```bash
# Gör dina ändringar
git add .
git commit -m "Din commit-meddelande"
git push origin main
```

2. **Deploya till produktion via SSH:**

```bash
# Windows (PowerShell)
ssh -i ~/.ssh/id_rsa mackan_eu@omega.hostup.se "cd ~/public_html && git pull origin main"

# Om SSH-nyckeln har ett annat namn eller sökväg
ssh -i C:\Users\marcu\.ssh\id_rsa mackan_eu@omega.hostup.se "cd ~/public_html && git pull origin main"
```

3. **Verifiera deployment:**

```bash
curl -I https://mackan.eu/
```

#### Troubleshooting SSH

Om SSH-anslutning timeout:

1. **Kontrollera SSH-nyckel:**
   ```bash
   ls ~/.ssh/id*
   ```

2. **Testa anslutning:**
   ```bash
   ssh -i ~/.ssh/id_rsa -v mackan_eu@omega.hostup.se
   ```

3. **Kontrollera nyckelpermissions:**
   ```powershell
   icacls C:\Users\marcu\.ssh\id_rsa
   ```

4. **Om timeout uppstår:** Kontakta webbhotellet för att verifiera SSH-access

#### Aktuell Git-konfiguration

- **Lokalt repo:** `tempdump/mackan-eu` → uppdaterat till `retea-se/mackan.eu`
- **Server repo:** `retea-se/mackan.eu`
- **GitHub Account:** retea-se
- **PAT:** Lagras säkert (ej i dokumentation)

### Metod 2: GitHub Webhook (utvecklas)

**Fil:** `deploy-webhook.php`

**Status:** 🚧 Under utveckling

Webhook-lösning för automatisk deployment utan GitHub Actions billing.

**Setup:**
1. Ladda upp `deploy-webhook.php` till server root
2. Sätt webhook secret i filen
3. Konfigurera GitHub webhook:
   - URL: `https://mackan.eu/deploy-webhook.php`
   - Secret: Samma som i PHP-filen
   - Event: Push to main

**Fördelar:**
- ✅ Automatisk deployment
- ✅ Ingen GitHub Actions-kostnad
- ✅ Fungerar med privata repos

**Nackdelar:**
- ⚠️ Kräver PHP på servern
- ⚠️ Behöver webhook-konfiguration

### Metod 3: PowerShell-script (Legacy)

**Fil:** `scripts/deploy.ps1` (om det finns)

**Kör:**

```powershell
powershell -ExecutionPolicy Bypass -File scripts/deploy.ps1
```

**Fördelar:**
- ✅ Fungerar direkt
- ✅ Full kontroll
- ✅ Ingen setup krävs

**Nackdelar:**
- ⚠️ Måste komma ihåg att köra manuellt
- ⚠️ Ingen historik

---

## 📋 Checklista: Är allt klart?

Använd denna checklista för att verifiera att allt är konfigurerat:

### ✅ Setup Checklista

- [ ] **SSH-nyckel skapad** (`C:\Users\marcu\.ssh\id_ed25519_github_actions_mackan`)
- [ ] **SSH-nyckel lagd till på servern** (kan logga in)
- [ ] **Workflow-fil finns** (`.github/workflows/deploy.yml`)
- [ ] **Workflow-fil är pushad till `main` branch**
- [ ] **Alla 4 secrets lagda i GitHub:**
  - [ ] `SSH_HOST` = `omega.hostup.se`
  - [ ] `SSH_USER` = `mackaneu`
  - [ ] `SSH_PRIVATE_KEY` = (hela nyckeln)
  - [ ] `DEPLOY_PATH` = `/home/mackaneu/public_html` (absolut sökväg)
- [ ] **`~/public_html` på servern är en git repository**
- [ ] **Git remote är korrekt konfigurerad** (pekar på GitHub)

### 🧪 Test Checklista

- [ ] Gjort en test-ändring och pushat till `main`
- [ ] Gått till Actions-sidan och sett workflow köras
- [ ] Workflow blev grön (✅) utan fel
- [ ] Ändringen syns på webbplatsen

---

## 🎯 Sammanfattning: Hur GitHub Actions Deployment Fungerar

### Det enkla svaret:

**GitHub Actions är en "robot" som automatiskt deployar din kod när du pushar till GitHub.**

### Detaljerat flöde:

1. **Du gör ändringar lokalt** → Redigerar filer på din dator
2. **Du pushar till GitHub** → `git push origin main`
3. **GitHub Actions startar automatiskt** → Ser att du pushade till `main`
4. **Workflow-filen körs** → Läser instruktioner från `.github/workflows/deploy.yml`
5. **Loggar in på servern** → Använder secrets (SSH_HOST, SSH_USER, SSH_PRIVATE_KEY)
6. **Kör git pull** → Hämtar senaste koden i `~/public_html`
7. **Klart!** → Din webbplats är uppdaterad

### Viktiga begrepp:

- **Workflow** = Instruktioner för vad GitHub Actions ska göra
- **Secrets** = Säkra variabler (lösenord, nycklar) som lagras krypterat
- **Trigger** = När workflow ska köras (t.ex. vid push till `main`)
- **Action** = Färdiga verktyg (t.ex. `appleboy/ssh-action` för SSH-anslutning)

### Före vs Efter:

**FÖRE (Manuellt):**

```
1. Gör ändringar
2. git push
3. Logga in på servern manuellt
4. cd ~/public_html
5. git pull
6. Klart
```

**EFTER (Automatiskt):**

```
1. Gör ändringar
2. git push
3. Klart! (GitHub Actions gör resten automatiskt)
```

---

## 🚨 Rollback (Återställning)

Om något går fel och du behöver återställa:

### Med GitHub Actions:

1. **Via GitHub UI:**

   - Gå till: https://github.com/tempdump/mackan-eu/actions
   - Hitta senaste lyckade deployment
   - Klicka "Re-run workflow"

2. **Via Git:**
   ```bash
   git revert HEAD
   git push origin main
   ```

### Manuellt på servern:

```bash
ssh mackaneu@omega.hostup.se
cd ~/public_html
git log  # Se tidigare commits
git reset --hard <commit-hash>  # Återställ till specifik commit
```

---

## ❓ Vanliga Frågor

**Q: Vad händer om deployment misslyckas?**
A: GitHub Actions visar felmeddelanden i loggarna. Gå till Actions-sidan och klicka på den misslyckade körningen för att se detaljer.

**Q: Kan jag deploya från andra branches?**
A: Ja! Ändra `branches: - main` till `branches: - feature/*` i workflow-filen. Eller lägg till flera branches.

**Q: Hur ofta körs deployment?**
A: Varje gång du pushar till `main` branch. Du kan också köra det manuellt från GitHub UI.

**Q: Behöver jag ändra något i min kod?**
A: Nej! Detta är bara deployment-process. Din kod förblir oförändrad.

**Q: Vad kostar GitHub Actions?**
A: Gratis för publika repositories! För privata repos finns det en gratis kvot.

**Q: Kan jag se historik över deployment?**
A: Ja! Gå till Actions-sidan på GitHub - där ser du alla deployment-körningar med loggar.

---

## 📞 Hjälp och Support

Om något inte fungerar:

1. **Kolla Actions-loggarna:**

   - Gå till: https://github.com/tempdump/mackan-eu/actions
   - Klicka på senaste körningen
   - Läs felmeddelandena

2. **Testa SSH-anslutning manuellt:**

   ```powershell
   ssh -i C:\Users\marcu\.ssh\id_ed25519_github_actions_mackan mackaneu@omega.hostup.se
   ```

3. **Kontrollera git repository på servern:**

   ```bash
   ssh mackaneu@omega.hostup.se "cd ~/public_html && git status"
   ```

4. **Se [Felsökning](#felsökning) sektionen** ovan för specifika problem

---

**Skapad:** 2025-01-15
**Senast uppdaterad:** 2025-11-17
**Status:** ⚠️ SSH-deployment aktiv, GitHub Actions inaktiverat

## 📝 Ändringslogg

### 2025-11-17
- ✅ Bytte GitHub-konto från `tempdump/mackan-eu` till `retea-se/mackan.eu`
- ✅ Uppdaterade deployment-instruktioner för SSH-metod
- ✅ Dokumenterade troubleshooting för SSH-anslutning
- ✅ La till webhook-alternativ (under utveckling)
- ⚠️ GitHub Actions temporärt inaktiverat pga SSH-timeout

### 2025-01-15
- ✅ Initial setup av GitHub Actions deployment
- ✅ Skapade workflow-fil och secrets
