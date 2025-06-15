<?php

// tools/css2json/readme.php - v4
$title = 'Om CSS till JSON-verktyget';
$metaDescription = 'Information om CSS till JSON-verktyget: syfte, funktioner, användning och exempel. Lär dig hur du konverterar CSS-filer till JSON-format direkt i webbläsaren.';
?>
<?php include '../../includes/layout-start.php'; ?>

<main class="readme">
  <nav class="readme__back">
    <a href="index.php" class="readme__backlink" aria-label="Tillbaka till verktyget">
      <i class="fa-solid fa-arrow-left"></i> Tillbaka till verktyget
    </a>
  </nav>

  <h1 class="readme__title">CSS till JSON – Dokumentation</h1>

  <section class="readme__section">
    <h2 class="readme__subtitle">Syfte</h2>
    <p class="readme__text">
      CSS till JSON-verktyget är utvecklat för att snabbt och enkelt konvertera en eller flera CSS-filer till JSON-format direkt i webbläsaren. Det förenklar vidare bearbetning, analys eller import av CSS-data i andra system och verktyg.
    </p>
  </section>

  <section class="readme__section">
    <h2 class="readme__subtitle">Funktioner</h2>
    <ul class="readme__list">
      <li>Ladda upp en eller flera CSS-filer via ett enkelt formulär</li>
      <li>Konvertera CSS-regler till strukturerad JSON</li>
      <li>Ladda ner resultatet som en JSON-fil</li>
      <li>Responsiv design och tillgänglighetsanpassade kontroller</li>
    </ul>
  </section>

  <section class="readme__section">
    <h2 class="readme__subtitle">Användning</h2>
    <ol class="readme__list">
      <li>Klicka på <strong>Välj CSS-fil(er)</strong> och ladda upp en eller flera <code>.css</code>-filer.</li>
      <li>Tryck på <strong>Konvertera</strong> för att omvandla filerna till JSON.</li>
      <li>Resultatet visas direkt på sidan. Du kan ladda ner det som en <code>.json</code>-fil.</li>
      <li>Knappen <strong>Rensa</strong> återställer formuläret och resultatet.</li>
    </ol>
  </section>

  <section class="readme__section">
    <h2 class="readme__subtitle">Exempel</h2>
    <div class="readme__codeblock" aria-label="CSS-exempel">
      <button class="readme__codecopy" title="Kopiera kod" aria-label="Kopiera kod">
        <i class="fa-solid fa-copy"></i>
      </button>
      <pre><code>/* Exempel på CSS */
.knapp {
  background: #007acc;
  color: #fff;
}</code></pre>
    </div>
    <div class="readme__codeblock" aria-label="JSON-exempel">
      <button class="readme__codecopy" title="Kopiera kod" aria-label="Kopiera kod">
        <i class="fa-solid fa-copy"></i>
      </button>
      <pre><code>{
  ".knapp": {
    "background": "#007acc",
    "color": "#fff"
  }
}</code></pre>
    </div>
  </section>

  <section class="readme__section">
    <h2 class="readme__subtitle">Teknisk information</h2>
    <ul class="readme__list">
      <li>All konvertering sker lokalt i webbläsaren – ingen data skickas till servern.</li>
      <li>Verktyget använder JavaScript för filhantering och konvertering.</li>
      <li>Ingen extern API eller tredjepartsbibliotek krävs för grundfunktionalitet.</li>
    </ul>
  </section>

  <section class="readme__section">
    <h2 class="readme__subtitle">Tips &amp; fallgropar</h2>
    <ul class="readme__list">
      <li>Stora eller komplexa CSS-filer kan ta längre tid att konvertera.</li>
      <li>Endast giltiga CSS-regler konverteras – kommentarer och ogiltig syntax ignoreras.</li>
      <li>Resultatet är avsett för vidare bearbetning, inte för att återskapa original-CSS.</li>
    </ul>
  </section>

  <footer class="readme__meta">© Mackan.eu – Dokumentation uppdaterad 2025-06-15</footer>
</main>

<script src="/js/readme-codecopy.js"></script>
<?php include '../../includes/layout-end.php';
