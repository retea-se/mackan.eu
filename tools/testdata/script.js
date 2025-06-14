// script.js - v12

document.addEventListener('DOMContentLoaded', () => {
  const generateBtn = document.getElementById('generateBtn');
  const antalInput = document.getElementById('antal');
  const tableBody = document.querySelector('#resultTable tbody');
  const tableHead = document.querySelector('#resultTable thead');
  const checkboxes = document.querySelectorAll('.field-toggle');
  const formatBtn = document.getElementById('formatBtn');
  const resultTable = document.getElementById('resultTable');
  const exportControls = document.getElementById('exportControls');

  function saneraEpost(epost) {
    return epost.normalize('NFD')
      .replace(/[̀-ͯ]/g, '')
      .replace(/[åä]/gi, 'a')
      .replace(/[ö]/gi, 'o');
  }

  const stored = localStorage.getItem('selectedFields');
  if (stored) {
    const selected = stored.split(',');
    checkboxes.forEach(cb => cb.checked = selected.includes(cb.value));
  }

  checkboxes.forEach(cb => {
    cb.addEventListener('change', () => {
      const fields = getSelectedFields();
      localStorage.setItem('selectedFields', fields.join(','));
    });
  });

  const getSelectedFields = () =>
    Array.from(checkboxes).filter(cb => cb.checked).map(cb => cb.value);

  const capitalize = str => str.charAt(0).toUpperCase() + str.slice(1);

  const rubriker = {
    fornamn: 'Förnamn',
    efternamn: 'Efternamn',
    kon: 'Kön',
    foretag: 'Företag',
    telefon: 'Telefon',
    mobiltelefon: 'Mobiltelefon',
    epost: 'E-post',
    personnummer: 'Personnummer',
    födelsedatum: 'Födelsedatum',
    ålder: 'Ålder',
    giltigt: 'Giltigt'
  };

  formatBtn.addEventListener('click', () => {
    const colIndex = Array.from(tableHead.querySelectorAll('th'))
      .findIndex(th => th.textContent.toLowerCase().includes('personnummer'));
    if (colIndex === -1) return;

    tableBody.querySelectorAll('tr').forEach(row => {
      const cell = row.cells[colIndex];
      if (!cell) return;
      cell.textContent = formatPersonnummer(cell.textContent.trim());
    });

    console.log('[v12] Personnummer formaterade.');
  });

  let sortDirection = 'asc';
  tableHead.addEventListener('click', e => {
    if (e.target.tagName !== 'TH') return;
    const index = [...e.target.parentNode.children].indexOf(e.target);
    const rows = Array.from(tableBody.rows);

    rows.sort((a, b) => {
      const valA = a.cells[index]?.textContent.trim() ?? '';
      const valB = b.cells[index]?.textContent.trim() ?? '';
      return sortDirection === 'asc'
        ? valA.localeCompare(valB, 'sv', { numeric: true })
        : valB.localeCompare(valA, 'sv', { numeric: true });
    });

    sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
    tableBody.innerHTML = '';
    rows.forEach(row => tableBody.appendChild(row));

    console.log(`[v12] Sorterat kolumn ${index + 1} (${sortDirection})`);
  });

  generateBtn.addEventListener('click', async () => {
    const antal = parseInt(antalInput.value) || 1;
    const fields = getSelectedFields();

    console.log(`[v12] Genererar ${antal} personer med fält:`, fields);

    tableHead.innerHTML = '<tr>' + fields.map(f => `<th>${rubriker[f] || capitalize(f)}</th>`).join('') + '</tr>';
    tableBody.innerHTML = '';
    resultTable.classList.remove('hidden');
    formatBtn.classList.remove('hidden');
    if (exportControls) exportControls.classList.remove('hidden');

    for (let i = 0; i < antal; i++) {
      try {
        const [baseRes, pnrRes] = await Promise.all([
          fetch('generate.php'),
          fetch('generatePerson.php')
        ]);
        const base = await baseRes.json();
        const pnr = (await pnrRes.json()).personnummer || 'okänt';
        const fullData = { ...base, personnummer: pnr };

        const row = document.createElement('tr');

        fields.forEach(f => {
          const cell = document.createElement('td');

          if (f === 'epost') {
            cell.textContent = saneraEpost(fullData[f] ?? '');
          } else {
            cell.textContent = fullData[f] ?? '';
          }

          if (f === 'personnummer') {
            cell.classList.add('personnummer-cell');

            if (fields.includes('kon')) {
              const kön = fullData['kon'];
              const valid = valideraPersonnummer(pnr, kön);
              if (!valid) {
                cell.classList.add('error');
                cell.title = `Stämmer ej med kön: ${kön}`;
              }
            }
          }

          cell.setAttribute('data-label', f); // Lägg till denna rad

          row.appendChild(cell);
        });

        tableBody.appendChild(row);
        console.log(`[v12] Person ${i + 1}:`, fullData);
      } catch (err) {
        console.error(`[v12] Fel vid person ${i + 1}:`, err);
      }
    }

    console.log('[v12] Klar!');
  });

  function renderTable(columns, data) {
    const table = document.getElementById('resultTable');
    table.innerHTML = '';

    // Skapa thead
    const thead = document.createElement('thead');
    const headRow = document.createElement('tr');
    columns.forEach(col => {
      const th = document.createElement('th');
      th.textContent = col;
      headRow.appendChild(th);
    });
    thead.appendChild(headRow);
    table.appendChild(thead);

    // Skapa tbody
    const tbody = document.createElement('tbody');
    data.forEach(row => {
      const tr = document.createElement('tr');
      columns.forEach(col => {
        const td = document.createElement('td');
        td.textContent = row[col] ?? '';
        td.setAttribute('data-label', col); // Viktigt för responsivitet!
        tr.appendChild(td);
      });
      tbody.appendChild(tr);
    });
    table.appendChild(tbody);
  }
});
