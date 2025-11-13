# 🔐 SSH-nycklar Setup - Komplett Guide

## ✅ Vad som redan fungerar:

1. **SSH-nyckel till servern finns:** ✅ Installerad på `omega.hostup.se`
2. **Manuell SSH-anslutning fungerar:** ✅ Du kan logga in på servern
3. **Manuell deployment fungerar:** ✅ Git pull fungerar perfekt
4. **Git push till GitHub fungerar:** ✅ Du pushar kod utan problem

## ⚠️ Vad som inte fungerar:

**GitHub Actions kan inte ansluta till servern**

## 🔍 Problemanalys:

Det finns två olika SSH-nycklar:

### 1. SSH-nyckel för DIN DATOR → GITHUB
- **Används för:** Att pusha kod till GitHub från din dator
- **Status:** ✅ Fungerar (du pushar redan kod)
- **Behöver inte fixas**

### 2. SSH-nyckel för GITHUB ACTIONS → SERVERN
- **Används för:** Deployment från GitHub Actions till servern
- **Status:** ❌ Fungerar inte
- **Detta är problemet!**

## 🎯 Lösning:

GitHub Actions behöver SSH-nyckeln i **secrets**. Den privata nyckeln (`id_ed25519_github_actions_mackan`) måste kopieras korrekt till GitHub secrets.

### Steg 1: Kopiera SSH-nyckeln exakt

Den privata nyckeln som ska kopieras till GitHub secrets:

```
-----BEGIN OPENSSH PRIVATE KEY-----
b3BlbnNzaC1rZXktdjEAAAAACmFlczI1Ni1jdHIAAAAGYmNyeXB0AAAAGAAAABAHX7AJOB
oXDaw1U5tQE17gAAAAGAAAAAEAAAAzAAAAC3NzaC1lZDI1NTE5AAAAIDd3XzbME5XTJX62
GQhnqsipAMVU8rO8REgtkHY8XfriAAAAoOwDyNQbE2DxEUFWK8J85UghjLzT4jUO40M9ZT
INEXJ/1hm5a8GRePAmTUQt5asAJgj3mB/GBAqHtlRRHvpMsTGm1cxXT5VcbOs6ztvTjD4S
H2IWnOZ/wEZySLH54onPFV9h5avwHWI4eXRm1WbCy22mXuwKvwZ7+c6psYJ9XxGAnE26LK
DKNDaUXlFVFSOmD/XAkUZyMr3xGxwJrZbvV3I=
-----END OPENSSH PRIVATE KEY-----
```

### Steg 2: Verifiera i GitHub Secrets

1. Gå till: https://github.com/tempdump/mackan-eu/settings/secrets/actions
2. Klicka på `SSH_PRIVATE_KEY`
3. Verifiera att den innehåller:
   - **Exakt samma nyckel** som ovan
   - Inga extra radbrytningar
   - Börjar med `-----BEGIN OPENSSH PRIVATE KEY-----`
   - Slutar med `-----END OPENSSH PRIVATE KEY-----`

### Steg 3: Möjliga problem

Om deployment fortfarande misslyckas efter att ha uppdaterat secretet, kan det bero på:

1. **GitHub Actions IP-adresser blockeras**
   - Serverns firewall blockerar GitHub Actions
   - Kontakta hosting-providern för att öppna för GitHub IP-adresser

2. **SSH-nyckeln har extra tecken**
   - Kontrollera att det inte finns extra mellanslag eller radbrytningar

3. **SSH-nyckeln är fel format**
   - Den måste vara i OpenSSH format (som den är)

## 🔧 Alternativ lösning:

Om problemet kvarstår kan vi testa att:
1. Skapa en ny SSH-nyckel specifikt för GitHub Actions
2. Installera den på servern
3. Lägga till den i GitHub secrets

---

**Viktigaste:** GitHub Actions behöver `SSH_PRIVATE_KEY` secret för att ansluta till servern. Denna nyckel måste matcha den publika nyckeln som finns på servern.



