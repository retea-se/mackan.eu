// getdata.js - v18

document.getElementById('dataForm')?.addEventListener('submit', async (e) => {
  e.preventDefault();

  const orgInput = document.getElementById('orgnr');
  const rawOrgnr = orgInput?.value.trim() || '';
  const orgnr = rawOrgnr.replace(/[^0-9]/g, ''); // Tillåter både 556475-6467 och 5564756467

  const table = document.getElementById('orgTable');
  const tbody = table?.querySelector('tbody');
  const section = document.getElementById('tableSection');
  const exportBtn = document.getElementById('exportBtn');
  const spinner = document.getElementById('loadingSpinner');

  if (!tbody || !section || !/^\d{10}$/.test(orgnr)) return;

  tbody.innerHTML = '';
  section.classList.add('hidden');
  exportBtn?.classList.add('hidden');
  spinner?.classList.remove('hidden');

  try {
    const res = await fetch(`get_data.php?orgnr=${orgnr}`);
    const data = await res.json();
    const org = data?.organisationer?.[0];
    if (!org) throw new Error('Ingen data');

    const flat = flattenObject(org);
    const rows = [];
    const exportLines = [];

    Object.entries(flat).forEach(([key, val]) => {
      if (val === null || val === '') return;
      const label = LABEL_MAP[key] || beautifyKey(key);
      const safe = String(val).replace(/</g, '&lt;').replace(/>/g, '&gt;');
      rows.push(`<tr><th>${label}</th><td>${safe}</td></tr>`);
      exportLines.push(`${label}: ${val}`);
    });

    tbody.innerHTML = rows.join('');
    section.classList.remove('hidden');
    exportBtn?.classList.remove('hidden');

    exportBtn.onclick = () => {
      const popup = window.open('', '_blank', 'width=600,height=600,scrollbars=yes');
      popup?.document.write(`<pre>${exportLines.join('\n')}</pre>`);
      popup?.document.close();
    };

  } catch (err) {
    console.error('Fel vid hämtning:', err);
    tbody.innerHTML = '<tr><td colspan="2">⚠️ Kunde inte hämta data</td></tr>';
    section.classList.remove('hidden');
    exportBtn?.classList.add('hidden');
  } finally {
    spinner?.classList.add('hidden');
  }
});

function flattenObject(obj, prefix = '', res = {}) {
  for (const [key, val] of Object.entries(obj)) {
    const path = prefix ? `${prefix}.${key}` : key;
    if (typeof val === 'object' && val !== null && !Array.isArray(val)) {
      flattenObject(val, path, res);
    } else if (Array.isArray(val)) {
      val.forEach((v, i) => flattenObject(v, `${path}[${i}]`, res));
    } else {
      res[path] = val;
    }
  }
  return res;
}

function beautifyKey(key) {
  return key
    .split('.')
    .pop()
    .replace(/\[\d+\]/, '')
    .replace(/([a-z])([A-Z])/g, '$1 $2')
    .replace(/_/g, ' ')
    .replace(/\b\w/g, c => c.toUpperCase());
}

// ********** START Etiketter **********
const LABEL_MAP = {
  'organisationsidentitet.identitetsbeteckning': 'Organisationsnummer',
  'organisationsidentitet.typ.klartext': 'Identitetstyp',
  'organisationsnamn.organisationsnamnLista[0].namn': 'Företagsnamn',
  'organisationsnamn.organisationsnamnLista[0].organisationsnamntyp.klartext': 'Namn-typ',
  'organisationsnamn.organisationsnamnLista[0].registreringsdatum': 'Namnregistrering',
  'juridiskForm.klartext': 'Juridisk form',
  'juridiskForm.kod': 'Juridisk formkod',
  'organisationsform.klartext': 'Organisationsform',
  'organisationsform.kod': 'Organisationsformkod',
  'verksamhetsbeskrivning.beskrivning': 'Verksamhetsbeskrivning',
  'verksamOrganisation.kod': 'Verksam',
  'organisationsdatum.registreringsdatum': 'Registreringsdatum',
  'registreringsland.klartext': 'Registreringsland',
  'registreringsland.kod': 'Registreringslandkod',
  'postadressOrganisation.postadress.utdelningsadress': 'Adress',
  'postadressOrganisation.postadress.postnummer': 'Postnummer',
  'postadressOrganisation.postadress.postort': 'Postort',
  'postadressOrganisation.postadress.coAdress': 'c/o-adress',
  'naringsgrenOrganisation.sni[0].kod': 'SNI-kod',
  'naringsgrenOrganisation.sni[0].klartext': 'SNI-text',
  'naringsgrenOrganisation.sni[1].kod': 'SNI-kod 2',
  'naringsgrenOrganisation.sni[1].klartext': 'SNI-text 2',
  'naringsgrenOrganisation.sni[2].kod': 'SNI-kod 3',
  'naringsgrenOrganisation.sni[2].klartext': 'SNI-text 3',
  'naringsgrenOrganisation.sni[3].kod': 'SNI-kod 4',
  'naringsgrenOrganisation.sni[3].klartext': 'SNI-text 4',
  'naringsgrenOrganisation.sni[4].kod': 'SNI-kod 5',
  'naringsgrenOrganisation.sni[4].klartext': 'SNI-text 5',
  'avregistreradOrganisation.avregistreringsdatum': 'Avregistrerad',
  'avregistreringsorsak.klartext': 'Avregistreringsorsak'
};
// ********** SLUT Etiketter **********
