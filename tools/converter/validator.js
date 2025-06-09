// tools/converter/validator.js - v1

/* ********** START: JSON-validator ********** */
export function init() {
  console.log("validator.js v1 initieras");

  const section = document.getElementById('tab-validator');
  section.innerHTML = `
    <form class="form-group">
      <label for="validatorInput">Klistra in JSON att validera:</label>
      <textarea id="validatorInput" class="textarea" rows="10" placeholder='{"exempel": 123}'></textarea>
      <div class="horizontal-tools">
        <button type="button" class="button" id="validateBtn">Validera</button>
      </div>
    </form>

    <div class="form-group">
      <label for="validatorResult">Resultat:</label>
      <div id="validatorResult" class="info-text"></div>
    </div>
  `;

  document.getElementById("validateBtn").addEventListener("click", validateJson);
}

function validateJson() {
  const input = document.getElementById("validatorInput").value;
  const output = document.getElementById("validatorResult");

  try {
    JSON.parse(input);
    output.textContent = "✅ JSON är giltig!";
    output.style.color = "green";
    console.log("JSON validerad utan fel.");
  } catch (e) {
    output.textContent = "❌ Fel i JSON: " + e.message;
    output.style.color = "red";
    console.warn("Valideringsfel:", e.message);
  }
}
/* ********** SLUT: JSON-validator ********** */
