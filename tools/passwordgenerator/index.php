<!-- index.php - v10 -->
<?php
$title = 'Lösenordsgenerator';
$metaDescription = 'Generera säkra lösenord och ordfraser direkt i webbläsaren med olika inställningar och exportfunktion.';
?>
<?php include '../../includes/layout-start.php'; ?>

<main class="container">


  <!-- ********** START Sektion: Förhandslösenord ********** -->
  <section class="preview-section">
    <h2 class="preview-password-row">
      <span id="previewText">Förhandslösenord</span>
      <span class="preview-actions">
        <button id="previewRefresh" class="icon-button" aria-label="Generera nytt lösenord" data-tippy-content="Generera nytt lösenord">
          <i class="fa-solid fa-rotate"></i>
        </button>
        <button id="previewCopy" class="icon-button" aria-label="Kopiera lösenord" data-tippy-content="Kopiera lösenord">
          <i class="fa-solid fa-copy"></i>
        </button>
      </span>
    </h2>
  </section>
  <!-- ********** SLUT Sektion: Förhandslösenord ********** -->

  <!-- ********** START Sektion: Formulär ********** -->
  <form id="generatorForm" class="form-group">
    <label for="length">Lösenordslängd (4–128)</label>
    <input type="number" id="length" class="input" min="4" max="128" value="20">

    <label for="amount">Antal att generera</label>
    <input type="number" id="amount" class="input" min="1" max="100" value="5">

    <label><input type="checkbox" id="useLower" checked> Små bokstäver</label>
    <label><input type="checkbox" id="useUpper" checked> Versaler</label>
    <label><input type="checkbox" id="useNumbers" checked> Siffror</label>
    <label><input type="checkbox" id="useSymbols" checked> Symboler</label>
    <label><input type="checkbox" id="usePassphrase"> Använd ordfras</label>

    <button type="submit" class="button" data-tippy-content="Generera nya lösenord">Generera</button>
  </form>
  <!-- ********** SLUT Sektion: Formulär ********** -->

  <!-- ********** START Sektion: Resultattabell ********** -->
  <table class="table" id="resultTable">
    <thead>
      <tr><th>Lösenord</th><th>Åtgärd</th></tr>
    </thead>
    <tbody></tbody>
  </table>
  <button id="exportBtn" class="button hidden" data-tippy-content="Exportera till fil">Exportera</button>
  <button id="resetBtn" class="button hidden" data-tippy-content="Rensa resultat">Rensa</button>
  <!-- ********** SLUT Sektion: Resultattabell ********** -->
</main>

<!-- ********** START Sektion: Scripts ********** -->
<script src="script.js"></script>
<script src="preview.js"></script>
<script src="passphrase.js"></script>
<script src="export.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    if (window.tippy) {
      tippy('[data-tippy-content]', {
        theme: 'light',
        delay: [100, 0],
      });
    }
  });
</script>
<!-- ********** SLUT Sektion: Scripts ********** -->

<?php include '../../includes/layout-end.php'; ?>
