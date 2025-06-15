<?php

session_start();
if (empty($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit;
}
require_once __DIR__ . '/../tools/kortlank/includes/db.php';

// Exportera till CSV
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=kortlankar.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Alias', 'Destination', 'Klick', 'Skapad', 'Status']);
    $stmt = $pdo->query("SELECT id, custom_alias, target_url, hits, created_at, is_active, expires_at FROM shortlinks ORDER BY created_at DESC");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $status = $row['is_active'] ? 'Aktiv' : 'Inaktiv';
        if ($row['expires_at'] && strtotime($row['expires_at']) < time()) {
            $status .= ' (Utgången)';
        }
        fputcsv($output, [
            $row['id'],
            $row['custom_alias'],
            $row['target_url'],
            $row['hits'],
            $row['created_at'],
            $status
        ]);
    }
    fclose($output);
    exit;
}

// Hantera radering och inaktivering
if (!empty($_POST['selected_ids']) && is_array($_POST['selected_ids'])) {
    $ids = array_map('trim', $_POST['selected_ids']);
    $in = str_repeat('?,', count($ids) - 1) . '?';
    if (isset($_POST['delete'])) {
        $stmt = $pdo->prepare("DELETE FROM shortlinks WHERE id IN ($in)");
        $stmt->execute($ids);
        $deleted = $stmt->rowCount();
    } elseif (isset($_POST['deactivate'])) {
        $stmt = $pdo->prepare("UPDATE shortlinks SET is_active = 0 WHERE id IN ($in)");
        $stmt->execute($ids);
        $deactivated = $stmt->rowCount();
    }
}

// Filtrering och sökning
$where = [];
$params = [];
if (!empty($_GET['older_than'])) {
    $where[] = "created_at < DATE_SUB(NOW(), INTERVAL ? DAY)";
    $params[] = (int)$_GET['older_than'];
}
if (!empty($_GET['max_clicks'])) {
    $where[] = "hits <= ?";
    $params[] = (int)$_GET['max_clicks'];
}
if (!empty($_GET['inactive'])) {
    $where[] = "is_active = 0";
}
if (!empty($_GET['expired'])) {
    $where[] = "expires_at IS NOT NULL AND expires_at < NOW()";
}
if (!empty($_GET['search'])) {
    $where[] = "(custom_alias LIKE ? OR target_url LIKE ?)";
    $params[] = '%' . $_GET['search'] . '%';
    $params[] = '%' . $_GET['search'] . '%';
}
$whereSql = $where ? "WHERE " . implode(" AND ", $where) : "";

// Tillåtna kolumner att sortera på
$sortable = [
    'id' => 'id',
    'custom_alias' => 'custom_alias',
    'target_url' => 'target_url',
    'hits' => 'hits',
    'created_at' => 'created_at',
    'is_active' => 'is_active'
];
$sort = $_GET['sort'] ?? 'created_at';
$order = strtolower($_GET['order'] ?? 'desc') === 'asc' ? 'asc' : 'desc';
$sortCol = $sortable[$sort] ?? 'created_at';

$links = $pdo->prepare("SELECT id, custom_alias, target_url, hits, created_at, is_active, expires_at FROM shortlinks $whereSql ORDER BY $sortCol $order LIMIT 100");
$links->execute($params);
$rows = $links->fetchAll();
?>
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Hantera kortlänkar</title>
    <link rel="stylesheet" href="admin.css">
    <script>
    // Visa antal valda länkar
    function updateSelectedCount() {
        const count = document.querySelectorAll('.chk:checked').length;
        document.getElementById('selectedCount').textContent = count;
    }
    window.addEventListener('DOMContentLoaded', function() {
        for(let c of document.querySelectorAll('.chk')) {
            c.addEventListener('change', updateSelectedCount);
        }
        document.getElementById('selectAll').addEventListener('change', function() {
            for(let c of document.querySelectorAll('.chk')) c.checked = this.checked;
            updateSelectedCount();
        });
        updateSelectedCount();
    });
    </script>
</head>
<body>
<div class="dash-container">
    <header>
        <h1>Hantera kortlänkar</h1>
        <a href="index.php" class="btn">⬅️ Tillbaka</a>
        <a href="kortlank-stats.php" class="btn" style="background:#0074d9;">📊 Till statistik</a>
    </header>

    <form method="get" class="filter-form">
        <div class="filter-row">
            <label>Visa länkar äldre än <input type="number" name="older_than" value="<?= htmlspecialchars($_GET['older_than'] ?? '') ?>" min="0" style="width:50px;"> dagar</label>
            <label>Max <input type="number" name="max_clicks" value="<?= htmlspecialchars($_GET['max_clicks'] ?? '') ?>" min="0" style="width:50px;"> klick</label>
            <label><input type="checkbox" name="inactive" value="1" <?= !empty($_GET['inactive']) ? 'checked' : '' ?>> Endast inaktiva</label>
            <label><input type="checkbox" name="expired" value="1" <?= !empty($_GET['expired']) ? 'checked' : '' ?>> Endast utgångna</label>
        </div>
        <div class="filter-row">
            <label>Sök alias/destination: <input type="text" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" style="width:180px;"></label>
            <button type="submit" class="btn">Filtrera</button>
        </div>
    </form>

    <?php if (isset($deleted)): ?>
        <div class="stat-card" style="background:#e0ffe0;">Raderade <?= $deleted ?> länkar.</div>
    <?php endif; ?>
    <?php if (isset($deactivated)): ?>
        <div class="stat-card" style="background:#ffe0e0;">Inaktiverade <?= $deactivated ?> länkar.</div>
    <?php endif; ?>

    <form method="post" onsubmit="return confirm('Är du säker på att du vill utföra åtgärden på valda länkar?');">
        <div style="margin-bottom:1em;">
            <strong>Antal valda länkar: <span id="selectedCount">0</span></strong>
        </div>
        <table>
            <tr>
                <th><input type="checkbox" id="selectAll"></th>
                <th><?= sort_link('Kortlänk', 'id', $sort, $order) ?></th>
                <th><?= sort_link('Alias', 'custom_alias', $sort, $order) ?></th>
                <th><?= sort_link('Destination', 'target_url', $sort, $order) ?></th>
                <th><?= sort_link('Klick', 'hits', $sort, $order) ?></th>
                <th><?= sort_link('Skapad', 'created_at', $sort, $order) ?></th>
                <th><?= sort_link('Status', 'is_active', $sort, $order) ?></th>
            </tr>
            <?php foreach ($rows as $row): ?>
            <tr>
                <td><input type="checkbox" class="chk" name="selected_ids[]" value="<?= htmlspecialchars($row['id']) ?>"></td>
                <td><a href="/m/<?= htmlspecialchars($row['custom_alias'] ?: $row['id']) ?>" target="_blank">
                    /m/<?= htmlspecialchars($row['custom_alias'] ?: $row['id']) ?></a></td>
                <td><?= htmlspecialchars($row['custom_alias']) ?></td>
                <td><a href="<?= htmlspecialchars($row['target_url']) ?>" target="_blank"><?= htmlspecialchars($row['target_url']) ?></a></td>
                <td><?= (int)$row['hits'] ?></td>
                <td><?= htmlspecialchars($row['created_at']) ?></td>
                <td>
                    <?= $row['is_active'] ? 'Aktiv' : 'Inaktiv' ?>
                    <?php if ($row['expires_at'] && strtotime($row['expires_at']) < time()): ?>
                        <br><span style="color:#c00;">Utgången</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <button type="submit" name="delete" class="btn" style="background:#c00;">Radera valda</button>
        <button type="submit" name="deactivate" class="btn" style="background:#ff9800;">Inaktivera valda</button>
    </form>
</div>
</body>
</html><?php
function sort_link($label, $col, $currentSort, $currentOrder) {
    $order = ($currentSort === $col && $currentOrder === 'asc') ? 'desc' : 'asc';
    $icon = '';
    if ($currentSort === $col) {
        $icon = $currentOrder === 'asc' ? ' <span style="font-size:0.9em;">▲</span>' : ' <span style="font-size:0.9em;">▼</span>';
    }
    // Behåll övriga GET-parametrar
    $params = $_GET;
    $params['sort'] = $col;
    $params['order'] = $order;
    $url = '?' . http_build_query($params);
    return "<a href=\"$url\" style=\"color:inherit;text-decoration:none;\">$label$icon</a>";
}
