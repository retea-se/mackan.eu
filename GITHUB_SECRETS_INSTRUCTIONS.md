# 🔐 GitHub Secrets - Instruktioner

## SSH Private Key

**Fil:** `C:\Users\marcu\.ssh\id_ed25519_github_actions_mackan`

**Innehåll (kopiera HELA filen):**
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

## Steg-för-steg: Lägg till Secrets i GitHub

1. **Gå till:** https://github.com/tempdump/mackan-eu/settings/secrets/actions
   - (Du måste vara inloggad och ha admin-rättigheter)

2. **Klicka "New repository secret"** för varje secret:

### Secret 1: SSH_HOST
- **Name:** `SSH_HOST`
- **Value:** `omega.hostup.se`

### Secret 2: SSH_USER
- **Name:** `SSH_USER`
- **Value:** `mackaneu`

### Secret 3: SSH_PRIVATE_KEY
- **Name:** `SSH_PRIVATE_KEY`
- **Value:** (Öppna filen `C:\Users\marcu\.ssh\id_ed25519_github_actions_mackan` och kopiera HELA innehållet, inklusive `-----BEGIN OPENSSH PRIVATE KEY-----` och `-----END OPENSSH PRIVATE KEY-----`)

### Secret 4: DEPLOY_PATH
- **Name:** `DEPLOY_PATH`
- **Value:** `~/public_html` (eller rätt sökväg - kontrollera med kommandot nedan)

**Kontrollera DEPLOY_PATH:**
```powershell
& "C:\Windows\System32\OpenSSH\ssh.exe" -i "$env:USERPROFILE\.ssh\id_rsa_pollify" mackaneu@omega.hostup.se "pwd && ls -la"
```

## ✅ När secrets är lagda till:

1. Gör en liten test-ändring
2. Commit och push till `main`
3. Gå till: https://github.com/tempdump/mackan-eu/actions
4. Se deployment köras automatiskt! 🎉

