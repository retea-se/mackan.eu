// export.js - v2
// Använder gemensamma export-funktioner från tools-common.js

// Hjälper till att ladda ner fil i olika format
export function exportData(data, format) {
  if (!data || data.length === 0) {
    showToast('Ingen data att exportera.', 'warning');
    return;
  }

  // Skapa filnamn med timestamp
  const now = new Date();
  const pad = n => n.toString().padStart(2, '0');
  const timestamp = `${now.getFullYear()}${pad(now.getMonth()+1)}${pad(now.getDate())}_${pad(now.getHours())}${pad(now.getMinutes())}${pad(now.getSeconds())}`;

  switch (format) {
    case 'json':
      // Använd gemensam exportToJSON från tools-common.js
      exportToJSON(data, `testid_${timestamp}.json`);
      break;
    case 'csv':
      // Använd gemensam exportToCSV från tools-common.js
      exportToCSV(data, `testid_${timestamp}.csv`);
      break;
    case 'xlsx':
      // Använd gemensam exportToExcel från tools-common.js
      exportToExcel(data, `testid_${timestamp}.xlsx`, 'Testpersoner');
      break;
    default:
      showToast('Okänt exportformat.', 'error');
  }
}
