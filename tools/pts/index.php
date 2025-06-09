<!-- tools/pts/index.php - v4 -->
<?php
$title = 'PTS Diarium – Ärendehämtare';
$metaDescription = 'Sök, filtrera och exportera ärenden från PTS diarium. Klicka för att visa handlingar eller generera ordmoln.';
?>
<?php include '../../includes/layout-start.php'; ?>

<main class="container">


  <!-- ********** START Sektion: Formulär ********** -->
  <form class="form-group" id="dateForm">
    <div class="form-group">
      <label for="startDate">Startdatum</label>
      <input type="date" id="startDate" class="input">
    </div>

    <div class="form-group">
      <label for="endDate">Slutdatum</label>
      <input type="date" id="endDate" class="input">
    </div>

    <div class="form-group">
      <label for="search">Filtrera rubriker</label>
      <input type="text" id="search" class="input" placeholder="Sök...">
    </div>

    <div class="horizontal-tools">
      <button type="submit" class="button">Kör</button>
      <button type="button" class="button" id="exportJson" disabled>Exportera JSON</button>
      <button type="button" class="button" id="exportCsv" disabled>Exportera CSV</button>
      <button type="button" class="button" id="showWordcloud" disabled>Ordmoln</button>
    </div>
  </form>
  <!-- ********** SLUT Sektion: Formulär ********** -->

  <!-- ********** START Sektion: Resultat ********** -->
  <div class="table-wrapper">
    <table class="table compact-table" id="resultTable">
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
  <!-- ********** SLUT Sektion: Resultat ********** -->

  <!-- ********** START Sektion: Ordmolnsmodal ********** -->
  <div id="wordcloudModal" class="hidden" style="position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.7); display: flex; justify-content: center; align-items: center; z-index: 9999;">
    <div style="background: #fff; padding: 2rem; border-radius: 8px; max-width: 90vw; max-height: 90vh; overflow: auto; position: relative;">
      <button id="closeWordcloud" style="position:absolute; top:8px; right:8px; font-size:1.2rem;">✖</button>
      <canvas id="wordcloudCanvas" width="600" height="400"></canvas>
      <div id="wordlist" style="margin-top:2rem;"></div>

    </div>
  </div>
  <!-- ********** SLUT Sektion: Ordmolnsmodal ********** -->

</main>

<?php include '../../includes/layout-end.php'; ?>
<script src="script.js" defer></script>
<script src="export.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/wordcloud2.js/1.1.2/wordcloud2.min.js" defer></script>
<script type="module">
  import { initWordcloud } from './cloud.js';
  const btn = document.getElementById('showWordcloud');
  btn.addEventListener('click', () => initWordcloud(window.ärenden || []));
</script>
