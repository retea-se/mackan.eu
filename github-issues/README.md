# GitHub Issues - Förbättringar för Tools-mappen

Detta mapp innehåller alla GitHub issues som skapats baserat på analysen av `/tools/`-mappen.

## 📋 Issues

### 🔴 Hög prioritet (Säkerhet)

1. **[01-sakerhet-ta-bort-eval.md](./01-sakerhet-ta-bort-eval.md)**
   - Ta bort `eval()` från `tools/converter/utilities.js`
   - Kritiskt säkerhetsproblem

2. **[02-sakerhet-input-validering.md](./02-sakerhet-input-validering.md)**
   - Lägg till input-validering för verktyg med POST-data
   - Verktyg: RKA, Kortlänk, Skyddad delning

### 🟡 Medel prioritet (SEO & UX)

3. **[03-seo-meta-description.md](./03-seo-meta-description.md)**
   - Lägg till `metaDescription` för verktyg som saknar det
   - Verktyg: Addy, TTS, RKA

4. **[04-seo-json-ld.md](./04-seo-json-ld.md)**
   - Lägg till strukturerad data (JSON-LD) för alla verktyg
   - 17 verktyg saknar JSON-LD

5. **[05-ux-felhantering-toast.md](./05-ux-felhantering-toast.md)**
   - Standardisera felhantering - Ersätt `alert()` med toast
   - Verktyg: PTS, Bolagsverket, Testdata, Converter

6. **[06-ux-loading-indikatorer.md](./06-ux-loading-indikatorer.md)**
   - Lägg till loading-indikatorer för asynkrona operationer
   - Verktyg: Testdata, PTS, Bolagsverket, Converter, Koordinat

### 🟢 Låg prioritet (Kodkvalitet)

7. **[07-kodkvalitet-bem-struktur.md](./07-kodkvalitet-bem-struktur.md)**
   - Migrera gamla verktyg till BEM-struktur
   - Verktyg: TTS, Converter, RKA, Stötta, Skyddad delning

8. **[08-kodkvalitet-gemensam-js.md](./08-kodkvalitet-gemensam-js.md)**
   - Skapa gemensam JavaScript-bas för vanliga funktioner
   - Reducera kod-duplicering

## 🚀 Hur du använder dessa issues

### Alternativ 1: Skapa issues manuellt i GitHub
1. Öppna varje `.md`-fil
2. Kopiera innehållet
3. Skapa ett nytt issue i GitHub
4. Klistra in innehållet
5. Lägg till rätt labels (finns i varje fil)

### Alternativ 2: Använd GitHub CLI
```bash
# Skapa issue från fil
gh issue create --title "🛡️ SÄKERHET: Ta bort eval() från converter/utilities.js" \
  --body-file github-issues/01-sakerhet-ta-bort-eval.md \
  --label "bug,security,high-priority,tools"
```

### Alternativ 3: Använd GitHub API
Se [GitHub API dokumentation](https://docs.github.com/en/rest/issues/issues#create-an-issue) för att skapa issues programmatiskt.

## 📊 Sammanfattning

- **Totalt antal issues**: 8
- **Hög prioritet**: 2
- **Medel prioritet**: 4
- **Låg prioritet**: 2

## 🔗 Relaterade dokument

- [FORBATTRINGSFORSLAG_TOOLS.md](../FORBATTRINGSFORSLAG_TOOLS.md) - Fullständig analys
- [PRODUKTION_TEST_RESULTAT.md](../PRODUKTION_TEST_RESULTAT.md) - Testresultat

---
**Skapad**: 2025-11-13
**Status**: Klar för review

