<?php
// tools/qr_v2/index.php - v1
$title = 'QR v2';
include '../../includes/layout-start.php';
?>

<main class="layout__container">

  <div class="form__grupp">
    <label data-tippy-content="Välj QR-kodtyp">Välj typ av kod:</label>
    <div id="typeButtons" class="form__verktyg">
      <button class="knapp" data-type="text" data-tippy-content="Skapar QR för text">Text</button>
      <button class="knapp" data-type="url" data-tippy-content="Skapar QR för länk">Länk</button>
      <button class="knapp" data-type="vcard" data-tippy-content="Skapar QR för kontakt">Kontakt</button>
      <button class="knapp" data-type="wifi" data-tippy-content="Skapar QR för WiFi">WiFi</button>
      <button class="knapp" data-type="email" data-tippy-content="Skapar QR för e-post">E-post</button>
      <button class="knapp" data-type="sms" data-tippy-content="Skapar QR för SMS">SMS</button>
      <button class="knapp" data-type="phone" data-tippy-content="Skapar QR för telefon">Telefon</button>
      <button class="knapp" data-type="geo" data-tippy-content="Skapar QR för plats">Plats</button>
    </div>
  </div>

  <div id="formFields" data-tippy-content="Fyll i information för vald QR-typ"></div>

  <button id="generateBtn" class="knapp" data-tippy-content="Generera QR-kod utifrån dina val">Skapa QR-kod</button>

  <div id="qrPreview" class="kort utils--mt-1" data-tippy-content="Förhandsvisning av QR-kod">
</main>

<?php include '../../includes/layout-end.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
  if (typeof QRCode === 'undefined') {
    const fallback = document.createElement('script');
    fallback.src = 'qrcode.min.js';
    document.head.appendChild(fallback);
  }
</script>
<script src="script.js?v=13"></script>
