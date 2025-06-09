<?php
// =============================================================
// index.php - v7
// feat: tar bort dubbla rubriker och behåller endast titeln från layout
// - Behåller info-ikon separat
// - Titel skrivs nu bara ut via layout/header.php
// =============================================================

$title = 'Lösenordsgenerator';
$metaDescription = 'Generera säkra och anpassade lösenord direkt i webbläsaren med kontroll över teckentyper, längd och antal.';
include '../../includes/layout-start.php';
?>

<main class="container">

  <!-- ********** START Sektion: Förhandslösenord ********** -->
  <div class="form-group">
    <div class="center" style="gap: 0.75rem; flex-wrap: wrap;">
      <h2 id="previewDisplay" class="mono" style="font-weight: 600;"></h2>
      <div>
        <button id="regenPreview" class="icon-button" aria-label="Generera nytt lösenord" data-tippy-content="Generera nytt lösenord"><i class="fa-solid fa-rotate-right"></i></button>
        <button id="copyPreview" class="icon-button" aria-label="Kopiera lösenord" data-tippy-content="Kopiera lösenord"><i class="fa-solid fa-copy"></i></button>
      </div>
    </div>
  </div>
  <!--<?php include '../../includes/readme-icon.php'; ?>-->
  <!-- ********** SLUT Sektion: Förhandslösenord ********** -->

  <!-- ********** START Sektion: Formulär ********** -->
  <form id="generatorForm" class="form-group">
    <div class="form-group">
      <label for="length" data-tippy-content="Hur många tecken varje lösenord ska innehålla. Rekommenderat: minst 12.">Lösenordslängd (4–128)</label>
      <input type="number" id="length" class="input" min="4" max="128" value="24" aria-label="Lösenordslängd">
    </div>

    <div class="form-group">
      <label for="amount" data-tippy-content="Hur många lösenord du vill generera på en gång.">Antal lösenord</label>
      <input type="number" id="amount" class="input" min="1" max="100" value="1" aria-label="Antal lösenord">
    </div>

    <div class="form-group" data-tippy-content="Välj vilka typer av tecken som ska användas i lösenordet.">
      <label class="checkbox"><input type="checkbox" id="useLower" checked> Små bokstäver (a–z)</label>
      <label class="checkbox"><input type="checkbox" id="useUpper" checked> Stora bokstäver (A–Z)</label>
      <label class="checkbox"><input type="checkbox" id="useNumbers" checked> Siffror (0–9)</label>
      <label class="checkbox"><input type="checkbox" id="useSymbols" checked> Specialtecken (!@#...)</label>
    </div>

    <div class="horizontal-tools">
      <button type="submit" class="button" data-tippy-content="Klicka för att generera lösenord med aktuella inställningar.">Generera</button>
      <button type="button" id="exportBtn" class="button hidden" data-tippy-content="Exportera lösenord till fil (txt, csv, json).">Exportera</button>
      <button type="button" id="resetBtn" class="button hidden" data-tippy-content="Töm resultatlistan och börja om.">Rensa</button>
    </div>
  </form>
  <!-- ********** SLUT Sektion: Formulär ********** -->

  <!-- ********** START Sektion: Resultat ********** -->
  <table class="table mt-1" id="resultTable">
    <thead>
      <tr>
        <th data-tippy-content="Här visas varje genererat lösenord tillsammans med dess styrka.">Lösenord</th>
        <th data-tippy-content="Tryck på knappen för att kopiera lösenordet till urklipp.">Kopiera</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>
  <!-- ********** SLUT Sektion: Resultat ********** -->
</main>

<?php include '../../includes/layout-end.php'; ?>
<script src="script.js" defer></script>
<script src="export.js" defer></script>
<script src="preview.js" defer></script>
