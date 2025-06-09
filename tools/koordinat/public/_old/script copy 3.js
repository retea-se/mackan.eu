/**
 * script.js
 * Version 6 - Höjd över havet + SWEREF99-zon (Tooltip)
 * 
 * Loggning:
 * - Klick på kartan
 * - Klick på konvertera-knappen
 * - API-anrop och svar
 * - Felhantering
 * - Markering av koordinater på kartan
 * - Höjddata + SWEREF99-zon
 */

console.log("[DEBUG] Laddar script.js - Version 6");

// Initialisera kartan
let map = L.map('map').setView([59.3293, 18.0686], 10);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

let marker = null;
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
    
    let [lat, lng] = coords.split(",").map(num => parseFloat(num.trim()));
    if (!isNaN(lat) && !isNaN(lng)) {
        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng]).addTo(map);
        }
        map.setView([lat, lng], 12);
        console.log(`[DEBUG] Markör placerad på: Nord=${lat}, Öst=${lng}`);
    } else {
        console.error("[ERROR] Felaktiga koordinater för markering!");
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
        fetchElevation(lat, lng, data);
    })
    .catch(error => console.error("[ERROR] Fel vid API-anrop:", error));
});

// Hämta höjddata från Open-Elevation
function fetchElevation(lat, lng, data) {
    fetch(`https://api.open-elevation.com/api/v1/lookup?locations=${lat},${lng}`)
        .then(response => response.json())
        .then(elevationData => {
            let elevation = elevationData.results[0].elevation;
            console.log(`[DEBUG] Höjd hämtad: ${elevation} m`);
            data["elevation"] = `${elevation} m över havet`;
            data["sweref99_zone"] = getSWEREF99Zone(lng);
            updateResults(data);
        })
        .catch(error => {
            console.error("[ERROR] Fel vid hämtning av höjddata:", error);
            updateResults(data);
        });
}

// Beräknar SWEREF99-zonen baserat på longitud
function getSWEREF99Zone(lng) {
    let zones = [
        { min: 10.5, max: 12.5, zone: "SWEREF99 1215" },
        { min: 12.5, max: 14.5, zone: "SWEREF99 1330" },
        { min: 14.5, max: 16.5, zone: "SWEREF99 1450" },
        { min: 16.5, max: 18.5, zone: "SWEREF99 1630" },
        { min: 18.5, max: 20.5, zone: "SWEREF99 1800" }
    ];
    let zone = zones.find(z => lng >= z.min && lng < z.max);
    return zone ? zone.zone : "Okänd zon";
}

// Uppdaterar resultattabellen
function updateResults(data) {
    let resultBody = document.getElementById("result-body");
    resultBody.innerHTML = "";
    
    const translations = {
        "detectedFormat": "Upptäckt format",
        "lat": "Nord",
        "lon": "Öst",
        "north": "Nord",
        "east": "Öst",
        "elevation": "Höjd",
        "sweref99_zone": "SWEREF99-zon"
    };
    
    Object.keys(data).forEach(key => {
        let displayKey = translations[key] || key;
        let value = data[key];
        
        if (typeof value === 'object' && value !== null) {
            value = Object.entries(value)
                .map(([subKey, subValue]) => `${translations[subKey] || subKey}: ${subValue}`)
                .join("<br>");
        }
        let tooltip = key === "sweref99_zone" ? `title='Zon baserad på longitud'` : "";
        let row = `<tr><td ${tooltip}>${displayKey}</td><td>${value}</td></tr>`;
        resultBody.innerHTML += row;
    });
    
    document.getElementById("result").classList.remove("hidden");
    console.log("[DEBUG] Resultattabell uppdaterad");
}

function cleanCoordinates(input) {
    return input
        .replace(/[^\d.,-]/g, '')  // Tar bort allt utom siffror, punkt, komma och minustecken
        .replace(/,+/g, ',')       // Ersätter flera kommatecken med ett enda
        .replace(/\s+/g, '');      // Tar bort mellanslag
}

// Uppdaterad händelsehantering för konvertering
document.getElementById("convert-form").addEventListener("submit", function (e) {
    e.preventDefault();
    let rawCoords = document.getElementById("coordinates").value;
    let coords = cleanCoordinates(rawCoords);
    
    console.log(`[DEBUG] Rensade koordinater: ${coords}`);

    if (!coords) {
        console.error("[ERROR] Inga giltiga koordinater efter rensning!");
        return;
    }

    let [lat, lng] = coords.split(",").map(num => parseFloat(num.trim()));
    if (!isNaN(lat) && !isNaN(lng)) {
        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng]).addTo(map);
        }
        map.setView([lat, lng], 12);
        console.log(`[DEBUG] Markör placerad på: Nord=${lat}, Öst=${lng}`);
    } else {
        console.error("[ERROR] Felaktiga koordinater för markering!");
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
        fetchElevation(lat, lng, data);
    })
    .catch(error => console.error("[ERROR] Fel vid API-anrop:", error));
});
