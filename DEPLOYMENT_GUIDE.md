# 🚀 Deployment Guide för Mackan.eu

## Nuvarande Situation
- **Server**: omega.hostup.se (via SSH)
- **Repository**: GitHub (tempdump/mackan-eu)
- **Branch**: `main` (produktion)
- **Tidigare metod**: SFTP (manuell filöverföring)
- **Nuvarande metod**: SSH Git Pull (via `scripts/deploy.ps1`)

---

## 📋 Rekommenderade Deployment-Alternativ

### 🥇 **Alternativ 1: GitHub Actions (CI/CD) - REKOMMENDERAT**

**Fördelar:**
- ✅ Automatisk deployment vid push till `main`
- ✅ Gratis för publika repos
- ✅ Historik och loggar i GitHub
- ✅ Kan köra tester innan deployment
- ✅ Rollback via GitHub UI
- ✅ Ingen lokal installation krävs

**Nackdelar:**
- ⚠️ Kräver SSH-nyckel på GitHub (säkert)
- ⚠️ Första setup tar ~10 minuter

**Hur det fungerar:**
1. Du pushar till `main` på GitHub
2. GitHub Actions kör automatiskt
3. Script loggar in via SSH och kör `git pull`
4. Klart! 🎉

---

### 🥈 **Alternativ 2: SSH Git Pull (Enkel & Snabb) - DU HAR REDAN DETTA!**

**Fördelar:**
- ✅ Mycket enkelt
- ✅ Fungerar direkt
- ✅ Full kontroll
- ✅ Inga extra verktyg
- ✅ **Du har redan `scripts/deploy.ps1`!**

**Nackdelar:**
- ⚠️ Manuellt (måste köra kommandot)
- ⚠️ Ingen automatisk deployment
- ⚠️ Ingen historik

**Hur det fungerar:**
```powershell
# Använd ditt befintliga script:
powershell -ExecutionPolicy Bypass -File scripts/deploy.ps1

# Eller direkt SSH:
"C:\Windows\System32\OpenSSH\ssh.exe" mackaneu@omega.hostup.se "cd ~/public_html && git pull origin main"
```

**⚠️ OBS:** Justera `$remotePath` i `scripts/deploy.ps1` till rätt sökväg för mackan.eu!

---

### 🥉 **Alternativ 3: Webhook-baserad (Avancerat)**

**Fördelar:**
- ✅ Automatisk deployment
- ✅ Kontrolleras av din server

**Nackdelar:**
- ⚠️ Kräver PHP-script på servern
- ⚠️ Mer komplex setup
- ⚠️ Säkerhetsöverväganden

---

## 🎯 **MIN REKOMMENDATION: GitHub Actions**

För ditt projekt passar **GitHub Actions** bäst eftersom:
1. Du redan använder GitHub
2. Du vill ha automatisk deployment
3. Du vill ha historik och kontroll
4. Det är gratis och professionellt
5. **Workflow-filen är redan skapad!** (`.github/workflows/deploy.yml`)

**Alternativ:** Om du föredrar manuell kontroll, använd ditt befintliga `scripts/deploy.ps1` script.

---

## 📝 Steg-för-steg: Setup GitHub Actions

**⚠️ VIKTIGT:** Du behöver veta var din webbplats ligger på servern!
- Är det `~/public_html`?
- Är det `~/public_html/mackan.eu`?
- Eller något annat?

Kontrollera genom att logga in:
```bash
"C:\Windows\System32\OpenSSH\ssh.exe" mackaneu@omega.hostup.se "pwd && ls -la"
```

### Steg 1: Skapa SSH-nyckel för GitHub Actions

**✅ KLART!** SSH-nyckeln har skapats:
- **Fil**: `C:\Users\marcu\.ssh\id_ed25519_github_actions_mackan`
- **Publik nyckel**: `C:\Users\marcu\.ssh\id_ed25519_github_actions_mackan.pub`

### Steg 2: Lägg till SSH-nyckel på servern

**✅ KLART!** SSH-nyckeln har lagts till på servern.

Den publika nyckeln har lagts till i `~/.ssh/authorized_keys` på `omega.hostup.se`.

### Steg 3: Lägg till secrets i GitHub

**⚠️ DU MÅSTE GÖRA DETTA MANUELLT:**

1. Gå till: https://github.com/tempdump/mackan-eu/settings/secrets/actions
2. Klicka "New repository secret" för varje secret nedan:

   **Secret 1:**
   - **Name**: `SSH_HOST`
   - **Value**: `omega.hostup.se`

   **Secret 2:**
   - **Name**: `SSH_USER`
   - **Value**: `mackaneu`

   **Secret 3:**
   - **Name**: `SSH_PRIVATE_KEY`
   - **Value**: (Öppna filen `C:\Users\marcu\.ssh\id_ed25519_github_actions_mackan` och kopiera HELA innehållet, inklusive `-----BEGIN OPENSSH PRIVATE KEY-----` och `-----END OPENSSH PRIVATE KEY-----`)

   **Secret 4:**
   - **Name**: `DEPLOY_PATH`
   - **Value**: `~/public_html` (eller rätt sökväg - kontrollera med kommandot nedan)

**Kontrollera DEPLOY_PATH:**
```powershell
"C:\Windows\System32\OpenSSH\ssh.exe" -i "$env:USERPROFILE\.ssh\id_rsa_pollify" mackaneu@omega.hostup.se "pwd && ls -la"
```

### Steg 4: Skapa GitHub Actions Workflow

**✅ Filen är redan skapad:** `.github/workflows/deploy.yml`

Filen är redo att användas! Den kommer automatiskt deploya när du pushar till `main`.

### Steg 5: Testa!

1. Gör en liten ändring (t.ex. lägg till en kommentar i en fil)
2. Commit och push till `main`:
   ```bash
   git add .
   git commit -m "Test: Deployment test"
   git push origin main
   ```
3. Gå till: https://github.com/tempdump/mackan-eu/actions
4. Se deployment köras automatiskt! 🎉
5. Vänta ~30 sekunder och testa din webbplats

**Eller testa manuellt:**
```powershell
# Kör ditt befintliga script:
powershell -ExecutionPolicy Bypass -File scripts/deploy.ps1
```

---

## 🔄 **Alternativ: Enkel SSH Git Pull (Om du föredrar manuell)**

### Skapa ett lokalt script: `deploy.ps1`

```powershell
# deploy.ps1
$sshKey = "C:\Users\marcu\.ssh\id_rsa_pollify"
$server = "mackaneu@omega.hostup.se"
$deployPath = "~/public_html"  # Justera detta!

Write-Host "🚀 Deployar till produktion..." -ForegroundColor Green

# Kör git pull på servern
& "C:\Windows\System32\OpenSSH\ssh.exe" -i $sshKey $server "cd $deployPath && git pull origin main"

if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Deployment lyckades!" -ForegroundColor Green
} else {
    Write-Host "❌ Deployment misslyckades!" -ForegroundColor Red
}
```

**Användning:**
```powershell
.\deploy.ps1
```

---

## 🛡️ **Säkerhetsöverväganden**

### För GitHub Actions:
- ✅ Använd **secrets** för känslig data (aldrig hårdkoda!)
- ✅ Begränsa SSH-nyckelns rättigheter (endast git pull)
- ✅ Överväg att använda **deploy keys** istället för full SSH-åtkomst

### För SSH Git Pull:
- ✅ Använd SSH-nycklar (inte lösenord)
- ✅ Begränsa SSH-nyckelns rättigheter
- ✅ Överväg att använda `git pull --ff-only` för säkerhet

---

## 📊 **Jämförelse**

| Funktion | GitHub Actions | SSH Git Pull | Webhook |
|----------|---------------|--------------|---------|
| Automatisk | ✅ | ❌ | ✅ |
| Enkel setup | ⚠️ | ✅ | ⚠️ |
| Historik | ✅ | ❌ | ⚠️ |
| Rollback | ✅ | ⚠️ | ⚠️ |
| Kostnad | Gratis | Gratis | Gratis |
| Säkerhet | ✅ | ✅ | ⚠️ |

---

## 🎓 **Rekommendation för Ditt Projekt**

### **Alternativ A: GitHub Actions (Automatisk) - REKOMMENDERAT**

**Starta med: GitHub Actions**

Varför?
1. Du får automatisk deployment (sparar tid)
2. Du får historik (ser vad som deployades när)
3. Du kan enkelt rollbacka (via GitHub)
4. Det är professionellt och skalbart
5. Du lär dig moderna DevOps-praktiker
6. **Workflow-filen är redan skapad!**

**Setup-tid:** ~10 minuter (en gång)

### **Alternativ B: PowerShell Script (Manuell) - DU HAR REDAN DETTA**

**Om GitHub Actions känns för komplext:**
- Använd ditt befintliga `scripts/deploy.ps1`
- Justera `$remotePath` till rätt sökväg
- Kör: `powershell -ExecutionPolicy Bypass -File scripts/deploy.ps1`

**Fördelar:**
- ✅ Fungerar direkt
- ✅ Full kontroll
- ✅ Ingen setup krävs

**Nackdelar:**
- ⚠️ Måste komma ihåg att köra manuellt
- ⚠️ Ingen historik

### **Min Slutgiltiga Rekommendation:**

**Börja med GitHub Actions** - det är bara 10 minuters setup och ger dig mycket mer värde. Om det inte fungerar, fallback till ditt PowerShell-script.

---

## 🚨 **Rollback-Process**

Om något går fel:

### Med GitHub Actions:
```bash
# Gå till GitHub → Actions → Välj tidigare deployment → Re-run
# ELLER
git revert HEAD
git push origin main
```

### Med SSH Git Pull:
```bash
# Logga in på servern och återställ:
ssh mackaneu@omega.hostup.se "cd ~/public_html && git reset --hard rollback-point"
```

---

## 📚 **Nästa Steg**

1. **Välj metod**: GitHub Actions eller SSH Git Pull?
2. **Följ setup-guiden** ovan
3. **Testa** med en liten ändring
4. **Dokumentera** din process

---

## ❓ **Vanliga Frågor**

**Q: Vad händer om deployment misslyckas?**
A: GitHub Actions visar felmeddelanden. SSH Git Pull visar fel i terminalen.

**Q: Kan jag deploya från feature-branches?**
A: Ja! Ändra `branches: - main` till `branches: - feature/*` i workflow.

**Q: Hur gör jag rollback?**
A: Använd git tag `rollback-point` som vi skapade tidigare, eller revert commit.

**Q: Behöver jag ändra något i koden?**
A: Nej! Detta är bara deployment-process. Koden förblir oförändrad.

---

## 📞 **Support**

Om du fastnar:
1. Kolla GitHub Actions logs (om du använder Actions)
2. Testa SSH-anslutning manuellt först
3. Kontrollera att git-repo finns på servern
4. Verifiera sökvägar (`DEPLOY_PATH`)

---

**Skapad:** 2025-01-15
**Senast uppdaterad:** 2025-01-15

