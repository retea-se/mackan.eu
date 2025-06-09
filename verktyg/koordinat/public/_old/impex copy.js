/**
 * impex.js - Version 12 (Full Debugging + Elevation API)
 * 
 * Funktionalitet:
 * - Importera & konvertera koordinater från fil och textinput
 * - Hämta höjddata via Open-Elevation API
 * - Exportera resultat till CSV, JSON och Excel
 */

console.log("[DEBUG] Laddar impex.js - Version 12");

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
    console.log("[DEBUG] Skickar koordinater till API:", locations);

    try {
        const response = await fetch("https://mackan.eu/verktyg/koordinat/api/convert.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ locations })
        });

        const results = await response.json();
        console.log("[DEBUG] API-svar mottaget", results);
        if (!Array.isArray(results)) {
            console.error("[ERROR] API-svaret är oväntat format:", results);
            return;
        }
        displayResults(results);
    } catch (error) {
        console.error("[ERROR] Fel vid API-anrop:", error);
    }
}

// Visa resultaten i tabellen
function displayResults(results) {
    console.log("[DEBUG] Uppdaterar resultattabellen med", results.length, "rader");

    let resultBody = document.getElementById("result-body");
    if (!resultBody) {
        console.error("[ERROR] result-body saknas i DOM!");
        return;
    }

    resultBody.innerHTML = "";

    results.forEach((data, index) => {
        console.log(`[DEBUG] Lägger till rad ${index + 1}:`, data);

        let row = document.createElement("tr");
        
        row.innerHTML = `
            <td>${data.wgs84?.lat || "N/A"}</td>
            <td>${data.wgs84?.lon || "N/A"}</td>
            <td>${data.sweref99?.north || "N/A"}</td>
            <td>${data.sweref99?.east || "N/A"}</td>
            <td>${data.rt90?.north || "N/A"}</td>
            <td>${data.rt90?.east || "N/A"}</td>
            <td>${data.elevation || "N/A"}</td>
            <td>${data.sweref99_zone || "Ej tillgänglig"}</td>
        `;

        resultBody.appendChild(row);
    });

    console.log("[DEBUG] Tabelluppdatering slutförd");
}
