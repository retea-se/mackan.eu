# QR v4 – Design Tokens

Alla värden är extraherade från `mackan.eu/index` (svartvit grunddesign) och ska användas konsekvent i hela verktyget.

## Färger

| Token | Hex | Användning |
| --- | --- | --- |
| `--color-bg` | `#fefefe` | Standardbakgrund |
| `--color-bg-alt` | `#f4f4f2` | Sektioner/kort |
| `--color-text` | `#111111` | Brödtext |
| `--color-muted` | `#6d6d6d` | Sekundär text/etiketter |
| `--color-border` | `#1c1c1c` | Tunna linjer/ramar |
| `--color-accent` | `#ffd500` | Fokus/accenter (knappar, badges) |
| `--color-danger` | `#ff4d4f` | Varningar |

**Regel:** accentfärg används sparsamt; UI ska kännas monokromt med tydliga kontraster.

## Typografi

| Element | Font | Storlek | Vikt | Övrigt |
| --- | --- | --- | --- | --- |
| Display/H1 | `Space Grotesk`, sans-serif | 36px | 600 | Versaler |
| H2 | `Space Grotesk` | 24px | 600 | Versaler |
| Body | `Inter`, sans-serif | 16px | 400 | 1.6 line-height |
| Monospaced detaljer | `IBM Plex Mono` | 14px | 400 | Används för status/loggar |

`Space Grotesk` och `Inter` finns redan globalt – annars inkluderas via Google Fonts lokalt.

## Spacing & layout

| Token | Värde | Användning |
| --- | --- | --- |
| `--space-1` | 4px | Mikroavstånd |
| `--space-2` | 8px | Element-gap |
| `--space-3` | 16px | Standardmarginal |
| `--space-4` | 24px | Sektioner |
| `--space-5` | 32px | Hero/huvudsektion |

Grid: `minmax(320px, 440px)` vänsterkolumn (form) och flexibelt högerspalt (preview/export).

## Komponentlinjer

- Linjer/ramar använder 1px #1c1c1c eller #d4d4d4 beroende på kontrast.
- Hörnradie: 8px generellt, 12px för kort och buttons.
- Skuggor undviks; använd istället kantlinjer och layering.

## Ikonografi

- Emojis är förbjudna. Använd endast egenuppsatta svartvita SVG-ikoner (inline eller som komponenter).
- Ikoner ska ha 16–20 px storlek, 1.5 px stroke och kan inverteras på hover/fokus.

## Fokus & interaktion

- Fokusmarkering: 2px outline `#ffd500`.
- Hover på knappar: inverterad bakgrund (`#111` ↔ `#fefefe`) och textfärg för tydlighet.

## Anpassningar för mörkt läge

- `--color-bg` ↔ `#0b0b0b`, `--color-text` ↔ `#f7f7f2`, `--color-border` ↔ `#2a2a2a`.
- Accentfärg oförändrad men med min 3:1 kontrast.

