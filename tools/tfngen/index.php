<!-- index.php - v7 -->
<?php
$title = 'Testnummer – Generera svenska telefonnummer';
$metaDescription = 'Skapa testnummer inom mobil- och fastnät. Välj serier, format och exportera till CSV, text eller JSON.';
$keywords = 'testnummer, telefonnummer, mobilnummer, fastnät, svenska telefonnummer, testdata';
$canonical = 'https://mackan.eu/tools/tfngen/';

// Strukturerad data för sökmotorer
$extraHead = '
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebApplication",
  "name": "Testnummer – Generera svenska telefonnummer",
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
    "Generera testnummer",
    "Mobil- och fastnät",
    "Välj serier",
    "Export till CSV/JSON"
  ],
  "author": {
    "@type": "Organization",
    "name": "Mackan.eu"
  }
}
</script>';

include '../../includes/tool-layout-start.php';
?>

  <header class="layout__sektion text--center">
    <h1 class="rubrik rubrik--sektion">
      <?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>
    </h1>
    <p class="text--lead">
      Generera svenska testnummer för mobil- och fastnät. Välj serier och format, exportera resultatet vid behov.
    </p>
  </header>

  <section class="layout__sektion">
    <form class="form" id="generatorForm" novalidate>
      <div class="form__grupp">
        <label for="antalNummer" class="falt__etikett">Antal nummer (max 500)</label>
        <input type="number" id="antalNummer" class="falt__input" value="100" min="1" max="500">
      </div>

      <div class="form__grupp">
        <span class="falt__etikett">Välj serier</span>
        <div class="flex-column">
          <label class="falt__checkbox"><input type="checkbox" name="serier" value="070" checked> 070 (mobil)</label>
          <label class="falt__checkbox"><input type="checkbox" name="serier" value="031" checked> 031 (Göteborg)</label>
          <label class="falt__checkbox"><input type="checkbox" name="serier" value="040" checked> 040 (Malmö)</label>
          <label class="falt__checkbox"><input type="checkbox" name="serier" value="08" checked> 08 (Stockholm)</label>
          <label class="falt__checkbox"><input type="checkbox" name="serier" value="0980" checked> 0980 (Kiruna)</label>
        </div>
      </div>

      <div class="form__grupp">
        <label for="formatVal" class="falt__etikett">Internationellt format</label>
        <select id="formatVal" class="falt__dropdown">
          <option value="nej" selected>Nej</option>
          <option value="ja">Ja</option>
          <option value="slumpa">Slumpa</option>
        </select>
      </div>

      <div class="form__verktyg">
        <button type="submit" class="knapp" data-tippy-content="Generera önskat antal testnummer">Generera</button>
        <button type="button" class="knapp knapp--liten hidden" id="rensaknapp" data-tippy-content="Rensa listan">Rensa</button>
      </div>
    </form>
  </section>

  <section class="layout__sektion">
    <div class="knapp__grupp hidden" id="exportKnappContainer">
      <button type="button" class="knapp knapp--liten" id="exportJsonBtn" data-tippy-content="Exportera genererade nummer som JSON">Exportera som JSON</button>
    </div>

    <article class="kort">
      <h2 class="kort__rubrik">Genererade nummer</h2>
      <div class="kort__innehall">
        <ul id="nummerLista"></ul>
      </div>
    </article>
  </section>

</main>

<?php include '../../includes/tool-layout-end.php'; ?>
<script src="script.js" defer></script>
