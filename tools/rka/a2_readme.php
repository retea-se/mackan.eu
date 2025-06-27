<?php
// tools/rka/a2_readme.php - v1 - Med projektets layout-system OCH all SEO
$title = 'README – Reservkraftkalkylator (diesel / HVO) med derating & tankprognos';
$metaDescription = 'Open-source verktyg för bränsleförbrukning, ekonomi & CO₂-analys av reservkraftaggregat. Inkluderar SMHI-baserad derating, lastprofil, provkörnings-simulering och 24-mån tankprognos.';
$metaKeywords = 'reservkraft, dieselelverk, HVO, EcoPar, bränslekalkylator, derating, ullage, sump, testkörning, tankprognos, CO2, MSB reservkraft';
$canonicalUrl = 'https://mackan.eu/tools/rka/a2_readme.php';

// Open Graph / Twitter specifika meta-värden
$ogTitle = 'Reservkraftkalkylator – komplett README';
$ogDescription = 'Beräknar L/h, CO₂ och tankprognos för diesel-/HVO-aggregat.';
$ogImage = 'og-rka-tool.jpg';
$ogUrl = 'https://mackan.eu/tools/rka/a2_readme.php';
$twitterCard = 'summary_large_image';

// Extra CSS som läggs till i head-sektionen
$extraCSS = '
<style>
  .readme {max-width:900px;margin:2rem auto;line-height:1.55}
  .readme h1,.readme h2,.readme h3{font-weight:700;margin:1.1em 0 .4em}
  .readme code,.readme pre{background:#f6f6f6;padding:.15em .35em;border-radius:4px}
  .readme table{border-collapse:collapse;width:100%}
  .readme th,.readme td{border:1px solid #ccc;padding:.4rem;text-align:center}
  .readme .alert{background:#ffe5e5;color:#b30000;font-weight:600}
  .readme a{color:#0366d6}
  .readme__info{background:#e7f3ff;border-left:4px solid #0366d6;padding:1rem;margin:1rem 0}
  .readme__warning{background:#fff3cd;border-left:4px solid #ffc107;padding:1rem;margin:1rem 0}
  .readme__quote{background:#f8f9fa;border-left:4px solid #6c757d;padding:1rem;margin:1rem 0;font-style:italic}
  .readme__back{margin-top:2rem;text-align:center}
  .readme__backlink{display:inline-block;padding:0.5rem 1rem;background:#007bff;color:white;text-decoration:none;border-radius:4px}
</style>';
?>
<?php include '../../includes/layout-start.php'; ?>

<main class="readme">
  <article class="readme__section">
    <!-- Befintlig HTML-kod behålls helt intakt här -->
    <header>
      <h1>Reservkraftkalkylator – README & teknisk dokumentation</h1>
      <p>
        Det här är den officiella dokumentationen till det webbaserade
        <strong>bränsle-, ekonomi- och CO₂-verktyg för reservkraftaggregat</strong>
        (diesel, HVO100, EcoPar). Verktyget täcker allt från momentana liter-per-timme-beräkningar
        till <em>SMHI-styrd derating</em>, avancerade lastprofiler och 24-månaders
        tankprognoser inklusive provkörningar.
      </p>
    </header>

    <!-- ===================================================== -->
    <h2>1&nbsp;· Mapp- & filstruktur</h2>

    <pre class="readme__codeblock"><code>rka/
├── a2.php             # Huvud-GUI med SMHI-dropdown, aktuell last & ekonomi
├── calc_engine.php    # Alla beräkningsfunktioner (derating, L/kWh, CO₂ …)
├── a2.js              # Export-funktioner och auto-submit
├── a2_readme.php      # Denna README-fil
</code></pre>

    <!-- ===================================================== -->
    <h2>2&nbsp;· Beroenden & versionskrav</h2>

    <table class="readme__table">
    <thead><tr><th>Lager</th><th>Teknik</th><th>Min-version</th><th>Kommentar</th></tr></thead>
    <tbody>
    <tr><td>Server-side</td><td>PHP</td><td>7.4</td><td>Ingen databas – all state i&nbsp;POST</td></tr>
    <tr><td>Browser</td><td>Chrome / Firefox / Edge</td><td>ES6</td><td>Vanilla JS, <code>&lt;template></code>, <code>fetch</code></td></tr>
    <tr><td>Export</td><td>SheetJS / jsPDF</td><td>Latest</td><td>Excel/PDF-export via CDN</td></tr>
    <tr><td>Tooltips</td><td>Tippy.js</td><td>6.x</td><td>Hover-förklaringar på alla fält</td></tr>
    </tbody></table>

    <!-- ===================================================== -->
    <h2>3&nbsp;· Beräkningsmotor (<code>calc_engine.php</code>)</h2>

    <h3>3.1 Bränsleschablon & faktorer</h3>
    <pre class="readme__codeblock"><code>const BASE_SC = 0.25;           // L/kWh @ diesel (100–300 kVA)
$fuelFac = ['DIESEL'=>1.00, 'HVO100'=>1.04, 'ECOPAR'=>0.93];
$sc = BASE_SC * $fuelFac[$fuel]; // dynamisk specifik förbrukning
</code></pre>

    <h3>3.2 Temperatur- & höjdderating</h3>
    <pre class="readme__codeblock"><code>$derateTemp = max(0,($ambient-25)/5)   * 0.01; // ≈ +1 % per 5 °C över 25
$derateAlt  = max(0,($altitude-300)/300)* 0.03; // ≈ +3 % per 300 m över 300 m
$derate     = 1 + $derateTemp + $derateAlt;     // total derating-faktor
</code></pre>

    <h3>3.3 Aktuell last-beräkning</h3>
    <p>Verktyget är nu förenklat till att använda endast "aktuell last" istället för komplexa lastprofiler:</p>
    <pre class="readme__codeblock"><code>$aktuellLastKW = $aktuellLast * $aktuellLastConv; // Konvertera till kW
$LperHour = $aktuellLastKW * $sc * $derate;       // L/h med derating
$Ldrift = $LperHour * $runHrs;                    // Total årlig drift
</code></pre>

    <h3>3.4 Provkörnings-logik</h3>
    <pre class="readme__codeblock"><code>$LperTest = $ratingKW * $sc * ($provMin/60) * $derate;
$testsPerYear = (365.25 * 24 * 60) / ($provEveryVal * $provEveryConv);
$LprovTot = $LperTest * $testsPerYear;
</code></pre>

    <!-- ===================================================== -->
    <h2>4&nbsp;· Tankvolym, ullage & sump</h2>

    <div class="readme__info">
      <i class="fa-solid fa-info-circle"></i>
      <strong>Ullage</strong> (10 %) ger expansionsvolym & undviker överfyllnad.
      <strong>Sump</strong> (5 %) är "död volym" där vatten / föroreningar kan fällas ut.
      Nettonyttovolym = <code>85&nbsp;%</code> av nominell tank.
    </div>

    <pre class="readme__codeblock"><code>$ullage_pct = 10;  // Fritt utrymme för termisk expansion
$sump_pct = 5;     // Dödvolym som ej kan pumpas ut
$usable_pct = 100 - $ullage_pct - $sump_pct; // 85% användbar volym
$Lnett = $Ltank * ($usable_pct / 100);        // Nettotankvolym
</code></pre>

    <!-- ===================================================== -->
    <h2>5&nbsp;· SMHI-integration & derating</h2>

    <p>Verktyget använder SMHI-data för automatisk temperatur- och höjdjustering:</p>

    <pre class="readme__codeblock"><code>// Exempel SMHI-data
$smhi = [
  ['name' => 'Stockholm', 'temp' => 8.5, 'alt' => 44],
  ['name' => 'Göteborg', 'temp' => 9.2, 'alt' => 12],
  ['name' => 'Malmö', 'temp' => 9.8, 'alt' => 27],
  // ... fler städer
];
</code></pre>

    <div class="readme__warning">
      <i class="fa-solid fa-exclamation-triangle"></i>
      <strong>Viktigt:</strong> Temperatur och höjd påverkar motorns prestanda betydligt.
      Varje 5°C över 25°C ökar bränsleåtgången ≈1%. Varje 300m över 300 möh ökar bränsleåtgången ≈3%.
    </div>

    <!-- ===================================================== -->
    <h2>6&nbsp;· Export-funktioner</h2>

    <p>Verktyget stöder export i tre format:</p>

    <ul class="readme__list">
      <li><strong>PDF</strong> - Komplett rapport med all data och beräkningar</li>
      <li><strong>Excel</strong> - Strukturerad data för vidare analys</li>
      <li><strong>TXT</strong> - Enkel textfil för dokumentation</li>
    </ul>

    <pre class="readme__codeblock"><code>// Export aktiveras via a2.js
document.getElementById('exportPDF').addEventListener('click', async function() {
  // Genererar PDF med jsPDF från CDN
});

document.getElementById('exportExcel').addEventListener('click', function() {
  // Skapar Excel-fil med SheetJS från CDN
});
</code></pre>

    <!-- ===================================================== -->
    <h2>7&nbsp;· Källor & vidare läsning</h2>

    <ol class="readme__list">
      <li>
        <a href="https://www.msb.se/contentassets/ee7389c4f9d5435aa2e7a5a93b146486/verktygslada_for_reservkraftprocessen.pdf"
           target="_blank" rel="noopener">
           MSB – "Verktygslåda för reservkraftprocessen"</a>
      </li>
      <li>
        <a href="https://coromatic.se/76786_wp-uploads/2017/12/Produktblad-V650C2.pdf"
           target="_blank" rel="noopener">
           Coromatic V650C2 – produktblad 650 kVA</a>
      </li>
      <li>
        <a href="https://drivkraftsverige.se/wp-content/uploads/2023/11/DS-HVO-faktablad-Final.pdf"
           target="_blank" rel="noopener">
           Drivkraft Sverige – HVO100 faktablad</a>
      </li>
      <li>
        <a href="https://www.ecopar.se/en/fuels/ecopar-a/"
           target="_blank" rel="noopener">
           EcoPar A – tekniskt datablad</a>
      </li>
    </ol>

    <!-- ===================================================== -->
    <h2>8&nbsp;· Credits & licens</h2>

    <div class="readme__quote">
      <i class="fa-solid fa-quote-left"></i>
      Verktyget är utvecklat av <strong>Mackan.eu</strong>, 2025.
      Licens: MIT (se LICENSE).
    </div>

    <div class="readme__back">
      <a href="a2.php" class="readme__backlink" data-tippy-content="Gå tillbaka till reservkraftkalkylatorn">
        <i class="fa-solid fa-arrow-left"></i>
        Till reservkraftkalkylatorn
      </a>
    </div>

  </article>
</main>

<?php include '../../includes/layout-end.php'; ?>
