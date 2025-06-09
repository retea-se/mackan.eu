<!DOCTYPE html>
<html lang="sv">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard</title>
  <link rel="stylesheet" href="dashboard.css" />
  <script src="https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/echarts-gl@2.0.9/dist/echarts-gl.min.js"></script>
  <link rel="icon" href="data:,">

</head>
<body>
  <div class="dash-container">

    <header>
      <h1>Dashboard</h1>
      <div>
        <a href="visits.php" class="btn">⬅️ Tillbaka</a>
        <a href="?logout=1" class="btn">Logga ut</a>
      </div>
    </header>

    <div class="stats">
      <div class="stat-card">Totalt: <span id="stat-total">–</span></div>
      <div class="stat-card">Unika IP: <span id="stat-unique">–</span></div>
      <div class="stat-card">Människor: <span id="stat-humans">–</span></div>
      <div class="stat-card">Botar: <span id="stat-bots">–</span></div>
    </div>

    <div class="graph-grid">
      <div class="chart-box"><h2>Besök per timme</h2><div id="chart-hour" class="chart"></div></div>
      <div class="chart-box"><h2>Topp sidor</h2><div id="chart-pages" class="chart"></div></div>
      <div class="chart-box"><h2>Enheter</h2><div id="chart-device" class="chart"></div></div>
      <div class="chart-box"><h2>Topp klick</h2><div id="chart-clicks" class="chart"></div></div>
      <div class="chart-box"><h2>Skärmstorlek</h2><div id="chart-res" class="chart"></div></div>
      <div class="chart-box"><h2>Referers</h2><div id="chart-referer" class="chart"></div></div>
      <div class="chart-box"><h2>Språk</h2><div id="chart-lang" class="chart"></div></div>
      <div class="chart-box"><h2>Tidszoner</h2><div id="chart-tz" class="chart"></div></div>
      <div class="chart-box"><h2>User Agents</h2><div id="chart-agent" class="chart"></div></div>
      <div class="chart-box"><h2>3D-analys</h2><div id="chart-3d" class="chart" style="height:300px;"></div></div>
    </div>
  </div>

  <script src="dashboard.js"></script>
</body>
</html>
