<?php
/******************************************************************************
 *  provkorning.php  –  RKA-kalkylator inkl. provkörning & 24-månads-simulering
 ******************************************************************************/
require_once "calc_engine.php";
require_once "profile_table.php";

/*** 0.  --------------------------------------------------------------- ***/
$smhi_locations = [
  ["name"=>"Stockholm","temp"=>6,"alt"=>44],["name"=>"Göteborg","temp"=>8,"alt"=>12],
  ["name"=>"Malmö","temp"=>8,"alt"=>14],    ["name"=>"Umeå","temp"=>3,"alt"=>14],
  ["name"=>"Kiruna","temp"=>-1,"alt"=>450], ["name"=>"Sundsvall","temp"=>5,"alt"=>15],
  ["name"=>"Östersund","temp"=>3,"alt"=>376],["name"=>"Visby","temp"=>7,"alt"=>47],
  ["name"=>"Karlstad","temp"=>6,"alt"=>64], ["name"=>"Luleå","temp"=>2,"alt"=>11],
];

/*** 1.  Hämta POST-data  ********************************************** */
$rating        = $_POST['rating']        ?? 100;
$ratingUnit    = $_POST['ratingUnit']    ?? 'kVA';
$cosphi        = $_POST['cosphi']        ?? COS_PHI_DEF;
$phasemode     = $_POST['phasemode']     ?? '3';
$fuel          = $_POST['fuel']          ?? 'DIESEL';
$price         = $_POST['price']         ?? 20;
$co2           = $_POST['co2']           ?? 2.67;
$ambient       = $_POST['ambient']       ?? 20;
$altitude      = $_POST['altitude']      ?? 0;
$hours_per_yr  = $_POST['hours_per_year']?? 100;
$extra_invest  = $_POST['extra_invest']  ?? 0;

/* --- provkörning ----------------------------------------------------- */
$test_freq     = $_POST['test_freq']     ?? 'monthly';   // weekly|fortnight|monthly|quarterly
$test_minutes  = $_POST['test_minutes']  ?? 30;          // minuter per provkörning
$test_kW       = $_POST['test_kW']       ?? 30;          // **kW** belastning vid provkörning
$tank_cap      = $_POST['tank_cap']      ?? 2000;        // tankvolym (L)
$tank_level    = $_POST['tank_level']    ?? 1500;        // nuvarande nivå (L)
$alert_pct     = $_POST['alert_pct']     ?? 30;          // larmgräns %

/*** 2.  Lastprofilen *************************************************** */
$profile = [];
if (!empty($_POST['profileData'])) {
  $j = json_decode($_POST['profileData'], true);
  if (is_array($j))
    foreach ($j as $s)
      if (is_numeric($s['hours']) && is_numeric($s['load']) && $s['hours']>0)
        $profile[]=['hours'=>(float)$s['hours'],'loadkW'=>(float)$s['load']];
}
if ($_SERVER['REQUEST_METHOD']==='GET' && empty($profile))
  $profile=[['hours'=>8,'loadkW'=>5],['hours'=>12,'loadkW'=>10],['hours'=>4,'loadkW'=>2]];

/*** 3.  Beräkningar **************************************************** */
$derate                 = derate_factor($ambient,$altitude);
[$ratingKW,$ratingKW_d] = calc_generator_effect($rating,$ratingUnit,$cosphi,$derate);
$current                = calc_current($phasemode,$ratingKW_d,$cosphi);

[$profRows,$profL,$profCost,$profCO2,$profH] =
  calc_profile($profile,$fuel,$price,$co2,$ratingKW_d);

/*** 4.  Provkörning **************************************************** */
function tests_per_year($f){return ['weekly'=>52,'fortnight'=>26,'monthly'=>12,'quarterly'=>4][$f]??12;}
$tests_per_year = tests_per_year($test_freq);

$sc            = BASE_SC * $GLOBALS['fuelFac'][$fuel];          // L/kWh
$test_L_run    = $test_kW * $sc * ($test_minutes/60);           // liter / provkörning
$test_L_year   = $test_L_run * $tests_per_year;
$test_CO2_year = $test_L_year * $co2;
$test_cost_year= $test_L_year * $price;

/*** 5.  Totalt ********************************************************* */
$totalL     = $profL + $test_L_year;
$totalCost  = $profCost + $test_cost_year;
$totalCO2   = $profCO2 + $test_CO2_year;
$totHours   = $profH   + $tests_per_year * ($test_minutes/60);

/*** 6.  24-månaders-simulering **************************************** */
$monthly_profL  = $profL / 12;                  // jämn fördelning
$monthly_testL  = $test_L_year / 12;            // jämn fördelning

$sim = [];
$remain = $tank_level;

for($m=1;$m<=24;$m++){
  $use_prof = $monthly_profL;
  $use_test = $monthly_testL;
  $remain  -= ($use_prof + $use_test);
  $sim[] = [
    'month'=>$m,
    'L_prof'=>round($use_prof,1),
    'L_test'=>round($use_test,1),
    'remain'=>max(0, round($remain,1)),
    'alert' => ($remain/$tank_cap*100) <= $alert_pct
  ];
}
[$avgLph,$annual_cost,$payback] =
  calc_economics($totalL,$totHours,$price,$hours_per_yr,$extra_invest);
[$annual_co2,$eq_km,$eq_trees]  =
  calc_environment($totalL,$totHours,$co2,$hours_per_yr);
?>
<!doctype html><html lang="sv"><head>
<meta charset="utf-8"><title>RKA – provkörning & 24-mån tankprognos</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="stylesheet" href="style.css">
<style>
  table.sim{border-collapse:collapse;width:100%;margin:1.5rem 0}
  table.sim th,td{border:1px solid #ccc;padding:.45rem;text-align:center}
  table.sim tbody tr:nth-child(even){background:#f6f6f6}
  table.sim .alert{background:#ffe5e5;color:#b30000;font-weight:600}
  button.primary{background:#0078d4;color:#fff;padding:.7em 2em;border:none;border-radius:4px}
</style>
</head><body>

<h1>RKA-kalkylator med provkörning & tankprognos</h1>

<form id="advForm" method="post" autocomplete="off">
<!----------------- Grundfälten (förkortad) --------------------------------->
<label>Märkeffekt
  <input name="rating" type="number" min="1" step="0.1" value="<?=$rating?>"> kVA
</label>
<label>Testlast <small>(kW)</small>
  <input name="test_kW" type="number" min="1" step="1" value="<?=$test_kW?>">
</label>
<label>Testtid per gång (min)
  <input name="test_minutes" type="number" min="1" step="1" value="<?=$test_minutes?>">
</label>
<label>Intervall
  <select name="test_freq">
    <option value="weekly"<?=$test_freq=='weekly'?' selected':''?>>varje vecka</option>
    <option value="fortnight"<?=$test_freq=='fortnight'?' selected':''?>>varannan vecka</option>
    <option value="monthly"<?=$test_freq=='monthly'?' selected':''?>>varje månad</option>
    <option value="quarterly"<?=$test_freq=='quarterly'?' selected':''?>>varje kvartal</option>
  </select>
</label>
<label>Tankvolym (L)    <input name="tank_cap"   type="number" value="<?=$tank_cap?>"></label>
<label>Nuvarande nivå (L)<input name="tank_level" type="number" value="<?=$tank_level?>"></label>
<label>Larmgräns (%)     <input name="alert_pct"  type="number" value="<?=$alert_pct?>"></label>

<fieldset><legend>Lastprofil (enkel)</legend>
  <table id="profileTable"><thead><tr><th>#</th><th>Tid (h)</th><th>Last (kW)</th><th></th></tr></thead><tbody></tbody></table>
  <button type="button" onclick="addRow()">+ Lägg till rad</button>
  <input type="hidden" id="profileData" name="profileData" value='<?=htmlspecialchars(json_encode($profile))?>'>
</fieldset>

<button type="submit" class="primary">Beräkna</button>
</form>

<?php
/* ---------- kort rapport före tabellen ---------- */
echo "<h2>Sammanfattning av provkörningen</h2>";
echo "<p>Vid varje provkörning belastas elverket med <strong>".number_format($test_kW,1).
     " kW</strong> i <strong>".$test_minutes." minuter</strong>. ";
echo "Det motsvarar <strong>".number_format($test_L_run,1)." liter</strong> per tillfälle, ";
echo "vilket med intervall <strong>".$test_freq_sv.
     "</strong> ger ungefär <strong>".number_format($test_L_year,1)." liter per år</strong>.</p>";

echo "<p>Tabellen nedan visar hur både den ordinarie driften (kolumnen ”Drift L”) ";
echo "och provkörningarna (”Prov L”) påverkar bränslenivån månad för månad de ";
echo "närmaste två åren. Rader som når din larmgräns på <strong>$alert_pct %</strong> ";
echo "markeras med röd bakgrund.</p>";
?>

<?php if($totHours>0): ?>
<h2>24-månaders tankprognos</h2>

<table class="sim">
  <thead>
    <tr>
      <th>Månad</th>
      <th>Drift L</th>
      <th>Prov L</th>
      <th>Nivå kvar (L)</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($sim as $r): ?>
      <tr class="<?=$r['alert']?'alert':''?>">
        <td><?=$r['month']?></td>
        <td><?=number_format($r['L_prof'],1)?></td>
        <td><?=number_format($r['L_test'],1)?></td>
        <td><?=number_format($r['remain'],1)?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>

<script src="profile.js"></script>
</body></html>
