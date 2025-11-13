# Instruktioner för att skapa GitHub Issues

## Problem
Repositoryt `tempdump/mackan-eu` kunde inte nås via GitHub CLI/API. Detta kan bero på:
1. Repositoryt finns inte ännu
2. Repositoryt är privat och du har inte rätt åtkomst
3. Repositoryt finns under ett annat namn eller organisation

## Lösningar

### Alternativ 1: Skapa issues via PowerShell-skript
1. Se till att repositoryt `tempdump/mackan-eu` finns och att du har åtkomst
2. Kör skriptet:
   ```powershell
   .\github-issues\create-issues.ps1
   ```

### Alternativ 2: Skapa issues via GitHub CLI manuellt
```powershell
# För varje issue-fil:
gh issue create --repo tempdump/mackan-eu --title "🛡️ SÄKERHET: Ta bort eval() från converter/utilities.js" --body-file "github-issues\01-sakerhet-ta-bort-eval.md" -l bug -l security
```

### Alternativ 3: Skapa issues via GitHub Web UI
1. Öppna varje `.md`-fil i `github-issues/`-mappen
2. Kopiera innehållet
3. Gå till https://github.com/tempdump/mackan-eu/issues/new
4. Klistra in innehållet
5. Lägg till rätt labels (finns i varje fil)

### Alternativ 4: Skapa issues via GitHub API
```powershell
$token = gh auth token
$headers = @{
    Authorization = "Bearer $token"
    Accept = "application/vnd.github.v3+json"
}
$body = Get-Content "github-issues\01-sakerhet-ta-bort-eval.md" -Raw
$json = @{
    title = "🛡️ SÄKERHET: Ta bort eval() från converter/utilities.js"
    body = $body
    labels = @("bug", "security")
} | ConvertTo-Json

Invoke-RestMethod -Uri "https://api.github.com/repos/tempdump/mackan-eu/issues" -Method Post -Headers $headers -Body $json -ContentType "application/json"
```

## Issues att skapa

1. **01-sakerhet-ta-bort-eval.md** - Hög prioritet (Säkerhet)
2. **02-sakerhet-input-validering.md** - Hög prioritet (Säkerhet)
3. **03-seo-meta-description.md** - Medel prioritet (SEO)
4. **04-seo-json-ld.md** - Medel prioritet (SEO)
5. **05-ux-felhantering-toast.md** - Medel prioritet (UX)
6. **06-ux-loading-indikatorer.md** - Medel prioritet (UX)
7. **07-kodkvalitet-bem-struktur.md** - Låg prioritet (Kodkvalitet)
8. **08-kodkvalitet-gemensam-js.md** - Låg prioritet (Kodkvalitet)

## Nästa steg

1. Kontrollera att repositoryt `tempdump/mackan-eu` finns
2. Verifiera att du har rätt åtkomst till repositoryt
3. Kör PowerShell-skriptet eller skapa issues manuellt
4. Verifiera att alla issues har skapats korrekt

