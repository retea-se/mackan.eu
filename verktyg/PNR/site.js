document.addEventListener('DOMContentLoaded', () => {
    loadData();
    document.getElementById('generator-form').addEventListener('submit', (e) => {
        e.preventDefault();
        generateSites();
    });

    // Koppla exportknappar
    setupExportButtons();
});

let dataDatabase = [];
let siteCodes = {};

async function loadData() {
    try {
        const response = await fetch('data.csv');
        const data = await response.text();
        dataDatabase = Papa.parse(data, { header: true, skipEmptyLines: true }).data;
    } catch (error) {
        console.error('Error loading data:', error);
    }
}

function generateSites() {
    const numRecords = parseInt(document.getElementById('num-records').value);
    const generatedData = [];
    const usedAddresses = new Set();

    for (let i = 0; i < numRecords; i++) {
        const randomIndex = Math.floor(Math.random() * dataDatabase.length);
        const randomRecord = dataDatabase[randomIndex];
        let streetName = randomRecord.street.trim();
        let houseNumber = Math.floor(Math.random() * 100) + 1;

        while (usedAddresses.has(`${streetName} ${houseNumber}`)) {
            houseNumber = Math.floor(Math.random() * 100) + 1;
        }

        usedAddresses.add(`${streetName} ${houseNumber}`);

        let siteCodeBase = streetName.substring(0, 4).toUpperCase();
        if (!siteCodes[siteCodeBase]) {
            siteCodes[siteCodeBase] = 1;
        } else {
            siteCodes[siteCodeBase]++;
        }

        let siteCode = `${siteCodeBase}${String(siteCodes[siteCodeBase]).padStart(2, '0')}`;
        generatedData.push({ SITE: siteCode, Adress: `${streetName} ${houseNumber}` });
    }

    populateTable(generatedData);
}

function populateTable(data) {
    const table = document.getElementById('result-table');
    const exportButtons = document.getElementById('export-buttons');

    // Lägg till rubriker
    const columns = ['SITE', 'Adress'];
    const thead = document.createElement('thead');
    const headerRow = document.createElement('tr');

    columns.forEach(col => {
        const th = document.createElement('th');
        th.textContent = col;
        headerRow.appendChild(th);
    });

    thead.appendChild(headerRow);
    table.innerHTML = '';
    table.appendChild(thead);

    // Fyll tabellen
    const tbody = document.createElement('tbody');

    data.forEach(row => {
        const tr = document.createElement('tr');
        Object.values(row).forEach(value => {
            const td = document.createElement('td');
            td.textContent = value;
            tr.appendChild(td);
        });
        tbody.appendChild(tr);
    });

    table.appendChild(tbody);

    // Visa exportknappar om data finns
    if (data.length > 0) {
        exportButtons.style.display = 'flex';
        exportButtons.classList.remove('hidden');
    } else {
        exportButtons.style.display = 'none';
        exportButtons.classList.add('hidden');
    }
}

// Koppla exportknappar
function setupExportButtons() {
    const exportCsvButton = document.getElementById('export-to-csv');
    const exportExcelButton = document.getElementById('export-to-excel');
    const exportJsonButton = document.getElementById('export-to-json');

    if (exportCsvButton) {
        exportCsvButton.addEventListener('click', () => {
            console.log('Exportera till CSV-knappen klickades.');
            const tableData = getTableData();
            exportToCsv(tableData);
        });
    }

    if (exportExcelButton) {
        exportExcelButton.addEventListener('click', () => {
            console.log('Exportera till Excel-knappen klickades.');
            const tableData = getTableData();
            exportToExcel(tableData);
        });
    }

    if (exportJsonButton) {
        exportJsonButton.addEventListener('click', () => {
            console.log('Exportera till JSON-knappen klickades.');
            const tableData = getTableData();
            exportToJson(tableData);
        });
    }
}

// Hämta data från tabellen
function getTableData() {
    const table = document.getElementById('result-table');
    const rows = table.querySelectorAll('tbody tr');
    const headers = Array.from(table.querySelectorAll('thead th')).map(th => th.textContent);

    const data = Array.from(rows).map(row => {
        const cells = row.querySelectorAll('td');
        const rowData = {};
        cells.forEach((cell, index) => {
            rowData[headers[index]] = cell.textContent;
        });
        return rowData;
    });

    console.log('Hämtad tabelldata för export:', data);
    return data;
}

// Exportera till CSV
function exportToCsv(data) {
    const csv = Papa.unparse(data);
    const blob = new Blob(["\uFEFF" + csv], { type: 'text/csv;charset=utf-8' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = `site_data_${new Date().toISOString().slice(0, 10)}.csv`;
    link.click();
    console.log('CSV-export slutförd.');
}

// Exportera till Excel
function exportToExcel(data) {
    const workbook = XLSX.utils.book_new();
    const worksheet = XLSX.utils.json_to_sheet(data);
    XLSX.utils.book_append_sheet(workbook, worksheet, 'Sites');
    XLSX.writeFile(workbook, `site_data_${new Date().toISOString().slice(0, 10)}.xlsx`);
    console.log('Excel-export slutförd.');
}

// Exportera till JSON
function exportToJson(data) {
    const json = JSON.stringify(data, null, 2);
    const blob = new Blob([json], { type: 'application/json;charset=utf-8' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = `site_data_${new Date().toISOString().slice(0, 10)}.json`;
    link.click();
    console.log('JSON-export slutförd.');
}
