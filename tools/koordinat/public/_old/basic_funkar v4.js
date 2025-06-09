/**
 * script.js
 * Version 4 - Förbättrad terminologi
 * 
 * Loggning:
 * - Klick på kartan
 * - Klick på konvertera-knappen
 * - API-anrop och svar
 * - Felhantering
 */

console.log("[DEBUG] Laddar script.js - Version 4");

// Initialisera kartan
let map = L.map('map').setView([59.3293, 18.0686], 10);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

console.log("[DEBUG] Karta initialiserad och centrerad på Stockholm");

// Händelsehantering för klick på kartan
document.addEventListener("DOMContentLoaded", () => {
    map.on('click', function (e) {
        let lat = e.latlng.lat.toFixed(6);
        let lng = e.latlng.lng.toFixed(6);
        console.log(`[DEBUG] Kartklick: Nord=${lat}, Öst=${lng}`);
        document.getElementById("coordinates").value = `${lat}, ${lng}`;
    });
});

// Hantering av konverteringsknappen
document.getElementById("convert-form").addEventListener("submit", function (e) {
    e.preventDefault();
    let coords = document.getElementById("coordinates").value;
    console.log(`[DEBUG] Konvertera-knappen klickad med koordinater: ${coords}`);
    if (!coords) {
        console.error("[ERROR] Inga koordinater angivna!");
        return;
    }
    
    fetch("https://mackan.eu/verktyg/koordinat/api/convert.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ coordinates: coords })
    })
    .then(response => response.json())
    .then(data => {
        console.log("[DEBUG] API-svar mottaget", data);
        updateResults(data);
    })
    .catch(error => console.error("[ERROR] Fel vid API-anrop:", error));
});

// Uppdaterar resultattabellen
function updateResults(data) {
    let resultBody = document.getElementById("result-body");
    resultBody.innerHTML = "";
    
    const translations = {
        "detectedFormat": "Upptäckt format",
        "lat": "Nord",
        "lon": "Öst",
        "north": "Nord",
        "east": "Öst"
    };
    
    Object.keys(data).forEach(key => {
        let displayKey = translations[key] || key;
        let value = data[key];
        
        if (typeof value === 'object' && value !== null) {
            value = Object.entries(value)
                .map(([subKey, subValue]) => `${translations[subKey] || subKey}: ${subValue}`)
                .join("<br>");
        }
        let row = `<tr><td>${displayKey}</td><td>${value}</td></tr>`;
        resultBody.innerHTML += row;
    });
    
    document.getElementById("result").classList.remove("hidden");
    console.log("[DEBUG] Resultattabell uppdaterad");
}
