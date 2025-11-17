<!-- impex_map_help.php - Hjälpsida för karta & terminalutskrift -->
<!DOCTYPE html>
<html lang="sv">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hjälp - Kartplot och terminalutskrift</title>
  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-PGYPYWZ1L1"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'G-PGYPYWZ1L1');
  </script>
  <link rel="stylesheet" href="https://mackan.eu/verktyg/assets/css/reset.css">
  <link rel="stylesheet" href="https://mackan.eu/verktyg/assets/css/variables.css">
  <link rel="stylesheet" href="https://mackan.eu/verktyg/assets/css/typography.css">
  <link rel="stylesheet" href="https://mackan.eu/verktyg/assets/css/layout.css">
  <link rel="stylesheet" href="https://mackan.eu/verktyg/assets/css/components.css">
  <link rel="stylesheet" href="https://mackan.eu/verktyg/assets/css/utilities.css">
  <style>
    main {
      max-width: 800px;
      margin: auto;
      padding: 2rem;
    }
    code {
      background: #f4f4f4;
      padding: 2px 6px;
      border-radius: 4px;
      font-family: monospace;
    }
    pre {
      background: #111;
      color: #00ff88;
      padding: 1rem;
      border-radius: 6px;
      overflow-x: auto;
    }
  </style>
</head>
<body>
  <header class="header">
    <div class="container">
      <h1 class="page-title">Hjälp: Kartverktyg & Terminalutskrift</h1>
    </div>
  </header>
  <main>
    <p>Detta verktyg låter dig klicka i en karta för att få koordinater, adress, höjddata och rita upp fågelvägssträckor. All data loggas även i en terminal-liknande ruta.</p>

    <h2>1. Klicka i kartan</h2>
    <p>När du klickar på en plats i kartan händer detta:</p>
    <ul>
      <li>Latitud och longitud fångas</li>
      <li>Höjd hämtas från API</li>
      <li>Adressuppgifter hämtas automatiskt</li>
      <li>En markör visas i kartan</li>
      <li>En ny rad läggs till i tabellen</li>
      <li>En terminalrad visas med kort info</li>
    </ul>

    <h2>2. Avstånd och rutter</h2>
    <p>När du klickat minst 2 punkter ritas en linje mellan dem och avstånd beräknas automatiskt.</p>
    <ul>
      <li>Sträcka visas per punktpar (t.ex. <code>8.31 km</code>)</li>
      <li>Totalsumma visas under tabellen (t.ex. <code>3.23 mil</code>)</li>
    </ul>

    <h2>3. Terminalutskrift</h2>
    <p>Alla klick loggas i en "terminal" under kartan, exempel:</p>
    <pre>
▶ 59.33012, 18.06892 | 32.10 m | Drottninggatan 1 | 111 51 Stockholm | 1.20 km
▶ 59.33456, 18.06101 | 34.92 m | Kungsgatan 45 | 111 22 Stockholm | 900.25 m
    </pre>

    <h2>4. Kopiera data</h2>
    <p>Tryck på <code>Kopiera data</code>-knappen för att kopiera terminalutskriften till urklipp. Du får en bekräftelse via ett "toast"-meddelande i nedre hörnet.</p>

    <h2>5. Rensa</h2>
    <p>Knappen <code>Rensa tabell</code> rensar:</p>
    <ul>
      <li>Tabell</li>
      <li>Terminalutskrift</li>
      <li>Markörer</li>
      <li>Ritade linjer</li>
    </ul>

    <h2>6. Klistra in koordinater</h2>
    <p>Under rutan <strong>"Klistra in koordinater"</strong> kan du lägga in flera punkter samtidigt:</p>
    <pre>
59.3293, 18.0686
59.3300, 18.0690
    </pre>
    <p>Varje rad tolkas som <code>latitud, longitud</code>. Verktyget tolkar, ritar upp, och hämtar data automatiskt när du trycker på <code>Konvertera</code>.</p>

    <h2>7. Importera fil</h2>
    <p>Du kan ladda upp en fil (.txt eller .csv) med koordinater på samma sätt. Formatet är samma som ovan:</p>
    <pre>
59.3254, 18.0567
59.3281, 18.0721
    </pre>
    <p>Klicka på <code>Importera fil</code> och sedan <code>Ladda koordinater</code> för att visualisera punkterna.</p>

    <h2>8. Hämta adresser</h2>
    <p>Om du importerat punkter manuellt eller via fil, kan du trycka på <code>Hämta adresser</code> för att slå upp adressinformation i efterhand.</p>

    <h2>9. Exportera</h2>
    <p>Exportfunktioner låter dig ladda ner koordinater som:</p>
    <ul>
      <li><strong>CSV</strong> – Enkelt tabellformat</li>
      <li><strong>GeoJSON</strong> – För GIS-system</li>
    </ul>

    <p>Vill du veta mer om hur API:er fungerar, importformat eller andra funktioner? Läs <a href="help1.php">denna hjälpsida</a>.</p>

    <p><a href="impex_map.php">← Tillbaka till verktyget</a></p>
  </main>
</body>
</html>
