let lastJson = null;

const convertButton = document.getElementById('convertBtn');
const downloadButton = document.getElementById('downloadBtn');
const resetButton = document.getElementById('resetBtn');
const output = document.getElementById('output');
const resultSection = document.getElementById('resultSection');
const fileInput = document.getElementById('cssFiles');

convertButton?.addEventListener('click', async () => {
  if (!fileInput?.files?.length) {
    return;
  }

  const result = {};
  for (const file of fileInput.files) {
    const text = await file.text();
    result[file.name] = cssToJson(text);
  }

  lastJson = JSON.stringify(result, null, 2);
  output.textContent = lastJson;

  downloadButton?.classList.remove('hidden');
  downloadButton?.removeAttribute('disabled');
  resetButton?.classList.remove('hidden');
  resultSection?.classList.remove('hidden');
});

downloadButton?.addEventListener('click', () => {
  if (!lastJson) return;
  const blob = new Blob([lastJson], { type: 'application/json' });
  const url = URL.createObjectURL(blob);
  const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
  const filename = `css-json-${timestamp}.json`;

  const a = document.createElement('a');
  a.href = url;
  a.download = filename;
  a.click();
  URL.revokeObjectURL(url);
});

resetButton?.addEventListener('click', () => {
  if (fileInput) {
    fileInput.value = '';
  }
  lastJson = null;
  output.textContent = '';
  downloadButton?.classList.add('hidden');
  downloadButton?.setAttribute('disabled', 'disabled');
  resetButton?.classList.add('hidden');
  resultSection?.classList.add('hidden');
});

// Enkel CSS till JSON-konverterare (väldigt grundläggande)
function cssToJson(css) {
  const rules = {};
  const regex = /([^{]+)\{([^}]+)\}/g;
  let match;
  while ((match = regex.exec(css)) !== null) {
    const selector = match[1].trim();
    const body = match[2].trim();
    const props = {};
    body.split(';').forEach(line => {
      const [key, value] = line.split(':');
      if (key && value) props[key.trim()] = value.trim();
    });
    rules[selector] = props;
  }
  return rules;
}
