<?php
// tools/qr_v2/index.php - v3 med SEO-förbättringar och JSON-LD
$title = 'QR-kodgenerator - Skapa anpassade QR-koder';
$metaDescription = 'Skapa professionella QR-koder för text, länkar, WiFi, kontakter och mer. Anpassa färger, storlek och lägg till logotyp. Gratis QR-kodgenerator.';
$keywords = 'QR-kod generator, QR code, WiFi QR, kontakt QR, länk QR, anpassade QR-koder, gratis QR-generator';
$canonical = 'https://mackan.eu/tools/qr_v2/';

// Strukturerad data för sökmotorer
$extraHead = '
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebApplication",
  "name": "QR-kodgenerator - Skapa anpassade QR-koder",
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
    "QR-kodgenerering",
    "Anpassade färger",
    "WiFi QR-koder",
    "Kontakt QR-koder",
    "Länk QR-koder"
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
      Skapa professionella QR-koder för text, länkar, WiFi, kontakter och mer. Välj typ och fyll i uppgifterna
      – koden genereras direkt i din webbläsare.
    </p>
  </header>

  <section class="layout__sektion">
    <div class="qr" data-qr="v2">
      <div class="qr__type-grid" id="qrTypeGrid">
        <button class="qr__type-btn" data-type="text" data-tippy-content="Skapar QR för ren text" aria-label="Text QR-kod">
          <span class="qr__type-icon" aria-hidden="true"></span>
          <span>Text</span>
        </button>
        <button class="qr__type-btn" data-type="url" data-tippy-content="Skapar QR för webbadress" aria-label="Länk QR-kod">
          <span class="qr__type-icon" aria-hidden="true"></span>
          <span>Länk</span>
        </button>
        <button class="qr__type-btn" data-type="vcard" data-tippy-content="Skapar QR för kontaktuppgifter" aria-label="Kontakt QR-kod">
          <span class="qr__type-icon" aria-hidden="true"></span>
          <span>Kontakt</span>
        </button>
        <button class="qr__type-btn" data-type="wifi" data-tippy-content="Skapar QR för WiFi-anslutning" aria-label="WiFi QR-kod">
          <span class="qr__type-icon" aria-hidden="true"></span>
          <span>WiFi</span>
        </button>
        <button class="qr__type-btn" data-type="email" data-tippy-content="Skapar QR för e-postmeddelande" aria-label="E-post QR-kod">
          <span class="qr__type-icon" aria-hidden="true"></span>
          <span>E-post</span>
        </button>
        <button class="qr__type-btn" data-type="sms" data-tippy-content="Skapar QR för SMS-meddelande" aria-label="SMS QR-kod">
          <span class="qr__type-icon" aria-hidden="true"></span>
          <span>SMS</span>
        </button>
        <button class="qr__type-btn" data-type="phone" data-tippy-content="Skapar QR för telefonsamtal" aria-label="Telefon QR-kod">
          <span class="qr__type-icon" aria-hidden="true"></span>
          <span>Telefon</span>
        </button>
        <button class="qr__type-btn" data-type="geo" data-tippy-content="Skapar QR för geografisk plats" aria-label="Plats QR-kod">
          <span class="qr__type-icon" aria-hidden="true"></span>
          <span>Plats</span>
        </button>
      </div>

      <div id="formFields" class="form"></div>

      <div class="qr__actions">
        <button id="generateBtn" class="knapp knapp--liten hidden">Skapa QR-kod</button>
      </div>

      <div id="qrPreview" class="qr__preview" aria-live="polite"></div>
      <div id="extraButtons" class="qr__extra hidden" aria-hidden="true"></div>

      <div class="qr__related">
        <h2 class="rubrik rubrik--underrubrik">Relaterade verktyg</h2>
        <div class="qr__related-grid">
          <a class="qr__related-link" href="../koordinat/">
            <h3 class="rubrik rubrik--underrubrik">Koordinatverktyg</h3>
            <p class="text--muted text--small">Konvertera koordinater och exportera resultat.</p>
          </a>
          <a class="qr__related-link" href="../rka/">
            <h3 class="rubrik rubrik--underrubrik">RKA-kalkylatorer</h3>
            <p class="text--muted text--small">Dimensionera reservkraftverk och provkörningsscheman.</p>
          </a>
          <a class="qr__related-link" href="../qr_v3/">
            <h3 class="rubrik rubrik--underrubrik">QR-verkstad</h3>
            <p class="text--muted text--small">Avancerad QR-generator med batch-stöd.</p>
          </a>
        </div>
        <p class="text--muted text--small text--center" id="versionInfo"></p>
      </div>
    </div>
  </section>
</main>

<!-- Strukturerad data för SEO -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebApplication",
  "name": "QR-kodgenerator",
  "description": "<?= htmlspecialchars($metaDescription) ?>",
  "url": "<?= $canonical ?>",
  "applicationCategory": "UtilityApplication",
  "operatingSystem": "Web Browser",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "SEK"
  },
  "featureList": [
    "Text QR-koder",
    "URL QR-koder",
    "WiFi QR-koder",
    "Kontakt QR-koder",
    "E-post QR-koder",
    "SMS QR-koder",
    "Telefon QR-koder",
    "GPS QR-koder"
  ]
}
</script>

<?php include '../../includes/tool-layout-end.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
  if (typeof QRCode === 'undefined') {
    const fallback = document.createElement('script');
    fallback.src = 'qrcode.min.js';
    document.head.appendChild(fallback);
  }
</script>
<script src="script.js" defer></script>