let genereradeNummer = [];

function genereraNummer() {
    // Rensa tidigare genererade nummer
    genereradeNummer = [];

    // Hämta antal nummer att generera och valt format, med en maxgräns
    const antal = Math.min(document.getElementById('antalNummer').value, 2000);
    const formatVal = document.getElementById('formatVal').value;

    for (let i = 0; i < antal; i++) {
        let nyttNummer = skapaNummer();
        
        if (formatVal === 'ja') {
            nyttNummer = formatInternationellt(nyttNummer);
        } else if (formatVal === 'slumpa' && Math.random() < 0.5) {
            nyttNummer = formatInternationellt(nyttNummer);
        }

        genereradeNummer.push(nyttNummer);
    }

    uppdateraNummerLista();
}

function skapaNummer() {
    const serier = [
        { prefix: "070-1740", range: [605, 699] },
        { prefix: "031-3900", range: [600, 699] },
        { prefix: "040-6280", range: [400, 499] },
        { prefix: "08-465004", range: [0, 99] },
        { prefix: "0980-319", range: [200, 299] }
    ];

    let nyttNummer;
    do {
        const valdSerie = serier[Math.floor(Math.random() * serier.length)];
        const slumpNummer = Math.floor(Math.random() * (valdSerie.range[1] - valdSerie.range[0] + 1)) + valdSerie.range[0];
        nyttNummer = `${valdSerie.prefix}${slumpNummer}`;
    } while (genereradeNummer.includes(nyttNummer));

    return nyttNummer;
}

function formatInternationellt(nummer) {
    return '+46' + nummer.substring(1);
}

function uppdateraNummerLista() {
    const nummerLista = document.getElementById('nummerLista');
    nummerLista.innerHTML = genereradeNummer.map(nummer => `<li>${nummer}</li>`).join('');

    // Visa exportalternativen om det finns genererade nummer
    document.getElementById('exportContainer').style.display = genereradeNummer.length > 0 ? 'block' : 'none';
}

// Funktion för att exportera nummer
function exporteraNummer() {
    const format = document.getElementById('exportFormat').value;
    switch (format) {
        case 'csv':
            exportTillCSV();
            break;
        case 'text':
            exportTillText();
            break;
    }
}

// Funktion för att exportera till CSV
function exportTillCSV() {
    const csvContent = "data:text/csv;charset=utf-8," + genereradeNummer.join("\n");
    laddaNer(csvContent, 'telefonnummer.csv');
}

function exportTillText() {
    const nu = new Date();
    const datumTidStr = `${nu.getFullYear()}${(nu.getMonth() + 1).toString().padStart(2, '0')}${nu.getDate().toString().padStart(2, '0')} ${nu.getHours().toString().padStart(2, '0')}-${nu.getMinutes().toString().padStart(2, '0')}-${nu.getSeconds().toString().padStart(2, '0')}`;

    const textContent = genereradeNummer.map(nummer => nummer.replace(/^\+/, '')).join("\n");

    const fileName = `Telefonnummer ${datumTidStr}.txt`;

    laddaNer("data:text/plain;charset=utf-8," + encodeURIComponent(textContent), fileName);
}





// Funktion för att ladda ner filen
function laddaNer(content, fileName) {
    const encodedUri = encodeURI(content);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", fileName);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Funktion för att exportera till Excel med SheetJS (kommenterad bort)
// function exportTillExcel() {
//     // Här kan du implementera logiken för att exportera till Excel
// }
