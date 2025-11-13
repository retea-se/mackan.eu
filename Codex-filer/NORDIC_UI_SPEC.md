# Nordic UI — Designspecifikation (v1)

Mål: Enhetlig, skandinavisk design över alla verktyg på mackan.eu. Minimalism, hög läsbarhet, robust tillgänglighet, konsekventa komponenter och responsivt beteende.

## Principer
- Enkelhet: få färger, mycket luft, lugna skuggor, rundade hörn.
- Tillgänglighet: AA/AAA‑kontrast, synliga fokusstilar, korrekt semantik och ARIA.
- Konsekvens: samma typografi, spacing, radier och komponentbeteenden överallt.
- Responsivt: mobile‑first, grid med `auto-fit/minmax`, typografi via `clamp()`.

## Design‑tokens (variables.css)
- Färger: neutrals (bakgrund/text/border), accent `--primary-color` (+ mörk variant), status (info/success/error). Allt via CSS‑variabler.
- Typografi: bas 16px; rubriker med `clamp()`; systemfont/Inter.
- Spacing: skala i 4/8/12/16/24 px; återanvänd genomgående.
- Radier/skuggor: `--border-radius: 8px`; kortskugga svag, inga hårda konturer.

## Layout
- Sticky footer: `.layout` + `.layout__main` (flex: 1) + `.sidfot` (flex-shrink: 0).
- Header: hem‑ikon, breadcrumb ("Verktyg › namn"), tema‑knapp, README‑ikon.
- Container: `.layout__container` (max‑width 1200px), god standard‑padding.

## Komponenter (BEM)
- Knappar: `.knapp` (+ `--liten|--stor|--fara`), ikonknapp `.knapp__ikon`.
- Form: `.form__grupp`, fält `.falt__input|__dropdown|__textarea`, hjälp `.form__hint`.
- Status: `.status status--info|--success|--error` (återanvänds i verktyg).
- Chips (segmenterad radio): horisontell grupp med runda “chips” för val.
- Kort: diskret ram, rundade hörn, lätt skugga, generöst med luft.

## Tema
- Använd `prefers-color-scheme` och `data-theme` (server‑side cookie) för FOUC‑fri växling.
- Alla färger via variabler; inga hårdkodade färger i blocks/komponenter.

## Tillgänglighet
- Fokusring synlig med kontrast (`:focus-visible`).
- ARIA på ikonknappar, `aria-describedby` för hjälptexter.
- `prefers-reduced-motion`, semantiska rubriker (H1→H2→H3), korta tooltips.

## Prestanda
- Preconnect, lazy‑loading på bilder, lång cache för statiska assets, no‑cache för PHP.
- Minimera externa beroenden; CDN bara när det ger effekt.

## Migreringsplan
1) Bas‑harmonisering (klart): compat‑CSS och auto‑BEM för omedelbar visuell jämnhet.
2) Konsolidera vägar: alla verktyg via `index.php` + layout‑includes (redirect från `.html`).
3) Markup: byt “nakna” element till `.knapp` och `.falt__*`; gruppera med `.form__grupp`.
4) Komponentisera: chips (radio), statusblock, kort; lyft sid‑CSS till blocks/.
5) QA: DevTools‑UI‑audit och Lighthouse; åtgärda kontrast/FOUC/semantik.

## Mätning
- DevTools‑skript (headless) för console/UI‑audit över alla verktyg.
- Lighthouse CLI för Performance/Accessibility/Best Practices/SEO.

