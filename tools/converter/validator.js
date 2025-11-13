// tools/converter/validator.js - v1

/* ********** START: JSON-validator ********** */
export function init() {
  console.log("validator.js v1 initieras");

  const section = document.getElementById('tab-validator');
  section.innerHTML = `
    <form class="form">
      <div class="form__grupp">
        <label for="validatorInput" class="falt__etikett">Klistra in JSON att validera:</label>
        <textarea id="validatorInput" class="falt__textarea" rows="10" placeholder='{"exempel": 123}'></textarea>
      </div>
      <div class="form__verktyg">
        <button type="button" class="knapp knapp--liten" id="validateBtn">Validera</button>
      </div>
    </form>

    <div class="form__grupp">
      <label for="validatorResult" class="falt__etikett">Resultat:</label>
      <div id="validatorResult" class="info__text"></div>
    </div>
  `;

  document.getElementById("validateBtn").addEventListener("click", validateJson);
}

function validateJson() {
  const input = document.getElementById("validatorInput").value;
  const output = document.getElementById("validatorResult");

  if (!input.trim()) {
    showToast("Mata in JSON att validera.", 'warning');
    return;
  }

  try {
    JSON.parse(input);
    output.textContent = "✅ JSON är giltig!";
    output.style.color = "green";
    console.log("JSON validerad utan fel.");
    showToast("JSON är giltig!", 'success');
  } catch (e) {
    output.textContent = "❌ Fel i JSON: " + e.message;
    output.style.color = "red";
    console.warn("Valideringsfel:", e.message);
    showToast("Fel i JSON: " + e.message, 'error');
  }
}
/* ********** SLUT: JSON-validator ********** */
