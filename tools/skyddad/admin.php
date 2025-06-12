<?php
// admin.php - v7
// git commit: Lägg till cronloggning via logEvent-funktion och fixa stapeldiagram korrekt

require_once __DIR__ . '/includes/bootstrap.php';

$title = 'Adminpanel';
$metaDescription = 'Administrativ vy för Skyddad-verktyget.';

// Hjälpfunktion för att logga händelser
function logEvent(string $type, string $secret_id = 'system', ?string $ip = null): void {
  global $pdo;
  $stmt = $pdo->prepare("INSERT INTO log_events (event_type, secret_id, ip) VALUES (?, ?, ?)");
  $stmt->execute([$type, $secret_id, $ip ?? $_SERVER['REMOTE_ADDR'] ?? 'unknown']);
}

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
$counts = array_values($typeCounts);

include '../../includes/layout-start.php';
?>

<main class="container">
  <h1 class="title">👁️‍🗨️ <?= $title ?></h1>

  <section class="card">
    <h2>📊 Statistik</h2>
    <ul>
      <li><strong>Totalt skapade:</strong> <?= $countCreated ?></li>
      <li><strong>Totalt visade:</strong> <?= $countViewed ?></li>
      <li><strong>Totalt cron-jobb:</strong> <?= $countCron ?></li>
    </ul>

    <form method="GET" style="margin-top: 1rem">
      <label for="days">Visa senaste</label>
      <select name="days" id="days" onchange="this.form.submit()">
        <option value="1" <?= $days === 1 ? 'selected' : '' ?>>1 dag</option>
        <option value="7" <?= $days === 7 ? 'selected' : '' ?>>7 dagar</option>
        <option value="30" <?= $days === 30 ? 'selected' : '' ?>>30 dagar</option>
      </select>
    </form>
  </section>

  <section class="card">
    <h2>📈 Diagram</h2>
    <div id="chart" style="height:400px"></div>
    <script src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>
    <script>
      const chart = echarts.init(document.getElementById('chart'));
      chart.setOption({
        tooltip: {},
        xAxis: { type: 'category', data: <?= json_encode($types) ?> },
        yAxis: { type: 'value' },
        series: [{
          name: 'Antal',
          type: 'bar',
          data: <?= json_encode($counts) ?>
        }]
      });
    </script>
  </section>

  <section class="card">
    <h2>📜 Händelser (senaste <?= $days ?> dagar)</h2>
    <div class="table-wrapper">
      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Typ</th>
            <th>Secret ID</th>
            <th>IP</th>
            <th>Skapad</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($events as $event): ?>
          <tr>
            <td><?= htmlspecialchars($event['id']) ?></td>
            <td><?= htmlspecialchars($event['event_type']) ?></td>
            <td><?= htmlspecialchars($event['secret_id']) ?></td>
            <td><?= htmlspecialchars($event['ip']) ?></td>
            <td><?= htmlspecialchars($event['created_at']) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </section>
</main>

<?php include '../../includes/layout-end.php'; ?>
