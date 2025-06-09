<?php
header('Content-Type: application/json');
require_once '../src/CoordinateConverter.php';

// Kontrollera om vi har fått en POST-förfrågan
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Endast POST-förfrågningar tillåtna']);
    exit;
}

// Hämta JSON-data
$inputData = json_decode(file_get_contents('php://input'), true);

// Kontrollera att vi har nödvändiga parametrar
if (!isset($inputData['coordinates'])) {
    echo json_encode(['error' => 'Felaktiga parametrar']);
    exit;
}

$coordinates = trim($inputData['coordinates']);

// Identifiera format
$format = CoordinateConverter::identifyFormat($coordinates);

if ($format === 'UNKNOWN') {
    echo json_encode(['error' => 'Okänt koordinatformat']);
    exit;
}

$coords = explode(',', $coordinates);
$lat = floatval(trim($coords[0]));
$lon = floatval(trim($coords[1]));

$result = [
    'detectedFormat' => $format,
    'wgs84' => null,
    'sweref99' => null,
    'rt90' => null
];

// Konvertera automatiskt till alla system
if ($format === 'WGS84') {
    $result['wgs84'] = ['lat' => $lat, 'lon' => $lon];
    $result['sweref99'] = CoordinateConverter::wgs84ToSweref99($lat, $lon);
    $result['rt90'] = CoordinateConverter::wgs84ToRt90($lat, $lon);
} elseif ($format === 'SWEREF99TM') {
    $wgs = CoordinateConverter::sweref99ToWgs84($lat, $lon);
    $result['wgs84'] = $wgs;
    $result['sweref99'] = ['north' => $lat, 'east' => $lon];
    $result['rt90'] = CoordinateConverter::wgs84ToRt90($wgs['lat'], $wgs['lon']);
} elseif ($format === 'RT90') {
    $wgs = CoordinateConverter::rt90ToWgs84($lat, $lon);
    $result['wgs84'] = $wgs;
    $result['sweref99'] = CoordinateConverter::wgs84ToSweref99($wgs['lat'], $wgs['lon']);
    $result['rt90'] = ['north' => $lat, 'east' => $lon];
}

// Skicka JSON-svar
echo json_encode($result);
exit;
