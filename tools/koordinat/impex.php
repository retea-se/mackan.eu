<?php
// tools/koordinat/impex.php - v5 (Ny mallstruktur)
$title = 'Avancerad Koordinathantering';
$metaDescription = 'Importera och konvertera stora mängder koordinater mellan WGS84, SWEREF99, RT90. Stöder CSV-import, batch-bearbetning och export till olika format.';
$metaKeywords = 'batch koordinatkonvertering, CSV import, koordinat export, WGS84, SWEREF99, RT90, GIS batch';
$canonical = 'https://mackan.eu/tools/koordinat/impex.php';

include '../../includes/layout-start.php';
?>

<main class="layout__container">
  <header class="layout__sektion text--center">
    <h1 class="rubrik rubrik--sektion">
      <?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>
    </h1>
    <p class="text--lead">
      Importera stora mängder koordinater från CSV-fil eller klistra in text. Konvertera mellan olika koordinatsystem och exportera resultatet.
    </p>
  </header>

  <!-- ********** START Sektion: Import ********** -->
  <section class="layout__sektion">
    <div class="kort">
      <h2 class="kort__rubrik">Import av koordinater</h2>

      <form class="form" novalidate>
        <div class="form__grupp">
          <label for="file-input" class="falt__etikett">Välj CSV-fil:</label>
          <input type="file" id="file-input" class="falt__input" accept=".csv,.txt" data-tippy-content="Ladda upp en CSV-fil med koordinater">
        </div>

        <div class="form__grupp">
          <label for="text-input" class="falt__etikett">Eller klistra in koordinater:</label>
          <textarea id="text-input" class="falt__textarea" rows="6" placeholder="Klistra in koordinater här, en per rad eller separerade med komma..."></textarea>
        </div>

        <div class="form__grupp">
          <label for="input-format" class="falt__etikett">Källformat:</label>
          <select id="input-format" class="falt__dropdown">
            <option value="auto">Automatisk detektering</option>
            <option value="wgs84">WGS84 (lat, lon)</option>
            <option value="sweref99">SWEREF99 TM</option>
            <option value="rt90">RT90</option>
          </select>
        </div>

        <div class="form__verktyg">
          <button type="button" id="import-btn" class="knapp" data-tippy-content="Importera och bearbeta koordinaterna">Importera</button>
          <button type="button" id="clear-import" class="knapp knapp--sekundär" data-tippy-content="Rensa alla fält">Rensa</button>
        </div>
      </form>
    </div>
  </section>
  <!-- ********** SLUT Sektion: Import ********** -->

  <!-- ********** START Sektion: Bearbetning ********** -->
  <section class="layout__sektion hidden" id="processing-section">
    <div class="kort">
      <h2 class="kort__rubrik">Bearbetningsalternativ</h2>

      <form class="form" novalidate>
        <div class="form__grupp">
          <span class="falt__etikett">Utdataformat:</span>
          <div class="flex-column">
            <label class="falt__checkbox"><input type="checkbox" id="format-wgs84" checked> WGS84 (lat, lon)</label>
            <label class="falt__checkbox"><input type="checkbox" id="format-sweref99"> SWEREF99 TM</label>
            <label class="falt__checkbox"><input type="checkbox" id="format-rt90"> RT90</label>
            <label class="falt__checkbox"><input type="checkbox" id="format-utm"> UTM</label>
          </div>
        </div>

        <div class="form__grupp">
          <label class="falt__checkbox"><input type="checkbox" id="include-elevation"> Inkludera höjddata</label>
        </div>

        <div class="form__verktyg">
          <button type="button" id="process-btn" class="knapp" data-tippy-content="Konvertera alla koordinater enligt valda format">Konvertera alla</button>
          <button type="button" id="export-csv" class="knapp knapp--sekundär hidden" data-tippy-content="Exportera resultatet som CSV-fil">Exportera CSV</button>
          <button type="button" id="export-json" class="knapp knapp--sekundär hidden" data-tippy-content="Exportera resultatet som JSON-fil">Exportera JSON</button>
        </div>
      </form>
    </div>
  </section>
  <!-- ********** SLUT Sektion: Bearbetning ********** -->

  <!-- ********** START Sektion: Resultat ********** -->
  <section class="layout__sektion hidden" id="results-section">
    <div class="kort">
      <h2 class="kort__rubrik">Resultat</h2>
      <div id="status-info" class="text--muted"></div>

    <div class="tabell__wrapper">
      <table class="tabell" id="results-table">
        <thead>
          <tr>
            <th>Nr</th>
            <th>Original</th>
            <th>WGS84</th>
            <th>SWEREF99</th>
            <th>RT90</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody id="results-body">
          <!-- Dynamiska resultat fylls här -->
        </tbody>
      </table>
      </div>
    </div>
  </section>
  <!-- ********** SLUT Sektion: Resultat ********** -->

  <!-- Navigationslänkar -->
  <section class="layout__sektion">
    <div class="knapp__grupp">
      <a href="index.php" class="knapp knapp--sekundär" data-tippy-content="Tillbaka till enkel koordinatkonvertering">← Enkel konvertering</a>
      <a href="impex_map.php" class="knapp knapp--sekundär" data-tippy-content="Visa koordinater på karta">Kartvisning</a>
      <a href="help1.php" class="knapp knapp--sekundär" data-tippy-content="Hjälp och information">Hjälp</a>
    </div>
  </section>
</main>

<?php include '../../includes/layout-end.php'; ?>

<!-- Ladda JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.6.2/proj4.js"></script>
<script type="module" src="impex.js" defer></script>

