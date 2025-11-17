// ********** START Sektion: Hjälpfunktioner **********

// Helper: Hämta ett slumpmässigt tecken ur sträng
function slumpaTecken(sträng) {
  return sträng[Math.floor(Math.random() * sträng.length)];
}

// Password generator
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
  // Shuffle tecknen (för att garanterade tecken inte alltid hamnar först)
  return lösenord.split('').sort(() => 0.5 - Math.random()).join('');
}

// Styrkoberäkning
function beräknaStyrka(lösenord) {
  const längd = lösenord.length;
  const variation = [...new Set(lösenord)].length;
  if (längd >= 14 && variation > 10) return 'stark';
  if (längd >= 10) return 'medel';
  return 'svag';
}

// ********** SLUT Sektion: Hjälpfunktioner **********


// ********** START Sektion: DOM-hantering **********
document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("generatorForm");
  const table = document.getElementById("resultTable").querySelector("tbody");
  const exportBtn = document.getElementById("exportBtn");
  const resetBtn = document.getElementById("resetBtn");
  const passphraseBox = document.getElementById("usePassphrase");
  const optionBoxes = ["useLower", "useUpper", "useNumbers", "useSymbols"].map(id => document.getElementById(id));
  const resultWrapper = document.getElementById("resultWrapper");

  const genererade = [];

  // Toggla bokstav/nummer-symbol-boxar om "Använd ordfras" är vald
  passphraseBox.addEventListener("change", () => {
    optionBoxes.forEach(el => el.disabled = passphraseBox.checked);
  });

  form.addEventListener("submit", (e) => {
    e.preventDefault();

    table.innerHTML = '';
    genererade.length = 0;

    const längd = parseInt(document.getElementById("length").value, 10);
    const antal = parseInt(document.getElementById("amount").value, 10);
    const användOrdfras = passphraseBox.checked;

    const inställningar = {
      lower: document.getElementById("useLower").checked,
      upper: document.getElementById("useUpper").checked,
      numbers: document.getElementById("useNumbers").checked,
      symbols: document.getElementById("useSymbols").checked
    };

    for (let i = 0; i < antal; i++) {
      let lösenord = användOrdfras && window.generatePassphrase
        ? window.generatePassphrase(längd)
        : genereraLösenord(längd, inställningar);

      if (!lösenord) continue;

      const styrka = beräknaStyrka(lösenord);

      const rad = document.createElement("tr");
      const tdPw = document.createElement("td");
      tdPw.className = "pw-cell";

      // Skriv ut lösenord + tag för styrka
      const pwText = document.createTextNode(lösenord + " ");

      const tag = document.createElement("span");
      tag.className = `tag tag--${styrka}`; // t.ex. tag--stark
      tag.textContent = styrka.charAt(0).toUpperCase() + styrka.slice(1);

      tdPw.appendChild(pwText);
      tdPw.appendChild(tag);

      const tdActions = document.createElement("td");
      const copyBtn = document.createElement("button");
      copyBtn.type = "button";
      copyBtn.className = "copy-btn knapp__ikon knapp__ikon--liten";
      copyBtn.setAttribute("aria-label", "Kopiera lösenord");
      copyBtn.innerHTML = '<i class="fa-solid fa-copy"></i>';

      // Lägg till event listeners:
      copyBtn.addEventListener("mousedown", e => e.stopPropagation());
      copyBtn.addEventListener("mouseup", e => e.stopPropagation());
      copyBtn.addEventListener("click", e => {
        e.stopPropagation();
        navigator.clipboard.writeText(lösenord); // losenord = lösenordet för raden
        showToast("Lösenord kopierat!");
      });

      tdActions.appendChild(copyBtn);
      rad.appendChild(tdPw);
      rad.appendChild(tdActions);
      table.appendChild(rad);

      genererade.push({ lösenord, styrka });
    }

    // Visa knapparna när det finns resultat
    if (genererade.length) {
      exportBtn.classList.remove("hidden");
      resetBtn.classList.remove("hidden");
      exportBtn.dataset.hasResults = "true";
    } else {
      exportBtn.classList.add("hidden");
      resetBtn.classList.add("hidden");
      exportBtn.dataset.hasResults = "";
    }

    visaResultatTabell();
  });

  resetBtn.addEventListener("click", () => {
    table.innerHTML = '';
    // Dölj knapparna
    exportBtn.classList.add("hidden");
    resetBtn.classList.add("hidden");
    genererade.length = 0;
    doldResultatTabell();
    console.log("Resultat rensat");
  });

  // Event delegation för kopiera-knappar (om du vill stödja dynamiska knappar)
  table.addEventListener('click', function(e) {
    const copyBtn = e.target.closest('.copy-btn');
    if (copyBtn) {
      const pwCell = copyBtn.closest('tr').querySelector('.pw-cell');
      if (pwCell) {
        // Ta bara lösenordet (utan taggen)
        const password = pwCell.childNodes[0].textContent.trim();
        navigator.clipboard.writeText(password);
        showToast("Lösenord kopierat!");
      }
    }
  });

  // Enklare toast
  function showToast(msg) {
    let toast = document.querySelector('.toast');
    if (!toast) {
      toast = document.createElement('div');
      toast.className = 'toast';
      document.body.appendChild(toast);
    }
    toast.textContent = msg;
    toast.classList.add('toast--synlig');
    setTimeout(() => toast.classList.remove('toast--synlig'), 1500);
  }

  function visaResultatTabell() {
    resultWrapper?.classList.remove('hidden');
  }
  function doldResultatTabell() {
    resultWrapper?.classList.add('hidden');
  }

  // Gör exporten tillgänglig för export.js
  window.genereradeLösenord = () => genererade;
  window.genereraLösenord = genereraLösenord;
  window.beräknaStyrka = beräknaStyrka;
});
// ********** SLUT Sektion: DOM-hantering **********
