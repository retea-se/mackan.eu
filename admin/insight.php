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

    <!-- User Journey Analysis -->
    <h2>🛣️ Användarresor</h2>
    <section id="userJourneySection" class="mt-2">
        <div class="journey-filters" style="margin-bottom: 1em;">
            <label>Minimum sidvisningar: <input type="number" id="minPageViews" value="2" min="1" max="50" style="width: 4em;"></label>
            <button class="btn" onclick="updateJourneyAnalysis()">Uppdatera</button>
        </div>
        <div id="journeyResults"></div>
    </section>

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

    function updateJourneyAnalysis() {
      const minPageViews = document.getElementById('minPageViews').value;
      fetch(`api/stats.php?action=user_journey&min_pages=${minPageViews}`)
        .then(response => response.json())
        .then(data => renderUserJourneys(data))
        .catch(error => console.error('Error loading journey data:', error));
    }

    function renderUserJourneys(journeys) {
      const container = document.getElementById('journeyResults');
      
      if (!journeys || journeys.length === 0) {
        container.innerHTML = '<p style="text-align: center; color: #888;">Inga användarresor hittades med de angivna kriterierna.</p>';
        return;
      }

      container.innerHTML = `
        <div class="journey-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(400px, 1fr)); gap: 1.5em;">
          ${journeys.map(journey => `
            <div class="journey-card" style="background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 8px; padding: 1.5em;">
              <div class="journey-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1em; padding-bottom: 0.5em; border-bottom: 1px solid #e9ecef;">
                <div class="journey-ip" style="font-family: monospace; font-weight: bold; color: #1976d2;">${journey.ip}</div>
                <div class="journey-stats" style="text-align: right; color: #666; font-size: 0.9em;">
                  <div>${journey.page_views} sidvisningar</div>
                  <div>${journey.unique_pages} unika sidor</div>
                  <div>${Math.round(journey.session_duration / 60)} min session</div>
                </div>
              </div>
              <div class="journey-path" style="margin-bottom: 1em;">
                <div class="journey-timeline" style="position: relative; padding-left: 2em;">
                  ${journey.path.map((step, index) => `
                    <div class="journey-step" style="position: relative; margin-bottom: 0.8em; padding-bottom: 0.8em; ${index < journey.path.length - 1 ? 'border-left: 2px solid #1976d2;' : ''} margin-left: -2em; padding-left: 2em;">
                      <div class="step-marker" style="position: absolute; left: -6px; top: 0; width: 10px; height: 10px; background: #1976d2; border-radius: 50%; border: 2px solid #fff;"></div>
                      <div class="step-content">
                        <div class="step-page" style="font-weight: bold; color: #333; margin-bottom: 0.2em;">${step.page}</div>
                        <div class="step-meta" style="color: #666; font-size: 0.85em;">
                          <span class="step-time">${step.time}</span>
                          ${step.time_on_page ? `<span style="margin-left: 1em;">⏱️ ${step.time_on_page}s</span>` : ''}
                          ${step.referer && step.referer !== step.page ? `<span style="margin-left: 1em;">← ${step.referer}</span>` : ''}
                        </div>
                      </div>
                    </div>
                  `).join('')}
                </div>
              </div>
              <div class="journey-summary" style="background: #e3f2fd; padding: 0.8em; border-radius: 4px; font-size: 0.9em; color: #1976d2;">
                <strong>Sammanfattning:</strong> 
                ${journey.avg_time_per_page ? `⌀ ${Math.round(journey.avg_time_per_page)}s per sida` : 'Ingen tidsdata'} • 
                ${journey.bounce ? 'Studsade' : 'Utforskade webbplatsen'} • 
                ${journey.device_type || 'Okänd enhet'}
              </div>
            </div>
          `).join('')}
        </div>
        <div style="margin-top: 2em; text-align: center;">
          <button class="btn" onclick="exportJourneyData()">📊 Exportera resedata</button>
        </div>
      `;
    }

    function exportJourneyData() {
      const minPageViews = document.getElementById('minPageViews').value;
      fetch(`api/stats.php?action=user_journey&min_pages=${minPageViews}`)
        .then(response => response.json())
        .then(data => {
          const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
          const url = URL.createObjectURL(blob);
          const a = document.createElement('a');
          a.href = url;
          a.download = `user-journeys-${new Date().toISOString().split('T')[0]}.json`;
          a.click();
          URL.revokeObjectURL(url);
        })
        .catch(error => console.error('Error exporting journey data:', error));
    }

    // Auto-load journey analysis on page load
    document.addEventListener('DOMContentLoaded', function() {
      updateJourneyAnalysis();
    });
  </script>
</body>
</html>
