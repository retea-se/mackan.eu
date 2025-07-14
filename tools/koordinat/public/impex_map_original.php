<?php
// tools/koordinat/public/impex_map.php - v4 (Ny mallstruktur)
$title = 'GeoParser & Plotter - Koordinatverktyg';
$metaDescription = 'Avancerat verktyg för att parsa och visualisera geografiska koordinater från text. Importera koordinater från olika format och visa på interaktiv karta.';
$metaKeywords = 'koordinatparsning, geoparser, kartvisualisering, GPS-koordinater, textparsning, interactive map';
$canonical = 'https://mackan.eu/tools/koordinat/impex_map.php';

// Lägg till extra head-content för Leaflet
$extraHead = '
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    
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
';

// Inkludera layout-start
include_once '../../../includes/layout-start.php';
?>

<div class="container">
    <div class="content-wrapper">
        <div class="breadcrumbs">
            <a href="/tools/">Verktyg</a> → 
            <a href="/tools/koordinat/">Koordinater</a> → 
            <span>GeoParser & Plotter</span>
        </div>

        <div class="hero-section">
            <h1 class="hero-title">GeoParser & Plotter</h1>
            <p class="hero-description">Avancerat verktyg för att parsa och visualisera geografiska koordinater från text. Importera koordinater från olika format och visa på interaktiv karta.</p>
        </div>

        <div class="grid-layout">
            <div class="main-content">
                <div class="card">
                    <div class="card-header">
                        <h2>Koordinat-parser och kartvisualisering</h2>
                    </div>
                    <div class="card-content">
                        <div class="form-row">
                            <label for="coordinateText" class="form-label">Klistra in text med koordinater:</label>
                            <textarea id="coordinateText" class="form-control" rows="10" placeholder="Klistra in text som innehåller koordinater..."></textarea>
                        </div>
                        
                        <div class="button-group">
                            <button id="parseBtn" class="btn btn-primary">Parsa koordinater</button>
                            <button id="clearBtn" class="btn btn-secondary">Rensa</button>
                            <button id="exportBtn" class="btn btn-accent" disabled>Exportera CSV</button>
                        </div>

                        <div id="results" class="results-section" style="display: none;">
                            <h3>Hittade koordinater:</h3>
                            <div class="table-container">
                                <table id="coordinateTable" class="table">
                                    <thead>
                                        <tr>
                                            <th>Index</th>
                                            <th>Ursprunglig text</th>
                                            <th>Latitude</th>
                                            <th>Longitude</th>
                                            <th>Format</th>
                                            <th>Adress</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>

                        <div id="map"></div>
                        
                        <div id="status" class="status-message"></div>
                    </div>
                </div>
            </div>

            <div class="sidebar">
                <div class="card">
                    <div class="card-header">
                        <h3>Information</h3>
                    </div>
                    <div class="card-content">
                        <p>Detta verktyg kan hitta och visualisera koordinater i följande format:</p>
                        <ul class="feature-list">
                            <li>Decimalgrader (DD): 59.3293, 18.0686</li>
                            <li>Grader, minuter (DM): 59° 19.758', 18° 4.116'</li>
                            <li>Grader, minuter, sekunder (DMS): 59° 19' 45.48", 18° 4' 6.96"</li>
                            <li>SWEREF99 TM: 6578160, 674032</li>
                            <li>RT90: 6577806, 1628440</li>
                        </ul>
                        
                        <div class="info-box">
                            <strong>Tips:</strong> Klistra in text från dokument, e-post eller andra källor. Verktyget hittar automatiskt koordinater och visar dem på kartan.
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Verktyg</h3>
                    </div>
                    <div class="card-content">
                        <div class="button-list">
                            <a href="/tools/koordinat/" class="btn btn-outline">Huvudverktyg</a>
                            <a href="/tools/koordinat/impex.php" class="btn btn-outline">Batch-konvertering</a>
                            <a href="/tools/koordinat/help1.php" class="btn btn-outline">Hjälp & Info</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Initialize currentYear for footer
    document.addEventListener('DOMContentLoaded', function() {
        const yearElement = document.getElementById('currentYear');
        if (yearElement) {
            yearElement.textContent = new Date().getFullYear();
        }
    });
</script>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="impex.js"></script>
<script src="export.js"></script>
<script src="export_advanced.js"></script>
<script src="geocoding.js"></script>
<script src="impex_map.js"></script> <!-- denna måste ligga sist -->

<?php
// Inkludera layout-end
include_once '../../../includes/layout-end.php';
?>
