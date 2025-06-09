// tools/converter/converter.js - v1

/* ********** START: JSON ↔ CSV-konverterare ********** */
export function init() {
  console.log("converter.js v1 initieras");

  const section = document.getElementById('tab-converter');
  section.innerHTML = `
    <form class="form-group">
      <label for="converterInput">Input (JSON eller CSV):</label>
      <textarea id="converterInput" class="textarea" rows="10"></textarea>

      <div class="horizontal-tools">
        <select id="converterDirection" class="dropdown">
          <option value="json2csv">JSON → CSV</option>
          <option value="csv2json">CSV → JSON</option>
        </select>
        <button type="button" class="button tiny" id="convertGeneric">Konvertera</button>
      </div>
    </form>

    <div class="form-group">
      <label for="converterOutput">Output:</label>
      <textarea id="converterOutput" class="textarea" rows="10" readonly></textarea>
    </div>
  `;

  document.getElementById('convertGeneric').addEventListener('click', handleConversion);
}

function handleConversion() {
  const direction = document.getElementById('converterDirection').value;
  const input = document.getElementById('converterInput').value.trim();
  const output = document.getElementById('converterOutput');

  try {
    if (direction === 'json2csv') {
      const json = JSON.parse(input);
      const csv = Papa.unparse(json);
      output.value = csv;
    } else {
      const result = Papa.parse(input, { header: true });
      output.value = JSON.stringify(result.data, null, 2);
    }
    console.log("Konvertering klar:", direction);
  } catch (e) {
    output.value = "❌ Fel vid konvertering: " + e.message;
  }
}
/* ********** SLUT: JSON ↔ CSV-konverterare ********** */
