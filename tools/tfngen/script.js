// script.js - v3

let genereradeNummer = [];

// ********** START: Huvudfunktion **********
function genereraNummer() {
    genereradeNummer = [];

    const antal = Math.min(document.getElementById('antalNummer').value, 500);
    const formatVal = document.getElementById('formatVal').value;

    // Hämta alla ikryssade serier
    const valdaSerier = Array.from(document.querySelectorAll('input[name="serier"]:checked'))
        .map(input => input.value);

    if (valdaSerier.length === 0) {
        alert("Du måste välja minst en serie!");
        return;
    }

    for (let i = 0; i < antal; i++) {
        let nyttNummer = skapaNummer(valdaSerier);

        if (formatVal === 'ja') {
            nyttNummer = formatInternationellt(nyttNummer);
        } else if (formatVal === 'slumpa' && Math.random() < 0.5) {
            nyttNummer = formatInternationellt(nyttNummer);
        }

        genereradeNummer.push(nyttNummer);
    }

    console.log("[v3] Antal genererade nummer:", genereradeNummer.length);
    uppdateraNummerLista();
}
// ********** SLUT: Huvudfunktion **********

// ********** START: Nummergenerering **********
function skapaNummer(valdaSerier) {
    const serier = [
        { prefix: "070-1740", range: [605, 699], kod: "070" },
        { prefix: "031-3900", range: [600, 699], kod: "031" },
        { prefix: "040-6280", range: [400, 499], kod: "040" },
        { prefix: "08-465004", range: [0, 99], kod: "08" },
        { prefix: "0980-319", range: [200, 299], kod: "0980" }
    ];

    let nyttNummer;
    do {
        const tillgängliga = serier.filter(s => valdaSerier.includes(s.kod));
        const valdSerie = tillgängliga[Math.floor(Math.random() * tillgängliga.length)];
        const slumpNummer = Math.floor(Math.random() * (valdSerie.range[1] - valdSerie.range[0] + 1)) + valdSerie.range[0];
        nyttNummer = `${valdSerie.prefix}${slumpNummer}`;
    } while (genereradeNummer.includes(nyttNummer));

    return nyttNummer;
}
// ********** SLUT: Nummergenerering **********

function formatInternationellt(nummer) {
    return '+46' + nummer.replace(/^0/, '').replace('-', '');
}

function uppdateraNummerLista() {
    const nummerLista = document.getElementById('nummerLista');
    nummerLista.innerHTML = genereradeNummer.map(nummer => `<li>${nummer}</li>`).join('');

    // Visa exportknapp endast om det finns data
    const knappContainer = document.getElementById('exportKnappContainer');
    const rensaKnapp = document.getElementById('rensaknapp');
    const hasNumbers = genereradeNummer.length > 0;

    if (knappContainer) {
        knappContainer.classList.toggle('hidden', !hasNumbers);
    }
    if (rensaKnapp) {
        rensaKnapp.classList.toggle('hidden', !hasNumbers);
    }
}

// ********** START: JSON-export **********
function exporteraTillJSON() {
    const jsonContent = "data:application/json;charset=utf-8," + encodeURIComponent(JSON.stringify(genereradeNummer, null, 2));
    const fileName = `telefonnummer_${skapaTidsstämpel()}.json`;
    laddaNer(jsonContent, fileName);
}
// ********** SLUT: JSON-export **********

function skapaTidsstämpel() {
    const nu = new Date();
    return `${nu.getFullYear()}${(nu.getMonth()+1).toString().padStart(2,'0')}${nu.getDate().toString().padStart(2,'0')}_${nu.getHours().toString().padStart(2,'0')}${nu.getMinutes().toString().padStart(2,'0')}${nu.getSeconds().toString().padStart(2,'0')}`;
}

function laddaNer(content, fileName) {
    const encodedUri = encodeURI(content);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", fileName);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('generatorForm')?.addEventListener('submit', (event) => {
        event.preventDefault();
        genereraNummer();
    });

    document.getElementById('exportJsonBtn')?.addEventListener('click', exporteraTillJSON);

    document.getElementById('rensaknapp')?.addEventListener('click', () => {
        genereradeNummer = [];
        uppdateraNummerLista();
    });
});
