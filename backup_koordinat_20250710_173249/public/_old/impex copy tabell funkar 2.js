/**
 * impex.js - Version 13 (Full Debugging + Elevation API)
 * 
 * Funktionalitet:
 * - Importera & konvertera koordinater från fil och textinput
 * - Hämta höjddata via Open-Elevation API
 * - Exportera resultat till CSV, JSON och Excel
 */

console.log("[DEBUG] Laddar impex.js - Version 13");

// Vänta tills DOM är redo innan event listeners sätts
document.addEventListener("DOMContentLoaded", function () {
    console.log("[DEBUG] DOM laddad, initierar event listeners");

    // Korrekt knappreferenser från HTML
    const convertButton = document.getElementById("convert-textarea");
    const importButton = document.getElementById("import-button");
    const exportCsvButton = document.getElementById("export-csv");
    const exportJsonButton = document.getElementById("export-json");
    const exportXlsxButton = document.getElementById("export-xlsx");

    if (!convertButton) console.error("[ERROR] convert-textarea saknas i DOM!");
    else convertButton.addEventListener("click", handleTextInput);

    if (!importButton) console.error("[ERROR] import-button saknas i DOM!");
    else importButton.addEventListener("click", handleFileImport);

    if (!exportCsvButton || !exportJsonButton || !exportXlsxButton) {
        console.error("[ERROR] Export-knappar saknas i DOM!");
    } else {
        exportCsvButton.addEventListener("click", () => exportResults("csv"));
        exportJsonButton.addEventListener("click", () => exportResults("json"));
        exportXlsxButton.addEventListener("click", () => exportResults("xlsx"));
    }
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

// Hämta höjdinformation från Open-Elevation API
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
    console.log("[DEBUG] Skickar koordinater till API (en åt gången):", locations);

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
                continue; // Hoppa över felaktiga svar
            }

            // 🔥 Se till att höjden finns kvar i resultatet!
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
// Visa resultaten i tabellen
function displayResults(results) {
    console.log("[DEBUG] Uppdaterar resultattabellen med", results.length, "rader");

    let resultTable = document.getElementById("result-table");
    let resultBody = document.getElementById("result-body");

    if (!resultBody || !resultTable) {
        console.error("[ERROR] result-body eller result-table saknas i DOM!");
        return;
    }

    // 🔥 Se till att tabellhuvudet är korrekt!
    resultTable.innerHTML = `
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
    `;

    resultBody = document.getElementById("result-body"); // Hämta om efter uppdatering

    resultBody.innerHTML = "";

    results.forEach((data, index) => {
        console.log(`[DEBUG] Lägger till rad ${index + 1}:`, data);

        let swerefZone = getSwerefZone(data.sweref99?.east, data.sweref99?.north);

        let row = document.createElement("tr");
        
        row.innerHTML = `
            <td>${data.wgs84?.lat || "N/A"}</td>
            <td>${data.wgs84?.lon || "N/A"}</td>
            <td>${data.sweref99?.north || "N/A"}</td>
            <td>${data.sweref99?.east || "N/A"}</td>
            <td>${data.rt90?.north || "N/A"}</td>
            <td>${data.rt90?.east || "N/A"}</td>
            <td>${data.elevation ?? "Ej tillgänglig"}</td>
            <td>${swerefZone || "Ej tillgänglig"}</td>
        `;

        resultBody.appendChild(row);
    });

    console.log("[DEBUG] Tabelluppdatering slutförd");
}


// Funktion för att beräkna SWEREF-zon (Placeholder)
function getSwerefZone(east, north) {
    if (!east || !north) return null;

    // Placeholder-logik: Beroende på vilket intervall East/North ligger i
    if (east >= 200000 && east < 300000) return "SWEREF99 13 30";
    if (east >= 300000 && east < 400000) return "SWEREF99 15 00";
    if (east >= 400000 && east < 500000) return "SWEREF99 18 00";
    if (east >= 500000 && east < 600000) return "SWEREF99 21 00";

    return "Okänd zon";
}

