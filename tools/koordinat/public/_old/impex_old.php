<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avancerad Koordinathantering</title>
    
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

    <!-- Leaflet & JS -->
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
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <h1 class="page-title">Avancerad Koordinathantering</h1>
        </div>
    </header>

    <!-- Hamburgermeny -->
    <div class="menu-container">
        <button class="menu-toggle">☰</button>
        <nav class="menu-list hidden">
            <ul>
                <li><a href="help2.php">Hjälp</a></li>
                <li><a href="index.php">Enkel</a></li>
            </ul>
        </nav>
    </div>

    <main class="container">
        <p class="description">Mata in eller importera koordinater för avancerad hantering.</p>

        <!-- BEFINTLIGT FORMULÄR OCH KNAPPAR (ÅTERSTÄLLT) -->
        <form id="advanced-form" class="form">
            <label for="coordinates" class="form-label">Koordinater:</label>
            <input type="text" id="coordinates" class="input" placeholder="Ex: 59.3293, 18.0686" required>

            <label for="import-file" class="form-label">Importera fil:</label>
            <input type="file" id="import-file" class="input-file">

            <button type="submit" class="button primary-button">Konvertera</button>
            <button type="button" id="import-button" class="button secondary-button">Importera</button>
        </form>

        <!-- RESULTATTABELL -->
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

        // Hantera importknappen
        document.getElementById("import-button").addEventListener("click", function() {
            alert("Importfunktion är ännu inte implementerad!");
        });

        // Hantera formuläret för avancerad konvertering
        document.getElementById("advanced-form").addEventListener("submit", function(e) {
            e.preventDefault();
            alert("Konvertering påbörjas... (Exempel)");
        });
    </script>

</body>
</html>
