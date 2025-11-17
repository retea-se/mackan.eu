/**
 * Theme Toggle - Växla mellan ljust och mörkt tema
 * Sparar valet i localStorage
 */

function toggleTheme() {
  const html = document.documentElement;
  const currentTheme = html.getAttribute('data-theme');
  const newTheme = currentTheme === 'light' ? 'dark' : 'light';

  html.setAttribute('data-theme', newTheme);

  const themeText = document.getElementById('theme-text');
  const themeIcon = document.querySelector('.landing-theme-toggle i');

  if (newTheme === 'dark') {
    if (themeText) themeText.textContent = 'Ljust';
    if (themeIcon) themeIcon.className = 'fas fa-sun';
  } else {
    if (themeText) themeText.textContent = 'Mörkt';
    if (themeIcon) themeIcon.className = 'fas fa-moon';
  }

  // Spara i localStorage
  try {
    localStorage.setItem('theme', newTheme);
  } catch (e) {
    console.warn('localStorage inte tillgängligt:', e);
  }
}

// Ladda sparat tema vid sidladdning
(function loadSavedTheme() {
  let savedTheme = 'light';

  try {
    savedTheme = localStorage.getItem('theme') || 'light';
  } catch (e) {
    console.warn('localStorage inte tillgängligt:', e);
  }

  document.documentElement.setAttribute('data-theme', savedTheme);

  // Uppdatera knapptext och ikon
  const themeText = document.getElementById('theme-text');
  const themeIcon = document.querySelector('.landing-theme-toggle i');

  if (savedTheme === 'dark') {
    if (themeText) themeText.textContent = 'Ljust';
    if (themeIcon) themeIcon.className = 'fas fa-sun';
  } else {
    if (themeText) themeText.textContent = 'Mörkt';
    if (themeIcon) themeIcon.className = 'fas fa-moon';
  }
})();
