<?php
$title = 'Lösenordsgenerator';
$metaDescription = 'Generera säkra och anpassade lösenord direkt i webbläsaren med kontroll över teckentyper, längd och antal.';
include '../../includes/layout-start.php';
?>

<main class="container">
  <h1 class="title">
    <?= $title ?>
    <?php include '../../includes/readme-icon.php'; ?>
  </h1>

  <!-- ********** START Sektion: Formulär ********** -->
  <form id="generatorForm" class="form-group">
    <div class="form-group">
      <label for="length">Lösenordslängd (4–128)</label>
      <input type="number" id="length" class="input" min="4" max="128" value="16" aria-label="Lösenordslängd">
    </div>

    <div class="form-group">
      <label for="amount">Antal lösenord</label>
      <input type="number" id="amount" class="input" min="1" max="100" value="1" aria-label="Antal lösenord">
    </div>

    <div class="form-group">
      <label class="checkbox"><input type="checkbox" id="useLower" checked> Små bokstäver (a–z)</label>
      <label class="checkbox"><input type="checkbox" id="useUpper" checked> Stora bokstäver (A–Z)</label>
      <label class="checkbox"><input type="checkbox" id="useNumbers" checked> Siffror (0–9)</label>
      <label class="checkbox"><input type="checkbox" id="useSymbols"> Specialtecken (!@#...)</label>
    </div>

    <div class="horizontal-tools">
      <button type="submit" class="button" data-tippy-content="Generera lösenord enligt dina val">Generera</button>
      <button type="button" id="exportBtn" class="button hidden" data-tippy-content="Exportera genererade lösenord">Exportera</button>
      <button type="button" id="resetBtn" class="button hidden" data-tippy-content="Rensa resultatlistan">Rensa</button>
    </div>
  </form>
  <!-- ********** SLUT Sektion: Formulär ********** -->

  <!-- ********** START Sektion: Resultat ********** -->
  <table class="table mt-1" id="resultTable">
    <thead>
      <tr>
        <th data-tippy-content="Lösenordet som genererats">Lösenord</th>
        <th data-tippy-content="Klicka för att kopiera">Kopiera</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>
  <!-- ********** SLUT Sektion: Resultat ********** -->
</main>

<?php include '../../includes/layout-end.php'; ?>
<script src="script.js" defer></script>
<script src="export.js" defer></script>
