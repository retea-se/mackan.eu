<?php
// tools/koordinat/impex_map.php - GeoParser & Plotter
$title = 'GeoParser & Plotter';
$metaDescription = 'Plotta koordinater på karta, hämta adresser och exportera resultat. Perfekt för GIS-arbete och geografisk analys.';
$metaKeywords = 'koordinat plotter, geocoding, adresssökning, kartvisning, GIS, batch koordinater, koordinat export';
$canonical = 'https://mackan.eu/tools/koordinat/impex_map.php';

// Lägg till extra head-content för Leaflet
$extraHead = '
    <!-- Preconnect för snabbare laddning -->
    <link rel="preconnect" href="https://unpkg.com">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

    <style>
      #map {
        height: 500px;
        width: 100%;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-small);
      }

      .tabell th:last-child,
      .tabell td:last-child {
        white-space: nowrap;
      }
    </style>

    <!-- Leaflet marker icon CSS override -->
    <link rel="stylesheet" href="/css/leaflet-override.css" />
';

include '../../includes/layout-start.php';
?>

<main class="layout__container">
  <header class="layout__sektion text--center">
    <h1 class="rubrik rubrik--sektion">
      <?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>
    </h1>
    <p class="text--lead">
      Plotta koordinater på karta, hämta adresser via geocoding och exportera resultat.
      Klicka på kartan eller importera koordinater från fil.
    </p>
  </header>

  <section class="layout__sektion">
    <form id="advanced-form" class="form" novalidate>
      <div class="form__grupp">
        <label for="coordinates-textarea" class="falt__etikett">Klistra in koordinater:</label>
        <textarea id="coordinates-textarea" class="falt__textarea" rows="6" placeholder="Ex: 59.3293, 18.0686&#10;59.3300, 18.0690"></textarea>
      </div>

      <div class="form__grupp">
        <label for="import-file" class="falt__etikett">Importera fil:</label>
        <input type="file" id="import-file" class="falt__input" accept=".csv,.txt">
      </div>

      <div class="form__verktyg">
        <button type="button" id="convert-textarea" class="knapp" data-tippy-content="Konvertera koordinater från textfältet">🔄 Konvertera text</button>
        <button type="button" id="fetch-addresses" class="knapp knapp--sekundär" data-tippy-content="Hämta adresser för koordinater i tabellen">📍 Hämta adresser</button>
        <button type="button" id="load-markers" class="knapp knapp--sekundär" data-tippy-content="Ladda koordinater på kartan">🗺️ Ladda koordinater</button>
        <button type="button" id="export-button" class="knapp knapp--sekundär" data-tippy-content="Exportera resultatet som CSV">💾 Exportera CSV</button>
      </div>
    </form>
  </section>

  <!-- RESULTATTABELL -->
  <section class="layout__sektion hidden" id="result-section">
    <div class="kort">
      <h2 class="kort__rubrik">Resultat</h2>
      <div class="tabell__wrapper">
        <table class="tabell" id="result-table">
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
          <tbody id="result-body">
            <!-- Dynamiska resultat fylls här -->
          </tbody>
        </table>
      </div>
      <div id="total-distance" class="text--muted text--small text--center" style="margin-top: 1rem;">Total sträcka: 0 m</div>
      <div class="form__verktyg">
        <button type="button" id="clear-table" class="knapp knapp--sekundär" data-tippy-content="Rensa alla resultat">Rensa tabell</button>
      </div>
    </div>
  </section>

  <!-- KARTA -->
  <section class="layout__sektion">
    <div class="kort">
      <div id="map"></div>
    </div>
  </section>

  <!-- Terminal output (för avancerad export) -->
  <section class="layout__sektion hidden" id="hacker-output-section">
    <div class="kort kort__terminal">
      <h2 class="kort__rubrik">Terminal Output</h2>
      <pre id="terminal-lines" class="kort__innehall"></pre>
      <div class="form__verktyg">
        <button id="copy-output" class="knapp knapp--liten" data-tippy-content="Kopiera terminaldata">Kopiera data</button>
      </div>
    </div>
  </section>

  <!-- Navigationslänkar -->
  <section class="layout__sektion">
    <div class="knapp__grupp">
      <a href="index.php" class="knapp knapp--sekundär" data-tippy-content="Tillbaka till enkel koordinatkonvertering">← Enkel konvertering</a>
      <a href="impex.php" class="knapp knapp--sekundär" data-tippy-content="Avancerad batch-konvertering">Avancerad/Batch</a>
      <a href="help1.php" class="knapp knapp--sekundär" data-tippy-content="Hjälp och information">Hjälp</a>
    </div>
  </section>
</main>

<?php include '../../includes/layout-end.php'; ?>

<!-- Scripts -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script type="module" src="export_advanced.js"></script>
<script type="module" src="impex_map.js" defer></script>
<script src="geocoding.js"></script>
