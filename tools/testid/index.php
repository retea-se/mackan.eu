<!-- tools/testid/index.php - v4 -->
<?php $title = 'TestID'; ?>
<?php include '../../includes/layout-start.php'; ?>

<main class="layout__container">

  <!-- ********** START Sektion: Formulär ********** -->
  <form class="form__grupp">
    <div class="form__grupp">
      <label for="antal">Antal personnummer</label>
      <input type="number" id="antal" class="falt__input" value="10" min="1" max="100">
    </div>

    <div class="form__grupp">
      <label for="startYear">Från år</label>
      <input type="number" id="startYear" class="falt__input" value="1950" min="1930" max="2025">
    </div>

    <div class="form__grupp">
      <label for="endYear">Till år</label>
      <input type="number" id="endYear" class="falt__input" value="2020" min="1930" max="2025">
    </div>

    <div class="horizontal-tools"><!-- TODO: osäker konvertering -->
      <button type="button" class="knapp" id="generateBtn">Hämta</button>
      <div id="loader" class="spinner utils--dold" aria-label="Laddar"></div>
    </div>
  </form>
  <!-- ********** SLUT Sektion: Formulär ********** -->

  <!-- ********** START Exportmeny ********** -->
  <div id="exportMenu" class="export-menu utils--dold"><!-- TODO: osäker konvertering: export-menu -->
    <button class="knapp knapp--liten" data-format="json">JSON</button>
    <button class="knapp knapp--liten" data-format="csv">CSV</button>
    <button class="knapp knapp--liten" data-format="xlsx">Excel</button>
  </div>
  <!-- ********** SLUT Exportmeny ********** -->

  <!-- ********** START Sektion: Resultat ********** -->
  <table class="tabell" id="resultTable">
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
