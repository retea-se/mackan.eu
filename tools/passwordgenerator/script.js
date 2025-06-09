// script.js - v4

console.log("🔐 script.js v4 laddad");

// ********** START Sektion: Hjälpfunktioner **********

function slumpaTecken(sträng) {
  return sträng[Math.floor(Math.random() * sträng.length)];
}

function beräknaStyrka(lösenord) {
  const längd = lösenord.length;
  const variation = [...new Set(lösenord)].length;

  if (längd >= 14 && variation > 10) return 'stark';
  if (längd >= 10) return 'medel';
  return 'svag';
}

function genereraLösenord(längd, inställningar) {
  const typer = {
    lower: 'abcdefghijklmnopqrstuvwxyz',
    upper: 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
    numbers: '0123456789',
    symbols: '!@#$%^&*()-_=+[]{};:,.<>?'
  };

  let teckenpool = '';
  let garanterade = [];

  for (const [typ, aktiv] of Object.entries(inställningar)) {
    if (aktiv) {
      teckenpool += typer[typ];
      garanterade.push(slumpaTecken(typer[typ]));
    }
  }

  if (!teckenpool || längd < garanterade.length) return null;

  let lösenord = garanterade.join('');
  for (let i = lösenord.length; i < längd; i++) {
    lösenord += slumpaTecken(teckenpool);
  }

  return lösenord.split('').sort(() => 0.5 - Math.random()).join('');
}

// ********** SLUT Sektion: Hjälpfunktioner **********


// ********** START Sektion: DOM-hantering **********

document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("generatorForm");
  const table = document.getElementById("resultTable").querySelector("tbody");
  const exportBtn = document.getElementById("exportBtn");
  const resetBtn = document.getElementById("resetBtn");

  const genererade = [];

  form.addEventListener("submit", (e) => {
    e.preventDefault();

    table.innerHTML = '';
    genererade.length = 0;

    const längd = parseInt(document.getElementById("length").value, 10);
    const antal = parseInt(document.getElementById("amount").value, 10);
    const inställningar = {
      lower: document.getElementById("useLower").checked,
      upper: document.getElementById("useUpper").checked,
      numbers: document.getElementById("useNumbers").checked,
      symbols: document.getElementById("useSymbols").checked,
    };

    const aktivaTyper = Object.values(inställningar).filter(Boolean).length;
    if (!aktivaTyper) return alert("Välj minst en teckentyp.");
    if (längd < aktivaTyper) return alert(`Lösenordet måste vara minst ${aktivaTyper} tecken långt.`);

    for (let i = 0; i < antal; i++) {
      const pw = genereraLösenord(längd, inställningar);
      if (!pw || pw.length !== längd) continue;

      const styrka = beräknaStyrka(pw);

      const rad = document.createElement("tr");
      const tdPw = document.createElement("td");
      const tdCopy = document.createElement("td");
      const knapp = document.createElement("button");

      tdPw.innerHTML = `${pw} <span class="tag-${styrka}">(${styrka})</span>`;
      knapp.textContent = "📋";
      knapp.className = "button-small";
      knapp.setAttribute("data-tippy-content", "Kopiera lösenordet");
      knapp.addEventListener("click", () => {
        navigator.clipboard.writeText(pw).then(() => {
          console.log("✅ Lösenord kopierat:", pw);
        });
      });

      tdCopy.appendChild(knapp);
      rad.appendChild(tdPw);
      rad.appendChild(tdCopy);
      table.appendChild(rad);

      genererade.push({ lösenord: pw, styrka });
    }

    if (genererade.length) {
      exportBtn.classList.remove("hidden");
      resetBtn.classList.remove("hidden");
      exportBtn.dataset.hasResults = "true";
    }
  });

  resetBtn.addEventListener("click", () => {
    table.innerHTML = '';
    exportBtn.classList.add("hidden");
    resetBtn.classList.add("hidden");
    genererade.length = 0;
    console.log("🧹 Resultat rensat");
  });

  window.genereradeLösenord = () => genererade;
});

// ********** SLUT Sektion: DOM-hantering **********
