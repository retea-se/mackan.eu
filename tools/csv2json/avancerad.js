// *********************** START: Filuppladdning ***********************
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
        console.log("FileReader laddade filen");
        const csvContent = event.target.result;

        // Visa CSV-innehållet i textarean
        document.getElementById('csvInput').value = csvContent;

        // Kör statistik och andra funktioner
        displayCsvStatistics(csvContent);
        populateNestedOptions(csvContent);
    };

    // Läs filen med vald teckenkodning
    const selectedEncoding = document.getElementById('encodingSelect').value;
    reader.readAsText(file, selectedEncoding);
}
// *********************** SLUT: Filuppladdning ***********************

// *********************** START: Statistik för CSV ***********************
function displayCsvStatistics(csvContent) {
    console.log("displayCsvStatistics körs");
    const rows = csvContent.split(/\r?\n/).filter(row => row.trim() !== '');
    const columnCount = rows[0]?.split(',').length || 0;

    const statsMessage = `
        Rader: ${rows.length} 
        Kolumner: ${columnCount} 
        Första raden: ${rows[0]}
    `;

    console.log("Statistik för CSV:", statsMessage);
    const statsOutput = document.getElementById('csvStats');
    statsOutput.textContent = statsMessage.trim();
}
// *********************** SLUT: Statistik för CSV ***********************

// *********************** START: Nested JSON-struktur ***********************
function populateNestedOptions(csvContent) {
    console.log("populateNestedOptions körs");
    const rows = csvContent.split(/\r?\n/).filter(row => row.trim() !== '');
    const headers = rows[0]?.split(',') || [];

    const optionsContainer = document.getElementById('nestedStructureOptions');
    optionsContainer.innerHTML = ''; // Rensa tidigare innehåll

    headers.forEach((header, index) => {
        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.id = `nested-col-${index}`;
        checkbox.value = header;

        const label = document.createElement('label');
        label.htmlFor = `nested-col-${index}`;
        label.textContent = header;

        optionsContainer.appendChild(checkbox);
        optionsContainer.appendChild(label);
        optionsContainer.appendChild(document.createElement('br'));
    });
}

function generateNestedJson() {
    console.log("generateNestedJson körs");
    const csvContent = document.getElementById('csvInput').value.trim();
    const rows = csvContent.split(/\r?\n/).filter(row => row.trim() !== '');
    const headers = rows[0]?.split(',') || [];
    const dataRows = rows.slice(1).map(row => row.split(','));

    const selectedColumns = Array.from(document.querySelectorAll('#nestedStructureOptions input:checked')).map(input => input.value);

    const nestedJson = dataRows.map(row => {
        const nestedObj = {};
        const flatObj = {};

        headers.forEach((header, index) => {
            if (selectedColumns.includes(header)) {
                nestedObj[header] = row[index];
            } else {
                flatObj[header] = row[index];
            }
        });

        return { ...flatObj, nested: nestedObj };
    });

    console.log("Genererad Nested JSON:", nestedJson);
    document.getElementById('nestedJsonOutput').textContent = JSON.stringify(nestedJson, null, 4);
}
// *********************** SLUT: Nested JSON-struktur ***********************
