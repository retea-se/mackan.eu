<?php
// tools/koordinat/public/impex_map.php - v5
$title = 'GeoParser & Plotter';
include '../../includes/layout-start.php';
?>

<main class="container">
  <nav class="hamburger-menu">
    <input type="checkbox" id="menu-toggle">
    <label for="menu-toggle" class="menu-icon">
      <span></span><span></span><span></span>
    </label>
    <ul class="menu">
      <li><a href="/index.php">Hem</a></li>
      <li><a href="help1.php">Information</a></li>
      <li><a href="impex.php">Avancerad/Batch</a></li>
      <li><a href="impex_map.php">Plot/Adress</a></li>
      <li><a href="impex_map_help.php">Om Plot/Adress</a></li>
    </ul>
  </nav>

  <h1 class="title">
    <?= $title ?>
    <a href="readme.php" class="info-link-floating" title="Om verktyget">ⓘ</a>
  </h1>

  <form id="advanced-form" class="form-group">
    <label for="coordinates-textarea">Klistra in koordinater:</label>
    <textarea id="coordinates-textarea" class="textarea" placeholder="Ex: 59.3293, 18.0686&#10;59.3300, 18.0690"></textarea>

    <label for="import-file">Importera fil:</label>
    <input type="file" id="import-file" class="input">

    <div class="horizontal-tools">
      <button type="button" id="convert-textarea" class="button">🔄 Konvertera text</button>
      <button type="button" id="fetch-addresses" class="button">📍 Hämta adresser</button>
      <button type="button" id="load-markers" class="button">🗺️ Ladda koordinater</button>
      <button type="button" id="export-button" class="button">💾 Exportera CSV</button>
    </div>
  </form>

  <div id="result" class="card">
    <h2>Resultat</h2>
    <table class="table">
      <thead>
        <tr>
          <th>Latitud (WGS84)</th>
          <th>Longitud (WGS84)</th>
          <th>Höjd (m)</th>
          <th>SWEREF99-Zon</th>
          <th>Gata</th>
          <th>Stad</th>
          <th>Postnummer</th>
          <th>Land</th>
          <th>Distans</th>
        </tr>
      </thead>
      <tbody id="result-body"></tbody>
    </table>
    <div id="total-distance" class="info-text">Total sträcka: 0 m</div>
    <button type="button" id="clear-table" class="button danger-button">Rensa tabell</button>
  </div>

  <div id="map" class="card" style="height: 500px; width: 100%; margin-top: 20px;"></div>

  <div id="hacker-output" class="terminal-output hidden">
    <pre id="terminal-lines"></pre>
    <button id="copy-output" class="button button-small copy-btn">Kopiera data</button>
  </div>

  <div id="toast" class="toast">Rensat!</div>
</main>

<?php include '../../includes/layout-end.php'; ?>

<!-- Leaflet & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
  document.getElementById('currentYear').textContent = new Date().getFullYear();
</script>
<script src="impex.js"></script>
<script src="export.js"></script>
<script src="export_advanced.js"></script>
<script src="geocoding.js"></script>
<script src="impex_map.js"></script> <!-- Viktigt att ligga sist -->
