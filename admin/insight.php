<?php
// ******************* START insight.php - v2 *******************
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: visits.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="sv">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Besöksanalys – Insight</title>
  <link rel="stylesheet" href="dashboard.css" />
  <link rel="icon" href="data:,">

</head>

<body>
  <div class="dash-container">
    <header>
      <h1>📖 Insight</h1>
      <div style="display:flex;gap:0.5em;">
        <a href="visits.php" class="btn">← Tillbaka</a>
        <a href="?logout=1" class="btn">🚪 Logga ut</a>
      </div>
    </header>

    <!-- ********** START Filter ********** -->
    <section class="filter-container" style="margin:1rem 0;">
      <label for="timeFilter"><strong>Tidsintervall:</strong></label>
      <select id="timeFilter" class="btn">
        <option value="1h">Senaste timmen</option>
        <option value="24h" selected>Senaste 24h</option>
        <option value="7d">Senaste 7 dagar</option>
        <option value="all">Alla</option>
      </select>
    </section>
    <!-- ********** SLUT Filter ********** -->

    <!-- ********** START Statistik ********** -->
    <section id="statSummary" class="mt-1">
      <!-- Basstatistik skrivs ut här via JS -->
    </section>

    <!-- ********** START Exportknappar ********** -->
    <div id="exportTools" class="horizontal-tools mt-1">
      <!-- Knappar skapas i insight.js via initExportButtons() -->
    </div>
    <!-- ********** SLUT Exportknappar ********** -->

    <!-- ********** START Accordion ********** -->
    <section id="visitorAccordion" class="mt-2">
      <!-- Accordion laddas av JS -->
    </section>
<div id="exportTools" class="horizontal-tools mt-1"></div>

    <!-- ********** START Fulltabell ********** -->
    <section id="fullTableSection" class="mt-2">
      <!-- Full datatabell genereras av JS -->
    </section>
    <!-- ********** SLUT Fulltabell ********** -->

  </div>

  <script src="insight.js"></script>
  <script src="export.js"></script>
  <script src="sort.js"></script>
</body>
</html>
