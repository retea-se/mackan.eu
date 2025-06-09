<?php
// filepath: includes/logger.php

// Databasuppgifter
$host = 'omega.hostup.se';
$user = 'visits_db_user';
$pass = 'I%O@$72RL_z%';
$db   = 'visits';
$port = 3306;

// Hämta data från POST (om AJAX)
$data = json_decode(file_get_contents('php://input'), true);

// Fallback om något saknas
function safe($arr, $key) {
    return isset($arr[$key]) ? $arr[$key] : null;
}

$ip           = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$user_agent   = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
$page         = safe($data, 'page') ?? ($_SERVER['REQUEST_URI'] ?? null);
$referer      = $_SERVER['HTTP_REFERER'] ?? null;
$language     = safe($data, 'language') ?? ($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? null);
$get_data     = json_encode($_GET);
$post_data    = json_encode($_POST);
$cookies      = json_encode($_COOKIE);
$click_event  = safe($data, 'click_event');
$time_on_page = safe($data, 'time_on_page');
$screen_size  = safe($data, 'screen_size');
$error_message= safe($data, 'error_message');
$device_type  = safe($data, 'device_type');
$timezone     = safe($data, 'timezone');

$conn = new mysqli($host, $user, $pass, $db, $port);
if ($conn->connect_error) {
    http_response_code(500);
    exit('DB error');
}

$stmt = $conn->prepare("INSERT INTO visits (
    ip, user_agent, page, referer, language,
    get_data, post_data, cookies, click_event,
    time_on_page, screen_size, error_message, device_type, timezone
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param(
    "ssssssssssssss",
    $ip, $user_agent, $page, $referer, $language,
    $get_data, $post_data, $cookies, $click_event,
    $time_on_page, $screen_size, $error_message,
    $device_type, $timezone
);
$stmt->execute();
$stmt->close();
$conn->close();

// echo "OK";
?>
