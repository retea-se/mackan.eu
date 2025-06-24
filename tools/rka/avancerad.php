<?php
require_once "calc_engine.php";
require_once "profile_table.php";

// SMHI-orter
$smhi_locations = [
  ["name" => "Stockholm",   "temp" => 6,  "alt" => 44],
  ["name" => "Göteborg",    "temp" => 8,  "alt" => 12],
  ["name" => "Malmö",       "temp" => 8,  "alt" => 14],
  ["name" => "Umeå",        "temp" => 3,  "alt" => 14],
  ["name" => "Kiruna",      "temp" => -1, "alt" => 450],
  ["name" => "Sundsvall",   "temp" => 5,  "alt" => 15],
  ["name" => "Östersund",   "temp" => 3,  "alt" => 376],
  ["name" => "Visby",       "temp" => 7,  "alt" => 47],
  ["name" => "Karlstad",    "temp" => 6,  "alt" => 64],
  ["name" => "Luleå",       "temp" => 2,  "alt" => 11],
];

// Läs in POST-data
$rating     = $_POST['rating']     ?? 100;
$ratingUnit = $_POST['ratingUnit'] ?? 'kVA';
$cosphi     = $_POST['cosphi']     ?? COS_PHI_DEF;
$phasemode  = $_POST['phasemode']  ?? '3';
$fuel       = $_POST['fuel']       ?? 'DIESEL';
$price      = $_POST['price']      ?? 20;
$co2        = $_POST['co2']        ?? 2.67;
$ambient    = $_POST['ambient']    ?? 20;
$altitude   = $_POST['altitude']   ?? 0;
$hours_per_year = $_POST['hours_per_year'] ?? 100;
$extra_invest   = $_POST['extra_invest'] ?? 0;

// Läs in lastprofil
$profile = [];
if (!empty($_POST['profileData'])) {
    $json = json_decode($_POST['profileData'], true);
    if (is_array($json)) {
        foreach ($json as $seg) {
            if (is_numeric($seg['hours']) && is_numeric($seg['load']) && $seg['hours'] > 0) {
                $profile[] = ['hours'=>(float)$seg['hours'], 'loadkW'=>(float)$seg['load']];
            }
        }
    }
}
// Sätt defaultprofil ENDAST vid första GET
if ($_SERVER['REQUEST_METHOD'] === 'GET' && empty($profile)) {
    $profile = [
        ['hours'=>8,  'loadkW'=>5],
        ['hours'=>12, 'loadkW'=>10],
        ['hours'=>4,  'loadkW'=>2]
    ];
}

// Kör alla beräkningar
$derate = derate_factor($ambient, $altitude);
list($ratingKW, $ratingKW_derated) = calc_generator_effect($rating, $ratingUnit, $cosphi, $derate);
$current = calc_current($phasemode, $ratingKW_derated, $cosphi);
list($profileResults, $totalL, $totalCost, $totalCO2, $totHours) =
    calc_profile($profile, $fuel, $price, $co2, $ratingKW_derated);

// Ekonomi och miljö
list($avg_Lph, $annual_cost, $payback) = calc_economics($totalL, $totHours, $price, $hours_per_year, $extra_invest);
list($annual_co2, $eq_km, $eq_trees) = calc_environment($totalL, $totHours, $co2, $hours_per_year);
?>
<!doctype html>
<html lang="sv">
<head>
  <meta charset="utf-8">
  <title>Avancerad RKA-kalkylator – modulär version</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/dist/tippy.css" />
  <style>
    body{font-family:system-ui,Arial,sans-serif;max-width:820px;margin:2rem auto;line-height:1.55}
    h1{font-size:1.6rem;margin:0 0 .7rem}
    .row{display:flex;gap:.7rem;flex-wrap:wrap}
    fieldset{border:1px solid #ccc;padding:1rem 1.5rem;margin-top:1.5rem}
    .table-mini input{width:5.6rem}
    .green{border-color:#35a853;color:#35a853}
    button[type=button]{padding:.2em .7em;margin-left:.5em;font-size:1em}
    button.primary{background:#0078d4;color:#fff;border:none;padding:0.7em 2em;border-radius:4px;font-size:1.1em;cursor:pointer;transition:background 0.2s;}
    button.primary:hover{background:#005fa3;}
  </style>
</head>
<body>

<h1>Avancerad och robust RKA-kalkylator</h1>
<form id="advForm" method="post" autocomplete="off">
  <label>
    Generatorns märkeffekt
    <span class="info" data-tippy-content="Märkeffekt på elverket i kVA eller kW. Ange enligt märkplåt eller data.">?</span>
    <div class="row">
      <input id="rating" name="rating" type="number" min="1" step="0.1" value="<?=htmlspecialchars($rating)?>">
      <select id="ratingUnit" name="ratingUnit">
        <option value="kVA"<?=$ratingUnit==='kVA'?' selected':''?>>kVA</option>
        <option value="kW" <?=$ratingUnit==='kW'?' selected':''?>>kW</option>
      </select>
    </div>
  </label>
  <label>
    Effektfaktor (cos φ)
    <span class="info" data-tippy-content="Visar förhållandet mellan aktiv och skenbar effekt. Normalt 0,8.">?</span>
    <input id="cosphi" name="cosphi" type="number" min="0.4" max="1" step="0.01" value="<?=htmlspecialchars($cosphi)?>">
  </label>
  <label>
    Elverkstyp
    <span class="info" data-tippy-content="Välj enfas (1-fas, 230V) eller trefas (3-fas, 400V).">?</span>
    <div class="row">
      <label><input type="radio" name="phasemode" value="3" <?=($phasemode=='3'?'checked':'')?>> Trefas</label>
      <label><input type="radio" name="phasemode" value="1" <?=($phasemode=='1'?'checked':'')?>> Enfas</label>
    </div>
  </label>
  <!-- SMHI-dropdown ovanför temp/altitud -->
  <label>
    Välj svensk ort (SMHI normal)
    <span class="info" data-tippy-content="Fyller automatiskt i temperatur och höjd enligt SMHI för vald ort.">?</span>
    <select id="smhi_location">
      <option value="">– välj –</option>
      <?php foreach($smhi_locations as $loc): ?>
        <option value="<?=htmlspecialchars(json_encode([$loc['temp'],$loc['alt']]))?>"
          <?=($ambient == $loc['temp'] && $altitude == $loc['alt']) ? 'selected' : ''?>>
          <?=$loc['name']?> (<?=$loc['temp']?> °C, <?=$loc['alt']?> m)
        </option>
      <?php endforeach; ?>
    </select>
  </label>
  <div class="row">
    <label style="flex:1">
      Omgivningstemperatur (°C)
      <span class="info" data-tippy-content="Medeltemperatur där elverket står, påverkar effekt.">?</span>
      <input id="ambient" name="ambient" type="number" min="-40" max="60" step="1" value="<?=htmlspecialchars($ambient)?>">
    </label>
    <label style="flex:1">
      Altitud (m)
      <span class="info" data-tippy-content="Höjd över havet där elverket står, påverkar effekt.">?</span>
      <input id="altitude" name="altitude" type="number" min="0" max="5000" step="1" value="<?=htmlspecialchars($altitude)?>">
    </label>
  </div>
  <label>
    Bränsletyp
    <span class="info" data-tippy-content="Typ av bränsle som används i elverket.">?</span>
    <select id="fuel" name="fuel">
      <option value="DIESEL" <?=$fuel==='DIESEL'?'selected':''?>>Diesel</option>
      <option value="HVO100" <?=$fuel==='HVO100'?'selected':''?>>HVO100</option>
      <option value="ECOPAR" <?=$fuel==='ECOPAR'?'selected':''?>>EcoPar</option>
    </select>
  </label>
  <label>
    Bränslepris (SEK/L)
    <span class="info" data-tippy-content="Nuvarande inköpspris på valt bränsle i kronor per liter.">?</span>
    <input id="price" name="price" type="number" min="0" step="0.1" value="<?=htmlspecialchars($price)?>">
  </label>
  <label>
    CO₂-faktor (kg/L)
    <span class="info" data-tippy-content="Gram CO₂ per liter bränsle. Varierar mellan bränslen.">?</span>
    <input id="co2" name="co2" type="number" min="0" step="0.01" value="<?=htmlspecialchars($co2)?>">
  </label>
  <fieldset>
    <legend>Manuell lastprofil (tid, last)
      <span class="info" data-tippy-content="Ange antal timmar och last (kW) för varje segment. Summeras automatiskt.">?</span>
    </legend>
    <table id="profileTable" class="table-mini">
      <thead>
        <tr><th>#</th><th>Tid (h)</th><th>Last (kW)</th><th></th></tr>
      </thead>
      <tbody>
        <!-- Fylls av JS (profile.js) -->
      </tbody>
    </table>
    <button type="button" onclick="addRow()">+ Lägg till rad</button>
    <input type="hidden" id="profileData" name="profileData" value="<?=htmlspecialchars(json_encode($profile))?>">
    <small>Summeras automatiskt.</small>
  </fieldset>
  <fieldset>
    <legend>Ekonomi</legend>
    <label>
      Drifttimmar per år
      <span class="info" data-tippy-content="Hur många timmar per år elverket normalt används.">?</span>
      <input id="hours_per_year" name="hours_per_year" type="number" min="1" step="1" value="<?=htmlspecialchars($hours_per_year)?>">
    </label>
    <label>
      Extra investering (kr, valfritt)
      <span class="info" data-tippy-content="Om du vill jämföra payback på investering, ange extra kostnad här.">?</span>
      <input id="extra_invest" name="extra_invest" type="number" min="0" step="1" value="<?=htmlspecialchars($extra_invest)?>">
    </label>
  </fieldset>
  <button type="submit" class="primary" id="calcBtn" style="margin-top:1.5em;font-size:1.2em;">Beräkna</button>
</form>

<?php if ($totHours > 0): ?>
<div id="result" class="green" style="border:3px solid #35a853;border-radius:8px;padding:1rem;margin:1rem 0">
  <ul>
    <li><strong>Derating-faktor:</strong> <?=round($derate*100,1)?> % (<?=$ambient?> °C, <?=$altitude?> m)</li>
    <li><strong>Märkeffekt nominellt:</strong> <?=number_format($ratingKW,1)?> kW</li>
    <li><strong>Märkeffekt justerad:</strong> <?=number_format($ratingKW_derated,1)?> kW<br>
      <small>cos φ = <?=htmlspecialchars($cosphi)?></small>
    </li>
    <li><strong>Strömuttag per fas:</strong> <?=number_format($current,1)?> A</li>
  </ul>
</div>
<?php endif; ?>

<?php
// Rendera resultat-tabell för profil (om rader finns)
if(count($profileResults)>0) {
    render_profile_table($profileResults, $totalL, $totalCost, $totalCO2, $totHours);
}
?>

<?php if ($totHours > 0): ?>
  <h2>Totalsammanställning</h2>
  <ul>
    <li><strong>Total driftstid:</strong> <?=number_format($totHours,1)?> timmar</li>
    <li><strong>Total bränsleåtgång:</strong> <?=number_format($totalL,1)?> liter</li>
    <li><strong>Total CO₂-utsläpp:</strong> <?=number_format($totalCO2,1)?> kg</li>
    <li><strong>Total kostnad:</strong> <?=number_format($totalCost,0)?> kr</li>
    <li><strong>Medelbelastning:</strong> <?=number_format($totalL>0?$totalL/$totHours:0,1)?> L/h</li>
  </ul>
  <h2>Ekonomisk sammanställning</h2>
  <ul>
    <li>Genomsnittlig förbrukning: <?=number_format($avg_Lph,2)?> L/h</li>
    <li>Årsdriftskostnad (<?=htmlspecialchars($hours_per_year)?> h): <strong><?=number_format($annual_cost,0)?> kr</strong></li>
    <?php if ($extra_invest > 0 && $payback): ?>
      <li>Återbetalningstid för investeringen (<?=number_format($extra_invest,0)?> kr): <strong><?=number_format($payback,1)?> år</strong></li>
    <?php endif; ?>
  </ul>
  <h2>Miljöanalys</h2>
  <ul>
    <li>Årlig CO₂-belastning: <strong><?=number_format($annual_co2,1)?> kg CO₂</strong></li>
    <li>Motsvarar ca <strong><?=number_format($eq_km,0)?> km</strong> med svensk bil</li>
    <li>Skulle kräva ca <strong><?=$eq_trees?></strong> träd för att binda denna CO₂ på ett år</li>
  </ul>
<?php endif; ?>

<?php
// PHP som genererar JS-data
$labels = [];
$kwData = [];
$lphData = [];
$co2Data = [];
$costData = [];
foreach ($profileResults as $seg) {
    $labels[] = $seg['hours'] . " h";
    $kwData[] = $seg['loadkW'];
    $lphData[] = ($seg['hours'] > 0) ? $seg['L'] / $seg['hours'] : 0;
    $co2Data[] = $seg['co2'];
    $costData[] = $seg['cost'];
}
?>
<?php if ($totHours > 0): ?>
<h2>Graf: Last och bränsleförbrukning</h2>
<canvas id="profileChart" width="800" height="350"></canvas>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const profileChartData = {
  labels: <?=json_encode($labels)?>,
  datasets: [
    {
      label: "Last (kW)",
      data: <?=json_encode($kwData)?>,
      type: 'bar',
      backgroundColor: "rgba(54,162,235,0.7)",
      yAxisID: "y"
    },
    {
      label: "Bränsleförbrukning (L/h)",
      data: <?=json_encode($lphData)?>,
      type: 'line',
      borderColor: "rgba(255,99,132,1)",
      backgroundColor: "rgba(255,99,132,0.2)",
      yAxisID: "y1"
    },
    {
      label: "CO₂ (kg)",
      data: <?=json_encode($co2Data)?>,
      type: 'line',
      borderColor: "rgba(0,200,80,1)",
      backgroundColor: "rgba(0,200,80,0.2)",
      yAxisID: "y2"
    },
    {
      label: "Kostnad (kr)",
      data: <?=json_encode($costData)?>,
      type: 'line',
      borderColor: "rgba(100,100,100,1)",
      backgroundColor: "rgba(100,100,100,0.2)",
      yAxisID: "y3"
    }
  ]
};
window.addEventListener('DOMContentLoaded', function() {
  if (typeof profileChartData !== "undefined" && document.getElementById('profileChart')) {
    new Chart(document.getElementById('profileChart').getContext('2d'), {
      data: profileChartData,
      options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        plugins: { legend: { position: 'top' } },
        scales: {
          y: {
            type: 'linear',
            display: true,
            position: 'left',
            title: { display: true, text: 'Last (kW)' }
          },
          y1: {
            type: 'linear',
            display: true,
            position: 'right',
            title: { display: true, text: 'Bränsle (L/h)' },
            grid: { drawOnChartArea: false }
          },
          y2: {
            type: 'linear',
            display: true,
            position: 'right',
            title: { display: true, text: 'CO₂ (kg)' },
            grid: { drawOnChartArea: false },
            offset: true
          },
          y3: {
            type: 'linear',
            display: true,
            position: 'right',
            title: { display: true, text: 'Kostnad (kr)' },
            grid: { drawOnChartArea: false },
            offset: true
          }
        }
      }
    });
  }
});
</script>
<?php endif; ?>

<!-- SMHI-dropdown JS (fyller bara i fält, ingen autosubmit) -->
<script>
document.getElementById('smhi_location').addEventListener('change', function() {
  if(this.value) {
    const [temp, alt] = JSON.parse(this.value);
    document.getElementById('ambient').value = temp;
    document.getElementById('altitude').value = alt;
  }
});
</script>

<script src="profile.js"></script>
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="https://unpkg.com/tippy.js@6"></script>
<script>
tippy('.info', { theme: 'light-border', placement: 'right', arrow: true });
</script>
</body>
</html>
