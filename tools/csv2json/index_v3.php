<?php $title = 'CSV 2 JSON v3'; ?>
<?php include '../../includes/layout-start.php'; ?>

<main class="layout__container">

  <!-- ********** START Sektion ********** -->
  <section class="form__grupp">
    <h2>1. Ladda upp din fil</h2>
    <p>Välj en CSV-, JSON- eller Excel-fil för att starta konverteringen.</p>
    <input type="file" id="fileInput" class="falt__input">
  </section>

  <section class="form__grupp">
    <h2>2. Välj konverteringsformat</h2>
    <label for="outputFormat">Konvertera till:</label>
    <select id="outputFormat" class="falt__select"><!-- TODO: osäker konvertering: dropdown -->
      <option value="csv">CSV</option>
      <option value="json">JSON</option>
      <option value="xlsx">Excel (xlsx)</option>
    </select>
    <button class="knapp" onclick="konvertera()">Konvertera</button>
  </section>

  <section>
    <h2>3. Förhandsgranskning</h2>
    <p>Granska datan nedan. Sortera och paginera enkelt i tabellen.</p>
    <div id="preview">
      <table id="dataTable" class="tabell tabell--display" style="width:100%"><!-- TODO: osäker konvertering: display -->
      </table>
      <div id="chartContainer" class="layout__diagram"><!-- TODO: osäker konvertering: chart-container -->
        <canvas id="myChart"></canvas>
      </div>
    </div>
  </section>

  <section>
    <h2>4. Ladda ner den konverterade filen</h2>
    <button id="exportButton" class="knapp utils--dold" onclick="exportera()">Exportera</button>
  </section>

  <section class="form__grupp">
    <h3>Statistik för filen</h3>
    <div id="statistik" class="kort"></div>
  </section>
  <!-- ********** SLUT Sektion ********** -->
</main>

<?php include '../../includes/layout-end.php'; ?>

<!-- ********** START Skript ********** -->
<script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.4.1/papaparse.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="avancerad2.js" defer></script>
<!-- ********** SLUT Skript ********** -->
