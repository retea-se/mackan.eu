/**
 * script.js
 * Återställd version - Hanterar karta och koordinatkonvertering
 */

console.log("[DEBUG] Laddar script.js");

// Vänta tills DOM är redo innan vi laddar kartan
document.addEventListener("DOMContentLoaded", function() {
    console.log("[DEBUG] DOM är nu redo. Startar kartinitiering...");

    let mapElement = document.getElementById("map");
    
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
        let lat = e.latlng.lat.toFixed(6);
        let lng = e.latlng.lng.toFixed(6);
        console.log(`[DEBUG] Kartklick: Nord=${lat}, Öst=${lng}`);
        
        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng]).addTo(window.map);
        }

        document.getElementById("coordinates").value = `${lat}, ${lng}`;
    });
});

// Hantering av konverteringsknappen
document.getElementById("convert-form").addEventListener("submit", function (e) {
    e.preventDefault();

    let rawCoords = document.getElementById("coordinates").value;
    let coords = cleanCoordinates(rawCoords);

    if (!coords) {
        console.error("[ERROR] Inga giltiga koordinater efter rensning!");
        alert("Fel: Ange koordinater i formatet 'lat,long'");
        return;
    }

    let [lat, lng] = coords.split(",").map(num => parseFloat(num.trim()));

    if (isNaN(lat) || isNaN(lng)) {
        console.error("[ERROR] Felaktiga koordinater!");
        alert("Fel: Ogiltigt koordinatformat");
        return;
    }

    console.log(`[DEBUG] Skickar till API: ${coords}`);

    fetch("https://mackan.eu/verktyg/koordinat/api/convert.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ coordinates: coords })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`[ERROR] API svarade med status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log("[DEBUG] API-svar mottaget", data);

        if (data.error) {
            console.error(`[ERROR] API returnerade fel: ${data.error}`);
            alert(`API-fel: ${data.error}`);
            return;
        }

        updateResults(data);
    })
    .catch(error => {
        console.error("[ERROR] Fel vid API-anrop:", error);
        alert("Ett nätverksfel uppstod. Kontrollera din anslutning och försök igen.");
    })
    .finally(() => {
        document.getElementById("coordinates").value = "";
        console.log("[DEBUG] Inputfält rensat efter konvertering.");
    });
});

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
    let resultBody = document.getElementById("result-body");
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
        let displayKey = translations[key] || key;
        let value = data[key];

        if (typeof value === 'object' && value !== null) {
            value = Object.entries(value)
                .map(([subKey, subValue]) => `${subKey}: ${subValue}`)
                .join("<br>");
        }

        let row = `<tr><td>${displayKey}</td><td>${value}</td></tr>`;
        resultBody.innerHTML += row;
    });

    document.getElementById("result").classList.remove("hidden");
    console.log("[DEBUG] Resultattabell uppdaterad");
}
document.addEventListener("DOMContentLoaded", function() {
    const menuToggle = document.getElementById("menu-toggle");
    document.body.addEventListener("click", function(e) {
        if (!document.querySelector(".hamburger-menu").contains(e.target)) {
            menuToggle.checked = false;
        }
    });
});
