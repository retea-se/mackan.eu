// cloud.js - v6

export function initWordcloud(ärenden) {
  const canvas = document.getElementById('wordcloudCanvas');
  const modal = document.getElementById('wordcloudModal');
  const closeBtn = document.getElementById('closeWordcloud');
  const listContainer = document.getElementById('wordlist');

  const ordmap = {};

  ärenden.forEach(e => {
    const text = e.caseTitle
      .normalize('NFKD')
      .replace(/[–—.,:;!?()"'`]/g, '')
      .toLowerCase();

    text.split(/\s+/).forEach(word => {
      if (word.length >= 3) ordmap[word] = (ordmap[word] || 0) + 1;
    });
  });

  const stoppord = ['för', 'och', 'att', 'med', 'den', 'det', 'från', 'till', 'som', 'utan', 'enligt', 'gällande'];
  const lista = Object.entries(ordmap)
    .filter(([word]) => !stoppord.includes(word))
    .sort((a, b) => b[1] - a[1])
    .slice(0, 100);

  console.log('[cloud.js] 🧪 Debug:', {
    totalRubriker: ärenden.length,
    ordmap,
    lista
  });

  if (lista.length === 0) {
    console.warn('[cloud.js] ⚠️ Inga ord kunde användas till ordmoln.');
    alert('Inga användbara ord hittades i rubrikerna.');
    return;
  }

  try {
    WordCloud(canvas, {
      list: lista,
      gridSize: 10,
      weightFactor: 4,
      fontFamily: 'Poppins, sans-serif',
      color: 'random-dark',
      backgroundColor: '#fff',
      rotateRatio: 0.1,
      drawOutOfBound: false,
      shuffle: true
    });

    listContainer.innerHTML = `
      <h3 class="rubrik rubrik--underrubrik text--center">Vanligaste orden i rubriker</h3>
      <div class="tabell__wrapper">
        <table class="tabell tabell--kompakt">
          <thead>
            <tr><th>Ord</th><th>Frekvens</th></tr>
          </thead>
          <tbody>
            ${lista.slice(0, 10).map(([word, freq]) =>
              `<tr><td>${word}</td><td>${freq}</td></tr>`
            ).join('')}
          </tbody>
        </table>
      </div>
    `;

    modal.classList.remove('hidden');
    document.body.classList.add('modal-open');
    console.log(`[cloud.js] ☁️ Ordmoln + tabell genererad (${lista.length} ord)`);
  } catch (err) {
    console.error('[cloud.js] ❌ Wordcloud-krasch:', err);
    listContainer.innerHTML = `<p class="text--muted">⚠️ Kunde inte generera ordmoln (minnesfel eller för många ord).</p>`;
    modal.classList.remove('hidden');
    document.body.classList.add('modal-open');
  }

  closeBtn.addEventListener('click', () => {
    modal.classList.add('hidden');
    document.body.classList.remove('modal-open');
  });
}
