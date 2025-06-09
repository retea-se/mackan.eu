// tools/bolagsverket/arsredovisningar.js - v1

export async function hamtaArsredovisningar(orgnr, container) {
  const table = document.createElement('table');
  table.className = 'table compact-table';
  const tbody = document.createElement('tbody');

  const res = await fetch(`get_dokumentlista.php?orgnr=${encodeURIComponent(orgnr)}`);
  const data = await res.json();

  if (!res.ok || !data.dokument?.length) {
    tbody.innerHTML = `<tr><td colspan="4">❌ Inga dokument hittades</td></tr>`;
    table.appendChild(tbody);
    container.innerHTML = '';
    container.appendChild(table);
    container.classList.remove('hidden');
    return;
  }

  // Rubrik
  const head = `<tr class="section-header"><th colspan="4">📄 Årsredovisningar</th></tr>`;
  tbody.insertAdjacentHTML('beforeend', head);

  data.dokument.forEach(doc => {
    const rader = `
      <tr>
        <td>${doc.rapporteringsperiodTom}</td>
        <td>${doc.registreringstidpunkt}</td>
        <td>${doc.filformat}</td>
        <td><a href="get_dokument.php?dokumentId=${doc.dokumentId}" class="button small" target="_blank">Ladda ner</a></td>
      </tr>
    `;
    tbody.insertAdjacentHTML('beforeend', rader);
  });

  table.appendChild(tbody);
  container.innerHTML = '';
  container.appendChild(table);
  container.classList.remove('hidden');
}
