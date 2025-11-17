// script.js - v8

// ********** START Sektion: Initiering **********

const form = document.querySelector('#dateForm');
const tableBody = document.querySelector('#resultTable tbody');
const searchInput = document.querySelector('#search');
const exportJsonBtn = document.querySelector('#exportJson');
const exportCsvBtn = document.querySelector('#exportCsv');

let ärenden = []; // Lokalt minne

// Sätt förvalda datum (senaste 30 dagar)
const idag = new Date();
const för30dagar = new Date();
för30dagar.setDate(idag.getDate() - 30);
document.querySelector('#startDate').value = för30dagar.toISOString().slice(0, 10);
document.querySelector('#endDate').value = idag.toISOString().slice(0, 10);

// ********** SLUT Sektion: Initiering **********


// ********** START Sektion: Hämta data **********

async function hamtaDiarium(start, end) {
  if (new Date(start) > new Date(end)) {
    console.warn('[v8] Ogiltigt datumintervall: startdatum efter slutdatum');
    showToast('Startdatum får inte vara efter slutdatum.', 'warning');
    tableBody.innerHTML = `<tr><td colspan="5">Startdatum får inte vara efter slutdatum.</td></tr>`;
    exportJsonBtn.disabled = exportCsvBtn.disabled = true;
    return;
  }

  const url = `proxy.php?start=${start}&end=${end}`;
  console.log(`[v8] Begär ärenden från: ${url}`);

  try {
    const res = await fetch(url);
    if (!res.ok) throw new Error(`Fel: ${res.status}`);
    const data = await res.json();

    if (data.error) throw new Error(data.error);

    console.log(`[v8] ${data.length} ärenden hämtade`);
    ärenden = data;
    window.ärenden = data;

    visaResultat(data);
    exportJsonBtn.disabled = exportCsvBtn.disabled = data.length === 0;
    document.getElementById('showWordcloud').disabled = data.length === 0;

    if (data.length > 0) {
      showToast(`${data.length} ärenden hämtade.`, 'success');
    }

  } catch (err) {
    console.error('[v8] Fel vid hämtning:', err);
    showToast(`Kunde inte hämta ärenden: ${err.message}`, 'error');
    tableBody.innerHTML = `<tr><td colspan="5">Fel: ${err.message}</td></tr>`;
    exportJsonBtn.disabled = exportCsvBtn.disabled = true;
  } finally {
    // Dölj loading-indikator
    hideLoading(tableBody.parentElement);
  }
}

// ********** SLUT Sektion: Hämta data **********


// ********** START Sektion: Visa resultat **********

function visaResultat(lista) {
  console.log(`[v8] Visar ${lista.length} ärenden i tabellen`);
  tableBody.innerHTML = '';

  if (!lista.length) {
    tableBody.innerHTML = '<tr><td colspan="5">Inga ärenden.</td></tr>';
    return;
  }

  lista.forEach(ärende => {
    const rad = document.createElement('tr');
    rad.classList.add('clickable');
    rad.dataset.caseIdentifier = ärende.caseIdentifier;

    rad.innerHTML = `
      <td>${ärende.caseId}</td>
      <td>${ärende.caseIdentifier}</td>
      <td>${ärende.caseRegDate?.split('T')[0]}</td>
      <td>${ärende.caseStatus ?? '–'}</td>
      <td>${ärende.caseTitle}</td>
    `;

    tableBody.appendChild(rad);
  });
}

// ********** SLUT Sektion: Visa resultat **********


// ********** START Sektion: Filtrering **********

searchInput.addEventListener('input', () => {
  const filtrerat = ärenden.filter(a =>
    a.caseTitle.toLowerCase().includes(searchInput.value.toLowerCase())
  );
  console.log(`[v8] Filtrering aktiv: ${filtrerat.length} matchningar`);
  visaResultat(filtrerat);
});

// ********** SLUT Sektion: Filtrering **********


// ********** START Sektion: Visa handlingar **********

tableBody.addEventListener('click', async e => {
  const rad = e.target.closest('tr.clickable');
  if (!rad) return;

  const caseId = rad.dataset.caseIdentifier;
  console.log(`[v8] Klick på ärende ${caseId}, försöker hämta handlingar...`);

  const nextRow = rad.nextElementSibling;
  if (nextRow?.classList.contains('deed-row')) {
    nextRow.remove(); // toggla bort
    return;
  }

  const url = `proxy.php?caseid=${caseId}`;

  // Visa loading-indikator
  const loadingEl = showLoading(rad, 'Hämtar handlingar...');

  try {
    const res = await fetch(url);
    if (!res.ok) throw new Error(`404 Not Found`);
    const json = await res.json();

    if (json.error) {
      console.warn(`[v8] Fel från proxy: ${json.error}`);
      showToast(`Inga handlingar hittades för ${caseId}.`, 'warning');
      return;
    }

    const deeds = json.deeds ?? [];
    console.log(`[v8] ➕ ${deeds.length} handling(ar) för ${caseId}`);
    const deedRow = document.createElement('tr');
    deedRow.classList.add('deed-row');
    const cell = document.createElement('td');
    cell.colSpan = 5;
    cell.innerHTML = deeds.length
      ? `<ul>${deeds.map(d =>
          `<li>
            <strong>${d.deedIdentifer}</strong> – ${d.deedTitle}
            (${d.deedArrivalDate?.split('T')[0]}, nr ${d.deedDiaryDocumentNo})
          </li>`
        ).join('')}</ul>`
      : 'Inga handlingar hittades för detta ärende.';
    deedRow.appendChild(cell);
    rad.insertAdjacentElement('afterend', deedRow);
  } catch (err) {
    console.error(`[v8] Fel vid hämtning av handlingar (${caseId}):`, err);
    showToast(`Det gick inte att ladda handlingar för ${caseId}: ${err.message}`, 'error');
  } finally {
    // Dölj loading-indikator
    hideLoading(rad);
  }
});

// ********** SLUT Sektion: Visa handlingar **********


// ********** START Sektion: Händelser **********

form.addEventListener('submit', e => {
  e.preventDefault();
  const start = document.querySelector('#startDate').value;
  const end = document.querySelector('#endDate').value;
  hamtaDiarium(start, end);
});

exportJsonBtn.addEventListener('click', () => {
  exportToJSON(ärenden, 'pts-diarium.json');
});
exportCsvBtn.addEventListener('click', () => {
  // Konvertera ärenden till format som exportToCSV förväntar sig
  const data = ärenden.map(a => ({
    ID: a.caseId || '',
    Diarienummer: a.caseIdentifier || '',
    Datum: a.caseRegDate?.split('T')[0] || '',
    Status: a.caseStatus || '',
    Rubrik: a.caseTitle || ''
  }));
  exportToCSV(data, 'pts-diarium.csv');
});

// ********** SLUT Sektion: Händelser **********
