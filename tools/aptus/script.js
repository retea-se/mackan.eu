// script.js - v1 (Aptus-verktyg)

/* Konvertera hex till dec */
function hexToDec(hexString) {
  const decValue = BigInt("0x" + hexString);
  return decValue.toString().slice(-9);
}

/* Konvertera vid knapptryck */
document.getElementById('convertButton')?.addEventListener('click', () => {
  const input = document.getElementById('hexInput').value.trim();
  const hexValues = input.split('\n').map(val => val.trim()).filter(val => val);
  const resultBody = document.getElementById('resultBody');
  const resultWrapper = document.getElementById('resultWrapper');
  let resultHTML = '';

  hexValues.forEach(hex => {
    const dec = /^[0-9A-Fa-f]+$/.test(hex) ? hexToDec(hex) : 'Ogiltig';
    resultHTML += `<tr><td>${hex}</td><td>${dec}</td></tr>`;
  });

  resultBody.innerHTML = resultHTML;
  const hasRows = hexValues.length > 0;
  if (resultWrapper) {
    resultWrapper.classList.toggle('hidden', !hasRows);
  }
  document.getElementById('exportButton').classList.toggle('hidden', !hasRows);
  document.getElementById('clearButton').classList.toggle('hidden', !hasRows);
});

/* Exportera till CSV */
document.getElementById('exportButton')?.addEventListener('click', () => {
  let csv = "data:text/csv;charset=utf-8,Original EM,Aptus\n";
  const rows = document.querySelectorAll("#resultTable tbody tr");

  rows.forEach(row => {
    const cols = row.querySelectorAll("td");
    const data = Array.from(cols).map(cell => cell.textContent).join(',');
    csv += data + "\n";
  });

  const link = document.createElement("a");
  link.href = encodeURI(csv);
  link.download = "konverterade_varden.csv";
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
});

/* Rensa resultat */
document.getElementById('clearButton')?.addEventListener('click', () => {
  document.getElementById('hexInput').value = '';
  document.getElementById('resultBody').innerHTML = '';
  document.getElementById('exportButton').classList.add('hidden');
  document.getElementById('clearButton').classList.add('hidden');
  document.getElementById('resultWrapper')?.classList.add('hidden');
});

/* Enter som trigger */
document.getElementById('hexInput')?.addEventListener('keydown', (e) => {
  if (e.key === 'Enter') {
    e.preventDefault();
    document.getElementById('convertButton')?.click();
  }
});

/* Visa version */
document.addEventListener('DOMContentLoaded', () => {
  const el = document.getElementById('versionNumber');
  if (el) el.textContent = "1.0";
});
