<?php
// consolidated-stats.php - Consolidated statistics API from all databases
session_start();

// Check authentication
if (empty($_SESSION['admin_logged_in'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

header('Content-Type: application/json; charset=utf-8');

// Simple file-based caching (5 minutes)
$cacheFile = __DIR__ . '/../cache/stats-cache.json';
$cacheTime = 300; // 5 minutes

function getCachedStats() {
    global $cacheFile, $cacheTime;

    if (file_exists($cacheFile)) {
        $cacheData = json_decode(file_get_contents($cacheFile), true);
        if ($cacheData && (time() - $cacheData['timestamp']) < $cacheTime) {
            return $cacheData['data'];
        }
    }
    return null;
}

function setCachedStats($data) {
    global $cacheFile;

    // Ensure cache directory exists
    $cacheDir = dirname($cacheFile);
    if (!is_dir($cacheDir)) {
        mkdir($cacheDir, 0755, true);
    }

    file_put_contents($cacheFile, json_encode([
        'timestamp' => time(),
        'data' => $data
    ]));
}

// Try to get cached data
$cached = getCachedStats();
if ($cached !== null) {
    echo json_encode($cached);
    exit;
}

// Initialize response
$stats = [
    'visits' => [],
    'kortlank' => [],
    'skyddad' => [],
    'timestamp' => time()
];

// ========== VISITS DATABASE ==========
try {
    $host = 'omega.hostup.se';
    $db = 'visits';
    $db_user = 'visits_db_user';
    $db_pass = 'I%O@$72RL_z%';
    $port = 3306;

    $conn = new mysqli($host, $db_user, $db_pass, $db, $port);

    if (!$conn->connect_error) {
        // Total visits
        $result = $conn->query("SELECT COUNT(*) as total FROM visits");
        $stats['visits']['total'] = (int)($result->fetch_assoc()['total'] ?? 0);

        // Unique IPs
        $result = $conn->query("SELECT COUNT(DISTINCT ip) as unique_ips FROM visits");
        $stats['visits']['unique_ips'] = (int)($result->fetch_assoc()['unique_ips'] ?? 0);

        // Today's visits
        $result = $conn->query("SELECT COUNT(*) as today FROM visits WHERE DATE(visited_at) = CURDATE()");
        $stats['visits']['today'] = (int)($result->fetch_assoc()['today'] ?? 0);

        // Yesterday's visits
        $result = $conn->query("SELECT COUNT(*) as yesterday FROM visits WHERE DATE(visited_at) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)");
        $stats['visits']['yesterday'] = (int)($result->fetch_assoc()['yesterday'] ?? 0);

        // Live visitors (last 5 minutes)
        $result = $conn->query("SELECT COUNT(DISTINCT ip) as live FROM visits WHERE visited_at >= DATE_SUB(NOW(), INTERVAL 5 MINUTE)");
        $stats['visits']['live'] = (int)($result->fetch_assoc()['live'] ?? 0);

        // Bots vs humans
        $result = $conn->query("SELECT
            SUM(CASE WHEN user_agent LIKE '%bot%' OR user_agent LIKE '%crawl%' OR user_agent LIKE '%spider%' THEN 1 ELSE 0 END) as bots,
            SUM(CASE WHEN user_agent NOT LIKE '%bot%' AND user_agent NOT LIKE '%crawl%' AND user_agent NOT LIKE '%spider%' THEN 1 ELSE 0 END) as humans
            FROM visits");
        $row = $result->fetch_assoc();
        $stats['visits']['bots'] = (int)($row['bots'] ?? 0);
        $stats['visits']['humans'] = (int)($row['humans'] ?? 0);

        // Top pages (last 7 days)
        $result = $conn->query("SELECT page, COUNT(*) as count FROM visits
            WHERE visited_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
            GROUP BY page
            ORDER BY count DESC
            LIMIT 10");
        $stats['visits']['top_pages'] = [];
        while ($row = $result->fetch_assoc()) {
            $stats['visits']['top_pages'][] = [
                'page' => $row['page'],
                'count' => (int)$row['count']
            ];
        }

        // Hourly visits (last 24 hours)
        $result = $conn->query("SELECT HOUR(visited_at) as hour, COUNT(*) as count
            FROM visits
            WHERE visited_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
            GROUP BY HOUR(visited_at)
            ORDER BY hour");
        $stats['visits']['hourly'] = [];
        while ($row = $result->fetch_assoc()) {
            $stats['visits']['hourly'][] = [
                'hour' => (int)$row['hour'],
                'count' => (int)$row['count']
            ];
        }

        $conn->close();
    }
} catch (Exception $e) {
    error_log("Visits DB error: " . $e->getMessage());
}

// ========== KORTLANK DATABASE ==========
try {
    require_once __DIR__ . '/../../tools/kortlank/includes/db.php';

    // Store kortlank PDO in separate variable to avoid conflicts
    $kortlankPdo = $pdo;

    // Total links
    $stats['kortlank']['total'] = (int)$kortlankPdo->query("SELECT COUNT(*) FROM shortlinks")->fetchColumn();

    // Total clicks
    $stats['kortlank']['total_clicks'] = (int)($kortlankPdo->query("SELECT SUM(hits) FROM shortlinks")->fetchColumn() ?? 0);

    // Active/Inactive
    $stats['kortlank']['active'] = (int)$kortlankPdo->query("SELECT COUNT(*) FROM shortlinks WHERE is_active = 1")->fetchColumn();
    $stats['kortlank']['inactive'] = (int)$kortlankPdo->query("SELECT COUNT(*) FROM shortlinks WHERE is_active = 0")->fetchColumn();

    // With password
    $stats['kortlank']['with_password'] = (int)$kortlankPdo->query("SELECT COUNT(*) FROM shortlinks WHERE password_hash IS NOT NULL AND password_hash != ''")->fetchColumn();

    // Expired
    $stats['kortlank']['expired'] = (int)$kortlankPdo->query("SELECT COUNT(*) FROM shortlinks WHERE expires_at IS NOT NULL AND expires_at < NOW()")->fetchColumn();

    // Unique destinations
    $stats['kortlank']['unique_destinations'] = (int)$kortlankPdo->query("SELECT COUNT(DISTINCT target_url) FROM shortlinks")->fetchColumn();

    // Top links (by hits)
    $result = $kortlankPdo->query("SELECT id, custom_alias, target_url, hits, created_at
        FROM shortlinks
        ORDER BY hits DESC
        LIMIT 10");
    $stats['kortlank']['top_links'] = $result->fetchAll(PDO::FETCH_ASSOC);

    // Recent links
    $result = $kortlankPdo->query("SELECT id, custom_alias, target_url, created_at
        FROM shortlinks
        ORDER BY created_at DESC
        LIMIT 10");
    $stats['kortlank']['recent_links'] = $result->fetchAll(PDO::FETCH_ASSOC);

    // Links per day (last 14 days)
    $result = $kortlankPdo->query("SELECT DATE(created_at) AS day, COUNT(*) AS count
        FROM shortlinks
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 14 DAY)
        GROUP BY DATE(created_at)
        ORDER BY day DESC");
    $stats['kortlank']['per_day'] = $result->fetchAll(PDO::FETCH_ASSOC);

    // Clicks per day (last 14 days)
    $result = $kortlankPdo->query("SELECT DATE(last_hit) AS day, SUM(hits) AS clicks
        FROM shortlinks
        WHERE last_hit >= DATE_SUB(NOW(), INTERVAL 14 DAY) AND last_hit IS NOT NULL
        GROUP BY DATE(last_hit)
        ORDER BY day DESC");
    $stats['kortlank']['clicks_per_day'] = $result->fetchAll(PDO::FETCH_ASSOC);

    // Unset kortlank PDO to avoid conflicts
    unset($kortlankPdo);

} catch (Exception $e) {
    error_log("Kortlank DB error: " . $e->getMessage());
}

// ========== SKYDDAD DATABASE ==========
try {
    require_once __DIR__ . '/../../tools/skyddad/includes/config.php';
    require_once __DIR__ . '/../../tools/skyddad/includes/db.php';

    // Store skyddad PDO in separate variable to avoid conflicts
    $skyddadPdo = $pdo;

    // Total secrets
    $stats['skyddad']['total'] = (int)$skyddadPdo->query("SELECT COUNT(*) FROM passwords")->fetchColumn();

    // Event counts
    $stats['skyddad']['created'] = (int)$skyddadPdo->query("SELECT COUNT(*) FROM log_events WHERE event_type = 'created'")->fetchColumn();
    $stats['skyddad']['viewed'] = (int)$skyddadPdo->query("SELECT COUNT(*) FROM log_events WHERE event_type = 'viewed'")->fetchColumn();
    $stats['skyddad']['cron'] = (int)$skyddadPdo->query("SELECT COUNT(*) FROM log_events WHERE event_type = 'cron'")->fetchColumn();

    // Recent events (last 7 days)
    $result = $skyddadPdo->query("SELECT * FROM log_events
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
        ORDER BY created_at DESC
        LIMIT 50");
    $stats['skyddad']['recent_events'] = $result->fetchAll(PDO::FETCH_ASSOC);

    // Events per day (last 14 days)
    $result = $skyddadPdo->query("SELECT DATE(created_at) AS day, event_type, COUNT(*) AS count
        FROM log_events
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 14 DAY)
        GROUP BY DATE(created_at), event_type
        ORDER BY day DESC");
    $stats['skyddad']['events_per_day'] = $result->fetchAll(PDO::FETCH_ASSOC);

    // Most viewed secrets
    $result = $skyddadPdo->query("SELECT secret_id, COUNT(*) as view_count
        FROM log_events
        WHERE event_type = 'viewed'
        GROUP BY secret_id
        ORDER BY view_count DESC
        LIMIT 10");
    $stats['skyddad']['most_viewed'] = $result->fetchAll(PDO::FETCH_ASSOC);

    // Unset skyddad PDO to avoid conflicts
    unset($skyddadPdo);

} catch (Exception $e) {
    error_log("Skyddad DB error: " . $e->getMessage());
}

// Cache the results
setCachedStats($stats);

// Return JSON
echo json_encode($stats, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

