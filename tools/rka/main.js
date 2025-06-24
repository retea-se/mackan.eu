/***** Konstanter *****/
const SCHABLON   = 0.25;                         // L per kVA·h
const REF_SC     = 0.25;                         // L per kWh (referens)
const FUEL_FCT   = { DIESEL:1.00, HVO100:1.04, ECOPAR:0.93 };

/***** Hjälpfunktioner *****/
// Omvandla godtycklig effekt till kVA resp. kW
function toKVA(value, unit, cosPhi){ return unit === "kVA" ? value : value / cosPhi; }
function toKW (value, unit, cosPhi){ return unit === "kW"  ? value : value * cosPhi; }

/***** Huvud-beräkning *****/
function calc() {
  /* hämta värden */
  const ratingVal = +document.getElementById("ratingVal").value;
  const ratingUnit= document.getElementById("ratingUnit").value;
  const loadVal   = +document.getElementById("loadVal").value;
  const loadUnit  = document.getElementById("loadUnit").value;
  const cosPhi    = +document.getElementById("cosPhi").value || 1;
  const hours     = +document.getElementById("hours").value;
  const fuel      = document.getElementById("fuel").value;

  /* grundvalidering */
  if (!ratingVal || !loadVal || !hours || cosPhi <= 0) { hideResult(); return; }

  /* omräkningar */
  const rating_kVA = toKVA(ratingVal, ratingUnit, cosPhi);
  const load_kVA   = toKVA(loadVal,  loadUnit,  cosPhi);
  const loadPct    = load_kVA / rating_kVA * 100;
  if (loadPct <= 0) { hideResult(); return; }

  /* bränsleflöde & totalvolym */
  const Lph   = load_kVA * SCHABLON;                      // L/h
  const fuelL = Lph * hours * FUEL_FCT[fuel];

  /* producerad energi */
  const load_kW = toKW(loadVal, loadUnit, cosPhi);
  const kWh     = load_kW * hours;
  const specC   = fuelL / kWh;                            // L/kWh
  const gamma   = specC / REF_SC;

  /* klassificera */
  const cls = gamma <= 1.2 ? "green" : (gamma <= 1.6 ? "yellow" : "red");

  /* rendera resultat */
  const box = document.getElementById("result");
  box.className = cls;
  box.style.display = "block";
  box.innerHTML = `
    <p><strong>Last:</strong> ${loadPct.toFixed(0)} % av märk­effekten</p>
    <p><strong>Total bränsleåtgång:</strong> ${fuelL.toFixed(1)} L</p>
    <p><strong>Specifik förbrukning:</strong> ${specC.toFixed(2)} L/kWh</p>
    <p>${cls === "green" ? "✅ Effektiv drift" :
        cls === "yellow" ? "⚠️ Måttlig ineffektivitet" :
                           "❌ Mycket ineffektivt"} (${(gamma*100-100).toFixed(0)} % över referens)</p>
    ${loadPct < 20 ? "<p>⚠️ Lasten <20 % – risk för wet-stacking och hög förbrukning.</p>" : ""}
  `;
}

function hideResult(){ document.getElementById("result").style.display = "none"; }

/***** Event-lyssnare *****/
document.querySelectorAll(".calc-field").forEach(el => {
  el.addEventListener("input", calc);          // realtids-uppdatering
});
calc();                                        // kör en gång vid laddning
