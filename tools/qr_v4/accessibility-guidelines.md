# QR v4 – Accessibility Guidelines

Alla nya funktioner ska verifieras mot dessa regler innan merge.

## Kontrast & färg

- Text < 18pt: minst 4.5:1 kontrast mot bakgrund.
- Text ≥ 18pt eller semibold: minst 3:1.
- Ikoner/linjer: minst 3:1.
- Accentfärg (`#ffd500`) får endast användas på element som klarar kontrastkraven; annars använd mörkare variant `#d4aa00`.
- Lägg aldrig text direkt över gradient utan solid overlay (minst 80 % opacitet).

## QR-specifika krav

- Logoinlägg får max täcka 30 % av QR-ytan.
- Minst 10 % av modulbredd ska vara fri runt loggan (skyddszon).
- Standardmarginal runt QR: 4 moduler (kan justeras uppåt i expertläge, men aldrig under 2 moduler).
- Automatisk kontroll ska varna/blockera export om:
  - Kontrast mellan QR-moduler och bakgrund < 2.5:1.
  - Logo överskrider safe-zone eller saknar transparent/solid bakgrund.

## Storlek & läsbarhet

- Minsta rekommenderade utskriftsbredd: 20 mm för korta URL:er, 30 mm för längre data.
- För tryck på distans (posters) ska preview visa guideline (“Läsbar på upp till X m”).
- På mobil ska QR-simple-läge kunna zoomas till minst 320px.

## Interaction & fokus

- Alla interaktiva element måste vara tangentbordstilgängliga (tabindex=0, aria-pressed för toggles).
- Fokusmarkering: 2px solid accent + 2px transparent outline offset.
- Statusmeddelanden (t.ex. “QR genererad”, “Export klar”) ska annonseras via `aria-live="polite"`.

## Test & verifiering

- E2E-tester ska inkludera:
  - Kontroll att expertlägets varningssystem triggas på fel värden.
  - Verifiering att färgval som bryter mot regler blockeras.
- Smoke-test checklista ska innehålla: tangentbordsgenerering, screenreader av rubriker, exportknappar.

## Dokumentation

- Alla avvikelser (t.ex. kundbegärd logostorlek >30 %) måste dokumenteras i PR och i `notes/accessibility-exceptions.md`.

