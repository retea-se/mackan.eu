<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$lat = $_GET['lat'] ?? null;
$lon = $_GET['lon'] ?? null;

if (!$lat || !$lon) {
    echo json_encode(['error' => 'Invalid parameters']);
    exit;
}

$remote = "https://api.opentopodata.org/v1/eudem25m?locations=$lat,$lon";
$response = file_get_contents($remote);

if ($response !== false) {
    echo $response;
} else {
    echo json_encode(['error' => 'Unable to fetch data']);
}
