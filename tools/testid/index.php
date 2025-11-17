<!-- tools/testid/index.php - v5 -->
<?php
$title = 'TestID';
$metaDescription = 'Generera testpersonnummer för angivet intervall. Resultatet kan exporteras till flera format för vidare testning.';
$keywords = 'testid, testpersonnummer, personnummer, testdata, svenska personnummer, testnummer generator';
$canonical = 'https://mackan.eu/tools/testid/';

// Strukturerad data för sökmotorer
$extraHead = '
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebApplication",
  "name": "TestID",
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
    "Generera testpersonnummer",
    "Välj intervall",
    "Export till JSON/CSV/Excel",
    "Testdata"
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
      Generera testpersonnummer för angivet intervall. Resultatet kan exporteras till flera format för vidare testning.
    </p>
  </header>

  <section class="layout__sektion">
    <form class="form" id="generateForm" novalidate>
      <div class="form__grupp">
        <label for="antal" class="falt__etikett">Antal personnummer</label>
        <input type="number" id="antal" class="falt__input" value="10" min="1" max="100">
      </div>

      <div class="form__grupp">
        <label for="startYear" class="falt__etikett">Från år</label>
        <input type="number" id="startYear" class="falt__input" value="1950" min="1930" max="2025">
      </div>

      <div class="form__grupp">
        <label for="endYear" class="falt__etikett">Till år</label>
        <input type="number" id="endYear" class="falt__input" value="2020" min="1930" max="2025">
      </div>

      <div class="form__verktyg">
        <button type="button" class="knapp" id="generateBtn" data-tippy-content="Hämta nya testpersoner">Hämta</button>
      </div>
    </form>
  </section>

  <div id="loader" class="hidden text--muted text--center" aria-live="polite">
    <span aria-hidden="true">🔄</span> Hämtar testpersoner ...
  </div>

  <section class="layout__sektion">
    <div id="exportMenu" class="knapp__grupp hidden">
      <button class="knapp knapp--liten" data-format="json">JSON</button>
      <button class="knapp knapp--liten" data-format="csv">CSV</button>
      <button class="knapp knapp--liten" data-format="xlsx">Excel</button>
    </div>

    <div class="tabell__wrapper">
      <table class="tabell" id="resultTable">
        <thead>
          <tr>
            <th>Personnummer</th>
            <th>Födelsedatum</th>
            <th>Kön</th>
            <th>Ålder</th>
            <th>Giltigt</th>
          </tr>
        </thead>
        <tbody>
          <!-- Dynamiskt innehåll fylls av JS -->
        </tbody>
      </table>
    </div>
  </section>

</main>

<?php include '../../includes/tool-layout-end.php'; ?>
<script type="module" src="script.js"></script>
