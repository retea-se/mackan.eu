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
    <title>Koordinatkonverterare</title>
    
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
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.6.2/proj4.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <script defer src="script.js"></script>

    <style>
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
            <h1 class="page-title">Koordinatkonverterare</h1>
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

    <script>
        document.getElementById('currentYear').textContent = new Date().getFullYear();
        
        // Hantering av hamburgermenyn
        document.querySelector(".menu-toggle").addEventListener("click", function() {
            document.querySelector(".menu-list").classList.toggle("hidden");
        });
    </script>

</body>
</html>
