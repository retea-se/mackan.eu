<?php
// ******************* START insight.php - v3 *******************
// Adminpanel för besöksstatistik – listformat & statistik-kort
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
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #f8f8f8;
      margin: 0;
      color: #22223b;
    }
    .dash-container {
      max-width: 900px;
      margin: 2em auto;
      padding: 0 1em;
    }
    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2em;
    }
    h1 {
      margin: 0;
      font-size: 2rem;
      letter-spacing: 0.01em;
    }
    .btn {
      display: inline-block;
      padding: 0.5em 1.2em;
      background: #1976d2;
      color: #fff;
      border-radius: 4px;
      text-decoration: none;
      margin-left: 0.5em;
      border: none;
      cursor: pointer;
      font-size: 1em;
      font-family: inherit;
      transition: background 0.2s;
    }
    .btn:hover {
      background: #1256a3;
    }
    .stat-list {
      display: flex;
      gap: 1.5em;
      margin-bottom: 2em;
      flex-wrap: wrap;
    }
    .stat-card {
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 8px #eee;
      padding: 1.2em 2em;
      min-width: 160px;
      text-align: center;
      margin-bottom: 1em;
      flex: 1 1 160px;
    }
    .stat-label {
      color: #888;
      font-size: 0.95em;
      margin-bottom: 0.2em;
    }
    .stat-value {
      font-size: 1.6em;
      font-weight: bold;
      margin-top: 0.2em;
      letter-spacing: 0.01em;
    }
    .filter-container {
      margin: 1.5em 0 2em 0;
    }
    .visit-list {
      list-style: none;
      padding: 0;
      margin: 0;
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 8px #eee;
    }
    .visit-list li {
      display: flex;
      gap: 1.2em;
      padding: 0.8em 1em;
      border-bottom: 1px solid #f0f0f0;
      align-items: center;
      font-size: 1em;
    }
    .visit-list li:last-child {
      border-bottom: none;
    }
    .visit-time { min-width: 130px; color: #555; font-family: monospace; }
    .visit-ip { min-width: 100px; color: #888; font-family: monospace; }
    .visit-page { flex: 1; }
    .visit-ref { color: #888; min-width: 90px; }
    @media (max-width: 700px) {
      .stat-list { flex-direction: column; gap: 0.7em; }
      .visit-list li { flex-direction: column; align-items: flex-start; gap: 0.2em; }
      .visit-time, .visit-ip, .visit-page, .visit-ref { min-width: 0; }
    }
    h2 {
      margin-top: 2.5em;
      margin-bottom: 0.7em;
      font-size: 1.2em;
      letter-spacing: 0.01em;
      color: #1976d2;
    }
  </style>
</head>
<body>
  <div class="dash-container">
    <header>
      <h1>📖 Insight</h1>
      <div>
        <a href="visits.php" class="btn">← Tillbaka</a>
        <a href="?logout=1" class="btn">🚪 Logga ut</a>
      </div>
    </header>

    <!-- Filter för tidsintervall -->
    <section class="filter-container">
      <label for="timeFilter"><strong>Tidsintervall:</strong></label>
      <select id="timeFilter" class="btn">
        <option value="1h">Senaste timmen</option>
        <option value="24h" selected>Senaste 24h</option>
        <option value="7d">Senaste 7 dagar</option>
        <option value="all">Alla</option>
      </select>
    </section>

    <!-- Statistik-kort -->
    <section class="stat-list" id="statSummary">
      <div class="stat-card">
        <div class="stat-label">Totalt antal besök</div>
        <div class="stat-value" id="stat-total">1 234</div>
      </div>
      <div class="stat-card">
        <div class="stat-label">Unika besökare</div>
        <div class="stat-value" id="stat-unique">567</div>
      </div>
      <div class="stat-card">
        <div class="stat-label">Sidvisningar</div>
        <div class="stat-value" id="stat-views">2 345</div>
      </div>
    </section>

    <!-- Exportknappar -->
    <div id="exportTools" class="form__verktyg" style="margin-bottom:2em;">
      <button class="btn" onclick="exportVisits()">Exportera CSV</button>
      <button class="btn" onclick="exportVisits('json')">Exportera JSON</button>
    </div>

    <!-- Lista över senaste besök -->
    <h2>Senaste besök</h2>
    <ul class="visit-list" id="visitList"></ul>

    <!-- Accordion för sessioner -->
    <h2>Sessioner</h2>
    <section id="visitorAccordion" class="mt-2"></section>

    <!-- Full datatabell -->
    <h2>Full datatabell</h2>
    <section id="fullTableSection" class="mt-2"></section>
  </div>

  <script src="insight.js"></script>
  <script src="export.js"></script>
  <script>
    // Exempel: Dynamisk uppdatering av statistik och lista (ersätt med AJAX/JS)
    // document.getElementById('stat-total').textContent = ...;
    // document.getElementById('stat-unique').textContent = ...;
    // document.getElementById('stat-views').textContent = ...;
    // document.getElementById('visitList').innerHTML = ...;
    function exportVisits(type) {
      // Din exportfunktion här
      alert('Exportfunktion kommer här!');
    }

    function renderStats(data) {
      // Filtrera bort din egen IP
      const filtered = data.filter(r => r.IP !== "155.4.222.132");
      const statBox = document.getElementById("statSummary");
      if (!statBox) return;

      const unikaIP = [...new Set(filtered.map((r) => r.IP))].length;
      const människa = filtered.filter((r) => r.Typ?.includes("Människa")).length;
      const bot = filtered.length - människa;

      statBox.innerHTML = `
        <div class="stat-card">
          <div class="stat-label">Totalt antal besök</div>
          <div class="stat-value">${filtered.length}</div>
        </div>
        <div class="stat-card">
          <div class="stat-label">Unika besökare</div>
          <div class="stat-value">${unikaIP}</div>
        </div>
        <div class="stat-card">
          <div class="stat-label">👤 Mänskliga</div>
          <div class="stat-value">${människa}</div>
        </div>
        <div class="stat-card">
          <div class="stat-label">🤖 Botar</div>
          <div class="stat-value">${bot}</div>
        </div>
      `;
    }

    function renderVisitList(data) {
      // Filtrera bort din egen IP
      const filtered = data.filter(row => row.IP !== "155.4.222.132");
      const list = document.getElementById('visitList');
      list.innerHTML = filtered.map(row => `
        <li>
          <span class="visit-time">${row.time}</span>
          <span class="visit-ip">${row.IP}</span>
          <span class="visit-page">${row.page}</span>
          <span class="visit-ref">${row.ref}</span>
        </li>
      `).join('');
    }
  </script>
</body>
</html>
