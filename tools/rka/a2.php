<?php
// Aktivera felrapportering för debugging av 500-fel
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

// Spara debug-info om du vill felsöka, annars ta bort denna rad
// if ($_POST) file_put_contents(__DIR__.'/debug.txt', print_r($_POST, true));

const COS_PHI_DEF = 0.80; const BASE_SC = 0.25;
$fuelFac = ['ECOPAR'=>0.93,'DIESEL'=>1.00,'HVO100'=>1.04];
$defaults = [
 'rating'=>100,'ratingUnit'=>'kVA','cosphi'=>COS_PHI_DEF,
 'swedeTown'=>'Stockholm','genType'=>'Container','phase'=>'3-fas',
 'fuel'=>'ECOPAR','price'=>21,'co2'=>1.30,
 'provMin'=>30,'provEveryVal'=>1,'provEveryUnit'=>'månad',
 'runHrs'=>120,'tankInt'=>12,'buffDays'=>10,'buffPct'=>70
];
// Ladda värden från POST eller defaults
foreach($defaults as $k=>$v) $$k = isset($_POST[$k]) ? $_POST[$k] : $v;

// SMHI-orter
$smhi = json_decode(file_get_contents(__DIR__.'/towns.json'),true);
$townData = null;
foreach($smhi as $t) {
    if($t['name'] === $swedeTown) {
        $townData = $t;
        break;
    }
}
if(!$townData) $townData = $smhi[0];
$tempNorm=$townData['temp']; $altNorm=$townData['alt'];

// --- ALLA BERÄKNINGSFAKTORER måste sättas FÖRE lastprofilen ---
// Märkeffekt och derating
$ratingKW = $ratingUnit==='kW' ? $rating : $rating*$cosphi;
$derate   = 1 + max(0,($tempNorm-25)/5)*.01 + max(0,($altNorm-1000)/300)*.01;

// Hantera aktuell last-beräkning
$aktuellLast = floatval(isset($_POST['aktuellLast']) ? $_POST['aktuellLast'] : 50);
$aktuellLastUnit = isset($_POST['aktuellLastUnit']) ? $_POST['aktuellLastUnit'] : '%';
$aktuellTid = floatval(isset($_POST['aktuellTid']) ? $_POST['aktuellTid'] : 8);
$aktuellResult = null;

// --- Grundläggande kalkylatorberäkningar ---
$LphFull = $ratingKW*BASE_SC*$fuelFac[$fuel]*$derate;
$weeksPerMonth = 4.345;
$intervalMonths = $tankInt;
$testsPerYear = $provEveryUnit==='vecka'
 ? $intervalMonths*$weeksPerMonth/$provEveryVal
 : $intervalMonths/$provEveryVal;
$LperTest = ($provMin/60)*$LphFull;
$LprovTot = $LperTest*$testsPerYear;
$Lbuff    = $LphFull*24*$buffDays;
$Ldrift   = $LphFull*$runHrs;
$Lnett    = $LprovTot+$Lbuff+$Ldrift;

if ((isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET') === 'POST' && $aktuellLast > 0 && $aktuellTid > 0) {
  $loadKW = $aktuellLastUnit === 'kW' ? $aktuellLast :
            ($aktuellLastUnit === 'kVA' ? $aktuellLast * $cosphi :
             $ratingKW * ($aktuellLast / 100));

  $loadKVA = $loadKW / $cosphi;
  $loadPct = $ratingKW ? $loadKW / $ratingKW * 100 : 0;

  $sc = BASE_SC * $fuelFac[$fuel] * $derate;
  $Lph = $loadKW * $sc;
  $totalLiter = $Lph * $aktuellTid;
  $totalKr = $totalLiter * $price;
  $totalCO2 = $totalLiter * $co2;

  $aktuellResult = [
    'loadKW' => $loadKW,
    'loadKVA' => $loadKVA,
    'loadPct' => $loadPct,
    'Lph' => $Lph,
    'totalLiter' => $totalLiter,
    'totalKr' => $totalKr,
    'totalCO2' => $totalCO2,
    'hours' => $aktuellTid,
    'sc' => $sc
  ];

  // Lägg till aktuell last i totalen
  $Lnett += $totalLiter;
}

$Ltank = $Lnett/($buffPct/100);
$costY = $Lnett*$price; $co2Y = $Lnett*$co2;

// Tankspecifikationer för förbättrad sammanfattning
$ullage_pct = 10; // Ullage (fritt utrymme ovanför vätska)
$sump_pct = 5;    // Sump/dödvolym (kan ej pumpas ut)
$alert_pct = 30;  // Larmgräns (% av nettovolym)
$netto_pct = 100 - $ullage_pct - $sump_pct; // Användbara procent av bruttovolym
$usable_pct = round($netto_pct, 0); // För visning
$alert_level_L = round($Ltank * ($netto_pct/100) * ($alert_pct/100), 0); // Larmgräns i liter

$title = 'Avancerad kalkylator för dimensionering av tankvolym till reservkraftverk';
$metaDescription = 'Använd denna avancerade kalkylator för att enkelt dimensionera tankvolym till elverk och reservkraftverk. Beräkna snabbt rätt bränslemängd för säker och driftsäker reservkraft. Gratis onlineverktyg med tydliga resultat.';

// SEO-förbättringar
$keywords = 'tankvolym kalkylator, reservkraftverk dimensionering, bränsleberäkning generator, elverk tankstorlek, diesel tank kalkylator, HVO100 förbrukning, provkörning beräkning';
$canonical = 'https://mackan.eu/tools/rka/a2.php';

include '../../includes/layout-start.php';
?>

<div class="layout__container">
  <!-- Breadcrumbs för bättre SEO och navigation -->
  <nav class="breadcrumbs" aria-label="Du är här" style="margin-bottom: 1rem; font-size: 0.9rem;">
    <a href="/" style="color: #007bff; text-decoration: none;">Hem</a> ›
    <a href="/tools/" style="color: #007bff; text-decoration: none;">Verktyg</a> ›
    <a href="/tools/rka/" style="color: #007bff; text-decoration: none;">RKA-kalkylatorer</a> ›
    <span style="color: #6c757d;">Avancerad 2</span>
  </nav>

  <h1 class="rubrik"><?= $title ?></h1>

  <!-- Förbättrad introduktion för SEO -->
  <div class="intro-text" style="margin-bottom: 2rem; padding: 1rem; background: #f8f9fa; border-left: 4px solid #007bff; border-radius: 4px;">
    <p><strong>Professionell tankdimensionering för reservkraftverk</strong></p>
    <p>Denna avancerade kalkylator hjälper dig beräkna exakt tankvolym för reservkraftverk baserat på provkörning, buffertdagar och planerad drift. Verktyget tar hänsyn till klimatjustering, olika bränsletyper och ger detaljerade ekonomi- och miljöanalyser.</p>

    <details style="margin-top: 1rem;">
      <summary style="cursor: pointer; font-weight: 600; color: #007bff;">💡 Vad kan denna kalkylator beräkna?</summary>
      <ul style="margin-top: 0.5rem; margin-bottom: 0;">
        <li>✅ Exakt tankvolym baserat på driftkrav</li>
        <li>✅ Provkörningskostnader per år</li>
        <li>✅ Klimatjustering för temperatur och höjd</li>
        <li>✅ Jämförelse mellan diesel, HVO100 och Ecopar</li>
        <li>✅ CO₂-påverkan och miljöanalys</li>
        <li>✅ Ekonomisk analys med kostnad per kWh</li>
      </ul>
    </details>
  </div>

  <!-- Länksamling till verktygssidor -->
  <nav class="menykort menykort--center mt-2 mb-2" aria-label="Verktygsnavigering">
    <a class="menykort__lank<?=basename(__FILE__)=='index.php'?' menykort__lank--aktiv':''?>" href="index.php" data-tippy-content="Snabb kalkyl för bränsle och tank">Snabbkalkyl</a>
    <a class="menykort__lank<?=basename(__FILE__)=='avancerad.php'?' menykort__lank--aktiv':''?>" href="avancerad.php" data-tippy-content="Avancerad kalkyl med miljö och ekonomi">Avancerad</a>
    <a class="menykort__lank<?=basename(__FILE__)=='a2.php'?' menykort__lank--aktiv':''?>" href="a2.php" data-tippy-content="Provkörnings-kalkylator">Avancerad 2</a>
    <a class="menykort__lank<?=basename(__FILE__)=='provkorning.php'?' menykort__lank--aktiv':''?>" href="provkorning.php" data-tippy-content="Provkörning & tankprognos">Provkörning & tankprognos</a>
    <a class="menykort__lank<?=basename(__FILE__)=='a2_readme.php'?' menykort__lank--aktiv':''?>" href="a2_readme.php" data-tippy-content="Teknisk dokumentation och README">📖 README</a>
  </nav>
  <!-- /Länksamling -->

  <!-- ********** START Sektion: Formulär ********** -->
  <form id="advForm" class="form" method="post" autocomplete="off">

    <div class="form__grupp">
      <label class="falt__etikett" for="genType">Elverkstyp</label>
      <select class="falt__dropdown" id="genType" name="genType">
        <?php foreach(['Container','Öppet','Inbyggt'] as $t): ?>
          <option value="<?=$t?>" <?=$genType===$t?'selected':''?>><?=$t?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form__grupp">
      <label class="falt__etikett" for="phase">Fassystem</label>
      <select class="falt__dropdown" id="phase" name="phase">
        <option <?=$phase==='1-fas'?'selected':''?>>1-fas</option>
        <option <?=$phase==='3-fas'?'selected':''?>>3-fas</option>
      </select>
      <small class="form__hint">Påverkar strömuttaget per fas i resultatet.</small>
    </div>
    <div class="form__grupp">
      <label class="falt__etikett" for="swedeTown">Välj svensk ort (SMHI normal)</label>
      <select class="falt__dropdown" id="swedeTown" name="swedeTown">
        <?php foreach($smhi as $loc): ?>
          <option value="<?=$loc['name']?>" <?=$loc['name']===$swedeTown?'selected':''?>>
            <?=$loc['name']?> (<?=$loc['temp']?> °C / <?=$loc['alt']?> m)
          </option>
        <?php endforeach; ?>
      </select>
      <small class="form__hint">
        Temperatur och höjd avgör motorns derating.
        <span data-tippy-content="Varje 5 °C över 25 °C ökar bränsleåtgången ≈ 1%. Varje 300 m över 1000 möh ökar bränsleåtgången ≈ 1%. Baserat på Cummins/CAT/Generac deratingtabeller. Kalkylen applicerar justeringen automatiskt." style="cursor: help; text-decoration: underline dotted;">📊 Detaljerad info</span>
      </small>
    </div>
    <div class="form__grupp">
      <label class="falt__etikett" for="rating">Märkeffekt</label>
      <input class="falt__input" id="rating" name="rating" type="number" min="1" step="0.1" value="<?=htmlspecialchars($rating)?>">
      <select class="falt__dropdown" id="ratingUnit" name="ratingUnit">
        <option value="kVA"<?=$ratingUnit==='kVA'?' selected':''?>>kVA</option>
        <option value="kW" <?=$ratingUnit==='kW'?' selected':''?>>kW</option>
      </select>
      <small class="form__hint">Typiska värden: 20-500 kVA för mindre anläggningar, 500-2000 kVA för större.</small>
    </div>
    <div class="form__grupp">
      <label class="falt__etikett" for="cosphi">Effektfaktor (cos φ)</label>
      <input class="falt__input" id="cosphi" name="cosphi" type="number" min="0.4" max="1" step="0.01" value="<?=htmlspecialchars($cosphi)?>">
      <small class="form__hint">Typiska värden: 0.8-0.9 för blandad last, 0.85 för kontor, 0.95 för resistiv last.</small>
    </div>
    <div class="form__grupp">
      <label class="falt__etikett" for="fuel">Drivmedel</label>
      <select class="falt__dropdown" id="fuel" name="fuel">
        <?php foreach($fuelFac as $k=>$v): ?>
          <option value="<?=$k?>" <?=$fuel===$k?'selected':''?>><?=ucfirst(strtolower($k))?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form__grupp">
      <label class="falt__etikett" for="co2">CO₂-faktor (kg/L)</label>
      <input class="falt__input" id="co2" name="co2" type="number" min="0" step="0.01" value="<?=htmlspecialchars($co2)?>">
    </div>
    <div class="form__grupp">
      <label class="falt__etikett" for="price">Pris (kr/L)</label>
      <input class="falt__input" id="price" name="price" type="number" min="0" step="0.1" value="<?=htmlspecialchars($price)?>">
      <small class="form__hint">Aktuella priser: Diesel ~18 kr/L, HVO100 ~23 kr/L, Ecopar ~21 kr/L.</small>
    </div>
    <div class="form__grupp">
      <label class="falt__etikett" for="provMin">Provkörningstid (min)</label>
      <input class="falt__input" id="provMin" name="provMin" type="number" min="0" step="1" value="<?=htmlspecialchars($provMin)?>">
      <small class="form__hint">NFPA 110 kräver ≥ 30 min / månad.</small>
    </div>
    <div class="form__grupp">
      <label class="falt__etikett" for="provEveryVal">Provkörnings­intervall</label>
      <input class="falt__input" id="provEveryVal" name="provEveryVal" type="number" min="1" step="1" value="<?=htmlspecialchars($provEveryVal)?>">
      <select class="falt__dropdown" id="provEveryUnit" name="provEveryUnit">
        <?php foreach(['vecka','månad','kvartal'] as $u): ?>
          <option value="<?=$u?>" <?=$provEveryUnit===$u?'selected':''?>><?=$u?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form__grupp">
      <label class="falt__etikett" for="runHrs">Planerade drifttimmar per år</label>
      <input class="falt__input" id="runHrs" name="runHrs" type="number" min="0" step="1" value="<?=htmlspecialchars($runHrs)?>">
      <small class="form__hint">Typiska värden: 50-200h för backup, 500-2000h för primärkraft.</small>
    </div>
    <div class="form__grupp">
      <label class="falt__etikett" for="tankInt">Tanknings­intervall (mån)</label>
      <input class="falt__input" id="tankInt" name="tankInt" type="number" min="1" step="1" value="<?=htmlspecialchars($tankInt)?>">
      <small class="form__hint">Rekommenderat: 6-12 månader beroende på bränslets hållbarhet.</small>
    </div>
    <div class="form__grupp">
      <label class="falt__etikett" for="buffDays">Buffert (oavbruten drift)</label>
      <input class="falt__input" id="buffDays" name="buffDays" type="number" min="1" step="1" value="<?=htmlspecialchars($buffDays)?>">
      dygn vid
      <input class="falt__input" id="buffPct" name="buffPct" type="number" min="10" max="90" step="1" value="<?=htmlspecialchars($buffPct)?>"> %
      <small class="form__hint">Kritiska system: 7-14 dygn, normala system: 3-7 dygn.</small>
    </div>

    <!-- Aktuell last fält -->
    <div class="form__grupp">
      <label class="falt__etikett" for="aktuellLast">Aktuell last</label>
      <input class="falt__input" id="aktuellLast" name="aktuellLast" type="number" min="0" step="0.1"
             value="<?=htmlspecialchars(isset($_POST['aktuellLast']) ? $_POST['aktuellLast'] : '50')?>">>>
      <select class="falt__dropdown" id="aktuellLastUnit" name="aktuellLastUnit">
        <?php $unit = isset($_POST['aktuellLastUnit']) ? $_POST['aktuellLastUnit'] : '%'; ?>
        <option value="%" <?=$unit=='%'?'selected':''?>>% av märk</option>
        <option value="kW" <?=$unit=='kW'?'selected':''?>>kW</option>
        <option value="kVA" <?=$unit=='kVA'?'selected':''?>>kVA</option>
      </select>
      <small class="form__hint">Rekommenderat: 50-80% för optimal drift. Över 85% minskar livslängden.</small>
    </div>
    <div class="form__grupp">
      <label class="falt__etikett" for="aktuellTid">Drifttid</label>
      <input class="falt__input" id="aktuellTid" name="aktuellTid" type="number" min="0" step="0.1"
             value="<?=htmlspecialchars(isset($_POST['aktuellTid']) ? $_POST['aktuellTid'] : '8')?>"> timmar
      <small class="form__hint">Enkel beräkning med konstant last under angiven tid.</small>
    </div>

    <div class="form__grupp">
      <button type="submit" class="knapp">Beräkna</button>
    </div>
  </form>

  <?php if ((isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET') === 'POST') { ?>

    <div class="layout__sektion">
      <h2 class="rubrik--sektion">Tekniska data</h2>
      <div class="tabell__wrapper">
        <table class="tabell">
          <tbody>
            <tr><td class="tabell__cell">Märkeffekt nominellt</td><td class="tabell__cell"><?=$rating?> <?=$ratingUnit?><?php if($ratingUnit==='kVA'): ?>(× cos φ <?=$cosphi?> ⇒ <?=number_format($ratingKW,1)?> kW)<?php else: ?>(<?=number_format($rating/$cosphi,1)?> kVA vid cos φ <?=$cosphi?>)<?php endif; ?></td></tr>
            <tr><td class="tabell__cell">Justerad effekt efter derating</td><td class="tabell__cell"><?=number_format($ratingKW*$derate,1)?> kW</td></tr>
            <tr><td class="tabell__cell">cos φ</td><td class="tabell__cell"><?=$cosphi?> • <?=$phase?></td></tr>
          </tbody>
        </table>
      </div>
    </div>

    <?php if($aktuellResult): ?>
    <div class="layout__sektion">
      <h2 class="rubrik--sektion">Aktuell last – resultat</h2>
      <div class="tabell__wrapper">
        <table class="tabell">
          <tbody>
            <tr><td class="tabell__cell">Last</td><td class="tabell__cell"><?= number_format($aktuellResult['loadKW'], 1) ?> kW (<?= number_format($aktuellResult['loadKVA'], 1) ?> kVA)</td></tr>
            <tr><td class="tabell__cell">Belastning</td><td class="tabell__cell"><?= number_format($aktuellResult['loadPct'], 1) ?>% av märkeffekt</td></tr>
            <tr><td class="tabell__cell">Förbrukning</td><td class="tabell__cell"><?= number_format($aktuellResult['Lph'], 2) ?> L/h</td></tr>
            <tr><td class="tabell__cell">Drifttid</td><td class="tabell__cell"><?= number_format($aktuellResult['hours'], 1) ?> timmar</td></tr>
            <tr><td class="tabell__cell"><strong>Total förbrukning</strong></td><td class="tabell__cell"><strong><?= number_format($aktuellResult['totalLiter'], 1) ?> L</strong></td></tr>
            <tr><td class="tabell__cell">Kostnad</td><td class="tabell__cell"><?= number_format($aktuellResult['totalKr'], 0, ' ', ' ') ?> kr</td></tr>
            <tr><td class="tabell__cell">CO₂-utsläpp</td><td class="tabell__cell"><?= number_format($aktuellResult['totalCO2'], 1) ?> kg</td></tr>
            <tr><td class="tabell__cell">Schablon justerad</td><td class="tabell__cell"><?= number_format($aktuellResult['sc'], 3) ?> L/kWh</td></tr>
            <tr><td class="tabell__cell" colspan="2">Inkluderar derating för temperatur och höjd över havet.</td></tr>
          </tbody>
        </table>
      </div>
    </div>
    <?php endif; ?>

    <div class="layout__sektion">
      <h2 class="rubrik--sektion">Förbrukning & buffert</h2>
      <div class="tabell__wrapper">
        <table class="tabell">
          <tbody>
            <tr><td class="tabell__cell">Test/provkörning per år</td><td class="tabell__cell"><?=round($testsPerYear,1)?> gånger → <?=number_format($LprovTot,1)?> L</td></tr>
            <tr><td class="tabell__cell">Buffert <?=$buffDays?> dygn</td><td class="tabell__cell"><?=number_format($Lbuff,0)?> L</td></tr>
            <tr><td class="tabell__cell">Planerad profildrift (<?=$runHrs?> h)</td><td class="tabell__cell"><?=number_format($Ldrift,0)?> L</td></tr>
            <tr><td class="tabell__cell">Netto tankvolym</td><td class="tabell__cell"><?=number_format($Lnett,0)?> L</td></tr>
            <tr><td class="tabell__cell">Brutto vid <?=$buffPct?> % fyllnad</td><td class="tabell__cell"><strong><?=number_format($Ltank,0)?> L</strong></td></tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="layout__sektion">
      <h2 class="rubrik--sektion">Ekonomi & miljö</h2>
      <div class="tabell__wrapper">
        <table class="tabell">
          <tbody>
            <tr><td class="tabell__cell">Kostnad/år</td><td class="tabell__cell"><?=number_format($costY,0,' ',' ')?> kr</td></tr>
            <tr><td class="tabell__cell">CO₂/år</td><td class="tabell__cell"><?=number_format($co2Y,0,' ',' ')?> kg</td></tr>
            <?php $kgPerTree = 22; $trees = ceil($co2Y / $kgPerTree); $gPerKmCar = 0.12; $kmCar = ceil($co2Y / $gPerKmCar); ?>
            <tr><td class="tabell__cell">≈ <?=$trees?> träd krävs för att binda årets CO₂-utsläpp.</td><td class="tabell__cell">Motsvarar cirka <?=$kmCar?> km med en genomsnittlig bensinbil.</td></tr>
            <tr><td class="tabell__cell" colspan="2"><strong>Det motsvarar ungefär:</strong><br>
              • <?=$co2Y/1000>1?number_format($co2Y/1000,2):number_format($co2Y,0)?> ton CO₂, lika mycket som <?=number_format($co2Y/4600,1)?> årsutsläpp från en EU-genomsnittlig personbil (4,6 t/år).<br>
              • <?=$co2Y/BASE_SC/1000>1?number_format($co2Y/BASE_SC/1000,1):number_format($co2Y/BASE_SC,0)?> liter diesel förbrända i personbilar.
            </td></tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="layout__sektion">
      <h2 class="rubrik--sektion">Snabbsammanfattning – tankkapacitet och provkörning</h2>
      <p><strong>Vad du ser nedan:</strong></p>
      <ul>
        <li>Hur mycket bränsle som behövs för ordinarie drift + provkörning</li>
        <li>Hur stor del av tankvolymen som är användbar (netto)</li>
        <li>Var marginalerna finns (ullage & bottensump)</li>
        <li>När nivån når larmgränsen <?=$alert_pct?> %</li>
      </ul>

      <div class="tabell__wrapper">
        <table class="tabell">
          <tbody>
            <tr><td class="tabell__cell">Bruttovolym</td><td class="tabell__cell"><?=number_format($Ltank,0)?> L</td></tr>
            <tr><td class="tabell__cell" style="font-size: 0.9em; color: #666;">• <em>Bruttovolym = total geometrisk volym – innan ullage & sump har dragits av</em></td><td class="tabell__cell"></td></tr>
            <tr><td class="tabell__cell">Ullage (fritt utrymme)</td><td class="tabell__cell"><?=$ullage_pct?> % (<?=number_format($Ltank*$ullage_pct/100,0)?> L)</td></tr>
            <tr><td class="tabell__cell" style="font-size: 0.9em; color: #666;">• <em>Ullage är det fria utrymmet över vätskeytan som krävs för termisk expansion och säker påfyllning</em></td><td class="tabell__cell"></td></tr>
            <tr><td class="tabell__cell">Sump/dödvolym</td><td class="tabell__cell"><?=$sump_pct?> % (<?=number_format($Ltank*$sump_pct/100,0)?> L)</td></tr>
            <tr><td class="tabell__cell" style="font-size: 0.9em; color: #666;">• <em>Sump/dödvolym är den lägsta delen av tanken som aldrig kan pumpas ut pga. avlopp & föroreningar</em></td><td class="tabell__cell"></td></tr>
            <tr><td class="tabell__cell"><strong>Nettovolym (användbar)</strong></td><td class="tabell__cell"><strong><?=number_format($Lnett,0)?> L</strong></td></tr>
            <tr><td class="tabell__cell" style="font-size: 0.9em; color: #666;">• <em>Nettovolym = Bruttovolym × (1 – ullage – sump)</em></td><td class="tabell__cell"></td></tr>
            <tr><td class="tabell__cell">Användbar andel av tank</td><td class="tabell__cell"><?=$usable_pct?> % av bruttovolymen</td></tr>
            <tr><td class="tabell__cell">Larmgräns (<?=$alert_pct?> % av netto)</td><td class="tabell__cell"><?=number_format($alert_level_L,0)?> L</td></tr>
            <tr><td class="tabell__cell">Årlig förbrukning</td><td class="tabell__cell"><?=number_format($Lnett,0)?> L</td></tr>
            <tr><td class="tabell__cell">Kostnad/år</td><td class="tabell__cell"><?=number_format($costY,0)?> kr</td></tr>
          </tbody>
        </table>
      </div>

      <details style="margin-top: 1rem;">
        <summary><strong>Visa beräkningsmetod</strong></summary>
        <ul style="margin-top: 0.5rem;">
          <li><strong>Nettovolym (L)</strong> = Tank L × (1 – Ullage – Sump) = <?=number_format($Ltank,0)?> × (1 – <?=$ullage_pct/100?> – <?=$sump_pct/100?>) = <?=number_format($Lnett,0)?> L</li>
          <li><strong>Provkörning (L/gång)</strong> = <?=number_format($ratingKW,1)?> kW × 0,25 L/kWh × <?=$provMin?> min/60 = <?=number_format($LperTest,1)?> L</li>
          <li><strong>Provkörning (L/år)</strong> = <?=number_format($LperTest,1)?> L × <?=round($testsPerYear,1)?> gånger/år = <?=number_format($LprovTot,1)?> L</li>
          <li><strong>Buffert <?=$buffDays?> dygn</strong> = <?=number_format($ratingKW,1)?> kW × 0,25 L/kWh × 24 h × <?=$buffDays?> dygn = <?=number_format($Lbuff,0)?> L</li>
          <li><strong>Planerad drift</strong> = <?=number_format($ratingKW,1)?> kW × 0,25 L/kWh × <?=$runHrs?> h = <?=number_format($Ldrift,0)?> L</li>
        </ul>
      </details>

      <!-- ====== Bonus: Insikter ================================================= -->
      <?php
      /* --- beräkna kostnad/kWh och jämför tre bränslen ------------------------- */
      $fuels = ['DIESEL'=>1.00,'HVO100'=>1.04,'ECOPAR'=>0.93];
      $prices_map = ['DIESEL'=>17.8, 'HVO100'=>22.8, 'ECOPAR'=>21.0]; // Aktuella marknadspr iser

      // Beräkna total energiproduktion (kWh) per år
      $total_hours_år = $runHrs + ($testsPerYear * $provMin/60); // Total drifttid
      if ($aktuellResult) {
          $total_hours_år += $aktuellResult['hours']; // Lägg till aktuell last-tid
      }
      $kwh_år = max($total_hours_år * $ratingKW, 1); // kWh = timmar × effekt

      $cost_kwh = []; $delta = [];
      foreach($fuels as $f=>$fac){
          $L_tot_year = ($LprovTot + $Ldrift) * ($fac / $fuelFac[$fuel]); // Justerat för bränslefaktor
          if ($aktuellResult) {
              $L_tot_year += $aktuellResult['totalLiter'] * ($fac / $fuelFac[$fuel]);
          }
          $cost_kwh[$f] = ($L_tot_year * $prices_map[$f]) / $kwh_år;   // kr / kWh
      }

      // Beräkna skillnader från valt bränsle
      $selected_fuel_cost = isset($cost_kwh[$fuel]) ? $cost_kwh[$fuel] : (isset($cost_kwh['ECOPAR']) ? $cost_kwh['ECOPAR'] : 0);
      foreach($fuels as $f=>$fac){
          $delta[$f] = ($cost_kwh[$f] - $selected_fuel_cost);           // diff mot valt
      }

      // Tankstatus - beräkna dygn kvar baserat på årlig förbrukning
      $annual_consumption = $LprovTot + $Ldrift; // Total årlig förbrukning i liter
      if ($aktuellResult) {
          $annual_consumption += $aktuellResult['totalLiter'];
      }
      $daily_consumption = max($annual_consumption / 365, 0.1); // Undvik division med noll
      $current_tank_level = $Ltank * ($buffPct/100); // Nuvarande nivå baserat på buffert
      $remaining_until_alarm = max(0, $current_tank_level - $alert_level_L);
      $days_left = floor($remaining_until_alarm / $daily_consumption);
      ?>
      <details style="margin-top:1.4rem">
        <summary style="cursor:pointer;font-weight:600">
          🎁 Bonus&nbsp;&ndash;&nbsp;Insikter&nbsp;(kWh-pris, bränsle-jämförelse, tankstatus)
        </summary>
        <div style="padding:.8rem 1.2rem 0 1.2rem;font-size:.95em">
          <h3 style="margin:.3rem 0 .5rem">Kostnad per kWh</h3>
          <table style="border-collapse:collapse;width:100%;margin-bottom:1rem;border:1px solid #ddd">
            <thead>
              <tr style="background:#f5f5f5">
                <th style="text-align:left;padding:8px;border:1px solid #ddd">Bränsle</th>
                <th style="padding:8px;border:1px solid #ddd">kr&nbsp;/&nbsp;kWh</th>
                <th style="padding:8px;border:1px solid #ddd">Skillnad</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach($cost_kwh as $f=>$c): ?>
              <?php $cls = abs($delta[$f])>0.15 ? 'style="color:#c00;font-weight:600"' : ''; ?>
              <tr>
                <td style="padding:8px;border:1px solid #ddd"><?=$f?></td>
                <td style="padding:8px;border:1px solid #ddd;text-align:right"><?=number_format($c,2)?></td>
                <td style="padding:8px;border:1px solid #ddd;text-align:right" <?=$cls?>>
                  <?= $f===$fuel ? '– vald' :
                       ($delta[$f]>0?'+':'').number_format($delta[$f],2).' kr ('.number_format($selected_fuel_cost>0?$delta[$f]/$selected_fuel_cost*100:0,1).' %)' ?>
                </td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>

          <h3 style="margin:.3rem 0 .5rem">Tankstatus & Driftstid</h3>
          <div style="background:#f8f9fa;padding:12px;border-radius:6px;border-left:4px solid #28a745">
            <p><strong>Nuvarande tanknivå:</strong> <?=number_format($current_tank_level,0)?> L (<?=$buffPct?>% av <?=number_format($Ltank,0)?> L)</p>
            <p><strong>Daglig förbrukning:</strong> <?=number_format($daily_consumption,1)?> L/dag (baserat på årsprofil)</p>
            <p><strong>Dygn kvar till larm:</strong>
              <?php if ($days_left > 0): ?>
                <span style="color:#28a745;font-weight:600"><?=$days_left?> dygn</span>
              <?php else: ?>
                <span style="color:#dc3545;font-weight:600">Redan under larmgräns!</span>
              <?php endif; ?>
            </p>
            <p><strong>Larmgräns:</strong> <?=number_format($alert_level_L,0)?> L (<?=$alert_pct?>% av nettovolym)</p>
          </div>

          <?php if ($aktuellResult): ?>
          <h3 style="margin:1rem 0 .5rem">Aktuell Last-analys</h3>
          <p>Med din angivna last på <strong><?=number_format($aktuellResult['loadPct'],1)?>%</strong>
             (<?=number_format($aktuellResult['loadKW'],1)?> kW) under <?=number_format($aktuellResult['hours'],1)?> timmar:</p>
          <ul style="margin:.5rem 0">
            <li>Förbrukning: <strong><?=number_format($aktuellResult['Lph'],2)?> L/h</strong></li>
            <li>Total kostnad: <strong><?=number_format($aktuellResult['totalKr'],0)?> kr</strong></li>
            <li>Kostnad per kWh: <strong><?=number_format($aktuellResult['totalKr']/($aktuellResult['loadKW']*$aktuellResult['hours']),3)?> kr/kWh</strong></li>
          </ul>
          <?php endif; ?>

          <p style="font-size:.9em;color:#555;margin-top:1rem">
            <em>💡 Tips:&nbsp;Justera "Bränslepris" eller välj annan bränsletyp ovan
            och klicka <strong>Beräkna</strong> för att se hur skillnaderna förändras direkt.</em>
          </p>
        </div>
      </details>
      <!-- ======================================================================= -->

      <div style="background-color: #f8f9fa; border-left: 4px solid #007bff; padding: 1rem; margin-top: 1rem;">
        <h4 style="margin-top: 0;">Exempeltolkning</h4>
        <p>Aggregatet provkörs var <?=$provEveryVal?> <?=$provEveryUnit?> i <?=$provMin?> min med <?=number_format($ratingKW,1)?> kW.
        Det drar ≈ <?=number_format($LperTest,1)?> L per gång (0,25 L/kWh × belastning).
        Med planerad drift på <?=$runHrs?> h/år blir totalen <?=number_format($Lnett,0)?> L/år.
        <?php $years_to_alarm = $Lnett > 0 ? round($alert_level_L / $Lnett, 1) : 0; ?>
        Nettotanken är <?=number_format($Lnett,0)?> L – så du når larmnivån <?=$alert_pct?> % (≈ <?=number_format($alert_level_L,0)?> L) om cirka <?=$years_to_alarm?> år utan påfyllning.</p>
      </div>
    </div>

    <div class="layout__sektion">
      <h2 class="rubrik--sektion">Inmatade värden</h2>
      <div class="tabell__wrapper">
        <table class="tabell">
          <tbody>
            <tr><td class="tabell__cell">Elverkstyp</td><td class="tabell__cell"><?=htmlspecialchars($genType)?></td></tr>
            <tr><td class="tabell__cell">Fassystem</td><td class="tabell__cell"><?=htmlspecialchars($phase)?></td></tr>
            <tr><td class="tabell__cell">Ort</td><td class="tabell__cell"><?=htmlspecialchars($swedeTown)?></td></tr>
            <tr><td class="tabell__cell">Märkeffekt</td><td class="tabell__cell"><?=htmlspecialchars($rating)?> <?=htmlspecialchars($ratingUnit)?></td></tr>
            <tr><td class="tabell__cell">Effektfaktor (cos φ)</td><td class="tabell__cell"><?=htmlspecialchars($cosphi)?></td></tr>
            <tr><td class="tabell__cell">Drivmedel</td><td class="tabell__cell"><?=htmlspecialchars($fuel)?></td></tr>
            <tr><td class="tabell__cell">CO₂-faktor (kg/L)</td><td class="tabell__cell"><?=htmlspecialchars($co2)?></td></tr>
            <tr><td class="tabell__cell">Pris (kr/L)</td><td class="tabell__cell"><?=htmlspecialchars($price)?></td></tr>
            <tr><td class="tabell__cell">Provkörningstid (min)</td><td class="tabell__cell"><?=htmlspecialchars($provMin)?></td></tr>
            <tr><td class="tabell__cell">Provkörningsintervall</td><td class="tabell__cell"><?=htmlspecialchars($provEveryVal)?> <?=htmlspecialchars($provEveryUnit)?></td></tr>
            <tr><td class="tabell__cell">Planerade drifttimmar/år</td><td class="tabell__cell"><?=htmlspecialchars($runHrs)?></td></tr>
            <tr><td class="tabell__cell">Tankningsintervall (mån)</td><td class="tabell__cell"><?=htmlspecialchars($tankInt)?></td></tr>
            <tr><td class="tabell__cell">Buffert (dygn)</td><td class="tabell__cell"><?=htmlspecialchars($buffDays)?></td></tr>
            <tr><td class="tabell__cell">Buffertnivå (%)</td><td class="tabell__cell"><?=htmlspecialchars($buffPct)?></td></tr>
            <tr><td class="tabell__cell">Aktuell last</td><td class="tabell__cell"><?=htmlspecialchars(isset($_POST['aktuellLast']) ? $_POST['aktuellLast'] : '50')?> <?=htmlspecialchars(isset($_POST['aktuellLastUnit']) ? $_POST['aktuellLastUnit'] : '%')?></td></tr>
            <tr><td class="tabell__cell">Drifttid</td><td class="tabell__cell"><?=htmlspecialchars(isset($_POST['aktuellTid']) ? $_POST['aktuellTid'] : '8')?> timmar</td></tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="knapp__grupp">
      <button id="exportPDF" type="button" class="knapp">Exportera PDF</button>
      <button id="exportExcel" type="button" class="knapp">Exportera Excel</button>
      <button id="exportTXT" type="button" class="knapp">Exportera TXT</button>
    </div>
  <?php } ?>

  <!-- Strukturerad data för sökmotorer -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "WebApplication",
    "name": "Avancerad tankvolym-kalkylator för reservkraftverk",
    "description": "<?= htmlspecialchars($metaDescription) ?>",
    "url": "<?= isset($canonical) ? $canonical : '' ?>",
    "applicationCategory": "UtilityApplication",
    "operatingSystem": "Web Browser",
    "offers": {
      "@type": "Offer",
      "price": "0",
      "priceCurrency": "SEK"
    },
    "featureList": [
      "Tankvolymberäkning",
      "Provkörningsanalys",
      "Klimatjustering",
      "Bränslejämförelse",
      "CO₂-analys",
      "Ekonomisk kalkyl"
    ],
    "softwareRequirements": "Webbläsare",
    "author": {
      "@type": "Organization",
      "name": "Mackan.eu"
    }
  }
  </script>

  <!-- FAQ för bättre SEO -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
      {
        "@type": "Question",
        "name": "Hur beräknas tankvolym för reservkraftverk?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "Tankvolymen beräknas baserat på provkörning, buffertdagar, planerad drift och säkerhetsmarginaler. Kalkylatorn tar hänsyn till ullage (10%) och sump (5%) för att ge korrekt bruttovolym."
        }
      },
      {
        "@type": "Question",
        "name": "Vilka bränsletyper stöds i kalkylatorn?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "Kalkylatorn stöder Diesel (1.0 faktor), HVO100 (1.04 faktor) och Ecopar (0.93 faktor). Olika bränslen har olika energidensitet och påverkar förbrukningsberäkningen."
        }
      },
      {
        "@type": "Question",
        "name": "Vad är derating och hur påverkar det beräkningen?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "Derating justerar motoreffekten baserat på omgivningstemperatur och höjd över havet. Varje 5°C över 25°C eller 300m över 1000m ökar bränsleförbrukningen med cirka 1%."
        }
      }
    ]
  }
  </script>

  <!-- Relaterade verktyg för bättre intern länkning -->
  <aside class="related-content" style="margin-top: 3rem; padding: 2rem; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #28a745;">
    <h3 style="margin-top: 0; color: #28a745;">🔗 Relaterade verktyg och resurser</h3>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-top: 1rem;">
      <div>
        <h4 style="margin-bottom: 0.5rem; color: #495057;">📊 Andra RKA-kalkylatorer</h4>
        <ul style="list-style: none; padding: 0; margin: 0;">
          <li style="margin-bottom: 0.3rem;">→ <a href="index.php" style="color: #007bff; text-decoration: none;">Snabbkalkyl för bränsle och tank</a></li>
          <li style="margin-bottom: 0.3rem;">→ <a href="avancerad.php" style="color: #007bff; text-decoration: none;">Avancerad kalkyl med miljöanalys</a></li>
          <li>→ <a href="provkorning.php" style="color: #007bff; text-decoration: none;">Provkörning & tankprognos</a></li>
        </ul>
      </div>

      <div>
        <h4 style="margin-bottom: 0.5rem; color: #495057;">🛠️ Andra verktyg</h4>
        <ul style="list-style: none; padding: 0; margin: 0;">
          <li style="margin-bottom: 0.3rem;">→ <a href="../koordinat/" style="color: #007bff; text-decoration: none;">Koordinatverktyg</a></li>
          <li style="margin-bottom: 0.3rem;">→ <a href="../qr_v2/" style="color: #007bff; text-decoration: none;">QR-kodgenerator</a></li>
          <li>→ <a href="../../" style="color: #007bff; text-decoration: none;">Alla verktyg</a></li>
        </ul>
      </div>
    </div>

    <div style="margin-top: 1.5rem; padding: 1rem; background: white; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
      <h4 style="margin-top: 0; color: #495057;">📖 Teknisk dokumentation</h4>
      <p style="margin-bottom: 0.5rem;">För djupare förståelse av beräkningsmetoder och tekniska specifikationer:</p>
      <a href="a2_readme.php" style="color: #007bff; font-weight: 600; text-decoration: none;">→ Läs fullständig dokumentation och README</a>
    </div>
  </aside>

  <!-- SEO-vänlig sammanfattning -->
  <section class="seo-summary" style="margin-top: 2rem; padding: 1.5rem; border: 1px solid #dee2e6; border-radius: 8px; background: #ffffff;">
    <h3 style="margin-top: 0; color: #495057;">Sammanfattning: Avancerad tankdimensionering</h3>
    <p>Denna kalkylator är speciellt utvecklad för professionell dimensionering av bränsletankar till reservkraftverk. Verktyget beräknar exakt tankvolym baserat på:</p>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; margin: 1rem 0;">
      <div>
        <strong style="color: #6f42c1;">🔧 Tekniska faktorer:</strong>
        <ul style="margin: 0.5rem 0;">
          <li>Märkeffekt och cos φ</li>
          <li>Klimatjustering (derating)</li>
          <li>Bränsletype och förbrukningsfaktor</li>
        </ul>
      </div>

      <div>
        <strong style="color: #17a2b8;">⏱️ Driftparametrar:</strong>
        <ul style="margin: 0.5rem 0;">
          <li>Provkörningsschema</li>
          <li>Planerade drifttimmar</li>
          <li>Buffertdagar</li>
        </ul>
      </div>

      <div>
        <strong style="color: #28a745;">💰 Ekonomi & miljö:</strong>
        <ul style="margin: 0.5rem 0;">
          <li>Årskostnad per bränsle</li>
          <li>CO₂-utsläpp och miljöpåverkan</li>
          <li>Kostnad per kWh</li>
        </ul>
      </div>
    </div>

    <p style="margin-bottom: 0;"><em>Resultatet ger dig bruttovolym inklusive ullage och sump, samt detaljerad analys av driftsekonomi och miljöpåverkan.</em></p>
  </section>
</div>

<style>
/* Mobile responsiveness and UX improvements */
html {
  scroll-behavior: smooth;
}

/* Auto-submit indicator */
.form--submitting {
  position: relative;
}

.form--submitting::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 3px;
  background: linear-gradient(90deg, #007bff, #28a745, #007bff);
  background-size: 200% 100%;
  animation: autoSubmitProgress 0.8s ease-in-out;
  z-index: 10;
}

@keyframes autoSubmitProgress {
  0% {
    background-position: -200% 0;
    opacity: 0;
  }
  10% {
    opacity: 1;
  }
  90% {
    opacity: 1;
  }
  100% {
    background-position: 200% 0;
    opacity: 0;
  }
}

@media (max-width: 768px) {
  .form__grupp {
    margin-bottom: 1rem;
  }

  .falt__input, .falt__dropdown {
    font-size: 16px; /* Prevents zoom on iOS */
  }

  /* Stack export buttons vertically on mobile */
  .knapp__grupp {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
  }

  .knapp__grupp .knapp {
    width: 100%;
    margin: 0;
  }

  /* Better table display on mobile */
  .tabell__wrapper {
    overflow-x: auto;
  }

  .tabell {
    min-width: 300px;
  }

  /* Compact form layout */
  .form__grupp label {
    font-size: 0.9rem;
    font-weight: 600;
  }

  .form__hint {
    font-size: 0.8rem;
  }
}

@media (min-width: 769px) {
  /* Desktop: horizontal export buttons */
  .knapp__grupp {
    display: flex;
    flex-direction: row;
    gap: 1rem;
    justify-content: center;
  }
}

/* Input validation styling */
.input-warning {
  animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-5px); }
  to { opacity: 1; transform: translateY(0); }
}

/* Enhanced tooltips */
[data-tippy-content] {
  cursor: help;
  text-decoration: underline dotted;
  color: #0066cc;
}

/* Loading state for export buttons */
.knapp:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.knapp.loading::after {
  content: "...";
  animation: dots 1s infinite;
}

@keyframes dots {
  0%, 20% { content: ""; }
  40% { content: "."; }
  60% { content: ".."; }
  80%, 100% { content: "..."; }
}
</style>

<?php include '../../includes/layout-end.php'; ?>
<script src="a2.js" defer></script>
