// tools/converter/converter.js - v1

/* ********** START: JSON ↔ CSV-konverterare ********** */
export function init() {
  console.log("converter.js v1 initieras");

  const section = document.getElementById('tab-converter');
  section.innerHTML = `
    <form class="form">
      <div class="form__grupp">
        <label for="converterInput" class="falt__etikett">Input (JSON eller CSV):</label>
        <textarea id="converterInput" class="falt__textarea" rows="10"></textarea>
      </div>

      <div class="form__verktyg">
        <select id="converterDirection" class="falt__dropdown">
          <option value="json2csv">JSON → CSV</option>
          <option value="csv2json">CSV → JSON</option>
        </select>
        <button type="button" class="knapp knapp--liten" id="convertGeneric">Konvertera</button>
      </div>
    </form>

    <div class="form__grupp">
      <label for="converterOutput" class="falt__etikett">Output:</label>
      <textarea id="converterOutput" class="falt__textarea" rows="10" readonly></textarea>
    </div>
  `;

  document.getElementById('convertGeneric').addEventListener('click', handleConversion);
}

function handleConversion() {
  const direction = document.getElementById('converterDirection').value;
  const input = document.getElementById('converterInput').value.trim();
  const output = document.getElementById('converterOutput');

  if (!input.trim()) {
    showToast("Mata in data att konvertera.", 'warning');
    return;
  }

  try {
    if (direction === 'json2csv') {
      const json = JSON.parse(input);
      const csv = Papa.unparse(json);
      output.value = csv;
      showToast("JSON konverterad till CSV.", 'success');
    } else {
      const result = Papa.parse(input, { header: true });
      output.value = JSON.stringify(result.data, null, 2);
      showToast("CSV konverterad till JSON.", 'success');
    }
    console.log("Konvertering klar:", direction);
  } catch (e) {
    output.value = "❌ Fel vid konvertering: " + e.message;
    showToast("Fel vid konvertering: " + e.message, 'error');
  }
}
/* ********** SLUT: JSON ↔ CSV-konverterare ********** */
