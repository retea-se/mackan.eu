// tools/converter/formatter.js - v1

/* ********** START: Formatter med JSONEditor ********** */
let editor;

export function init() {
  console.log("formatter.js v1 initieras");

  const section = document.getElementById('tab-formatter');
  section.innerHTML = `
    <div id="jsoneditor" style="height: 400px;"></div>

    <div class="horizontal-tools">
      <button class="button tiny" id="btnBeautify">Beautify</button>
      <button class="button tiny" id="btnMinify">Minify</button>
      <button class="button secondary tiny" id="btnCopy">Kopiera</button>
    </div>
  `;

  const container = document.getElementById("jsoneditor");
  editor = new JSONEditor(container, {
    mode: 'code',
    modes: ['code', 'tree', 'view'],
    onError: err => alert("Fel i JSON: " + err.message),
  });

  document.getElementById("btnBeautify").addEventListener("click", () => formatJson(false));
  document.getElementById("btnMinify").addEventListener("click", () => formatJson(true));
  document.getElementById("btnCopy").addEventListener("click", copyJson);
}

function formatJson(minify = false) {
  try {
    const data = editor.get();
    const output = JSON.stringify(data, null, minify ? 0 : 2);
    editor.setText(output);
    console.log("JSON formatterad:", minify ? "minify" : "beautify");
  } catch (e) {
    alert("Kunde inte formattera: " + e.message);
  }
}

function copyJson() {
  try {
    const text = editor.getText();
    navigator.clipboard.writeText(text).then(() => alert("JSON kopierat!"));
  } catch (e) {
    alert("Kunde inte kopiera: " + e.message);
  }
}
/* ********** SLUT: Formatter med JSONEditor ********** */
