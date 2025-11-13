# 🔐 GitHub Secrets - Steg för Steg Guide

## Vad är GitHub Secrets?

GitHub Secrets är säkra variabler som lagras krypterat i GitHub. De används av GitHub Actions för att deploya din kod till servern **utan att exponera känslig information** (som SSH-nycklar eller lösenord) i din kod.

## ✅ Checklista - 4 Secrets att lägga till

Du behöver lägga till dessa **4 secrets** i GitHub:

1. ✅ `SSH_HOST` - Serverns adress
2. ✅ `SSH_USER` - Användarnamn för servern
3. ✅ `SSH_PRIVATE_KEY` - Din SSH-nyckel (hela filen)
4. ✅ `DEPLOY_PATH` - Var på servern koden ska deployas

---

## 📝 Steg 1: Öppna Secrets-sidan i GitHub

**Länk:** https://github.com/tempdump/mackan-eu/settings/secrets/actions

1. Gå till länken ovan (eller: Repository → Settings → Secrets and variables → Actions)
2. Du ska se "This repository has no secrets"
3. Klicka på **"New repository secret"** (blå knapp)

---

## 🔑 Steg 2: Lägg till Secret 1 - SSH_HOST

### I formuläret:

1. **Name:** Skriv exakt: `SSH_HOST`
2. **Secret:** Skriv exakt: `omega.hostup.se`
3. Klicka **"Add secret"**

✅ Klart! Nu är SSH_HOST tillagt.

---

## 🔑 Steg 3: Lägg till Secret 2 - SSH_USER

### Klicka "New repository secret" igen:

1. **Name:** Skriv exakt: `SSH_USER`
2. **Secret:** Skriv exakt: `mackaneu`
3. Klicka **"Add secret"**

✅ Klart! Nu är SSH_USER tillagt.

---

## 🔑 Steg 4: Lägg till Secret 3 - SSH_PRIVATE_KEY

### Detta är den viktigaste - din SSH-nyckel:

1. Klicka **"New repository secret"** igen
2. **Name:** Skriv exakt: `SSH_PRIVATE_KEY`
3. **Secret:** Kopiera HELA innehållet nedan (inklusive `-----BEGIN` och `-----END` raderna):

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

4. Klicka **"Add secret"**

⚠️ **VIKTIGT:** Kopiera ALLT, inklusive `-----BEGIN OPENSSH PRIVATE KEY-----` och `-----END OPENSSH PRIVATE KEY-----`

✅ Klart! Nu är SSH_PRIVATE_KEY tillagt.

---

## 🔑 Steg 5: Lägg till Secret 4 - DEPLOY_PATH

### Sista secretet:

1. Klicka **"New repository secret"** igen
2. **Name:** Skriv exakt: `DEPLOY_PATH`
3. **Secret:** Skriv exakt: `~/public_html`
4. Klicka **"Add secret"**

✅ Klart! Nu är DEPLOY_PATH tillagt.

---

## ✅ Steg 6: Verifiera att alla secrets är lagda

Du ska nu se **4 secrets** i listan:

1. ✅ `SSH_HOST`
2. ✅ `SSH_USER`
3. ✅ `SSH_PRIVATE_KEY`
4. ✅ `DEPLOY_PATH`

---

## 🧪 Steg 7: Testa Deployment

När alla secrets är lagda, testa deployment:

### Alternativ 1: Testa med en liten ändring

```bash
# Gör en liten test-ändring
echo "# Test deployment $(date)" >> DEPLOYMENT_STATUS.md
git add DEPLOYMENT_STATUS.md
git commit -m "Test: GitHub Actions deployment"
git push origin main
```

### Alternativ 2: Köra workflow manuellt

1. Gå till: https://github.com/tempdump/mackan-eu/actions
2. Klicka på workflow **"🚀 Deploy to Production"**
3. Klicka **"Run workflow"** (höger upp)
4. Välj branch: `main`
5. Klicka **"Run workflow"**

---

## 📊 Steg 8: Se deployment köras

1. Gå till: https://github.com/tempdump/mackan-eu/actions
2. Du ska se en workflow-körning med status:
   - 🟡 **Yellow** = Körs nu
   - 🟢 **Green** = Lyckades!
   - 🔴 **Red** = Misslyckades (klicka för att se fel)

---

## ❓ Felsökning

### Problem: "Permission denied (publickey)"
- **Orsak:** SSH_PRIVATE_KEY är felaktig eller ofullständig
- **Lösning:** Kontrollera att du kopierade HELA nyckeln inklusive `-----BEGIN` och `-----END`

### Problem: "cd: no such file or directory"
- **Orsak:** DEPLOY_PATH är felaktig
- **Lösning:** Kontrollera rätt sökväg på servern:
  ```bash
  ssh mackaneu@omega.hostup.se "pwd && ls -la"
  ```

### Problem: "git pull failed"
- **Orsak:** Repository finns inte på servern eller fel branch
- **Lösning:** Kontrollera att repository är klonat på servern i rätt mapp

---

## 🎉 Klart!

När alla secrets är lagda och deployment fungerar:

1. ✅ Varje gång du pushar till `main` → Deployment körs automatiskt
2. ✅ Du kan se deployment-historik i Actions-fliken
3. ✅ Din kod deployas automatiskt till `omega.hostup.se`

---

## 📞 Hjälp

Om något inte fungerar:
1. Kolla Actions-loggarna: https://github.com/tempdump/mackan-eu/actions
2. Kolla att alla 4 secrets är lagda korrekt
3. Testa SSH-anslutningen manuellt:
   ```bash
   ssh -i C:\Users\marcu\.ssh\id_ed25519_github_actions_mackan mackaneu@omega.hostup.se
   ```

---

**Skapad:** 2025-01-15
**Status:** Redo att använda! 🚀

