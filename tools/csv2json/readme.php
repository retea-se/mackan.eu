<?php
// tools/csv2json/readme.php - v2
$title = 'Dokumentation – CSV till JSON';
include '../../includes/layout-start.php';
?>

<main class="container">
 

  <article class="card">
    <h2>Vad är CSV till JSON?</h2>
    <p>Denna applikation gör det enkelt att konvertera data från CSV-format till JSON-format. CSV (Comma Separated Values) är ett vanligt format för att lagra tabulär data, medan JSON (JavaScript Object Notation) är ett strukturerat dataformat som är lämpligt för webbtillämpningar.</p>
  </article>

  <article class="card">
    <h2>Förklaringar av inställningar</h2>

    <h3>Separator</h3>
    <p>En separator används för att skilja kolumner i din CSV-data. Alternativen inkluderar:</p>
    <ul>
      <li><strong>Auto-detektera:</strong> Försöker automatiskt identifiera vilken separator som används.</li>
      <li><strong>Kommatecken (,):</strong> Vanligast i engelska CSV-filer.</li>
      <li><strong>Semikolon (;):</strong> Vanligast i europeiska CSV-filer.</li>
      <li><strong>Tab:</strong> Används ofta i tab-separerade filer (TSV).</li>
    </ul>

    <h3>Tolka nummer</h3>
    <p>Om denna kryssruta är aktiverad kommer numeriska värden i CSV-filen att konverteras till riktiga siffror i JSON (t.ex. <code>"123"</code> blir <code>123</code>).</p>

    <h3>Tolka JSON-fält</h3>
    <p>Aktivera denna inställning om du vill att text som redan är i JSON-format i CSV-filen ska tolkas som JSON (t.ex. <code>{"key":"value"}</code>).</p>

    <h3>Transponera data</h3>
    <p>Om denna kryssruta är aktiverad kommer rader och kolumner i din CSV att byta plats innan konverteringen sker.</p>

    <h3>Utdataformat</h3>
    <p>Välj hur den konverterade JSON-datan ska struktureras:</p>
    <ul>
      <li><strong>Array:</strong> JSON kommer att bestå av en lista med objekt (t.ex. <code>[{"kolumn1":"värde1"}]</code>).</li>
      <li><strong>Hash:</strong> JSON kommer att struktureras som ett objekt med rader indexerade med nycklar (t.ex. <code>{"rad1":{"kolumn1":"värde1"}}</code>).</li>
    </ul>

    <h3>Minifiera JSON</h3>
    <p>Om denna kryssruta är aktiverad tas radbrytningar och mellanrum bort i den genererade JSON-filen, vilket gör den mer kompakt.</p>
  </article>

  <article class="card">
    <h2>Hur fungerar det?</h2>
    <p>Följ dessa steg för att använda applikationen:</p>
    <ol>
      <li>Ladda upp din CSV-fil eller klistra in datan i textområdet.</li>
      <li>Justera inställningarna efter behov.</li>
      <li>Klicka på <strong>Konvertera till JSON</strong> för att skapa din JSON-data.</li>
      <li>Visa resultatet i textområdet för JSON.</li>
      <li>Välj att ladda ner JSON-filen eller kopiera datan till urklipp.</li>
    </ol>
  </article>

  <article class="card">
    <h2>Versionsstruktur</h2>

    <h3>Version 1 – <code>index.php</code></h3>
    <ul>
      <li>Stöd för CSV-ingång via fil eller textarea</li>
      <li>Val för minifierad JSON och transponering</li>
      <li>Manuella kolumnfilter</li>
      <li>Live preview samt JSON-utdata med nedladdning/kopiering</li>
      <li><strong>JS:</strong> <code>script.js</code> + <code>tools.js</code></li>
    </ul>

    <h3>Version 2 – <code>index_v2.php</code></h3>
    <ul>
      <li>Data presenteras med DataTables</li>
      <li>Förhandsgranskning, kolumnfilter, statistik och JSON-export</li>
      <li><strong>JS:</strong> <code>avancerad.js</code></li>
    </ul>

    <h3>Version 3 – <code>index_v3.php</code></h3>
    <ul>
      <li>Stöd för flera format: CSV, JSON, XLSX</li>
      <li>Konvertering mellan format (import/export)</li>
      <li>Inbyggd validering av rader och kolumner</li>
      <li>Statistikpanel och visualisering via Chart.js</li>
      <li><strong>JS:</strong> <code>avancerad2.js</code></li>
    </ul>
  </article>

  <article class="card">
    <h2>JavaScript-filer</h2>
    <ul>
      <li><code>script.js</code> – grundfunktioner för uppladdning, parsing och export</li>
      <li><code>tools.js</code> – kolumnfilter och interaktioner (v1)</li>
      <li><code>avancerad.js</code> – hantering av DataTables och exportfunktioner (v2)</li>
      <li><code>avancerad2.js</code> – parsing av flera format, konvertering, diagram, statistik (v3)</li>
    </ul>
  </article>

  <article class="card">
    <h2>CSS och layout</h2>
    <ul>
      <li>Formulär och komponenter använder <code>tools.css</code> och <code>components.css</code></li>
      <li>Temaväxling och färgstöd från <code>theme.css</code></li>
      <li>Responsivitet via <code>layout.css</code></li>
    </ul>
  </article>

  <article class="card">
    <h2>Vanliga frågor</h2>
    <h3>Kan jag konvertera stora CSV-filer?</h3>
    <p>Ja, men bearbetningstiden kan öka beroende på filstorleken. För mycket stora filer rekommenderas att använda ett dedikerat program.</p>

    <h3>Vad gör jag om min JSON ser konstig ut?</h3>
    <p>Dubbelkolla att du har valt rätt separator och att din CSV-fil är korrekt formaterad. Om problem kvarstår, ring en vän.</p>
  </article>
</main>

<?php include '../../includes/layout-end.php'; ?>
