# 🔐 SSH-nycklar - Förklaring

## Två olika SSH-nycklar behövs:

### 1. SSH-nyckel mellan DIN DATOR och GITHUB (för git push)
- **Behövs:** Om du vill pusha kod till GitHub via SSH
- **Status:** ❓ Behöver kontrolleras
- **Fil:** `C:\Users\marcu\.ssh\id_ed25519` (eller liknande)

### 2. SSH-nyckel mellan GITHUB ACTIONS och SERVERN (för deployment)
- **Behövs:** För att GitHub Actions ska kunna logga in på servern
- **Status:** ✅ Finns redan (`id_ed25519_github_actions_mackan`)
- **Fil:** `C:\Users\marcu\.ssh\id_ed25519_github_actions_mackan`

## Viktigt:

**De två nycklarna är olika och används för olika ändamål!**

- **Dator → GitHub:** För att pusha kod
- **GitHub Actions → Server:** För att deploya kod

## Om du pushar med HTTPS:

Om du pushar med HTTPS (t.ex. `https://github.com/tempdump/mackan-eu.git`), behöver du INTE en SSH-nyckel för GitHub. Du loggar in med användarnamn/lösenord eller token istället.

## Om du vill pusha med SSH:

Om du vill pusha med SSH (t.ex. `git@github.com:tempdump/mackan-eu.git`), behöver du en SSH-nyckel för GitHub.

---

**För GitHub Actions deployment behöver vi:**
- SSH-nyckel som fungerar mellan GitHub Actions och servern (omega.hostup.se)
- Denna nyckel måste vara lagd i GitHub secrets som `SSH_PRIVATE_KEY`



