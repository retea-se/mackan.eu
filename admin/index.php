<?php

session_start();

// Logga ut
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

// Inloggning
$login_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = 'mackan';
    $pass = 'MaxHund2025!';
    $input_user = $_POST['username'] ?? '';
    $input_pass = $_POST['password'] ?? '';
    if ($input_user === $user && $input_pass === $pass) {
        $_SESSION['admin_logged_in'] = true;
    } else {
        $login_error = 'Felaktigt användarnamn eller lösenord.';
    }
}

// Om inte inloggad, visa formulär
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
</body>
</html>
<?php
exit;
endif;

// === HÄMTA NYCKELVÄRDEN FRÅN STATISTIK ===
$kortlank = $skyddad = $visits = [];
try {
    // Kortlänk
    require_once __DIR__ . '/../tools/kortlank/includes/db.php';
    $kortlank['antal'] = (int)$pdo->query("SELECT COUNT(*) FROM shortlinks")->fetchColumn();
    $kortlank['mest_popular'] = $pdo->query("SELECT custom_alias FROM shortlinks ORDER BY hits DESC LIMIT 1")->fetchColumn();
    $kortlank['mest_klick'] = (int)$pdo->query("SELECT MAX(hits) FROM shortlinks")->fetchColumn();

    // Skyddad
    require_once __DIR__ . '/../tools/skyddad/includes/config.php';
    require_once __DIR__ . '/../tools/skyddad/includes/db.php';
    $skyddad['antal'] = (int)$pdo->query("SELECT COUNT(*) FROM passwords")->fetchColumn();
    $skyddad['mest_visad'] = $pdo->query("SELECT id FROM passwords ORDER BY views_left DESC LIMIT 1")->fetchColumn();

    // Besök
    if (file_exists(__DIR__ . '/visits.php')) {
        $visits['antal'] = @file_get_contents(__DIR__ . '/../data/visits.txt');
    }
} catch (Throwable $e) {
    // Ignorera fel, visa inga nyckelvärden
}
?>
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Adminpanel</title>
    <style>
        body { font-family: sans-serif; background: #f8f8f8; margin: 2em; }
        .dash-container { max-width: 700px; margin: auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px #eee; padding: 2em; }
        header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2em; }
        h1 { margin: 0; }
        .btn { display: inline-block; padding: 0.5em 1em; background: #0074d9; color: #fff; border-radius: 4px; text-decoration: none; margin-bottom: 0.5em; }
        ul { list-style: none; padding: 0; }
        li { margin-bottom: 1em; }
        li .btn { width: 100%; text-align: left; font-size: 1.1em; }
        .nav-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2em; margin: 2em 0; }
        .nav-section h4 { margin: 0 0 1em 0; font-size: 1.1em; color: #0074d9; border-bottom: 2px solid #e0e7ef; padding-bottom: 0.5em; }
        .nav-section ul { list-style: none; padding: 0; margin: 0; }
        .nav-section li { margin-bottom: 0.5em; }
        .nav-section .btn { width: 100%; text-align: left; }
        .btn.nerd { background: #9b59b6; }
        .btn.security { background: #e74c3c; }
        .stats-row { display: flex; gap: 1.5em; margin: 2em 0; flex-wrap: wrap; }
        .stat-card { background: #f0f7ff; border-radius: 8px; padding: 1em 1.5em; min-width: 180px; flex: 1 1 180px; box-shadow: 0 1px 4px #e0e7ef; position: relative; }
        .stat-card h3 { margin: 0 0 0.3em 0; font-size: 1.1em; color: #0074d9; }
        .stat-card .big { font-size: 2em; font-weight: bold; }
        .stat-card small { color: #888; }
        .stat-card.realtime { background: #ffe6e6; animation: pulse 2s infinite; }
        .stat-card.realtime h3 { color: #e74c3c; }
        .stat-card.trend { background: #e8f5e8; }
        .stat-card.trend h3 { color: #27ae60; }
        .stat-card.security { background: #fff3cd; }
        .stat-card.security h3 { color: #856404; }
        .stat-card.performance { background: #e1f5fe; }
        .stat-card.performance h3 { color: #0277bd; }
        .trend-data, .security-data, .performance-data { display: flex; flex-direction: column; gap: 0.5em; }
        .trend-item, .security-item, .performance-item { display: flex; justify-content: space-between; font-size: 0.9em; }
        .trend-change { font-weight: bold; }
        .trend-change.positive { color: #27ae60; }
        .trend-change.negative { color: #e74c3c; }
        @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.7; } 100% { opacity: 1; } }
        @media (max-width: 768px) {
            .dash-container { padding: 1em; }
            header { flex-direction: column; gap: 1em; }
            .nav-grid { grid-template-columns: 1fr; gap: 1em; }
            .stats-row { flex-direction: column; gap: 1em; }
        }
    </style>
</head>
<body>
<div class="dash-container">
    <header>
        <h1>Adminpanel</h1>
        <a href="?logout=1" class="btn">🚪 Logga ut</a>
    </header>
    <div class="nav-grid">
        <div class="nav-section">
            <h4>📊 Analyser</h4>
            <ul>
                <li><a href="dashboard.php" class="btn">📊 Dashboard</a></li>
                <li><a href="insight.php" class="btn">🔍 Insight</a></li>
                <li><a href="pro-analytics.php" class="btn nerd">🤓 Pro Analytics</a></li>
                <li><a href="security-monitor.php" class="btn security">🛡️ Säkerhet</a></li>
            </ul>
        </div>
        <div class="nav-section">
            <h4>🔗 Kortlänkar</h4>
            <ul>
                <li><a href="kortlank-stats.php" class="btn">📈 Statistik</a></li>
                <li><a href="hantera-kortlankar.php" class="btn">🗑️ Hantera</a></li>
            </ul>
        </div>
        <div class="nav-section">
            <h4>🛡️ Skyddad</h4>
            <ul>
                <li><a href="skyddad-stats.php" class="btn">📊 Statistik</a></li>
                <li><a href="hantera-skyddad.php" class="btn">🗑️ Hantera</a></li>
            </ul>
        </div>
        <div class="nav-section">
            <h4>👥 Besökare</h4>
            <ul>
                <li><a href="visits.php" class="btn">📈 Besöksstatistik</a></li>
                <li><a href="geo-country.php" class="btn">🌍 Geolokalisering</a></li>
            </ul>
        </div>
    </div>

    <!-- Real-time statistik -->
    <div class="stats-row">
        <div class="stat-card realtime" id="realtime-visitors">
            <h3>🔴 Live Besökare</h3>
            <div class="big" id="live-count">-</div>
            <small>Online nu (senaste 5 min)</small>
        </div>
        <div class="stat-card">
            <h3>🔗 Kortlänkar</h3>
            <div class="big"><?= isset($kortlank['antal']) ? $kortlank['antal'] : '?' ?></div>
            <small>Mest klickad: <?= isset($kortlank['mest_popular']) ? htmlspecialchars($kortlank['mest_popular']) : '-' ?> (<?= isset($kortlank['mest_klick']) ? $kortlank['mest_klick'] : '?' ?> klick)</small>
        </div>
        <div class="stat-card">
            <h3>🛡️ Skyddad</h3>
            <div class="big"><?= isset($skyddad['antal']) ? $skyddad['antal'] : '?' ?></div>
            <small>Mest visad ID: <?= isset($skyddad['mest_visad']) ? htmlspecialchars($skyddad['mest_visad']) : '-' ?></small>
        </div>
        <div class="stat-card">
            <h3>📈 Besök</h3>
            <div class="big"><?= isset($visits['antal']) ? (int)$visits['antal'] : '?' ?></div>
            <small>Totalt antal besök</small>
        </div>
    </div>

    <!-- Trendanalys -->
    <div class="stats-row">
        <div class="stat-card trend">
            <h3>📊 Idag vs Igår</h3>
            <div class="trend-data" id="today-vs-yesterday">
                <div class="trend-item">
                    <span class="trend-label">Besök:</span>
                    <span class="trend-value" id="visits-trend">-</span>
                    <span class="trend-change" id="visits-change">-</span>
                </div>
                <div class="trend-item">
                    <span class="trend-label">Unika:</span>
                    <span class="trend-value" id="unique-trend">-</span>
                    <span class="trend-change" id="unique-change">-</span>
                </div>
            </div>
        </div>
        <div class="stat-card security">
            <h3>🛡️ Säkerhet</h3>
            <div class="security-data" id="security-stats">
                <div class="security-item">
                    <span class="security-label">Botar:</span>
                    <span class="security-value" id="bot-count">-</span>
                </div>
                <div class="security-item">
                    <span class="security-label">Misstänkt:</span>
                    <span class="security-value" id="suspicious-count">-</span>
                </div>
            </div>
        </div>
        <div class="stat-card performance">
            <h3>⚡ Prestanda</h3>
            <div class="performance-data" id="performance-stats">
                <div class="performance-item">
                    <span class="performance-label">Snitttid:</span>
                    <span class="performance-value" id="avg-time">-</span>
                </div>
                <div class="performance-item">
                    <span class="performance-label">Bounce:</span>
                    <span class="performance-value" id="bounce-rate">-</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Real-time updates
function updateRealTimeStats() {
    fetch('api/stats.php?action=realtime')
        .then(response => response.json())
        .then(data => {
            document.getElementById('live-count').textContent = data.live_visitors || 0;
            document.getElementById('visits-trend').textContent = data.today_visits || 0;
            document.getElementById('visits-change').textContent = data.visits_change || '0%';
            document.getElementById('visits-change').className = 'trend-change ' + (data.visits_change_type || 'neutral');
            document.getElementById('unique-trend').textContent = data.today_unique || 0;
            document.getElementById('unique-change').textContent = data.unique_change || '0%';
            document.getElementById('unique-change').className = 'trend-change ' + (data.unique_change_type || 'neutral');
            document.getElementById('bot-count').textContent = data.bot_count || 0;
            document.getElementById('suspicious-count').textContent = data.suspicious_count || 0;
            document.getElementById('avg-time').textContent = data.avg_time || '0s';
            document.getElementById('bounce-rate').textContent = data.bounce_rate || '0%';
        })
        .catch(error => console.error('Error updating stats:', error));
}

// Update stats on page load and every 30 seconds
updateRealTimeStats();
setInterval(updateRealTimeStats, 30000);

// Refresh indicator
function showRefreshIndicator() {
    const indicator = document.createElement('div');
    indicator.style.cssText = 'position:fixed;top:10px;right:10px;background:#27ae60;color:white;padding:0.5em 1em;border-radius:4px;z-index:1000;';
    indicator.textContent = 'Uppdaterar...';
    document.body.appendChild(indicator);
    setTimeout(() => document.body.removeChild(indicator), 1000);
}

setInterval(showRefreshIndicator, 30000);
</script>
</body>
</html>
