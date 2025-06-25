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

// Hämta hela rapporten (du kan justera selector om du vill ha mer/mindre)
function getReportHtml() {
  // Exportera både resultat och inmatade värden
  const output = document.querySelector('#output');
  let html = '';
  if (output) html += output.innerHTML;
  // Hämta ALLA tabeller med klass "tabell"
  document.querySelectorAll('.tabell__wrapper').forEach(wrapper => {
    html += '<h2>Inmatade värden</h2>' + wrapper.innerHTML;
  });
  return html;
}

// Excel-export med unika arknamn
document.getElementById('exportExcel').addEventListener('click', function() {
  console.log("Startar Excel-export...");
  import('https://cdn.sheetjs.com/xlsx-0.20.3/package/xlsx.mjs').then(XLSX => {
    try {
      const html = getReportHtml();
      const temp = document.createElement('div');
      temp.innerHTML = html;

      let aoa = [];
      // Rubrik
      const mainTitle = temp.querySelector('h1, h2, .rubrik');
      if (mainTitle) aoa.push([mainTitle.textContent]);
      aoa.push([]);

      // Hämta ALLA tabeller och deras rubriker
      temp.querySelectorAll('.tabell').forEach(table => {
        // Hitta närmaste föregående rubrik (h2 eller h3)
        let heading = table;
        while (heading && heading.previousSibling) {
          heading = heading.previousSibling;
          if (heading.nodeType === 1 && /H[2-3]/.test(heading.tagName)) break;
        }
        if (heading && heading.textContent) aoa.push([heading.textContent]);
        Array.from(table.rows).forEach(row => {
          aoa.push(Array.from(row.cells).map(cell => cell.textContent.trim()));
        });
        aoa.push([]);
      });

      // Skapa arbetsbok och ark
      const wb = XLSX.utils.book_new();
      const ws = XLSX.utils.aoa_to_sheet(aoa);
      XLSX.utils.book_append_sheet(wb, ws, "Rapport");

      // Unikt filnamn
      const now = new Date();
      const pad = n => n.toString().padStart(2, '0');
      const fname = `RKA-rapport_${now.getFullYear()}-${pad(now.getMonth()+1)}-${pad(now.getDate())}_${pad(now.getHours())}${pad(now.getMinutes())}${pad(now.getSeconds())}.xlsx`;
      console.log("Sparar Excel-fil:", fname);
      XLSX.writeFile(wb, fname);
      console.log("Excel-export klar!");
    } catch (err) {
      console.error("Excel-export FEL:", err);
      alert("Kunde inte skapa Excel-fil. Se konsolen för mer info.");
    }
  }).catch(err => {
    console.error("Kunde inte ladda SheetJS:", err);
    alert("Kunde inte ladda SheetJS för Excel-export.");
  });
});

// PDF-export med rätt jsPDF-anrop
document.getElementById('exportPDF').addEventListener('click', async function() {
  console.log("Startar PDF-export...");
  import('https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js').then(async jsPDFModule => {
    const jsPDF = jsPDFModule.jsPDF || window.jspdf?.jsPDF || window.jsPDF;
    const doc = new jsPDF('p', 'mm', 'a4');
    // Skapa en temporär wrapper för rapporten
    const report = document.createElement('div');
    report.className = "pdf-export";
    // Lägg till CSS som tvingar fontstorlek
    const pdfCss = `
      <style>
        .pdf-export, .pdf-export * {
          font-size: 10pt !important;
          font-family: Arial, sans-serif !important;
          line-height: 1.2 !important;
        }
        .pdf-export h1, .pdf-export h2, .pdf-export h3, .pdf-export h4, .pdf-export h5, .pdf-export h6 {
          font-size: 11pt !important;
        }
        .pdf-export table { width: 100% !important; }
        .pagebreak { page-break-before: always; }
      </style>
    `;
    report.innerHTML = pdfCss + getReportHtml();
    document.body.appendChild(report);

    // Ladda html2canvas om det inte redan finns
    if (!window.html2canvas) {
      await import('https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js');
    }

    doc.html(report, {
      callback: function (doc) {
        const now = new Date();
        const pad = n => n.toString().padStart(2, '0');
        const fname = `RKA-rapport_${now.getFullYear()}-${pad(now.getMonth()+1)}-${pad(now.getDate())}_${pad(now.getHours())}${pad(now.getMinutes())}${pad(now.getSeconds())}.pdf`;
        doc.save(fname);
        document.body.removeChild(report);
        console.log("PDF-export klar!");
      },
      margin: [10, 10, 10, 10],
      autoPaging: 'text',
      x: 0,
      y: 0,
      width: 190 // mm, ungefär A4 minus marginal
    });
  }).catch(err => {
    console.error("Kunde inte ladda jsPDF:", err);
    alert("Kunde inte ladda jsPDF för PDF-export.");
  });
}); // PDF-export slut

}); // <-- Avslutar hela DOMContentLoaded-funktionen
