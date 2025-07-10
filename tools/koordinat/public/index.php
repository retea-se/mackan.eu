<!DOCTYPE html>
<html lang="sv">
<head>
    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({'gtm.start': new Date().getTime(), event: 'gtm.js'});
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-MKP7SFFM');
    </script>
    <!-- End Google Tag Manager -->

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koordinatkonverterare - WGS84, SWEREF99, RT90 | Mackan.eu</title>
    <meta name="description" content="Professionellt koordinatverktyg för konvertering mellan WGS84, SWEREF99, RT90. Stöder batch-import, höjddata och CSV-export. Perfekt för GIS-arbete och lantmäteri.">
    <meta name="keywords" content="koordinatkonvertering, WGS84, SWEREF99, RT90, GIS, lantmäteri, batch-import, höjddata, koordinatsystem, GPS">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://mackan.eu/tools/koordinat/">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Koordinatkonverterare - WGS84, SWEREF99, RT90">
    <meta property="og:description" content="Professionellt koordinatverktyg för konvertering mellan WGS84, SWEREF99, RT90. Stöder batch-import och CSV-export.">
    <meta property="og:url" content="https://mackan.eu/tools/koordinat/">
    <meta property="og:image" content="https://mackan.eu/icon/android-chrome-512x512.png">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Koordinatkonverterare - WGS84, SWEREF99, RT90">
    <meta name="twitter:description" content="Professionellt koordinatverktyg för konvertering mellan koordinatsystem.">

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/icon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/icon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/icon/favicon-16x16.png">
    <link rel="manifest" href="/icon/site.webmanifest">
    <link rel="shortcut icon" href="/icon/favicon.ico">

    <!-- Preconnect för snabbare laddning -->
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="preconnect" href="https://unpkg.com">

    <!-- CSS -->
    <link rel="stylesheet" href="https://mackan.eu/verktyg/assets/css/reset.css">
    <link rel="stylesheet" href="https://mackan.eu/verktyg/assets/css/variables.css">
    <link rel="stylesheet" href="https://mackan.eu/verktyg/assets/css/typography.css">
    <link rel="stylesheet" href="https://mackan.eu/verktyg/assets/css/layout.css">
    <link rel="stylesheet" href="https://mackan.eu/verktyg/assets/css/components.css">
    <link rel="stylesheet" href="https://mackan.eu/verktyg/assets/css/utilities.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.6.2/proj4.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <script defer src="script.js"></script>

    <!-- Strukturerad data för sökmotorer -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebApplication",
      "name": "Koordinatkonverterare",
      "description": "Professionellt koordinatverktyg för konvertering mellan WGS84, SWEREF99, RT90",
      "url": "https://mackan.eu/tools/koordinat/",
      "applicationCategory": "UtilityApplication",
      "operatingSystem": "Web Browser",
      "offers": {
        "@type": "Offer",
        "price": "0",
        "priceCurrency": "SEK"
      },
      "featureList": [
        "WGS84 till SWEREF99 konvertering",
        "RT90 koordinatsystem",
        "Batch-import från CSV",
        "Kartvisning",
        "Höjddata",
        "Export till olika format"
      ],
      "author": {
        "@type": "Organization",
        "name": "Mackan.eu"
      }
    }
    </script>

    <style>
        .breadcrumbs {
            margin-bottom: 1rem;
            font-size: 0.9rem;
            color: #6c757d;
            padding: 0.5rem 0;
        }
        .breadcrumbs a {
            color: #007bff;
            text-decoration: none;
        }
        .breadcrumbs a:hover {
            text-decoration: underline;
        }

        .menu-container {
            position: absolute;
            top: 10px;
            left: 10px;
            background: rgba(255, 255, 255, 0.9);
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }
        .menu-list ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .menu-list ul li {
            margin: 5px 0;
        }
        .menu-list ul li a {
            text-decoration: none;
            color: var(--primary-color);
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <!-- Breadcrumbs -->
            <nav class="breadcrumbs" aria-label="Du är här">
                <a href="/">🏠 Hem</a> ›
                <a href="/tools/">🔧 Verktyg</a> ›
                <span>🗺️ Koordinatverktyg</span>
            </nav>

            <h1 class="page-title">Koordinatkonverterare</h1>
            <p style="margin-top: 0.5rem; color: #6c757d; font-size: 1.1rem;">
                Konvertera mellan WGS84, SWEREF99 och RT90 koordinatsystem
            </p>
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
        <li><a href="https://mackan.eu/verktyg/koordinat/public/help1.php">Information</a></li>
        <li><a href="https://mackan.eu/verktyg/koordinat/public/impex.php">Avancerad/Batch</a></li>
        <li><a href="https://mackan.eu/verktyg/koordinat/public/impex_map.php">Plot/Adress</a></li>
    </ul>
</nav>


    <main class="container">
        <p class="description">Klicka på kartan för att få koordinater eller mata in dem manuellt.</p>
        <div id="map" style="height: 500px; width: 100%; border-radius: 8px; margin-bottom: 20px;"></div>

        <form id="convert-form" class="form">
            <label for="coordinates" class="form-label">Koordinater:</label>
            <input type="text" id="coordinates" class="input" placeholder="Ex: 59.3293, 18.0686" required>
            <p id="format-info" class="info-text hidden">Format: Okänt</p>
            <button type="submit" class="button primary-button">Konvertera</button>
        </form>

        <div id="result" class="card">
            <h2 class="card-title">Resultat</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Format</th>
                        <th>Värde</th>
                    </tr>
                </thead>
                <tbody id="result-body">
                    <!-- Dynamiska resultat fylls här -->
                </tbody>
            </table>
        </div>
    </main>

    <footer class="footer">
        <div class="footer-left">
            <a href="javascript:history.back()" class="back-link">&larr; Tillbaka</a>
        </div>
        <div class="footer-center">
            © <span id="currentYear"></span> Mackan.eu
        </div>
    </footer>

    <!-- Relaterade verktyg för bättre SEO och användning -->
    <aside class="related-tools" style="margin: 2rem auto; max-width: 1200px; padding: 1.5rem; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #28a745;">
        <div class="container">
            <h3 style="margin-top: 0; color: #28a745;">🔗 Relaterade verktyg</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem; margin-top: 1rem;">

                <a href="/tools/rka/" style="text-decoration: none; padding: 1rem; border: 1px solid #dee2e6; border-radius: 6px; background: white; display: block; transition: all 0.2s; box-shadow: 0 1px 3px rgba(0,0,0,0.1);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.1)'">
                    <h4 style="margin: 0 0 0.5rem; color: #007bff; font-size: 1rem;">⚡ RKA-kalkylatorer</h4>
                    <p style="margin: 0; color: #6c757d; font-size: 0.9rem; line-height: 1.4;">Dimensionera reservkraftverk och beräkna bränsleförbrukning</p>
                </a>

                <a href="/tools/qr_v2/" style="text-decoration: none; padding: 1rem; border: 1px solid #dee2e6; border-radius: 6px; background: white; display: block; transition: all 0.2s; box-shadow: 0 1px 3px rgba(0,0,0,0.1);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.1)'">
                    <h4 style="margin: 0 0 0.5rem; color: #007bff; font-size: 1rem;">📱 QR-kodgenerator</h4>
                    <p style="margin: 0; color: #6c757d; font-size: 0.9rem; line-height: 1.4;">Skapa anpassade QR-koder med logo och färger</p>
                </a>

                <a href="/tools/converter/" style="text-decoration: none; padding: 1rem; border: 1px solid #dee2e6; border-radius: 6px; background: white; display: block; transition: all 0.2s; box-shadow: 0 1px 3px rgba(0,0,0,0.1);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.1)'">
                    <h4 style="margin: 0 0 0.5rem; color: #007bff; font-size: 1rem;">🔄 Enhetskonverterare</h4>
                    <p style="margin: 0; color: #6c757d; font-size: 0.9rem; line-height: 1.4;">Konvertera mellan olika måttenheter</p>
                </a>

            </div>
            <div style="margin-top: 1rem; text-align: center;">
                <a href="/tools/" style="color: #007bff; text-decoration: none; font-weight: 600;">→ Se alla verktyg</a>
            </div>
        </div>
    </aside>

    <script>
        document.getElementById('currentYear').textContent = new Date().getFullYear();

        // Hantering av hamburgermenyn
        document.querySelector(".menu-toggle").addEventListener("click", function() {
            document.querySelector(".menu-list").classList.toggle("hidden");
        });
    </script>

</body>
</html>
