// theme.js - v1.1
console.log("🎨 theme.js - v1.1 laddad");

function setTheme(theme) {
  document.documentElement.setAttribute("data-theme", theme);
  localStorage.setItem("theme", theme);
  updateThemeIcon(theme);
}

function toggleTheme() {
  const current = document.documentElement.getAttribute("data-theme") || "light";
  const next = current === "light" ? "dark" : "light";
  setTheme(next);
}

function updateThemeIcon(theme) {
  const icon = document.getElementById("themeToggleIcon");
  if (icon) icon.textContent = theme === "dark" ? "☀️" : "🌙";
}

function createThemeToggleButton() {
  const btn = document.createElement("button");
  btn.title = "Byt tema";
  btn.style.border = "none";
  btn.style.background = "transparent";
  btn.style.cursor = "pointer";
  btn.style.padding = "2px 6px";
  btn.style.fontSize = "14px";
  btn.style.borderRadius = "4px";

  const icon = document.createElement("span");
  icon.id = "themeToggleIcon";
  icon.textContent = "🌙";
  btn.appendChild(icon);

  btn.addEventListener("click", toggleTheme);
  return btn;
}

// Lägg in i exportmeny om den finns
const themeBtn = createThemeToggleButton();
document.getElementById("exportMenu")?.appendChild(themeBtn);

// Initiera
const saved = localStorage.getItem("theme") || "light";
setTheme(saved);
