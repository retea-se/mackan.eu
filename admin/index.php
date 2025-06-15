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
        .stats-row { display: flex; gap: 2em; margin: 2em 0 0 0; flex-wrap: wrap; }
        .stat-card { background: #f0f7ff; border-radius: 8px; padding: 1em 1.5em; min-width: 180px; flex: 1 1 180px; box-shadow: 0 1px 4px #e0e7ef; }
        .stat-card h3 { margin: 0 0 0.3em 0; font-size: 1.1em; color: #0074d9; }
        .stat-card .big { font-size: 2em; font-weight: bold; }
        .stat-card small { color: #888; }
        @media (max-width: 600px) {
            .dash-container { padding: 1em; }
            header { flex-direction: column; gap: 1em; }
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
    <ul>
        <li><a href="dashboard.php" class="btn">📊 Dashboard</a></li>
        <li><a href="insight.php" class="btn">🔍 Insight</a></li>
        <li><a href="kortlank-stats.php" class="btn">🔗 Kortlänk-statistik</a></li>
        <li><a href="hantera-kortlankar.php" class="btn">🗑️ Hantera kortlänkar</a></li>
        <li><a href="visits.php" class="btn">📈 Besöksstatistik</a></li>
        <li><a href="skyddad-stats.php" class="btn">🛡️ Skyddad-statistik</a></li>
        <li><a href="hantera-skyddad.php" class="btn">🗑️ Hantera Skyddad-meddelanden</a></li>
        <!-- Lägg till fler länkar här om du vill -->
    </ul>

    <!-- Nyckelvärden/statistik -->
    <div class="stats-row">
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
</div>
</body>
</html>
