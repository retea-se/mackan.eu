/**
 * impex.js - Version 33 (Fixad resultatvisning + borttagen importknapp)
 * 
 * Funktionalitet:
 * - Automatisk import av CSV, JSON, TXT vid filinläsning
 * - Visar format och koordinater korrekt i tabellen
 * - Loggar alla steg och fel i `console.log`
 */

console.log("[DEBUG] Laddar impex.js - Version 33");

// **Uppdaterad: `updateResultTable` för att visa WGS84, RT90, SWEREF99 korrekt**
function updateResultTable(data) {
    console.log("[DEBUG] Uppdaterar resultattabellen med konverterade koordinater");

    let resultBody = document.getElementById("result-body");
    resultBody.innerHTML = "";

    data.forEach(coord => {
        let row = document.createElement("tr");
        row.innerHTML = `
            <td>WGS84</td><td>${coord.latitude}, ${coord.longitude}</td>
            <td>RT90</td><td>${coord.rt90_north}, ${coord.rt90_east}</td>
            <td>SWEREF99</td><td>${coord.sweref99_north}, ${coord.sweref99_east}</td>
        `;
        resultBody.appendChild(row);
    });

    document.getElementById("result").classList.remove("hidden");
    console.log("[DEBUG] Resultattabell uppdaterad");
}

// **Flyttad upp: `processCoordinates` så att den definieras innan `handleFileImport`**
async function processCoordinates(data) {
    console.log("[DEBUG] processCoordinates körs");

    let rows = data.split("\n").map(row => row.trim()).filter(row => row);
    console.log(`[DEBUG] Antal rader funna i CSV/TXT: ${rows.length}`, rows);

    if (rows.length > 0 && /[a-zA-Z]/.test(rows[0])) {
        console.log("[DEBUG] Ignorerar första raden (rubrikrad)");
        rows.shift();
    }

    let formattedData = rows.map(row => {
        row = row.replace(/"/g, '');
        row = row.replace(/;/g, ',');
        let [lat, lon] = row.split(",");

        return { latitude: parseFloat(lat), longitude: parseFloat(lon) };
    }).filter(coord => !isNaN(coord.latitude) && !isNaN(coord.longitude));

    console.log(`[DEBUG] Förbereder att skicka ${formattedData.length} koordinater till API`, formattedData);

    if (formattedData.length > 0) {
        let convertedData = await sendToConversionAPI(formattedData);
        updateResultTable(convertedData);
    } else {
        console.warn("[WARNING] Inga giltiga koordinater kunde extraheras!");
    }
}

// **API-anrop för konvertering**
async function sendToConversionAPI(locations) {
    console.log("[DEBUG] Skickar koordinater till API:", locations);

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
            console.log("[DEBUG] API-svar mottaget:", data);

            if (data.error) {
                console.error(`[ERROR] API-fel: ${data.error}`);
                continue;
            }

            results.push({
                latitude: coord.latitude,
                longitude: coord.longitude,
                rt90_north: data.rt90.north,
                rt90_east: data.rt90.east,
                sweref99_north: data.sweref99.north,
                sweref99_east: data.sweref99.east,
            });

        } catch (error) {
            console.error("[ERROR] Fel vid API-anrop:", error);
        }
    }

    return results;
}

// **Vänta tills hela sidan är laddad innan event listeners sätts**
window.onload = function () {
    console.log("[DEBUG] DOM laddad, initierar event listeners");

    const fileInput = document.getElementById("import-file");

    console.log("[DEBUG] Kontroll av DOM-element:");
    console.log("fileInput:", fileInput);

    if (fileInput) {
        console.log("[DEBUG] Filinput hittad och kopplad - Startar import vid filinläsning");
        fileInput.addEventListener("change", handleFileImport);
    } else {
        console.error("[ERROR] Filinput saknas i DOM!");
    }
};
