// export.js - v1

// ********** START Sektion: Exportera JSON **********

function exportJSON(data, filename) {
  const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
  const länk = document.createElement('a');
  länk.href = URL.createObjectURL(blob);
  länk.download = filename;
  länk.click();
  console.log(`[export.js] 📦 Exporterad JSON: ${filename}`);
}

// ********** SLUT Sektion: Exportera JSON **********


// ********** START Sektion: Exportera CSV **********

function exportCSV(data, filename) {
  if (!data.length) return;

  const headers = ['caseIdentifier', 'caseTitle'];
  const rows = data.map(d => [d.caseIdentifier, `"${d.caseTitle}"`]);
  const csv = [headers.join(','), ...rows.map(r => r.join(','))].join('\n');

  const blob = new Blob([csv], { type: 'text/csv' });
  const länk = document.createElement('a');
  länk.href = URL.createObjectURL(blob);
  länk.download = filename;
  länk.click();
  console.log(`[export.js] 📦 Exporterad CSV: ${filename}`);
}

// ********** SLUT Sektion: Exportera CSV **********
