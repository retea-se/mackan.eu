<?php
/**
 * impex.php
 * Sida för att importera och konvertera koordinater
 */
?>
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avancerad Koordinathantering</title>

    <!-- CSS -->
    <link rel="stylesheet" href="https://mackan.eu/verktyg/assets/css/reset.css">
    <link rel="stylesheet" href="https://mackan.eu/verktyg/assets/css/variables.css">
    <link rel="stylesheet" href="https://mackan.eu/verktyg/assets/css/typography.css">
    <link rel="stylesheet" href="https://mackan.eu/verktyg/assets/css/layout.css">
    <link rel="stylesheet" href="https://mackan.eu/verktyg/assets/css/components.css">
    <link rel="stylesheet" href="https://mackan.eu/verktyg/assets/css/utilities.css">
    
    <!-- Importera Leaflet (Ej nödvändigt här men behåller om framtida kartfunktioner behövs) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css">

    <!-- JavaScript -->
    <script defer src="impex.js"></script>
    <script defer src="export.js"></script>

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

        <!-- FORMULÄR FÖR KOORDINATER & IMPORT -->
        <form id="advanced-form" class="form">
            <label for="coordinates-textarea" class="form-label">Klistra in koordinater:</label>
            <textarea id="coordinates-textarea" class="input" placeholder="Ex: 59.3293, 18.0686&#10;59.3300, 18.0690"></textarea>

            <label for="import-file" class="form-label">Importera fil:</label>
            <input type="file" id="import-file" class="input-file">

            <button type="button" id="convert-textarea" class="button primary-button">Konvertera</button>
            <button type="button" id="export-button" class="button secondary-button">Exportera CSV</button>
        </form>

        <!-- RESULTATTABELL -->
        <div id="result" class="card hidden">
            <h2 class="card-title">Resultat</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Latitud (WGS84)</th>
                        <th>Longitud (WGS84)</th>
                        <th>Höjd (m)</th>
                        <th>SWEREF99-Zon</th>
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

        // Koppla event listeners till knappar
        document.getElementById("convert-textarea").addEventListener("click", handleTextInput);
        document.getElementById("export-button").addEventListener("click", () => exportTable("csv"));
    </script>

</body>
</html>
