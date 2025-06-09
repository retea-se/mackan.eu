// nodtyp.js - v1.3
console.log("🧱 nodtyp.js - v1.3 laddad");

const typmeny = document.createElement("div");
typmeny.id = "nodtypmeny";
typmeny.style.position = "absolute";
typmeny.style.zIndex = "9999";
typmeny.style.background = "var(--card-bg, #fff)";
typmeny.style.border = "1px solid var(--border-color, #ccc)";
typmeny.style.borderRadius = "6px";
typmeny.style.boxShadow = "0 2px 10px rgba(0,0,0,0.15)";
typmeny.style.fontSize = "14px";
typmeny.style.display = "none";
typmeny.style.minWidth = "120px";

["start", "steg", "slut"].forEach(type => {
  const btn = document.createElement("button");
  btn.innerHTML = `🧱 <span style="opacity:0.9">${type}</span>`;
  btn.style.display = "flex";
  btn.style.alignItems = "center";
  btn.style.gap = "0.5rem";
  btn.style.width = "100%";
  btn.style.border = "none";
  btn.style.background = "transparent";
  btn.style.padding = "6px 12px";
  btn.style.cursor = "pointer";
  btn.style.textAlign = "left";
  btn.style.color = "var(--text-color, #000)";

  btn.onmouseenter = () => btn.style.background = "rgba(255, 255, 255, 0.1)";
  btn.onmouseleave = () => btn.style.background = "transparent";

  btn.onclick = () => {
    if (window.__activeNodeId && typeof window.setNodes === "function") {
      window.setNodes((nds) =>
        nds.map((n) =>
          n.id === window.__activeNodeId
            ? { ...n, type: type, className: "nod-" + type }
            : n
        )
      );
      console.log(`🧱 Typ för nod ${window.__activeNodeId} ändrad till "${type}"`);
    }
    typmeny.style.display = "none";
  };

  typmeny.appendChild(btn);
});

document.body.appendChild(typmeny);

// Visa meny vid högerklick på nod
window.addEventListener("contextmenu", (e) => {
  const nodEl = e.target.closest(".react-flow__node");
  if (!nodEl) return;

  e.preventDefault();

  const nodId = nodEl.dataset.id;
  if (!nodId) return;

  window.__activeNodeId = nodId;

  typmeny.style.left = `${e.pageX}px`;
  typmeny.style.top = `${e.pageY}px`;
  typmeny.style.display = "block";
});

// Stäng meny om man klickar utanför
window.addEventListener("click", () => {
  typmeny.style.display = "none";
});
