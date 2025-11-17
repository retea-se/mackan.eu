<!-- tools/aptus/index.php - v2 -->
<?php
$title = 'Hex till Dec Konverterare';
$metaDescription = 'Konvertera hexadecimala Aptus-värden till decimalform. Klistra in värden, få resultat och exportera till CSV. Enkel och snabb konvertering.';
$keywords = 'aptus, hex, decimal, konverterare, hexadecimal, konvertering, CSV export';
$canonical = 'https://mackan.eu/tools/aptus/';

// Strukturerad data för sökmotorer
$extraHead = '
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebApplication",
  "name": "Hex till Dec Konverterare",
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
    "Hex till decimal konvertering",
    "Aptus-värden",
    "CSV export",
    "Snabb konvertering"
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
      Klistra in hexadecimala Aptus-värden – verktyget konverterar automatiskt till decimal
      och låter dig exportera resultatet som CSV.
    </p>
  </header>

  <section class="layout__sektion">
    <form id="converter-form" class="form" novalidate>
      <div class="form__grupp">
        <label for="hexInput" class="falt__etikett">Hex-värden</label>
        <textarea id="hexInput" class="falt__textarea" rows="10" placeholder="Ex: 0102030A&#10;FFEE3100"></textarea>
        <p class="falt__hjälp">Skriv ett värde per rad. Ogiltiga rader markeras automatiskt.</p>
      </div>

      <div class="form__verktyg">
        <button type="button" class="knapp" id="convertButton" data-tippy-content="Konvertera alla inmatade värden">Konvertera</button>
        <button type="button" class="knapp knapp--liten hidden" id="exportButton" data-tippy-content="Exportera resultat till CSV">Exportera CSV</button>
        <button type="button" class="knapp knapp--liten hidden" id="clearButton" data-tippy-content="Rensa fält och resultat">Rensa</button>
      </div>
    </form>
  </section>

  <section class="layout__sektion hidden" id="resultWrapper">
    <div class="tabell__wrapper">
      <table id="resultTable" class="tabell">
        <thead>
          <tr>
            <th>Original (HEX)</th>
            <th>Decimal</th>
          </tr>
        </thead>
        <tbody id="resultBody"></tbody>
      </table>
    </div>
  </section>

</main>

<?php include '../../includes/tool-layout-end.php'; ?>
<script src="script.js" defer></script>
