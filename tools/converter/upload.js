// tools/converter/upload.js - v4 (debug)

export function init() {
  console.log("upload.js v4 initieras");

  const container = document.getElementById('uploadContainer');
  if (!container) {
    console.warn("uploadContainer saknas i DOM, init avbruten");
    return;
  }

  const fileInput = document.createElement('input');
  fileInput.type = 'file';
  fileInput.accept = '.csv,.json,.xlsx';
  fileInput.className = 'falt__input';
  fileInput.id = 'uploadInput';

  const info = document.createElement('div');
  info.id = 'uploadInfo';
  info.className = 'form__grupp';
  info.style.marginTop = '0.5rem';

  container.appendChild(fileInput);
  container.appendChild(info);

  console.log("Filuppladdningsfält skapat:", fileInput);

  fileInput.addEventListener('change', handleFile);
}

function handleFile(event) {
  const file = event.target.files[0];
  if (!file) return;

  const ext = file.name.split('.').pop().toLowerCase();
  const output = document.getElementById('converterInput');
  const info = document.getElementById('uploadInfo');
  info.textContent = `📂 Fil vald: ${file.name} (${ext.toUpperCase()})`;

  if (ext === 'json') {
    const reader = new FileReader();
    reader.onload = e => {
      output.value = e.target.result;
      console.log("JSON fil laddad");
    };
    reader.readAsText(file);
  }

  else if (ext === 'csv') {
    const reader = new FileReader();
    reader.onload = e => {
      output.value = e.target.result;
      console.log("CSV fil laddad");
    };
    reader.readAsText(file);
  }

  else if (ext === 'xlsx') {
    const reader = new FileReader();
    reader.onload = e => {
      const data = new Uint8Array(e.target.result);
      const workbook = XLSX.read(data, { type: 'array' });
      const firstSheet = workbook.SheetNames[0];
      const csv = XLSX.utils.sheet_to_csv(workbook.Sheets[firstSheet]);
      output.value = csv;
      info.textContent += ` – ark: ${firstSheet}`;
      console.log("XLSX fil laddad och konverterad till CSV");
    };
    reader.readAsArrayBuffer(file);
  }

  else {
    output.value = "❌ Ogiltigt filformat.";
    console.warn("Filen kunde inte tolkas.");
    showToast("Ogiltigt filformat. Stödda format: JSON, CSV, XLSX", 'error');
  }
}
