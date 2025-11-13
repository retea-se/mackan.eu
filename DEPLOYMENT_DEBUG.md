# 🔍 Deployment Debug - Problem och Lösningar

## ✅ Vad som fungerar:

1. **SSH-anslutning från din dator:** ✅ Fungerar perfekt
2. **Manuell deployment:** ✅ Fungerar perfekt
3. **Git push till GitHub:** ✅ Fungerar perfekt
4. **Workflow-fil:** ✅ Konfigurerad med debug och timeout

## ❌ Vad som inte fungerar:

**GitHub Actions kan inte ansluta till servern**

- Run 16 misslyckades fortfarande
- Servern är fortfarande på commit `19d059f` (inte den senaste `f526b58`)
- Loggarna laddas inte korrekt via GitHub UI

## 🔍 Möjliga orsaker:

### 1. SSH_PRIVATE_KEY secret är felaktig

**Problem:** Nyckeln i GitHub secrets matchar inte den på servern.

**Lösning:**
1. Gå till: https://github.com/tempdump/mackan-eu/settings/secrets/actions
2. Klicka på `SSH_PRIVATE_KEY` secret
3. Verifiera att den innehåller **HELA** nyckeln (från filen `C:\Users\marcu\.ssh\id_ed25519_github_actions_mackan`)
4. Kontrollera att den:
   - Börjar med: `-----BEGIN OPENSSH PRIVATE KEY-----`
   - Slutar med: `-----END OPENSSH PRIVATE KEY-----`
   - Har **inga extra radbrytningar** i början eller slutet
   - Har **inga extra mellanslag** i början eller slutet

### 2. SSH-nyckel på servern matchar inte

**Problem:** Den publika nyckeln på servern matchar inte den privata nyckeln i GitHub secrets.

**Lösning:**
Kontrollera att den publika nyckeln (`id_ed25519_github_actions_mackan.pub`) är installerad på servern:

```bash
ssh -i C:\Users\marcu\.ssh\id_ed25519_github_actions_mackan mackaneu@omega.hostup.se "cat ~/.ssh/authorized_keys | grep -A 2 'github'"
```

### 3. GitHub Actions IP-adresser blockeras

**Problem:** Serverns firewall blockerar anslutningar från GitHub Actions IP-adresser.

**Lösning:**
GitHub Actions använder dynamiska IP-adresser. Kontakta din hosting-provider för att:
- Ta bort IP-begränsningar för SSH-port 22
- Eller whitelista GitHub Actions IP-ranges: https://api.github.com/meta

### 4. SSH-port eller host är felaktig

**Problem:** `SSH_HOST` eller SSH-port är felaktig i GitHub secrets.

**Lösning:**
Verifiera secrets:
- `SSH_HOST` = `omega.hostup.se` (utan `http://` eller `https://`)
- SSH-port = `22` (standard)

## 🧪 Testa manuellt:

Kör detta för att testa SSH-anslutningen som GitHub Actions skulle göra:

```bash
ssh -i C:\Users\marcu\.ssh\id_ed25519_github_actions_mackan -p 22 mackaneu@omega.hostup.se "echo 'SSH connection successful'"
```

Om detta fungerar men GitHub Actions inte gör det, är problemet troligen med hur SSH-nyckeln är kopierad till GitHub secrets.

## 📊 Nästa steg:

1. **Verifiera SSH_PRIVATE_KEY secret** - Kontrollera att den är korrekt kopierad
2. **Kontrollera SSH-nyckel på servern** - Verifiera att den publika nyckeln finns
3. **Kontakta hosting-provider** - Om IP-blockering är problemet
4. **Testa med alternativ deployment-metod** - T.ex. webhook eller annat verktyg

---

**Senaste körning:** Run 16 (commit `f526b58`) - Misslyckades
**Server commit:** `19d059f` (inte uppdaterad)



