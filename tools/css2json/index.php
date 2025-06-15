<?php

// tools/css2json/index.php - v4
$title = 'CSS till JSON';
$metaDescription = 'Konvertera en eller flera CSS-filer till JSON-format direkt i webbläsaren.';
include '../../includes/layout-start.php';
?>


  <!-- ********** START Sektion: Formulär ********** -->
  <form class="form" aria-label="CSS till JSON-formulär">
    <div class="form__grupp">
      <label for="cssFiles" class="falt__etikett">Välj CSS-fil(er)</label>
      <input type="file" id="cssFiles" class="falt__input" multiple accept=".css" aria-label="Ladda upp en eller flera CSS-filer" data-tippy-content="Välj en eller flera CSS-filer att konvertera till JSON">
    </div>
    <div class="form__verktyg">
      <button type="button" class="knapp" id="convertBtn" data-tippy-content="Konvertera valda CSS-filer till JSON och visa resultatet" aria-label="Konvertera">Konvertera</button>
      <button type="button" class="knapp utils--dold" id="downloadBtn" data-tippy-content="Ladda ner resultatet som JSON-fil" aria-label="Ladda ner JSON" disabled>Ladda ner JSON</button>
      <button type="button" class="knapp utils--dold" id="resetBtn" data-tippy-content="Rensa uppladdade filer och resultat" aria-label="Rensa">Rensa</button>
    </div>
  </form>
  <!-- ********** SLUT Sektion: Formulär ********** -->

  <!-- ********** START Sektion: Resultat ********** -->
  <div class="utils--mt-1">
    <pre id="output" class="kort__terminal" aria-label="JSON-resultat"></pre>
  </div>
  <!-- ********** SLUT Sektion: Resultat ********** -->
</main>

<?php include '../../includes/layout-end.php'; ?>
<script src="script.js" defer></script>
