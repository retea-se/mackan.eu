// export.js - v6 med BOM och korrekt encoding

document.addEventListener('DOMContentLoaded', () => {
  const downloadBtn = document.getElementById('downloadBtn');
  const exportFormatSelect = document.getElementById('exportFormat');
  const resultTable = document.getElementById('resultTable');

  // Använd gemensam getTableData från tools-common.js istället
  // function getTableData() { ... } // Borttagen - använder getTableData() från tools-common.js

  // Använd gemensam exportToCSV från tools-common.js istället
  // convertToCSV har flyttats till tools-common.js (exportToCSV)

  function convertToTXT(data) {
    const headers = Object.keys(data[0]);
    const lines = [headers.join('\t')];
    data.forEach(row => {
      const line = headers.map(h => row[h] || '').join('\t');
      lines.push(line);
    });
    return '\uFEFF' + lines.join('\n'); // BOM först
  }

  // Använd gemensamma funktioner från tools-common.js istället
  // exportExcel, makeFileName, downloadBlob har flyttats till tools-common.js

  function openNewTabWithContent(content, format) {
    const newWin = window.open('', '_blank');
    if (!newWin) {
      alert('Popup blockerad! Tillåt popup-fönster för denna sida.');
      return;
    }

    const escapedContent = content.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    const now = new Date();
    const pad = n => n.toString().padStart(2, '0');
    const filename = `testdata_${now.getFullYear()}${pad(now.getMonth()+1)}${pad(now.getDate())}_${pad(now.getHours())}${pad(now.getMinutes())}${pad(now.getSeconds())}.${format}`;

    newWin.document.write(`
      <html>
      <head>
        <title>Export - ${format.toUpperCase()}</title>
        <style>
          body {
            font-family: monospace, monospace;
            background: white;
            color: black;
            padding: 1rem;
            white-space: pre-wrap;
          }
          button {
            margin-right: 1rem;
            margin-bottom: 1rem;
            padding: 0.5rem 1rem;
            font-size: 1rem;
            cursor: pointer;
          }
        </style>
      </head>
      <body>
        <button id="copyBtn">Kopiera till urklipp</button>
        <button id="downloadBtn">Ladda ner fil</button>
        <pre>${escapedContent}</pre>
        <script>
          const content = ` + "`" + content.replace(/`/g, '\`') + "`" + `;

          document.getElementById('copyBtn').addEventListener('click', () => {
            navigator.clipboard.writeText(content).then(() => {
              alert('Kopierat till urklipp!');
            }).catch(() => {
              alert('Misslyckades att kopiera.');
            });
          });

          document.getElementById('downloadBtn').addEventListener('click', () => {
            const blob = new Blob(["\uFEFF" + content], {type: 'text/${format === "txt" ? "plain" : format}'});
            const a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = '${filename}';
            document.body.appendChild(a);
            a.click();
            setTimeout(() => {
              document.body.removeChild(a);
              URL.revokeObjectURL(a.href);
            }, 100);
          });
        </script>
      </body>
      </html>
    `);

    newWin.document.close();
  }

  downloadBtn.addEventListener('click', () => {
    const format = exportFormatSelect.value.toLowerCase();
    const data = getTableData(); // Använd gemensam getTableData från tools-common.js
    if (data.length === 0) {
      showToast('Ingen data att exportera.', 'warning');
      return;
    }

    // Skapa filnamn med timestamp
    const now = new Date();
    const pad = n => n.toString().padStart(2, '0');
    const timestamp = `${now.getFullYear()}${pad(now.getMonth()+1)}${pad(now.getDate())}_${pad(now.getHours())}${pad(now.getMinutes())}${pad(now.getSeconds())}`;

    if (format === 'xlsx') {
      // Använd gemensam exportToExcel från tools-common.js
      exportToExcel(data, `testdata_${timestamp}.xlsx`, 'Testdata');
      return;
    }

    if (format === 'json') {
      // Använd gemensam exportToJSON från tools-common.js
      exportToJSON(data, `testdata_${timestamp}.json`);
      return;
    }

    if (format === 'csv') {
      // Använd gemensam exportToCSV från tools-common.js
      exportToCSV(data, `testdata_${timestamp}.csv`);
      return;
    }

    // För TXT, öppna i ny flik (behåller befintlig funktionalitet)
    if (format === 'txt') {
      const text = convertToTXT(data);
      openNewTabWithContent(text, format);
      return;
    }
  });
});
