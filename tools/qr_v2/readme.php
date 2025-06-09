<?php
// tools/qr_v2/readme.php - v1
$title = 'Dokumentation – QR v2';
include '../../includes/layout-start.php';
?>

<main class="container">


  <article class="card">
    <h2>Syfte</h2>
    <p>
      QR v2 är ett avancerat verktyg för att generera QR-koder för flera typer av data, t.ex. text, URL, kontaktinformation, WiFi-inställningar och plats. Verktyget riktar sig till användare som behöver dynamisk formgenerering och förhandsgranskning direkt i webbläsaren.
    </p>
  </article>

  <article class="card">
    <h2>Funktioner</h2>
    <ul>
      <li>Stöd för 8 QR-typer: Text, URL, Kontakt (vCard), WiFi, E-post, SMS, Telefon, Plats</li>
      <li>Dynamisk formulärvisning beroende på val</li>
      <li>Automatisk temaväxling (ljus/mörk)</li>
      <li>Förhandsvisning av QR-kod</li>
      <li>Fallback till lokal <code>qrcode.min.js</code> vid behov</li>
    </ul>
  </article>

  <article class="card">
    <h2>Användning</h2>
    <ol>
      <li>Välj en typ av QR-kod genom att klicka på motsvarande knapp</li>
      <li>Fyll i de visade fälten beroende på QR-typ</li>
      <li>Klicka på <strong>Skapa QR-kod</strong></li>
      <li>Resultatet visas i <code>#qrPreview</code></li>
    </ol>
  </article>

  <article class="card">
    <h2>Teknik</h2>
    <ul>
      <li><code>qrcode.min.js</code> från CDN (eller lokal fallback)</li>
      <li><code>script.js</code> ansvarar för typval, formulärgenerering och QR-generering</li>
      <li>Temastöd via JS och <code>data-theme</code>-attribut</li>
    </ul>
  </article>

  <article class="card">
    <h2>Stil & design</h2>
    <ul>
      <li>Följer centrala CSS-regler från Mackan.eu: <code>reset.css</code>, <code>layout.css</code>, <code>components.css</code>, <code>typography.css</code></li>
      <li>Anpassade stilar för .info-circle och .theme-toggle är nu integrerade i komponenttemat</li>
      <li>Responsiv layout, anpassad för både desktop och mobil</li>
    </ul>
  </article>
</main>

<?php include '../../includes/layout-end.php'; ?>
