<!-- impex_map.php - v3 -->
<!DOCTYPE html>
<html lang="sv">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>GeoParser & Plotter</title>

  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-PGYPYWZ1L1"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'G-PGYPYWZ1L1');
  </script>


  <!-- Leaflet CSS: MUST be first to avoid being overridden -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

  <!-- Favicon -->
  <link rel="apple-touch-icon" sizes="180x180" href="https://mackan.eu/icon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="https://mackan.eu/icon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="https://mackan.eu/icon/favicon-16x16.png">
  <link rel="manifest" href="https://mackan.eu/icon/site.webmanifest">
  <link rel="shortcut icon" href="https://mackan.eu/icon/favicon.ico">

  <!-- CSS -->
  <link rel="stylesheet" href="https://mackan.eu/verktyg/assets/css/reset.css">
  <link rel="stylesheet" href="https://mackan.eu/verktyg/assets/css/variables.css">
  <link rel="stylesheet" href="https://mackan.eu/verktyg/assets/css/typography.css">
  <link rel="stylesheet" href="https://mackan.eu/verktyg/assets/css/layout.css">
  <link rel="stylesheet" href="https://mackan.eu/verktyg/assets/css/components.css">
  <link rel="stylesheet" href="https://mackan.eu/verktyg/assets/css/utilities.css">

  <style>
    #map {
      height: 500px;
      width: 100%;
      margin-top: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .info-text {
      padding: 1rem;
      font-family: monospace;
      font-size: 1rem;
      text-align: right;
      color: #333;
      font-weight: bold;
    }

    .card .table th:last-child,
    .card .table td:last-child {
      white-space: nowrap;
    }
  </style>

  <!-- Leaflet marker icon CSS override (must be last) -->
  <link rel="stylesheet" href="/css/leaflet-override.css" />
</head>
<body>
  <header class="header">
    <div class="container">
    <h1 class="page-title">GeoParser & Plotter</h1>
    </div>
  </header>

  <nav class="hamburger-menu">
    <input type="checkbox" id="menu-toggle">
    <label for="menu-toggle" class="menu-icon">
      <span></span>
      <span></span>
      <span></span>
    </label>
    <ul class="menu">
      <li><a href="https://mackan.eu/verktyg/">Hem</a></li>
      <li><a href="https://mackan.eu/verktyg/koordinat/public/help1.php">Information</a></li>
      <li><a href="https://mackan.eu/verktyg/koordinat/public/impex.php">Avancerad/Batch</a></li>
      <li><a href="https://mackan.eu/verktyg/koordinat/public/impex_map.php">Plot/Adress</a></li>
      <li><a href="https://mackan.eu/verktyg/koordinat/public/impex_map_help.php">Information om Plot/Adress</a></li>
    </ul>
  </nav>

<form id="advanced-form" class="form">
  <label for="coordinates-textarea" class="form-label">Klistra in koordinater:</label>
  <textarea id="coordinates-textarea" class="input" placeholder="Ex: 59.3293, 18.0686&#10;59.3300, 18.0690"></textarea>

  <label for="import-file" class="form-label">Importera fil:</label>
  <input type="file" id="import-file" class="input-file">

  <div class="button-row">
    <button type="button" id="convert-textarea" class="button">🔄 Konvertera text</button>
    <button type="button" id="fetch-addresses" class="button">📍 Hämta adresser</button>
    <button type="button" id="load-markers" class="button">🗺️ Ladda koordinater</button>
    <button type="button" id="export-button" class="button">💾 Exportera CSV</button>
  </div>
</form>

    <!-- RESULTATTABELL -->
    <div id="result" class="card">
      <h2 class="card-title">Resultat</h2>
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
        <tbody id="result-body">
          <!-- Dynamiska resultat fylls här -->
        </tbody>
      </table>
      <div id="total-distance" class="info-text">Total sträcka: 0 m</div>
      <button type="button" id="clear-table" class="button danger-button">Rensa tabell</button>
    </div>

    <!-- KARTA -->
    <div id="map"></div>
  </main>

<!-- HACKER-INSPIRED OUTPUT -->
<div id="hacker-output" class="terminal-output hidden">
  <pre id="terminal-lines"></pre>
  <button id="copy-output" class="button small-button copy-btn">Kopiera data</button>
</div>


  <footer class="footer">
    <div class="footer-left">
      <a href="javascript:history.back()" class="back-link">&larr; Tillbaka</a>
    </div>
    <div class="footer-center">
      © <span id="currentYear"></span> Mackan.eu
    </div>
  </footer>

  <!-- Toast-meddelande -->
  <div id="toast" class="toast">Rensat!</div>

  <!-- Scripts -->
  <script>
    document.getElementById('currentYear').textContent = new Date().getFullYear();
  </script>

  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script src="impex.js"></script>
  <script src="export.js"></script>
  <script src="export_advanced.js"></script>
  <script src="geocoding.js"></script>
  <script src="impex_map.js"></script> <!-- denna måste ligga sist -->
</body>
</html>
