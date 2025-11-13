# 🔐 Lägg till GitHub Secrets - Steg för Steg

Jag kan inte automatiskt klicka i browsern, men här är exakta instruktioner:

## Steg 1: Gå till Secrets-sidan
Du är redan där: https://github.com/tempdump/mackan-eu/settings/secrets/actions

## Steg 2: Klicka "New repository secret"

## Steg 3: Lägg till varje secret (upprepa 4 gånger)

### Secret 1: SSH_HOST
1. **Name:** `SSH_HOST`
2. **Secret:** `omega.hostup.se`
3. Klicka "Add secret"

### Secret 2: SSH_USER
1. **Name:** `SSH_USER`
2. **Secret:** `mackaneu`
3. Klicka "Add secret"

### Secret 3: SSH_PRIVATE_KEY
1. **Name:** `SSH_PRIVATE_KEY`
2. **Secret:** (Kopiera HELA innehållet från filen nedan)
3. Klicka "Add secret"

**SSH Private Key (kopiera allt):**
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

### Secret 4: DEPLOY_PATH
1. **Name:** `DEPLOY_PATH`
2. **Secret:** `~/public_html`
3. Klicka "Add secret"

## ✅ När alla 4 secrets är lagda:

1. Gå till: https://github.com/tempdump/mackan-eu/actions
2. Du ska se att workflow körs automatiskt (eller klicka "Run workflow")

## 🧪 Testa Deployment:

När secrets är lagda, gör en liten ändring:

```bash
echo "# Test" >> DEPLOYMENT_STATUS.md
git add DEPLOYMENT_STATUS.md
git commit -m "Test: GitHub Actions deployment"
git push origin main
```

Sedan gå till Actions-sidan och se deployment köras! 🎉

