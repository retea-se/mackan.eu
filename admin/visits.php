<?php
// admin/visits.php - v2

// ******************** FELHANTERING ********************
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/php-error.log');
error_reporting(E_ALL);

session_start();
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: visits.php");
    exit;
}

// ******************** DB-UPPGIFTER ********************
$host = 'omega.hostup.se';
$db   = 'visits';
$db_user = 'visits_db_user';
$db_pass = 'I%O@$72RL_z%';
$port = 3306;

// ******************** LOGGNINGSFUNKTION ********************
function log_visit($page, $error_message = null) {
    $conn = @new mysqli($GLOBALS['host'], $GLOBALS['db_user'], $GLOBALS['db_pass'], $GLOBALS['db'], $GLOBALS['port']);
    if ($conn->connect_error) {
        error_log("DB-anslutningsfel: " . $conn->connect_error);
        return;
    }

    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    $referer = $_SERVER['HTTP_REFERER'] ?? null;
    $language = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? null;
    $get_data = json_encode($_GET, JSON_PARTIAL_OUTPUT_ON_ERROR);
    $post_data = json_encode($_POST, JSON_PARTIAL_OUTPUT_ON_ERROR);
    $cookies = json_encode($_COOKIE, JSON_PARTIAL_OUTPUT_ON_ERROR);

    $stmt = $conn->prepare("INSERT INTO visits (ip, user_agent, page, referer, language, get_data, post_data, cookies, error_message) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        error_log("Prepare-fel: " . $conn->error);
        $conn->close();
        return;
    }

    $stmt->bind_param("sssssssss", $ip, $user_agent, $page, $referer, $language, $get_data, $post_data, $cookies, $error_message);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}

// ******************** INLOGGNING ********************
$login_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = 'admin';
    $pass = 'byt-mig!';
    $input_user = $_POST['username'] ?? '';
    $input_pass = $_POST['password'] ?? '';
    if ($input_user === $user && $input_pass === $pass) {
        $_SESSION['admin_logged_in'] = true;
    } else {
        $login_error = 'Felaktigt användarnamn eller lösenord.';
        log_visit('admin/visits.php', 'Felaktig inloggning');
    }
}

if (empty($_SESSION['admin_logged_in'])):
?>
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <style>
        body { font-family: sans-serif; background: #f8f8f8; margin: 2em; }
        form { background: #fff; padding: 2em; max-width: 300px; margin: auto; border-radius: 8px; box-shadow: 0 2px 8px #ccc; }
        input { width: 100%; margin-bottom: 1em; padding: 0.5em; }
        .error { color: red; }
    </style>
    <link rel="icon" href="data:,">

</head>
<body>
    <form method="post">
        <h2>Admin Login</h2>
        <?php if ($login_error): ?><div class="error"><?= htmlspecialchars($login_error) ?></div><?php endif; ?>
        <input type="text" name="username" placeholder="Användarnamn" required>
        <input type="password" name="password" placeholder="Lösenord" required>
        <button type="submit">Logga in</button>
    </form>
    <script>console.log("visits.php - v2")</script>
</body>
</html>
<?php
exit;
endif;

// ******************** LADDA STATISTIK ********************
$conn = @new mysqli($host, $db_user, $db_pass, $db, $port);
if ($conn->connect_error) {
    error_log("DB-fel: " . $conn->connect_error);
    die('Kunde inte ansluta till databasen.');
}

$total = 0;
$rows = [];

$res = $conn->query("SELECT COUNT(*) AS total FROM visits");
if ($res) {
    $total = $res->fetch_assoc()['total'];
    $res->free();
} else {
    error_log("Fel vid COUNT(): " . $conn->error);
}

$res = $conn->query("SELECT * FROM visits ORDER BY visited_at DESC LIMIT 20");
if ($res) {
    $rows = $res->fetch_all(MYSQLI_ASSOC);
    $res->free();
} else {
    error_log("Fel vid SELECT: " . $conn->error);
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Besöksstatistik?! | Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #181c24;
            margin: 0;
            padding: 0;
            color: #e6e6e6;
        }
        .container {
            max-width: 1200px;
            margin: 2em auto;
            background: #232836;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.25);
            padding: 2em 2em 3em 2em;
        }
        h1 {
            margin-top: 0;
            font-size: 2.2em;
            letter-spacing: -1px;
            color: #fff;
        }
        .logout-btn {
            float: right;
            margin-top: -10px;
            margin-bottom: 1em;
            color: #fff;
            background: #e74c3c;
            padding: 10px 22px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.2s;
            border: none;
        }
        .logout-btn:hover {
            background: #c0392b;
        }
        .stats {
            margin-bottom: 1.5em;
            font-size: 1.1em;
            color: #b0b8c1;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            background: #232836;
            margin-bottom: 2em;
            font-size: 0.98em;
            color: #e6e6e6;
        }
        th, td {
            border: 1px solid #353b4a;
            padding: 8px 10px;
            text-align: left;
        }
        th {
            background: #232b3e;
            position: sticky;
            top: 0;
            z-index: 2;
            color: #fff;
        }
        tr:nth-child(even) {
            background: #20232e;
        }
        tr:hover {
            background: #2a3142;
        }
        pre {
            margin: 0;
            font-size: 0.95em;
            background: #181c24;
            border-radius: 4px;
            padding: 2px 4px;
            white-space: pre-wrap;
            word-break: break-all;
            color: #b0b8c1;
        }
        @media (max-width: 900px) {
            .container { padding: 1em 0.5em; }
            table, thead, tbody, th, td, tr { display: block; }
            th, td { border: none; }
            tr { margin-bottom: 1.2em; }
            th {
                background: none;
                position: static;
            }
            td {
                background: #232836;
                border-bottom: 1px solid #353b4a;
                position: relative;
                padding-left: 50%;
            }
            td:before {
                position: absolute;
                left: 10px;
                top: 8px;
                width: 45%;
                white-space: nowrap;
                font-weight: bold;
                color: #888;
                content: attr(data-label);
            }
        }
    </style>
    <link rel="icon" href="data:,">

</head>
<body>


<div class="container">
    <h1>📈 Besöksstatistik</h1>

    <div style="margin-bottom:1em;">
        <a href="insight.php" class="logout-btn" style="background:#1abc9c;margin-right:1em;">🔍 Insight</a>
        <a href="dashboard.php" class="logout-btn" style="background:#9b59b6;margin-right:1em;">📊 Dashboard</a>
        <a href="?logout=1" class="logout-btn" style="background:#e74c3c;">🚪 Logga ut</a>
    </div>

    <div class="stats"><strong>Totalt antal besök:</strong> <?= $total ?></div>
</div>


        <h2>Senaste besöken</h2>
        <div style="overflow-x:auto;">
        <table>
            <tr>
                <th>ID</th>
                <th>Tid</th>
                <th>IP</th>
                <th>User Agent</th>
                <th>Sida</th>
                <th>Referer</th>
                <th>Språk</th>
                <th>GET</th>
                <th>POST</th>
                <th>Cookies</th>
                <th>Klick</th>
                <th>Tid på sida</th>
                <th>Skärm</th>
                <th>Fel</th>
                <th>Enhet</th>
                <th>Tidszon</th>
                <th>Typ</th>
            </tr>
            <?php foreach ($rows as $row): ?>
            <tr>
                <td data-label="ID"><?= htmlspecialchars($row['id'] ?? '') ?></td>
                <td data-label="Tid"><?= htmlspecialchars($row['visited_at'] ?? '') ?></td>
                <td data-label="IP"><?= htmlspecialchars($row['ip'] ?? '') ?></td>
                <td data-label="User Agent"><?= htmlspecialchars($row['user_agent'] ?? '') ?></td>
                <td data-label="Sida"><?= htmlspecialchars($row['page'] ?? '') ?></td>
                <td data-label="Referer"><?= htmlspecialchars($row['referer'] ?? '') ?></td>
                <td data-label="Språk"><?= htmlspecialchars($row['language'] ?? '') ?></td>
                <td data-label="GET"><pre><?= htmlspecialchars($row['get_data'] ?? '') ?></pre></td>
                <td data-label="POST"><pre><?= htmlspecialchars($row['post_data'] ?? '') ?></pre></td>
                <td data-label="Cookies"><pre><?= htmlspecialchars($row['cookies'] ?? '') ?></pre></td>
                <td data-label="Klick"><?= htmlspecialchars($row['click_event'] ?? '') ?></td>
                <td data-label="Tid på sida"><?= htmlspecialchars($row['time_on_page'] ?? '') ?></td>
                <td data-label="Skärm"><?= htmlspecialchars($row['screen_size'] ?? '') ?></td>
                <td data-label="Fel"><?= htmlspecialchars($row['error_message'] ?? '') ?></td>
                <td data-label="Enhet"><?= htmlspecialchars($row['device_type'] ?? '') ?></td>
                <td data-label="Tidszon"><?= htmlspecialchars($row['timezone'] ?? '') ?></td>
                <td data-label="Typ" style="color:<?= is_bot($row['user_agent'] ?? '') ? '#e74c3c' : '#27ae60' ?>">
                    <?= is_bot($row['user_agent'] ?? '') ? '🤖 Bot' : '👤 Människa' ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        </div>
    </div>
    <script>console.log("visits.php - v2")</script>
</body>
</html>

<?php
function is_bot($user_agent) {
    if (!$user_agent) return false;
    $bots = [
        'bot', 'crawl', 'slurp', 'spider', 'curl', 'wget', 'python', 'scrapy', 'facebookexternalhit', 'mediapartners-google', 'google', 'bing', 'yandex', 'duckduckbot', 'baidu', 'sogou', 'exabot', 'facebot', 'ia_archiver'
    ];
    $ua = strtolower($user_agent);
    foreach ($bots as $bot) {
        if (strpos($ua, $bot) !== false) return true;
    }
    return false;
}
?>
