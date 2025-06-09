<?php
// ******************** START geo-country.php - v1 ********************
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ✅ Validera IP
$ip = $_GET['ip'] ?? '';
if (!filter_var($ip, FILTER_VALIDATE_IP)) {
    http_response_code(400);
    echo json_encode(["error" => "Ogiltig IP-adress"]);
    exit;
}

// 🛰️ Hämta landskod via ipapi.co
$url = "https://ipapi.co/$ip/country/";

$response = @file_get_contents($url);
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
// ******************** SLUT geo-country.php - v1 ********************
