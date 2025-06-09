/**
 * impex.js - Version 35 (Fixad event-hantering + fullständig import/export-hantering)
 *
 * Funktionalitet:
 * - Importera & konvertera koordinater från fil och textinput
 * - Hämta höjddata via Open-Elevation API
 * - Visa konverterade koordinater i en tabell
 * - Stöd för både manuell inmatning och filimport
 */

console.log("[DEBUG] Laddar impex.js - Version 35");

// Vänta tills DOM är redo innan event listeners sätts
document.addEventListener("DOMContentLoaded", function () {
    console.log("[DEBUG] DOM laddad, initierar event listeners");

    const convertButton = document.getElementById("convert-textarea");
    const importButton = document.getElementById("import-button");
    const fileInput = document.getElementById("import-file");

    if (convertButton) convertButton.addEventListener("click", handleTextInput);
    if (importButton) importButton.addEventListener("click", () => fileInput.click());
    if (fileInput) fileInput.addEventListener("change", handleFileImport);
});

// Hantera manuell inmatning
function handleTextInput() {
    console.log("[DEBUG] Konvertera-knappen för text klickad");

    let inputText = document.getElementById("coordinates-textarea")?.value;
    if (!inputText || !inputText.trim()) {
        console.warn("[WARNING] Ingen text angiven!");
        return;
    }

    console.log(`[DEBUG] Input-text mottagen (${inputText.length} tecken), skickar till processCoordinates`);
    processCoordinates(inputText);
}

// Hantera filimport
function handleFileImport(event) {
    console.log("[DEBUG] Fil har laddats in, startar importprocess");

    let file = event.target.files[0];
    if (!file) {
        console.warn("[WARNING] Ingen fil vald!");
        return;
    }

    console.log(`[DEBUG] Vald fil: ${file.name}, typ: ${file.type}`);

    let reader = new FileReader();
    reader.onload = function (e) {
        console.log(`[DEBUG] Fil laddad (${file.name}), försöker tolka format...`);
        processCoordinates(e.target.result);
    };

    reader.onerror = function () {
        console.error("[ERROR] Fel vid läsning av fil!");
    };

    reader.readAsText(file, "UTF-8");
}

// Bearbeta koordinater och skicka till API:et
async function processCoordinates(data) {
    console.log("[DEBUG] processCoordinates körs");

    let rows = data.split("\n").map(row => row.trim()).filter(row => row);
    if (rows.length === 0) {
        console.warn("[WARNING] Inga giltiga koordinatrader hittades!");
        return;
    }

    let formattedData = rows.map(row => {
        let [lat, lon] = row.replace(/;/g, ',').split(",");
        return { latitude: parseFloat(lat), longitude: parseFloat(lon) };
    }).filter(coord => !isNaN(coord.latitude) && !isNaN(coord.longitude));

    console.log(`[DEBUG] Förbereder att skicka ${formattedData.length} koordinater till API`, formattedData);

    try {
        const elevationResults = await fetchElevationData(formattedData);
        formattedData.forEach((point, index) => {
            point.elevation = elevationResults[index]?.elevation ?? 0;  // Om höjd saknas, sätt till 0 m
            point.sweref99_zone = getSweref99Zone(point.longitude);
        });

        console.log("[DEBUG] Elevation-data hämtad, skickar till konverterings-API");
        sendToConversionAPI(formattedData);
    } catch (error) {
        console.error("[ERROR] Fel vid hämtning av höjddata:", error);
    }
}

// Hämta höjddata från Open-Elevation API
async function fetchElevationData(locations) {
    console.log("[DEBUG] Hämtar höjddata för", locations.length, "punkter");

    if (locations.length === 0) return [];

    let requestBody = { locations: locations };

    try {
        const response = await fetch("https://api.open-elevation.com/api/v1/lookup", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(requestBody)
        });

        const data = await response.json();
        console.log("[DEBUG] Höjddata mottagen", data);
        return data.results;
    } catch (error) {
        console.error("[ERROR] Höjd-API misslyckades:", error);
        return locations.map(() => ({ elevation: 0 })); // Om API misslyckas, returnera 0m höjd
    }
}

// Skicka till koordinat-konverterings-API
async function sendToConversionAPI(locations) {
    console.log("[DEBUG] Skickar koordinater till API:", locations);

    if (!Array.isArray(locations) || locations.length === 0) {
        console.error("[ERROR] Ingen giltig data att skicka till API!");
        return;
    }

    let results = [];

    for (let coord of locations) {
        const payload = { coordinates: `${coord.latitude},${coord.longitude}` };

        console.log("[DEBUG] Payload som skickas till API:", JSON.stringify(payload));

        try {
            const response = await fetch("https://mackan.eu/verktyg/koordinat/api/convert.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(payload)
            });

            const data = await response.json();
            console.log("[DEBUG] API-svar mottaget för", coord, ":", data);

            if (data.error) {
                console.error(`[ERROR] API-fel för koordinat ${coord.latitude},${coord.longitude}:`, data.error);
                continue;
            }

            data.elevation = coord.elevation ?? 0;
            data.sweref99_zone = getSweref99Zone(coord.longitude);
            results.push(data);
        } catch (error) {
            console.error("[ERROR] Fel vid API-anrop:", error);
        }
    }

    if (results.length > 0) {
        displayResults(results);
    } else {
        console.warn("[WARNING] Inga giltiga koordinater kunde konverteras.");
    }
}

// Visa resultaten i tabellen
function displayResults(results) {
    let resultBody = document.getElementById("result-body");
    resultBody.innerHTML = "";

    results.forEach(data => {
        let row = document.createElement("tr");
        row.innerHTML = `<td>${data.latitude}</td><td>${data.longitude}</td><td>${data.elevation} m</td><td>${data.sweref99_zone}</td>`;
        resultBody.appendChild(row);
    });

    console.log("[DEBUG] Resultattabell uppdaterad");
}

// Bestäm SWEREF99-zon baserat på longitud
function getSweref99Zone(longitude) {
    return longitude >= 10.5 && longitude < 18.5 ? "SWEREF99 TM" : "Okänd zon";
}
