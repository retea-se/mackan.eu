<?php
// tools/koordinat/public/impex.php - v5 (Ny mallstruktur)
$title = 'Avancerad Koordinathantering';
$metaDescription = 'Importera och konvertera stora mängder koordinater mellan WGS84, SWEREF99, RT90. Stöder CSV-import, batch-bearbetning och export till olika format.';
$metaKeywords = 'batch koordinatkonvertering, CSV import, koordinat export, WGS84, SWEREF99, RT90, GIS batch';
$canonical = 'https://mackan.eu/tools/koordinat/impex.php';

include '../../includes/layout-start.php';
?>

<main class="container">
  <h1 class="rubrik">
    <?= $title ?>
    <?php include '../../includes/readme-icon.php'; ?>
  </h1>

  <!-- Breadcrumbs navigation -->
  <div class="breadcrumbs">
    <a href="/tools/">Verktyg</a> → <a href="index.php">Koordinatkonverterare</a> → Avancerad
  </div>

  <!-- Beskrivning -->
  <div class="verktygsinfo">
    <p>Importera stora mängder koordinater från CSV-fil eller klistra in text. Konvertera mellan olika koordinatsystem och exportera resultatet.</p>
  </div>

  <!-- ********** START Sektion: Import ********** -->
  <div class="kort mb-1">
    <h2 class="kort__rubrik">Import av koordinater</h2>
    
    <form class="form">
      <div class="form__grupp">
        <label for="file-input" class="fält__etikett">Välj CSV-fil:</label>
        <input type="file" id="file-input" class="fält" accept=".csv,.txt" data-tippy-content="Ladda upp en CSV-fil med koordinater">
      </div>

      <div class="form__grupp">
        <label for="text-input" class="fält__etikett">Eller klistra in koordinater:</label>
        <textarea id="text-input" class="fält" rows="6" placeholder="Klistra in koordinater här, en per rad eller separerade med komma..."></textarea>
      </div>

      <div class="form__grupp">
        <label for="input-format" class="fält__etikett">Källformat:</label>
        <select id="input-format" class="fält">
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
  <!-- ********** SLUT Sektion: Import ********** -->

  <!-- ********** START Sektion: Bearbetning ********** -->
  <div class="kort mb-1" id="processing-section" style="display: none;">
    <h2 class="kort__rubrik">Bearbetningsalternativ</h2>
    
    <form class="form">
      <div class="form__grupp">
        <label for="output-formats" class="fält__etikett">Utdataformat:</label>
        <div class="checkbox-group">
          <label><input type="checkbox" id="format-wgs84" checked> WGS84 (lat, lon)</label>
          <label><input type="checkbox" id="format-sweref99"> SWEREF99 TM</label>
          <label><input type="checkbox" id="format-rt90"> RT90</label>
          <label><input type="checkbox" id="format-utm"> UTM</label>
        </div>
      </div>

      <div class="form__grupp">
        <label><input type="checkbox" id="include-elevation"> Inkludera höjddata</label>
      </div>

      <div class="form__verktyg">
        <button type="button" id="process-btn" class="knapp" data-tippy-content="Konvertera alla koordinater enligt valda format">Konvertera alla</button>
        <button type="button" id="export-csv" class="knapp knapp--sekundär hidden" data-tippy-content="Exportera resultatet som CSV-fil">Exportera CSV</button>
        <button type="button" id="export-json" class="knapp knapp--sekundär hidden" data-tippy-content="Exportera resultatet som JSON-fil">Exportera JSON</button>
      </div>
    </form>
  </div>
  <!-- ********** SLUT Sektion: Bearbetning ********** -->

  <!-- ********** START Sektion: Resultat ********** -->
  <div class="kort" id="results-section" style="display: none;">
    <h2 class="kort__rubrik">Resultat</h2>
    <div id="status-info" class="info-text mb-1"></div>
    
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
  <!-- ********** SLUT Sektion: Resultat ********** -->

  <!-- Navigationslänkar -->
  <div class="mt-1">
    <a href="index.php" class="knapp knapp--sekundär" data-tippy-content="Tillbaka till enkel koordinatkonvertering">← Enkel konvertering</a>
    <a href="impex_map.php" class="knapp knapp--sekundär" data-tippy-content="Visa koordinater på karta">Kartvisning</a>
    <a href="help1.php" class="knapp knapp--sekundär" data-tippy-content="Hjälp och information">Hjälp</a>
  </div>
</main>

<?php include '../../includes/layout-end.php'; ?>

<!-- Ladda JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.6.2/proj4.js"></script>
<script src="impex.js" defer></script>

<style>
.checkbox-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.checkbox-group label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

#status-info {
    padding: 0.5rem;
    border-radius: 4px;
    background-color: var(--color-neutral-100);
}

.progress-bar {
    width: 100%;
    height: 20px;
    background-color: var(--color-neutral-200);
    border-radius: 10px;
    overflow: hidden;
    margin: 0.5rem 0;
}

.progress-fill {
    height: 100%;
    background-color: var(--color-primary);
    transition: width 0.3s ease;
}
</style>
