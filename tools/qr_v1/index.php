<?php $title = 'QR Code Generator'; ?>
<?php include '../../includes/layout-start.php'; ?>

<main class="container">


  <div class="horizontal-tools">
    <button class="button" onclick="showQRCodeSection('felanmalning')">QR för felanmälan</button>
    <button class="button" onclick="showQRCodeSection('lank')">QR för länkar</button>
  </div>

  <section id="qr-section" class="form-group hidden">
    <textarea id="textbox" class="textarea" rows="10" placeholder="Ange text eller länkar per rad..."></textarea>
    <button id="generate-button" class="button" onclick="generateQRCode()">Generera QR-koder</button>

    <div id="qrcode" class="qr-output"></div>

    <div class="horizontal-tools">
      <button id="download-button" class="button" onclick="downloadAllQRCodes()">Ladda ned alla QR-koder</button>
      <button id="download-docx-button" class="button" onclick="downloadAllDocx()">Ladda ned som DOCX</button>
    </div>
  </section>
</main>

<?php include '../../includes/layout-end.php'; ?>

<!-- Externa bibliotek -->
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.5/jszip.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/docx@7.0.0/build/index.js"></script>

<!-- Lokala skript -->
<script src="script.js" defer></script>
