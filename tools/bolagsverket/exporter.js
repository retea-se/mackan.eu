// exporter.js - v1

document.getElementById('exportBtn').addEventListener('click', () => {
  const raw = window._lastExport || 'Ingen data tillgänglig.';
  const popup = document.createElement('div');
  popup.innerHTML = `
    <div class="modal-backdrop">
      <div class="modal">
        <h2>Exporterad text</h2>
        <textarea readonly>${raw}</textarea>
        <button class="button secondary" id="closePopup">Stäng</button>
      </div>
    </div>
  `;
  document.body.appendChild(popup);
  document.getElementById('closePopup').onclick = () => popup.remove();
});
