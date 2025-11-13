<?php
// tools/pnr/index.php - v2
$title = 'PNR-verktyg';
$subtitle = 'Verifiera och analysera svenska personnummer';
include '../../includes/layout-start.php';
?>

<main class="layout__container">

  <header class="layout__sektion text--center">
    <h1 class="rubrik rubrik--sektion">
      <?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>
    </h1>
    <?php if (!empty($subtitle)): ?>
      <p class="text--lead"><?= htmlspecialchars($subtitle, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>
  </header>

  <section class="layout__sektion">
    <form class="form" id="pnrForm" novalidate>
      <div class="form__grupp">
        <label for="personnummerList" class="falt__etikett">Personnummer</label>
        <textarea id="personnummerList" class="falt__textarea" rows="10" placeholder="Klistra in personnummer här..." data-tippy-content="Klistra in personnummer här"></textarea>
      </div>
      <div class="form__verktyg">
        <button type="submit" class="knapp" id="processBtn" data-tippy-content="Bearbeta personnummer">Bearbeta</button>
      </div>
    </form>
  </section>

  <section class="layout__sektion hidden" id="resultSection">
    <h2 id="resultsHeader" class="rubrik rubrik--underrubrik">Resultat</h2>
    <div id="summary" class="kort">
      <div class="kort__innehall">
        <p>Antal män: <span id="maleCount">0</span> (<span id="malePercentage">0%</span>)</p>
        <p>Antal kvinnor: <span id="femaleCount">0</span> (<span id="femalePercentage">0%</span>)</p>
        <p>Snittålder: <span id="averageAge">0</span> år</p>
      </div>
    </div>

    <div class="tabell__wrapper">
      <table id="resultsTable" class="tabell hidden">
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
    </div>

    <div class="knapp__grupp hidden" id="exportWrapper">
      <button id="exportButton" class="knapp" data-tippy-content="Exporterar resultat">Exportera</button>
    </div>
  </section>

</main>

<?php include '../../includes/layout-end.php'; ?>
<script src="script.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

