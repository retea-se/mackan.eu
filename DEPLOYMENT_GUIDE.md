# 🚀 Deployment Guide - mackan.eu

**Senast uppdaterad:** 2025-11-17
**Status:** ✅ SSH-deployment fungerar

---

## 📝 Snabbstart - Deploya till Produktion

### Steg 1: Gör dina ändringar
```bash
# Redigera filer lokalt
# ...

# Stage och commit
git add .
git commit -m "Din commit-meddelande"
```

### Steg 2: Pusha till GitHub
```bash
git push origin main
```

### Steg 3: Deploya till produktion
```bash
"C:\Windows\System32\OpenSSH\ssh.exe" -i "C:\Users\marcu\.ssh\id_rsa_pollify" mackaneu@omega.hostup.se "cd ~/public_html && git pull origin main"
```

### Steg 4: Verifiera
```bash
curl -I https://mackan.eu/
```

**Klart!** 🎉

---

## 🔧 Detaljerad Setup-information

### SSH-konfiguration

**VIKTIGT:** Använd **ALLTID** Windows OpenSSH, ALDRIG Git for Windows SSH!

#### SSH-nyckel
- **Plats:** `C:\Users\marcu\.ssh\id_rsa_pollify`
- **Server:** `omega.hostup.se`
- **Användare:** `mackaneu`
- **Katalog:** `~/public_html`

#### Git Repository
- **GitHub:** `https://github.com/retea-se/mackan.eu`
- **Branch:** `main`
- **Remote:** `origin`

---

## 📋 Vanliga Kommandon

### Deploy till produktion
```bash
"C:\Windows\System32\OpenSSH\ssh.exe" -i "C:\Users\marcu\.ssh\id_rsa_pollify" mackaneu@omega.hostup.se "cd ~/public_html && git pull origin main"
```

### Logga in på servern
```bash
"C:\Windows\System32\OpenSSH\ssh.exe" -i "C:\Users\marcu\.ssh\id_rsa_pollify" mackaneu@omega.hostup.se
```

### Kolla git status på servern
```bash
"C:\Windows\System32\OpenSSH\ssh.exe" -i "C:\Users\marcu\.ssh\id_rsa_pollify" mackaneu@omega.hostup.se "cd ~/public_html && git status"
```

### Visa senaste commits
```bash
"C:\Windows\System32\OpenSSH\ssh.exe" -i "C:\Users\marcu\.ssh\id_rsa_pollify" mackaneu@omega.hostup.se "cd ~/public_html && git log --oneline -5"
```

### Kolla error log
```bash
"C:\Windows\System32\OpenSSH\ssh.exe" -i "C:\Users\marcu\.ssh\id_rsa_pollify" mackaneu@omega.hostup.se "tail -50 ~/public_html/error_log"
```

### Kopiera fil till servern (SCP)
```bash
"C:\Windows\System32\OpenSSH\scp.exe" -i "C:\Users\marcu\.ssh\id_rsa_pollify" "local-file.php" mackaneu@omega.hostup.se:~/public_html/
```

---

## 🔍 Felsökning

### Problem: "Permission denied"
**Orsak:** Fel SSH-nyckel eller inte korrekt nyckel

**Lösning:**
1. Kontrollera att du använder rätt nyckel: `id_rsa_pollify`
2. Kontrollera att sökvägen är korrekt
3. Använd Windows OpenSSH (inte Git for Windows)

### Problem: "Connection timeout"
**Orsak:** Nätverksproblem eller fel server

**Lösning:**
1. Kontrollera internetanslutning
2. Verifiera server-adress: `omega.hostup.se`
3. Kontakta webbhotell om problemet kvarstår

### Problem: "git pull failed"
**Orsak:** Merge-konflikter eller lokala ändringar på servern

**Lösning:**
```bash
# Logga in och kolla status
"C:\Windows\System32\OpenSSH\ssh.exe" -i "C:\Users\marcu\.ssh\id_rsa_pollify" mackaneu@omega.hostup.se

# På servern:
cd ~/public_html
git status
git stash  # Spara lokala ändringar tillfälligt
git pull origin main
git stash pop  # Återställ lokala ändringar (om nödvändigt)
```

### Problem: "Not a git repository"
**Orsak:** Katalogen är inte initierad som git repo

**Lösning:**
```bash
# Logga in på servern
"C:\Windows\System32\OpenSSH\ssh.exe" -i "C:\Users\marcu\.ssh\id_rsa_pollify" mackaneu@omega.hostup.se

# Gå till katalogen och klona
cd ~
rm -rf public_html  # OBS: Backup först!
git clone https://github.com/retea-se/mackan.eu.git public_html
```

---

## 🚨 Rollback (Återställning)

Om något går fel och du behöver återställa till en tidigare version:

### Metod 1: Via Git lokalt
```bash
# Hitta commit att återställa till
git log --oneline

# Återställ (ersätt COMMIT_HASH)
git revert COMMIT_HASH
git push origin main

# Deploya
"C:\Windows\System32\OpenSSH\ssh.exe" -i "C:\Users\marcu\.ssh\id_rsa_pollify" mackaneu@omega.hostup.se "cd ~/public_html && git pull origin main"
```

### Metod 2: Direkt på servern
```bash
# Logga in
"C:\Windows\System32\OpenSSH\ssh.exe" -i "C:\Users\marcu\.ssh\id_rsa_pollify" mackaneu@omega.hostup.se

# På servern:
cd ~/public_html
git log --oneline -10  # Se tidigare commits
git reset --hard COMMIT_HASH  # Återställ till specifik commit
```

⚠️ **VARNING:** `git reset --hard` raderar alla lokala ändringar!

---

## 📊 Deployment-flöde

```
┌─────────────────────────┐
│ 1. Gör ändringar lokalt │
└───────────┬─────────────┘
            │
            │ git add, commit
            ▼
┌─────────────────────────┐
│ 2. Pusha till GitHub     │
│    git push origin main  │
└───────────┬─────────────┘
            │
            │
            ▼
┌─────────────────────────┐
│ 3. Manuell deployment   │
│    via SSH-kommando     │
└───────────┬─────────────┘
            │
            │ Windows OpenSSH
            ▼
┌─────────────────────────┐
│ 4. Server kör git pull  │
│    ~/public_html        │
└───────────┬─────────────┘
            │
            │
            ▼
┌─────────────────────────┐
│ 5. ✅ Deployment klar!   │
│    https://mackan.eu/   │
└─────────────────────────┘
```

---

## ⚙️ Alternativa Deployment-metoder

### GitHub Webhook (Framtida)
**Fil:** `deploy-webhook.php`
**Status:** 🚧 Inte aktiverad än

För att aktivera automatisk deployment via webhook:

1. **Aktivera webhook-filen på servern**
   ```bash
   "C:\Windows\System32\OpenSSH\ssh.exe" -i "C:\Users\marcu\.ssh\id_rsa_pollify" mackaneu@omega.hostup.se "cd ~/public_html && chmod +x deploy-webhook.php"
   ```

2. **Sätt webhook secret i filen**
   - Redigera `deploy-webhook.php` och byt ut `REPLACE_ME_WITH_RANDOM_SECRET`

3. **Konfigurera GitHub webhook**
   - Gå till: https://github.com/retea-se/mackan.eu/settings/hooks
   - Add webhook
   - URL: `https://mackan.eu/deploy-webhook.php`
   - Content type: `application/json`
   - Secret: Samma som i PHP-filen
   - Event: "Just the push event"

**Fördelar:**
- ✅ Automatisk deployment vid push
- ✅ Ingen manuell SSH-kommando
- ✅ Ingen GitHub Actions-kostnad

---

## 📝 Viktiga Noteringar

### ✅ Vad som fungerar nu
- SSH-deployment via Windows OpenSSH
- Git pull från retea-se/mackan.eu
- Manuell deployment

### ⚠️ Vad som INTE fungerar
- GitHub Actions (inaktiverat)
- Git for Windows SSH (använd ALDRIG)
- Webhook (inte konfigurerad än)

### 🔐 Säkerhet
- SSH-nyckel: `id_rsa_pollify` är privat och får ALDRIG delas
- GitHub PAT: Lagras inte i kod eller dokumentation
- Webhook secret: Ska vara slumpmässig och hemlig

---

## 🎯 Checklista innan deployment

- [ ] Testat ändringarna lokalt
- [ ] Commitmeddelande är beskrivande
- [ ] Inga känsliga data (lösenord, nycklar) i koden
- [ ] Pushat till GitHub (`git push origin main`)
- [ ] Kört SSH-kommando för deployment
- [ ] Verifierat att sidan fungerar (`curl -I https://mackan.eu/`)

---

## 📞 Support

Om något inte fungerar:

1. **Kolla git status**
   ```bash
   git status
   git log --oneline -5
   ```

2. **Verifiera SSH-anslutning**
   ```bash
   "C:\Windows\System32\OpenSSH\ssh.exe" -i "C:\Users\marcu\.ssh\id_rsa_pollify" mackaneu@omega.hostup.se "echo 'SSH works!'"
   ```

3. **Kolla server logs**
   ```bash
   "C:\Windows\System32\OpenSSH\ssh.exe" -i "C:\Users\marcu\.ssh\id_rsa_pollify" mackaneu@omega.hostup.se "tail -50 ~/public_html/error_log"
   ```

---

## 📚 Dokumentation

- **GitHub Repo:** https://github.com/retea-se/mackan.eu
- **Live Site:** https://mackan.eu/
- **Server:** omega.hostup.se

---

**Skapad:** 2025-01-15
**Senast uppdaterad:** 2025-11-17
**Version:** 2.0
**Status:** ✅ Fungerande SSH-deployment
