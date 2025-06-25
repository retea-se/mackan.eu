<?php
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
foreach($defaults as $k=>$v) $$k = $_POST[$k] ?? $v;

// SMHI-orter
$smhi = json_decode(file_get_contents(__DIR__.'/towns.json'),true);
$townData = array_values(array_filter($smhi,fn($t)=>$t['name']===$swedeTown))[0]??$smhi[0];
$tempNorm=$townData['temp']; $altNorm=$townData['alt'];

// --- ALLA BERÄKNINGSFAKTORER måste sättas FÖRE lastprofilen ---
// Märkeffekt och derating
$ratingKW = $ratingUnit==='kW' ? $rating : $rating*$cosphi;
$derate   = 1 + max(0,($tempNorm-25)/5)*.01 + max(0,($altNorm-1000)/300)*.01;

// Lastprofil – alltid 3 rader, med default
$lpTime = $_POST['lpTime'] ?? ['8','8','8'];
$lpLoad = $_POST['lpLoad'] ?? ['50','50','50'];
$lpUnit = $_POST['lpUnit'] ?? ['%','%','%'];
$rows = 3;

// Förbereder summering för visning och beräkning
$lpRows = []; $totalHrs = 0; $sumKW = 0;
for ($i=0; $i<$rows; $i++) {
  $hrs = floatval($lpTime[$i] ?? 0);
  $val = floatval($lpLoad[$i] ?? 0);
  $u   = $lpUnit[$i] ?? '%';
  if($hrs<=0 || $val<=0) continue;
  $kW = $u==='kW' ? $val : ($u==='kVA' ? $val*$cosphi : $ratingKW*($val/100));
  $kVA = $kW / $cosphi;
  $liter = $hrs * $kW * BASE_SC * $fuelFac[$fuel] * $derate;
  $kr    = $liter * $price;
  $co2kg = $liter * $co2;
  $lpRows[] = [
    $i+1, $hrs, $kW, $kVA, $liter, $kr, $co2kg
  ];
  $totalHrs += $hrs;
  $sumKW    += $kW * $hrs;
}
$avgKW = $totalHrs>0 ? $sumKW/$totalHrs : 0;

// --- Resterande kalkylatorberäkningar ---
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

// Ta med lastprofil i totalen
if(!empty($lpRows)) {
  foreach($lpRows as [$n,$hrs,$kW]) {
    $Lnett += $hrs * $kW * BASE_SC * $fuelFac[$fuel] * $derate;
  }
}
$Ltank = $Lnett/($buffPct/100);
$costY = $Lnett*$price; $co2Y = $Lnett*$co2;
?>
<!doctype html><html lang="sv"><head>
<meta charset="utf-8">
<title>Provkörnings-kalkylator – robust RKA & drivmedel</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="stylesheet" href="style.css">
<style>
.tbl{border:1px solid #ddd;border-collapse:collapse;width:100%;margin-top:.5rem}
.tbl th,.tbl td{border:1px solid #ddd;padding:.25rem .4rem;text-align:center}
input:invalid{border-color:#ea4335}
fieldset{margin-bottom:1.3rem}
</style>
</head><body>

<nav class="breadcrumb">
 <a href="/tools/">Start</a> ›
 <a href="/tools/rka/index.php">Enkel</a> ›
 <a href="/tools/rka/avancerad.php">Avancerad</a> ›
 <strong>Provkörning</strong>
</nav>

<h1>Robust provkörnings-kalkylator för reservkraft</h1>

<form id="advForm" method="post" autocomplete="off">

<!-- Generatorinställningar -->
<label>Elverkstyp
 <select id="genType" name="genType">
   <?php foreach(['Container','Öppet','Inbyggt'] as $t): ?>
     <option value="<?=$t?>" <?=$genType===$t?'selected':''?>><?=$t?></option>
   <?php endforeach; ?>
 </select>
</label>

<label>Fassystem
 <select id="phase" name="phase">
   <option <?=$phase==='1-fas'?'selected':''?>>1-fas</option>
   <option <?=$phase==='3-fas'?'selected':''?>>3-fas</option>
 </select>
 <small class="hint">Påverkar strömuttaget per fas i resultatet.</small>
</label>

<label>Välj svensk ort (SMHI normal)
 <select id="swedeTown" name="swedeTown">
  <?php foreach($smhi as $loc): ?>
    <option value="<?=$loc['name']?>" <?=$loc['name']===$swedeTown?'selected':''?>>
      <?=$loc['name']?> (<?=$loc['temp']?> °C / <?=$loc['alt']?> m)
    </option>
  <?php endforeach; ?>
 </select>
 <small class="hint">
  Temperatur och höjd avgör motorns derating. +5 °C över 25 °C ≈ +1 % mer bränsle;
  300 m över 1 000 m ≈ +1 % – justeras automatiskt i kalkylen.
 </small>
</label>

<label>Märkeffekt
 <input id="rating" name="rating" type="number" min="1" step="0.1" value="<?=htmlspecialchars($rating)?>">
 <select id="ratingUnit" name="ratingUnit">
   <option value="kVA"<?=$ratingUnit==='kVA'?' selected':''?>>kVA</option>
   <option value="kW" <?=$ratingUnit==='kW'?' selected':''?>>kW</option>
 </select>
</label>

<label>Effektfaktor (cos φ)
 <input id="cosphi" name="cosphi" type="number" min="0.4" max="1" step="0.01" value="<?=htmlspecialchars($cosphi)?>">
</label>

<!-- Bränsle -->
<label>Drivmedel
 <select id="fuel" name="fuel">
   <?php foreach($fuelFac as $k=>$v): ?>
     <option value="<?=$k?>" <?=$fuel===$k?'selected':''?>><?=ucfirst(strtolower($k))?></option>
   <?php endforeach; ?>
 </select>
</label>

<label>CO₂-faktor (kg/L)
 <input id="co2" name="co2" type="number" min="0" step="0.01" value="<?=htmlspecialchars($co2)?>">
</label>

<label>Pris (kr/L)
 <input id="price" name="price" type="number" min="0" step="0.1" value="<?=htmlspecialchars($price)?>">
</label>

<!-- Provkörning -->
<label>Provkörningstid (min)
 <input id="provMin" name="provMin" type="number" min="0" step="1" value="<?=htmlspecialchars($provMin)?>">
 <small class="hint">NFPA 110 kräver ≥ 30 min / månad.</small>
</label>

<label>Provkörnings­intervall
 <input id="provEveryVal" name="provEveryVal" type="number" min="1" step="1" value="<?=htmlspecialchars($provEveryVal)?>">
 <select id="provEveryUnit" name="provEveryUnit">
   <?php foreach(['vecka','månad','kvartal'] as $u): ?>
     <option value="<?=$u?>" <?=$provEveryUnit===$u?'selected':''?>><?=$u?></option>
   <?php endforeach; ?>
 </select>
</label>

<!-- Drift & buffert -->
<label>Planerade drifttimmar per år
 <input id="runHrs" name="runHrs" type="number" min="0" step="1" value="<?=htmlspecialchars($runHrs)?>">
</label>

<label>Tanknings­intervall (mån)
 <input id="tankInt" name="tankInt" type="number" min="1" step="1" value="<?=htmlspecialchars($tankInt)?>">
</label>

<label>Buffert (oavbruten drift)
 <input id="buffDays" name="buffDays" type="number" min="1" step="1" value="<?=htmlspecialchars($buffDays)?>">
 dygn vid
 <input id="buffPct" name="buffPct" type="number" min="10" max="90" step="1" value="<?=htmlspecialchars($buffPct)?>"> %
</label>

<!-- Lastprofil -->
<fieldset style="border:1px solid #ccc;border-radius:6px;padding:.6rem">
<legend>Lastprofil (enkel)</legend>
<table id="lpTable" class="tbl">
<thead>
  <tr>
    <th>#</th>
    <th>Tid (h)</th>
    <th>Last-värde</th>
    <th>Enhet</th>
    <th></th>
  </tr>
</thead>
<tbody>
<?php
for ($i=0; $i<$rows; $i++):
  $t = $lpTime[$i] ?? '';
  $l = $lpLoad[$i] ?? '';
  $u = $lpUnit[$i] ?? '%';
?>
  <tr>
    <td><?=($i+1)?></td>
    <td><input name="lpTime[]" type="number" min="0" step="0.1" value="<?=htmlspecialchars($t)?>"></td>
    <td><input name="lpLoad[]" type="number" min="0" step="0.1" value="<?=htmlspecialchars($l)?>"></td>
    <td>
      <select name="lpUnit[]">
        <option value="%"   <?=$u=='%'  ?'selected':''?>>% av märk</option>
        <option value="kW"  <?=$u=='kW' ?'selected':''?>>kW</option>
        <option value="kVA" <?=$u=='kVA'?'selected':''?>>kVA</option>
      </select>
    </td>
    <td><button type="button" class="del">🗑️</button></td>
  </tr>
<?php endfor; ?>
</tbody>
</table>
<button id="addRow" type="button" style="margin-top:.4rem">+ Lägg till rad</button>
<button type="submit" style="margin-left:1rem">Beräkna lastprofil</button>
</fieldset>

</form>

<!-- Resultatrapport -->
<section id="output">
<?php if ($_SERVER['REQUEST_METHOD'] === 'POST') { ?>

<details open>
 <summary><strong>Detaljerad resultat­rapport</strong></summary>

<h2>Tekniska data</h2>
<ul>
 <li>Märkeffekt nominellt: <?=$rating?> <?=$ratingUnit?>
    <?php if($ratingUnit==='kVA'): ?>
        (× cos φ <?=$cosphi?> ⇒ <?=number_format($ratingKW,1)?> kW)
    <?php else: ?>
        (<?=number_format($rating/$cosphi,1)?> kVA vid cos φ <?=$cosphi?>)
    <?php endif; ?>
 </li>
 <li>Justerad effekt efter derating: <?=number_format($ratingKW*$derate,1)?> kW</li>
 <li>cos φ = <?=$cosphi?> &nbsp;•&nbsp; <?=$phase?></li>
</ul>

<?php if(count($lpRows) > 0) { ?>
<h3>Lastprofil – summering</h3>
<table class="tbl">
 <thead>
   <tr>
     <th>#</th>
     <th>Tid&nbsp;(h)</th>
     <th>Last&nbsp;kW</th>
     <th>Last&nbsp;kVA</th>
     <th>Liter</th>
     <th>Kostnad&nbsp;(kr)</th>
     <th>CO₂&nbsp;(kg)</th>
   </tr>
 </thead>
 <tbody>
  <?php
  $sumLiter=0; $sumKr=0; $sumCO2=0;
  foreach($lpRows as $row):
    list($n, $h, $kw, $kva, $liter, $kr, $co2kg) = $row;
    $sumLiter += $liter;
    $sumKr    += $kr;
    $sumCO2   += $co2kg;
  ?>
    <tr>
      <td><?= $n ?></td>
      <td><?= number_format($h,1) ?></td>
      <td><?= number_format($kw,1) ?></td>
      <td><?= number_format($kva,1) ?></td>
      <td><?= number_format($liter,1) ?></td>
      <td><?= number_format($kr,0,' ',' ') ?></td>
      <td><?= number_format($co2kg,1) ?></td>
    </tr>
  <?php endforeach; ?>
 </tbody>
 <tfoot>
   <tr style="font-weight:bold">
     <td colspan="4">Summa</td>
     <td><?= number_format($sumLiter,1) ?></td>
     <td><?= number_format($sumKr,0,' ',' ') ?></td>
     <td><?= number_format($sumCO2,1) ?></td>
   </tr>
 </tfoot>
</table>
<p class="hint">
  Total tid: <?= number_format($totalHrs,1) ?> h •
  Medelbelastning: <?= number_format($avgKW,1) ?> kW
  (<?= number_format($avgKW/$cosphi,1) ?> kVA)
</p>
<?php } ?>

<h2>Förbrukning & buffert</h2>
<ul>
 <li>Provkörningar/år: <?=round($testsPerYear,1)?> → <?=number_format($LprovTot,1)?> L</li>
 <li>Buffert <?=$buffDays?> dygn: <?=number_format($Lbuff,0)?> L</li>
 <li>Planerad drift (<?=$runHrs?> h): <?=number_format($Ldrift,0)?> L</li>
 <li>Netto tankvolym: <?=number_format($Lnett,0)?> L</li>
 <li>Brutto vid <?=$buffPct?> % fyllnad: <strong><?=number_format($Ltank,0)?> L</strong></li>
</ul>

<h2>Ekonomi & miljö</h2>
<ul>
 <li>Kostnad/år: <?=number_format($costY,0,' ',' ')?> kr</li>
 <li>CO₂/år: <?=number_format($co2Y,0,' ',' ')?> kg</li>
<?php
$kgPerTree = 22; $trees = ceil($co2Y / $kgPerTree);
$gPerKmCar = 0.12; $kmCar = ceil($co2Y / $gPerKmCar);
?>
  <li>≈ <?=$trees?> träd krävs för att binda årets CO₂-utsläpp.</li>
  <li>Motsvarar cirka <?=$kmCar?> km med en genomsnittlig bensinbil.</li>
</ul>

<p class="hint">
  Det motsvarar ungefär<br>
  • <?=$co2Y/1000>1?number_format($co2Y/1000,2):number_format($co2Y,0)?> ton CO₂, lika mycket som
  <?=number_format($co2Y/4600,1)?> årsutsläpp från en EU-genomsnittlig personbil (4,6 t/år).<br>
  • <?=$co2Y/BASE_SC/1000>1?number_format($co2Y/BASE_SC/1000,1):number_format($co2Y/BASE_SC,0)?>
  liter diesel förbrända i personbilar.
</p>
</details>

<h3>Snabb­sammanfattning</h3>
<p>Tank netto <b><?=number_format($Lnett,0)?> L</b> &nbsp;•
   bruttovolym <b><?=number_format($Ltank,0)?> L</b> &nbsp;•
   kostnad/år <b><?=number_format($costY,0)?> kr</b>.</p>

<?php } ?>
</section>

<script src="a2.js"></script>
</body></html>
