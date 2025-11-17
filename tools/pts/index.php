<!-- tools/pts/index.php - v5 -->
<?php
$title = 'PTS Diarium – Ärendehämtare';
$metaDescription = 'Sök, filtrera och exportera ärenden från PTS diarium. Klicka för att visa handlingar eller generera ordmoln.';
$keywords = 'PTS, diarium, ärenden, PTS diarium, ärendehämtare, Post- och Telestyrelsen';
$canonical = 'https://mackan.eu/tools/pts/';

// Strukturerad data för sökmotorer
$extraHead = '
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebApplication",
  "name": "PTS Diarium – Ärendehämtare",
  "description": "' . htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8') . '",
  "url": "' . htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8') . '",
  "applicationCategory": "UtilityApplication",
  "operatingSystem": "Web Browser",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "SEK"
  },
  "featureList": [
    "Sök ärenden",
    "Filtrera ärenden",
    "Exportera ärenden",
    "Generera ordmoln"
  ],
  "author": {
    "@type": "Organization",
    "name": "Mackan.eu"
  }
}
</script>';

include '../../includes/tool-layout-start.php';
?>

<main class="layout__container">

  <header class="layout__sektion text--center">
    <h1 class="rubrik rubrik--sektion">
      <?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>
    </h1>
    <p class="text--lead">
      Ange datumintervall och filtrera rubriker för att hämta ärenden från PTS diarium. Exportera resultatet eller analysera rubrikerna i ett ordmoln.
    </p>
  </header>

  <section class="layout__sektion">
    <form class="form" id="dateForm" novalidate>
      <div class="form__grupp">
        <label for="startDate" class="falt__etikett">Startdatum</label>
        <input type="date" id="startDate" class="falt__input">
      </div>

      <div class="form__grupp">
        <label for="endDate" class="falt__etikett">Slutdatum</label>
        <input type="date" id="endDate" class="falt__input">
      </div>

      <div class="form__grupp">
        <label for="search" class="falt__etikett">Filtrera rubriker</label>
        <input type="text" id="search" class="falt__input" placeholder="Sök...">
      </div>

      <div class="form__verktyg">
        <button type="submit" class="knapp" data-tippy-content="Hämta ärenden inom spannet">Kör</button>
        <button type="button" class="knapp" id="exportJson" disabled>Exportera JSON</button>
        <button type="button" class="knapp" id="exportCsv" disabled>Exportera CSV</button>
        <button type="button" class="knapp" id="showWordcloud" disabled>Ordmoln</button>
      </div>
    </form>
  </section>

  <section class="layout__sektion">
    <div class="tabell__wrapper">
      <table class="tabell tabell--kompakt" id="resultTable">
        <thead>
          <tr>
            <th>ID</th>
            <th>Diarienummer</th>
            <th>Datum</th>
            <th>Status</th>
            <th>Rubrik</th>
          </tr>
        </thead>
        <tbody>
          <tr><td colspan="5">⏳ Väntar på sökning...</td></tr>
        </tbody>
      </table>
    </div>
  </section>

  <div id="wordcloudModal" class="modal hidden" role="dialog" aria-modal="true" aria-labelledby="wordcloudTitle">
    <div class="modal__dialog">
      <button id="closeWordcloud" class="modal__close" aria-label="Stäng ordmoln">&times;</button>
      <h2 class="modal__title" id="wordcloudTitle">Ordmoln över rubriker</h2>
      <canvas id="wordcloudCanvas" width="600" height="400" aria-describedby="wordlist"></canvas>
      <div id="wordlist" class="modal__body"></div>
    </div>
  </div>

</main>

<?php include '../../includes/tool-layout-end.php'; ?>
<script src="script.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/wordcloud2.js/1.1.2/wordcloud2.min.js" defer></script>
<script type="module">
  import { initWordcloud } from './cloud.js';
  const btn = document.getElementById('showWordcloud');
  btn.addEventListener('click', () => initWordcloud(window.ärenden || []));
</script>
