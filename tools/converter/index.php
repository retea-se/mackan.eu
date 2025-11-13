<!-- tools/converter/index.php - v5 -->
<?php
$title = 'Data Converter';
$metaDescription = 'Konvertera mellan olika dataformat som CSV, JSON, YAML och XML. Formatera, validera och reparera JSON.';
$keywords = 'data converter, CSV, JSON, YAML, XML, konverterare, formatera, validera, reparera';
$canonical = 'https://mackan.eu/tools/converter/';

// Strukturerad data för sökmotorer
$extraHead = '
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebApplication",
  "name": "Data Converter",
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
    "CSV till JSON",
    "JSON formatering",
    "JSON validering",
    "JSON reparation",
    "YAML konvertering",
    "XML konvertering"
  ],
  "author": {
    "@type": "Organization",
    "name": "Mackan.eu"
  }
}
</script>';

include '../../includes/layout-start.php';
?>

<!-- ********** START: Converter Interface ********** -->
<main class="layout__container">
  <header class="layout__sektion text--center">
    <h1 class="rubrik rubrik--sektion">🔄 Data Converter</h1>
    <p class="text--lead">Konvertera mellan olika dataformat snabbt och enkelt</p>
  </header>

  <section class="layout__sektion">
    <div class="konverterar">
      <div class="konverterar__tabs">
        <button class="konverterar__tab konverterar__tab--aktiv" data-tab="csvtojson" data-tippy-content="Konvertera CSV till JSON" aria-label="CSV till JSON">
          <span class="konverterar__ikon">📊</span>
          <span class="konverterar__titel">CSV → JSON</span>
        </button>
        <button class="konverterar__tab" data-tab="formatter" data-tippy-content="Formatera JSON" aria-label="Formatter">
          <span class="konverterar__ikon">✨</span>
          <span class="konverterar__titel">Formatter</span>
        </button>
        <button class="konverterar__tab" data-tab="validator" data-tippy-content="Validera JSON" aria-label="Validera">
          <span class="konverterar__ikon">✅</span>
          <span class="konverterar__titel">Validera</span>
        </button>
        <button class="konverterar__tab" data-tab="fixer" data-tippy-content="Reparera JSON" aria-label="Reparera">
          <span class="konverterar__ikon">🔧</span>
          <span class="konverterar__titel">Reparera</span>
        </button>
        <button class="konverterar__tab" data-tab="utilities" data-tippy-content="Småverktyg" aria-label="Verktyg">
          <span class="konverterar__ikon">🛠️</span>
          <span class="konverterar__titel">Verktyg</span>
        </button>
        <button class="konverterar__tab" data-tab="converter" data-tippy-content="Konvertera JSON ↔ CSV/YAML/XML" aria-label="Konvertera">
          <span class="konverterar__ikon">🔄</span>
          <span class="konverterar__titel">Konvertera</span>
        </button>
      </div>

      <div class="konverterar__innehall">
        <section id="tab-csvtojson" class="konverterar__sektion">
          <h2 class="rubrik rubrik--underrubrik">📊 CSV till JSON</h2>
          <!-- Kommer fyllas av csvtojson.js -->
        </section>

        <section id="tab-formatter" class="konverterar__sektion hidden">
          <h2 class="rubrik rubrik--underrubrik">✨ JSON Formatter</h2>
          <!-- Kommer fyllas av formatter.js -->
        </section>

        <section id="tab-validator" class="konverterar__sektion hidden">
          <h2 class="rubrik rubrik--underrubrik">✅ Validera JSON</h2>
          <!-- Kommer fyllas av validator.js -->
        </section>

        <section id="tab-fixer" class="konverterar__sektion hidden">
          <h2 class="rubrik rubrik--underrubrik">🔧 Reparera JSON</h2>
          <!-- Kommer fyllas av fixer.js -->
        </section>

        <section id="tab-utilities" class="konverterar__sektion hidden">
          <h2 class="rubrik rubrik--underrubrik">🛠️ Småverktyg</h2>
          <!-- Kommer fyllas av utilities.js -->
        </section>

        <section id="tab-converter" class="konverterar__sektion hidden">
          <h2 class="rubrik rubrik--underrubrik">🔄 Konvertera JSON ↔ CSV/YAML/XML</h2>
          <div id="uploadContainer"></div>
        </section>
      </div>
    </div>
  </section>
</main>
<!-- ********** SLUT: Converter Interface ********** -->


<script type="module" src="script.js"></script>

<?php include '../../includes/layout-end.php'; ?>
