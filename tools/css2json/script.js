let lastJson = null;

document.getElementById('convertBtn').addEventListener('click', async () => {
  const files = document.getElementById('cssFiles').files;
  if (!files.length) return;

  const result = {};
  for (const file of files) {
    const text = await file.text();
    result[file.name] = cssToJson(text);
  }
  lastJson = JSON.stringify(result, null, 2);
  document.getElementById('output').textContent = lastJson;
  document.getElementById('downloadBtn').disabled = false;
});

document.getElementById('downloadBtn').addEventListener('click', () => {
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