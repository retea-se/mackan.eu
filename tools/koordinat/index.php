<?php
// tools/koordinat/index.php - v5 (Ny mallstruktur)
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

include '../../includes/tool-layout-start.php';
?>

<main class="layout__container">
  <header class="layout__sektion text--center">
    <h1 class="rubrik rubrik--sektion">
      <?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>
    </h1>
    <p class="text--lead">
      Klicka på kartan för att få koordinater eller mata in dem manuellt. Stöder konvertering mellan WGS84, SWEREF99, RT90 och andra koordinatsystem.
    </p>
  </header>

  <!-- Karta -->
  <section class="layout__sektion">
    <div class="kort">
      <div id="map" style="height: 500px; width: 100%; border-radius: 8px;"></div>
    </div>
  </section>

  <!-- ********** START Sektion: Formulär ********** -->
  <section class="layout__sektion">
    <form id="convert-form" class="form" novalidate>
      <div class="form__grupp">
        <label for="coordinates" class="falt__etikett">Koordinater:</label>
        <input type="text" id="coordinates" class="falt__input" placeholder="Ex: 59.3293, 18.0686" required>
        <p id="format-info" class="falt__hjälp hidden">Format: Okänt</p>
      </div>

    <div class="form__verktyg">
      <button type="submit" class="knapp" data-tippy-content="Konvertera koordinaterna till olika format">Konvertera</button>
      <button type="button" class="knapp knapp--sekundär hidden" id="export-csv" data-tippy-content="Exportera resultatet som CSV">Exportera CSV</button>
      <button type="button" class="knapp knapp--sekundär hidden" id="clear-btn" data-tippy-content="Rensa formulär och resultat">Rensa</button>
    </div>
  </form>
  <!-- ********** SLUT Sektion: Formulär ********** -->

  <!-- ********** START Sektion: Resultat ********** -->
  <section class="layout__sektion hidden" id="result-section">
    <div class="kort">
      <h2 class="kort__rubrik">Resultat</h2>
      <div class="tabell__wrapper">
        <table class="tabell" id="result-table">
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
  </section>
  <!-- ********** SLUT Sektion: Resultat ********** -->

  <!-- Navigationslänkar -->
  <section class="layout__sektion">
    <div class="knapp__grupp">
      <a href="help1.php" class="knapp knapp--sekundär" data-tippy-content="Läs mer om koordinatsystem och hur verktyget fungerar">Information</a>
      <a href="impex.php" class="knapp knapp--sekundär" data-tippy-content="Konvertera många koordinater samtidigt">Avancerad/Batch</a>
      <a href="impex_map.php" class="knapp knapp--sekundär" data-tippy-content="Plotta koordinater på karta och sök adresser">Plot/Adress</a>
    </div>
  </section>
</main>

<?php include '../../includes/tool-layout-end.php'; ?>

<!-- Ladda JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.6.2/proj4.js"></script>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script type="module" src="/js/modal-utils.js"></script>
<script src="script.js" defer></script>
<script src="export.js" defer></script>

<script>
// Rensa funktionalitet
document.addEventListener('DOMContentLoaded', function() {
    const clearBtn = document.getElementById('clear-btn');
    if (clearBtn) {
        clearBtn.addEventListener('click', function() {
            const coordinates = document.getElementById('coordinates');
            const resultSection = document.getElementById('result-section');
            const exportCsv = document.getElementById('export-csv');
            const formatInfo = document.getElementById('format-info');
            const resultBody = document.getElementById('result-body');

            if (coordinates) coordinates.value = '';
            if (resultSection) resultSection.classList.add('hidden');
            if (exportCsv) exportCsv.classList.add('hidden');
            if (clearBtn) clearBtn.classList.add('hidden');
            if (formatInfo) formatInfo.classList.add('hidden');
            if (resultBody) resultBody.innerHTML = '';

            // Ta bort marker från kartan
            if (window.map) {
                window.map.eachLayer(function(layer) {
                    if (layer instanceof L.Marker) {
                        window.map.removeLayer(layer);
                    }
                });
            }
        });
    }
});
</script>
