document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM laddat och klart.');
    loadData();

    // Initiera datepickers med loggning vid datumval
    $(".datepicker").datepicker({
        dateFormat: 'yy-mm-dd',
        onSelect: function(dateText) {
            console.log(`Datum valt: ${dateText} i fältet med id "${this.id}"`);
        }
    });

    // Koppla formulärets submit-händelse
    const form = document.getElementById('generator-form');
    if (form) {
        console.log('Formulär hittat. Kopplar event-lyssnare för submit.');
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            console.log('Generera-knappen klickades. Startar generering...');
            generateAlarms();
        });
    } else {
        console.error('Formulär med id "generator-form" hittades inte.');
    }

    // Koppla exportknappar
    setupExportButtons();
});

let dataDatabase = [];
let siteCodes = {};

// Ladda data från CSV
async function loadData() {
    try {
        console.log('Startar laddning av data...');
        const response = await fetch('data.csv');
        const data = await response.text();
        dataDatabase = Papa.parse(data, { delimiter: ';', header: true, skipEmptyLines: true }).data;
        console.log('Data laddad:', dataDatabase);
    } catch (error) {
        console.error('Fel vid laddning av data:', error);
    }
}

// Generera larmdata
function generateAlarms() {
    console.log('Startar larmgenerering...');
    const startDateInput = document.getElementById('start-date');
    const endDateInput = document.getElementById('end-date');

    if (!startDateInput || !endDateInput) {
        console.error('Startdatum eller slutdatum hittades inte i DOM.');
        alert('Vänligen välj både startdatum och slutdatum.');
        return;
    }

    const startDate = new Date(startDateInput.value);
    const endDate = new Date(endDateInput.value);

    console.log(`Valda datumintervall: ${startDate} till ${endDate}`);

    if (isNaN(startDate.getTime()) || isNaN(endDate.getTime()) || startDate >= endDate) {
        console.error('Ogiltigt datumintervall.');
        alert('Vänligen ange ett giltigt datumintervall.');
        return;
    }

    const numRecords = parseInt(document.getElementById('num-records').value);
    const randomizeSite = document.getElementById('randomize-site').checked;
    const randomizeResolved = document.getElementById('randomize-resolved').checked; // Nytt alternativ
    console.log(`Antal larm att generera: ${numRecords}`);
    console.log(`Slumpa site: ${randomizeSite}`);
    console.log(`Slumpa återställda larm: ${randomizeResolved}`);

    const generatedData = [];

    if (isNaN(numRecords) || numRecords < 1) {
        console.error('Ogiltigt antal larm att generera.');
        alert('Vänligen ange ett giltigt antal larm.');
        return;
    }

    // Generera grundläggande larm
    for (let i = 0; i < numRecords; i++) {
        const randomDate = new Date(startDate.getTime() + Math.random() * (endDate.getTime() - startDate.getTime()));
        const formattedDate = randomDate.toISOString().split('T')[0];
        const formattedTime = randomDate.toTimeString().split(' ')[0];
        const randomAlarmType = ['Brand', 'Fukt', 'Dörr öppen', 'Inbrott', 'Hög temperatur'][Math.floor(Math.random() * 5)];
        const alarmId = Math.floor(Math.random() * 0xFFFFFF).toString(16).padStart(8, '0').toUpperCase();

        const row = {
            Datum: formattedDate,
            Klockslag: formattedTime,
            Larm: randomAlarmType,
            'Larm-ID': alarmId
        };

        if (randomizeSite) {
            const siteData = generateSite();
            row['SITE'] = siteData.siteCode;
            row['Adress'] = siteData.address;
        }

        generatedData.push(row);
    }

    console.log('Genererad larmdata:', generatedData);

    // Slumpa återställda larm om alternativet är markerat
    if (randomizeResolved && generatedData.length > 1) {
        const resolvedCount = Math.max(1, Math.floor(generatedData.length * (Math.random() * 0.02 + 0.03))); // 3% till 5%
        console.log(`Antal återställda larm som genereras: ${resolvedCount}`);

        for (let i = 0; i < resolvedCount; i++) {
            const randomIndex = Math.floor(Math.random() * generatedData.length);
            const originalAlarm = generatedData[randomIndex];

            // Skapa en tid som är 5–15 minuter senare
            const originalTime = new Date(`${originalAlarm.Datum}T${originalAlarm.Klockslag}`);
            const resolvedTime = new Date(originalTime.getTime() + (Math.random() * 10 + 5) * 60 * 1000); // 5–15 minuter senare

            const resolvedRow = {
                Datum: resolvedTime.toISOString().split('T')[0],
                Klockslag: resolvedTime.toTimeString().split(' ')[0],
                Larm: `${originalAlarm.Larm} (kvitterat)`,
                'Larm-ID': originalAlarm['Larm-ID']
            };

            // Kopiera site och adress om de finns
            if (randomizeSite) {
                resolvedRow['SITE'] = originalAlarm['SITE'];
                resolvedRow['Adress'] = originalAlarm['Adress'];
            }

            generatedData.push(resolvedRow);
            console.log('Kvitterat larm tillagt:', resolvedRow);
        }
    }

    populateTable(generatedData);
}



// Generera site-koder och adresser
function generateSite() {
    console.log('Genererar site...');
    if (!dataDatabase || dataDatabase.length === 0) {
        console.error('Ingen data finns för att generera siter.');
        return { siteCode: 'UNKNOWN', address: 'Ingen adress' };
    }

    const randomIndex = Math.floor(Math.random() * dataDatabase.length);
    const randomRecord = dataDatabase[randomIndex];

    if (!randomRecord || !randomRecord.street) {
        console.error('Ogiltig rad hittades i datan:', randomRecord);
        return { siteCode: 'UNKNOWN', address: 'Ingen adress' };
    }

    let streetName = randomRecord.street.trim();
    let houseNumber = Math.floor(Math.random() * 100) + 1;

    let siteCodeBase = streetName.substring(0, 4).toUpperCase();
    if (!siteCodes[siteCodeBase]) {
        siteCodes[siteCodeBase] = 1;
    } else {
        siteCodes[siteCodeBase]++;
    }

    let siteCode = `${siteCodeBase}${String(siteCodes[siteCodeBase]).padStart(2, '0')}`;
    let address = `${streetName} ${houseNumber}`;
    console.log('Genererad site:', { siteCode, address });
    return { siteCode, address };
}

// Fyll tabellen med genererad data
function populateTable(data) {
    console.log('Startar tabellpopulering...');
    
    // Definiera variabler i början
    const table = document.getElementById('result-table');
    const header = document.getElementById('table-header');
    const exportButtons = document.getElementById('export-buttons'); // Exportknappar

    if (!table || !header) {
        console.error('Tabell eller tabellhuvud hittades inte i DOM.');
        return;
    }

    header.innerHTML = '';
    const columns = ['SITE', 'Adress', 'Datum', 'Klockslag', 'Larm', 'Larm-ID'];

    // Skapa tabellhuvud och koppla sorteringshändelser
    columns.forEach((col, index) => {
        const th = document.createElement('th');
        const wrapper = document.createElement('div'); // Flex-wrapper för rubrik och ikon
        wrapper.className = 'table-header-wrapper';
        wrapper.style.display = 'flex';

        const title = document.createElement('span'); // Titeltext
        title.textContent = col;

        const sortIcon = document.createElement('span'); // Sorteringsikon
        sortIcon.className = 'sort-icon';
        sortIcon.textContent = '⬍'; // Neutral ikon vid start

        wrapper.appendChild(title); // Lägg till rubriktext
        wrapper.appendChild(sortIcon); // Lägg till ikon
        th.appendChild(wrapper);

        th.addEventListener('click', () => {
            console.log(`Klickade på kolumn: ${col}`);
            sortTableByColumn(data, index, th, sortIcon);
        });

        header.appendChild(th);
    });

    // Fyll tabellkroppen
    const tbody = table.querySelector('tbody');
    if (!tbody) {
        console.error('Tabellens tbody hittades inte.');
        return;
    }

    tbody.innerHTML = '';
    data.forEach(row => {
        const tr = document.createElement('tr');
        columns.forEach(col => {
            const td = document.createElement('td');
            td.textContent = row[col] || '';
            tr.appendChild(td);
        });
        tbody.appendChild(tr);
    });

    console.log('Tabell uppdaterad. Antal rader:', data.length);

    // Aktivera exportknappar om data finns
    if (data.length > 0 && exportButtons) {
        exportButtons.style.display = 'flex'; // Gör knapparna synliga
        exportButtons.classList.remove('hidden'); // Ta bort klassen som döljer knapparna
        console.log('Exportknappar aktiverade.');
    } else if (exportButtons) {
        exportButtons.style.display = 'none'; // Dölj knapparna om ingen data finns
        console.log('Exportknappar inaktiverade.');
    }
}




// Koppla exportknappar
function setupExportButtons() {
    const exportCsvButton = document.getElementById('export-to-csv');
    const exportExcelButton = document.getElementById('export-to-excel');
    const exportJsonButton = document.getElementById('export-to-json'); // Ny knapp

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

    if (exportJsonButton) { // Koppla JSON-knappen
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
    console.log('Startar export till CSV...');
    if (!data || data.length === 0) {
        console.warn('Inget data att exportera.');
        return;
    }

    const csv = Papa.unparse(data);
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const fileName = `larmdata_${new Date().toISOString().slice(0, 10)}.csv`;

    link.href = URL.createObjectURL(blob);
    link.download = fileName;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    console.log('CSV-export slutförd.');
}

// Exportera till Excel
function exportToExcel(data) {
    console.log('Startar export till Excel...');
    if (!data || data.length === 0) {
        console.warn('Inget data att exportera.');
        return;
    }

    const workbook = XLSX.utils.book_new();
    const worksheet = XLSX.utils.json_to_sheet(data);

    XLSX.utils.book_append_sheet(workbook, worksheet, 'Larmdata');
    const fileName = `larmdata_${new Date().toISOString().slice(0, 10)}.xlsx`;

    XLSX.writeFile(workbook, fileName);
    console.log('Excel-export slutförd.');
}
let currentSortColumn = null; // Håller reda på aktuell sorteringskolumn
let sortDirection = 1; // 1 för stigande, -1 för fallande

function sortTableByColumn(data, columnIndex, headerElement, sortIcon) {
    const columns = ['SITE', 'Adress', 'Datum', 'Klockslag', 'Larm', 'Larm-ID'];

    // Ändra sorteringsriktning om samma kolumn klickas igen
    if (currentSortColumn === columnIndex) {
        sortDirection *= -1; // Växla riktning
    } else {
        currentSortColumn = columnIndex;
        sortDirection = 1; // Återställ till stigande ordning
    }

    // Rensa sorteringsikoner för alla kolumner
    document.querySelectorAll('.sort-icon').forEach(icon => {
        icon.textContent = '⬍'; // Neutral ikon för alla kolumner
    });

    // Uppdatera ikonen för aktuell kolumn
    sortIcon.textContent = sortDirection === 1 ? '▲' : '▼';

    // Sortera datan
    const columnName = columns[columnIndex];
    data.sort((a, b) => {
        const valueA = a[columnName];
        const valueB = b[columnName];

        if (columnName === 'Datum' || columnName === 'Klockslag') {
            return sortDirection * (new Date(`${a.Datum}T${a.Klockslag}`) - new Date(`${b.Datum}T${b.Klockslag}`));
        } else if (columnName === 'Larm-ID') {
            return sortDirection * (parseInt(valueA, 16) - parseInt(valueB, 16));
        } else {
            return sortDirection * valueA.localeCompare(valueB);
        }
    });

    // Uppdatera tabellen med den sorterade datan
    populateTable(data);
}

function exportToJson(data) {
    console.log('Startar export till JSON...');
    if (!data || data.length === 0) {
        console.warn('Inget data att exportera.');
        return;
    }

    const json = JSON.stringify(data, null, 2); // Konvertera data till JSON-sträng med indrag
    const blob = new Blob([json], { type: 'application/json;charset=utf-8;' });
    const link = document.createElement('a');
    const fileName = `larmdata_${new Date().toISOString().slice(0, 10)}.json`;

    link.href = URL.createObjectURL(blob);
    link.download = fileName;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    console.log('JSON-export slutförd.');
}

