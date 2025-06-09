// stats.js - v1.1
console.log("📊 stats.js - v1.1 laddad");

function genereraStatistikText() {
  const { nodes = [], edges = [] } = window.flowData || {};

  const nodTyper = {};
  nodes.forEach(n => {
    const typ = n.type || "default";
    nodTyper[typ] = (nodTyper[typ] || 0) + 1;
  });

  const etiketter = nodes.filter(n => n.data?.label?.trim()).length;

  const användaIds = new Set();
  edges.forEach(e => {
    användaIds.add(e.source);
    användaIds.add(e.target);
  });
  const oanvända = nodes.filter(n => !användaIds.has(n.id)).length;

  return `
📊 FLÖDESSTATISTIK
======================
📦 Antal noder: ${nodes.length}
🔗 Antal kopplingar: ${edges.length}
📝 Etiketter med text: ${etiketter}
🕳️ Oanvända noder: ${oanvända}

🧱 Noder per typ:
${Object.entries(nodTyper).map(([typ, antal]) => ` - ${typ}: ${antal}`).join("\n")}
`;
}

function visaStatistik() {
  const statsText = genereraStatistikText();

  const panel = document.createElement("div");
  panel.innerHTML = `
    <div id="statsPopup" style="
      position: fixed;
      top: 20%;
      left: 50%;
      transform: translateX(-50%);
      background: var(--card-bg, #fff);
      color: var(--text-color, #000);
      padding: 1rem;
      border-radius: 8px;
      border: 1px solid var(--border-color, #ccc);
      z-index: 9999;
      box-shadow: 0 0 20px rgba(0,0,0,0.2);
      max-width: 320px;
    ">
      <button id="closeStats" style="
        position: absolute;
        top: 4px;
        right: 6px;
        background: transparent;
        border: none;
        font-size: 18px;
        cursor: pointer;
        color: var(--text-color, #000);
      ">&times;</button>
      <h3 style="margin-top:0">📊 Statistik</h3>
      <pre style="font-size: 0.85rem; white-space: pre-wrap;">${statsText}</pre>
      <button id="exportStats" style="
        margin-top: 0.5rem;
        padding: 4px 8px;
        font-size: 0.85rem;
        cursor: pointer;
      ">💾 Exportera till .txt</button>
    </div>
  `;
  document.body.appendChild(panel);

  document.getElementById("closeStats").onclick = () => {
    panel.remove();
  };

  document.getElementById("exportStats").onclick = () => {
    const blob = new Blob([statsText], { type: "text/plain" });
    const url = URL.createObjectURL(blob);
    const länk = document.createElement("a");
    länk.href = url;
    const datum = new Date().toISOString().slice(0, 19).replace(/[:T]/g, "-");
    länk.download = `flodestatistik-${datum}.txt`;
    länk.click();
    URL.revokeObjectURL(url);
  };
}

// Lägg till knapp i exportmeny
const statsBtn = document.createElement("button");
statsBtn.textContent = "📊";
statsBtn.title = "Visa statistik";
statsBtn.style.border = "none";
statsBtn.style.background = "transparent";
statsBtn.style.cursor = "pointer";
statsBtn.style.padding = "2px 6px";
statsBtn.style.fontSize = "14px";
statsBtn.style.borderRadius = "4px";

statsBtn.addEventListener("click", visaStatistik);
document.getElementById("exportMenu")?.appendChild(statsBtn);
