// export.js - v6.1 – flytande meny, inga dubbletter
console.log("🧠 export.js - v6.1 laddad");

const exportMenu = document.createElement("div");
exportMenu.id = "exportMenu";
exportMenu.style.position = "absolute";
exportMenu.style.top = "10px";
exportMenu.style.right = "10px";
exportMenu.style.zIndex = "999";
exportMenu.style.background = "#f0f0f0";
exportMenu.style.border = "1px solid #ccc";
exportMenu.style.borderRadius = "6px";
exportMenu.style.padding = "4px";
exportMenu.style.display = "flex";
exportMenu.style.gap = "4px";
exportMenu.style.boxShadow = "0 2px 5px rgba(0,0,0,0.1)";
exportMenu.style.fontSize = "14px";

function createIconButton(symbol, title, onClick) {
  const btn = document.createElement("button");
  btn.textContent = symbol;
  btn.title = title;
  btn.style.border = "none";
  btn.style.background = "transparent";
  btn.style.cursor = "pointer";
  btn.style.padding = "2px 6px";
  btn.style.fontSize = "14px";
  btn.style.borderRadius = "4px";
  btn.addEventListener("click", onClick);
  return btn;
}

// 📄 JSON-popup
const btnJsonPopup = createIconButton("📄", "Exportera JSON", () => {
  const { nodes, edges } = window.flowData || {};
  if (!nodes || !edges) return;
  const json = JSON.stringify({ nodes, edges }, null, 2);
  const win = window.open("", "_blank", "width=600,height=500");
  win?.document.write(`<pre style="padding:1rem;font-family:monospace;">${json}</pre>`);
});

// 💾 Ladda ned JSON som text
const btnJsonDownload = createIconButton("💾", "Ladda ned JSON", () => {
  const { nodes, edges } = window.flowData || {};
  if (!nodes || !edges) return;
  const json = JSON.stringify({ nodes, edges }, null, 2);
  const blob = new Blob([json], { type: "text/plain" });
  const url = URL.createObjectURL(blob);
  const ts = new Date().toISOString().replace(/[:T]/g, "-").slice(0, 19);
  const a = document.createElement("a");
  a.href = url;
  a.download = `flow_${ts}.txt`;
  a.click();
  URL.revokeObjectURL(url);
});

// 🧾 Människovänlig textlista
const btnTextList = createIconButton("🧾", "Exportera som lista", () => {
  const { nodes, edges } = window.flowData || {};
  if (!nodes || !edges) return;
  const nodeList = nodes.map((n) => `- (${n.id}) ${n.data.label}`).join("\n");
  const edgeList = edges.map((e) => `- ${e.source} → ${e.target}`).join("\n");
  const textOutput = `Noder:\n${nodeList}\n\nKopplingar:\n${edgeList}`;
  const win = window.open("", "_blank", "width=500,height=400");
  win?.document.write(`<pre style="padding:1rem;font-family:monospace;">${textOutput}</pre>`);
});

// 🖼️ PNG
const btnPng = createIconButton("🖼️", "Exportera PNG", async () => {
  const exportArea = document.getElementById("root");
  exportMenu.style.visibility = "hidden";
  const canvas = await html2canvas(exportArea, { backgroundColor: "#fff" });
  exportMenu.style.visibility = "visible";
  const dataUrl = canvas.toDataURL("image/png");
  const ts = new Date().toISOString().replace(/[:T]/g, "-").slice(0, 19);
  const a = document.createElement("a");
  a.href = dataUrl;
  a.download = `flow_${ts}.png`;
  a.click();
});

// 📄 PDF
const btnPdf = createIconButton("📄", "Exportera PDF", async () => {
  const exportArea = document.getElementById("root");
  exportMenu.style.visibility = "hidden";
  const canvas = await html2canvas(exportArea, { backgroundColor: "#fff" });
  exportMenu.style.visibility = "visible";
  const imgData = canvas.toDataURL("image/png");

  const { jsPDF } = window.jspdf;
  const pdf = new jsPDF({ orientation: "landscape", unit: "px", format: "a4" });
  const pageWidth = pdf.internal.pageSize.getWidth();
  const pageHeight = pdf.internal.pageSize.getHeight();
  const scale = Math.min(pageWidth / canvas.width, pageHeight / canvas.height);
  const x = (pageWidth - canvas.width * scale) / 2;
  const y = (pageHeight - canvas.height * scale) / 2;
  pdf.addImage(imgData, "PNG", x, y, canvas.width * scale, canvas.height * scale);
  const ts = new Date().toISOString().replace(/[:T]/g, "-").slice(0, 19);
  pdf.save(`flow_${ts}.pdf`);
});

// Lägg till knappar
exportMenu.append(btnJsonPopup, btnJsonDownload, btnTextList, btnPng, btnPdf);
document.body.appendChild(exportMenu);


// Lägg till knappar UTAN lägg till nod (➕)
exportMenu.append(btnJsonPopup, btnJsonDownload, btnTextList, btnPng, btnPdf);
document.body.appendChild(exportMenu);
