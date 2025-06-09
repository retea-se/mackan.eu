<?php
// tools/bolagsverket/get_token.php - v2

// ********** START: Felrapportering **********
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// ********** SLUT: Felrapportering **********

// 🟡 Hämta miljövariabler från config.env
$env = parse_ini_file(__DIR__ . '/config.env');
$clientId = $env['BV_CLIENT_ID'] ?? '';
$clientSecret = $env['BV_CLIENT_SECRET'] ?? '';

// ********** START: Hämta token **********
$tokenUrl = 'https://portal.api.bolagsverket.se/oauth2/token';

$ch = curl_init($tokenUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'grant_type' => 'client_credentials'
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Basic ' . base64_encode("$clientId:$clientSecret"),
    'Content-Type: application/x-www-form-urlencoded'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'cURL-fel: ' . curl_error($ch)]);
    curl_close($ch);
    exit;
}

curl_close($ch);

header('Content-Type: application/json');
http_response_code($httpCode);
echo $response;
// ********** SLUT: Hämta token **********
