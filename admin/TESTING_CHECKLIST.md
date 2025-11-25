# Testing Checklist - Admin Dashboard

## Pre-Deployment Checks
- [x] Alla filer skapade
- [x] Inga syntaxfel (linter check)
- [x] Variabelkollisioner fixade (kortlankPdo, skyddadPdo)
- [x] Theme system implementerat
- [x] Mobile-first CSS
- [x] Magic link authentication

## Deployment Steps
1. Deploya alla nya filer till servern via git:
   ```bash
   git add admin/
   git commit -m "Admin dashboard konsolidering"
   git push origin main
   ssh -i ~/.ssh/id_rsa_pollify mackaneu@omega.hostup.se "cd ~/public_html && git pull origin main"
   ```

2. Skapa cache-katalog på servern:
   ```bash
   ssh -i ~/.ssh/id_rsa_pollify mackaneu@omega.hostup.se "mkdir -p ~/public_html/admin/cache && chmod 755 ~/public_html/admin/cache"
   ```

## Testing Steps

### 1. Test Magic Link Authentication
- [ ] Navigera till `/admin/auth.php`
- [ ] Testa med `marcus.ornstedt@gmail.com` (ska skicka email)
- [ ] Testa med annan email (ska visa bekräftelse men inte skicka)
- [ ] Klicka på magic link i email
- [ ] Verifiera att inloggning fungerar
- [ ] Testa rate limiting (3 requests per 15 min)

### 2. Test Dashboard (index.php)
- [ ] Logga in och navigera till `/admin/index.php`
- [ ] Verifiera att översiktskort visas korrekt
- [ ] Testa tema-växling (mörkt/ljust)
- [ ] Verifiera att tema sparas i localStorage
- [ ] Testa på mobil (accordion-sektioner)
- [ ] Testa på desktop (alla sektioner öppna)

### 3. Test API (consolidated-stats.php)
- [ ] Verifiera att API returnerar JSON
- [ ] Kontrollera att alla tre databaser hämtas korrekt
- [ ] Testa caching (5 min)
- [ ] Verifiera att felhantering fungerar

### 4. Test Charts
- [ ] Verifiera att timvis besök-diagram laddas
- [ ] Verifiera att topp-sidor diagram laddas
- [ ] Testa lazy loading (ladda först när sektion expanderas)
- [ ] Testa tema-växling med diagram (ska uppdatera färger)

### 5. Test Mobile Responsiveness
- [ ] Testa på iPhone SE (375px)
- [ ] Testa på Android small (360px)
- [ ] Verifiera touch-optimering (min 44px knappar)
- [ ] Testa horizontal scroll på tabeller
- [ ] Verifiera accordion-funktionalitet

### 6. Test Error Handling
- [ ] Testa med databas offline
- [ ] Verifiera att felmeddelanden visas korrekt
- [ ] Testa med ogiltig token
- [ ] Testa med utgången token

### 7. Test All Sections
- [ ] Besöksstatistik-sektion
- [ ] Kortlänk-sektion
- [ ] Skyddad-sektion
- [ ] Verifiera att alla tabeller laddas korrekt

## Known Issues to Fix
- [ ] Kontrollera att cache-katalog skapas automatiskt
- [ ] Verifiera att rate limit-filer skapas korrekt
- [ ] Testa email-sending funktionalitet

## Post-Testing Cleanup
- [ ] Ta bort `test-login.php` efter testning
- [ ] Ta bort `create-test-token.php` efter testning
- [ ] Verifiera att inga känsliga data exponeras

