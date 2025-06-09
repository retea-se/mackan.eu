// test.js - v3
const version = "test.js - v3";
const timestamp = new Date().toISOString();
const path = window.location.pathname;
const host = window.location.host;

console.log(`[DEBUG] ${version} laddad ${timestamp}`);

const scriptList = [
  "impex.js",
  "export.js",
  "export_advanced.js",
  "geocoding.js",
  "impex_map.js", // justera filnamn här om du använder t.ex. impex_map_v8.js
];

const results = [];

async function checkFileVersion(file) {
  try {
    const res = await fetch(file + `?t=${Date.now()}`); // bypass cache
    if (!res.ok) throw new Error("Status " + res.status);
    const text = await res.text();
    const firstLine = text.split("\n")[0].trim();
    results.push(`<li style="color:green">✅ ${file} hittades &mdash; <code>${firstLine}</code></li>`);
  } catch (err) {
    results.push(`<li style="color:red">❌ ${file} kunde inte läsas (${err.message})</li>`);
  }
}

async function runDiagnostics() {
  await Promise.all(scriptList.map(checkFileVersion));

  const box = document.createElement("div");
  box.style = `
    padding: 1rem;
    margin: 2rem auto;
    max-width: 800px;
    font-family: monospace;
    background: #f9f9f9;
    border: 1px solid #ccc;
    border-radius: 6px;
    line-height: 1.5;
  `;

  box.innerHTML = `
    <h2>${version}</h2>
    <p><b>Tidpunkt:</b> ${timestamp}</p>
    <p><b>Host:</b> ${host}</p>
    <p><b>Sökväg:</b> ${path}</p>
    <p><b>User Agent:</b> ${navigator.userAgent}</p>
    <p><b>Referrer:</b> ${document.referrer || "–"}</p>
    <hr>
    <h3>JavaScript-versioner:</h3>
    <ul>${results.join("")}</ul>
    <hr>
    <p style="color: green;">✅ test.js laddades korrekt – ovan ser du exakt vad som fungerar.</p>
  `;

  document.body.innerHTML = "";
  document.body.appendChild(box);
}

document.addEventListener("DOMContentLoaded", runDiagnostics);
