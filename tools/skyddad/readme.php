<?php
// tools/skyddad/readme.php - v1
$title = 'Om Skyddad';
$metaDescription = 'Lär dig hur Skyddad fungerar, vilka säkerhetsnivåer som används, och hur du skyddar dina hemliga texter via engångslänkar.';
?>
<?php include '../../includes/layout-start.php'; ?>

<main class="container">
  <h1 class="title">
    <?= $title ?>
    <?php include '../../includes/back-link.php'; ?>
  </h1>

  <article class="card readme">
    <h2>Syfte</h2>
    <p><strong>Skyddad</strong> är ett verktyg för att skapa och dela hemliga texter via <strong>engångslänkar</strong>. Texten är krypterad, länken är omöjlig att gissa, och informationen tas bort automatiskt efter visning.</p>

    <h2>Hur det fungerar</h2>
    <ol>
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

    <h2>Säkerhetsfunktioner</h2>
    <ul>
      <li>🔐 <strong>CSRF-skydd</strong> för att förhindra externa POST-attacker.</li>
      <li>🔑 <strong>HMAC-signatur</strong> för länken förhindrar manipulation.</li>
      <li>🔏 <strong>AES-256-kryptering</strong> gör att text aldrig lagras i klartext.</li>
      <li>🗑 <strong>Självförstörelse</strong> sker efter första visning.</li>
      <li>⌛ <strong>24h gräns</strong> för alla hemligheter.</li>
    </ul>

    <h2>Exempel</h2>
    <pre class="terminal-output">
Text: "Min API-nyckel till staging: xyz123"
Genererad länk: https://mackan.eu/tools/skyddad/visa.php?id=...

⇨ När någon klickar på länken visas texten exakt en gång, sedan tas allt bort.
    </pre>

    <h2>Status</h2>
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
      </tbody>
    </table>

    <h2>Förslag på framtida utveckling</h2>
    <ul>
      <li>🧹 <strong>Cron-städning</strong>: ta bort gamla hemligheter automatiskt</li>
      <li>⏱ <strong>Rate limiting</strong>: begränsa försök per IP/min</li>
      <li>📊 <strong>Statistikpanel</strong>: få översikt över användning</li>
      <li>📂 <strong>Export som .txt</strong>: ladda ner hemlighet</li>
      <li>📄 <strong>Loggning (anonym)</strong>: se när visning skett</li>
      <li>📏 <strong>QR-kod till länk</strong>: för mobil/skrivare</li>
      <li>🎨 <strong>Temastöd</strong>: Mörkt/ljust läge för UX</li>
         <li>🎨 <strong>statistik</strong>: antal skapade</li>

    </ul>
  </article>
</main>

<?php include '../../includes/layout-end.php'; ?>
