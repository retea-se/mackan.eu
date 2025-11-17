// tools/converter/formatter.js - v2 - Added JSONEditor loading check

/* ********** START: Formatter med JSONEditor ********** */
let editor;

export function init() {
  console.log("formatter.js v2 initieras");

  const section = document.getElementById('tab-formatter');
  section.innerHTML = `
    <div id="jsoneditor" style="height: 400px;"></div>

    <div class="form__verktyg knapp__grupp">
      <button class="knapp knapp--liten" id="btnBeautify">Beautify</button>
      <button class="knapp knapp--liten" id="btnMinify">Minify</button>
      <button class="knapp knapp--liten knapp--sekundar" id="btnCopy">Kopiera</button>
    </div>
  `;

  // Wait for JSONEditor to load with retry mechanism
  function initEditor(attempts = 0) {
    if (typeof JSONEditor !== 'undefined') {
      const container = document.getElementById("jsoneditor");
      editor = new JSONEditor(container, {
        mode: 'code',
        modes: ['code', 'tree', 'view'],
        onError: err => showToast("Fel i JSON: " + err.message, 'error'),
      });

      document.getElementById("btnBeautify").addEventListener("click", () => formatJson(false));
      document.getElementById("btnMinify").addEventListener("click", () => formatJson(true));
      document.getElementById("btnCopy").addEventListener("click", copyJson);

      console.log('✅ JSONEditor initialized successfully');
    } else if (attempts < 50) { // 5 seconds max (50 x 100ms)
      setTimeout(() => initEditor(attempts + 1), 100);
    } else {
      console.error('❌ JSONEditor failed to load after 5 seconds');
      showToast('JSONEditor kunde inte laddas. Försök ladda om sidan.', 'error');
    }
  }

  initEditor();
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
