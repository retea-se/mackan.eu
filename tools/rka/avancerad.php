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
                $profile[] = ['hours' => (float)$seg['hours'], 'loadkW' => (float)$seg['load']];
            }
        }
    }
}
// Sätt defaultprofil ENDAST vid första GET
if ($_SERVER['REQUEST_METHOD'] === 'GET' && empty($profile)) {
    $profile = [
        ['hours' => 8,  'loadkW' => 5],
        ['hours' => 12, 'loadkW' => 10],
        ['hours' => 4,  'loadkW' => 2]
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

$title = 'Avancerad och robust RKA-kalkylator';
$metaDescription = 'Avancerad reservkraftkalkylator med miljö- och ekonomiberäkning. Anpassad för svenska förhållanden.';
include '../../includes/layout-start.php';
?>

<main class="layout__container">
  <h1 class="rubrik"><?= $title ?? 'RKA-kalkylator' ?></h1>

  <!-- Länksamling till verktygssidor -->
  <nav class="menykort menykort--center mt-2 mb-2" aria-label="Verktygsnavigering">
    <a class="menykort__lank<?=basename(__FILE__)=='index.php'?' menykort__lank--aktiv':''?>" href="index.php" data-tippy-content="Snabb kalkyl för bränsle och tank">Snabbkalkyl</a>
    <a class="menykort__lank<?=basename(__FILE__)=='avancerad.php'?' menykort__lank--aktiv':''?>" href="avancerad.php" data-tippy-content="Avancerad kalkyl med miljö och ekonomi">Avancerad</a>
    <a class="menykort__lank<?=basename(__FILE__)=='a2.php'?' menykort__lank--aktiv':''?>" href="a2.php" data-tippy-content="Provkörnings-kalkylator">Avancerad 2 </a>
    <a class="menykort__lank<?=basename(__FILE__)=='provkorning.php'?' menykort__lank--aktiv':''?>" href="provkorning.php" data-tippy-content="Provkörning & tankprognos">Provkörning & tankprognos</a>
  </nav>
  <!-- /Länksamling -->

  <!-- ********** START Sektion: Formulär ********** -->
  <form id="advForm" method="post" class="form" autocomplete="off">
    <div class="form__grupp">
      <label class="falt__etikett" for="rating">
        Märkeffekt
        <span class="info" data-tippy-content="Märkeffekt på elverket i kVA eller kW. Ange enligt märkplåt eller data.">?</span>
      </label>
      <div class="form__rad">
        <input id="rating" name="rating" type="number" min="1" step="0.1" class="falt__input" value="<?=htmlspecialchars($rating)?>">
        <select id="ratingUnit" name="ratingUnit" class="falt__select">
          <option value="kVA"<?=$ratingUnit==='kVA'?' selected':''?>>kVA</option>
          <option value="kW" <?=$ratingUnit==='kW'?' selected':''?>>kW</option>
        </select>
      </div>
    </div>
    <div class="form__grupp">
      <label class="falt__etikett" for="cosphi">
        Effektfaktor (cos φ)
        <span class="info" data-tippy-content="Visar förhållandet mellan aktiv och skenbar effekt. Normalt 0,8.">?</span>
      </label>
      <input id="cosphi" name="cosphi" type="number" min="0.4" max="1" step="0.01" class="falt__input" value="<?=htmlspecialchars($cosphi)?>">
    </div>
    <div class="form__grupp">
      <label class="falt__etikett">
        Elverkstyp
        <span class="info" data-tippy-content="Välj enfas (1-fas, 230V) eller trefas (3-fas, 400V).">?</span>
      </label>
      <div class="form__rad">
        <label><input type="radio" name="phasemode" value="3" <?=($phasemode=='3'?'checked':'')?>> Trefas</label>
        <label><input type="radio" name="phasemode" value="1" <?=($phasemode=='1'?'checked':'')?>> Enfas</label>
      </div>
    </div>
    <div class="form__grupp">
      <label class="falt__etikett" for="smhi_location">
        Välj svensk ort (SMHI normal)
        <span class="info" data-tippy-content="Fyller automatiskt i temperatur och höjd enligt SMHI för vald ort.">?</span>
      </label>
      <select id="smhi_location" class="falt__select">
        <option value="">– välj –</option>
        <?php foreach($smhi_locations as $loc): ?>
          <option value="<?=htmlspecialchars(json_encode([$loc['temp'],$loc['alt']]))?>"
            <?=($ambient == $loc['temp'] && $altitude == $loc['alt']) ? 'selected' : ''?>>
            <?=$loc['name']?> (<?=$loc['temp']?> °C, <?=$loc['alt']?> m)
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form__rad">
      <div class="form__grupp" style="flex:1">
        <label class="falt__etikett" for="ambient">
          Omgivningstemperatur (°C)
          <span class="info" data-tippy-content="Medeltemperatur där elverket står, påverkar effekt.">?</span>
        </label>
        <input id="ambient" name="ambient" type="number" min="-40" max="60" step="1" class="falt__input" value="<?=htmlspecialchars($ambient)?>">
      </div>
      <div class="form__grupp" style="flex:1">
        <label class="falt__etikett" for="altitude">
          Altitud (m)
          <span class="info" data-tippy-content="Höjd över havet där elverket står, påverkar effekt.">?</span>
        </label>
        <input id="altitude" name="altitude" type="number" min="0" max="5000" step="1" class="falt__input" value="<?=htmlspecialchars($altitude)?>">
      </div>
    </div>
    <div class="form__grupp">
      <label class="falt__etikett" for="fuel">
        Bränsletyp
        <span class="info" data-tippy-content="Typ av bränsle som används i elverket.">?</span>
      </label>
      <select id="fuel" name="fuel" class="falt__select">
        <option value="DIESEL" <?=$fuel==='DIESEL'?'selected':''?>>Diesel</option>
        <option value="HVO100" <?=$fuel==='HVO100'?'selected':''?>>HVO100</option>
        <option value="ECOPAR" <?=$fuel==='ECOPAR'?'selected':''?>>EcoPar</option>
      </select>
    </div>
    <div class="form__grupp">
      <label class="falt__etikett" for="price">
        Bränslepris (SEK/L)
        <span class="info" data-tippy-content="Nuvarande inköpspris på valt bränsle i kronor per liter.">?</span>
      </label>
      <input id="price" name="price" type="number" min="0" step="0.1" class="falt__input" value="<?=htmlspecialchars($price)?>">
    </div>
    <div class="form__grupp">
      <label class="falt__etikett" for="co2">
        CO₂-faktor (kg/L)
        <span class="info" data-tippy-content="Gram CO₂ per liter bränsle. Varierar mellan bränslen.">?</span>
      </label>
      <input id="co2" name="co2" type="number" min="0" step="0.01" class="falt__input" value="<?=htmlspecialchars($co2)?>">
    </div>
    <fieldset class="kort mt-1">
      <legend class="kort__rubrik">
        Lastprofil (tid, last)
        <span class="info" data-tippy-content="Ange antal timmar och last (kW) för varje segment. Summeras automatiskt.">?</span>
      </legend>
      <div class="tabell__wrapper">
        <table id="profileTable" class="tabell">
          <thead>
            <tr><th>#</th><th>Tid (h)</th><th>Last (kW)</th><th></th></tr>
          </thead>
          <tbody>
  <?php foreach ($profile as $i => $row): ?>
    <tr>
      <td><?= $i+1 ?></td>
      <td><input type="number" name="hours" min="0" step="0.1" class="falt__input" value="<?=htmlspecialchars($row['hours'])?>"></td>
      <td><input type="number" name="load" min="0" step="0.1" class="falt__input" value="<?=htmlspecialchars($row['loadkW'])?>"></td>
      <td>
        <button type="button"
                class="knapp knapp__ikon knapp__ikon--liten knapp--fara"
                onclick="removeRow(this)"
                title="Ta bort rad"
                aria-label="Ta bort rad">×</button>
      </td>
    </tr>
  <?php endforeach; ?>
</tbody>
        </table>
      </div>
      <button type="button" class="knapp" onclick="addRow()" data-tippy-content="Lägg till rad">+ Lägg till rad</button>
      <input type="hidden" id="profileData" name="profileData" value="<?=htmlspecialchars(json_encode($profile))?>">
      <small>Summeras automatiskt.</small>
    </fieldset>
    <fieldset class="kort mt-1">
      <legend class="kort__rubrik">Ekonomi</legend>
      <div class="form__grupp">
        <label class="falt__etikett" for="hours_per_year">
          Drifttimmar per år
          <span class="info" data-tippy-content="Hur många timmar per år elverket normalt används.">?</span>
        </label>
        <input id="hours_per_year" name="hours_per_year" type="number" min="1" step="1" class="falt__input" value="<?=htmlspecialchars($hours_per_year)?>">
      </div>
      <div class="form__grupp">
        <label class="falt__etikett" for="extra_invest">
          Extra investering (kr, valfritt)
          <span class="info" data-tippy-content="Om du vill jämföra payback på investering, ange extra kostnad här.">?</span>
        </label>
        <input id="extra_invest" name="extra_invest" type="number" min="0" step="1" class="falt__input" value="<?=htmlspecialchars($extra_invest)?>">
      </div>
    </fieldset>
    <div class="form__verktyg">
      <button type="submit" class="knapp" id="calcBtn" data-tippy-content="Beräkna och visa resultat">Beräkna</button>
    </div>
  </form>

  <?php if ($totHours > 0): ?>
  <section class="kort mt-1 kort--gron">
    <h2 class="kort__rubrik">Tekniska data</h2>
    <div class="kort__innehall">
      <ul>
        <li><strong>Derating-faktor:</strong> <?=round($derate*100,1)?> % (<?=$ambient?> °C, <?=$altitude?> m)</li>
        <li><strong>Märkeffekt nominellt:</strong> <?=number_format($ratingKW,1)?> kW</li>
        <li><strong>Märkeffekt justerad:</strong> <?=number_format($ratingKW_derated,1)?> kW<br>
          <small>cos φ = <?=htmlspecialchars($cosphi)?></small>
        </li>
        <li><strong>Strömuttag per fas:</strong> <?=number_format($current,1)?> A</li>
      </ul>
    </div>
  </section>
  <?php endif; ?>

  <?php
  // Rendera resultat-tabell för profil (om rader finns)
  if(count($profileResults)>0) {
      render_profile_table($profileResults, $totalL, $totalCost, $totalCO2, $totHours);
  }
  ?>

  <?php if ($totHours > 0): ?>
    <section class="kort mt-1">
      <h2 class="kort__rubrik">Totalsammanställning</h2>
      <div class="kort__innehall">
        <ul>
          <li><strong>Total driftstid:</strong> <?=number_format($totHours,1)?> timmar</li>
          <li><strong>Total bränsleåtgång:</strong> <?=number_format($totalL,1)?> liter</li>
          <li><strong>Total CO₂-utsläpp:</strong> <?=number_format($totalCO2,1)?> kg</li>
          <li><strong>Total kostnad:</strong> <?=number_format($totalCost,0)?> kr</li>
          <li><strong>Medelbelastning:</strong> <?=number_format($totalL>0?$totalL/$totHours:0,1)?> L/h</li>
        </ul>
      </div>
    </section>
    <section class="kort mt-1">
      <h2 class="kort__rubrik">Ekonomisk sammanställning</h2>
      <div class="kort__innehall">
        <ul>
          <li>Genomsnittlig förbrukning: <?=number_format($avg_Lph,2)?> L/h</li>
          <li>Årsdriftskostnad (<?=htmlspecialchars($hours_per_year)?> h): <strong><?=number_format($annual_cost,0)?> kr</strong></li>
          <?php if ($extra_invest > 0 && $payback): ?>
            <li>Återbetalningstid för investeringen (<?=number_format($extra_invest,0)?> kr): <strong><?=number_format($payback,1)?> år</strong></li>
          <?php endif; ?>
        </ul>
      </div>
    </section>
    <section class="kort mt-1">
      <h2 class="kort__rubrik">Miljöanalys</h2>
      <div class="kort__innehall">
        <ul>
          <li>Årlig CO₂-belastning: <strong><?=number_format($annual_co2,1)?> kg CO₂</strong></li>
          <li>Motsvarar ca <strong><?=number_format($eq_km,0)?> km</strong> med personbil</li>
          <li>Skulle kräva ca <strong><?=$eq_trees?></strong> träd för att binda denna CO₂ på ett år</li>
        </ul>
      </div>
    </section>
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
  <section class="kort mt-1">
    <h2 class="kort__rubrik">Graf: Last och bränsleförbrukning</h2>
    <div class="kort__innehall">
      <canvas id="profileChart" width="800" height="350"></canvas>
    </div>
  </section>
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

</main>

<script>
document.getElementById('smhi_location').addEventListener('change', function() {
  if(this.value) {
    const [temp, alt] = JSON.parse(this.value);
    document.getElementById('ambient').value = temp;
    document.getElementById('altitude').value = alt;
  }
});
</script>
<script src="profile.js" defer></script>
<?php include '../../includes/layout-end.php'; ?>
