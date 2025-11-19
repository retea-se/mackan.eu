<?php
// tools/tts/index.php - v3
$title = 'Text-to-Speech';
$metaDescription = 'Skriv text och spela upp med vald röst. Konvertera text till tal direkt i webbläsaren med flera röster och språk. Ladda ner som ljudfil.';
$keywords = 'text-to-speech, tts, tal, röst, syntes, ljud, webbläsare, text-till-tal';
$canonical = 'https://mackan.eu/tools/tts/';
$subtitle = 'Skriv text och spela upp med vald röst';

// Strukturerad data för sökmotorer
$extraHead = '
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebApplication",
  "name": "Text-to-Speech",
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
    "Text till tal",
    "Flera röster",
    "Flera språk",
    "Ladda ner som ljudfil"
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
      <?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>
    </h1>
    <?php if (!empty($subtitle)): ?>
      <p class="text--lead"><?= htmlspecialchars($subtitle, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>
  </header>

  <section class="layout__sektion">
    <form class="form">
      <div class="form__grupp">
        <label for="textInput" class="falt__etikett">Klistra in text:</label>
        <textarea id="textInput" class="falt__textarea" rows="6"></textarea>
      </div>

      <div class="form__grupp">
        <label for="voiceSelect" class="falt__etikett">Välj röst:</label>
        <select id="voiceSelect" class="falt__select"></select>
      </div>

      <div class="form__verktyg">
        <button id="playBtn" class="knapp" type="button">Spela upp</button>
        <button id="downloadBtn" class="knapp" type="button">Ladda ner</button>
      </div>
    </form>
  </section>
</main>

<?php include '../../includes/tool-layout-end.php'; ?>
<script src="script.js"></script>
