// a2.js

document.addEventListener('DOMContentLoaded', function() {

// --- Elementreferenser ---
const form = document.getElementById('advForm');
const tbody = document.querySelector('#lpTable tbody');
const addRowBtn = document.getElementById('addRow');
const calcBtn = document.createElement('button');

// --- Debounce autosubmit (för övriga fält) ---
let dTimer = null, delay = 600;
function deb() {
  clearTimeout(dTimer);
  dTimer = setTimeout(() => {
    form.requestSubmit();
  }, delay);
}

const textIds = ['rating','cosphi','price','co2','provMin',
                 'provEveryVal','runHrs','tankInt','buffDays','buffPct'];
const selIds  = ['ratingUnit','fuel','provEveryUnit',
                 'swedeTown','genType','phase'];

textIds.forEach(id => {
  const el = document.getElementById(id);
  el && el.addEventListener('input', deb);
});
selIds.forEach(id => {
  const el = document.getElementById(id);
  el && el.addEventListener('change', deb);
});

// --- Automatisk pris & CO₂ vid bränslebyte ---
const co2Map   = {DIESEL:2.67,HVO100:0.35,ECOPAR:1.3};
const priceMap = {DIESEL:17.8,HVO100:22.8,ECOPAR:21};
const fuelEl = document.getElementById('fuel');
if (fuelEl) {
  fuelEl.addEventListener('change', e => {
    const f = e.target.value;
    if(co2Map[f])   document.getElementById('co2').value   = co2Map[f];
    if(priceMap[f]) document.getElementById('price').value = priceMap[f];
    deb();
  });
}

// --- Lastprofil: max 3 rader, standardvärden vid första laddning ---
function setRow(i, t = 8, l = 50, u = '%') {
  tbody.rows[i].querySelector('input[name="lpTime[]"]').value = t;
  tbody.rows[i].querySelector('input[name="lpLoad[]"]').value = l;
  tbody.rows[i].querySelector('select[name="lpUnit[]"]').value = u;
}

function hookLpInputs(tr) {
  tr.querySelectorAll('input').forEach(inp => {
    inp.addEventListener('change', () => calcBtn.disabled = false);
    inp.addEventListener('blur',   () => calcBtn.disabled = false);
  });
  tr.querySelector('select').addEventListener('change', () => calcBtn.disabled = false);
}

// Starta exakt 3 rader (aldrig fler)
while (tbody.rows.length > 3) tbody.deleteRow(tbody.rows.length - 1);
while (tbody.rows.length < 3) {
  const tr = document.createElement('tr');
  tr.innerHTML = `
    <td>${tbody.rows.length + 1}</td>
    <td><input name="lpTime[]" type="number" min="0" step="0.1"></td>
    <td><input name="lpLoad[]" type="number" min="0" step="0.1"></td>
    <td>
      <select name="lpUnit[]">
        <option value="%">% av märk</option>
        <option value="kW">kW</option>
        <option value="kVA">kVA</option>
      </select>
    </td>
    <td></td>
  `;
  tbody.appendChild(tr);
  hookLpInputs(tr);
}

// Sätt standardvärden om tabellen är tom (vid sidladdning)
if (
  !tbody.rows[0].querySelector('input[name="lpTime[]"]').value &&
  !tbody.rows[1].querySelector('input[name="lpTime[]"]').value &&
  !tbody.rows[2].querySelector('input[name="lpTime[]"]').value
) {
  setRow(0, 8, 50, '%');
  setRow(1, 8, 50, '%');
  setRow(2, 8, 50, '%');
}

// Dölj “lägg till rad”-knapp
if(addRowBtn) addRowBtn.style.display = "none";

// --- Manuell "Beräkna lastprofil"-knapp ---
calcBtn.type = "submit";
calcBtn.textContent = "Beräkna lastprofil";
calcBtn.style.marginTop = "0.6rem";
calcBtn.className = "button";
calcBtn.disabled = true;

// Aktivera knappen när användaren ändrar i någon cell
tbody.querySelectorAll('input,select').forEach(el => {
  el.addEventListener('input', () => calcBtn.disabled = false);
  el.addEventListener('change', () => calcBtn.disabled = false);
});

// Disable-knapp direkt vid submit (feedback)
form.addEventListener('submit', () => {
  calcBtn.disabled = true;
});

});
