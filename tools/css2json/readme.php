<?php

// tools/css2json/readme.php - v4
$title = 'Om CSS till JSON-verktyget';
$metaDescription = 'Information om CSS till JSON-verktyget: syfte, funktioner, användning och exempel. Lär dig hur du konverterar CSS-filer till JSON-format direkt i webbläsaren.';
?>
<?php include '../../includes/layout-start.php'; ?>



  <article class="kort__innehall readme">
    <h2>Syfte</h2>
    <p>
      CSS till JSON-verktyget är utvecklat för att snabbt och enkelt konvertera en eller flera CSS-filer till JSON-format direkt i webbläsaren. Det förenklar vidare bearbetning, analys eller import av CSS-data i andra system och verktyg.
    </p>

    <h2>Funktioner</h2>
    <ul>
      <li>Ladda upp en eller flera CSS-filer via ett enkelt formulär</li>
      <li>Konvertera CSS-regler till strukturerad JSON</li>
      <li>Ladda ner resultatet som en JSON-fil</li>
      <li>Responsiv design och tillgänglighetsanpassade kontroller</li>
    </ul>

    <h2>Användning</h2>
    <ol>
      <li>Klicka på <strong>Välj CSS-fil(er)</strong> och ladda upp en eller flera <code>.css</code>-filer.</li>
      <li>Tryck på <strong>Konvertera</strong> för att omvandla filerna till JSON.</li>
      <li>Resultatet visas direkt på sidan. Du kan ladda ner det som en <code>.json</code>-fil.</li>
      <li>Knappen <strong>Rensa</strong> återställer formuläret och resultatet.</li>
    </ol>

    <h2>Exempel</h2>
    <pre class="terminal-output">
/* Exempel på CSS */
.knapp {
  background: #007acc;
  color: #fff;
}

/* Konverterat till JSON */
{
  ".knapp": {
    "background": "#007acc",
    "color": "#fff"
  }
}
    </pre>

    <section id="markdownExampleSection" class="markdown-example-section">
      <h2>Kodexempel</h2>
      <div id="markdownExampleContainer"></div>
    </section>
    <!--
      För att visa kodexempel, inkludera följande script och anpassa kodblocket:
      <script type="module">
        import { renderMarkdownExample } from '/js/markdownExample.js';
        const exampleCode = `
\`\`\`json
{
  ".knapp": {
    "background": "#007acc",
    "color": "#fff"
  }
}
\`\`\`
        `;
        renderMarkdownExample('markdownExampleContainer', exampleCode);
      </script>
    -->
    <h2>Teknisk information</h2>
    <ul>
      <li>All konvertering sker lokalt i webbläsaren – ingen data skickas till servern.</li>
      <li>Verktyget använder JavaScript för filhantering och konvertering.</li>
      <li>Ingen extern API eller tredjepartsbibliotek krävs för grundfunktionalitet.</li>
    </ul>

    <h2>Tips & fallgropar</h2>
    <ul>
      <li>Stora eller komplexa CSS-filer kan ta längre tid att konvertera.</li>
      <li>Endast giltiga CSS-regler konverteras – kommentarer och ogiltig syntax ignoreras.</li>
      <li>Resultatet är avsett för vidare bearbetning, inte för att återskapa original-CSS.</li>
    </ul>

    <p class="mt-1">
      <a href="index.php" class="knapp" data-tippy-content="Tillbaka till verktyget">Tillbaka till verktyget</a>
    </p>
  </article>
</main>

<?php include '../../includes/layout-end.php';
