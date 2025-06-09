console.log("[DEBUG] Laddar impex_plot.js");

// Initialisera kartan
let map = L.map('map').setView([59.3293, 18.0686], 10); // Centrerad på Stockholm
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

let markersLayer = L.layerGroup().addTo(map); // Lager för markörer

// Funktion för att lägga till markörer
function plotCoordinates(coordinates) {
    console.log(`[DEBUG] Försöker plotta ${coordinates.length} koordinater på kartan.`);
    
    markersLayer.clearLayers(); // Rensa tidigare markörer

    coordinates.forEach(coord => {
        let lat = parseFloat(coord.latitude);
        let lon = parseFloat(coord.longitude);

        if (!isNaN(lat) && !isNaN(lon)) {
            let marker = L.marker([lat, lon]).addTo(markersLayer)
                .bindPopup(`
                    <b>Lat:</b> ${lat}<br>
                    <b>Lon:</b> ${lon}<br>
                    <b>Höjd:</b> ${coord.elevation || "N/A"}<br>
                    <b>SWEREF99-zon:</b> ${coord.sweref99_zone || "Okänd"}
                `);
        } else {
            console.warn(`[WARNING] Ogiltiga koordinater: ${coord.latitude}, ${coord.longitude}`);
        }
    });

    console.log(`[DEBUG] Plottade ${coordinates.length} markörer.`);
}

// Funktion för att extrahera och plotta koordinater från resultattabellen
function extractAndPlotFromTable() {
    let rows = document.querySelectorAll("#result-body tr");
    let coordinates = [];

    rows.forEach(row => {
        let cols = row.querySelectorAll("td");
        if (cols.length >= 2) {
            let lat = parseFloat(cols[0].innerText);
            let lon = parseFloat(cols[1].innerText);

            if (!isNaN(lat) && !isNaN(lon)) {
                coordinates.push({ latitude: lat, longitude: lon });
            }
        }
    });

    if (coordinates.length > 0) {
        console.log("[DEBUG] Koordinater extraherade från tabell:", coordinates);
        plotCoordinates(coordinates);
    } else {
        console.warn("[WARNING] Inga giltiga koordinater hittades i tabellen.");
    }
}

// Funktion för att hämta och plotta koordinater från API
async function fetchAndPlotCoordinates() {
    try {
        const response = await fetch("https://mackan.eu/verktyg/koordinat/api//export.php");
        const data = await response.json();

        if (Array.isArray(data) && data.length > 0) {
            console.log("[DEBUG] API-data hämtad, plottar nu koordinater.");
            plotCoordinates(data);
        } else {
            console.warn("[WARNING] Inga koordinater hittades från API.");
        }
    } catch (error) {
        console.error("[ERROR] Misslyckades att hämta koordinater från API:", error);
    }
}

// Kör hämtning och plottning vid sidladdning
document.addEventListener("DOMContentLoaded", () => {
    //fetchAndPlotCoordinates();
    document.getElementById("load-markers").addEventListener("click", () => {
        fetchAndPlotCoordinates();
    });
    
    // Kör även plottning från tabellen när en användare konverterar/importerar
    document.getElementById("convert-textarea")?.addEventListener("click", extractAndPlotFromTable);
});
