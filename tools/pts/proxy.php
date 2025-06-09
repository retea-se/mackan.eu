<?php
// proxy.php - v3
header('Content-Type: application/json');

function logAndExit($message, $httpCode = 500) {
    http_response_code($httpCode);
    error_log("[proxy.php] ❌ $message");
    echo json_encode(['error' => $message]);
    exit;
}

// Bygg API-URL beroende på parameter
if (isset($_GET['caseid'])) {
    $caseid = urlencode($_GET['caseid']);
    $url = "https://data.pts.se/v1/diarium/$caseid";
    error_log("[proxy.php] 📂 Begär handlingar för caseid=$caseid");
} elseif (isset($_GET['start']) && isset($_GET['end'])) {
    $start = $_GET['start'];
    $end = $_GET['end'];
    $url = "https://data.pts.se/v1/diarium/$start/$end";
    error_log("[proxy.php] 📅 Begär ärenden mellan $start och $end");
} else {
    logAndExit("Ogiltiga parametrar", 400);
}

// Hämta innehåll med felhantering
$options = [
    "http" => [
        "method" => "GET",
        "header" => "User-Agent: PTS-verktyg/1.0\r\n"
    ]
];
$context = stream_context_create($options);
$response = @file_get_contents($url, false, $context);

if ($response === false) {
    logAndExit("Kunde inte hämta data från $url", 404);
}

// Returnera resultat
echo $response;
