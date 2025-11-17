/**
 * impex.js - Version 37 (Modal-baserade meddelanden)
 *
 * Funktionalitet:
 * - Importera & konvertera koordinater från fil och textinput
 * - Hämta höjddata via Open-Elevation API
 * - Visa konverterade koordinater i en tabell
 * - Stöd för både manuell inmatning och filimport
 */

console.log("[DEBUG] Laddar impex.js - Version 37");

// Import modal utilities
import { showAlert } from '/js/modal-utils.js';

// Hantera manuell inmatning
async function handleTextInput() {
    console.log("[DEBUG] Konvertera-knappen för text klickad");

    const textInput = document.getElementById("text-input");
    const inputText = textInput?.value;

    if (!inputText || !inputText.trim()) {
        console.warn("[WARNING] Ingen text angiven!");
        await showAlert("Klistra in koordinater i textfältet", "Information");
        return;
    }

    console.log(`[DEBUG] Input-text mottagen (${inputText.length} tecken), skickar till processCoordinates`);

    // Visa processing section
    const processingSection = document.getElementById("processing-section");
    if (processingSection) {
        processingSection.classList.remove("hidden");
    }

    processCoordinates(inputText);
}

// Hantera filimport
async function handleFileImport(event) {
    console.log("[DEBUG] Fil har laddats in, startar importprocess");

    const file = event.target?.files?.[0] || event.files?.[0];
    if (!file) {
        console.warn("[WARNING] Ingen fil vald!");
        await showAlert("Välj en fil att importera", "Information");
        return;
    }

    console.log(`[DEBUG] Vald fil: ${file.name}, typ: ${file.type}`);

    // Visa processing section
    const processingSection = document.getElementById("processing-section");
    if (processingSection) {
        processingSection.classList.remove("hidden");
    }

    const reader = new FileReader();
    reader.onload = function (e) {
        console.log(`[DEBUG] Fil laddad (${file.name}), försöker tolka format...`);
        processCoordinates(e.target.result);
    };

    reader.onerror = async function () {
        console.error("[ERROR] Fel vid läsning av fil!");
        await showAlert("Fel vid läsning av fil. Kontrollera att filen är giltig.", "Fel");
    };

    reader.readAsText(file, "UTF-8");
}

// Vänta tills DOM är redo innan event listeners sätts
document.addEventListener("DOMContentLoaded", function () {
    console.log("[DEBUG] DOM laddad, initierar event listeners");

    const importBtn = document.getElementById("import-btn");
    const fileInput = document.getElementById("file-input");
    const textInput = document.getElementById("text-input");
    const clearImport = document.getElementById("clear-import");
    const processBtn = document.getElementById("process-btn");
    const exportCsv = document.getElementById("export-csv");
    const exportJson = document.getElementById("export-json");

    if (importBtn) {
        importBtn.addEventListener("click", async function() {
            if (fileInput && fileInput.files.length > 0) {
                await handleFileImport({ target: { files: [fileInput.files[0]] } });
            } else if (textInput && textInput.value.trim()) {
                await handleTextInput();
            } else {
                await showAlert("Välj en fil eller klistra in koordinater", "Information");
            }
        });
    }

    if (fileInput) {
        fileInput.addEventListener("change", handleFileImport);
    }

    if (textInput) {
        textInput.addEventListener("keydown", async function(e) {
            if (e.ctrlKey && e.key === 'Enter') {
                e.preventDefault();
                await handleTextInput();
            }
        });
    }

    if (clearImport) {
        clearImport.addEventListener("click", function() {
            if (fileInput) fileInput.value = '';
            if (textInput) textInput.value = '';
            const resultsSection = document.getElementById("results-section");
            const processingSection = document.getElementById("processing-section");
            if (resultsSection) resultsSection.classList.add("hidden");
            if (processingSection) processingSection.classList.add("hidden");
        });
    }

    if (processBtn) {
        processBtn.addEventListener("click", function() {
            // Process button logic will be handled by existing code
            console.log("[DEBUG] Process button clicked");
        });
    }
});

// Bearbeta koordinater och skicka till API:et
async function processCoordinates(data) {
    console.log("[DEBUG] processCoordinates körs");

    const rows = data.split("\n").map(row => row.trim()).filter(row => row);
    if (rows.length === 0) {
        console.warn("[WARNING] Inga giltiga koordinatrader hittades!");
        await showAlert("Inga giltiga koordinater hittades. Kontrollera formatet.", "Varning");
        return;
    }

    const formattedData = rows.map(row => {
        const parts = row.replace(/;/g, ',').split(",").map(p => p.trim());
        if (parts.length >= 2) {
            return {
                latitude: parseFloat(parts[0]),
                longitude: parseFloat(parts[1])
            };
        }
        return null;
    }).filter(coord => coord && !isNaN(coord.latitude) && !isNaN(coord.longitude));

    if (formattedData.length === 0) {
        await showAlert("Inga giltiga koordinater kunde parsas. Kontrollera formatet (lat,lon per rad).", "Varning");
        return;
    }

    console.log(`[DEBUG] Förbereder att skicka ${formattedData.length} koordinater till API`);

    // Visa loading state
    const statusInfo = document.getElementById("status-info");
    if (statusInfo) {
        statusInfo.textContent = `Bearbetar ${formattedData.length} koordinater...`;
    }

    try {
        const elevationResults = await fetchElevationData(formattedData);
        formattedData.forEach((point, index) => {
            point.elevation = elevationResults[index]?.elevation ?? 0;
            point.sweref99_zone = getSweref99Zone(point.longitude);
        });

        console.log("[DEBUG] Elevation-data hämtad, skickar till konverterings-API");
        await sendToConversionAPI(formattedData);

        if (statusInfo) {
            statusInfo.textContent = `${formattedData.length} koordinater konverterade.`;
        }
    } catch (error) {
        console.error("[ERROR] Fel vid hämtning av höjddata:", error);
        if (statusInfo) {
            statusInfo.textContent = "Fel vid bearbetning av koordinater.";
        }
        await showAlert("Ett fel uppstod vid bearbetning av koordinater. Försök igen.", "Fel");
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
            const response = await fetch("api/convert.php", {
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
    const resultBody = document.getElementById("results-body");
    const resultsSection = document.getElementById("results-section");
    const exportCsv = document.getElementById("export-csv");
    const exportJson = document.getElementById("export-json");

    if (!resultBody) {
        console.error("[ERROR] Results body not found!");
        return;
    }

    resultBody.innerHTML = "";

    results.forEach((data, index) => {
        const lat = data.wgs84?.lat ?? "N/A";
        const lon = data.wgs84?.lon ?? "N/A";
        const elevation = data.elevation ?? "N/A";
        const sweref99 = data.sweref99 ? `${data.sweref99.north?.toFixed(2) ?? 'N/A'}, ${data.sweref99.east?.toFixed(2) ?? 'N/A'}` : "N/A";
        const rt90 = data.rt90 ? `${data.rt90.north?.toFixed(2) ?? 'N/A'}, ${data.rt90.east?.toFixed(2) ?? 'N/A'}` : "N/A";
        const status = data.error ? "Fel" : "OK";

        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${index + 1}</td>
            <td>${lat}, ${lon}</td>
            <td>${sweref99}</td>
            <td>${rt90}</td>
            <td>${elevation} m</td>
            <td>${status}</td>
        `;
        resultBody.appendChild(row);
    });

    if (resultsSection) resultsSection.classList.remove("hidden");
    if (exportCsv) exportCsv.classList.remove("hidden");
    if (exportJson) exportJson.classList.remove("hidden");

    console.log("[DEBUG] Resultattabell uppdaterad med", results.length, "rader");
}


// Bestäm SWEREF99-zon baserat på longitud
function getSweref99Zone(longitude) {
    return longitude >= 10.5 && longitude < 18.5 ? "SWEREF99 TM" : "Okänd zon";
}
