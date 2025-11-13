// *********************** START: Formatterad och Komprimerad JSON ***********************

/**
 * Uppdaterar JSON-output beroende på valt format (formatterad eller komprimerad).
 */
function updateJsonFormat() {
    console.log("updateJsonFormat körs");
    const jsonOutput = document.getElementById('jsonOutput');
    const livePreview = document.getElementById('livePreview');
    const selectedFormat = document.querySelector('input[name="jsonFormat"]:checked').value;

    if (!jsonOutput.textContent.trim()) {
        console.warn("Ingen JSON-data att formatera");
        return;
    }

    if (selectedFormat === "formatted") {
        livePreview.textContent = JSON.stringify(JSON.parse(jsonOutput.textContent), null, 2);
    } else {
        livePreview.textContent = JSON.stringify(JSON.parse(jsonOutput.textContent));
    }

    console.log("JSON-output uppdaterad till format:", selectedFormat);
}

// *********************** SLUT: Formatterad och Komprimerad JSON ***********************

// *********************** START: JSON till CSV-konvertering ***********************

/**
 * Konverterar JSON-data som klistrats in till CSV-format och visar det i en output.
 */
function convertJsonToCsv() {
    console.log("convertJsonToCsv körs");
    const jsonInput = document.getElementById('jsonToCsvInput').value;
    const csvOutput = document.getElementById('csvOutput');

    try {
        const jsonData = JSON.parse(jsonInput);
        const headers = Object.keys(jsonData[0]);
        const csvRows = jsonData.map(row => headers.map(header => row[header] || "").join(','));

        csvOutput.textContent = [headers.join(','), ...csvRows].join('\n');
        console.log("JSON till CSV-konvertering slutförd");
    } catch (error) {
        console.error("Fel vid JSON till CSV-konvertering:", error.message);
        csvOutput.textContent = "Fel: Ogiltig JSON!";
    }
}

// *********************** SLUT: JSON till CSV-konvertering ***********************

// *********************** START: Kombinera flera CSV-filer ***********************

/**
 * Kombinerar data från flera CSV-filer till en sammanhängande CSV-output.
 */
function combineCsvFiles() {
    console.log("combineCsvFiles körs");
    const files = document.getElementById('multiCsvInput').files;
    const combinedData = [];
    const headersSet = new Set();

    if (files.length < 2) {
        alert("Vänligen välj minst två CSV-filer!");
        return;
    }

    let processedFiles = 0;

    Array.from(files).forEach(file => {
        const reader = new FileReader();
        reader.onload = function (event) {
            const rows = event.target.result.split(/\r?\n/).filter(row => row.trim() !== '');
            const headers = rows[0].split(',').map(header => header.trim());
            headers.forEach(header => headersSet.add(header));

            const dataRows = rows.slice(1).map(row => {
                const columns = row.split(',').map(value => value.trim());
                const obj = {};
                headers.forEach((header, index) => {
                    obj[header] = columns[index] || "";
                });
                combinedData.push(obj);
            });

            processedFiles++;
            if (processedFiles === files.length) {
                const combinedHeaders = Array.from(headersSet);
                const csvRows = combinedData.map(row => combinedHeaders.map(header => row[header] || "").join(','));
                document.getElementById('combinedCsvOutput').textContent = [combinedHeaders.join(','), ...csvRows].join('\n');
                console.log("Flera CSV-filer har kombinerats framgångsrikt");
            }
        };
        reader.readAsText(file, 'UTF-8');
    });
}

// *********************** SLUT: Kombinera flera CSV-filer ***********************

// *********************** START: Avancerad datatypshantering ***********************

/**
 * Identifierar och konverterar kolumner i CSV-data till rätt datatyper (nummer, boolean, datum).
 */
function convertDataTypes() {
    console.log("convertDataTypes körs");
    const csvContent = document.getElementById('csvInput').value;
    const rows = csvContent.split(/\r?\n/).filter(row => row.trim() !== '');
    if (rows.length === 0) {
        console.warn("Ingen CSV-data att hantera");
        return;
    }

    const headers = rows[0].split(',');
    const dataRows = rows.slice(1).map(row => row.split(','));

    const convertedData = dataRows.map(row => {
        const obj = {};
        headers.forEach((header, index) => {
            let value = row[index]?.trim();
            if (!isNaN(value) && value !== "") {
                obj[header] = Number(value);
            } else if (value?.toLowerCase() === "true" || value?.toLowerCase() === "false") {
                obj[header] = value.toLowerCase() === "true";
            } else if (Date.parse(value)) {
                obj[header] = new Date(value).toISOString();
            } else {
                obj[header] = value;
            }
        });
        return obj;
    });

    console.log("Konverterad data:", convertedData);
    document.getElementById('jsonOutput').textContent = JSON.stringify(convertedData, null, 2);
}

// *********************** SLUT: Avancerad datatypshantering ***********************
