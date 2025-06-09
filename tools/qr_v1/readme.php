<?php
// tools/qr_v1/readme.php - v2
$title = 'Dokumentation – QR Code Generator';
include '../../includes/layout-start.php';
?>

<main class="container">
  <section class="page-title">


  <article class="card">
    <h2>Syfte</h2>
    <p>
      Detta verktyg genererar QR-koder utifrån inmatad text eller länkar. Det är användbart för att snabbt skapa enskilda eller flera QR-koder för exempelvis informationsskyltar, digitala länkar, felanmälan eller nedladdningsresurser.
    </p>
  </article>

  <article class="card">
    <h2>Funktioner</h2>
    <ul>
      <li>Två lägen: "QR för felanmälan" och "QR för länkar"</li>
      <li>Textarea för inmatning av text/länkar, en per rad</li>
      <li>Generering av enskilda QR-koder i visuell vy</li>
      <li>Exportfunktion:
        <ul>
          <li>Hämta samtliga QR-koder som PNG-filer (zippat)</li>
          <li>Ladda ned alla QR-koder som DOCX (för t.ex. utskrifter)</li>
        </ul>
      </li>
    </ul>
  </article>

  <article class="card">
    <h2>Användning</h2>
    <ol>
      <li>Klicka på något av knapparna för att välja typ av QR-sektion</li>
      <li>Fyll i varje rad med den text eller URL du vill koda</li>
      <li>Klicka på <strong>Generera QR-koder</strong></li>
      <li>QR-koder visas direkt på sidan</li>
      <li>Välj exportmetod: PNG (zippad) eller DOCX</li>
    </ol>
  </article>

  <article class="card">
    <h2>Teknik</h2>
    <ul>
      <li><code>qrcode.min.js</code> används för generering av QR-grafik</li>
      <li><code>html2canvas</code> för omvandling till bild</li>
      <li><code>JSZip</code> för nedladdning som ZIP</li>
      <li><code>docx.js</code> för Word-export</li>
      <li>Lokalt skript: <code>script.js</code></li>
    </ul>
  </article>

  <article class="card">
    <h2>Stil & design</h2>
    <ul>
      <li>Bygger på Mackan.eu:s centrala CSS: <code>reset.css</code>, <code>layout.css</code>, <code>components.css</code>, <code>utilities.css</code></li>
      <li>Temaväxling stöds via global <code>theme-toggle.js</code></li>
      <li>Responsiv och kompakt layout</li>
    </ul>
  </article>
</main>

<?php include '../../includes/layout-end.php'; ?>
