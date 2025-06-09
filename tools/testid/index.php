<!-- tools/testid/index.php - v4 -->
<?php $title = 'TestID'; ?>
<?php include '../../includes/layout-start.php'; ?>

<main class="container">

  <!-- ********** START Sektion: Formulär ********** -->
  <form class="form-group">
    <div class="form-group">
      <label for="antal">Antal personnummer</label>
      <input type="number" id="antal" class="input" value="10" min="1" max="100">
    </div>

    <div class="form-group">
      <label for="startYear">Från år</label>
      <input type="number" id="startYear" class="input" value="1950" min="1930" max="2025">
    </div>

    <div class="form-group">
      <label for="endYear">Till år</label>
      <input type="number" id="endYear" class="input" value="2020" min="1930" max="2025">
    </div>

    <div class="horizontal-tools">
      <button type="button" class="button" id="generateBtn">Hämta</button>
<div id="loader" class="spinner hidden" aria-label="Laddar"></div>

    </div>
  </form>
  <!-- ********** SLUT Sektion: Formulär ********** -->

  <!-- ********** START Exportmeny ********** -->
  <div id="exportMenu" class="export-menu hidden">
    <button class="button tiny" data-format="json">JSON</button>
    <button class="button tiny" data-format="csv">CSV</button>
    <button class="button tiny" data-format="xlsx">Excel</button>
  </div>
  <!-- ********** SLUT Exportmeny ********** -->

  <!-- ********** START Sektion: Resultat ********** -->
  <table class="table" id="resultTable">
    <thead>
      <tr>
        <th>Personnummer</th>
        <th>Födelsedatum</th>
        <th>Kön</th>
        <th>Ålder</th>
        <th>Giltigt</th>
      </tr>
    </thead>
    <tbody>
      <!-- Dynamiskt innehåll fylls av JS -->
    </tbody>
  </table>
  <!-- ********** SLUT Sektion: Resultat ********** -->

</main>

<?php include '../../includes/layout-end.php'; ?>
<script type="module" src="script.js"></script>
