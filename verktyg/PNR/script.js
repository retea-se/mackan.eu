//PNR-verktyg

document.addEventListener('DOMContentLoaded', () => {
    populateYearSelects();
    loadData();
    document.getElementById('generator-form').addEventListener('submit', (e) => {
        e.preventDefault();
        generatePersonNumbers();
    });
});

let dataDatabase = [];

async function loadData() {
    try {
        const response = await fetch('data.csv');
        const data = await response.text();
        dataDatabase = Papa.parse(data, { header: true, skipEmptyLines: true }).data;
    } catch (error) {
        console.error('Error loading data:', error);
    }
}

function populateYearSelects() {
    const earliestYearSelect = document.getElementById('earliest-year');
    const latestYearSelect = document.getElementById('latest-year');

    for (let year = 1980; year <= 2009; year++) {
        const option1 = document.createElement('option');
        option1.value = year;
        option1.textContent = year;
        earliestYearSelect.appendChild(option1);

        const option2 = document.createElement('option');
        option2.value = year;
        option2.textContent = year;
        latestYearSelect.appendChild(option2);
    }
}

async function generatePersonNumbers() {
    const earliestYear = parseInt(document.getElementById('earliest-year').value);
    const latestYear = parseInt(document.getElementById('latest-year').value);
    const numRecords = parseInt(document.getElementById('num-records').value);

    try {
        const response = await fetch('pnr.csv');
        const data = await response.text();
        let personNumbers = data.split('\n').filter(row => {
            const year = parseInt(row.substring(0, 4));
            return year >= earliestYear && year <= latestYear;
        });

        if (numRecords > personNumbers.length || numRecords < 1) {
            alert(`Vänligen ange ett nummer mellan 1 och ${personNumbers.length}`);
            return;
        }

        personNumbers = shuffle(personNumbers).slice(0, numRecords);

        const generatedData = personNumbers.map(pnr => {
            const record = { Personnummer: formatPersonnummer(pnr) };

            if (document.getElementById('select-fornamn').checked) {
                record.Förnamn = generateRandomName('FirstnameF', 'FirstnameM');
            }

            if (document.getElementById('select-efternamn').checked) {
                record.Efternamn = generateRandomName('Lastname');
            }

            if (document.getElementById('select-mobiltelefon').checked) {
                record.Mobiltelefon = generateRandomPhoneNumber();
            }

            if (document.getElementById('select-foretag').checked) {
                const randomCompany = generateRandomCompany();
                record.Företag = randomCompany.name;
                record.Orgnr = randomCompany.orgnr;
            }

            if (document.getElementById('select-epost').checked) {
                record.Epost = generateEmail(record.Förnamn, record.Efternamn, record.Företag);
            }

            return record;
        });

        populateTable(generatedData);
    } catch (error) {
        console.error('Error fetching data:', error);
    }
}


function formatPersonnummer(pnr) {
    const year = pnr.substring(2, 4);
    const month = pnr.substring(4, 6);
    const day = pnr.substring(6, 8);
    const lastFourDigits = pnr.substring(8, 12);
    return `${year}${month}${day}-${lastFourDigits}`;
}

function generateRandomName(...columnNames) {
    const randomIndex = Math.floor(Math.random() * dataDatabase.length);
    const randomRecord = dataDatabase[randomIndex];
    const names = columnNames.flatMap(columnName => randomRecord[columnName].split(';'));
    return names[Math.floor(Math.random() * names.length)];
}

function shuffle(array) {
    for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
    }
    return array;
}

function populateTable(data) {
    const table = document.getElementById('result-table');
    table.innerHTML = ''; // Rensa tidigare innehåll

    // Skapa och lägg till tabellhuvud
    const thead = document.createElement('thead');
    const headRow = document.createElement('tr');
    if (data.length > 0) {
        Object.keys(data[0]).forEach(key => {
            const th = document.createElement('th');
            th.textContent = key;
            headRow.appendChild(th);
        });
    }
    thead.appendChild(headRow);
    table.appendChild(thead);

    // Skapa och lägg till tabellkropp
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

    document.getElementById('export-buttons').style.display = 'block';
    document.getElementById('export-to-csv').addEventListener('click', () => exportToCsv(data));
    document.getElementById('export-to-excel').addEventListener('click', () => exportToExcel(data));
}



function generateRandomPhoneNumber() {
    const baseNumber = 1740605;
    const maxNumber = 1740699;
    const randomNumber = baseNumber + Math.floor(Math.random() * (maxNumber - baseNumber + 1));
    return `070-${randomNumber.toString().slice(0, 3)} ${randomNumber.toString().slice(3, 5)} ${randomNumber.toString().slice(5, 7)}`;
}

function generateRandomCompany() {
    const randomIndex = Math.floor(Math.random() * dataDatabase.length);
    const randomRecord = dataDatabase[randomIndex];
    return {
        name: randomRecord.Company.trim(),
        orgnr: randomRecord.Orgnr.trim()
    };
}

function generateEmail(firstName, lastName, company) {
    if (firstName && lastName && company) {
        const domain = company.includes('Solutions') ? '.com' : '.se';
        const companyName = company.replace(' AB', '').replace(/\s+/g, '').toLowerCase();
        return `${firstName.toLowerCase()}.${lastName.toLowerCase()}@${companyName}${domain}`;
    }
    return '';
}


function exportToCsv(data) {
    const csv = Papa.unparse(data, { encoding: "ISO-8859-1" });
    
    const date = new Date();
    const formattedDate = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')} ${String(date.getHours()).padStart(2, '0')}:${String(date.getMinutes()).padStart(2, '0')}`;
    
    const blob = new Blob([csv], { type: 'text/csv;charset=ISO-8859-1;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = `Testdata ${formattedDate}.csv`;
    link.click();
}

function exportToExcel(data) {
    const workbook = XLSX.utils.book_new();
    const worksheet = XLSX.utils.json_to_sheet(data, { cellDates: true });
    
    const date = new Date();
    const formattedDate = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')} ${String(date.getHours()).padStart(2, '0')}:${String(date.getMinutes()).padStart(2, '0')}`;
    
    XLSX.utils.book_append_sheet(workbook, worksheet, 'Personnummer');
    XLSX.writeFile(workbook, `Testdata ${formattedDate}.xlsx`, { bookType: 'xlsx', type: 'binary', cellStyles: true });
}

function s2ab(s) {
    const buf = new ArrayBuffer(s.length);
    const view = new Uint8Array(buf);
    for (let i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
    return buf;
}
