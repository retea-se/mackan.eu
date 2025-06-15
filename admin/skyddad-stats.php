<?php

// skyddad-stats.php - v1
// Statistikpanel för Skyddad-verktyget (flyttad från tools/skyddad/admin.php)

require_once __DIR__ . '/../tools/skyddad/includes/bootstrap.php';

$title = 'Skyddad – Statistik';
$metaDescription = 'Statistik och logg för Skyddad-verktyget.';

// Datumfilter (default: senaste 7 dagar)
$days = isset($_GET['days']) ? (int)$_GET['days'] : 7;
$startDate = date('Y-m-d H:i:s', strtotime("-$days days"));

// Totalsiffror
$countCreated = $pdo->query("SELECT COUNT(*) FROM log_events WHERE event_type = 'created'")->fetchColumn();
$countViewed  = $pdo->query("SELECT COUNT(*) FROM log_events WHERE event_type = 'viewed'")->fetchColumn();
$countCron    = $pdo->query("SELECT COUNT(*) FROM log_events WHERE event_type = 'cron'")->fetchColumn();

// Händelser
$stmt = $pdo->prepare("SELECT * FROM log_events WHERE created_at >= ? ORDER BY created_at DESC LIMIT 100");
$stmt->execute([$startDate]);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Stapeldiagramdata per typ
$typeCounts = array_count_values(array_column($events, 'event_type'));
$types = array_keys($typeCounts);

?>
<!DOCTYPE html>
<html lang="sv">
<head>
  <meta charset="UTF-8">
  <title>Skyddad – Statistik</title>
  <link rel="stylesheet" href="admin.css">
</head>
<body>
<div class="dash-container">
  <nav style="margin-bottom:1em;">
    <a href="index.php" class="btn">&larr; Tillbaka till adminpanelen</a>
  </nav>
  <h1>Skyddad – Statistik &amp; logg</h1>

  <section>
    <h2>Snabbsiffror (senaste <?= htmlspecialchars($days) ?> dagar)</h2>
    <ul>
      <li>Skapade: <strong><?= $countCreated ?></strong></li>
      <li>Visade: <strong><?= $countViewed ?></strong></li>
      <li>Cron-jobb: <strong><?= $countCron ?></strong></li>
    </ul>
    <form method="get" style="margin-bottom:1em;">
      <label>Visa antal dagar:
        <input type="number" name="days" value="<?= htmlspecialchars($days) ?>" min="1" max="90" style="width:4em;">
      </label>
      <button type="submit">Uppdatera</button>
    </form>
  </section>

  <section>
    <h2>Händelselogg (max 100 senaste)</h2>
    <table>
      <thead>
        <tr>
          <th>Tidpunkt</th>
          <th>Typ</th>
          <th>Secret ID</th>
          <th>IP</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($events as $event): ?>
        <tr>
          <td><?= htmlspecialchars($event['created_at']) ?></td>
          <td><?= htmlspecialchars($event['event_type']) ?></td>
          <td><?= htmlspecialchars($event['secret_id']) ?></td>
          <td><?= htmlspecialchars($event['ip']) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </section>
</div>
</body>
</html>
