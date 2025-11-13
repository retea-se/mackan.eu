// tools/converter/csvtojson.js - v1

/* ********** START: CSV till JSON-konverterare ********** */
export function init() {
  console.log("csvtojson.js v1 initieras");

  const section = document.getElementById('tab-csvtojson');
  section.innerHTML = `
    <form class="form">
      <div class="form__grupp">
        <label for="csvInput" class="falt__etikett">Klistra in CSV-data (t.ex. från Excel):</label>
        <textarea id="csvInput" class="falt__textarea" rows="6" placeholder="Kolumn1\tKolumn2\nVärde1\tVärde2"></textarea>
      </div>
      <div class="form__verktyg">
        <button type="button" class="knapp knapp--liten" id="convertBtn">Konvertera</button>
        <button type="button" class="knapp knapp--liten knapp--sekundar" id="copyBtn">Kopiera JSON</button>
      </div>
    </form>

    <div class="form__grupp">
      <label for="jsonPreview" class="falt__etikett">Resultat (JSON):</label>
      <textarea id="jsonPreview" class="falt__textarea" rows="10" readonly></textarea>
    </div>
  `;

  document.getElementById('convertBtn').addEventListener('click', convertCsvToJson);
  document.getElementById('copyBtn').addEventListener('click', copyJson);
}

function convertCsvToJson() {
  console.log("convertCsvToJson körs");
  const input = document.getElementById('csvInput').value.trim();
  const lines = input.split(/\r?\n/).filter(Boolean);
  if (lines.length < 2) {
    showToast("Mata in minst en rubrikrad och en datarad.", 'warning');
    return;
  }

  try {
    const headers = lines[0].split(/\t|;/);
    const rows = lines.slice(1);

    const json = rows.map(row => {
      const values = row.split(/\t|;/);
      const obj = {};
      headers.forEach((h, i) => obj[h] = values[i] ?? "");
      return obj;
    });

    const output = JSON.stringify(json, null, 2);
    document.getElementById('jsonPreview').value = output;
    console.log("JSON genererad:", output);
    showToast(`${rows.length} rader konverterade till JSON.`, 'success');
  } catch (e) {
    showToast("Fel vid konvertering: " + e.message, 'error');
  }
}

function copyJson() {
  const output = document.getElementById('jsonPreview').value;
  if (!output) {
    showToast("Ingen JSON att kopiera.", 'warning');
    return;
  }
  navigator.clipboard.writeText(output).then(() => showToast("JSON kopierat!", 'success')).catch(err => showToast("Kunde inte kopiera: " + err.message, 'error'));
}
/* ********** SLUT: CSV till JSON-konverterare ********** */
