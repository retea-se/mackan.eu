// export.js - v1

// Hjälper till att ladda ner fil i olika format
export function exportData(data, format) {
  if (!data || data.length === 0) return;

  switch (format) {
    case 'json':
      downloadJSON(data);
      break;
    case 'csv':
      downloadCSV(data);
      break;
    case 'xlsx':
      downloadExcel(data);
      break;
  }
}

function downloadJSON(data) {
  const blob = new Blob([JSON.stringify(data, null, 2)], {
    type: 'application/json'
  });
  triggerDownload(blob, 'testpersoner.json');
}

function downloadCSV(data) {
  const headers = Object.keys(data[0]);
  const rows = data.map(row => headers.map(h => JSON.stringify(row[h] ?? '')).join(','));
  const csv = [headers.join(','), ...rows].join('\n');
  const blob = new Blob([csv], { type: 'text/csv' });
  triggerDownload(blob, 'testpersoner.csv');
}

function downloadExcel(data) {
  if (typeof XLSX === 'undefined') {
    alert('Excel-export kräver att XLSX biblioteket laddas in.');
    return;
  }
  const ws = XLSX.utils.json_to_sheet(data);
  const wb = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(wb, ws, 'Testpersoner');
  const blob = workbookToBlob(wb);
  triggerDownload(blob, 'testpersoner.xlsx');
}

function triggerDownload(blob, filename) {
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url;
  a.download = filename;
  a.click();
  URL.revokeObjectURL(url);
}

function workbookToBlob(workbook) {
  const wopts = { bookType: 'xlsx', type: 'binary' };
  const wbout = XLSX.write(workbook, wopts);
  const buf = new ArrayBuffer(wbout.length);
  const view = new Uint8Array(buf);
  for (let i = 0; i < wbout.length; ++i) view[i] = wbout.charCodeAt(i);
  return new Blob([buf], { type: 'application/octet-stream' });
}
