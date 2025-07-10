// a2.js - Refactored for simplified current load calculator

document.addEventListener('DOMContentLoaded', function() {

// --- Elementreferenser ---
const form = document.getElementById('advForm');

// --- Debounce autosubmit ---
let dTimer = null, delay = 600;
function deb() {
  clearTimeout(dTimer);
  dTimer = setTimeout(() => {
    form.requestSubmit();
  }, delay);
}

// Auto-submit för alla input- och select-fält
const textIds = ['rating','cosphi','price','co2','provMin',
                 'provEveryVal','runHrs','tankInt','buffDays','buffPct',
                 'aktuellLast','aktuellTid'];
const selIds  = ['ratingUnit','fuel','provEveryUnit',
                 'swedeTown','genType','phase','aktuellLastUnit'];

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

// --- EXPORT FUNCTIONS ---

// Hämta hela rapporten för export
function getReportHtml() {
  const main = document.querySelector('main');
  if (!main) return '';

  let html = '';

  // Lägg till titel
  const title = main.querySelector('h1');
  if (title) html += `<h1>${title.textContent}</h1>`;

  // Hämta alla resultat-sektioner (alla .layout__sektion efter formuläret)
  const resultSections = main.querySelectorAll('.layout__sektion');
  resultSections.forEach(section => {
    html += section.outerHTML;
  });

  return html;
}

// Excel-export
const exportExcelBtn = document.getElementById('exportExcel');
if (exportExcelBtn) {
  exportExcelBtn.addEventListener('click', function() {
    console.log("Startar Excel-export...");
    import('https://cdn.sheetjs.com/xlsx-0.20.3/package/xlsx.mjs').then(XLSX => {
      try {
        const html = getReportHtml();
        const temp = document.createElement('div');
        temp.innerHTML = html;

        let aoa = [];

        // Huvudrubrik
        const mainTitle = temp.querySelector('h1');
        if (mainTitle) aoa.push([mainTitle.textContent]);
        aoa.push([]);

        // Hämta alla sektioner och deras tabeller
        temp.querySelectorAll('.layout__sektion').forEach(section => {
          // Sektionsrubrik
          const heading = section.querySelector('h2');
          if (heading) aoa.push([heading.textContent]);

          // Tabelldata från denna sektion
          const table = section.querySelector('.tabell');
          if (table) {
            Array.from(table.rows).forEach(row => {
              aoa.push(Array.from(row.cells).map(cell => cell.textContent.trim()));
            });
          }
          aoa.push([]);
        });

        // Skapa arbetsbok
        const wb = XLSX.utils.book_new();
        const ws = XLSX.utils.aoa_to_sheet(aoa);
        XLSX.utils.book_append_sheet(wb, ws, "RKA Rapport");

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
}

// PDF-export
const exportPDFBtn = document.getElementById('exportPDF');
if (exportPDFBtn) {
  exportPDFBtn.addEventListener('click', async function() {
    console.log("Startar PDF-export...");
    try {
      const jsPDFModule = await import('https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js');
      const jsPDF = jsPDFModule.jsPDF || window.jspdf?.jsPDF || window.jsPDF;

      const doc = new jsPDF('p', 'mm', 'a4');

      // Skapa temporär wrapper för rapporten
      const report = document.createElement('div');
      report.className = "pdf-export";

      // CSS för PDF
      const pdfCss = `
        <style>
          .pdf-export, .pdf-export * {
            font-size: 10pt !important;
            font-family: Arial, sans-serif !important;
            line-height: 1.2 !important;
          }
          .pdf-export h1, .pdf-export h2, .pdf-export h3, .pdf-export h4 {
            font-size: 11pt !important;
            font-weight: bold !important;
          }
          .pdf-export table {
            width: 100% !important;
            border-collapse: collapse !important;
          }
          .pdf-export td, .pdf-export th {
            border: 1px solid #ddd !important;
            padding: 4px !important;
          }
          .pagebreak { page-break-before: always; }
        </style>
      `;

      report.innerHTML = pdfCss + getReportHtml();
      document.body.appendChild(report);

      // Ladda html2canvas om det behövs
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
        width: 190
      });
    } catch (err) {
      console.error("PDF-export FEL:", err);
      alert("Kunde inte skapa PDF-fil. Se konsolen för mer info.");
    }
  });
}

// TXT-export
const exportTXTBtn = document.getElementById('exportTXT');
if (exportTXTBtn) {
  exportTXTBtn.addEventListener('click', function() {
    console.log("Startar TXT-export...");
    try {
      const html = getReportHtml();
      const temp = document.createElement('div');
      temp.innerHTML = html;

      let text = temp.innerText || temp.textContent || "";

      // Rensa upp texten
      text = text.replace(/\n{3,}/g, '\n\n');
      text = text.replace(/\t+/g, ' ');

      // Skapa och ladda ned filen
      const blob = new Blob([text], {type: 'text/plain;charset=utf-8'});
      const now = new Date();
      const pad = n => n.toString().padStart(2, '0');
      const fname = `RKA-rapport_${now.getFullYear()}-${pad(now.getMonth()+1)}-${pad(now.getDate())}_${pad(now.getHours())}${pad(now.getMinutes())}${pad(now.getSeconds())}.txt`;

      const link = document.createElement('a');
      link.href = URL.createObjectURL(blob);
      link.download = fname;
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);

      console.log("TXT-export klar!");
    } catch (err) {
      console.error("TXT-export FEL:", err);
      alert("Kunde inte skapa TXT-fil. Se konsolen för mer info.");
    }
  });
}

console.log("a2.js laddad - export-funktioner aktiverade");

}); // Slut DOMContentLoaded
