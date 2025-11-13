<?php
// tools/mall/index.php - v4
$title = 'Mitt Verktyg';
$metaDescription = 'Beskrivning av vad verktyget gör, inklusive syfte, funktion och eventuell koppling till datakälla eller användningsområde.';
include '../../includes/layout-start.php';
?>

<main class="layout__container">
  <h1 class="rubrik">
    <?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>
  </h1>
  <?php $readmePath = 'readme.php'; include '../../includes/readme-icon.php'; ?>

  <!-- ********** START Sektion: Formulär ********** -->
  <form class="form">
    <div class="form__grupp">
      <label for="input1">Exempelinput</label>
      <input type="text" id="input1" class="falt__input" placeholder="Skriv något...">
    </div>

    <div class="form__grupp">
      <label for="input2">Ytterligare input</label>
      <textarea id="input2" class="falt__textarea" placeholder="Kommentarer..."></textarea>
    </div>

    <div class="form__verktyg">
      <button type="button" class="knapp" data-tippy-content="Kör verktyget och visa resultatet">Kör</button>
      <button type="button" class="knapp hidden">Exportera</button>
      <button type="button" class="knapp hidden">Rensa</button>
    </div>
  </form>
  <!-- ********** SLUT Sektion: Formulär ********** -->

  <!-- ********** START Sektion: Resultat ********** -->
  <div class="tabell__wrapper">
    <table class="tabell">
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
  </div>
  <!-- ********** SLUT Sektion: Resultat ********** -->
</main>

<?php include '../../includes/layout-end.php'; ?>
<script src="script.js" defer></script>
// =============================================================================
// 🧠 AI VERKTYGSMALL – RIKTLINJER FÖR LLM & STRUKTUR I MACKAN.EU
// =============================================================================
//
// ✅ ALLMÄNT
// - Använd endast CSS från /css/, inga inline-stilar eller extra CSS.
// - Struktur: Formulär överst, resultat under. Använd <main>, <form>, <table> etc.
// - Sätt $title och $metaDescription högst upp. Endast ett <h1> per sida.
// - Lägg till länk till readme.php med ikon/text.
// - All text på svenska om inget annat anges.
// - Gör sidan responsiv – testa i mobilvy!
// - Använd färdiga CSS-klasser för knappar, formulär, tabeller m.m.
// - Lägg till aria-labels för tillgänglighet.
// - Skriv kort, tydligt, informativt. Undvik utfyllnad.
// - Visa endast relevanta knappar/funktioner. Dölj t.ex. "Exportera" om ej aktuell.
// - Ladda JS-filer med defer. Undvik onödiga script/resurser.
// - Kontrollera att länkar och scriptvägar är relativa och fungerar.
// - Om sidan ej ska indexeras: lägg till rätt meta-taggar i meta.php.
// - Byt ut exempeltexter/titlar mot det som är relevant för verktyget.
// - Lägg till TODO/FIXME-kommentarer där AI:n ska göra val eller anpassning.
// - Tabeller: Lägg till sortering (stigande/fallande) med ikon om möjligt.
// - Dynamiskt innehåll: Använd CSS-stöd för laddningsindikator/spinner.
//
// =================== VANLIGA (GAMLA) KLASSER SOM KAN FÖREKOMMA ===================
// OBS! Dessa används inte längre i ny CSS-struktur men finns kvar i vissa äldre mallar.
// Layout:        .container, .card, .menu-grid, .menu-card
// Formulär:      .form-group, .input, .textarea, .dropdown
// Knappar:       .button, .button-small, .button-large, .danger-button
// Tabeller:      .table, .table-wrapper
// Text:          .info-text, .text-center
// Hjälpklasser:  .hidden, .center, .mt-1, .mb-1, .p-1, .full-width
// Special:       .info-link-floating, .terminal-output, .toast

// =================== TIPS TILL LLM ===================
// - Använd alltid befintliga CSS-klasser – skapa inte egna utan behov.
// - Testa alltid i mobilvy – många layouter är responsiva.
// - Använd .kort för visuella boxar, särskilt på info-sidor.
// - För startsidor/översikter: använd .menykort och .menykort__lank.
// - Använd .info-text för summeringar eller statusrader under tabeller.
// - Använd .toast för popup-meddelanden/feedback till användaren.
// - Använd .terminal-output för kod- eller resultatblock med terminalstil.
// - Dölj element med .hidden istället för att ta bort dem från DOM.
// - Använd .center för flex-centrering av innehåll.
// - Använd .mt-1, .mb-1, .p-1 för snabb marginal/padding.
// - För tabeller: använd .tabell__wrapper om tabellen kan bli bred.
// - Använd <label> för alla formulärfält, kopplat via for/id.
// - Undvik extra script/CSS – använd det som redan finns.
// - Säkerställ tillgänglighet: färgkontrast, fontstorlek, aria-etiketter.
// - Ikoner: använd Font Awesome-klasser som redan laddas in.
// - Kommentera alltid där AI ska ta beslut eller göra val.

// =================== TOOLTIP (Tippy.js) ===================
// - Tippy.js laddas automatiskt via layout-end.php för alla sidor.
// - Alla element med attributet data-tippy-content får automatiskt tooltip.
// - Tooltip ska vara kort, informativ och på svenska.
//   Ex: <button class="knapp" data-tippy-content="Kör verktyget och visa resultatet">Kör</button>
// - Använd på: knappar, ikoner, länkar, tabellrubriker, statusikoner, inställningsreglage m.m.
// - Tooltip aktiveras automatiskt – ingen extra JS behövs.

// =================== NY STRUKTUR – BEM & main.css ===================
// Projektet använder nu strikt BEM-metodik. All CSS definieras i block under /css/,
// och laddas centralt via main.css. Inga globala klassnamn används längre.
//
// ✅ Använd dessa komponenter (alla finns i /css/):
// - Formulär:       .form, .form__grupp, .fält
// - Knappar:        .knapp, .knapp--sekundär, .knapp--fara
// - Tabeller:       .tabell, .tabell__wrapper, [data-label=""]
// - Boxar/layout:   .kort, .kort__rubrik, .rubrik, .verktygsinfo
// - Utilities:      .mt-1, .mb-1, .hidden, .center, .text-center etc.
//
// 🔒 Regler:
// - Använd endast klasser från main.css – inga nya utan godkännande
// - Skapa aldrig egna klassnamn eller lägg till inline-stil
// - Vill du se en CSS-fil för en komponent? 🧑‍💻 Fråga mig så skickar jag den
//
// 📁 Referenser:
// - Huvudfil:        /css/main.css
// - Stilguide:       /css/readme.html
// - Komponenttest:   /css/csstest-komplett.html
//
// ❗ Detta system är AI-vänligt men strikt. Avvikelser orsakar stilbrott och buggar.
// ============================================================================
