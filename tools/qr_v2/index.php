<?php
// tools/qr_v2/index.php - v1
$title = 'QR v2';
include '../../includes/layout-start.php';
?>

<main class="layout__container">
 
  <div class="form__grupp">
    <label>Välj typ av kod:</label>
    <div id="typeButtons" class="horizontal-tools"><!-- TODO: osäker konvertering: button-group -->
      <button class="knapp type-button" data-type="text">Text</button>
      <button class="knapp type-button" data-type="url">Länk</button>
      <button class="knapp type-button" data-type="vcard">Kontakt</button>
      <button class="knapp type-button" data-type="wifi">WiFi</button>
      <button class="knapp type-button" data-type="email">E-post</button>
      <button class="knapp type-button" data-type="sms">SMS</button>
      <button class="knapp type-button" data-type="phone">Telefon</button>
      <button class="knapp type-button" data-type="geo">Plats</button>
    </div>
  </div>

  <div id="formFields"></div>

  <button id="generateBtn" class="knapp">Skapa QR-kod</button>

  <div id="qrPreview" class="kort utils--mt-1"><!-- TODO: osäker konvertering: preview mt-1 --></div>
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
