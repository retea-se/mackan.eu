/**
 * View Toggle - Växla mellan rutnät och lista per kategori
 */

function setView(button, view) {
  const parent = button.closest('.landing-category__header');
  const grid = parent.nextElementSibling;
  const buttons = parent.querySelectorAll('.landing-view-toggle__button');

  // Ta bort active från alla knappar
  buttons.forEach(btn => btn.classList.remove('landing-view-toggle__button--active'));

  // Lägg till active på klickad knapp
  button.classList.add('landing-view-toggle__button--active');

  // Växla vy
  if (view === 'list') {
    grid.classList.add('landing-tools--list');
  } else {
    grid.classList.remove('landing-tools--list');
  }
}
