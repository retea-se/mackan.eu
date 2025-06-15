<!-- tools/mall/readme.php - v4 -->
<?php
$title = 'Om Mitt Verktyg';
$metaDescription = 'Information om detta verktyg, dess syfte och funktion. Lär dig hur du använder det med exempel och tydlig förklaring.';
?>
<?php include '../../includes/layout-start.php'; ?>

<?php

/*
  OBS! Använd INTE nedanstående stycke:
  Titeln ($title) inkluderas redan automatiskt via header/layout-start.php.
  Om du lägger till detta manuellt riskerar du dubbla <h1>-element på sidan,
  vilket försämrar både SEO och tillgänglighet.
  Kommentera därför bort eller ta bort hela blocket nedan.
*/
/*
<main class="container">
  <h1 class="title">
    <?= $title ?>
      <!--nedan kan strula och generera dubbla titlar på sidan. undersöks närmare-->
    <?php include '../../includes/readme-icon.php'; ?>
  </h1>
  <article class="card readme">
    <h2>Syfte</h2>
    <p>Detta verktyg är skapat för att demonstrera mallstruktur och gemensam designstandard i projektet.</p>

    <h2>Funktioner</h2>
    <ul>
      <li>Inputfält för att skriva in data</li>
      <li>Knappar för att köra, exportera och rensa</li>
      <li>Responsiv tabell som visar resultat</li>
    </ul>

    <h2>Användning</h2>
    <p>Fyll i fälten och klicka på <strong>Kör</strong> för att se resultatet nedan. Knappen <strong>Exportera</strong> visar sig vid behov.</p>

    <h2>Exempel</h2>
    <pre class="terminal-output">
Input1: Test
Input2: Kommentar

Resultat:
Kolumn 1 | Kolumn 2
---------|---------
Test     | Rad
    </pre>

    <!--
      START: Markdown-exempel med kopieringsfunktion.
      Denna sektion visar snyggt formaterade kodexempel i markdownformat
      och kan kopieras till urklipp med en knapp.

      OBS! Om verktyget inte använder kodexempel i readme kan denna sektion
      tas bort eller kommenteras ut för att undvika onödig kod och laddning.
    -->
    <section id="markdownExampleSection" class="markdown-example-section">
      <h2>Kodexempel</h2>
      <div id="markdownExampleContainer"></div>
    </section>

  </article>
</main>

<?php include '../../includes/layout-end.php'; ?>

<!--
======================
OBS: Denna readme-mall laddar ett separat JavaScript
för att rendera markdown-exempel med kopieringsknapp.

Detta script ska bara aktiveras i verktyg som visar kodexempel.

För att aktivera, inkludera i din verktygs-HTML (readme.php) t.ex.:

<script type="module">
  import { renderMarkdownExample } from '/js/markdownExample.js';

  const exampleCode = `
\`\`\`json
{
  "nyckel": "värde"
}
\`\`\`
  `;

  renderMarkdownExample('markdownExampleContainer', exampleCode);
</script>

<!--
AI README-GENERERING – TIPS FÖR LLM:

- Sätt alltid variablerna `$title` och `$metaDescription` högst upp i filen.
- Använd endast ETT `<h1>`-element per sida, och låt det spegla `$title`.
- Om `includes/layout-start.php` används, undvik att lägga till ytterligare `<h1>` i innehållet.
- Beskriv syfte, funktioner och användning tydligt i separata sektioner med `<h2>`.
- Lägg till ett konkret exempel på användning/resultat i en `<pre>`-tagg.
- Om kodexempel ska visas, inkludera sektionen `markdownExampleSection` och visa ett relevant kodblock.
- Ta bort eller kommentera ut kodexempelsektionen om den inte behövs för verktyget.
- Skriv korta, informativa texter – undvik utfyllnad.
- Följ SEO-checklistan i slutet av mallen.
- Använd semantiska HTML-element: `<main>`, `<article>`, `<section>`, `<h2>`, osv.
- Lägg till en länk tillbaka till verktyget (t.ex. index.php) med tydlig ankartext.
- Skriv all text på svenska om inte annat anges.
- Lägg till versionskommentar i toppen av filen.
- Skriv kommentarer i koden där AI:n behöver göra val eller anpassningar.
- Om sektionen "Kodexempel" används:
  Se till att `js/markdownExample.js` finns i projektets `js`-mapp och importeras enligt instruktionen i mallen.

Ytterligare tips för bättre resultat:

- Byt ut exempeltexter, titlar och beskrivningar mot det som är relevant för det aktuella verktyget.
- Kontrollera att rubriknivåerna är logiska och inte hoppar (t.ex. <h2> direkt efter <h1>).
- Om du inte vill visa titeln via includes/title.php, sätt `$hideTitle = true;` innan du inkluderar layout-start.php.
- Om sidan inte ska indexeras av sökmotorer, säkerställ att rätt meta-taggar finns i meta.php.
- Om du visar kodexempel, se till att kodblockets språk (t.ex. ```json) är korrekt för syntaxhighlighting.
- Lägg till tydliga TODO- eller FIXME-kommentarer där AI:n behöver göra val eller anpassningar.
- Undvik att lägga till onödiga script eller CSS om de inte används på sidan.
- Kontrollera att alla länkar och scriptvägar är relativa och fungerar i projektets struktur.

<?php
// =================== INSTRUKTION FÖR TOOLTIP (Tippy.js) ===================
// - Tippy.js laddas automatiskt in via layout-end.php för alla sidor.
// - Alla element med attributet data-tippy-content får automatiskt en tooltip.
// - Lägg alltid till tydliga, pedagogiska och informativa tooltips på knappar, ikoner och andra interaktiva element.
//   Exempel: <button class="button" data-tippy-content="Kör verktyget och visa resultatet">Kör</button>
// - Tooltip-texten ska vara kort, tydlig och hjälpa användaren att förstå funktionen.
// - Skriv alltid tooltip-texten på svenska om inget annat anges.
// - Undvik att upprepa knappens text – förklara istället vad som händer eller varför knappen finns.
// - Kontrollera att alla knappar och viktiga ikoner har en relevant tooltip.
// - Tooltip aktiveras automatiskt – ingen extra JavaScript behövs i verktygssidorna.
// ===========================================================================

Tips:
Använd Tippy på alla interaktiva eller otydliga element där användaren kan behöva extra förklaring eller hjälp.
Du kan använda Tippy.js på många typer av element, inte bara knappar. Några vanliga exempel där Tippy är lämpligt:

Ikoner (t.ex. informations-, hjälp- eller varningsikoner)
Länkar (för att förklara vart länken leder eller vad som händer)
Formulärfält (inputs, textareas, dropdowns – för att ge tips eller instruktioner)
Tabellrubriker (för att förklara kolumnens innehåll)
Statusindikatorer (t.ex. färgade prickar, badges)
Bilder (för att visa bildbeskrivning eller extra info)
Listobjekt (för att ge mer kontext om ett alternativ)
Inställningsreglage (switchar, sliders, radioknappar)
Navigationsmenyer (för att förklara menyval)
-->
<?php
<!--
======================
AI README-INSTRUKTION
======================

När du genererar eller uppdaterar denna README, tänk på följande:

1. **Syfte och mål**
   - Beskriv kortfattat varför verktyget finns och vilket problem det löser.
   - Ange målgruppen (t.ex. utvecklare, tekniker, nybörjare).

2. **Hur verktyget fungerar**
   - Förklara huvudfunktionerna och arbetsflödet steg för steg.
   - Ge exempel på typiska användningsfall.
   - Om relevant: visa input → output med konkreta exempel.

3. **Teknisk information och källor**
   - Lista eventuella externa bibliotek, API:er eller algoritmer som används.
   - Ange källor eller inspiration om det är relevant (t.ex. Wikipedia, RFC, MDN).

4. **Nördiga detaljer och extra data**
   - Lägg gärna till fun facts, prestandadata, edge cases eller historik om tekniken.
   - Om verktyget har unika egenskaper, lyft fram dem.

5. **Struktur och stil**
   - Använd semantiska HTML-element och tydliga rubriker.
   - Håll texten kort, informativ och på svenska.
   - Lägg till kodexempel där det är relevant.
   - Lägg till tips eller varningar om det finns fallgropar.

6. **SEO och tillgänglighet**
   - Sätt alltid `$title` och `$metaDescription` högst upp.
   - Använd bara ett `<h1>` per sida.
   - Lägg till tooltips på knappar och ikoner.

7. **Anpassning**
   - Byt ut exempel och texter mot det som är relevant för just detta verktyg.
   - Lägg till eller ta bort sektioner efter behov.

-->
