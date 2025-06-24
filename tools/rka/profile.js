// profile.js
(function () {
  const table      = document.querySelector("#profileTable");
  const dataField  = document.getElementById("profileData");
  const form       = document.getElementById("advForm");

  /* === 1. Läs in eller initiera profil =========================== */
  let lastprofile = [];
  try {
    lastprofile = JSON.parse(dataField.value);
    if (!Array.isArray(lastprofile) || !lastprofile.length) throw 1;
  } catch {
    lastprofile = [
      { hours: 8,  load: 5 },
      { hours: 12, load: 10 },
      { hours: 4,  load: 2 }
    ];
  }

  /* === 2. Rendera tabellen ====================================== */
  function renderProfileTable() {
    const tbody = table.querySelector("tbody");
    tbody.innerHTML = "";
    lastprofile.forEach((row, i) => {
      tbody.insertAdjacentHTML(
        "beforeend",
        `<tr>
           <td>${i + 1}</td>
           <td><input type="number" min="0" step="0.1"
                 value="${row.hours ?? 0}"
                 onchange="updateProfile(${i},'hours',this.value)"></td>
           <td><input type="number" min="0" step="0.1"
                 value="${row.load ?? 0}"
                 onchange="updateProfile(${i},'load',this.value)"></td>
           <td>${lastprofile.length > 1
               ? `<button type="button" onclick="removeRow(${i})">–</button>`
               : ""}</td>
         </tr>`
      );
    });
    dataField.value = JSON.stringify(lastprofile);
  }

  /* === 3. Globala hjälpfunktioner (behövs av inline-handlers) ==== */
  window.updateProfile = function (idx, field, val) {
    lastprofile[idx][field] = parseFloat(val) || 0;
    dataField.value = JSON.stringify(lastprofile);
  };

  window.addRow = function () {
    lastprofile.push({ hours: 1, load: 0 });
    renderProfileTable();
  };

  window.removeRow = function (idx) {
    if (lastprofile.length > 1) {
      lastprofile.splice(idx, 1);
      renderProfileTable();
    }
  };

  /* === 4. Synka exakt innan submit ============================== */
  form.addEventListener("submit", () => {
    dataField.value = JSON.stringify(lastprofile);
  });

  /* === 5. Kör rendering vid sidladdning ========================= */
  document.addEventListener("DOMContentLoaded", renderProfileTable);

  /* === 6. Exportera globalt för PHP-snutten som läser window.xxx == */
  window.lastprofile = lastprofile;
})();
