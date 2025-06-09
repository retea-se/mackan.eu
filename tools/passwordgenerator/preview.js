// preview.js - v2
console.log("🧪 preview.js v2 laddad");

document.addEventListener("DOMContentLoaded", () => {
  const display = document.getElementById("previewDisplay");
  const regenBtn = document.getElementById("regenPreview");
  const copyBtn = document.getElementById("copyPreview");

  function hämtaInställningar() {
    return {
      längd: parseInt(document.getElementById("length").value, 10),
      lower: document.getElementById("useLower").checked,
      upper: document.getElementById("useUpper").checked,
      numbers: document.getElementById("useNumbers").checked,
      symbols: document.getElementById("useSymbols").checked
    };
  }

  function genereraPreview() {
    const inst = hämtaInställningar();
    const aktiva = Object.values(inst).slice(1).filter(Boolean);
    if (!aktiva.length) {
      display.textContent = "Välj minst en teckentyp";
      return;
    }

    const inställningar = {
      lower: inst.lower,
      upper: inst.upper,
      numbers: inst.numbers,
      symbols: inst.symbols,
    };

    const pw = window.genereraLösenord(inst.längd, inställningar);
    display.textContent = pw || "Fel vid generering";
    console.log("🔁 Nytt förhandslösenord:", pw);
  }

  regenBtn.addEventListener("click", genereraPreview);
  copyBtn.addEventListener("click", () => {
    const pw = display.textContent;
    navigator.clipboard.writeText(pw).then(() => {
      console.log("📋 Förhandslösenord kopierat:", pw);
    });
  });

  genereraPreview();
});
