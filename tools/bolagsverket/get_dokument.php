<?php
// tools/bolagsverket/get_dokument.php - v1
ini_set('display_errors', 1);
error_reporting(E_ALL);

$env = parse_ini_file(__DIR__ . '/config.env');
$clientId = $env['BV_CLIENT_ID'] ?? '';
$clientSecret = $env['BV_CLIENT_SECRET'] ?? '';

$dokumentId = $_GET['dokumentId'] ?? '';
if (!$dokumentId) {
  http_response_code(400);
  echo 'Saknar dokumentId';
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
  echo 'Tokenfel';
  exit;
}

// Hämta zip
function uuidv4() {
  return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
    mt_rand(0, 0xffff), mt_rand(0, 0xffff),
    mt_rand(0, 0xffff),
    mt_rand(0, 0x0fff) | 0x4000,
    mt_rand(0, 0x3fff) | 0x8000,
    mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
  );
}

$url = "https://gw.api.bolagsverket.se/vardefulla-datamangder/v1/dokument/{$dokumentId}";
$uuid = uuidv4();

$docRequest = curl_init($url);
curl_setopt_array($docRequest, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HTTPHEADER => [
    "Authorization: Bearer $accessToken",
    "X-Request-Id: $uuid",
    "Accept: application/zip"
  ]
]);

$data = curl_exec($docRequest);
$code = curl_getinfo($docRequest, CURLINFO_HTTP_CODE);
curl_close($docRequest);

if ($code !== 200 || !$data) {
  http_response_code($code);
  echo "Misslyckades: $code";
  exit;
}

// Skicka nedladdning
header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="arsredovisning.zip"');
echo $data;
