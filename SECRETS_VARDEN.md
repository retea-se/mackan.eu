# 🔐 GitHub Secrets - Snabbreferens

## Alla värden du behöver kopiera:

### Secret 1: SSH_HOST
```
omega.hostup.se
```

### Secret 2: SSH_USER
```
mackaneu
```

### Secret 3: SSH_PRIVATE_KEY
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
```
/home/mackaneu/public_html
```

**VIKTIGT:** Använd absolut sökväg (ovan) istället för `~/public_html` - GitHub Actions expanderar `~` inte alltid korrekt!

---

## 📋 Checklista

- [ ] SSH_HOST = `omega.hostup.se`
- [ ] SSH_USER = `mackaneu`
- [ ] SSH_PRIVATE_KEY = (hela nyckeln ovan)
- [ ] DEPLOY_PATH = `/home/mackaneu/public_html` ⚠️ **Uppdatera till absolut sökväg!**

---

## 🔗 Länkar

- **Secrets-sidan:** https://github.com/tempdump/mackan-eu/settings/secrets/actions
- **Actions-sidan:** https://github.com/tempdump/mackan-eu/actions

---

## 📝 Steg

1. Gå till secrets-sidan
2. Klicka "New repository secret"
3. Lägg till varje secret med värdena ovan
4. Testa deployment!

---

**Se `GITHUB_SECRETS_STEG_FOR_STEG.md` för detaljerade instruktioner.**

