<!-- tools/csv2json/index.php - v2 -->
<?php
$title = 'CSV till JSON';
$metaDescription = 'Konvertera CSV-data till JSON-format direkt i webbläsaren. Stöd för flera kolumner och radbrytningar.';
$keywords = 'CSV, JSON, konverterare, CSV till JSON, konvertering, data, webbläsare';
$canonical = 'https://mackan.eu/tools/csv2json/';

// Strukturerad data för sökmotorer
$extraHead = '
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebApplication",
  "name": "CSV till JSON",
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
    "CSV till JSON konvertering",
    "Flera kolumner",
    "Direkt i webbläsaren",
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

  <header class="layout__sektion text--center">
    <h1 class="rubrik rubrik--sektion">
      <?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>
    </h1>
  </header>

  <section class="layout__sektion">
    <h2 class="rubrik rubrik--underrubrik">Ladda upp eller klistra in CSV-data</h2>
    <form id="csvForm" class="form" autocomplete="off">
      <div class="form__grupp">
        <label for="csvFileInput" class="falt__etikett">Välj CSV-fil</label>
        <input type="file" id="csvFileInput" class="falt__input" accept=".csv">
      </div>
      <div class="form__verktyg">
        <button type="button" class="knapp" id="readFileBtn">Läs in fil</button>
      </div>
      <div class="form__grupp">
        <label for="csvInput" class="falt__etikett">Eller klistra in CSV-data</label>
        <textarea id="csvInput" class="falt__textarea" rows="10" placeholder="Klistra in din CSV-data här..."></textarea>
      </div>
    </form>
  </section>

  <section class="layout__sektion">
    <h2 class="rubrik rubrik--underrubrik">Statistik</h2>
    <p id="csvStats">Statistik för din CSV-fil kommer att visas här.</p>
  </section>

  <section class="layout__sektion">
    <h2 class="rubrik rubrik--underrubrik">Verktyg</h2>
    <div id="columnFilter" class="kort"></div>
    <div class="form__verktyg">
      <label class="falt__checkbox">
        <input type="checkbox" id="minifyCheckbox"> Kompakt JSON
      </label>
      <label class="falt__checkbox">
        <input type="checkbox" id="transposeCheckbox"> Transponera
      </label>
    </div>
  </section>

  <section class="layout__sektion">
    <h2 class="rubrik rubrik--underrubrik">Förhandsgranskning</h2>
    <pre id="livePreview" class="kort__terminal"></pre>
  </section>

  <section class="layout__sektion">
    <h2 class="rubrik rubrik--underrubrik">Konverterad JSON</h2>
    <div id="jsonOutput" class="kort"></div>
    <div class="form__verktyg">
      <button class="knapp" id="downloadJsonBtn">Ladda ner JSON</button>
      <button class="knapp" id="copyJsonBtn">Kopiera till urklipp</button>
    </div>
  </section>

  <!-- Vanliga frågor -->
  <section class="layout__sektion faq">
    <h2 class="faq__rubrik">Vanliga frågor</h2>
    <ul class="faq__lista">
      <li class="faq__item">
        <h3 class="faq__fraga">Hur konverterar jag CSV till JSON?</h3>
        <div class="faq__svar">
          <p>Du kan antingen ladda upp en CSV-fil genom att klicka på filväljaren eller klistra in din CSV-data direkt i textfältet. När datan är inläst visas en förhandsgranskning automatiskt och du kan sedan välja att ladda ner resultatet som en JSON-fil eller kopiera det till urklipp.</p>
        </div>
      </li>
      <li class="faq__item">
        <h3 class="faq__fraga">Vilka CSV-format stöds?</h3>
        <div class="faq__svar">
          <p>Verktyget stöder standard CSV-format med kommaseparerade värden. Det hanterar även filer med citattecken runt värden och kan hantera radbrytningar inom fält. De flesta CSV-filer som exporteras från Excel eller Google Sheets fungerar utan problem.</p>
        </div>
      </li>
      <li class="faq__item">
        <h3 class="faq__fraga">Kan jag välja JSON-format?</h3>
        <div class="faq__svar">
          <p>Ja, du kan välja mellan kompakt och formaterad JSON genom att använda alternativet för kompakt JSON. Du kan även transponera datan så att rader blir kolumner och vice versa. Dessutom kan du filtrera vilka kolumner som ska inkluderas i det slutliga resultatet.</p>
        </div>
      </li>
      <li class="faq__item">
        <h3 class="faq__fraga">Fungerar det med stora filer?</h3>
        <div class="faq__svar">
          <p>Verktyget kan hantera CSV-filer av varierande storlek, men eftersom all bearbetning sker i din webbläsare kan mycket stora filer göra att sidan blir långsam. För optimala prestanda rekommenderas filer med upp till några tusen rader. Vid större dataset kan det vara bättre att dela upp filen.</p>
        </div>
      </li>
      <li class="faq__item">
        <h3 class="faq__fraga">Sparas min data på servern?</h3>
        <div class="faq__svar">
          <p>Nej, all konvertering sker lokalt i din webbläsare och ingen data skickas till någon server. Din CSV-data behandlas endast på din egen enhet vilket garanterar full integritet och säkerhet. När du stänger sidan försvinner all data.</p>
        </div>
      </li>
      <li class="faq__item">
        <h3 class="faq__fraga">Hur använder jag det konverterade resultatet?</h3>
        <div class="faq__svar">
          <p>Du kan antingen ladda ner JSON-filen till din dator eller kopiera innehållet direkt till urklipp. Det genererade JSON-formatet kan användas i webbapplikationer, API-anrop, databaser eller andra system som accepterar JSON. Formatet följer standard JSON-syntax och är kompatibelt med alla moderna programmeringsspråk.</p>
        </div>
      </li>
    </ul>
  </section>

<?php include '../../includes/tool-layout-end.php'; ?>
<script src="script.js" defer></script>
<script src="tools.js" defer></script>
<script src="/js/faq.js"></script>
