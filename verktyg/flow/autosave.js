// autosave.js - v1.2
console.log("💾 autosave.js - v1.2 laddad");

const STORAGE_KEY = "flow-autosave";

// Ladda sparad data vid start
const savedData = localStorage.getItem(STORAGE_KEY);
if (savedData) {
  try {
    const parsed = JSON.parse(savedData);
    if (parsed.nodes && parsed.edges) {
      console.log("💾 Laddar autosparat flöde:", parsed);
      if (typeof window.applyImportedFlow === "function") {
        window.applyImportedFlow(parsed.nodes, parsed.edges);
      }
    }
  } catch (e) {
    console.warn("⚠️ Kunde inte tolka autosparad data:", e);
  }
}

// Spara data varje sekund om något ändrats
let lastData = "";
setInterval(() => {
  if (!window.flowData) return;

  const data = JSON.stringify(window.flowData);
  if (data !== lastData) {
    localStorage.setItem(STORAGE_KEY, data);
    console.log("💾 Autosparat", new Date().toLocaleTimeString());
    lastData = data;
  }
}, 1000);

// Rensningsfunktion med bekräftelse
function rensaAutosparning() {
  const ok = confirm("Är du säker på att du vill radera autosparad data?");
  if (!ok) return;
  localStorage.removeItem(STORAGE_KEY);
  console.log("🧹 Autosparning rensad");
  alert("Autosparad data har raderats.");
}

// Lägg till rensningsknapp i exportmeny
const clearBtn = document.createElement("button");
clearBtn.textContent = "🧹";
clearBtn.title = "Rensa autosparning";
clearBtn.style.border = "none";
clearBtn.style.background = "transparent";
clearBtn.style.cursor = "pointer";
clearBtn.style.padding = "2px 6px";
clearBtn.style.fontSize = "14px";
clearBtn.style.borderRadius = "4px";

clearBtn.addEventListener("click", rensaAutosparning);
document.getElementById("exportMenu")?.appendChild(clearBtn);
