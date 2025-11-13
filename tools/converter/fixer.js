// tools/converter/fixer.js - v1

/* ********** START: JSON-fixer ********** */
export function init() {
  console.log("fixer.js v1 initieras");

  const section = document.getElementById('tab-fixer');
  section.innerHTML = `
    <form class="form">
      <div class="form__grupp">
        <label for="fixerInput" class="falt__etikett">Klistra in JSON med fel:</label>
        <textarea id="fixerInput" class="falt__textarea" rows="10" placeholder='{name: "Anna", age: 25,} // trailing comma'></textarea>
      </div>
      <div class="form__verktyg">
        <button type="button" class="knapp knapp--liten" id="fixBtn">Försök reparera</button>
      </div>
    </form>

    <div class="form__grupp">
      <label for="fixerOutput" class="falt__etikett">Reparerad JSON:</label>
      <textarea id="fixerOutput" class="falt__textarea" rows="10" readonly></textarea>
    </div>
  `;

  document.getElementById("fixBtn").addEventListener("click", fixJson);
}

function fixJson() {
  const input = document.getElementById("fixerInput").value;
  const output = document.getElementById("fixerOutput");

  if (!input.trim()) {
    showToast("Mata in JSON att reparera.", 'warning');
    return;
  }

  try {
    let fixed = input
      .replace(/(['"])?([a-zA-Z0-9_]+)(['"])?:/g, '"$2":') // saknade citationstecken
      .replace(/'/g, '"')                                  // enkel- till dubbelcitat
      .replace(/,(\s*[}\]])/g, '$1')                       // trailing comma
      .replace(/\/\/.*$/gm, '')                            // enkelradskommentarer
      .replace(/\b(undefined|NaN|Infinity)\b/g, 'null')    // ogiltiga värden
      .trim();

    const parsed = JSON.parse(fixed);
    output.value = JSON.stringify(parsed, null, 2);
    console.log("Reparerad JSON:", parsed);
    showToast("JSON reparerad framgångsrikt!", 'success');
  } catch (e) {
    output.value = "❌ Kunde inte reparera: " + e.message;
    console.warn("Fixningsfel:", e.message);
    showToast("Kunde inte reparera: " + e.message, 'error');
  }
}
/* ********** SLUT: JSON-fixer ********** */
