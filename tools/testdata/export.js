// export.js - v6 med BOM och korrekt encoding

document.addEventListener('DOMContentLoaded', () => {
  const downloadBtn = document.getElementById('downloadBtn');
  const exportFormatSelect = document.getElementById('exportFormat');
  const resultTable = document.getElementById('resultTable');

  function getTableData() {
    const headers = Array.from(resultTable.querySelectorAll('thead th')).map(th => th.textContent.trim());
    const rows = resultTable.querySelectorAll('tbody tr');
    return Array.from(rows).map(row => {
      const obj = {};
      headers.forEach((h, i) => {
        obj[h] = row.cells[i]?.textContent.trim() || '';
      });
      return obj;
    });
  }

  function convertToCSV(data) {
    const headers = Object.keys(data[0]);
    const lines = [headers.join(';')];
    data.forEach(row => {
      const line = headers.map(h => `"${(row[h] || '').replace(/"/g, '""')}"`).join(';');
      lines.push(line);
    });
    return '\uFEFF' + lines.join('\n'); // BOM först
  }

  function convertToTXT(data) {
    const headers = Object.keys(data[0]);
    const lines = [headers.join('\t')];
    data.forEach(row => {
      const line = headers.map(h => row[h] || '').join('\t');
      lines.push(line);
    });
    return '\uFEFF' + lines.join('\n'); // BOM först
  }

  function exportExcel(data) {
    const ws = XLSX.utils.json_to_sheet(data);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'Testdata');

    const wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'array' });
    const blob = new Blob([wbout], { type: 'application/octet-stream' });
    downloadBlob(blob, makeFileName('xlsx'));
  }

  function makeFileName(ext) {
    const now = new Date();
    const pad = n => n.toString().padStart(2, '0');
    return `testdata_${now.getFullYear()}${pad(now.getMonth()+1)}${pad(now.getDate())}_${pad(now.getHours())}${pad(now.getMinutes())}${pad(now.getSeconds())}.${ext}`;
  }

  function downloadBlob(blob, filename) {
    const a = document.createElement('a');
    const url = URL.createObjectURL(blob);
    a.href = url;
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    setTimeout(() => {
      document.body.removeChild(a);
      URL.revokeObjectURL(url);
    }, 100);
  }

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
    const data = getTableData();
    if (data.length === 0) {
      alert('Ingen data att exportera.');
      return;
    }

    if (format === 'xlsx') {
      exportExcel(data);
      return;
    }

    let text = '';
    if (format === 'json') {
      text = JSON.stringify(data, null, 2);
    } else if (format === 'csv') {
      text = convertToCSV(data);
    } else if (format === 'txt') {
      text = convertToTXT(data);
    }

    openNewTabWithContent(text, format);
  });
});
