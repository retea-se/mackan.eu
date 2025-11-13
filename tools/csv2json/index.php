<!-- tools/csv2json/index.php - v1 -->
<?php $title = 'CSV till JSON v1'; ?>
<?php include '../../includes/layout-start.php'; ?>

<main class="layout__container">

  <header class="layout__sektion text--center">
    <h1 class="rubrik rubrik--sektion">
      <?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>
    </h1>
    <?php $readmePath = 'readme.php'; include '../../includes/readme-icon.php'; ?>
  </header>

  <section class="layout__sektion">
    <h2 class="rubrik rubrik--underrubrik">Ladda upp eller klistra in CSV-data</h2>
    <form id="csvForm" class="form" autocomplete="off">
      <div class="form__grupp">
        <label for="csvFileInput" class="falt__etikett">Välj CSV-fil</label>
        <input type="file" id="csvFileInput" class="falt__input" accept=".csv">
      </div>
      <div class="form__verktyg">
        <button type="button" class="knapp" id="readFileBtn">Läs in fil</button>
      </div>
      <div class="form__grupp">
        <label for="csvInput" class="falt__etikett">Eller klistra in CSV-data</label>
        <textarea id="csvInput" class="falt__textarea" rows="10" placeholder="Klistra in din CSV-data här..."></textarea>
      </div>
    </form>
  </section>

  <section class="layout__sektion">
    <h2 class="rubrik rubrik--underrubrik">Statistik</h2>
    <p id="csvStats">Statistik för din CSV-fil kommer att visas här.</p>
  </section>

  <section class="layout__sektion">
    <h2 class="rubrik rubrik--underrubrik">Verktyg</h2>
    <div id="columnFilter" class="kort"></div>
    <div class="form__verktyg">
      <label class="falt__checkbox">
        <input type="checkbox" id="minifyCheckbox"> Kompakt JSON
      </label>
      <label class="falt__checkbox">
        <input type="checkbox" id="transposeCheckbox"> Transponera
      </label>
    </div>
  </section>

  <section class="layout__sektion">
    <h2 class="rubrik rubrik--underrubrik">Förhandsgranskning</h2>
    <pre id="livePreview" class="kort__terminal"></pre>
  </section>

  <section class="layout__sektion">
    <h2 class="rubrik rubrik--underrubrik">Konverterad JSON</h2>
    <div id="jsonOutput" class="kort"></div>
    <div class="form__verktyg">
      <button class="knapp" id="downloadJsonBtn">Ladda ner JSON</button>
      <button class="knapp" id="copyJsonBtn">Kopiera till urklipp</button>
    </div>
  </section>
</main>

<?php include '../../includes/layout-end.php'; ?>
<script src="script.js" defer></script>
<script src="tools.js" defer></script>
