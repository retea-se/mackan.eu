<?php
$title = 'Lösenordsgenerator';
$metaDescription = 'Generera säkra och anpassade lösenord direkt i webbläsaren med kontroll över teckentyper, längd och antal.';
include '../../includes/layout-start.php';
?>

<main class="container">
  <h1 class="title" data-tippy-content="Skapa ett eller flera säkra lösenord med full kontroll på längd och innehåll.">
    <?= $title ?>
    <?php include '../../includes/readme-icon.php'; ?>
  </h1>

  <!-- ********** START Sektion: Formulär ********** -->
  <form id="generatorForm" class="form-group">
    <div class="form-group">
      <label for="length" data-tippy-content="Hur många tecken varje lösenord ska innehålla. Rekommenderat: minst 12.">Lösenordslängd (4–128)</label>
      <input type="number" id="length" class="input" min="4" max="128" value="16" aria-label="Lösenordslängd">
    </div>

    <div class="form-group">
      <label for="amount" data-tippy-content="Hur många unika lösenord du vill generera samtidigt.">Antal lösenord</label>
      <input type="number" id="amount" class="input" min="1" max="100" value="1" aria-label="Antal lösenord">
    </div>

    <div class="form-group" data-tippy-content="Välj vilka typer av tecken som ska kunna användas. Minst en måste vara vald.">
      <label class="checkbox"><input type="checkbox" id="useLower" checked> Små bokstäver (a–z)</label>
      <label class="checkbox"><input type="checkbox" id="useUpper" checked> Stora bokstäver (A–Z)</label>
      <label class="checkbox"><input type="checkbox" id="useNumbers" checked> Siffror (0–9)</label>
      <label class="checkbox"><input type="checkbox" id="useSymbols"> Specialtecken (!@#...)</label>
    </div>

    <div class="horizontal-tools">
      <button type="submit" class="button" data-tippy-content="Klicka för att skapa lösenord enligt dina val.">Generera</button>
      <button type="button" id="exportBtn" class="button hidden" data-tippy-content="Ladda ner lösenorden i olika format (txt, csv, json).">Exportera</button>
      <button type="button" id="resetBtn" class="button hidden" data-tippy-content="Töm resultatlistan och börja om.">Rensa</button>
    </div>
  </form>
  <!-- ********** SLUT Sektion: Formulär ********** -->

  <!-- ********** START Sektion: Resultat ********** -->
  <table class="table mt-1" id="resultTable">
    <thead>
      <tr>
        <th data-tippy-content="Ditt genererade lösenord visas här. Styrkan anges med färg: röd = svag, gul = medel, grön = stark.">Lösenord</th>
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
