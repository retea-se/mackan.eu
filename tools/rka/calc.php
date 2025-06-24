<?php
// calc.php  –  JSON in, JSON ut
header('Content-Type: application/json');

$payload = json_decode(file_get_contents('php://input'), true);
if (!$payload) { http_response_code(400); exit; }

$kva   = $payload['kva']   ?? 0;
$load  = $payload['loadPct'] ?? 0;
$h     = $payload['hours'] ?? 0;
$fuel  = $payload['fuel']  ?? 'DIESEL';

$SCH  = 0.25;
$REF  = 0.25;
$fuelFac = ['DIESEL'=>1.0,'HVO100'=>1.04,'ECOPAR'=>0.93][$fuel] ?? 1.0;

$lph   = $kva * ($load/100) * $SCH;
$fuelL = $lph * $h * $fuelFac;
$kWh   = $kva * ($load/100) * $h;
$sc    = $fuelL / $kWh;
$gamma = $sc / $REF;

$cls = $gamma<=1.2 ? 'green' : ($gamma<=1.6 ? 'yellow' : 'red');

echo json_encode([
  'fuelL' => round($fuelL,1),
  'specCons' => round($sc,2),
  'gamma' => round($gamma,2),
  'class' => $cls
]);
