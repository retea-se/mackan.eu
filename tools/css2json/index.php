<?php
// tools/css2json/index.php - v5
$title = 'CSS till JSON';
$metaDescription = 'Konvertera en eller flera CSS-filer till JSON-format direkt i webbläsaren.';
$keywords = 'CSS, JSON, konverterare, CSS till JSON, konvertering, webbläsare';
$canonical = 'https://mackan.eu/tools/css2json/';

// Strukturerad data för sökmotorer
$extraHead = '
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebApplication",
  "name": "CSS till JSON",
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
    "CSS till JSON konvertering",
    "Flera CSS-filer",
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
    <p class="text--lead">
      Ladda upp en eller flera CSS-filer och få en JSON-representation som kan laddas ned
      eller kopieras vidare i ditt projekt.
    </p>
  </header>

  <section class="layout__sektion">
    <form class="form" aria-label="CSS till JSON-formulär" id="cssForm" novalidate>
      <div class="form__grupp">
        <label for="cssFiles" class="falt__etikett">Välj CSS-fil(er)</label>
        <input type="file" id="cssFiles" class="falt__input" multiple accept=".css" aria-label="Ladda upp en eller flera CSS-filer" data-tippy-content="Välj en eller flera CSS-filer att konvertera till JSON">
        <p class="falt__hjälp">Filerna bearbetas helt i din webbläsare.</p>
      </div>
      <div class="form__verktyg">
        <button type="button" class="knapp" id="convertBtn" data-tippy-content="Konvertera valda CSS-filer till JSON">Konvertera</button>
        <button type="button" class="knapp knapp--liten hidden" id="downloadBtn" data-tippy-content="Ladda ner resultatet som JSON-fil" aria-label="Ladda ner JSON" disabled>Ladda ner JSON</button>
        <button type="button" class="knapp knapp--liten hidden" id="resetBtn" data-tippy-content="Rensa uppladdade filer och resultat">Rensa</button>
      </div>
    </form>
  </section>

  <section class="layout__sektion hidden" id="resultSection">
    <article class="kort">
      <h2 class="kort__rubrik">JSON-resultat</h2>
      <pre id="output" class="kort__innehall" aria-label="JSON-resultat"></pre>
    </article>
  </section>

  <!-- Vanliga frågor -->
  <section class="layout__sektion faq">
    <h2 class="faq__rubrik">Vanliga frågor</h2>
    <ul class="faq__lista">
      <li class="faq__item">
        <h3 class="faq__fraga">Hur konverterar jag CSS till JSON?</h3>
        <div class="faq__svar">
          <p>Klicka på filväljaren och välj en eller flera CSS-filer från din dator. När du klickar på Konvertera bearbetas filerna lokalt i din webbläsare och du får ett JSON-objekt där varje CSS-regel är strukturerad med sina selektorer och deklarationer.</p>
        </div>
      </li>
      <li class="faq__item">
        <h3 class="faq__fraga">Varför konvertera CSS till JSON?</h3>
        <div class="faq__svar">
          <p>JSON-format är användbart när du behöver bearbeta CSS-regler programmatiskt, till exempel för att analysera stilar, generera dokumentation eller transformera CSS i build-processer. Det strukturerade formatet gör det enkelt att arbeta med CSS i JavaScript eller andra programmeringsspråk.</p>
        </div>
      </li>
      <li class="faq__item">
        <h3 class="faq__fraga">Kan jag konvertera flera filer samtidigt?</h3>
        <div class="faq__svar">
          <p>Ja, verktyget stöder flera filer. Välj alla CSS-filer du vill konvertera samtidigt så slås de samman till ett JSON-objekt där varje fil blir en separat nyckel. Detta är praktiskt när du vill analysera hela stilmallar med flera delkomponenter.</p>
        </div>
      </li>
      <li class="faq__item">
        <h3 class="faq__fraga">Behålls CSS-kommentarer?</h3>
        <div class="faq__svar">
          <p>Nej, CSS-kommentarer tas bort vid konverteringen eftersom JSON-formatet fokuserar på den strukturerade datan från CSS-reglerna. Om du behöver kommentarer i din analys måste dessa hanteras separat innan konvertering.</p>
        </div>
      </li>
      <li class="faq__item">
        <h3 class="faq__fraga">Sparas mina CSS-filer någonstans?</h3>
        <div class="faq__svar">
          <p>Nej, all bearbetning sker lokalt i din webbläsare utan någon kommunikation med externa servrar. Dina CSS-filer och det genererade JSON-resultatet finns endast på din enhet. När du stänger sidan raderas all data automatiskt.</p>
        </div>
      </li>
      <li class="faq__item">
        <h3 class="faq__fraga">Vilket JSON-format används?</h3>
        <div class="faq__svar">
          <p>Verktyget skapar ett strukturerat JSON-objekt där varje CSS-regel representeras med sina selektorer som nycklar och deklarationer som värden. Formatet är designat för att vara lätt att arbeta med programmatiskt och följer standard JSON-syntax som fungerar i alla moderna programmeringsmiljöer.</p>
        </div>
      </li>
    </ul>
  </section>

<?php include '../../includes/tool-layout-end.php'; ?>
<script src="script.js" defer></script>
<script src="/js/faq.js"></script>
