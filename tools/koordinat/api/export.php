<?php
header('Content-Type: application/json');

// Dummy-koordinater för testning
$coordinates = [
    ["latitude" => 59.3293, "longitude" => 18.0686, "elevation" => 27, "sweref99_zone" => "SWEREF99 TM"],
    ["latitude" => 59.334591, "longitude" => 18.06324, "elevation" => 27, "sweref99_zone" => "SWEREF99 TM"],
    ["latitude" => 59.338825, "longitude" => 18.080895, "elevation" => 29, "sweref99_zone" => "SWEREF99 TM"],
    ["latitude" => 59.325117, "longitude" => 18.071093, "elevation" => 28, "sweref99_zone" => "SWEREF99 TM"],
    ["latitude" => 59.342115, "longitude" => 18.045876, "elevation" => 42, "sweref99_zone" => "SWEREF99 TM"]
];

echo json_encode($coordinates);
?>
