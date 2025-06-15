<?php

session_start();
if (empty($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit;
}
require_once __DIR__ . '/../tools/kortlank/includes/db.php';

// Totalt antal kortlänkar
$totalLinks = $pdo->query("SELECT COUNT(*) FROM shortlinks")->fetchColumn();

// Totalt antal klick
$totalClicks = $pdo->query("SELECT SUM(hits) FROM shortlinks")->fetchColumn();

// Antal aktiva/inaktiva länkar
$activeLinks = $pdo->query("SELECT COUNT(*) FROM shortlinks WHERE is_active = 1")->fetchColumn();
$inactiveLinks = $pdo->query("SELECT COUNT(*) FROM shortlinks WHERE is_active = 0")->fetchColumn();

// Antal länkar med lösenord
$pwLinks = $pdo->query("SELECT COUNT(*) FROM shortlinks WHERE password_hash IS NOT NULL AND password_hash != ''")->fetchColumn();

// Antal utgångna länkar
$expiredLinks = $pdo->query("SELECT COUNT(*) FROM shortlinks WHERE expires_at IS NOT NULL AND expires_at < NOW()")->fetchColumn();

// Antal unika destinationer
$uniqueDest = $pdo->query("SELECT COUNT(DISTINCT target_url) FROM shortlinks")->fetchColumn();

// Senaste skapade länk
$latestLink = $pdo->query("SELECT id, custom_alias, target_url, created_at FROM shortlinks ORDER BY created_at DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);

// Senaste klickade länk
$latestClick = $pdo->query("SELECT id, custom_alias, target_url, last_hit FROM shortlinks WHERE last_hit IS NOT NULL ORDER BY last_hit DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);

// Mest klickade länkar (topp 10)
$topLinks = $pdo->query("SELECT id, custom_alias, target_url, hits, created_at FROM shortlinks ORDER BY hits DESC LIMIT 10")->fetchAll();

// Senaste skapade länkar (topp 10)
$recentLinks = $pdo->query("SELECT id, custom_alias, target_url, created_at FROM shortlinks ORDER BY created_at DESC LIMIT 10")->fetchAll();

// Antal länkar per dag (senaste 14 dagar)
$linksPerDay = $pdo->query("SELECT DATE(created_at) AS dag, COUNT(*) AS antal FROM shortlinks GROUP BY dag ORDER BY dag DESC LIMIT 14")->fetchAll();

// Antal klick per dag (senaste 14 dagar)
$clicksPerDay = $pdo->query("SELECT DATE(last_hit) AS dag, SUM(hits) AS klick FROM shortlinks WHERE last_hit IS NOT NULL GROUP BY dag ORDER BY dag DESC LIMIT 14")->fetchAll();

?>
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Kortlänk-statistik | Admin</title>
    <style>
        body { font-family: sans-serif; background: #f8f8f8; margin: 2em; }
        .stats, .stat-card { margin-bottom: 1em; }
        .stat-card { background: #fff; padding: 1em; border-radius: 8px; box-shadow: 0 2px 8px #eee; display: inline-block; margin-right: 1em; }
        table { border-collapse: collapse; width: 100%; background: #fff; margin-bottom: 2em; }
        th, td { border: 1px solid #ddd; padding: 0.5em; }
        th { background: #f0f0f0; }
        h2 { margin-top: 2em; }
        .btn { display: inline-block; padding: 0.5em 1em; background: #0074d9; color: #fff; border-radius: 4px; text-decoration: none; margin-bottom: 1em; }
    </style>
</head>
<body>
<a href="index.php" class="btn">⬅️ Tillbaka till admin</a>
<a href="hantera-kortlankar.php" class="btn" style="background:#ff9800;">🗑️ Hantera kortlänkar</a>
<h1>🔗 Kortlänk-statistik</h1>

<div class="stats">
    <div class="stat-card">Totalt antal kortlänkar: <strong><?= $totalLinks ?></strong></div>
    <div class="stat-card">Totalt antal klick: <strong><?= $totalClicks ?></strong></div>
    <div class="stat-card">Aktiva länkar: <strong><?= $activeLinks ?></strong></div>
    <div class="stat-card">Inaktiva länkar: <strong><?= $inactiveLinks ?></strong></div>
    <div class="stat-card">Länkar med lösenord: <strong><?= $pwLinks ?></strong></div>
    <div class="stat-card">Utgångna länkar: <strong><?= $expiredLinks ?></strong></div>
    <div class="stat-card">Unika destinationer: <strong><?= $uniqueDest ?></strong></div>
</div>

<h2>Senaste skapade länk</h2>
<?php if ($latestLink): ?>
    <div class="stat-card">
        Kortlänk: <a href="/m/<?= htmlspecialchars($latestLink['custom_alias'] ?: $latestLink['id']) ?>" target="_blank">
        /m/<?= htmlspecialchars($latestLink['custom_alias'] ?: $latestLink['id']) ?></a><br>
        Destination: <a href="<?= htmlspecialchars($latestLink['target_url']) ?>" target="_blank"><?= htmlspecialchars($latestLink['target_url']) ?></a><br>
        Skapad: <?= htmlspecialchars($latestLink['created_at']) ?>
    </div>
<?php endif; ?>

<h2>Senaste klickade länk</h2>
<?php if ($latestClick): ?>
    <div class="stat-card">
        Kortlänk: <a href="/m/<?= htmlspecialchars($latestClick['custom_alias'] ?: $latestClick['id']) ?>" target="_blank">
        /m/<?= htmlspecialchars($latestClick['custom_alias'] ?: $latestClick['id']) ?></a><br>
        Destination: <a href="<?= htmlspecialchars($latestClick['target_url']) ?>" target="_blank"><?= htmlspecialchars($latestClick['target_url']) ?></a><br>
        Senaste klick: <?= htmlspecialchars($latestClick['last_hit']) ?>
    </div>
<?php endif; ?>

<h2>Mest klickade kortlänkar (topp 10)</h2>
<table>
    <tr>
        <th>Kortlänk</th>
        <th>Alias</th>
        <th>Destination</th>
        <th>Klick</th>
        <th>Skapad</th>
    </tr>
    <?php foreach ($topLinks as $row): ?>
    <tr>
        <td><a href="/m/<?= htmlspecialchars($row['custom_alias'] ?: $row['id']) ?>" target="_blank">
            /m/<?= htmlspecialchars($row['custom_alias'] ?: $row['id']) ?></a></td>
        <td><?= htmlspecialchars($row['custom_alias']) ?></td>
        <td><a href="<?= htmlspecialchars($row['target_url']) ?>" target="_blank"><?= htmlspecialchars($row['target_url']) ?></a></td>
        <td><?= (int)$row['hits'] ?></td>
        <td><?= htmlspecialchars($row['created_at']) ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<h2>Senaste skapade kortlänkar (topp 10)</h2>
<table>
    <tr>
        <th>Kortlänk</th>
        <th>Alias</th>
        <th>Destination</th>
        <th>Skapad</th>
    </tr>
    <?php foreach ($recentLinks as $row): ?>
    <tr>
        <td><a href="/m/<?= htmlspecialchars($row['custom_alias'] ?: $row['id']) ?>" target="_blank">
            /m/<?= htmlspecialchars($row['custom_alias'] ?: $row['id']) ?></a></td>
        <td><?= htmlspecialchars($row['custom_alias']) ?></td>
        <td><a href="<?= htmlspecialchars($row['target_url']) ?>" target="_blank"><?= htmlspecialchars($row['target_url']) ?></a></td>
        <td><?= htmlspecialchars($row['created_at']) ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<h2>Antal skapade länkar per dag (senaste 14 dagar)</h2>
<table>
    <tr>
        <th>Datum</th>
        <th>Antal skapade länkar</th>
    </tr>
    <?php foreach ($linksPerDay as $row): ?>
    <tr>
        <td><?= htmlspecialchars($row['dag']) ?></td>
        <td><?= (int)$row['antal'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<h2>Antal klick per dag (senaste 14 dagar)</h2>
<table>
    <tr>
        <th>Datum</th>
        <th>Antal klick</th>
    </tr>
    <?php foreach ($clicksPerDay as $row): ?>
    <tr>
        <td><?= htmlspecialchars($row['dag']) ?></td>
        <td><?= (int)$row['klick'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
