<?php
header('Content-Type: application/json');

// Skatteverkets dataset med testpersonnummer
$url = 'https://skatteverket.entryscape.net/rowstore/dataset/b4de7df7-63c0-4e7e-bb59-1f156a591763?_limit=1&_offset=' . rand(30500, 44000);
$data = json_decode(file_get_contents($url), true);

if (!$data || !isset($data['results'][0]['testpersonnummer'])) {
  echo json_encode(['error' => 'Ingen testperson kunde hämtas.']);
  exit;
}

$pnr = $data['results'][0]['testpersonnummer'];
echo json_encode(['personnummer' => $pnr]);
