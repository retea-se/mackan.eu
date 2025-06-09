<!-- index.php - v6 -->
<?php
$title = 'Testnummer – Generera svenska telefonnummer';
$metaDescription = 'Skapa testnummer inom mobil- och fastnät. Välj serier, format och exportera till CSV, text eller JSON.';
?>
<?php include '../../includes/layout-start.php'; ?>

<main class="container">


  <!-- ********** START Sektion: Formulär ********** -->
  <section>
    <h2>Genereringsinställningar</h2>
    <form class="form-group" onsubmit="event.preventDefault(); genereraNummer();">
      <div class="form-group">
        <label for="antalNummer">Antal nummer (max 500):</label>
        <input type="number" id="antalNummer" class="input" value="100" min="1" max="500">
      </div>

      <div class="form-group">
        <label>Välj serier:</label>
        <label class="checkbox"><input type="checkbox" name="serier" value="070" checked> 070 (mobil)</label>
        <label class="checkbox"><input type="checkbox" name="serier" value="031" checked> 031 (Göteborg)</label>
        <label class="checkbox"><input type="checkbox" name="serier" value="040" checked> 040 (Malmö)</label>
        <label class="checkbox"><input type="checkbox" name="serier" value="08" checked> 08 (Stockholm)</label>
        <label class="checkbox"><input type="checkbox" name="serier" value="0980" checked> 0980 (Kiruna)</label>
      </div>

      <div class="form-group">
        <label for="formatVal">Internationellt format:</label>
        <select id="formatVal" class="input">
          <option value="nej" selected>Nej</option>
          <option value="ja">Ja</option>
          <option value="slumpa">Slumpa</option>
        </select>
      </div>

      <div class="horizontal-tools">
        <button type="submit" class="button">Generera</button>
        <button type="button" class="button hidden" id="rensaknapp">Rensa</button>
      </div>
    </form>
  </section>
  <!-- ********** SLUT Sektion: Formulär ********** -->

  <!-- ********** START Sektion: Resultat ********** -->
  <section>
    <h2>Resultat</h2>

    <div id="exportKnappContainer" class="hidden" style="margin: 1em 0;">
      <button onclick="exporteraTillJSON();" class="button">Exportera som JSON</button>
    </div>

    <article class="card">
      <ul id="nummerLista" class="list"></ul>
    </article>
  </section>
  <!-- ********** SLUT Sektion: Resultat ********** -->
</main>

<?php include '../../includes/layout-end.php'; ?>
<script src="script.js" defer></script>
