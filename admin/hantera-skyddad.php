<?php

// admin/hantera-skyddad.php - v1
// Adminpanel för hantering av Skyddad-meddelanden (rensa, filtrera, exportera)

session_start();
if (empty($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit;
}
require_once __DIR__ . '/../tools/skyddad/includes/config.php';
require_once __DIR__ . '/../tools/skyddad/includes/db.php';

// Exportera till CSV
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=skyddad-meddelanden.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Förhandsvisning', 'Skapad', 'Utgår', 'Status', 'Antal visningar']);
    $stmt = $pdo->query("SELECT id, encrypted_data, expires_at, views_left FROM passwords ORDER BY expires_at DESC");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $preview = mb_substr(base64_decode($row['encrypted_data']), 0, 20) . '...';
        $status = ($row['views_left'] > 0 && $row['expires_at'] > time()) ? 'Aktiv' : 'Utgången';
        fputcsv($output, [
            $row['id'],
            $preview,
            date('Y-m-d H:i', $row['expires_at'] - 86400),
            date('Y-m-d H:i', $row['expires_at']),
            $status,
            $row['views_left']
        ]);
    }
    fclose($output);
    exit;
}

// Hantera radering
if (!empty($_POST['selected_ids']) && is_array($_POST['selected_ids'])) {
    $ids = array_filter($_POST['selected_ids'], fn($id) => preg_match('/^[a-f0-9]{32}$/', $id));
    if ($ids) {
        $in = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $pdo->prepare("DELETE FROM passwords WHERE id IN ($in)");
        $stmt->execute($ids);
        $deleted = $stmt->rowCount();
    }
}

// Filtrering
$where = [];
$params = [];
if (!empty($_GET['older_than'])) {
    $where[] = 'expires_at < ?';
    $params[] = time() - (int)$_GET['older_than'] * 86400;
}
if (!empty($_GET['active'])) {
    $where[] = 'views_left > 0 AND expires_at > ?';
    $params[] = time();
}
if (!empty($_GET['expired'])) {
    $where[] = 'expires_at < ?';
    $params[] = time();
}
if (!empty($_GET['search'])) {
    $where[] = 'id LIKE ?';
    $params[] = '%' . $_GET['search'] . '%';
}
$whereSql = $where ? "WHERE " . implode(" AND ", $where) : "";

// Sortering
$sortCol = 'expires_at';
$order = strtolower($_GET['order'] ?? 'desc') === 'asc' ? 'asc' : 'desc';

$links = $pdo->prepare("SELECT id, encrypted_data, expires_at, views_left FROM passwords $whereSql ORDER BY $sortCol $order LIMIT 100");
$links->execute($params);
$rows = $links->fetchAll();
?>
<!DOCTYPE html>
<html lang="sv">
<head>
  <meta charset="UTF-8">
  <title>Hantera Skyddad-meddelanden</title>
  <link rel="stylesheet" href="admin.css">
</head>
<body>
<div class="dash-container">
  <nav style="margin-bottom:1em;">
    <a href="index.php" class="btn">&larr; Tillbaka till adminpanelen</a>
  </nav>
  <h1>Hantera Skyddad-meddelanden</h1>
  <form method="get" style="margin-bottom:1em;">
    <input type="text" name="search" placeholder="Sök ID..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
    <label><input type="checkbox" name="active" value="1" <?= !empty($_GET['active']) ? 'checked' : '' ?>> Endast aktiva</label>
    <label><input type="checkbox" name="expired" value="1" <?= !empty($_GET['expired']) ? 'checked' : '' ?>> Endast utgångna</label>
    <label>Äldre än <input type="number" name="older_than" min="1" max="365" value="<?= htmlspecialchars($_GET['older_than'] ?? '') ?>" style="width:4em;"> dagar</label>
    <button type="submit">Filtrera</button>
    <a href="?export=csv" class="button" style="margin-left:1em;">Exportera CSV</a>
  </form>
  <form method="post">
    <table>
      <thead>
        <tr>
          <th><input type="checkbox" onclick="toggleAll(this)"></th>
          <th>ID</th>
          <th>Förhandsvisning</th>
          <th>Skapad</th>
          <th>Utgår</th>
          <th>Status</th>
          <th>Visningar kvar</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $row):
          $preview = htmlspecialchars(mb_substr(base64_decode($row['encrypted_data']), 0, 20)) . '...';
          $created = date('Y-m-d H:i', $row['expires_at'] - 86400);
          $expires = date('Y-m-d H:i', $row['expires_at']);
          $status = ($row['views_left'] > 0 && $row['expires_at'] > time()) ? 'Aktiv' : 'Utgången';
        ?>
        <tr>
          <td><input type="checkbox" name="selected_ids[]" value="<?= htmlspecialchars($row['id']) ?>"></td>
          <td><?= htmlspecialchars($row['id']) ?></td>
          <td><?= $preview ?></td>
          <td><?= $created ?></td>
          <td><?= $expires ?></td>
          <td><?= $status ?></td>
          <td><?= (int)$row['views_left'] ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <button type="submit" onclick="return confirm('Radera markerade meddelanden?')">Radera markerade</button>
  </form>
</div>
<script>
function toggleAll(box) {
  document.querySelectorAll('input[type=checkbox][name="selected_ids[]"]').forEach(cb => cb.checked = box.checked);
}
</script>
</body>
</html>
