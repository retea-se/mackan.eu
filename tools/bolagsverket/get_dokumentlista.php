<?php
// tools/bolagsverket/get_dokumentlista.php - v1
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

$env = parse_ini_file(__DIR__ . '/config.env');
$clientId = $env['BV_CLIENT_ID'] ?? '';
$clientSecret = $env['BV_CLIENT_SECRET'] ?? '';

if (!$clientId || !$clientSecret) {
  http_response_code(500);
  echo json_encode(['error' => 'Client ID/Secret saknas.']);
  exit;
}

$orgnr = $_GET['orgnr'] ?? '';
$orgnr = str_replace('-', '', $orgnr);

if (!preg_match('/^\d{10}$/', $orgnr)) {
  http_response_code(400);
  echo json_encode(['error' => 'Ogiltigt orgnr']);
  exit;
}

// Token
$tokenUrl = 'https://portal.api.bolagsverket.se/oauth2/token';
$scope = 'vardefulla-datamangder:read vardefulla-datamangder:ping';

$tokenRequest = curl_init($tokenUrl);
curl_setopt_array($tokenRequest, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_POST => true,
  CURLOPT_POSTFIELDS => http_build_query([
    'grant_type' => 'client_credentials',
    'client_id' => $clientId,
    'client_secret' => $clientSecret,
    'scope' => $scope
  ]),
  CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded']
]);

$tokenResponse = curl_exec($tokenRequest);
curl_close($tokenRequest);
$tokenData = json_decode($tokenResponse, true);
$accessToken = $tokenData['access_token'] ?? null;

if (!$accessToken) {
  http_response_code(500);
  echo json_encode(['error' => 'Token kunde inte hämtas.']);
  exit;
}

function uuidv4() {
  return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
    mt_rand(0, 0xffff), mt_rand(0, 0xffff),
    mt_rand(0, 0xffff),
    mt_rand(0, 0x0fff) | 0x4000,
    mt_rand(0, 0x3fff) | 0x8000,
    mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
  );
}

$payload = json_encode(['identitetsbeteckning' => $orgnr]);
$uuid = uuidv4();
$apiUrl = 'https://gw.api.bolagsverket.se/vardefulla-datamangder/v1/dokumentlista';

$apiRequest = curl_init($apiUrl);
curl_setopt_array($apiRequest, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_POST => true,
  CURLOPT_POSTFIELDS => $payload,
  CURLOPT_HTTPHEADER => [
    "Authorization: Bearer $accessToken",
    "X-Request-Id: $uuid",
    "Content-Type: application/json",
    "Accept: application/json"
  ]
]);

$response = curl_exec($apiRequest);
$httpCode = curl_getinfo($apiRequest, CURLINFO_HTTP_CODE);
curl_close($apiRequest);

http_response_code($httpCode);
echo $response;
