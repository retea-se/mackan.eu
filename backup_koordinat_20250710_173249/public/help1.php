<?php
/**
 * help1.php
 * Teknisk dokumentation för koordinatsystem och API
 * 
 * Beskrivning:
 * Dokumentationen sammanfattar hur systemet fungerar, inklusive koordinatkonvertering, API-anrop och filhantering.
 */
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokumentation - Koordinatsystem</title>
    
    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({ 'gtm.start': new Date().getTime(), event: 'gtm.js' });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-MKP7SFFM');
    </script>
    
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
    <style>
        .sidebar {
            width: 250px;
            position: fixed;
            height: 100vh;
            background: #f4f4f4;
            padding: 20px;
            overflow-y: auto;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            margin-bottom: 10px;
        }
        .sidebar ul li a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }
        .content {
            margin-left: 270px;
            padding: 20px;
        }
    </style>
</head>
<body>
<nav class="hamburger-menu">
    <input type="checkbox" id="menu-toggle">
    <label for="menu-toggle" class="menu-icon">
        <span></span>
        <span></span>
        <span></span>
    </label>
    <ul class="menu">
        <li><a href="https://mackan.eu/verktyg/">Hem</a></li>
        <li><a href="https://mackan.eu/verktyg/koordinat/public/help1.php">Information</a></li>
        <li><a href="https://mackan.eu/verktyg/koordinat/public/impex.php">Avancerad/Batch</a></li>
        <li><a href="https://mackan.eu/verktyg/koordinat/public/impex_map.php">Plot/Adress</a></li>
    </ul>
</nav>
    <header>
        <div class="container">
            <h1>Dokumentation - Koordinatsystem och API</h1>
        </div>
    </header>
    <nav class="hamburger-menu">
        <input type="checkbox" id="menu-toggle">
        <label for="menu-toggle" class="menu-icon">
            <span></span>
            <span></span>
            <span></span>
        </label>
        <ul class="menu">
            <li><a href="https://mackan.eu/verktyg/">Hem</a></li>
            <li><a href="help1.php">Dokumentation</a></li>
            <li><a href="impex.php">Avancerad/Batch</a></li>
            <li><a href="impex_map.php">Plot/Adress</a></li>
        </ul>
    </nav>
    <div class="sidebar">
        <h2>Innehåll</h2>
        <ul>
            <li><a href="#hur-fungerar">Hur fungerar sidan?</a></li>
            <li><a href="#api-backend">API och Backend</a></li>
            <li><a href="#koordinatberakningar">Koordinatberäkningar</a></li>
            <li><a href="#sweref99-zoner">SWEREF99-Zoner</a></li>
        </ul>
    </div>
    <main class="content">
    <section id="hur-fungerar">
            <h2>Hur fungerar sidan?</h2>
            <p>
                Applikationen möjliggör konvertering mellan olika koordinatsystem, inklusive WGS84, SWEREF99 och RT90. Användaren kan mata in koordinater manuellt, importera från en fil eller klicka på kartan för att hämta koordinater. Systemet hanterar sedan följande processer:
            </p>
            <ul>
                <li><strong>Identifiering av koordinatformat:</strong> Applikationen avgör om inmatade koordinater är i WGS84, SWEREF99 eller RT90 och normaliserar indata.</li>
                <li><strong>Visning på karta:</strong> Marker placeras på kartan för att visualisera de inmatade eller konverterade koordinaterna.</li>
                <li><strong>Hämtning av höjddata:</strong> Höjd över havet beräknas med hjälp av Open-Elevation API.</li>
                <li><strong>Omvandling till SWEREF99-zoner:</strong> Baserat på longitud beräknas rätt SWEREF99-zon.</li>
                <li><strong>Export och import av koordinater:</strong> Användare kan importera koordinater från CSV- och JSON-filer samt exportera konverterade koordinater i valfritt format.</li>
                <li><strong>API-anrop:</strong> Systemet integrerar ett internt API för att utföra koordinattransformationer, vilket gör det möjligt att hantera stora datamängder effektivt.</li>
            </ul>
            <p>
                Systemet bygger på Leaflet.js för kartinteraktioner och JavaScript för att hantera händelser och API-anrop. Backend-logiken sköter beräkningarna och omvandlingarna via PHP-baserade API-endpoints.
            </p>
        </section>
        <section id="api-backend">
            <h2>API och Backend</h2>
            <p>
                API:et hanterar koordinatkonvertering genom en RESTful endpoint. Anropen sker med HTTP POST och data skickas som JSON.
                API:et utför beräkningar för att konvertera mellan WGS84, SWEREF99 och RT90 samt hämtar höjddata.
            </p>
            <h3>API-endpoint</h3>
            <pre>
POST https://mackan.eu/verktyg/koordinat/api/convert.php
Content-Type: application/json
            </pre>
            <h3>Exempel på begäran</h3>
            <pre>
{
    "coordinates": "59.3293,18.0686"
}
            </pre>
            <h3>Exempel på svar</h3>
            <pre>
{
    "wgs84": { "lat": 59.3293, "lon": 18.0686 },
    "sweref99": { "north": 6580822, "east": 674032 },
    "rt90": { "north": 6580205, "east": 1631232 },
    "elevation": "45 m",
    "sweref99_zone": "SWEREF 99 18 00"
}
            </pre>
            <h3>Beräkningar</h3>
            <p>
                API:et använder Gauss-Krüger-projektion och Helmert-transformation för att beräkna SWEREF99 och RT90 från WGS84.
                Algoritmen använder transformationsparametrar och projiceringskonstanter för att omvandla latitud och longitud till plana koordinater.
            </p>
            <h3>SWEREF99-Zonberäkning</h3>
            <p>
                SWEREF99-zonen avgörs baserat på longitud enligt följande metod:
            </p>
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
            <p>
                Vid konvertering beräknas även höjddata via Open-Elevation API och inkluderas i svaret.
            </p>
        </section>
        <section id="koordinatberakningar">
            <h2>Koordinatberäkningar</h2>
            <p>
                För att konvertera mellan olika koordinatsystem används transformationer som bygger på Gauss-Krüger-projektion och Helmert-transformation.
                Grundläggande beräkningsformler för att transformera geografiska koordinater till plana koordinater (i exempelvis SWEREF99 och RT90) är:
            </p>
            <pre>
E = a + b * longitud + c * latitud + d * longitud² + e * latitud²
N = f + g * longitud + h * latitud + i * longitud² + j * latitud²
            </pre>
            <h3>Transformation mellan WGS84 och SWEREF99</h3>
            <p>
                WGS84 är ett globalt system medan SWEREF99 är anpassat för Sverige. För att konvertera mellan dessa används en sju-parameters Helmert-transformation:
            </p>
            <pre>
x' = x + T_x + R_x * y - R_y * z + S * x

y' = y + T_y + R_y * x + R_z * z + S * y

z' = z + T_z + R_z * y - R_x * x + S * z
            </pre>
            <p>
                Där:
                <ul>
                    <li><strong>T_x, T_y, T_z</strong> är translationsparametrar</li>
                    <li><strong>R_x, R_y, R_z</strong> är rotationsparametrar</li>
                    <li><strong>S</strong> är skalningsfaktorn</li>
                </ul>
            </p>
            <h3>Konvertering till RT90</h3>
            <p>
                RT90 är ett äldre svenskt system som använder en modifierad Gauss-Krüger-projektion. Vid konvertering används liknande matematiska transformationer som för SWEREF99.
            </p>
            <p>
                För noggranna beräkningar används tabeller med transformationsparametrar beroende på vilken SWEREF99-zon eller RT90-zon som används.
            </p>
        </section>
        <section id="sweref99-zoner">
            <h2>SWEREF99-Zoner</h2>
            <p>
                SWEREF99 är det nationella referenssystemet för Sverige och används för lantmäteriets kartor.
                Systemet är indelat i flera zoner som utgår från olika longituder.
            </p>
            <p>
                Vid konvertering från WGS84 till SWEREF99 avgörs zonen utifrån longituden enligt följande:
            </p>
            <pre>
function getSweref99Zone(longitude) {
    const zones = [
        { meridian: 11.0, name: "SWEREF99 1200" },
        { meridian: 13.5, name: "SWEREF99 1330" },
        { meridian: 15.0, name: "SWEREF99 TM" },
        { meridian: 18.0, name: "SWEREF99 1800" },
        { meridian: 21.0, name: "SWEREF99 2145" },
        { meridian: 23.0, name: "SWEREF99 2315" }
    ];
    return zones.find(z => Math.abs(longitude - z.meridian) < 1) ?.name || "SWEREF99 TM";
}
            </pre>
            <p>
                SWEREF99 TM är den nationella projektionszonen och används oftast vid rikstäckande kartläggningar,
                medan de övriga zonerna används vid mer detaljerade kartor beroende på geografiskt område.
            </p>
        </section>
    </main>
    <footer>
        <p>&copy; <span id="currentYear"></span> Mackan.eu</p>
    </footer>
    <script>
        document.getElementById('currentYear').textContent = new Date().getFullYear();
    </script>
</body>
</html>
