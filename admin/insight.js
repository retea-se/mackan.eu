// insight.js - v4.1

document.addEventListener("DOMContentLoaded", () => {
  console.log("🔍 insight.js v4.1 laddad");

  const filter = document.getElementById("timeFilter");
  if (filter) {
    filter.addEventListener("change", loadAndRenderData);
  }

  loadAndRenderData();
});

function getTimeLimit(value) {
  const now = new Date();
  switch (value) {
    case "1h": return new Date(now.getTime() - 60 * 60 * 1000);
    case "24h": return new Date(now.getTime() - 24 * 60 * 60 * 1000);
    case "7d": return new Date(now.getTime() - 7 * 24 * 60 * 60 * 1000);
    default: return null;
  }
}

async function loadAndRenderData() {
  console.clear();
  console.log("📥 Laddar besöksdata...");

  try {
    const response = await fetch("visits-export.php");
    const rows = await response.json();

    console.log(`📥 Laddade rader: ${rows.length}`);

    const timeLimit = getTimeLimit(document.getElementById("timeFilter")?.value);
    const filtered = timeLimit
      ? rows.filter((row) => new Date(row.Tid) >= timeLimit)
      : rows;

    console.log(`🔎 Filtrerade rader: ${filtered.length}`);

    renderStats(filtered);
    renderAccordion(filtered);
    renderTable(filtered);

    if (typeof initExportButtons === "function") {
      initExportButtons(filtered);
    }

  } catch (err) {
    console.error("❌ Fel vid hämtning/parsing av data:", err);
  }
}

function renderStats(data) {
  const statBox = document.getElementById("statSummary");
  if (!statBox) return;

  const unikaIP = [...new Set(data.map((r) => r.IP))].length;
  const människa = data.filter((r) => r.Typ?.includes("Människa")).length;
  const bot = data.length - människa;

  statBox.innerHTML = `
    <p><strong>Totalt:</strong> ${data.length} rader</p>
    <p><strong>Unika IP:</strong> ${unikaIP}</p>
    <p><strong>👤 Mänskliga:</strong> ${människa} | 🤖 Botar: ${bot}</p>
  `;
}

function renderAccordion(data) {
  const container = document.getElementById("visitorAccordion");
  if (!container) return;
  container.innerHTML = "";

  const grupper = {};
  data.forEach((rad) => {
    const id = `${rad.IP}_${getSessionId(rad.Cookies)}`;
    if (!grupper[id]) grupper[id] = [];
    grupper[id].push(rad);
  });

  console.log(`📦 Grupperade sessioner: ${Object.keys(grupper).length}`);

  Object.entries(grupper).forEach(([key, sessionRows]) => {
    const title = `${sessionRows[0].IP} – ${sessionRows[0].Enhet} – ${sessionRows[0].Typ}`;
    const item = document.createElement("div");
    item.classList.add("card");

    item.innerHTML = `
      <details>
        <summary><strong>${title}</strong> – ${sessionRows.length} händelser</summary>
        <ul class="list">
          ${sessionRows.map((r) => `
            <li>
              <code>${r.Tid}</code> – <em>${r.Sida}</em> – Klick: ${r.Klick || "–"} – Tid: ${r["Tid på sida"] || "?"}
            </li>`).join("")}
        </ul>
      </details>
    `;
    container.appendChild(item);
  });
}

function renderTable(data) {
  const container = document.getElementById("fullTableSection");
  if (!container) {
    console.warn("⚠️ Hittar inte #fullTableSection – tabell renderas ej");
    return;
  }

  container.innerHTML = ""; // töm tidigare

  const table = document.createElement("table");
  table.id = "fullDataTable";
  table.className = "mt-1";

  const headers = [
    "ID", "Tid", "IP", "User Agent", "Sida", "Referer", "Språk",
    "GET", "POST", "Cookies", "Klick", "Tid på sida", "Skärm", "Fel",
    "Enhet", "Tidszon", "Typ"
  ];

  table.innerHTML = `
    <thead>
      <tr>${headers.map(h => `<th>${h}</th>`).join("")}</tr>
    </thead>
    <tbody>
      ${data.map(r => `
        <tr>
          ${headers.map(h => `<td>${(r[h] || "").toString()}</td>`).join("")}
        </tr>
      `).join("")}
    </tbody>
  `;

  container.appendChild(table);

  if (typeof makeTableSortable === "function") {
    makeTableSortable(table);
  }

  console.log(`📊 Tabell renderad med ${data.length} rader`);
}

function getSessionId(cookieStr) {
  try {
    const parsed = typeof cookieStr === "string" ? JSON.parse(cookieStr) : cookieStr;
    return parsed?.PHPSESSID || "okänd";
  } catch (e) {
    return "okänd";
  }
}
