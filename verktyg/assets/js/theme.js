// theme.js - v1

console.log("theme.js - v1 laddad");

const toggleBtn = document.getElementById("themeToggle");
const icon = toggleBtn?.querySelector("i");
const root = document.documentElement;

function setTheme(theme) {
  root.setAttribute("data-theme", theme);
  localStorage.setItem("theme", theme);
  if (icon) icon.className = theme === "dark" ? "fa-solid fa-sun" : "fa-solid fa-moon";
  console.log("🌗 Tema satt till:", theme);
}

if (toggleBtn) {
  toggleBtn.addEventListener("click", () => {
    const current = root.getAttribute("data-theme");
    const next = current === "light" ? "dark" : "light";
    setTheme(next);
  });
}

setTheme(localStorage.getItem("theme") || "light");
