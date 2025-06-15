<?php
<!-- tools/mall/readme.php - v5 -->
<?php
$title = 'Om Mitt Verktyg';
$metaDescription = 'Information om detta verktyg, dess syfte och funktion. Lär dig hur du använder det med exempel och tydlig förklaring.';
?>
<?php include '../../includes/layout-start.php'; ?>

<?php
/*
================================================================================
README-MALL – RIKTLINJER & KOMMENTARER
================================================================================

1. Grundläggande struktur och metadata
   - Sätt alltid variablerna $title och $metaDescription högst upp i filen.
   - Använd endast ett <h1> per sida, och låt det spegla $title.
   - Om includes/layout-start.php används, undvik att lägga till ytterligare <h1>.
   - Lägg till versionskommentar i toppen av filen.
   - Skriv all text på svenska om inte annat anges.

2. Semantik och markup
   - Använd semantiska HTML5-element: <main>, <nav>, <section>, <article>, <footer>.
   - Hela huvudinnehållet ska wrappas i <main class="readme">.
   - Varje innehållsdel ska ligga i <article class="readme__section"> eller <section class="readme__section">.
   - Rubriker ska följa hierarkin: <h1> (endast en), därefter <h2> för sektioner, <h3> för eventuella underavsnitt.
   - Lägg till en länk tillbaka till verktyget (t.ex. index.php) med tydlig ankartext.

3. Komponentklasser och styling
   - All styling för README görs genom /css/blocks/readme.css. Ingen inline- eller global CSS för readme.
   - Använd endast readme-specifika klasser för komponenter:
     .readme__title, .readme__subtitle, .readme__text, .readme__list, .readme__codeblock, .readme__info, .readme__warning, .readme__quote, .readme__history, .readme__divider, .readme__meta, .readme__back, .readme__backlink, .readme__table
   - Inga globala klasser (t.ex. .container, .button, .card) får användas i readme – endast readme-namn.
   - Läsbarhet: Max 700px bredd på readme-container, gott om padding, luft mellan sektioner, extra radavstånd.
   - Dark mode och light mode måste alltid fungera och testas! (Se till att readme.css har [data-theme="dark"]-regler.)
   - Responsivitet: Alla readme-komponenter ska skala till mobil utan horisontell scroll. Kodblock får scroll om de är väldigt långa.

4. Kodexempel och kopiering
   - Kodexempel och svarsexempel ska alltid ligga i .readme__codeblock, även för enradiga snuttar.
   - Använd kopieringsknapp med klassen .readme__codecopy och ikon (t.ex. <i class="fa-solid fa-copy"></i>).
   - Kodkopiering ska aktiveras via JS för .readme__codecopy-knapp – se exempel i readme.html.
   - Om kodexempel ska visas, inkludera sektionen markdownExampleSection och visa ett relevant kodblock.
   - Om kodexempel inte används, ta bort sektionen för markdownexempel för att hålla sidan ren.
   - Om du visar kodexempel, se till att kodblockets språk (t.ex. ```json) är korrekt för syntaxhighlighting.

5. Tips/info, varningar och citat
   - Tips/info och varning ska använda .readme__info respektive .readme__warning – och alltid ha en ikon först!
   - Citat ska använda .readme__quote och gärna ikon.
   - Historiklistor ska använda .readme__history.
   - Dividera gärna med .readme__divider.
   - Alla tabeller i README ska använda .readme__table och får gärna ha ikoner i <th> (se styleguide).

6. Tillgänglighet och SEO
   - Lägg alltid till tydliga, pedagogiska och informativa tooltips på knappar, ikoner och andra interaktiva element med data-tippy-content.
   - Tooltip-texten ska vara kort, tydlig och hjälpa användaren att förstå funktionen.
   - Kontrollera att alla knappar och viktiga ikoner har en relevant tooltip.
   - Tooltip aktiveras automatiskt via Tippy.js – ingen extra JS behövs.
   - Om sidan inte ska indexeras av sökmotorer, säkerställ att rätt meta-taggar finns i meta.php.

7. Anpassning och underhåll
   - Byt ut exempeltexter, titlar och beskrivningar mot det som är relevant för det aktuella verktyget.
   - Lägg till eller ta bort sektioner efter behov.
   - Skriv korta, informativa texter – undvik utfyllnad.
   - Skriv kommentarer i koden där AI:n behöver göra val eller anpassningar.
   - Lägg till tydliga TODO- eller FIXME-kommentarer där AI:n behöver göra val eller anpassningar.
   - Undvik att lägga till onödiga script eller CSS om de inte används på sidan.
   - Kontrollera att alla länkar och scriptvägar är relativa och fungerar i projektets struktur.

8. Teknisk och innehållsmässig kvalitet
   - Beskriv syfte, funktioner och användning tydligt i separata sektioner med <h2>.
   - Ge exempel på typiska användningsfall och visa input → output med konkreta exempel.
   - Lista eventuella externa bibliotek, API:er eller algoritmer som används. men röj ingen känslig information om hur webbsidan eller funktionen är uppbyggd
   - Ange källor eller inspiration om det är relevant (t.ex. Wikipedia, RFC, MDN).
   - Lägg gärna till fun facts, prestandadata, edge cases eller historik om tekniken.
   - Om verktyget har unika egenskaper, lyft fram dem.
   - Kontrollera att rubriknivåerna är logiska och inte hoppar (t.ex. <h2> direkt efter <h1>).

Syftet med dessa riktlinjer är att säkerställa maximal läsbarhet, robust semantik, tillgänglighet och att alla readme-filer alltid är isolerade från övriga sidors styling.

================================================================================
Tippy.js-tips:
- Alla element med attributet data-tippy-content får automatiskt en tooltip.
- Lägg alltid till tydliga, pedagogiska och informativa tooltips på knappar, ikoner och andra interaktiva element.
- Tooltip-texten ska vara kort, tydlig och hjälpa användaren att förstå funktionen.
- Skriv alltid tooltip-texten på svenska om inget annat anges.
- Undvik att upprepa knappens text – förklara istället vad som händer eller varför knappen finns.
- Tooltip aktiveras automatiskt – ingen extra JavaScript behövs i verktygssidorna.
- Exempel: <button class="button" data-tippy-content="Kör verktyget och visa resultatet">Kör</button>
================================================================================
*/
?>

<?php include '../../includes/layout-end.php'; ?>

<!--
======================
OBS: Denna readme-mall laddar ett separat JavaScript
för att rendera markdown-exempel med kopieringsknapp.

js\readme-codecopy.js



Detta script ska bara aktiveras i verktyg som visar kodexempel.

För att aktivera, inkludera i din verktygs-HTML (readme.php), se koden// readme-codecopy.js
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.readme__codecopy').forEach(btn => {
    btn.addEventListener('click', e => {
      const pre = btn.nextElementSibling;
      if (pre && pre.tagName === 'PRE') {
        const code = pre.innerText;
        navigator.clipboard.writeText(code).then(() => {
          btn.title = 'Kopierat!';
          setTimeout(() => btn.title = 'Kopiera kod', 1200);
        });
      }
    });
  });
});

