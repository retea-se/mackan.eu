<!-- tools/addy/index.php - v3 -->
<?php
$title = 'AnonAddy Address Generator';
$metaDescription = 'Skapa vidarebefordringsadresser för AnonAddy på sekunder. Generera säkra e-postadresser för att skydda din riktiga e-postadress från spam.';
$keywords = 'anonaddy, e-post, vidarebefordring, spam-skydd, säker e-post, anonym e-post';
$canonical = 'https://mackan.eu/tools/addy/';

// Strukturerad data för sökmotorer
$extraHead = '
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebApplication",
  "name": "AnonAddy Address Generator",
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
    "Generera AnonAddy-adresser",
    "Säkra e-postadresser",
    "Spam-skydd",
    "Vidarebefordring"
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
  <form id="addressForm" class="form">
    <div class="form__grupp">
      <label for="fromAddress" id="fromLabel">Från</label>
      <input type="text" id="fromAddress" class="falt__input" placeholder="Ange avsändaradress...">
    </div>

    <div class="form__grupp">
      <label for="toAddress" id="toLabel">Till</label>
      <input type="text" id="toAddress" class="falt__input" placeholder="Ange mottagaradress...">
    </div>

    <div class="form__verktyg">
      <button type="button" class="knapp" id="generateButton" data-tippy-content="Generera en AnonAddy-adress">Generera adress</button>
      <button type="button" class="knapp knapp--liten hidden" id="copyButton" data-tippy-content="Kopiera adressen">Kopiera</button>
    </div>

    <div class="form__grupp">
      <label for="generatedAddress" id="resultLabel">Resultat</label>
      <input type="text" id="generatedAddress" class="falt__input" readonly placeholder="Din genererade adress...">
    </div>
  </form>

  <div class="form__verktyg">
    <button id="languageToggle" class="knapp knapp--liten" type="button" data-tippy-content="Byt språk på fälten">Sv/En</button>
  </div>
</main>

<?php include '../../includes/tool-layout-end.php'; ?>
<script src="script.js" defer></script>
