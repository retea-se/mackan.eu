<?php
// tools/pnr/index.php - v2
$title = 'PNR-verktyg';
$subtitle = 'Verifiera och analysera svenska personnummer';
include '../../includes/layout-start.php';
?>

<main class="container">


  <?php if (!empty($subtitle)): ?>
    <p class="subtitle"><?= $subtitle ?></p>
  <?php endif; ?>

  <textarea id="personnummerList" class="textarea" rows="10" placeholder="Klistra in personnummer här..."></textarea>
  <button class="button" onclick="processPersonnummer()">Bearbeta</button>

  <h2 id="resultsHeader" class="hidden">Resultat:</h2>
  <div id="summary" class="hidden">
    <p>Antal män: <span id="maleCount">0</span> (<span id="malePercentage">0%</span>)</p>
    <p>Antal kvinnor: <span id="femaleCount">0</span> (<span id="femalePercentage">0%</span>)</p>
    <p>Snittålder: <span id="averageAge">0</span> år</p>
  </div>

  <table id="resultsTable" class="table hidden">
    <thead>
      <tr>
        <th>Personnummer</th>
        <th>Validering</th>
        <th>Kommentar</th>
        <th>Kön</th>
        <th>Ålder</th>
        <th>Stjärntecken</th>
        <th>Dagar kvar till födelsedag</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>

  <button id="exportButton" class="button hidden">Exportera</button>
</main>

<?php include '../../includes/layout-end.php'; ?>
<script src="script.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

