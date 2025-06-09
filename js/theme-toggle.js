// theme-toggle.js - v3
console.log("🌓 theme-toggle.js laddad");

document.addEventListener('DOMContentLoaded', () => {
  const toggleBtn = document.getElementById('themeToggle');
  const icon = toggleBtn?.querySelector('i');
  const root = document.documentElement;

  // Initiera tema
  const savedTheme = localStorage.getItem('theme') || 'light';
  root.setAttribute('data-theme', savedTheme);
  updateIcon(savedTheme);

  // Byt tema vid klick
  toggleBtn?.addEventListener('click', () => {
    const current = root.getAttribute('data-theme');
    const next = current === 'light' ? 'dark' : 'light';
    root.setAttribute('data-theme', next);
    localStorage.setItem('theme', next);
    updateIcon(next);
    console.log(`🌗 Tema satt till: ${next}`);
  });


  function updateIcon(theme) {
    if (!icon) return;
    icon.className = theme === 'dark' ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
  }
});
