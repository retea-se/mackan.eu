// nodeactions.js - v1.1
console.log("🧩 nodeactions.js - v1.1 laddad");

// Knapp Lägg till nod
const addBtn = document.createElement("button");
addBtn.textContent = "➕";
addBtn.title = "Lägg till nod";
addBtn.style.border = "none";
addBtn.style.background = "transparent";
addBtn.style.cursor = "pointer";
addBtn.style.padding = "2px 6px";
addBtn.style.fontSize = "14px";
addBtn.style.borderRadius = "4px";

addBtn.addEventListener("click", () => {
  if (typeof window.addNode === "function") {
    window.addNode();
  } else {
    console.warn("⚠️ window.addNode är inte definierad");
  }
});

// Knapp Radera allt
const resetBtn = document.createElement("button");
resetBtn.textContent = "🗑️";
resetBtn.title = "Radera alla noder och kanter";
resetBtn.style.border = "none";
resetBtn.style.background = "transparent";
resetBtn.style.cursor = "pointer";
resetBtn.style.padding = "2px 6px";
resetBtn.style.fontSize = "14px";
resetBtn.style.borderRadius = "4px";

resetBtn.addEventListener("click", () => {
  if (!confirm("Är du säker på att du vill radera alla noder och kanter?")) return;

  if (typeof window.setNodes === "function" && typeof window.setEdges === "function") {
    window.setNodes([]);
    window.setEdges([]);
    console.log("🗑️ Alla noder och kanter raderade");
  } else {
    console.warn("⚠️ window.setNodes eller window.setEdges är inte definierade");
  }
});

// Lägg till knappar i exportmenyn
const exportMenuNodeActions = document.getElementById("exportMenu");
if (exportMenuNodeActions) {
  exportMenuNodeActions.appendChild(addBtn);
  exportMenuNodeActions.appendChild(resetBtn);
} else {
  console.warn("⚠️ exportMenu saknas – kunde inte lägga till nodeactions-knappar");
}
