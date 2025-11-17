<?php
// tools/qr_v3/index.php - Kombinerad QR-kodgenerator
$title = 'QR-kodgenerator - Komplett verktyg för alla behov';
$metaDescription = 'Skapa QR-koder för text, länkar, WiFi, kontakter, felanmälningar och mer. Batch-generering, anpassning och export till flera format.';
include '../../includes/tool-layout-start.php';
?>

<main class="layout__container">
  <header class="layout__sektion text--center">
    <h1 class="rubrik rubrik--sektion">
      <?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>
    </h1>
    <p class="text--lead">
      Skapa enstaka QR-koder eller generera hela listor i batch. Alla koder skapas lokalt i din webbläsare
      och kan exporteras som PNG, ZIP eller DOCX.
    </p>
  </header>

  <section class="layout__sektion">
    <div class="qr" data-qr="v3">
      <div class="qr__mode-tabs">
        <button class="qr__mode-btn qr__mode-btn--active" data-mode="single" aria-label="Enkel QR-kod">📱 Enkel QR-kod</button>
        <button class="qr__mode-btn" data-mode="batch" aria-label="Batch-generering">📋 Batch-generering</button>
      </div>

      <div id="single-mode" class="qr__mode-content qr__mode-content--active">
        <div class="qr__type-grid">
          <button class="qr__type-btn" data-type="text" title="Skapar QR för ren text" aria-label="Text QR-kod">
            <span class="qr__type-icon" aria-hidden="true">📝</span>
            <span>Text</span>
          </button>
          <button class="qr__type-btn" data-type="url" title="Skapar QR för webbadress" aria-label="Länk QR-kod">
            <span class="qr__type-icon" aria-hidden="true">🔗</span>
            <span>Länk</span>
          </button>
          <button class="qr__type-btn" data-type="vcard" title="Skapar QR för kontaktuppgifter" aria-label="Kontakt QR-kod">
            <span class="qr__type-icon" aria-hidden="true">👤</span>
            <span>Kontakt</span>
          </button>
          <button class="qr__type-btn" data-type="wifi" title="Skapar QR för WiFi-anslutning" aria-label="WiFi QR-kod">
            <span class="qr__type-icon" aria-hidden="true">📶</span>
            <span>WiFi</span>
          </button>
          <button class="qr__type-btn" data-type="email" title="Skapar QR för e-post" aria-label="E-post QR-kod">
            <span class="qr__type-icon" aria-hidden="true">✉️</span>
            <span>E-post</span>
          </button>
          <button class="qr__type-btn" data-type="sms" title="Skapar QR för SMS" aria-label="SMS QR-kod">
            <span class="qr__type-icon" aria-hidden="true">💬</span>
            <span>SMS</span>
          </button>
          <button class="qr__type-btn" data-type="phone" title="Skapar QR för telefon" aria-label="Telefon QR-kod">
            <span class="qr__type-icon" aria-hidden="true">📞</span>
            <span>Telefon</span>
          </button>
          <button class="qr__type-btn" data-type="geo" title="Skapar QR för plats" aria-label="Plats QR-kod">
            <span class="qr__type-icon" aria-hidden="true">📍</span>
            <span>Plats</span>
          </button>
        </div>

        <div id="single-form" class="form"></div>
        <div class="qr__actions">
          <button id="single-generate" class="knapp knapp--liten hidden">🎯 Skapa QR-kod</button>
        </div>
        <div id="qr-preview" class="qr__preview" aria-live="polite"></div>
      </div>

      <div id="batch-mode" class="qr__mode-content hidden">
        <div class="qr__batch-select">
          <button class="qr__mode-btn" data-batch-type="felanmalan" aria-label="Felanmälningar batch">⚠️ Felanmälningar</button>
          <button class="qr__mode-btn" data-batch-type="links" aria-label="Länkar batch">🔗 Länkar</button>
          <button class="qr__mode-btn" data-batch-type="text" aria-label="Text batch">📝 Text</button>
        </div>

        <div id="batch-input" class="hidden">
          <textarea id="batch-textarea" class="qr__batch-textarea" rows="10" placeholder="Ange text per rad..."></textarea>
          <div class="qr__actions">
            <button id="batch-generate" class="knapp knapp--liten">📋 Generera QR-koder</button>
          </div>
        </div>

        <div id="batch-preview" class="qr__batch-grid"></div>
        <p id="batch-status" class="qr__batch-status hidden"></p>
      </div>

      <div id="export-options" class="qr__export hidden">
        <h3>💾 Export</h3>
        <div class="qr__export-buttons">
          <button id="download-png" class="knapp knapp--liten">🖼️ PNG</button>
          <button id="download-zip" class="knapp knapp--liten">📦 ZIP</button>
          <button id="download-docx" class="knapp knapp--liten">📄 DOCX</button>
        </div>
      </div>
    </div>
  </section>

  <!-- Vanliga frågor -->
  <section class="layout__sektion faq">
    <h2 class="faq__rubrik">Vanliga frågor</h2>
    <ul class="faq__lista">
      <li class="faq__item">
        <h3 class="faq__fraga">Hur skapar jag en WiFi QR-kod?</h3>
        <div class="faq__svar">
          <p>Välj WiFi från typvalet, ange ditt nätverksnamn (SSID), lösenord och välj säkerhetstyp (WPA/WPA2 eller WEP). Klicka på Skapa QR-kod och koden genereras direkt. När någon skannar koden kan de ansluta automatiskt till nätverket utan att manuellt ange lösenord.</p>
        </div>
      </li>
      <li class="faq__item">
        <h3 class="faq__fraga">Kan jag generera flera QR-koder samtidigt?</h3>
        <div class="faq__svar">
          <p>Ja, använd fliken Batch-generering för att skapa flera QR-koder på en gång. Du kan välja mellan felanmälningar, länkar eller anpassad text. Ange en post per rad i textfältet och alla QR-koder genereras samtidigt. Du kan sedan exportera alla koder som ZIP-fil eller DOCX-dokument.</p>
        </div>
      </li>
      <li class="faq__item">
        <h3 class="faq__fraga">Vilka exportformat stöds?</h3>
        <div class="faq__svar">
          <p>Du kan exportera QR-koder som PNG-bilder (för enskilda koder), ZIP-fil (innehåller alla koder som PNG-filer) eller DOCX-dokument (för utskrift). DOCX-formatet är praktiskt om du vill skriva ut flera koder på samma sida eller inkludera dem i rapporter.</p>
        </div>
      </li>
      <li class="faq__item">
        <h3 class="faq__fraga">Sparas mina QR-koder på servern?</h3>
        <div class="faq__svar">
          <p>Nej, alla QR-koder genereras lokalt i din webbläsare med JavaScript. Ingen data skickas till våra servrar. Detta gör verktyget säkert att använda även för känslig information som WiFi-lösenord eller privata länkar. Dina uppgifter stannar i din webbläsare.</p>
        </div>
      </li>
      <li class="faq__item">
        <h3 class="faq__fraga">Kan jag använda QR-koderna kommersiellt?</h3>
        <div class="faq__svar">
          <p>Ja, alla QR-koder du skapar är fria att använda både privat och kommersiellt utan begränsningar. QR-kodstandarden är öppen och koderna du genererar tillhör dig. Du kan använda dem i marknadsföring, produkter, dokument eller var du vill.</p>
        </div>
      </li>
      <li class="faq__item">
        <h3 class="faq__fraga">Hur fungerar felanmälnings-QR-koder?</h3>
        <div class="faq__svar">
          <p>Felanmälnings-QR-koder är optimerade för att användas på utrustning och maskiner. När koden skannas öppnas ett formulär eller en länk där användaren kan rapportera fel. Du kan anpassa vilken information som ska ingå i QR-koden för att passa din organisation.</p>
        </div>
      </li>
    </ul>
  </section>
</main>

<!-- Strukturerad data -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebApplication",
  "name": "QR-kodgenerator v3",
  "description": "<?= htmlspecialchars($metaDescription) ?>",
  "url": "https://mackan.eu/tools/qr_v3/",
  "applicationCategory": "UtilityApplication",
  "operatingSystem": "Web Browser",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "SEK"
  },
  "featureList": [
    "Enkel QR-kodgenerering",
    "Batch-generering",
    "Felanmälnings-QR",
    "WiFi QR-koder",
    "Kontakt QR-koder",
    "Export till PNG/ZIP/DOCX"
  ]
}
</script>

<?php include '../../includes/tool-layout-end.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
  // CDN fallback för qrcodejs
  if (typeof QRCode === 'undefined') {
    const fallback = document.createElement('script');
    fallback.src = '/tools/qr_v2/qrcode.min.js';
    document.head.appendChild(fallback);
  }
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/docx@7.8.2/build/index.js"></script>
<script src="/js/faq.js"></script>
<script src="script.js" defer></script>
