// *********************** START: Filuppladdning och CSV-hantering ***********************

function handleFileUpload() {
    console.log("handleFileUpload körs");
    const fileInput = document.getElementById('csvFileInput');
    const file = fileInput.files[0];

    if (!file) {
        alert('Vänligen välj en CSV-fil.');
        return;
    }

    const reader = new FileReader();

    reader.onload = function (event) {
        console.log("FileReader laddat filen");
        const csvContent = event.target.result;

        document.getElementById('csvInput').value = csvContent;
        validateCsv(csvContent);
        updateCsvStatistics(csvContent);
        updateColumnFilter(csvContent);
        updateLivePreview(csvContent);

        // Lägg till event listeners för kryssrutorna
        document.getElementById('minifyCheckbox').addEventListener('change', () => updateLivePreview(csvContent));
        document.getElementById('transposeCheckbox').addEventListener('change', () => updateLivePreview(csvContent));
    };

    reader.readAsText(file, 'UTF-8');
}

function validateCsv(csvContent) {
    console.log("validateCsv körs");
    const rows = csvContent.split(/\r?\n/).filter(row => row.trim() !== '');
    const columnCounts = rows.map(row => row.split(',').length);
    const isValid = columnCounts.every(count => count === columnCounts[0]);

    const validationOutput = document.getElementById('validationOutput');
    if (validationOutput) {
        validationOutput.textContent = isValid ? 'CSV-filen är korrekt formaterad!' : 'Fel: CSV-filen har inkonsekventa kolumnantal!';
        validationOutput.style.color = isValid ? 'green' : 'red';
    }
}

function updateCsvStatistics(csvContent) {
    const rows = csvContent.split(/\r?\n/).filter(row => row.trim() !== '');
    const totalCharacters = csvContent.length;
    const totalRows = rows.length;
    const totalCells = rows.reduce((acc, row) => acc + row.split(',').length, 0);
    const headers = rows[0] ? rows[0].split(',') : [];

    const statsMessage = `
        Antal tecken: ${totalCharacters}
        Antal rader: ${totalRows}
        Antal celler: ${totalCells}
        Kolumnrubriker: ${headers.join(', ')}
    `;

    const statsElement = document.getElementById('csvStats');
    statsElement.textContent = statsMessage.trim();
}

function updateColumnFilter(csvContent) {
    const rows = csvContent.split(/\r?\n/).filter(row => row.trim() !== '');
    const headers = rows[0].split(',');

    const columnFilterContainer = document.getElementById('columnFilter');
    columnFilterContainer.innerHTML = '';

    headers.forEach((header, index) => {
        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.id = `column-${index}`;
        checkbox.value = header;
        checkbox.checked = true;
        checkbox.addEventListener('change', () => updateLivePreview(csvContent));

        const label = document.createElement('label');
        label.htmlFor = `column-${index}`;
        label.textContent = header;

        columnFilterContainer.appendChild(checkbox);
        columnFilterContainer.appendChild(label);
    });
}

// *********************** SLUT: Filuppladdning och CSV-hantering ***********************

// *********************** START: Förhandsgranskning och JSON-output ***********************

function updateLivePreview(csvContent) {
    console.log("updateLivePreview körs");

    const rows = csvContent.split(/\r?\n/).filter(row => row.trim() !== '');
    if (rows.length === 0) return;

    const headers = rows[0].split(',');
    const dataRows = rows.slice(1).map(row => row.split(','));
    const selectedColumns = Array.from(document.querySelectorAll('#columnFilter input:checked')).map(input => input.value);
    const isMinify = document.getElementById('minifyCheckbox').checked;
    const isTranspose = document.getElementById('transposeCheckbox').checked;

    let jsonData;

    if (isTranspose) {
        jsonData = transposeData(headers, dataRows);
    } else {
        jsonData = dataRows.map(row => {
            const obj = {};
            headers.forEach((header, index) => {
                if (selectedColumns.includes(header)) {
                    obj[header] = row[index];
                }
            });
            return obj;
        });
    }

    const readableJson = JSON.stringify(jsonData, null, isMinify ? 0 : 2);
    updateTextareaContent('livePreview', readableJson, isMinify);
    updateTextareaContent('jsonOutput', readableJson, isMinify);
}

function updateTextareaContent(elementId, content, isMinify) {
    const element = document.getElementById(elementId);
    element.innerHTML = '';

    const textarea = document.createElement('textarea');
    textarea.value = content;
    textarea.rows = isMinify ? 4 : 10;
    textarea.style.width = '100%';
    textarea.style.resize = 'none';
    element.appendChild(textarea);
}

function transposeData(headers, rows) {
    const transposed = rows[0].map((_, colIndex) => rows.map(row => row[colIndex]));
    const transposedHeaders = headers.map((_, colIndex) => `Kolumn ${colIndex + 1}`);
    return transposed.map((row, rowIndex) => {
        const obj = {};
        row.forEach((value, colIndex) => {
            obj[transposedHeaders[colIndex]] = value;
        });
        return obj;
    });
}

// *********************** SLUT: Förhandsgranskning och JSON-output ***********************

// *********************** START: Exportfunktioner ***********************

/**
 * Laddar ner JSON-innehållet som en fil.
 */
function downloadJson() {
    const jsonOutput = document.getElementById('jsonOutput').querySelector('textarea').value;
    const blob = new Blob([jsonOutput], { type: 'application/json' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'converted.json';
    link.click();
}

/**
 * Kopierar JSON-innehållet till urklipp.
 */
function copyToClipboard() {
    const jsonOutput = document.getElementById('jsonOutput').querySelector('textarea').value;
    navigator.clipboard.writeText(jsonOutput)
        .then(() => alert('JSON kopierat till urklipp!'))
        .catch(err => console.error('Kunde inte kopiera till urklipp:', err));
}

// *********************** SLUT: Exportfunktioner ***********************

