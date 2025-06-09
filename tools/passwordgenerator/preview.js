// preview.js - v8
// git commit: Förhandslösenord visas direkt vid sidladdning med FA-ikoner

console.log("🧪 preview.js v8 laddad");

document.addEventListener("DOMContentLoaded", () => {
  const previewEl = document.getElementById("previewText");
  const knapp = document.getElementById("previewRefresh");
  const kopiera = document.getElementById("previewCopy");

  function genereraPreview() {
    if (!previewEl) return console.warn("⚠️ previewText saknas i DOM");

    if (typeof window.genereraLösenord === "function") {
      const defaultSettings = {
        lower: true,
        upper: true,
        numbers: true,
        symbols: true,
      };
      const lösenord = window.genereraLösenord(20, defaultSettings);
      previewEl.textContent = lösenord;
      console.log("🔁 Nytt förhandslösenord:", lösenord);
    } else {
      previewEl.textContent = "[funktion saknas]";
    }
  }

  if (knapp) {
    knapp.innerHTML = '<i class="fa-solid fa-arrows-rotate"></i>';
    knapp.setAttribute("data-tippy-content", "Generera nytt säkert lösenord");
    knapp.addEventListener("click", genereraPreview);
  }

  if (kopiera) {
    kopiera.innerHTML = '<i class="fa-solid fa-copy"></i>';
    kopiera.setAttribute("data-tippy-content", "Kopiera förhandslösenordet");
    kopiera.addEventListener("click", () => {
      const text = previewEl?.textContent;
      if (text) navigator.clipboard.writeText(text);
    });
  }

  genereraPreview();
});
