<?php
// tools/mall/index.php - v4
$title = 'Mitt Verktyg';
$metaDescription = 'Beskrivning av vad verktyget gör, inklusive syfte, funktion och eventuell koppling till datakälla eller användningsområde.';
include '../../includes/layout-start.php';
?>

<main class="container">
  <h1 class="title">
    <?= $title ?>
      <!--nedan kan strula och generera dubbla titlar på sidan. undersöks närmare-->
    <?php include '../../includes/readme-icon.php'; ?>
  </h1>

  <!-- ********** START Sektion: Formulär ********** -->
  <form class="form-group">
    <div class="form-group">
      <label for="input1">Exempelinput</label>
      <input type="text" id="input1" class="input" placeholder="Skriv något...">
    </div>

    <div class="form-group">
      <label for="input2">Ytterligare input</label>
      <textarea id="input2" class="textarea" placeholder="Kommentarer..."></textarea>
    </div>

    <div class="horizontal-tools">
      <button type="button" class="button" data-tippy-content="Kör verktyget och visa resultatet">Kör</button>
      <button type="button" class="button hidden">Exportera</button>
      <button type="button" class="button hidden">Rensa</button>
    </div>
  </form>
  <!-- ********** SLUT Sektion: Formulär ********** -->

  <!-- ********** START Sektion: Resultat ********** -->
  <table class="table">
    <thead>
      <tr>
        <th>Kolumn 1</th>
        <th>Kolumn 2</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Exempel</td>
        <td>Rad</td>
      </tr>
    </tbody>
  </table>
  <!-- ********** SLUT Sektion: Resultat ********** -->
</main>

<?php include '../../includes/layout-end.php'; ?>
<script src="script.js" defer></script>

// =================== AI VERKTYGSMALL – KORTA TIPS FÖR LLM ===================
// - Använd endast CSS från /css/, inga inline-stilar eller extra CSS.
// - Struktur: Formulär överst, resultat under. Använd <main>, <form>, <table> m.m.
// - Sätt $title och $metaDescription högst upp. Endast ett <h1> per sida.
// - Lägg till länk till readme.php med ikon/text.
// - All text på svenska om inget annat anges.
// - Gör sidan responsiv – testa i mobilvy!
// - Använd färdiga CSS-klasser för knappar, formulär, tabeller, mm.
// - Lägg till aria-labels för tillgänglighet.
// - Skriv kort, tydligt, informativt. Undvik utfyllnad.
// - Visa bara relevanta knappar/funktioner. Dölj t.ex. "Exportera" om ej aktuell.
// - Ladda JS-filer med defer. Undvik onödiga script/resurser.
// - Kontrollera att länkar och scriptvägar är relativa och fungerar.
// - Om sidan ej ska indexeras: rätt meta-taggar i meta.php.
// - Byt ut exempeltexter/titlar mot det som är relevant för verktyget.
// - Lägg till TODO/FIXME-kommentarer där AI:n ska göra val/anpassning.
// - Tabeller: Lägg till sortering (stigande/fallande) med ikon om möjligt.
// - Dynamiskt innehåll: Använd CSS-stöd för laddningsindikator/spinner.
// =================== VANLIGA KLASSER ATT ANVÄNDA ===================
// Layout:        .container, .card, .menu-grid, .menu-card
// Formulär:      .form-group, .input, .textarea, .dropdown
// Knappar:       .button, .button-small, .button-large, .danger-button
// Tabeller:      .table, .table-wrapper
// Text:          .info-text, .text-center
// Hjälpklasser:  .hidden, .center, .mt-1, .mb-1, .p-1, .full-width
// Special:       .info-link-floating, .terminal-output, .toast


// =================== YTTERLIGARE TIPS FÖR LLM ===================
// - Använd alltid befintliga CSS-klasser – skapa inte egna utan behov.
// - Testa alltid i mobilvy – många klasser och layouter är responsiva.
// - Använd .card för avgränsade sektioner, särskilt på readme/info-sidor.
// - För startsidor/översikter: använd .menu-grid och .menu-card för rutnätslayout.
// - Lägg till .info-text för summeringar eller statusrader under tabeller.
// - Använd .toast för popup-meddelanden/feedback till användaren.
// - Lägg till .terminal-output för kod- eller resultatblock med terminalstil.
// - Dölj element med .hidden istället för att ta bort dem från DOM.
// - Använd .center för flexbox-centrering av innehåll.
// - Använd .mt-1, .mb-1, .p-1 för snabb marginal/padding.
// - För tabeller: använd .table-wrapper om tabellen kan bli bredare än skärmen.
// - Lägg till aria-labels på knappar, länkar och formulärfält för tillgänglighet.
// - Använd alltid <label> för inputfält och koppla ihop med id.
// - Om du visar laddningsindikator/spinner, använd projektets CSS-stöd för detta.
// - Undvik att lägga till extra script eller CSS – använd det som redan finns i projektet.
// - Kontrollera att färgkontraster och textstorlekar är tillräckliga för tillgänglighet.
// - Om du använder ikoner, använd Font Awesome-klasser som redan laddas in.
// - Skriv alltid tydliga kommentarer där AI:n ska göra val eller anpassningar.
// ================================================================
?>
?>
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

Lägg till data-label="Kolumnnamn" på varje <td> i tabeller för bästa mobilstöd.
