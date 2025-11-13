# 🎯 Nästa steg för att fixa GitHub Actions

## Problem

Alla GitHub Actions-körningar misslyckas, och loggarna laddas inte eller är tomma.

## Lösning

### Steg 1: Uppdatera DEPLOY_PATH secret till absolut sökväg

GitHub Actions kanske inte expanderar `~` korrekt. Vi behöver ändra `DEPLOY_PATH` från `~/public_html` till `/home/mackaneu/public_html`.

1. **Gå till:** https://github.com/tempdump/mackan-eu/settings/secrets/actions
2. **Klicka på `DEPLOY_PATH` secret** (om du kan se den, annars klicka på "New repository secret")
3. **Ändra värdet från:**
   ```
   ~/public_html
   ```
   **Till:**
   ```
   /home/mackaneu/public_html
   ```
4. **Spara**

### Steg 2: Verifiera SSH_PRIVATE_KEY secret

Kontrollera att SSH-nyckeln är korrekt kopierad:

1. **Gå till:** https://github.com/tempdump/mackan-eu/settings/secrets/actions
2. **Klicka på `SSH_PRIVATE_KEY` secret**
3. **Verifiera att den:**
   - Börjar med: `-----BEGIN OPENSSH PRIVATE KEY-----`
   - Slutar med: `-----END OPENSSH PRIVATE KEY-----`
   - Innehåller hela nyckeln (många rader)

### Steg 3: Testa deployment igen

När secrets är uppdaterade:

1. **Gör en liten test-ändring** (eller push en ny commit)
2. **Gå till:** https://github.com/tempdump/mackan-eu/actions
3. **Klicka på den nya körningen**
4. **Klicka på "Job Deploy via SSH"**
5. **Kolla loggarna** - nu ska de ladda korrekt!

## Alternativ: Testa manuellt först

Om du vill testa deployment-scriptet manuellt innan du uppdaterar secrets:

```bash
ssh -i C:\Users\marcu\.ssh\id_ed25519_github_actions_mackan mackaneu@omega.hostup.se "DEPLOY_PATH='/home/mackaneu/public_html'; cd \"\$DEPLOY_PATH\" && git fetch origin main && git pull origin main 2>&1 || { echo 'Git pull failed, resetting...'; git reset --hard origin/main; } && echo 'Deployment complete!' && git rev-parse --short HEAD"
```

---

**Viktigaste ändringen:** Uppdatera `DEPLOY_PATH` secret till `/home/mackaneu/public_html` istället för `~/public_html`.

