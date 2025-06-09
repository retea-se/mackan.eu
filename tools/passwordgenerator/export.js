// export.js - v1

console.log("📁 export.js v1 laddad");

// ********** START Sektion: Hjälpfunktioner **********

function skapaFilnamn(extension) {
  const nu = new Date();
  const pad = (n) => n.toString().padStart(2, '0');
  const tid = `${nu.getFullYear()}${pad(nu.getMonth() + 1)}${pad(nu.getDate())}-${pad(nu.getHours())}${pad(nu.getMinutes())}`;
  return `losenord-${tid}.${extension}`;
}

function sparaFil(innehåll, typ, extension) {
  const blob = new Blob([innehåll], { type: typ });
  const länk = document.createElement("a");
  länk.href = URL.createObjectURL(blob);
  länk.download = skapaFilnamn(extension);
  länk.click();
  console.log(`💾 Exporterat som ${länk.download}`);
}

// ********** SLUT Sektion: Hjälpfunktioner **********


// ********** START Sektion: UI-hantering **********

document.addEventListener("DOMContentLoaded", () => {
  const exportKnapp = document.getElementById("exportBtn");

  const meny = document.createElement("div");
  meny.className = "card p-1 hidden";
  meny.style.position = "absolute";
  meny.style.zIndex = "10";
  meny.innerHTML = `
    <button class="button-small full-width mb-1" data-export="txt" data-tippy-content="Spara som vanlig textfil">.txt (text)</button>
    <button class="button-small full-width mb-1" data-export="csv" data-tippy-content="Spara som kalkylvänlig fil">.csv (Excelvänlig)</button>
    <button class="button-small full-width" data-export="json" data-tippy-content="Spara som strukturerad JSON">.json (struktur)</button>
  `;
  document.body.appendChild(meny);

  exportKnapp.addEventListener("click", (e) => {
    if (!exportKnapp.dataset.hasResults) return;
    const { left, bottom } = exportKnapp.getBoundingClientRect();
    meny.style.left = `${left}px`;
    meny.style.top = `${bottom + 10}px`;
    meny.classList.toggle("hidden");
  });

  document.addEventListener("click", (e) => {
    if (!meny.contains(e.target) && e.target !== exportKnapp) {
      meny.classList.add("hidden");
    }
  });

  meny.addEventListener("click", (e) => {
    if (!e.target.dataset.export) return;
    const typ = e.target.dataset.export;
    const data = window.genereradeLösenord();
    let output = '';
    if (typ === "txt") {
      output = data.map(d => d.lösenord).join("\n");
      sparaFil(output, "text/plain", "txt");
    } else if (typ === "csv") {
      output = "lösenord,styrka\n" + data.map(d => `${d.lösenord},${d.styrka}`).join("\n");
      sparaFil(output, "text/csv", "csv");
    } else if (typ === "json") {
      output = JSON.stringify(data, null, 2);
      sparaFil(output, "application/json", "json");
    }
    meny.classList.add("hidden");
  });
});

// ********** SLUT Sektion: UI-hantering **********
