// script.js - v9
// commit: Åtgärdat ordfrashantering, korrekt längd, preview-funktion och knappikoner

console.log("🔐 script.js v9 laddad");

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
  const passphraseBox = document.getElementById("usePassphrase");
  const optionBoxes = ["useLower", "useUpper", "useNumbers", "useSymbols"].map(id => document.getElementById(id));

  const genererade = [];

  // Toggla andra boxar om "Använd ordfras" är vald
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

      console.log("Genererat lösenord:", JSON.stringify(lösenord));

      if (!lösenord) continue;

      const styrka = beräknaStyrka(lösenord);

      const rad = document.createElement("tr");
      const tdPw = document.createElement("td");
      tdPw.className = "pw-cell";

      // Skapa lösenordstext
      const pwText = document.createTextNode(lösenord + " ");

      // Skapa färgad styrka-tag inom parentes
      const tag = document.createElement("span");
      tag.className = `tag-${styrka}`;
      tag.textContent = `(${styrka})`;

      tdPw.appendChild(pwText);
      tdPw.appendChild(tag);

      const tdActions = document.createElement("td");
      const copyBtn = document.createElement("button");
      copyBtn.className = "icon-button copy-btn";
      copyBtn.setAttribute("aria-label", "Kopiera lösenord");
      copyBtn.setAttribute("data-tippy-content", "Kopiera lösenord");
      copyBtn.innerHTML = '<i class="fa-solid fa-copy"></i>';
      copyBtn.addEventListener("click", () => {
        navigator.clipboard.writeText(lösenord);
      });

      tdActions.appendChild(copyBtn);
      rad.appendChild(tdPw);
      rad.appendChild(tdActions);
      table.appendChild(rad);

      genererade.push({ lösenord, styrka });
    }

    if (genererade.length) {
      exportBtn.classList.remove("hidden");
      resetBtn.classList.remove("hidden");
      exportBtn.dataset.hasResults = "true";
    }

    visaResultatTabell();
  });

  resetBtn.addEventListener("click", () => {
    table.innerHTML = '';
    exportBtn.classList.add("hidden");
    resetBtn.classList.add("hidden");
    genererade.length = 0;
    doldResultatTabell();
    console.log("🧹 Resultat rensat");
  });

  // Event delegation för kopiera-knappar i resultattabellen
  document.getElementById('resultTable').addEventListener('click', function(e) {
    // Hitta närmaste knapp med klassen 'copy-btn'
    const copyBtn = e.target.closest('.copy-btn');
    if (copyBtn) {
      const row = copyBtn.closest('tr');
      const passwordCell = row.querySelector('.password-cell');
      if (passwordCell) {
        const password = passwordCell.textContent;
        navigator.clipboard.writeText(password).then(() => {
          copyBtn.classList.add('copied');
          setTimeout(() => copyBtn.classList.remove('copied'), 1000);
        });
      }
    }
  });

  // Kopiera lösenord från tabellen
  document.addEventListener('click', function(e) {
    const btn = e.target.closest('.copy-btn');
    if (btn) {
      const passwordCell = btn.closest('tr').querySelector('.password-cell');
      if (passwordCell) {
        const password = passwordCell.textContent.trim();
        // Felsökning: visa vad som ska kopieras
        // alert('Kopierar: ' + password);
        if (navigator.clipboard && window.isSecureContext) {
          navigator.clipboard.writeText(password)
            .then(() => {
              btn.classList.add('kopierad');
              setTimeout(() => btn.classList.remove('kopierad'), 1000);
            })
            .catch(err => {
              alert('Kunde inte kopiera: ' + err);
            });
        } else {
          // Fallback för äldre webbläsare
          const textarea = document.createElement('textarea');
          textarea.value = password;
          document.body.appendChild(textarea);
          textarea.select();
          try {
            document.execCommand('copy');
            btn.classList.add('kopierad');
            setTimeout(() => btn.classList.remove('kopierad'), 1000);
          } catch (err) {
            alert('Kunde inte kopiera: ' + err);
          }
          document.body.removeChild(textarea);
        }
      }
    }
  });

  function visaResultatKnappar() {
    document.getElementById('exportBtn').classList.remove('utils--dold');
    document.getElementById('resetBtn').classList.remove('utils--dold');
  }

  function doldResultatKnappar() {
    document.getElementById('exportBtn').classList.add('utils--dold');
    document.getElementById('resetBtn').classList.add('utils--dold');
  }

  function visaResultatTabell() {
    document.querySelector('.tabell__wrapper').classList.remove('utils--dold');
  }
  function doldResultatTabell() {
    document.querySelector('.tabell__wrapper').classList.add('utils--dold');
  }

  window.genereradeLösenord = () => genererade;
  window.genereraLösenord = genereraLösenord;
});
// ********** SLUT Sektion: DOM-hantering **********
