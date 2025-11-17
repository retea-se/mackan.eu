/**
 * script.js - Koordinatkonverterare
 * Hanterar karta och koordinatkonvertering för index.php
 */

console.log("[DEBUG] Laddar script.js");

// Vänta tills DOM är redo innan vi laddar kartan
document.addEventListener("DOMContentLoaded", function() {
    console.log("[DEBUG] DOM är nu redo. Startar kartinitiering...");

    const mapElement = document.getElementById("map");
    if (!mapElement) {
        console.error("[ERROR] Map container not found! Se till att <div id='map'> finns i HTML.");
        return;
    }

    // Skapa en ny Leaflet-karta
    window.map = L.map('map').setView([59.3293, 18.0686], 10);

    // Lägg till standardkartlagret (OpenStreetMap)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(window.map);

    // Markörhantering
    let marker = null;
    window.map.on('click', function (e) {
        const lat = e.latlng.lat.toFixed(6);
        const lng = e.latlng.lng.toFixed(6);
        console.log(`[DEBUG] Kartklick: Nord=${lat}, Öst=${lng}`);

        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng]).addTo(window.map);
        }

        const coordsInput = document.getElementById("coordinates");
        if (coordsInput) {
            coordsInput.value = `${lat}, ${lng}`;
        }
    });

    // Hantering av konverteringsknappen
    const convertForm = document.getElementById("convert-form");
    if (convertForm) {
        convertForm.addEventListener("submit", handleConvert);
    }
});

// Hantera konvertering
async function handleConvert(e) {
    e.preventDefault();

    const coordsInput = document.getElementById("coordinates");
    const exportCsv = document.getElementById("export-csv");
    const clearBtn = document.getElementById("clear-btn");
    const formatInfo = document.getElementById("format-info");
    const resultSection = document.getElementById("result-section");

    if (!coordsInput) {
        console.error("[ERROR] Coordinates input not found!");
        return;
    }

    const rawCoords = coordsInput.value.trim();
    if (!rawCoords) {
        showToast("Ange koordinater i formatet 'lat,long'", 'warning');
        return;
    }

    const coords = cleanCoordinates(rawCoords);
    if (!coords) {
        showToast("Fel: Ange koordinater i formatet 'lat,long'", 'error');
        return;
    }

    const [lat, lng] = coords.split(",").map(num => parseFloat(num.trim()));
    if (isNaN(lat) || isNaN(lng)) {
        showToast("Fel: Ogiltigt koordinatformat", 'error');
        return;
    }

    // Visa loading state
    const loadingEl = showLoading(resultSection || document.body, "Konverterar koordinater...");

    if (formatInfo) {
        formatInfo.textContent = "Konverterar...";
        formatInfo.classList.remove("hidden");
    }

    try {
        console.log(`[DEBUG] Skickar till API: ${coords}`);

        const response = await fetch("api/convert.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ coordinates: coords })
        });

        if (!response.ok) {
            throw new Error(`API svarade med status: ${response.status}`);
        }

        const data = await response.json();
        console.log("[DEBUG] API-svar mottaget", data);

        if (data.error) {
            showToast(`API-fel: ${data.error}`, 'error');
            return;
        }

        updateResults(data);

        // Visa export och clear knappar
        if (exportCsv) exportCsv.classList.remove("hidden");
        if (clearBtn) clearBtn.classList.remove("hidden");
        if (resultSection) resultSection.classList.remove("hidden");

        // Uppdatera format info
        if (formatInfo && data.detectedFormat) {
            formatInfo.textContent = `Format: ${data.detectedFormat}`;
        }

        showToast("Koordinater konverterade framgångsrikt.", 'success');
    } catch (error) {
        console.error("[ERROR] Fel vid API-anrop:", error);
        showToast("Ett nätverksfel uppstod. Kontrollera din anslutning och försök igen.", 'error');
    } finally {
        // Dölj loading-indikator
        hideLoading(resultSection || document.body);
    }
}

// Visa felmeddelande (deprecated - använd showToast istället)
async function showError(message) {
    // Använd vår nya showToast-funktion
    showToast(message, 'error');
}

// Funktion för att rensa och standardisera koordinater
function cleanCoordinates(input) {
    console.log(`[DEBUG] Rå inmatning: "${input}"`);

    if (!input || typeof input !== 'string') {
        console.error("[ERROR] Ogiltig input till cleanCoordinates!");
        return "";
    }

    // Ta bort oönskade symboler och text
    input = input.replace(/\b(X|Y|Latitud|Longitude|Längdgrad|Breddgrad|Koordinater)\s*[:=]?\s*/gi, '');
    input = input.replace(/[:;°]/g, ',');
    input = input.replace(/[()]/g, '').trim();
    input = input.replace(/\b(N|E|S|W)\b/gi, ' ');
    input = input.replace(/(\d),(\d)/g, '$1.$2');
    input = input.replace(/[\s,]+/g, ',');
    input = input.replace(/^,|,$/g, '');

    let parts = input.split(',').filter(part => !isNaN(part) && part.trim() !== '');
    if (parts.length !== 2) {
        console.error(`[ERROR] Felaktigt koordinatformat: "${input}"`);
        return "";
    }

    let lat = parseFloat(parts[0]);
    let lng = parseFloat(parts[1]);

    if (Math.abs(lat) > 90) {
        [lat, lng] = [lng, lat];
    }

    if (isNaN(lat) || isNaN(lng)) {
        console.error(`[ERROR] Misslyckades att rensa koordinater korrekt: "${input}"`);
        return "";
    }

    let cleanedCoords = `${lat},${lng}`;
    console.log(`[DEBUG] Rensad inmatning: "${cleanedCoords}"`);
    return cleanedCoords;
}

// Uppdaterar resultattabellen
function updateResults(data) {
    const resultBody = document.getElementById("result-body");
    if (!resultBody) {
        console.error("[ERROR] Result body not found!");
        return;
    }

    resultBody.innerHTML = "";

    const translations = {
        "detectedFormat": "Upptäckt format",
        "wgs84": "WGS84-koordinater",
        "sweref99": "SWEREF99-koordinater",
        "rt90": "RT90-koordinater",
        "elevation": "Höjd",
        "sweref99_zone": "SWEREF99-zon"
    };

    Object.keys(data).forEach(key => {
        if (key === 'detectedFormat') return; // Skippa detectedFormat i tabellen

        const displayKey = translations[key] || key;
        let value = data[key];

        if (typeof value === 'object' && value !== null) {
            value = Object.entries(value)
                .map(([subKey, subValue]) => `${subKey}: ${subValue}`)
                .join("<br>");
        }

        const row = document.createElement("tr");
        row.innerHTML = `<td>${displayKey}</td><td>${value}</td>`;
        resultBody.appendChild(row);
    });

    console.log("[DEBUG] Resultattabell uppdaterad");
}
