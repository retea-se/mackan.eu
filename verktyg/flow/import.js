// import.js - v2
console.log("📥 import.js - v2 laddad");

function tryImport(jsonString) {
  try {
    const parsed = JSON.parse(jsonString);
    if (!parsed.nodes || !parsed.edges) {
      alert("❌ Ogiltigt format – saknar 'nodes' eller 'edges'");
      return;
    }

    console.log("📥 Importerat flöde:", parsed);
    window.flowData = parsed;

    if (typeof window.applyImportedFlow === "function") {
      window.applyImportedFlow(parsed.nodes, parsed.edges);
    } else {
      alert("✅ Import lyckades – ladda om sidan för att se resultatet.");
    }
  } catch (e) {
    alert("❌ Fel vid tolkning av JSON");
    console.error("Importfel:", e);
  }
}

function createImportButton() {
  const btn = document.createElement("button");
  btn.textContent = "📥";
  btn.title = "Importera flöde från fil eller JSON";
  btn.style.border = "none";
  btn.style.background = "transparent";
  btn.style.cursor = "pointer";
  btn.style.padding = "2px 6px";
  btn.style.fontSize = "14px";
  btn.style.borderRadius = "4px";

  btn.addEventListener("click", () => {
    const val = confirm("Vill du:\nOK = Ladda fil\nAvbryt = Klistra in JSON manuellt");
    if (val) {
      const input = document.createElement("input");
      input.type = "file";
      input.accept = ".json,.txt,application/json";
      input.onchange = () => {
        const file = input.files[0];
        const reader = new FileReader();
        reader.onload = () => tryImport(reader.result);
        reader.readAsText(file);
      };
      input.click();
    } else {
      const text = prompt("Klistra in JSON-innehåll:");
      if (text) tryImport(text);
    }
  });

  return btn;
}

// Lägg till i #exportMenu om den finns
const menu = document.getElementById("exportMenu");
if (menu) {
  const importBtn = createImportButton();
  menu.appendChild(importBtn);
} else {
  console.warn("⚠️ exportMenu saknas – importknapp kunde inte läggas till.");
}
