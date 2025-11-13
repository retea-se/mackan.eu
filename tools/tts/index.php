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

include '../../includes/layout-start.php';
?>

<main class="layout__container">
  <h1 class="title"><!-- TODO: osäker konvertering: title -->
    <?= $title ?>
  </h1>
  <?php if (!empty($subtitle)): ?>
    <p class="subtitle"><!-- TODO: osäker konvertering: subtitle --><?= $subtitle ?></p>
  <?php endif; ?>

  <div class="form__grupp">
    <label for="textInput">Klistra in text:</label>
    <textarea id="textInput" class="falt__textarea" rows="6"></textarea>
  </div>

  <div class="form__grupp">
    <label for="voiceSelect">Välj röst:</label>
    <select id="voiceSelect" class="falt__input"></select>
  </div>

  <div class="form__grupp">
    <button id="playBtn" class="knapp">Spela upp</button>
    <button id="downloadBtn" class="knapp">Ladda ner</button>
  </div>
</main>

<?php include '../../includes/layout-end.php'; ?>
<script src="script.js"></script>
