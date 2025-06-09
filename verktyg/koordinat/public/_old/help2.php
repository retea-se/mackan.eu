<?php
/**
 * help2.php
 * Hjälpfil för Avancerad Koordinathantering
 * 
 * Beskrivning:
 * Dokumentation för API:er och funktioner i avancerad koordinathantering.
 */
?>
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hjälp - Avancerad Koordinathantering</title>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="https://mackan.eu/icon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="https://mackan.eu/icon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="https://mackan.eu/icon/favicon-16x16.png">
    <link rel="manifest" href="https://mackan.eu/icon/site.webmanifest">
    <link rel="shortcut icon" href="https://mackan.eu/icon/favicon.ico">

    <!-- CSS från ditt system -->
    <link rel="stylesheet" href="https://mackan.eu/verktyg/assets/css/reset.css">
    <link rel="stylesheet" href="https://mackan.eu/verktyg/assets/css/variables.css">
    <link rel="stylesheet" href="https://mackan.eu/verktyg/assets/css/typography.css">
    <link rel="stylesheet" href="https://mackan.eu/verktyg/assets/css/layout.css">
    <link rel="stylesheet" href="https://mackan.eu/verktyg/assets/css/components.css">
    <link rel="stylesheet" href="https://mackan.eu/verktyg/assets/css/utilities.css">

    <style>
        .help-container {
            display: flex;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Statisk sidomeny */
        .sidebar {
            width: 250px;
            padding-right: 20px;
            border-right: 1px solid #ddd;
            flex-shrink: 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            top: 0;
            left: 0;
            background-color: #fff;
            padding-top: 20px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin-top: 20px;
        }

        .sidebar ul li {
            margin-bottom: 10px;
        }

        .sidebar a {
            text-decoration: none;
            color: var(--primary-color);
            font-weight: bold;
        }

        .content {
            flex-grow: 1;
            padding-left: 280px;
        }

        pre {
            background: #f4f4f4;
            padding: 10px;
            border-radius: 5px;
            overflow-x: auto;
            font-family: monospace;
        }

        .footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-top: 1px solid #ddd;
            margin-top: 40px;
        }

        .footer-left {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <h1 class="page-title">Hjälp - Avancerad Koordinathantering</h1>
        </div>
    </header>

    <div class="help-container">
        <nav class="sidebar">
            <h2>📌 Innehåll</h2>
            <ul>
                <li><a href="#intro">📍 Introduktion</a></li>
                <li><a href="#usage">🛠️ Hur det fungerar</a></li>
                <li><a href="#api-convert">🔄 API: Konvertering</a></li>
                <li><a href="#api-elevation">🌍 API: Höjddata</a></li>
                <li><a href="#transformations">📐 Koordinatberäkningar</a></li>
                <li><a href="#sweref-zones">📍 SWEREF99-Zoner</a></li>
                <li><a href="#export">📤 Exportera CSV</a></li>
            </ul>
        </nav>

        <main class="content">
            <section id="intro">
                <h2>📍 Introduktion</h2>
                <p>
                    Denna dokumentation beskriver hur systemet hanterar konvertering av koordinater mellan WGS84, SWEREF99 och RT90.
                    Vi tar emot indata, konverterar och presenterar resultaten inklusive höjddata.
                </p>
            </section>

            <section id="usage">
                <h2>🛠️ Hur det fungerar</h2>
                <p>När du trycker på "Konvertera" gör systemet följande:</p>
                <ul>
                    <li>Identifierar koordinaternas format (WGS84, SWEREF99, RT90).</li>
                    <li>Hämtar höjddata via Open-Elevation API.</li>
                    <li>Beräknar SWEREF99-zonen direkt i webbläsaren.</li>
                    <li>Visar resultaten i en tabell.</li>
                    <li>Export till CSV är möjlig.</li>
                </ul>
            </section>

            <section id="api-convert">
                <h2>🔄 API: Konvertering</h2>
                <pre>POST https://mackan.eu/verktyg/koordinat/api/convert.php</pre>
            </section>

            <section id="api-elevation">
                <h2>🌍 API: Höjddata</h2>
                <pre>POST https://api.open-elevation.com/api/v1/lookup</pre>
            </section>

            <section id="transformations">
                <h2>📐 Koordinatberäkningar</h2>
                <p>SWEREF99 och RT90 bygger på Gauss-Krüger-projektionen och omvandlingen ser ut så här:</p>
                <pre>
E = a + b * longitud + c * latitud + d * longitud² + e * latitud²
N = f + g * longitud + h * latitud + i * longitud² + j * latitud²
                </pre>
            </section>

            <section id="sweref-zones">
                <h2>📍 SWEREF99-Zoner</h2>
                <p>Longitud bestämmer vilken SWEREF99-zon en koordinat tillhör.</p>
                <pre>
function getSweref99Zone(longitude) {
    const zones = [
        { meridian: 11.0, name: "SWEREF99 1200" },
        { meridian: 13.5, name: "SWEREF99 1330" },
        { meridian: 15.0, name: "SWEREF99 TM" },
        { meridian: 18.0, name: "SWEREF99 1800" }
    ];
    return zones.find(z => Math.abs(longitude - z.meridian) < 1) ?.name || "SWEREF99 TM";
}
                </pre>
            </section>

            <section id="export">
                <h2>📤 Exportera CSV</h2>
                <p>Export sker via <code>export_advanced.js</code>. Filerna är UTF-8 BOM och semikolon-separerade.</p>
            </section>
        </main>
    </div>

    <footer class="footer">
        <div class="footer-left">
            <a href="javascript:history.back()" class="back-link">&larr; Tillbaka</a>
        </div>
        <div class="footer-center">
            © <span id="currentYear"></span> Mackan.eu
        </div>
    </footer>

    <script>
        document.getElementById('currentYear').textContent = new Date().getFullYear();
    </script>
</body>
</html>
