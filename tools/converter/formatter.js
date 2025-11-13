// tools/converter/formatter.js - v1

/* ********** START: Formatter med JSONEditor ********** */
let editor;

export function init() {
  console.log("formatter.js v1 initieras");

  const section = document.getElementById('tab-formatter');
  section.innerHTML = `
    <div id="jsoneditor" style="height: 400px;"></div>

    <div class="form__verktyg knapp__grupp">
      <button class="knapp knapp--liten" id="btnBeautify">Beautify</button>
      <button class="knapp knapp--liten" id="btnMinify">Minify</button>
      <button class="knapp knapp--liten knapp--sekundar" id="btnCopy">Kopiera</button>
    </div>
  `;

  const container = document.getElementById("jsoneditor");
  editor = new JSONEditor(container, {
    mode: 'code',
    modes: ['code', 'tree', 'view'],
    onError: err => showToast("Fel i JSON: " + err.message, 'error'),
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
    showToast(`JSON ${minify ? 'minifierad' : 'formaterad'} framgångsrikt.`, 'success');
  } catch (e) {
    showToast("Kunde inte formattera: " + e.message, 'error');
  }
}

function copyJson() {
  try {
    const text = editor.getText();
    navigator.clipboard.writeText(text).then(() => showToast("JSON kopierat!", 'success'));
  } catch (e) {
    showToast("Kunde inte kopiera: " + e.message, 'error');
  }
}
/* ********** SLUT: Formatter med JSONEditor ********** */
