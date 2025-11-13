// tools/testid/script.js - v16
// Använder gemensamma funktioner från tools-common.js

import { exportData } from './export.js';

console.log('TestID v16 laddat - Använder gemensamma funktioner från tools-common.js');

// Använd gemensam validateLuhn från tools-common.js istället för lokal isValidLuhn

const getKön = (pnr) => {
  const genderDigit = parseInt(pnr.charAt(10), 10);
  return genderDigit % 2 === 0 ? 'Kvinna' : 'Man';
};

const getAge = (pnr) => {
  const birthYear = parseInt(pnr.slice(0, 4), 10);
  const birthMonth = parseInt(pnr.slice(4, 6), 10) - 1;
  const birthDay = parseInt(pnr.slice(6, 8), 10);
  const birthDate = new Date(birthYear, birthMonth, birthDay);
  const today = new Date();
  let age = today.getFullYear() - birthYear;
  if (
    today.getMonth() < birthMonth ||
    (today.getMonth() === birthMonth && today.getDate() < birthDay)
  ) {
    age--;
  }
  return age;
};

let exportReadyData = [];

document.getElementById('generateBtn').addEventListener('click', async () => {
  const antal = parseInt(document.getElementById('antal').value, 10);
  const startYear = parseInt(document.getElementById('startYear').value, 10);
  const endYear = parseInt(document.getElementById('endYear').value, 10);
  const tableBody = document.querySelector('#resultTable tbody');
  const exportMenu = document.getElementById('exportMenu');
  const loader = document.getElementById('loader');

  exportMenu?.classList.add('hidden');

  // Visa loading-indikator
  const loadingEl = showLoading(tableBody, `Hämtar ${antal} personnummer...`);
  tableBody.innerHTML = '';

  if (startYear > endYear) {
    showToast('Startår kan inte vara större än slutår.', 'error');
    hideLoading(tableBody);
    loader?.classList.add('hidden');
    return;
  }

  console.log(`📥 Begärt ${antal} personnummer mellan ${startYear} och ${endYear}`);

  const totalOffsets = 20;
  const rowsPerOffset = 75;
  const offsetRange = {
    start: 30500,
    end: 44000
  };

  const step = Math.floor((offsetRange.end - offsetRange.start) / totalOffsets);
  const offsets = Array.from({ length: totalOffsets }, (_, i) =>
    offsetRange.start + i * step + Math.floor(Math.random() * 25)
  );

  try {
    let allResults = [];

    for (const [i, offset] of offsets.entries()) {
      console.log(`📦 Offset ${i + 1}: ${offset}`);
      const res = await fetch(
        `https://skatteverket.entryscape.net/rowstore/dataset/b4de7df7-63c0-4e7e-bb59-1f156a591763?_limit=${rowsPerOffset}&_offset=${offset}`
      );
      const data = await res.json();
      allResults = allResults.concat(data.results);
    }

    console.log(`📚 Totalt insamlade rader: ${allResults.length}`);

    const yearMap = {};
    const giltiga = [];
    const pnrSet = new Set();

    for (const row of allResults) {
      const pnr = row.testpersonnummer;
      if (!pnr || !/^\d{12}$/.test(pnr)) continue;
      const year = parseInt(pnr.slice(0, 4), 10);
      if (year < startYear || year > endYear || pnrSet.has(pnr)) continue;

      pnrSet.add(pnr);
      yearMap[year] = (yearMap[year] || 0) + 1;
      giltiga.push({ ...row, year });
    }

    console.log(`✅ Giltiga och unika: ${giltiga.length}`);
    console.table(yearMap);

    const selected = [];
    const usedIndexes = new Set();

    while (selected.length < antal && usedIndexes.size < giltiga.length) {
      const i = Math.floor(Math.random() * giltiga.length);
      if (!usedIndexes.has(i)) {
        selected.push(giltiga[i]);
        usedIndexes.add(i);
      }
    }

    console.log(`🎯 Slutligt urval: ${selected.length} av ${antal}`);

    if (selected.length === 0) {
      tableBody.innerHTML = `<tr><td colspan="5">Inga giltiga personnummer hittades.</td></tr>`;
      loader?.classList.add('hidden');
      return;
    }

    exportReadyData = selected.map(row => {
      const pnr = row.testpersonnummer;
      return {
        personnummer: pnr,
        födelsedatum: `${pnr.slice(0, 4)}-${pnr.slice(4, 6)}-${pnr.slice(6, 8)}`,
        kön: getKön(pnr),
        ålder: getAge(pnr),
        giltigt: validateLuhn(pnr) // Använd gemensam validateLuhn från tools-common.js
      };
    });

    exportMenu?.classList.remove('hidden');
    loader?.classList.add('hidden');

    exportReadyData.forEach(item => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>${item.personnummer}</td>
        <td>${item.födelsedatum}</td>
        <td>${item.kön}</td>
        <td>${item.ålder}</td>
        <td>${item.giltigt ? '✔️' : '❌'}</td>
      `;
      tableBody.appendChild(tr);
    });

    showToast(`${selected.length} personnummer genererade.`, 'success');
  } catch (err) {
    console.error('🚨 Fel vid hämtning eller hantering:', err);
    showToast('Fel vid hämtning: ' + err.message, 'error');
    loader?.classList.add('hidden');
  } finally {
    // Dölj loading-indikator
    hideLoading(tableBody);
  }
});

// Exportknappar
const exportMenu = document.getElementById('exportMenu');
if (exportMenu) {
  exportMenu.addEventListener('click', (e) => {
    const btn = e.target.closest('button');
    if (!btn) return;
    const format = btn.dataset.format;
    exportData(exportReadyData, format);
  });
}

// Tabellsortering
const table = document.getElementById('resultTable');
if (table) {
  table.querySelectorAll('th').forEach((th, colIndex) => {
    let ascending = true;
    th.style.cursor = 'pointer';
    th.addEventListener('click', () => {
      const rows = Array.from(table.querySelectorAll('tbody tr'));
      rows.sort((a, b) => {
        const aText = a.children[colIndex].textContent.trim();
        const bText = b.children[colIndex].textContent.trim();
        return ascending ? aText.localeCompare(bText, 'sv') : bText.localeCompare(aText, 'sv');
      });
      rows.forEach(row => table.querySelector('tbody').appendChild(row));
      ascending = !ascending;
    });
  });
}
