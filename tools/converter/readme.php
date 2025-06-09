<!-- tools/converter/readme.php - v3 -->
<?php
$title = 'Om JSON Converter';
$metaDescription = 'Konvertera, formattera, validera, reparera och exportera JSON, CSV och XLSX direkt i webbläsaren med stöd för filuppladdning och avancerade verktyg.';
?>
<?php include '../../includes/layout-start.php'; ?>

<main class="container">
  <h1 class="title">
    <?= $title ?>
    <a href="index.php" class="info-link-floating" title="Tillbaka till verktyget">&larr;</a>
  </h1>

  <article class="card readme">
    <h2>Syfte</h2>
    <p>Verktyget JSON Converter samlar flera kraftfulla funktioner för att arbeta med JSON-, CSV- och XLSX-data i ett reklambefriat gränssnitt direkt i webbläsaren.</p>

    <h2>Funktioner per flik</h2>
    <ul>
      <li><strong>CSV till JSON</strong>: Konvertera inklistrad tabell (t.ex. från Excel) till strukturerad JSON med kolumnfilter, statistik och transponering.</li>
      <li><strong>Formatter</strong>: Formatera JSON med JSONEditor (trädvy, kodvy), samt beautify/minify.</li>
      <li><strong>Validator</strong>: Kontrollera att JSON är syntaktiskt korrekt (via <code>JSON.parse()</code>).</li>
      <li><strong>Fixer</strong>: Rensa och laga felaktig JSON automatiskt (t.ex. trailing commas, enkla citationstecken).</li>
      <li><strong>Utilities</strong>: Småverktyg för:
        <ul>
          <li>🔁 <code>URL Encode</code> / <code>Decode</code></li>
          <li>🔒 <code>Escape</code> / <code>Unescape</code></li>
          <li>🧠 <code>JSON.stringify</code> – konvertera JS-objekt till JSON-sträng</li>
        </ul>
      </li>
      <li><strong>Converter</strong>: Filuppladdning och konvertering mellan JSON, CSV och XLSX med exportmöjligheter och avancerad datatypstolkning.</li>
    </ul>

    <h2>Knappar – vad de gör</h2>
    <ul>
      <li><code>Konvertera</code> (i flera flikar): Kör transformation baserat på aktuell inmatning.</li>
      <li><code>Kopiera</code>: Lägger resultatet på urklipp.</li>
      <li><code>Beautify</code>: Indenterar JSON med radbrytningar.</li>
      <li><code>Minify</code>: Tar bort radbrytningar och mellanrum i JSON.</li>
      <li><code>Validera</code>: Kontrollera att din JSON är korrekt.</li>
      <li><code>Försök reparera</code>: Laga vanlig JSON-syntax (felcitat, saknade nycklar etc.).</li>
      <li><code>Hämta</code>: Ladda in data från fil eller inklistring (Converter-fliken).</li>
      <li><code>Ladda ner</code>: Exportera data som JSON, CSV eller XLSX.</li>
    </ul>

    <h2>Exempel: CSV till JSON</h2>
    <pre class="terminal-output">
Produkt	ID	Pris
Mus	1001	149
Tangentbord	1002	399
Skärm	1003	1899

Resultat:
[
  { "Produkt": "Mus", "ID": "1001", "Pris": "149" },
  { "Produkt": "Tangentbord", "ID": "1002", "Pris": "399" },
  { "Produkt": "Skärm", "ID": "1003", "Pris": "1899" }
]
    </pre>

    <h2>Tips</h2>
    <ul>
      <li>All bearbetning sker lokalt i din webbläsare – ingen data skickas till servern.</li>
      <li>Du kan fritt klistra in från Excel, t.ex. rubrik + rader.</li>
      <li>Använd filuppladdningen för att snabbt ladda stora eller komplexa filer.</li>
      <li>Formatter-fliken erbjuder både kodvy och trädvy via JSONEditor.</li>
      <li>Utforska flikarna för olika verktyg och arbetsflöden.</li>
    </ul>
  </article>
</main>

<?php include '../../includes/layout-end.php'; ?>
