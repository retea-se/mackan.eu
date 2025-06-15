<?php

// tools/kortlank/readme.php - v1.1 (2025-06-15)
// Uppdaterad med readme.css-klasser (BEM, luft, tillgänglighet)
$title = 'Om Kortlänk';
$metaDescription = 'Lär dig allt om verktyget Kortlänk: syfte, användningsområden, historik och nördiga fakta. Skapa och hantera korta länkar enkelt.';
?>
<?php include '../../includes/layout-start.php'; ?>

<main class="readme">
  <nav class="readme__back">
    <a href="skapa-lank.php" class="readme__backlink" aria-label="Tillbaka till Kortlänk-verktyget">
      <i class="fa-solid fa-arrow-left"></i> Tillbaka till verktyget
    </a>
  </nav>

  <h1 class="readme__title">Kortlänk – Dokumentation &amp; API</h1>

  <section class="readme__section">
    <h2 class="readme__subtitle">Syfte</h2>
    <p class="readme__text">
      Kortlänk är ett verktyg för att skapa, hantera och dela korta webbadresser (URL:er). Det löser problemet med långa, svårhanterliga länkar – särskilt i e-post, sociala medier och tryckt material. Målgruppen är både utvecklare, kommunikatörer och alla som vill förenkla länkdelning.
    </p>
  </section>

  <section class="readme__section">
    <h2 class="readme__subtitle">Historik &amp; Nördiga Fakta</h2>
    <div class="readme__info" role="status" aria-label="Info">
      <i class="fa-solid fa-info-circle" aria-hidden="true"></i>
      Idén med kortlänkar föddes redan på 2000-talet när tjänster som TinyURL och bit.ly blev populära. Kortlänk bygger på samma princip: en unik, kort kod pekar mot en längre destination. Verktyget har använts i interna projekt sedan 2022 och har optimerats för snabb omdirigering och enkel administration.
    </div>
    <ul class="readme__list">
      <li>Varje kortlänk genereras med en hash eller sekventiell ID-sträng.</li>
      <li>Stöd för statistik och utgångsdatum kan enkelt läggas till.</li>
      <li>Edge case: Om två användare försöker skapa samma kortlänk samtidigt hanteras kollisioner automatiskt.</li>
    </ul>
  </section>

  <section class="readme__section">
    <h2 class="readme__subtitle">Typiska användningsfall</h2>
    <ul class="readme__list">
      <li>Dela långa länkar i SMS eller på visitkort.</li>
      <li>Spåra klick och engagemang i kampanjer.</li>
      <li>Gör QR-koder mer läsbara genom att använda korta länkar som mål.</li>
    </ul>
  </section>

  <section class="readme__section">
    <h2 class="readme__subtitle">Användning</h2>
    <p class="readme__text">
      Klistra in din långa URL i fältet, klicka på <strong>Skapa kortlänk</strong> och få en kort, lättdelad adress. Du kan när som helst radera eller uppdatera dina länkar.
    </p>
  </section>

  <section class="readme__section">
    <h2 class="readme__subtitle">Exempel</h2>
    <div class="readme__codeblock" aria-label="Exempel på kortlänk">
      <button class="readme__codecopy" aria-label="Kopiera kod" title="Kopiera kod">
        <i class="fa-solid fa-copy" aria-hidden="true"></i>
      </button>
      <pre><code>Input: https://www.example.com/produkter/nyheter/2025/06/15/lansering?ref=nyhetsbrev
Resultat: https://mackan.eu/s/abc123</code></pre>
    </div>
  </section>

  <section class="readme__section">
    <h2 class="readme__subtitle">Kodexempel</h2>
    <div class="readme__codeblock" aria-label="Kodexempel">
      <button class="readme__codecopy" aria-label="Kopiera kod" title="Kopiera kod">
        <i class="fa-solid fa-copy" aria-hidden="true"></i>
      </button>
      <pre><code>
# Skapa en kortlänk via terminalen (exempel med curl)
curl -X POST -d "url=https://www.example.com" https://mackan.eu/tools/kortlank/api/shorten.php
  </code></pre>
    </div>
  </section>

  <section class="readme__section">
    <h2 class="readme__subtitle">Fun facts</h2>
    <ul class="readme__list">
      <li>En kortlänk kan vara så kort som 6 tecken – det ger över 56 miljarder möjliga kombinationer (base62).</li>
      <li>Kortlänk kan enkelt integreras med QR-kod-generatorer.</li>
      <li>Verktyget är inspirerat av RFC 3986 (URI Generic Syntax).</li>
    </ul>
  </section>

  <section class="readme__section">
    <h2 class="readme__subtitle">API för att skapa kortlänkar</h2>
    <p class="readme__text">
      Kortlänk erbjuder ett öppet API för att skapa korta länkar programmatiskt. Alla kan använda API:et utan autentisering, men för att skydda mot missbruk finns en begränsning på antalet skapade kortlänkar per IP-adress.
    </p>
    <h3 class="readme__subtitle" style="font-size:1.1em;">Skapa en kortlänk</h3>
    <ul class="readme__list">
      <li>
        <strong>Endpoint:</strong>
        <code>https://mackan.eu/tools/kortlank/api/shorten.php</code>
      </li>
      <li>
        <strong>Metod:</strong> <code>POST</code>
      </li>
      <li>
        <strong>Parametrar:</strong>
        <ul class="readme__list">
          <li><code>url</code> <em>(krävs)</em> – Den långa URL:en som ska förkortas</li>
          <li><code>custom_alias</code> <em>(valfri)</em> – Egen kortkod (om ledig)</li>
          <li><code>description</code> <em>(valfri)</em> – Beskrivning av länken</li>
          <li><code>password</code> <em>(valfri)</em> – Lösenordsskyddad länk</li>
        </ul>
      </li>
    </ul>
    <h3 class="readme__subtitle" style="font-size:1.1em;">Rate limiting</h3>
    <div class="readme__warning" role="alert" aria-label="Varning">
      <i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i>
      För att förhindra missbruk kan varje IP-adress skapa maximalt <strong>200 kortlänkar per dygn</strong> via API:et. Om gränsen överskrids returneras ett felmeddelande och statuskod 429.
    </div>
    <h3 class="readme__subtitle" style="font-size:1.1em;">Exempel på anrop</h3>
    <div class="readme__codeblock" aria-label="Exempel på curl-anrop">
      <button class="readme__codecopy" aria-label="Kopiera kod" title="Kopiera kod">
        <i class="fa-solid fa-copy" aria-hidden="true"></i>
      </button>
      <pre><code>curl -X POST -d "url=https://www.example.com" https://mackan.eu/tools/kortlank/api/shorten.php
</code></pre>
    </div>
    <h3 class="readme__subtitle" style="font-size:1.1em;">Exempel på svar</h3>
    <div class="readme__codeblock" aria-label="Exempel på API-svar">
      <button class="readme__codecopy" aria-label="Kopiera kod" title="Kopiera kod">
        <i class="fa-solid fa-copy" aria-hidden="true"></i>
      </button>
      <pre><code>{
  "success": true,
  "short_url": "https://mackan.eu/s/abc123",
  "message": "Kortlänk skapad"
}</code></pre>
    </div>
    <h3 class="readme__subtitle" style="font-size:1.1em;">Exempel på felmeddelande vid rate limit</h3>
    <div class="readme__codeblock" aria-label="Exempel på felmeddelande">
      <button class="readme__codecopy" aria-label="Kopiera kod" title="Kopiera kod">
        <i class="fa-solid fa-copy" aria-hidden="true"></i>
      </button>
      <pre><code>HTTP/1.1 429 Too Many Requests
{
  "success": false,
  "message": "Rate limit: För många skapade kortlänkar från denna IP-adress det senaste dygnet. Försök igen senare."
}</code></pre>
    </div>
    <p class="readme__text">
      <strong>OBS:</strong> API:et är öppet för alla men begränsat för att skydda mot missbruk. Kontakta oss om du behöver högre gräns för ett projekt.
    </p>
  </section>

  <footer class="readme__meta">© Mackan.eu – Dokumentation uppdaterad 2025-06-15</footer>
</main>

<script src="/js/readme-codecopy.js"></script>
<?php include '../../includes/layout-end.php';
