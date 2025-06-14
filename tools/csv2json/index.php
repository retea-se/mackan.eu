<!-- tools/csv2json/index.php - v1 -->
<?php $title = 'CSV till JSON v1'; ?>
<?php include '../../includes/layout-start.php'; ?>

<main class="layout__container">

  <section>
    <h2>Ladda upp CSV-data</h2>
    <input type="file" id="csvFileInput" class="falt__input" accept=".csv">
    <button class="knapp" onclick="handleFileUpload()">Läs in fil</button>
    <textarea id="csvInput" class="falt__textarea" placeholder="Klistra in din CSV-data här..."></textarea>
  </section>

  <section>
    <h2>Statistik</h2>
    <p id="csvStats">Statistik för din CSV-fil kommer att visas här.</p>
  </section>

  <section>
    <h2>Verktyg</h2>
    <div id="columnFilter" class="kort"></div>
    <div class="horizontal-tools"><!-- TODO: osäker konvertering -->
      <label class="checkbox"><!-- TODO: osäker konvertering -->
        <input type="checkbox" id="minifyCheckbox"> Kompakt JSON
      </label>
      <label class="checkbox"><!-- TODO: osäker konvertering -->
        <input type="checkbox" id="transposeCheckbox"> Transponera
      </label>
    </div>
  </section>

  <section>
    <h2>Förhandsgranskning</h2>
    <pre id="livePreview" class="kort__terminal"></pre>
  </section>

  <section>
    <h2>Konverterad JSON</h2>
    <div id="jsonOutput" class="kort"></div>
    <div class="horizontal-tools"><!-- TODO: osäker konvertering -->
      <button class="knapp" onclick="downloadJson()">Ladda ner JSON</button>
      <button class="knapp" onclick="copyToClipboard()">Kopiera till urklipp</button>
    </div>
  </section>
</main>

<?php include '../../includes/layout-end.php'; ?>
<script src="script.js" defer></script>
<script src="tools.js" defer></script>
