<?php
/**
 * help2.php
 * Hjälpfil för Avancerad Koordinathantering
 * 
 * Beskrivning:
 * Denna sida förklarar funktionerna i den avancerade koordinathanteringen (impex.php),
 * samt hur man använder de tillhörande API:erna.
 */
?>
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-MKP7SFFM');
    </script>
    <!-- End Google Tag Manager -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hjälp - Avancerad Koordinathantering</title>
    
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/icon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/icon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/icon/favicon-16x16.png">
    <link rel="manifest" href="/icon/site.webmanifest">
    <link rel="shortcut icon" href="/icon/favicon.ico">
    
    <!-- CSS -->
    <link rel="stylesheet" href="https://mackan.eu/verktyg/assets/css/reset.css">
    <link rel="stylesheet" href="https://mackan.eu/verktyg/assets/css/variables.css">
    <link rel="stylesheet" href="https://mackan.eu/verktyg/assets/css/typography.css">
    <link rel="stylesheet" href="https://mackan.eu/verktyg/assets/css/layout.css">
    <link rel="stylesheet" href="https://mackan.eu/verktyg/assets/css/components.css">
    <link rel="stylesheet" href="https://mackan.eu/verktyg/assets/css/utilities.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <h1 class="page-title">Hjälp - Avancerad Koordinathantering</h1>
        </div>
    </header>

    <main class="container">
        <section>
            <h2>Hur fungerar denna sida?</h2>
            <p>
                Den avancerade koordinathanteringen låter dig klistra in eller importera koordinater för att analysera och konvertera dem till olika referenssystem.
                När du trycker på "Konvertera" kommer systemet att:
            </p>
            <ul>
                <li>Identifiera koordinaternas format (WGS84, SWEREF99, RT90).</li>
                <li>Beräkna SWEREF99-zonen direkt i webbläsaren.</li>
                <li>Hämta höjddata via Open-Elevation API.</li>
                <li>Visa resultaten i en tabell.</li>
                <li>Låta dig exportera resultaten till CSV.</li>
            </ul>
        </section>

        <section>
            <h2>API: Konvertera koordinater</h2>
            <p>
                För att konvertera en koordinat kan du använda vårt API.
            </p>
            <h3>API-endpoint:</h3>
            <code>POST https://mackan.eu/verktyg/koordinat/api/convert.php</code>

            <h3>Exempelanrop:</h3>
            <pre>
POST /verktyg/koordinat/api/convert.php
Content-Type: application/json

{
    "coordinates": "59.3293,18.0686"
}
            </pre>

            <h3>Exempelsvar:</h3>
            <pre>
{
    "detectedFormat": "WGS84",
    "wgs84": { "lat": 59.3293, "lon": 18.0686 },
    "sweref99": { "lat": 6580821, "lon": 674032 },
    "rt90": { "lat": 6580994, "lon": 1628293 },
    "sweref99_zone": "SWEREF99 TM",
    "elevation": 27
}
            </pre>
        </section>

        <section>
            <h2>API: Hämta höjddata</h2>
            <p>
                Vi använder Open-Elevation API för att hämta höjddata för en given latitud och longitud.
            </p>

            <h3>API-endpoint:</h3>
            <code>POST https://api.open-elevation.com/api/v1/lookup</code>

            <h3>Exempelanrop:</h3>
            <pre>
POST /api/v1/lookup
Content-Type: application/json

{
    "locations": [
        { "latitude": 59.3293, "longitude": 18.0686 }
    ]
}
            </pre>

            <h3>Exempelsvar:</h3>
            <pre>
{
    "results": [
        { "latitude": 59.3293, "longitude": 18.0686, "elevation": 27 }
    ]
}
            </pre>
        </section>

        <section>
            <h2>Javascript och Funktionalitet</h2>
            <p>
                Javascript-hanteringen sker i flera filer:
            </p>
            <ul>
                <li><strong><code>impex.js</code>:</strong> Hanterar inmatning, konvertering och API-anrop.</li>
                <li><strong><code>export_advanced.js</code>:</strong> Sköter exporten av resultatet till CSV.</li>
            </ul>
        </section>

        <section>
            <h2>Teckenkodning och CSV-export</h2>
            <p>
                För att säkerställa att svenska tecken (å, ä, ö) fungerar korrekt använder vi UTF-8 BOM vid export.  
                CSV-filen är semikolon-separerad för att fungera med svenska Excel-inställningar.
            </p>
        </section>
    </main>

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
