<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GeoParser & Plotter - No Include Version</title>
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        #map { height: 400px; width: 100%; margin: 20px 0; border: 1px solid #ccc; }
        .form-control { width: 100%; padding: 10px; margin: 10px 0; }
        .btn { padding: 10px 20px; margin: 5px; background: #007cba; color: white; border: none; cursor: pointer; }
        .btn:hover { background: #005a87; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="container">
        <h1>GeoParser & Plotter (No Include Version)</h1>
        <p>Denna version använder inga PHP includes för att isolera 500-fel problemet.</p>
        
        <nav style="margin: 20px 0;">
            <a href="/tools/">← Tillbaka till verktyg</a> |
            <a href="/tools/koordinat/">Koordinatverktyg</a> |
            <a href="/tools/koordinat/impex.php">Batch-konvertering</a>
        </nav>
        
        <div style="background: #f9f9f9; padding: 20px; margin: 20px 0;">
            <h2>Koordinat-parser och kartvisualisering</h2>
            
            <label for="coordinateText">Klistra in text med koordinater:</label>
            <textarea id="coordinateText" class="form-control" rows="8" placeholder="Klistra in text som innehåller koordinater...

Exempel:
Stockholm: 59.3293, 18.0686
Göteborg: N 57° 42' 51.84'', E 11° 58' 19.44''
Malmö: 6145600, 372550 (SWEREF99 TM)"></textarea>
            
            <div style="margin: 10px 0;">
                <button id="parseBtn" class="btn">Parsa koordinater</button>
                <button id="clearBtn" class="btn" style="background: #666;">Rensa</button>
                <button id="exportBtn" class="btn" style="background: #28a745;" disabled>Exportera CSV</button>
            </div>

            <div id="results" style="display: none; margin-top: 20px;">
                <h3>Hittade koordinater:</h3>
                <div style="overflow-x: auto;">
                    <table id="coordinateTable">
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
            
            <div id="status" style="margin-top: 20px; padding: 10px; background: #e7f3ff; border-left: 4px solid #007cba; display: none;"></div>
        </div>

        <div style="background: #fff; padding: 20px; border: 1px solid #ddd; margin-top: 20px;">
            <h3>Information</h3>
            <p>Detta verktyg kan hitta och visualisera koordinater i följande format:</p>
            <ul>
                <li><strong>Decimalgrader (DD):</strong> 59.3293, 18.0686</li>
                <li><strong>Grader, minuter (DM):</strong> 59° 19.758', 18° 4.116'</li>
                <li><strong>Grader, minuter, sekunder (DMS):</strong> 59° 19' 45.48", 18° 4' 6.96"</li>
                <li><strong>SWEREF99 TM:</strong> 6578160, 674032</li>
                <li><strong>RT90:</strong> 6577806, 1628440</li>
            </ul>
            
            <p><strong>Tips:</strong> Klistra in text från dokument, e-post eller andra källor. Verktyget hittar automatiskt koordinater och visar dem på kartan.</p>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        console.log("No-include version av impex_map laddad");
        
        // Grundläggande event listeners
        document.getElementById('parseBtn').addEventListener('click', function() {
            console.log("Parse-knapp klickad - funktionalitet kommer att laddas från externa JS-filer");
            document.getElementById('status').style.display = 'block';
            document.getElementById('status').innerHTML = 'Parsa-funktionalitet laddas...';
        });
        
        document.getElementById('clearBtn').addEventListener('click', function() {
            document.getElementById('coordinateText').value = '';
            document.getElementById('results').style.display = 'none';
            document.getElementById('status').style.display = 'none';
        });
        
        // Försök initiera karta
        try {
            if (typeof L !== 'undefined') {
                var map = L.map('map').setView([59.3293, 18.0686], 6);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);
                console.log("Karta initierad framgångsrikt");
            }
        } catch (e) {
            console.error("Fel vid kartinitiering:", e);
            document.getElementById('map').innerHTML = '<p style="padding: 20px; text-align: center; color: #666;">Karta kunde inte laddas: ' + e.message + '</p>';
        }
    </script>
    
    <!-- Försök ladda externa JS-filer om de finns -->
    <script src="impex.js" onerror="console.log('impex.js inte tillgänglig')"></script>
    <script src="impex_map.js" onerror="console.log('impex_map.js inte tillgänglig')"></script>
</body>
</html>
