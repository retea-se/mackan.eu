<?php
// tools/koordinat/public/index.php - v5 (Ny mallstruktur)
$title = 'Koordinatkonverterare';
$metaDescription = 'Professionellt koordinatverktyg för konvertering mellan WGS84, SWEREF99, RT90. Stöder batch-import, höjddata och CSV-export. Perfekt för GIS-arbete och lantmäteri.';
$metaKeywords = 'koordinatkonvertering, WGS84, SWEREF99, RT90, GIS, lantmäteri, batch-import, höjddata, koordinatsystem, GPS';
$canonical = 'https://mackan.eu/tools/koordinat/';

// Lägg till extra head-content för Leaflet och Proj4
$extraHead = '
    <!-- Preconnect för snabbare laddning -->
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="preconnect" href="https://unpkg.com">
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css">
    
    <!-- Strukturerad data för sökmotorer -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebApplication",
      "name": "Koordinatkonverterare",
      "description": "Professionellt koordinatverktyg för konvertering mellan WGS84, SWEREF99, RT90",
      "url": "https://mackan.eu/tools/koordinat/",
      "applicationCategory": "UtilityApplication",
      "operatingSystem": "Web Browser",
      "offers": {
        "@type": "Offer",
        "price": "0",
        "priceCurrency": "SEK"
      },
      "featureList": [
        "WGS84 till SWEREF99 konvertering",
        "RT90 koordinatsystem",
        "Batch-import från CSV",
        "Kartvisning",
        "Höjddata",
        "Export till olika format"
      ],
      "author": {
        "@type": "Organization",
        "name": "Mackan.eu"
      }
    }
    </script>';

include '../../includes/layout-start.php';
?>

<main class="container">
  <h1 class="rubrik">
    <?= $title ?>
    <?php include '../../includes/readme-icon.php'; ?>
  </h1>

  <!-- Breadcrumbs navigation -->
  <div class="breadcrumbs">
    <a href="/tools/">Verktyg</a> → Koordinatkonverterare
  </div>

  <!-- Beskrivning -->
  <div class="verktygsinfo">
    <p>Klicka på kartan för att få koordinater eller mata in dem manuellt. Stöder konvertering mellan WGS84, SWEREF99, RT90 och andra koordinatsystem.</p>
  </div>

  <!-- Karta -->
  <div class="kort mb-1">
    <div id="map" style="height: 500px; width: 100%; border-radius: 8px;"></div>
  </div>

  <!-- ********** START Sektion: Formulär ********** -->
  <form id="convert-form" class="form">
    <div class="form__grupp">
      <label for="coordinates" class="fält__etikett">Koordinater:</label>
      <input type="text" id="coordinates" class="fält" placeholder="Ex: 59.3293, 18.0686" required>
      <p id="format-info" class="info-text hidden">Format: Okänt</p>
    </div>

    <div class="form__verktyg">
      <button type="submit" class="knapp" data-tippy-content="Konvertera koordinaterna till olika format">Konvertera</button>
      <button type="button" class="knapp knapp--sekundär hidden" id="export-btn" data-tippy-content="Exportera resultatet som CSV">Exportera</button>
      <button type="button" class="knapp knapp--sekundär hidden" id="clear-btn" data-tippy-content="Rensa formulär och resultat">Rensa</button>
    </div>
  </form>
  <!-- ********** SLUT Sektion: Formulär ********** -->

  <!-- ********** START Sektion: Resultat ********** -->
  <div class="kort" id="result-section" style="display: none;">
    <h2 class="kort__rubrik">Resultat</h2>
    <div class="tabell__wrapper">
      <table class="tabell">
        <thead>
          <tr>
            <th>Format</th>
            <th>Värde</th>
          </tr>
        </thead>
        <tbody id="result-body">
          <!-- Dynamiska resultat fylls här -->
        </tbody>
      </table>
    </div>
  </div>
  <!-- ********** SLUT Sektion: Resultat ********** -->

  <!-- Navigationslänkar -->
  <div class="mt-1">
    <a href="help1.php" class="knapp knapp--sekundär" data-tippy-content="Läs mer om koordinatsystem och hur verktyget fungerar">Information</a>
    <a href="impex.php" class="knapp knapp--sekundär" data-tippy-content="Konvertera många koordinater samtidigt">Avancerad/Batch</a>
    <a href="impex_map.php" class="knapp knapp--sekundär" data-tippy-content="Plotta koordinater på karta och sök adresser">Plot/Adress</a>
  </div>
</main>

<?php include '../../includes/layout-end.php'; ?>

<!-- Ladda JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.6.2/proj4.js"></script>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="script.js" defer></script>

<script>
// Hantera resultatvisning
document.getElementById('convert-form').addEventListener('submit', function(e) {
    e.preventDefault();
    document.getElementById('result-section').style.display = 'block';
    document.getElementById('export-btn').classList.remove('hidden');
    document.getElementById('clear-btn').classList.remove('hidden');
});

// Rensa funktionalitet
document.getElementById('clear-btn').addEventListener('click', function() {
    document.getElementById('coordinates').value = '';
    document.getElementById('result-section').style.display = 'none';
    document.getElementById('export-btn').classList.add('hidden');
    document.getElementById('clear-btn').classList.add('hidden');
    document.getElementById('format-info').classList.add('hidden');
});
</script>
