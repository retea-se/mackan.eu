<?php
// ******************** START geo-country.php - v2 ********************
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Om anropas som API (med IP-parameter), returnera JSON
$ip = $_GET['ip'] ?? '';
if (!empty($ip)) {
    header('Content-Type: application/json; charset=utf-8');

    // ✅ Validera IP
    if (!filter_var($ip, FILTER_VALIDATE_IP)) {
        http_response_code(400);
        echo json_encode(["error" => "Ogiltig IP-adress"]);
        exit;
    }

    // 🛰️ Hämta landskod via ipapi.co
    $url = "https://ipapi.co/$ip/country/";

    $context = stream_context_create([
        'http' => [
            'timeout' => 5,
            'user_agent' => 'Mackan.eu/1.0'
        ]
    ]);

    $response = @file_get_contents($url, false, $context);
    if ($response === false) {
        http_response_code(500);
        echo json_encode(["error" => "Kunde inte hämta data från ipapi.co"]);
        exit;
    }

    // Returnera t.ex. { "country": "SE", "ip": "1.2.3.4" }
    echo json_encode([
        "country" => strtoupper(trim($response)),
        "ip" => $ip
    ]);
    exit;
}

// Om anropas som sida (utan IP-parameter), visa admin-gränssnitt
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit;
}

$title = 'Geolokalisering';
?>
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> | Admin</title>
    <link rel="stylesheet" href="dashboard.css">
    <style>
        .geo-container {
            max-width: 1200px;
            margin: 2em auto;
            padding: 2em;
        }
        .geo-form {
            background: #fff;
            padding: 2em;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 2em;
        }
        .geo-form input {
            width: 100%;
            padding: 0.75em;
            font-size: 1em;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 1em;
        }
        .geo-form button {
            background: #007bff;
            color: white;
            border: none;
            padding: 0.75em 2em;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
        }
        .geo-form button:hover {
            background: #0056b3;
        }
        .result {
            background: #f8f9fa;
            padding: 1.5em;
            border-radius: 8px;
            margin-top: 1em;
            display: none;
        }
        .result.show {
            display: block;
        }
        .result.success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        .result.error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 1em;
            color: #007bff;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="geo-container">
        <a href="index.php" class="back-link">← Tillbaka till adminpanel</a>

        <div class="geo-form">
            <h1>🌍 Geolokalisering</h1>
            <p>Ange en IP-adress för att hämta landskod:</p>
            <form id="geoForm">
                <input type="text" name="ip" placeholder="t.ex. 8.8.8.8" required>
                <button type="submit">Sök</button>
            </form>
            <div id="result" class="result"></div>
        </div>
    </div>

    <script>
        document.getElementById('geoForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const ip = this.ip.value;
            const resultDiv = document.getElementById('result');

            resultDiv.className = 'result';
            resultDiv.textContent = 'Laddar...';
            resultDiv.classList.add('show');

            try {
                const response = await fetch(`geo-country.php?ip=${encodeURIComponent(ip)}`);
                const data = await response.json();

                if (response.ok && data.country) {
                    resultDiv.className = 'result success show';
                    resultDiv.innerHTML = `
                        <h3>Resultat:</h3>
                        <p><strong>IP-adress:</strong> ${data.ip}</p>
                        <p><strong>Land:</strong> ${data.country}</p>
                    `;
                } else {
                    resultDiv.className = 'result error show';
                    resultDiv.textContent = `Fel: ${data.error || 'Okänt fel'}`;
                }
            } catch (error) {
                resultDiv.className = 'result error show';
                resultDiv.textContent = `Fel: ${error.message}`;
            }
        });
    </script>
</body>
</html>
<?php
// ******************** SLUT geo-country.php - v2 ********************
?>
