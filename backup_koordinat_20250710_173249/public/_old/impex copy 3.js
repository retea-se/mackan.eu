/**
 * impex.js - Version 14 (Rensad export)
 * 
 * Funktionalitet:
 * - Importera & konvertera koordinater från fil och textinput
 * - Hämta höjddata via Open-Elevation API
 * - Visa konverterade koordinater i en tabell
 */

console.log("[DEBUG] Laddar impex.js - Version 14");

// Vänta tills DOM är redo innan event listeners sätts
document.addEventListener("DOMContentLoaded", function () {
    console.log("[DEBUG] DOM laddad, initierar event listeners");

    // Hämta knappar och element
    const convertButton = document.getElementById("convert-textarea");
    const importButton = document.getElementById("import-button");

    if (!convertButton) console.error("[ERROR] convert-textarea saknas i DOM!");
    else convertButton.addEventListener("click", handleTextInput);

    if (!importButton) console.error("[ERROR] import-button saknas i DOM!");
    else importButton.addEventListener("click", handleFileImport);
});

// Hantera filimport
function handleFileImport() {
    console.log("[DEBUG] Importknappen klickad");

    let fileInput = document.getElementById("file-input");
    if (!fileInput || !fileInput.files.length) {
        console.warn("[WARNING] Ingen fil vald!");
        return;
    }

    let file = fileInput.files[0];
    console.log(`[DEBUG] Vald fil: ${file.name}, typ: ${file.type}`);

    let reader = new FileReader();

    reader.onload = function (e) {
        console.log(`[DEBUG] Fil laddad (${file.name}), skickar till processCoordinates`);
        processCoordinates(e.target.result);
    };

    reader.onerror = function () {
        console.error("[ERROR] Fel vid läsning av fil!");
    };

    reader.readAsText(file, "UTF-8");
}

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

// Bearbeta koordinater och skicka till API:et
async function processCoordinates(data) {
    console.log("[DEBUG] processCoordinates körs");

    let rows = data.split("\n").map(row => row.trim()).filter(row => row);
    if (rows.length === 0) {
        console.warn("[WARNING] Inga giltiga koordinatrader hittades!");
        return;
    }

    let formattedData = rows.map(row => {
        let [lat, lon] = row.split(",");
        return { latitude: parseFloat(lat), longitude: parseFloat(lon) };
    });

    console.log(`[DEBUG] Förbereder att skicka ${formattedData.length} koordinater till API`, formattedData);

    try {
        const elevationResults = await fetchElevationData(formattedData);
        formattedData.forEach((point, index) => {
            point.elevation = elevationResults[index]?.elevation ?? 0;  // Om höjd saknas, sätt till 0 m
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
    console.log("[DEBUG] Uppdaterar resultattabellen med", results.length, "rader");

    let container = document.querySelector("main"); // För att lägga in tabellen dynamiskt om den saknas
    let resultTable = document.getElementById("result-table");
    let resultBody = document.getElementById("result-body");

    // 🔥 Om tabellen inte finns, skapa den dynamiskt!
    if (!resultTable) {
        console.warn("[WARNING] result-table saknas, skapar tabellen automatiskt!");

        let section = document.createElement("section");
        section.innerHTML = `
            <h2>Konverterade koordinater</h2>
            <table id="result-table" class="table">
                <thead>
                    <tr>
                        <th>Latitud (WGS84)</th>
                        <th>Longitud (WGS84)</th>
                        <th>Nord (SWEREF99)</th>
                        <th>Öst (SWEREF99)</th>
                        <th>Nord (RT90)</th>
                        <th>Öst (RT90)</th>
                        <th>Höjd (m)</th>
                        <th>SWEREF Zon</th>
                    </tr>
                </thead>
                <tbody id="result-body"></tbody>
            </table>
        `;

        container.appendChild(section);
        resultTable = document.getElementById("result-table");
        resultBody = document.getElementById("result-body");
    }

    resultBody.innerHTML = ""; // Rensa gammal data

    results.forEach(data => {
        let row = document.createElement("tr");
        row.innerHTML = `
            <td>${data.wgs84.lat}</td>
            <td>${data.wgs84.lon}</td>
            <td>${data.sweref99.north}</td>
            <td>${data.sweref99.east}</td>
            <td>${data.rt90.north}</td>
            <td>${data.rt90.east}</td>
            <td>${data.elevation}</td>
            <td>${data.sweref99_zone || "Ej tillgänglig"}</td>
        `;
        resultBody.appendChild(row);
    });

    console.log("[DEBUG] Tabelluppdatering slutförd");
}
