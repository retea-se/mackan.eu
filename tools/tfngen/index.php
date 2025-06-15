<!-- index.php - v6 -->
<?php
$title = 'Testnummer – Generera svenska telefonnummer';
$metaDescription = 'Skapa testnummer inom mobil- och fastnät. Välj serier, format och exportera till CSV, text eller JSON.';
?>
<?php include '../../includes/layout-start.php'; ?>

<main class="layout__container">

  <!-- ********** START Sektion: Formulär ********** -->
  <section>
    <h2>Genereringsinställningar</h2>
    <form class="form__grupp" onsubmit="event.preventDefault(); genereraNummer();">
      <div class="form__grupp">
        <label for="antalNummer">Antal nummer (max 500):</label>
        <input type="number" id="antalNummer" class="falt__input" value="100" min="1" max="500">
      </div>

      <div class="form__grupp">
        <label>Välj serier:</label>
        <label class="checkbox"><input type="checkbox" name="serier" value="070" checked> 070 (mobil)</label>
        <label class="checkbox"><input type="checkbox" name="serier" value="031" checked> 031 (Göteborg)</label>
        <label class="checkbox"><input type="checkbox" name="serier" value="040" checked> 040 (Malmö)</label>
        <label class="checkbox"><input type="checkbox" name="serier" value="08" checked> 08 (Stockholm)</label>
        <label class="checkbox"><input type="checkbox" name="serier" value="0980" checked> 0980 (Kiruna)</label>
      </div>

      <div class="form__grupp">
        <label for="formatVal">Internationellt format:</label>
        <select id="formatVal" class="falt__select">
          <option value="nej" selected>Nej</option>
          <option value="ja">Ja</option>
          <option value="slumpa">Slumpa</option>
        </select>
      </div>

      <div class="form__verktyg"><!-- TODO: osäker konvertering -->
        <button type="submit" class="knapp">Generera</button>
        <button type="button" class="knapp utils--dold" id="rensaknapp">Rensa</button>
      </div>
    </form>
  </section>
  <!-- ********** SLUT Sektion: Formulär ********** -->

  <!-- ********** START Sektion: Resultat ********** -->
  <section>
    <h2>Resultat</h2>

    <div id="exportKnappContainer" class="utils--dold" style="margin: 1em 0;">
      <button onclick="exporteraTillJSON();" class="knapp">Exportera som JSON</button>
    </div>

    <article class="kort">
      <ul id="nummerLista" class="list"><!-- TODO: osäker konvertering: list --></ul>
    </article>
  </section>
  <!-- ********** SLUT Sektion: Resultat ********** -->
</main>

<?php include '../../includes/layout-end.php'; ?>
<script src="script.js" defer></script>
