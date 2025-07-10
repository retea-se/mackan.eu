<?php
// tools/qr_v2/index.php - v2 med SEO-förbättringar
$title = 'QR-kodgenerator - Skapa anpassade QR-koder';
$metaDescription = 'Skapa professionella QR-koder för text, länkar, WiFi, kontakter och mer. Anpassa färger, storlek och lägg till logotyp. Gratis QR-kodgenerator.';
$keywords = 'QR-kod generator, QR code, WiFi QR, kontakt QR, länk QR, anpassade QR-koder, gratis QR-generator';
$canonical = 'https://mackan.eu/tools/qr_v2/';
include '../../includes/layout-start.php';
?>

<link rel="stylesheet" href="styles.css">

<main class="layout__container">
  <!-- Breadcrumbs -->
  <nav class="breadcrumbs" aria-label="Du är här" style="margin-bottom: 1rem; font-size: 0.9rem; color: #6c757d;">
    <a href="/" style="color: #007bff; text-decoration: none;">🏠 Hem</a> ›
    <a href="/tools/" style="color: #007bff; text-decoration: none;">🔧 Verktyg</a> ›
    <span>📱 QR-kodgenerator</span>
  </nav>

  <div class="container">
    <h1><?= $title ?></h1>
    <p style="color: #6c757d; margin-bottom: 2rem; text-align: center;">
      Skapa professionella QR-koder för olika ändamål. Välj typ nedan och fyll i information.
    </p>

    <div class="form-group">
      <label>Välj typ av QR-kod:</label>
      <div class="button-group">
        <button class="type-button" data-type="text" data-tippy-content="Skapar QR för ren text">📝 Text</button>
        <button class="type-button" data-type="url" data-tippy-content="Skapar QR för webbadress">🔗 Länk</button>
        <button class="type-button" data-type="vcard" data-tippy-content="Skapar QR för kontaktuppgifter">👤 Kontakt</button>
        <button class="type-button" data-type="wifi" data-tippy-content="Skapar QR för WiFi-anslutning">📶 WiFi</button>
        <button class="type-button" data-type="email" data-tippy-content="Skapar QR för e-postmeddelande">✉️ E-post</button>
        <button class="type-button" data-type="sms" data-tippy-content="Skapar QR för SMS-meddelande">💬 SMS</button>
        <button class="type-button" data-type="phone" data-tippy-content="Skapar QR för telefonsamtal">📞 Telefon</button>
        <button class="type-button" data-type="geo" data-tippy-content="Skapar QR för geografisk plats">📍 Plats</button>
      </div>
    </div>

    <div id="formFields"></div>

    <button id="generateBtn" class="button" style="display: none;">🎯 Skapa QR-kod</button>

    <div class="preview">
      <div id="qrPreview"></div>
    </div>

    <!-- Relaterade verktyg -->
    <aside style="margin-top: 3rem; padding: 1.5rem; background: #f8f9fa; border-radius: 8px;">
      <h3 style="margin-top: 0;">🔗 Relaterade verktyg</h3>
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
        <a href="../koordinat/" style="text-decoration: none; padding: 1rem; border: 1px solid #dee2e6; border-radius: 6px; background: white; display: block; transition: transform 0.2s;">
          <h4 style="margin: 0 0 0.5rem; color: #007bff;">🗺️ Koordinatverktyg</h4>
          <p style="margin: 0; color: #6c757d; font-size: 0.9rem;">Konvertera koordinater</p>
        </a>
        <a href="../rka/" style="text-decoration: none; padding: 1rem; border: 1px solid #dee2e6; border-radius: 6px; background: white; display: block; transition: transform 0.2s;">
          <h4 style="margin: 0 0 0.5rem; color: #007bff;">⚡ RKA-kalkylatorer</h4>
          <p style="margin: 0; color: #6c757d; font-size: 0.9rem;">Dimensionera reservkraftverk</p>
        </a>
        <a href="../qr_v1/" style="text-decoration: none; padding: 1rem; border: 1px solid #dee2e6; border-radius: 6px; background: white; display: block; transition: transform 0.2s;">
          <h4 style="margin: 0 0 0.5rem; color: #007bff;">📱 QR v1</h4>
          <p style="margin: 0; color: #6c757d; font-size: 0.9rem;">Grundläggande QR-generator</p>
        </a>
      </div>
    </aside>

    <!-- Footer med versioninfo -->
    <div id="footer">
      <div id="versionInfo"></div>
    </div>
  </div>
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

<?php include '../../includes/layout-end.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
  if (typeof QRCode === 'undefined') {
    console.warn('CDN misslyckades, laddar lokal kopia...');
    const fallback = document.createElement('script');
    fallback.src = 'qrcode.min.js';
    fallback.onload = () => console.log('Lokal QRCode laddad');
    document.head.appendChild(fallback);
  }
</script>
<script src="script.js?v=13"></script>
