// readme-codecopy.js
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.readme__codecopy').forEach(btn => {
    btn.addEventListener('click', e => {
      const pre = btn.nextElementSibling;
      if (pre && pre.tagName === 'PRE') {
        const code = pre.innerText;
        navigator.clipboard.writeText(code).then(() => {
          btn.title = 'Kopierat!';
          setTimeout(() => btn.title = 'Kopiera kod', 1200);
        });
      }
    });
  });
});
