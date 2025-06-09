<?php
// ******************** START visits-export.php - v6 ********************
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

// ******************** KONFIGURATION ********************
$host     = 'omega.hostup.se';
$db       = 'visits';
$db_user  = 'visits_db_user';
$db_pass  = 'I%O@$72RL_z%';
$port     = 3306;
$limit    = 1000;

// ******************** INLOGGNINGSKONTROLL ********************
session_start();
if (empty($_SESSION['admin_logged_in'])) {
    http_response_code(403);
    echo json_encode(["error" => "Du är inte inloggad."]);
    exit;
}

// ******************** ANSLUTNING ********************
$conn = new mysqli($host, $db_user, $db_pass, $db, $port);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Databasfel: " . $conn->connect_error]);
    exit;
}

// ******************** HÄMTA ALLA BESÖK ********************
$data = [];
$sql = "SELECT * FROM visits ORDER BY visited_at DESC LIMIT ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $limit);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $data[] = [
        "ID"           => (int) $row['id'],
        "Tid"          => $row['visited_at'],
        "IP"           => $row['ip'],
        "User Agent"   => $row['user_agent'],
        "Sida"         => $row['page'],
        "Referer"      => $row['referer'],
        "Språk"        => $row['language'],
        "GET"          => safe_json($row['get_data']),
        "POST"         => safe_json($row['post_data']),
        "Cookies"      => safe_json($row['cookies']),
        "Klick"        => $row['click_event'],
        "Tid på sida"  => $row['time_on_page'],
        "Skärm"        => $row['screen_size'],
        "Fel"          => $row['error_message'],
        "Enhet"        => $row['device_type'],
        "Tidszon"      => $row['timezone'],
        "Typ"          => is_bot($row['user_agent']) ? '🤖 Bot' : '👤 Människa'
    ];
}

$stmt->close();
$conn->close();

echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

// ******************** HJÄLPFUNKTIONER ********************
function safe_json($raw) {
    $decoded = json_decode($raw, true);
    return is_array($decoded) ? $decoded : [];
}

function is_bot($user_agent) {
    $bots = ['bot','crawl','slurp','spider','curl','wget','python','scrapy','google','bing','yandex','duckduckbot','baidu','sogou','exabot','facebot','ia_archiver'];
    $ua = strtolower($user_agent ?? '');
    foreach ($bots as $bot) {
        if (strpos($ua, $bot) !== false) return true;
    }
    return false;
}

// ******************** SLUT visits-export.php - v6 ********************
