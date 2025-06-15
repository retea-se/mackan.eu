<!-- tools/aptus/index.php - v1 -->
<?php
$title = 'Hex till Dec Konverterare';
$metaDescription = 'Konvertera hexadecimala Aptus-värden till decimalform. Klistra in värden, få resultat och exportera till CSV. Enkel och snabb konvertering.';
?>
<?php include '../../includes/layout-start.php'; ?>




  <form id="converter-form" class="form__grupp">
    <textarea id="hexInput" class="falt__input" placeholder="Klistra in hexadecimala värden här, ett per rad"></textarea>

    <div class="form__verktyg"><!-- TODO: osäker konvertering -->
      <button type="button" class="knapp" id="convertButton" onclick="convertHex()">Konvertera</button>
      <button type="button" class="knapp utils--dold" id="exportButton" onclick="exportToCSV()">Exportera till CSV</button>
      <button type="button" class="knapp utils--dold" id="clearButton" onclick="clearResults()">Rensa</button>
    </div>
  </form>

<div class="form__verktyg"><!-- TODO: osäker konvertering -->
  <a href="readme.php" class="ikon--info-flytande" title="Om verktyget">
    <i class="fa-solid fa-circle-info"></i>
  </a>
</div>

  <table id="resultTable" class="tabell">
    <thead>
      <tr>
        <th>Original EM</th>
        <th>Aptus</th>
      </tr>
    </thead>
    <tbody id="resultBody"></tbody>
  </table>
</main>

<?php include '../../includes/layout-end.php'; ?>
<script src="script.js" defer></script>
