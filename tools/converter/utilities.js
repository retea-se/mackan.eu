// tools/converter/utilities.js - v1

/* ********** START: JSON utilities ********** */
export function init() {
  console.log("utilities.js v1 initieras");

  const section = document.getElementById('tab-utilities');
  section.innerHTML = `
    <form class="form-group">
      <label for="utilityInput">Input:</label>
      <textarea id="utilityInput" class="textarea" rows="6" placeholder='{"nyckel":"värde"}'></textarea>

      <div class="form__verktyg">
        <button type="button" class="knapp knapp--liten" data-func="encode">URL Encode</button>
        <button type="button" class="knapp knapp--liten" data-func="decode">URL Decode</button>
        <button type="button" class="knapp knapp--liten" data-func="escape">Escape</button>
        <button type="button" class="knapp knapp--liten" data-func="unescape">Unescape</button>
        <button type="button" class="knapp knapp--liten" data-func="stringify">JSON.stringify</button>
      </div>
    </form>

    <div class="form-group">
      <label for="utilityOutput">Output:</label>
      <textarea id="utilityOutput" class="textarea" rows="6" readonly></textarea>
    </div>
  `;

  section.querySelectorAll('[data-func]').forEach(button => {
    button.addEventListener('click', () => runUtility(button.dataset.func));
  });
}

function runUtility(type) {
  const input = document.getElementById("utilityInput").value;
  const output = document.getElementById("utilityOutput");

  try {
    let result;
    switch (type) {
      case 'encode':
        result = encodeURIComponent(input);
        break;
      case 'decode':
        result = decodeURIComponent(input);
        break;
      case 'escape':
        result = JSON.stringify(input).slice(1, -1); // enklare escape
        break;
      case 'unescape':
        result = JSON.parse(`"${input}"`);
        break;
      case 'stringify':
        result = JSON.stringify(eval("(" + input + ")"), null, 2);
        break;
      default:
        result = "Okänd funktion.";
    }
    output.value = result;
    console.log(`Verktyg ${type} kördes`);
  } catch (e) {
    output.value = "❌ Fel: " + e.message;
  }
}
/* ********** SLUT: JSON utilities ********** */
