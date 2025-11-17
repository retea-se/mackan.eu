# QR v4 – Samlat QR-superverktyg

## 1. Syfte och målgrupp

- Samla funktionaliteten från `qr_v1–v3` i ett verktyg som funkar lika bra för support, kommunikations- och eventteam utan teknisk bakgrund.
- Fokusera på direktanvändning i webbläsare: inga inlogg, ingen reklam, inga betalväggar.
- Bygg modulärt så att framtida dynamik/statistik kan aktiveras när backendstöd (databas/spårning) finns.

## 2. Arv från befintliga versioner

| Källa   | Funktion                                                       | Status i `qr_v4`            |
| ------- | -------------------------------------------------------------- | --------------------------- |
| `qr_v1` | Batch-rader → QR för felanmälan/länkar                         | ✅ Ingår i Batch-läge       |
| `qr_v1` | Export till PNG (ZIP) & DOCX                                   | ✅ Ingår i Exportpanelen    |
| `qr_v2` | Snabbval för text, URL, WiFi, vCard, e-post, SMS, telefon, geo | ✅ Ingår i Formulärbyggaren |
| `qr_v2` | Realtidsförhandsvisning, kopiera/ladda ned bild                | ✅ Ingår i Förhandsvyn      |
| `qr_v3` | Lägesväxling Single/Batch                                      | ✅ Första nivå i UI         |
| `qr_v3` | Batchtyper (felanmälan, länkar, valfri text)                   | ✅ + utbyggbart fler mallar |
| `qr_v3` | Export DOCX/ZIP + statusindikator                              | ✅                          |

### 2.1 Detaljerad kartläggning

- **qr_v1**
  - Styrkeprofil: superenkel batchhantering för felanmälan/länkar, tydliga exportsätt (ZIP, DOCX).
  - Brister: saknar olika QR-typer, saknar förhandsvisning per rad, inget stöd för färg/logga.
  - Erfarenhet att ta med: nod/adress-parsern och e-postmallar för felanmälan ska lyftas över som en batch-mall.
- **qr_v2**
  - Styrkeprofil: Guidat formulär för enstaka koder med alt-texter, knappar för kopiera/ladda ner.
  - Brister: ingen historik, inget batch-läge, inget exportsätt utöver PNG/kopiera, få hjälpfunktioner.
  - Erfarenhet att ta med: tydliga komponentnamn, versionstext, MutationObserver för tillgänglighet.
- **qr_v3**
  - Styrkeprofil: Kombinerar single + batch, export till flera format, statusindikatorer.
  - Brister: designmässigt spretig, få pedagogiska stödtexter, begränsad mallhantering, inga avancerade typer.
  - Erfarenhet att ta med: två lägen ska finnas kvar men med tydligare onboarding, exportpanelens struktur fungerade väl.

### 2.2 Gap-analys

- **Genereringstyper**: qr_v2/v3 täcker standardtyper men saknar t.ex. kalender, WhatsApp, betalning – planeras för framtida release.
- **Batch**: qr_v1 har nodadressparser, qr_v3 har generellt batchflöde men saknar etikettutskrifter och färg/logotyper – läggs in i MVP.
- **Export**: qr_v1/3 har ZIP/DOCX, men saknar färdig PDF med grid. `qr_v4` får en PDF-generator (canvas→PDF) i MVP.
- **Pedagogik**: inga versioner har onboarding/hjälp-hub, därför nytt steg i `qr_v4`.
- **Historik/mallar**: saknas helt, införs via localStorage utan konto.

## 3. Informationsarkitektur & layout

1. **Hero / Onboarding-panel**
   - Kort pitch + användningsexempel.
   - Mini-checklista: “1. Välj läge → 2. Mata in → 3. Förhandsgranska → 4. Exportera”.
2. **Lägesnav**
   - Primär toggle: `Enkel QR` vs `Batch & etiketter`.
   - Sekundära chips för dataset (Text, URL, WiFi, vCard, etc.).
3. **Formuläryta**
   - Dynamiskt formulär per typ, med inline hjälptexter och validering.
   - “Avancerat”-accordion för färger, logotyp, storlek.
   - “Expertläge”-toggle som öppnar panel med formvariationer, gradienter och logoplacering.
4. **Förhandsvy**
   - Direkt återkoppling med större QR och kort sammanfattning (“URL + färg + logga”).
   - Tillgänglighetsindikator (alt-text, kontrast OK).
5. **Exportpanel**
   - Primära knappar: `Ladda ner PNG`, `Kopiera`, `Skapa etiketter (PDF)`, `Zip (batch)`, `DOCX`.
   - “Spara mall” (lokal storage) + “Historik”.
6. **Hjälp & lärande**
   - Högermeny med tooltips, vanliga frågor och snabbguide, visas responsivt som drawer/mobil-modal.

### Textbaserade wireframes

```
[ Hero ] ----------------------------------------------------
| QR-superverktyg · [Kom igång-checklista]                  |
-------------------------------------------------------------
[ Lägesnav ]  [Enkel QR] [Batch & etiketter]  |  Taggar för typ
-------------------------------------------------------------
[ Formulär ]  (dynamiska fält)      |  [Avancerat ▼]
-------------------------------------------------------------
[ Förhandsvy ]  |  [Sammanfattning av datat]  |  [Status]
-------------------------------------------------------------
[ Exportpanel ] PNG · Kopiera · PDF-etiketter · ZIP · DOCX
-------------------------------------------------------------
[ Hjälp & historikdrawer ] (mallar, senaste, tips)
```

### 3.1 Flöden

- **Single-läge**
  1. Välj typ via chips → chip får `aria-pressed="true"`.
  2. Formulär renderas med fokus i första fältet; validering sker on-blur.
  3. Knappen “Generera QR” aktiveras först när obligatoriska fält har innehåll.
  4. Förhandsvyn uppdateras, exportpanel öppnas, historik loggas lokalt.
  5. Användaren kan justera färger/logga i “Avancerat”; om “Expertläge” aktiveras visas även kontroller för modulstorlek, ögonform, gradienter och logoplacering med varningar om läsbarhet.
- **Batch-läge**
  1. Välj batchmall (felanmälan, länkar, etc.) → visar stegindikator “Steg 1/3”.
  2. Multiradstextarea med placeholder + exempel; import av CSV/Excel (drag and drop) planeras i Plus-version.
  3. På “Generera” visas statusbanner (spinner + progress) och lista av QR-miniatyrer.
  4. Exportpanel slår om till batch-läge med val för `Zip`, `DOCX`, `PDF-etiketter`.
  5. Batchresultat kan filtras (sök/markera) innan export.
- **Historik & mallar**
  1. “Spara som mall” öppnar modal där namn + taggar anges.
  2. Mallar sparas lokalt; UI visar 3 senaste under panelen.
  3. Återanvändning laddar in formulärdata + inställningar + förhandsvy.

### 3.2 Tillgänglighet, responsivitet & visuellt ramverk

- All interaktion sker via semantiska element (button/nav) och är fokusmarkerade.
- `aria-live="polite"` på status/feedback för export och generering.
- Grid layout: `grid-template-columns: minmax(280px, 420px) minmax(280px, 1fr)` på desktop. Under 900px staplas sektioner och drawer blir fullbreddsmodal.
- Basdesign hämtar inspiration från `mackan.eu/index`: svart/vitt tema, tunna gränslinjer, generös whitespace, monospaced rubriker i uppercase. Accentfärg (t.ex. gul) används sparsamt för fokus- och primärknappar.
- Verktyget ska klara mörkt/ljust tema genom CSS-variabler där svartvita stilen är default.
- Ikonografi: inga emojis får användas någonstans i UI. Endast egna SVG-ikoner i svart/vitt får förekomma (inline eller importerade) för att passa designen.
- **Design tokens**: Innan kod skrivs ska typografi, färgpalett och spacing extraheras från `mackan.eu/index` och dokumenteras i `tools/qr_v4/design-tokens.md`. Detta dokument är obligatorisk referens för alla UI-beslut.

### 3.3 Expertläge

- Aktiveras via en toggle i närheten av “Avancerat”-panelen.
- Ger tillgång till kontroller för:
  - Modulform/ögondesign (kvadrat, rund, droppar, dots).
  - Färggradienter (två färgstopp) och bakgrundsmönster.
  - Logoinlägg: uppladdning av SVG/PNG, storleksslider, form (cirkel/kvadrat) och skyddszon-indikator.
  - Feedback-banner som varnar om för hög täckning eller för låg kontrast.
- Standardläge döljer dessa inställningar för att hålla UI:et avskalat för enklare användare.

### 3.4 UI-förenklingsplan (feedback 2025-11-17)

- **Layoutomtag**: Slå ihop hero, lägesnav och formulär till en vertikal trestegsmotor (`1. Typ`, `2. Innehåll`, `3. Stil`) som alltid ligger i vänsterkolumnen. Förhandsvy/export/histori kort läggs i en högerkolumn (sticky på desktop, staplad på mobil) för att minska scroll och dubbla paneler.
- **Batch upplevelse**: Ge batch-läget en egen sida med samma tre steg men fokus på input + resultatkort; hjälptexter och mallar hamnar i en toppbanner istället för under textarea.
- **Snabbstarter**: Lägg till tre stora snabbkort överst (Text, URL, Felanmälan) som förfyller rätt formulär och stilval så nya användare slipper tolka chip/accordion-kombinationen.
- **Avancerat/expert**: Visa primära färgkontroller och logoupload inline medan övriga expertinställningar flyttas till en högerdrawer som öppnas med “Fler stilval”. Drawer visar även varningar (kontrast, logga, exportblock) så panelen inte känns överväldigande.
- **Assist-panel**: Kombinera statusindikator, hjälplänkar och varningskort till en tydlig assist-panel med ikonchips (t.ex. `Kontrast`, `Logga`, `Export`). Panelen blir sticky i högerkolumnen och ersätter spridda banners.
- **Tokenjustering**: Uppdatera `design-tokens.md` med nya spacing-nivåer (24 px primärt, 16 px sekundärt), kortskuggor och textstorlekar så koden kan förenklas i nästa UI-pass.

## 4. UX-principer

- Stegvis guidning med visuella “checkar” när ett steg är klart.
- Inline-validering med konkreta förslag (lägg till https://, kontrollera latitud-format).
- Responsiv grid där förhandsvyn hoppar under formuläret på mobil, men ligger sida vid sida på desktop.
- Tillgänglighet: tangentbordsnavigering, aria-live-meddelanden för generering/export, hög kontrast.

## 5. MVP-funktioner (utan databas)

1. **Single-läge**
   - Stöder alla typer från `qr_v2`.
   - Färg-/logoval (SVG/PNG upp till 500 KB) och Expertläge med formvariationer, gradienter och logoplacering.
   - Snabbknappar “Återställ standard”, “Duplicera inställningar”.
   - **Autouppdatering**: QR-förhandsvyn ska regenereras direkt när text/data, modulform, hörnform, färg, logga eller storlek ändras så “Generera”-knappen endast behövs för historik/export semantik.
   - **Logoväg**: Uppladdad logotyp renderas i preview (utan vit täckyta) och visas i assist-panelen med status (OK, för stor, saknas).
2. **Batch-läge**
   - Mallväljare (felanmälan, länkar, fri text).
   - Förhandslista med miniatyrer + statusrad (“12 av 12 lyckades”).
   - Export: PNG (ZIP), DOCX, etikett-PDF (A4 grid).
3. **Historik & mallar**
   - Lokalt sparade senaste 10 genereringarna.
   - Mallgalleri med beskrivning (“Eventaffisch”, “WiFi för gäster”).
4. **Hjälp & pedagogik**
   - Stickies för riktlinjer (“Så läser mobilen färgen”, “QR-storlek vs avstånd”).
   - Snabbknapp “Testa i mobil” som öppnar QR i nytt fönster för skanning.

## 6. Framtida/premiumfunktioner (planerade, kräver backend)

- Dynamiska QR-koder (ändringsbar destination, versionshistorik).
- Spårning/statistik (skanningar per plats/enhet/tid, CSV-export).
- Integrationer (webhooks, Zapier-liknande, API-nycklar).
- Säkerhetslager: lösenord, temporära landningssidor, GDPR-formulär.
- Offline/PWA-läge och batch-redigering med synk vid uppkoppling.
- Magic-link-konton: om användare ska skapa konto i framtiden används engångslänkar via e-post (ingen lösenordshantering). Koppla statistik/dynamik till sådana konton när backend finns.

### 6.1 Specifikation för kommande funktioner

- **Dynamiska QR-koder**
  - Skapa “QR-profil” med mål-URL, metadata och versionslogg.
  - Frontend visar badge “Dynamisk” och länk till versionshistorik-modal.
  - Kräver backend-endpoint: `POST /qr-profiles`, `PATCH /qr-profiles/:id`.
- **Statistik**
  - Dashboard med kort (totala skanningar, senaste 7 dagar, toppkanaler).
  - Tabell per QR med exportknapp (CSV, JSON).
  - Kräver loggning av event och aggregering; i MVP visas placeholderkort “Kommer snart”.
- **Integrationer**
  - Webhook-hantering (URL + secret) per dynamisk QR.
  - Zapier/Make-aktiveringslänkar; API-nyckel genereras per konto.
  - UI visar status för senaste webhook-körning.
- **Säkerhet & landningssidor**
  - Möjlighet att kräva PIN/lösenord innan redirect.
  - Enkel builder för landningssida (rubrik, text, CTA, formulär).
  - GDPR-flaggor: toggles för cookie-banner, loggopt-out.
- **Offline/PWA**
  - Installationsprompt + caching av senaste mallar och exportkö.
  - Batchredigering i offlinekö; när uppkoppling återkommer körs export/server-sync.
- **API**
  - REST och senare GraphQL för generering, statistik, mallhantering.
  - Rollenivåer: “viewer”, “editor”, “owner”.
- **Konto/Autentisering**
  - Endast magic links: användare anger e-post → får engångslänk (giltig 10 min).
  - Sessionshantering via stateless token + refresh.
  - Admin kan bjuda in kollegor via magic link, inga lösenord lagras.

## 7. Leverabler

- Detta dokument: krav + IA + wireframe.
- Kommande steg: skapa `index.php`, `script.js`, `styles.css` i `tools/qr_v4` enligt ovan.
- Dokumentera prioriteringar i slutet av README när utvecklingen fortskrider (MVP → Plus → Premium).
- Alla implementationer ska göras i separat git-branch `qr_v4` innan merge.
- Varje större ändring måste ackompanjeras av uppdaterade tester (se kapitel 10).

## 8. Prioriteringsmatris

| Nivå        | Funktioner                                                                                                                              |
| ----------- | --------------------------------------------------------------------------------------------------------------------------------------- |
| **MVP**     | Single/batch, färg/logga, export (PNG/ZIP/DOCX/PDF), historik/mallar (lokal), hjälp-drawer                                              |
| **Plus**    | Etikett-layoutbuilder, CSV-import, fler QR-typer (kalender, WhatsApp), bättre färgkontroll, mallgalleri i moln (fortfarande utan konto) |
| **Premium** | Dynamiska QR, statistik, integrationer, magic-link-konton, säkerhetslager, API, offline/PWA                                             |

## 9. Dokumentationsspårning

- `tools/qr_v4/README.md` (detta dokument) = Single source of truth för krav, IA, UX.
- `tools/qr_v4/wireframes/` (kommande) kan innehålla PNG/PDF när visuella mockups tas fram; tills dess fungerar ASCII-wireframe ovan som utgångspunkt.
- Varje kommande funktion bör kryssas av i denna fil under respektive nivå för att hålla parity mellan implementation och plan.
- Testresultat, Lighthouse/SEO-rapporter och fel-loggar ska arkiveras i underkataloger till `tools/qr_v4` för enkel spårning.
- **Scope-regel**: All kod, dokumentation, tester och skript kopplat till QR v4 måste ligga i denna mappstruktur. Inga filer utanför `tools/qr_v4/` får ändras av projektet och ingen del av projektet ska spridas till andra mappar.
- `tools/qr_v1–v3` ligger kvar orörda under utvecklingen; borttag sker först när v4 ersätter dem och hanteras manuellt av ägaren.

## 10. Kvalitetssäkring & drift

- **Automatiserade tester**
  - Djupa, aggressiva end-to-end-tester (Playwright/Cypress) ska täcka huvudflöden inkl. expertläge, batch-exporter och historik. Testerna ska vara helt skräddarsydda för `qr_v4` (inga delade/generiska flows) och uppdateras vid varje förändring.
  - Smoke tests körs efter varje deployment/merge för att säkerställa att kritiska flöden (single generate, batch export, hjälppanel) fungerar.
  - Playwright setup finns i `playwright.config.ts` med scenarier under `tests/e2e/` och `tests/smoke/` (exempel: `single-text-basic`, `expert-mode-validations`, `batch-felanmalan-export`, `history-and-mall-reuse`).
- **Loggning**
  - Lokal fel-logg i `tools/qr_v4/logs/` (roterade textfiler) fångar JS-fel/spårning under utveckling. UI visar diskret banner om fel loggats.
- **Prestanda & SEO**
  - Lighthouse, SEO och AISEO-kontroller (t.ex. via automatiserade skript) ska uppnå “grönt” resultat innan release. Rapporter sparas i `tools/qr_v4/reports/`.
  - Tillgänglighetsresultat från Lighthouse dokumenteras och regressions bevakas.
- **Process**
  - Utveckling sker i branch `qr_v4`, PR måste innehålla:
    - Länk till senaste e2e-rapport.
    - Smoke-testresultat.
    - Lighthouse/SEO/AISEO-rapport.
    - Notering om lokala fel-loggar är rena eller bifogade.
  - Obligatoriska npm-/script-kommandon ska finnas för att köra Lighthouse/SEO/AISEO lokalt och automatiskt exportera rapporter till `tools/qr_v4/reports/`.
  - `package.json` scriptöversikt:
    - `npm run dev` – startar lokal PHP-server (`php -S localhost:5173 -t tools/qr_v4/app`).
    - `npm run test:e2e` – kör Playwright (kräver `npx playwright install` första gången).
    - `npm run test:smoke` – kör `scripts/run-smoke.js` (stub tills riktiga tester finns).
    - `npm run report:lighthouse|seo|aiseo` – genererar JSON-rapporter i respektive katalog via stubscript.
    - `npm run logs:rotate` – roterar `logs/runtime.log`.

## 11. Förberedande artefakter och spikes

- **Expertläge / QR-bibliotek**

  - Dokumentera utvärdering i `tools/qr_v4/notes/qr_lib_eval.md` med tabell för kandidater (licens, modul-/ögonform, gradienter, logga, performance, underhåll).
  - Spike: skapa små prototyper (kod eller screenshots) i samma dokument och spara exempel i `tools/qr_v4/notes/screens/`.
  - Beslut loggas i README så alla vet vilket bibliotek som valts och varför.

- **Filstruktur för tester/loggar/rapporter**

  - Följande struktur gäller innan kod skrivs:
    ```
    tools/qr_v4/
      ├── app/
      ├── tests/
      │   ├── e2e/
      │   ├── smoke/
      │   └── helpers/
      ├── logs/
      │   ├── runtime.log
      │   └── runtime-YYYYMMDD.log
      ├── reports/
      │   ├── lighthouse/lighthouse-YYYYMMDD.json
      │   ├── seo/seo-YYYYMMDD.json
      │   └── aiseo/aiseo-YYYYMMDD.json
      └── notes/
    ```
  - Scripts ska lägga filer enligt mönstret ovan för enkel versionskontroll.
  - Stubbar för rapportscripts och logghantering finns i `tools/qr_v4/scripts/` och ska ersättas med riktiga implementationer när respektive verktyg kopplas in.

- **Designreferenser**

  - `tools/qr_v4/design-tokens.md` beskriver typografi, färger, spacing och komponentmönster hämtade från `mackan.eu/index` (skärmdumpar/tokens).
  - Dokumentet ska innehålla exempel på rubriker, knappar, cards, samt “Do/Don't”.

- **Tillgänglighetsdefault**
  - `tools/qr_v4/accessibility-guidelines.md` definierar:
    - Minst 4.5:1 kontrast för text (<18 pt) och 3:1 för större element.
    - Logoinlägg max 30 % av QR-ytan, minst 10 % fri zon runt logga.
    - Automatisk varning/blockering om färgval/gradienter bryter mot läsbarhet.
    - Minimistorlekar för QR (t.ex. 20 mm vid normal skanning).
  - Checklistan ska refereras av QA så e2e tester inkluderar dessa regler.

## 12. Kända problem och åtgärdsplan (2025-11-17)

1. **Rörigt gränssnitt**
   - Symptombild: Hero, lägesnav, chips, avancerat och expert visas samtidigt och upplevs som svårnavigerade.
   - Åtgärd: Implementera UI-förenklingsplanen i §3.4, inkl. trestegsflöde, assist-panel och separerat batchläge. Uppdatera `design-tokens.md` innan kodning.
2. **Logo uppladdas men resultatet blir vit ruta**
   - Hypotes: `QRCodeStyling` renderar en tom SVG p.g.a. felaktig dataURI/cors eller för stor `hideBackgroundDots`-yta.
   - Plan: Debugga `handleLogoUpload` + `updateQRInstance`, säkerställ att `image` får korrekt dataURL, testa både PNG/SVG, samt lägg till fallback-preview bredvid upload-fältet.
3. **Manuellt “Generera”-krav upplevs onödigt**
   - Beslut: Inför autogenerering som del av MVP (se §5) med debounced `updateQRInstance` när formulär/inställningar ändras. Behåll knappen endast för att logga historik och ge användaren explicit “spara”-ögonblick.
4. **Tester**
   - Lägg till Playwright-scenarier som täcker logo-preview, autouppdatering (utan extra klick) samt nya layoutflödet för både single och batch för att förhindra regressioner när UI förenklas.
