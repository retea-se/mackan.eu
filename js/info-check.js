// info-check.js - v20250601
// Dölj infolänk (ⓘ) om readme.php inte finns
document.addEventListener('DOMContentLoaded', () => {
  const infoLink = document.querySelector('.info-link-floating');
  if (infoLink) {
    fetch('readme.php', { method: 'HEAD' })
      .then(res => {
        if (!res.ok) infoLink.classList.add('hidden');
      })
      .catch(() => infoLink.classList.add('hidden'));
  }
});
