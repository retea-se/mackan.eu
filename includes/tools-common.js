/**
 * tools-common.js - Gemensam JavaScript-bas för verktyg
 *
 * Innehåller vanliga funktioner för:
 * - Toast-meddelanden
 * - Loading-indikatorer
 * - Felhantering
 *
 * Användning:
 * <script src="/includes/tools-common.js"></script>
 */

/**
 * Visar ett toast-meddelande
 * @param {string} message - Meddelandet att visa
 * @param {string} type - Typ av meddelande ('error', 'success', 'info', 'warning')
 * @param {number} duration - Varaktighet i millisekunder (standard: 3000)
 */
function showToast(message, type = 'error', duration = 3000) {
  // Ta bort befintliga toast-meddelanden
  const existingToasts = document.querySelectorAll('.toast');
  existingToasts.forEach(toast => toast.remove());

  // Skapa ny toast
  const toast = document.createElement('div');
  toast.className = 'toast';

  // Lägg till typ-modifierare
  if (type === 'error') {
    toast.style.backgroundColor = '#c0392b';
    toast.style.color = '#fff';
  } else if (type === 'success') {
    toast.style.backgroundColor = '#1e8449';
    toast.style.color = '#fff';
  } else if (type === 'info') {
    toast.style.backgroundColor = '#2980b9';
    toast.style.color = '#fff';
  } else if (type === 'warning') {
    toast.style.backgroundColor = '#b9770e';
    toast.style.color = '#fff';
  }

  toast.textContent = message;
  document.body.appendChild(toast);

  // Visa toast
  setTimeout(() => {
    toast.classList.add('toast--synlig');
  }, 10);

  // Dölj och ta bort toast efter duration
  setTimeout(() => {
    toast.classList.remove('toast--synlig');
    setTimeout(() => {
      toast.remove();
    }, 400); // Vänta på transition
  }, duration);
}

/**
 * Visar en loading-indikator
 * @param {HTMLElement|string} container - Container-element eller selector
 * @param {string} message - Meddelande att visa (standard: 'Laddar...')
 * @returns {HTMLElement} Loading-elementet
 */
function showLoading(container, message = 'Laddar...') {
  const containerEl = typeof container === 'string'
    ? document.querySelector(container)
    : container;

  if (!containerEl) {
    console.warn('showLoading: Container hittades inte');
    return null;
  }

  // Ta bort befintliga loading-indikatorer
  const existingLoading = containerEl.querySelector('.loading');
  if (existingLoading) {
    existingLoading.remove();
  }

  // Skapa loading-indikator
  const loading = document.createElement('div');
  loading.className = 'loading';
  loading.innerHTML = `
    <div style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 1rem;">
      <span style="display: inline-block; width: 20px; height: 20px; border: 3px solid rgba(0,0,0,0.1); border-top-color: var(--primary-color, #0066cc); border-radius: 50%; animation: spin 0.6s linear infinite;"></span>
      <span>${message}</span>
    </div>
  `;

  // Lägg till spin-animation om den inte finns
  if (!document.querySelector('#loading-spin-style')) {
    const style = document.createElement('style');
    style.id = 'loading-spin-style';
    style.textContent = `
      @keyframes spin {
        to { transform: rotate(360deg); }
      }
    `;
    document.head.appendChild(style);
  }

  containerEl.appendChild(loading);
  return loading;
}

/**
 * Döljer en loading-indikator
 * @param {HTMLElement|string} container - Container-element eller selector
 */
function hideLoading(container) {
  const containerEl = typeof container === 'string'
    ? document.querySelector(container)
    : container;

  if (!containerEl) {
    return;
  }

  const loading = containerEl.querySelector('.loading');
  if (loading) {
    loading.remove();
  }
}

/**
 * Wrapper för async-funktioner med automatisk felhantering och loading
 * @param {Function} asyncFn - Async-funktion att köra
 * @param {Object} options - Alternativ
 * @param {HTMLElement|string} options.loadingContainer - Container för loading-indikator
 * @param {string} options.loadingMessage - Meddelande för loading
 * @param {string} options.errorMessage - Meddelande vid fel
 * @param {boolean} options.showToastOnError - Visa toast vid fel (standard: true)
 */
async function withLoading(asyncFn, options = {}) {
  const {
    loadingContainer = null,
    loadingMessage = 'Laddar...',
    errorMessage = 'Ett fel uppstod',
    showToastOnError = true
  } = options;

  let loadingElement = null;

  try {
    // Visa loading
    if (loadingContainer) {
      loadingElement = showLoading(loadingContainer, loadingMessage);
    }

    // Kör funktion
    const result = await asyncFn();
    return result;
  } catch (error) {
    console.error('withLoading error:', error);

    // Visa felmeddelande
    if (showToastOnError) {
      const message = error.message || errorMessage;
      showToast(message, 'error');
    }

    throw error;
  } finally {
    // Dölj loading
    if (loadingElement) {
      hideLoading(loadingContainer);
    }
  }
}

/**
 * Exporterar data till CSV
 * @param {Array} data - Data att exportera (array av objekt)
 * @param {string} filename - Filnamn för exporten (standard: 'export.csv')
 * @param {string} delimiter - Avgränsare (standard: ';')
 * @param {boolean} includeBOM - Inkludera BOM för UTF-8 (standard: true)
 */
function exportToCSV(data, filename = 'export.csv', delimiter = ';', includeBOM = true) {
  if (!data || data.length === 0) {
    showToast('Ingen data att exportera.', 'warning');
    return;
  }

  try {
    const headers = Object.keys(data[0]);
    const lines = [headers.join(delimiter)];

    data.forEach(row => {
      const line = headers.map(h => {
        const value = row[h] ?? '';
        // Escape quotes och wrap in quotes
        return `"${String(value).replace(/"/g, '""')}"`;
      }).join(delimiter);
      lines.push(line);
    });

    const csv = lines.join('\n');
    const content = includeBOM ? '\uFEFF' + csv : csv; // BOM för UTF-8
    const blob = new Blob([content], { type: 'text/csv;charset=utf-8;' });
    downloadBlob(blob, filename);

    showToast(`CSV exporterad: ${filename}`, 'success');
  } catch (error) {
    console.error('Export CSV error:', error);
    showToast('Fel vid export till CSV: ' + error.message, 'error');
  }
}

/**
 * Exporterar data till JSON
 * @param {Array|Object} data - Data att exportera
 * @param {string} filename - Filnamn för exporten (standard: 'export.json')
 * @param {boolean} pretty - Formatera JSON (standard: true)
 */
function exportToJSON(data, filename = 'export.json', pretty = true) {
  if (!data) {
    showToast('Ingen data att exportera.', 'warning');
    return;
  }

  try {
    const json = pretty
      ? JSON.stringify(data, null, 2)
      : JSON.stringify(data);
    const blob = new Blob([json], { type: 'application/json;charset=utf-8;' });
    downloadBlob(blob, filename);

    showToast(`JSON exporterad: ${filename}`, 'success');
  } catch (error) {
    console.error('Export JSON error:', error);
    showToast('Fel vid export till JSON: ' + error.message, 'error');
  }
}

/**
 * Exporterar data till Excel (XLSX)
 * @param {Array} data - Data att exportera (array av objekt)
 * @param {string} filename - Filnamn för exporten (standard: 'export.xlsx')
 * @param {string} sheetName - Namn på arket (standard: 'Data')
 */
function exportToExcel(data, filename = 'export.xlsx', sheetName = 'Data') {
  if (typeof XLSX === 'undefined') {
    showToast('Excel-export kräver att XLSX biblioteket laddas in.', 'error');
    return;
  }

  if (!data || data.length === 0) {
    showToast('Ingen data att exportera.', 'warning');
    return;
  }

  try {
    const ws = XLSX.utils.json_to_sheet(data);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, sheetName);

    const wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'array' });
    const blob = new Blob([wbout], { type: 'application/octet-stream' });
    downloadBlob(blob, filename);

    showToast(`Excel exporterad: ${filename}`, 'success');
  } catch (error) {
    console.error('Export Excel error:', error);
    showToast('Fel vid export till Excel: ' + error.message, 'error');
  }
}

/**
 * Hjälpfunktion för att ladda ner en blob
 * @param {Blob} blob - Blob att ladda ner
 * @param {string} filename - Filnamn
 */
function downloadBlob(blob, filename) {
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url;
  a.download = filename;
  document.body.appendChild(a);
  a.click();
  setTimeout(() => {
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
  }, 100);
}

/**
 * Standardiserar personnummer till 12-siffrigt format
 * @param {string} pnr - Personnummer (10 eller 12 siffror)
 * @returns {string} - 12-siffrigt personnummer eller tom sträng om ogiltigt
 */
function standardizePersonnummer(pnr) {
  if (!pnr) return '';

  // Ta bort bindestreck och mellanslag
  pnr = pnr.trim().replace(/[-\s]/g, '');

  // Om 10 siffror, lägg till sekel
  if (pnr.length === 10) {
    const year = parseInt(pnr.substring(0, 2));
    const currentYear = new Date().getFullYear() % 100;
    const century = (year > currentYear) ? '19' : '20';
    return century + pnr;
  }

  // Om 12 siffror, returnera som det är
  if (pnr.length === 12 && /^\d{12}$/.test(pnr)) {
    return pnr;
  }

  return '';
}

/**
 * Validerar Luhn-algoritm för personnummer
 * @param {string} pnr - 12-siffrigt personnummer
 * @returns {boolean} - True om Luhn-validering lyckas
 */
function validateLuhn(pnr) {
  if (!pnr || pnr.length !== 12) return false;

  // Använd de 9 första siffrorna (utan sekel och kontrollsiffra)
  const digits = pnr.substring(2, 11).split('').map(Number);
  let sum = 0;

  for (let i = 0; i < digits.length; i++) {
    let val = digits[i] * (i % 2 === 0 ? 2 : 1);
    if (val > 9) val -= 9;
    sum += val;
  }

  const kontroll = (10 - (sum % 10)) % 10;
  const sista = Number(pnr[11]);

  return kontroll === sista;
}

/**
 * Validerar personnummer med Luhn och kön
 * @param {string} pnr - 12-siffrigt personnummer
 * @param {string} kön - 'man' eller 'kvinna' (valfritt)
 * @returns {boolean} - True om personnumret är giltigt
 */
function validatePersonnummer(pnr, kön = null) {
  // Standardisera först
  const standardized = standardizePersonnummer(pnr);
  if (!standardized) return false;

  // Validera Luhn
  if (!validateLuhn(standardized)) return false;

  // Validera kön om angivet
  if (kön) {
    const nästSista = Number(standardized[10]);
    const ärJämn = nästSista % 2 === 0;
    const ärKvinna = kön.toLowerCase() === 'kvinna' || kön.toLowerCase() === 'kvinnlig';

    if (ärKvinna && !ärJämn) return false;
    if (!ärKvinna && ärJämn) return false;
  }

  return true;
}

/**
 * Formaterar personnummer för visning: YYMMDD-NNNX
 * @param {string} pnr - 12-siffrigt personnummer
 * @returns {string} - Formaterat personnummer
 */
function formatPersonnummer(pnr) {
  const standardized = standardizePersonnummer(pnr);
  if (!standardized || standardized.length !== 12) return pnr;

  const yymmdd = standardized.substring(2, 8);
  const nnnx = standardized.substring(8);
  return `${yymmdd}-${nnnx}`;
}

/**
 * Hämtar data från en tabell
 * @param {string|HTMLElement} tableSelector - Tabell-selector eller element
 * @returns {Array} - Array av objekt med tabelldata
 */
function getTableData(tableSelector) {
  const table = typeof tableSelector === 'string'
    ? document.querySelector(tableSelector)
    : tableSelector;

  if (!table) {
    console.warn('getTableData: Tabell hittades inte');
    return [];
  }

  const headers = Array.from(table.querySelectorAll('thead th')).map(th => th.textContent.trim());
  const rows = table.querySelectorAll('tbody tr');

  return Array.from(rows).map(row => {
    const obj = {};
    headers.forEach((h, i) => {
      obj[h] = row.cells[i]?.textContent.trim() || '';
    });
    return obj;
  });
}

// Exportera funktioner för användning i moduler
if (typeof module !== 'undefined' && module.exports) {
  module.exports = {
    showToast,
    showLoading,
    hideLoading,
    withLoading,
    exportToCSV,
    exportToJSON,
    exportToExcel,
    downloadBlob,
    standardizePersonnummer,
    validateLuhn,
    validatePersonnummer,
    formatPersonnummer,
    getTableData
  };
}

