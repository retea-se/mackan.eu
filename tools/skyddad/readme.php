<?php
// tools/skyddad/readme.php - v6
// git commit: Lagt till info om kortlänksfunktion och länk till Kortlänk-verktyget

$title = 'Om Skyddad';
$metaDescription = 'Lär dig hur Skyddad fungerar, vilka säkerhetsnivåer som används, och hur du skyddar dina hemliga texter via engångslänkar.';
?>
<?php include '../../includes/layout-start.php'; ?>

<main class="readme">
  <h1 class="readme__title">
    <?= $title ?>
    <?php include '../../includes/back-link.php'; ?>
  </h1>

  <article class="readme__section">
    <h2 class="readme__subtitle">Syfte</h2>
    <p class="readme__text"><strong>Skyddad</strong> är ett verktyg för att skapa och dela hemliga texter via <strong>engångslänkar</strong>. Texten är krypterad, länken är omöjlig att gissa, och informationen tas bort automatiskt efter visning.</p>
  </article>

  <article class="readme__section">
    <h2 class="readme__subtitle">Hur det fungerar</h2>
    <ol class="readme__list">
      <li>Du skriver in en text du vill dela.</li>
      <li>Texten krypteras lokalt med <strong>AES-256-CBC</strong> och sparas i databasen.</li>
      <li>En länk med unik ID och signatur (HMAC) skapas.</li>
      <li>Du delar länken till mottagaren.</li>
      <li>När någon öppnar länken:
        <ul>
          <li>Servern validerar signaturen.</li>
          <li>Om giltig: dekrypterar och visar texten.</li>
          <li>Innehållet raderas från databasen direkt.</li>
        </ul>
      </li>
    </ol>
  </article>

  <article class="readme__section">
    <h2 class="readme__subtitle">Nyhet: Kortlänkar för enkel delning</h2>
    <div class="readme__info">
      <i class="fa-solid fa-link"></i>
      Nu skapas automatiskt en <strong>kortlänk</strong> när du delar en hemlig text. Kortlänken är lättare att kopiera, dela och använda – och fungerar precis som den ursprungliga engångslänken.
    </div>
    <p class="readme__text">
      Kortlänkar genereras via verktyget <a href="https://mackan.eu/tools/kortlank/" target="_blank" rel="noopener">Kortlänk</a>. Du kan läsa mer om hur kortlänkar fungerar och skapa egna på <a href="https://mackan.eu/tools/kortlank/" target="_blank" rel="noopener">mackan.eu/tools/kortlank/</a>.
    </p>
    <div class="readme__codeblock">
      <button class="readme__codecopy" title="Kopiera kod">
        <i class="fa-solid fa-copy"></i>
      </button>
      <pre><code>Exempel på kortlänk:
https://mackan.eu/m/abc12345
</code></pre>
    </div>
  </article>

  <article class="readme__section">
    <h2 class="readme__subtitle">Säkerhetsfunktioner</h2>
    <ul class="readme__list">
      <li>🔐 <strong>CSRF-skydd</strong> för att förhindra externa POST-attacker.</li>
      <li>🔑 <strong>HMAC-signatur</strong> för länken förhindrar manipulation.</li>
      <li>🔏 <strong>AES-256-kryptering</strong> gör att text aldrig lagras i klartext.</li>
      <li>🗑 <strong>Självförstörelse</strong> sker efter första visning.</li>
      <li>⌛ <strong>24h gräns</strong> för alla hemligheter.</li>
    </ul>
  </article>

  <article class="readme__section">
    <h2 class="readme__subtitle">Förkortningar</h2>
    <div class="table-wrapper">
      <table class="table">
        <thead><tr><th>Förkortning</th><th>Beskrivning</th></tr></thead>
        <tbody>
          <tr><td>CSRF</td><td>Cross-Site Request Forgery – skydd mot otillåtna formuläranrop</td></tr>
          <tr><td>HMAC</td><td>Hash-based Message Authentication Code – skyddar länkar från att manipuleras</td></tr>
          <tr><td>AES-256-CBC</td><td>Advanced Encryption Standard med 256-bitars nyckel och CBC-läge – stark kryptering</td></tr>
          <tr><td>IP</td><td>Internet Protocol – används för att visa varifrån en händelse kommer</td></tr>
          <tr><td>Cron</td><td>Automatiskt serverjobb som körs med jämna mellanrum</td></tr>
        </tbody>
      </table>
    </div>
  </article>

  <article class="readme__section">
    <h2 class="readme__subtitle">Exempel</h2>
    <pre class="terminal-output">
Text: "Min API-nyckel till staging: xyz123"
Genererad länk: https://mackan.eu/tools/skyddad/visa.php?id=...

⇨ När någon klickar på länken visas texten exakt en gång, sedan tas allt bort.
    </pre>
  </article>

  <article class="readme__section">
    <h2 class="readme__subtitle">Status</h2>
    <div class="table-wrapper">
      <table class="table">
        <thead><tr><th>Del</th><th>Vad det innebär</th><th>Status</th></tr></thead>
        <tbody>
          <tr><td>🔐 CSRF-skydd</td><td>Skyddar formulär mot extern manipulation</td><td>✔</td></tr>
          <tr><td>🔑 HMAC-token</td><td>Unik signatur i länken skyddar mot gissning</td><td>✔</td></tr>
          <tr><td>🔏 AES-256</td><td>Kryptering innan lagring</td><td>✔</td></tr>
          <tr><td>🗑 Självförstöring</td><td>Text tas bort efter visning</td><td>✔</td></tr>
          <tr><td>⌛ 24h giltighet</td><td>Automatisk utgång efter ett dygn</td><td>✔</td></tr>
          <tr><td>📁 Kodstruktur</td><td>Separata mappar för logik och mallar</td><td>✔</td></tr>
          <tr><td>🧪 Felvisning</td><td>PHP-errors visas i dev-läge</td><td>✔</td></tr>
          <tr><td>📊 Adminpanel</td><td>Visar antal skapade och visade texter</td><td>✔</td></tr>
          <tr><td>📈 Statistikdiagram</td><td>Stapeldiagram via ECharts</td><td>✔</td></tr>
        </tbody>
      </table>
    </div>
  </article>

  <article class="readme__section">
    <h2 class="readme__subtitle">Integritet</h2>
    <p class="readme__text">Skyddad skyddar din hemlighet. Ingen loggning, ingen spårning, ingen insyn. Vi sparar ingen metadata eller klartext. Händelser i systemet är anonyma och visas endast i form av statistik eller totalsiffror.</p>
  </article>

  <article class="readme__section">
    <h2 class="readme__subtitle">Förslag på framtida utveckling</h2>
    <ul class="readme__list">

      <li>📂 <strong>Export som .txt</strong>: Möjlighet att ladda ned texten lokalt</li>

      <li>🔁 <strong>Delningshistoria</strong>: Lista över egna skapade länkar (lokalt)</li>

      <li>🌐 <strong>Språkstöd</strong>: Fler språkversioner av gränssnittet</li>
      <li>📈 <strong>Avancerad adminpanel</strong>: Filter, export, fler vyer</li>
    </ul>
  </article>
</main>

<?php include '../../includes/layout-end.php'; ?>
