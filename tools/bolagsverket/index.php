<?php
$title = 'Bolagsverket API';
$metaDescription = 'Hämta företagsdata från Bolagsverkets API baserat på organisationsnummer.';
$keywords = 'bolagsverket, API, företagsdata, organisationsnummer, bolagsinformation, Sverige';
$canonical = 'https://mackan.eu/tools/bolagsverket/';

// Strukturerad data för sökmotorer
$extraHead = '
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebApplication",
  "name": "Bolagsverket API",
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
    "Hämta företagsdata",
    "Bolagsverket API",
    "Organisationsnummer",
    "Bolagsinformation"
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
      Slå upp företag direkt mot Bolagsverkets API. Ange ett organisationsnummer så presenteras
      struktur­erad data som du kan exportera.
    </p>
  </header>

  <section class="layout__sektion">
    <form id="dataForm" class="form" novalidate>
      <div class="form__grupp">
        <label for="orgnr" class="falt__etikett">Organisationsnummer</label>
        <input type="text" id="orgnr" class="falt__input" placeholder="Ex: 556475-6467" required inputmode="numeric" autocomplete="off">
        <p class="falt__hjälp">Både formatet 5564756467 och 556475-6467 fungerar.</p>
      </div>
      <div class="form__verktyg">
        <button type="submit" class="knapp" data-tippy-content="Hämta uppgifter från Bolagsverket">Hämta företagsinfo</button>
        <button type="button" class="knapp knapp--liten hidden" id="exportBtn" data-tippy-content="Exportera resultatet som text">Exportera</button>
      </div>
    </form>
  </section>

  <div id="loadingSpinner" class="hidden text--muted text--center" aria-live="polite">
    <span aria-hidden="true">🔄</span> Hämtar uppgifter ...
  </div>

  <section id="tableSection" class="layout__sektion hidden">
    <div class="tabell__wrapper">
      <table class="tabell tabell--kompakt" id="orgTable">
        <thead>
          <tr>
            <th class="tabell__huvud">Fält</th>
            <th class="tabell__huvud">Värde</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </section>
</main>

<?php include '../../includes/tool-layout-end.php'; ?>
<script src="getdata.js" defer></script>
