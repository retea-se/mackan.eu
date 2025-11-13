<?php
/******************************************************************************
 *  provkorning.php  –  RKA-kalkylator inkl. provkörning & 24-månads-simulering
 ******************************************************************************/
require_once "calc_engine.php";
require_once "profile_table.php";

// --- PHP-datahantering (oförändrad) ---
$smhi_locations = [
  ["name"=>"Stockholm","temp"=>6,"alt"=>44],["name"=>"Göteborg","temp"=>8,"alt"=>12],
  ["name"=>"Malmö","temp"=>8,"alt"=>14],    ["name"=>"Umeå","temp"=>3,"alt"=>14],
  ["name"=>"Kiruna","temp"=>-1,"alt"=>450], ["name"=>"Sundsvall","temp"=>5,"alt"=>15],
  ["name"=>"Östersund","temp"=>3,"alt"=>376],["name"=>"Visby","temp"=>7,"alt"=>47],
  ["name"=>"Karlstad","temp"=>6,"alt"=>64], ["name"=>"Luleå","temp"=>2,"alt"=>11],
];

// Ladda valideringsfunktioner
require_once __DIR__ . '/../../includes/tools-validator.php';

// Läs in calc_engine för COS_PHI_DEF
require_once "calc_engine.php";

// Validera POST-data
$rating = validateNumeric($_POST['rating'] ?? null, ['min' => 1, 'max' => 10000, 'default' => 100]);
$ratingUnit = validateEnum($_POST['ratingUnit'] ?? null, ['kVA', 'kW'], 'kVA');
$cosphi = validateNumeric($_POST['cosphi'] ?? null, ['min' => 0.1, 'max' => 1.0, 'default' => COS_PHI_DEF]);
$phasemode = validateEnum($_POST['phasemode'] ?? null, ['1', '3'], '3');
$fuel = validateEnum($_POST['fuel'] ?? null, ['DIESEL', 'HVO100', 'ECOPAR'], 'DIESEL');
$price = validateNumeric($_POST['price'] ?? null, ['min' => 0, 'max' => 1000, 'default' => 20]);
$co2 = validateNumeric($_POST['co2'] ?? null, ['min' => 0, 'max' => 10, 'default' => 2.67]);
$ambient = validateNumeric($_POST['ambient'] ?? null, ['min' => -50, 'max' => 50, 'default' => 20]);
$altitude = validateNumeric($_POST['altitude'] ?? null, ['min' => 0, 'max' => 5000, 'default' => 0]);
$hours_per_yr = validateNumeric($_POST['hours_per_year'] ?? null, ['min' => 0, 'max' => 8760, 'default' => 100]);
$extra_invest = validateNumeric($_POST['extra_invest'] ?? null, ['min' => 0, 'max' => 10000000, 'default' => 0]);

$test_freq = validateEnum($_POST['test_freq'] ?? null, ['monthly', 'weekly', 'daily'], 'monthly');
$test_minutes = validateNumeric($_POST['test_minutes'] ?? null, ['min' => 1, 'max' => 300, 'default' => 30]);
$test_kW = validateNumeric($_POST['test_kW'] ?? null, ['min' => 0, 'max' => 10000, 'default' => 30]);
$tank_cap = validateNumeric($_POST['tank_cap'] ?? null, ['min' => 0, 'max' => 100000, 'default' => 2000]);
$tank_level = validateNumeric($_POST['tank_level'] ?? null, ['min' => 0, 'max' => 100000, 'default' => 1500]);
$alert_pct = validateNumeric($_POST['alert_pct'] ?? null, ['min' => 0, 'max' => 100, 'default' => 30]);

// Validera lastprofil
$profile = [];
if (!empty($_POST['profileData'])) {
  $json = validateJson($_POST['profileData'], []);
  if (is_array($json) && !empty($json)) {
    foreach ($json as $s) {
      if (isset($s['hours']) && isset($s['load'])) {
        $hours = validateNumeric($s['hours'], ['min' => 0, 'max' => 8760, 'default' => 0]);
        $load = validateNumeric($s['load'], ['min' => 0, 'max' => 10000, 'default' => 0]);
        if ($hours > 0 && $load >= 0) {
          $profile[] = ['hours' => (float)$hours, 'loadkW' => (float)$load];
        }
      }
    }
  }
}
if ($_SERVER['REQUEST_METHOD']==='GET' && empty($profile))
  $profile=[['hours'=>8,'loadkW'=>5],['hours'=>12,'loadkW'=>10],['hours'=>4,'loadkW'=>2]];

$derate                 = derate_factor($ambient,$altitude);
[$ratingKW,$ratingKW_d] = calc_generator_effect($rating,$ratingUnit,$cosphi,$derate);
$current                = calc_current($phasemode,$ratingKW_d,$cosphi);

[$profRows,$profL,$profCost,$profCO2,$profH] =
  calc_profile($profile,$fuel,$price,$co2,$ratingKW_d);

function tests_per_year($f){return ['weekly'=>52,'fortnight'=>26,'monthly'=>12,'quarterly'=>4][$f]??12;}
$tests_per_year = tests_per_year($test_freq);

$sc            = BASE_SC * $GLOBALS['fuelFac'][$fuel];
$test_L_run    = $test_kW * $sc * ($test_minutes/60);
$test_L_year   = $test_L_run * $tests_per_year;
$test_CO2_year = $test_L_year * $co2;
$test_cost_year= $test_L_year * $price;

$totalL     = $profL + $test_L_year;
$totalCost  = $profCost + $test_cost_year;
$totalCO2   = $profCO2 + $test_CO2_year;
$totHours   = $profH   + $tests_per_year * ($test_minutes/60);

$monthly_profL  = $profL / 12;
$monthly_testL  = $test_L_year / 12;

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

// --- Layout-start (använd din vanliga layout-include om du har) ---
include '../../includes/layout-start.php';
?>

<main class="layout__container">
  <h1 class="rubrik">RKA-kalkylator med provkörning &amp; tankprognos</h1>

  <!-- Länksamling till verktygssidor -->
  <nav class="menykort menykort--center mt-2 mb-2" aria-label="Verktygsnavigering">
    <a class="menykort__lank<?=basename(__FILE__)=='index.php'?' menykort__lank--aktiv':''?>" href="index.php" data-tippy-content="Snabb kalkyl för bränsle och tank">Snabbkalkyl</a>
    <a class="menykort__lank<?=basename(__FILE__)=='avancerad.php'?' menykort__lank--aktiv':''?>" href="avancerad.php" data-tippy-content="Avancerad kalkyl med miljö och ekonomi">Avancerad</a>
    <a class="menykort__lank<?=basename(__FILE__)=='a2.php'?' menykort__lank--aktiv':''?>" href="a2.php" data-tippy-content="Provkörnings-kalkylator">Avancerad 2</a>
    <a class="menykort__lank<?=basename(__FILE__)=='provkorning.php'?' menykort__lank--aktiv':''?>" href="provkorning.php" data-tippy-content="Provkörning & tankprognos">Provkörning & tankprognos</a>
  </nav>
  <!-- /Länksamling -->

  <!-- ********** START Sektion: Formulär ********** -->
  <form id="advForm" method="post" autocomplete="off" class="form">
    <div class="form__grupp">
      <label class="falt__etikett" for="rating">Märkeffekt</label>
      <input id="rating" name="rating" type="number" min="1" step="0.1" class="falt__input" value="<?=$rating?>" />
      <span class="form__hint">kVA</span>
    </div>
    <div class="form__grupp">
      <label class="falt__etikett" for="test_kW">Testlast <small>(kW)</small></label>
      <input id="test_kW" name="test_kW" type="number" min="1" step="1" class="falt__input" value="<?=$test_kW?>" />
    </div>
    <div class="form__grupp">
      <label class="falt__etikett" for="test_minutes">Testtid per gång (min)</label>
      <input id="test_minutes" name="test_minutes" type="number" min="1" step="1" class="falt__input" value="<?=$test_minutes?>" />
    </div>
    <div class="form__grupp">
      <label class="falt__etikett" for="test_freq">Intervall</label>
      <select id="test_freq" name="test_freq" class="falt__select">
        <option value="weekly"<?=$test_freq=='weekly'?' selected':''?>>varje vecka</option>
        <option value="fortnight"<?=$test_freq=='fortnight'?' selected':''?>>varannan vecka</option>
        <option value="monthly"<?=$test_freq=='monthly'?' selected':''?>>varje månad</option>
        <option value="quarterly"<?=$test_freq=='quarterly'?' selected':''?>>varje kvartal</option>
      </select>
    </div>
    <div class="form__grupp">
      <label class="falt__etikett" for="tank_cap">Tankvolym (L)</label>
      <input id="tank_cap" name="tank_cap" type="number" class="falt__input" value="<?=$tank_cap?>" />
    </div>
    <div class="form__grupp">
      <label class="falt__etikett" for="tank_level">Nuvarande nivå (L)</label>
      <input id="tank_level" name="tank_level" type="number" class="falt__input" value="<?=$tank_level?>" />
    </div>
    <div class="form__grupp">
      <label class="falt__etikett" for="alert_pct">Larmgräns (%)</label>
      <input id="alert_pct" name="alert_pct" type="number" class="falt__input" value="<?=$alert_pct?>" />
    </div>

    <fieldset class="kort mt-1">
      <legend class="kort__rubrik">Lastprofil (enkel)</legend>
      <div class="tabell__wrapper">
        <table id="profileTable" class="tabell">
          <thead>
            <tr>
              <th>#</th>
              <th>Tid (h)</th>
              <th>Last (kW)</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($profile as $i => $row): ?>
              <tr>
                <td><?= $i+1 ?></td>
                <td><input type="number" ... value="<?=htmlspecialchars($row['hours'])?>"></td>
                <td><input type="number" ... value="<?=htmlspecialchars($row['loadkW'])?>"></td>
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
      <input type="hidden" id="profileData" name="profileData" value='<?=htmlspecialchars(json_encode($profile))?>'>
    </fieldset>

    <div class="form__verktyg">
      <button type="submit" class="knapp" data-tippy-content="Beräkna och visa resultat">Beräkna</button>
    </div>
  </form>
  <!-- ********** SLUT Sektion: Formulär ********** -->

  <!-- ********** START Sektion: Resultat ********** -->
  <section class="kort mt-1">
    <h2 class="kort__rubrik">Sammanfattning av provkörningen</h2>
    <div class="kort__innehall">
      <p>
        Vid varje provkörning belastas elverket med <strong><?=number_format($test_kW,1)?> kW</strong>
        i <strong><?=$test_minutes?> minuter</strong>.
        Det motsvarar <strong><?=number_format($test_L_run,1)?> liter</strong> per tillfälle,
        vilket med intervall <strong><?=$test_freq?></strong>
        ger ungefär <strong><?=number_format($test_L_year,1)?> liter per år</strong>.
      </p>
      <p>
        Tabellen nedan visar hur både den ordinarie driften (kolumnen ”Drift L”)
        och provkörningarna (”Prov L”) påverkar bränslenivån månad för månad de
        närmaste två åren. Rader som når din larmgräns på <strong><?=$alert_pct?> %</strong>
        markeras med röd bakgrund.
      </p>
    </div>
  </section>

  <?php if($totHours>0): ?>
  <section class="kort mt-1">
    <h2 class="kort__rubrik">24-månaders tankprognos</h2>
    <div class="tabell__wrapper">
      <table class="tabell sim">
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
            <tr class="<?=$r['alert']?'tabell--fara':''?>">
              <td><?=$r['month']?></td>
              <td><?=number_format($r['L_prof'],1)?></td>
              <td><?=number_format($r['L_test'],1)?></td>
              <td><?=number_format($r['remain'],1)?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </section>
  <?php endif; ?>
  <!-- ********** SLUT Sektion: Resultat ********** -->
</main>

<?php include '../../includes/layout-end.php'; ?>
<script src="profile.js" defer></script>
