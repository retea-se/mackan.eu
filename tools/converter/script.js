// tools/converter/script.js - v5

/* ********** START: Tabbväxling ********** */
document.addEventListener('DOMContentLoaded', () => {
  console.log("script.js v5 laddad");

  const buttons = document.querySelectorAll('.knapp[data-tab]');
  const sections = document.querySelectorAll('.tab-section');
  let uploadModuleLoaded = false;

  buttons.forEach(button => {
    button.addEventListener('click', () => {
      const targetId = `tab-${button.dataset.tab}`;

      sections.forEach(section => {
        section.classList.toggle('hidden', section.id !== targetId);
      });

      // Om fliken är 'converter', ladda upload.js först efter visning med fördröjning
      if (button.dataset.tab === 'converter') {
        if (!uploadModuleLoaded) {
          setTimeout(() => {
            import('./upload.js').then(m => m.init?.());
            uploadModuleLoaded = true;
          }, 150);
        }
      }

      // Ladda övriga moduler
      loadModule(button.dataset.tab);
    });
  });

  // Ladda initial tabb
  loadModule('csvtojson');
});
/* ********** SLUT: Tabbväxling ********** */

/* ********** START: Moduluppladdning ********** */
function loadModule(tab) {
  console.log("Laddar modul för:", tab);

  switch (tab) {
    case 'csvtojson':
      import('./csvtojson.js').then(m => m.init?.());
      break;
    case 'formatter':
      import('./formatter.js').then(m => m.init?.());
      break;
    case 'validator':
      import('./validator.js').then(m => m.init?.());
      break;
    case 'fixer':
      import('./fixer.js').then(m => m.init?.());
      break;
    case 'utilities':
      import('./utilities.js').then(m => m.init?.());
      break;
    case 'converter':
      import('./converter.js').then(m => m.init?.());
      break;
    default:
      console.warn("Okänd modul:", tab);
  }
}
/* ********** SLUT: Moduluppladdning ********** */
