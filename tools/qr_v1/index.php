<?php $title = 'QR Code Generator'; ?>
<?php include '../../includes/layout-start.php'; ?>

<main class="layout__container">

  <div class="form__verktyg"><!-- TODO: osäker konvertering -->
    <button class="knapp" onclick="showQRCodeSection('felanmalning')" data-tippy-content="Visar QR för felanmälan">QR för felanmälan</button>
    <button class="knapp" onclick="showQRCodeSection('lank')" data-tippy-content="Visar QR för länkar">QR för länkar</button>
  </div>

  <section id="qr-section" class="form__grupp hidden">
    <textarea id="textbox" class="falt__textarea" rows="10" placeholder="Ange text eller länkar per rad..." data-tippy-content="Skriv text eller länkar här"></textarea>
    <button id="generate-button" class="knapp" onclick="generateQRCode()" data-tippy-content="Skapar QR-koder">Generera QR-koder</button>

    <div id="qrcode"><!-- TODO: osäker konvertering: qr-output --></div>

    <div class="form__verktyg"><!-- TODO: osäker konvertering -->
      <button id="download-button" class="knapp" onclick="downloadAllQRCodes()" data-tippy-content="Laddar ner alla QR-koder">Ladda ned alla QR-koder</button>
      <button id="download-docx-button" class="knapp" onclick="downloadAllDocx()" data-tippy-content="Laddar ner QR som DOCX">Ladda ned som DOCX</button>
    </div>
  </section>
</main>

<?php include '../../includes/layout-end.php'; ?>

<!-- Externa bibliotek -->
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js" defer></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.5/jszip.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/docx@7.0.0/build/index.js" defer></script>

<!-- Lokala skript -->
<script src="script.js" defer></script>
