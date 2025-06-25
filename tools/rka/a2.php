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

$title = 'Avancerad kalkylator för dimensionering av tankvolym till reservkraftverk';
$metaDescription = 'Använd denna avancerade kalkylator för att enkelt dimensionera tankvolym till elverk och reservkraftverk. Beräkna snabbt rätt bränslemängd för säker och driftsäker reservkraft. Gratis onlineverktyg med tydliga resultat.';
include '../../includes/layout-start.php';
?>

<main class="layout__container">
  <h1 class="rubrik"><?= $title ?></h1>

  <!-- Länksamling till verktygssidor -->
  <nav class="menykort menykort--center mt-2 mb-2" aria-label="Verktygsnavigering">
    <a class="menykort__lank<?=basename(__FILE__)=='index.php'?' menykort__lank--aktiv':''?>" href="index.php" data-tippy-content="Snabb kalkyl för bränsle och tank">Snabbkalkyl</a>
    <a class="menykort__lank<?=basename(__FILE__)=='avancerad.php'?' menykort__lank--aktiv':''?>" href="avancerad.php" data-tippy-content="Avancerad kalkyl med miljö och ekonomi">Avancerad</a>
    <a class="menykort__lank<?=basename(__FILE__)=='a2.php'?' menykort__lank--aktiv':''?>" href="a2.php" data-tippy-content="Provkörnings-kalkylator">Avancerad 2/a>
    <a class="menykort__lank<?=basename(__FILE__)=='provkorning.php'?' menykort__lank--aktiv':''?>" href="provkorning.php" data-tippy-content="Provkörning & tankprognos">Provkörning & tankprognos</a>
  </nav>
  <!-- /Länksamling -->

  <!-- ********** START Sektion: Formulär ********** -->
  <form id="advForm" class="form" method="post" autocomplete="off">
    <div class="form__grupp">
      <label for="genType">Elverkstyp</label>
      <select id="genType" name="genType">
        <?php foreach(['Container','Öppet','Inbyggt'] as $t): ?>
          <option value="<?=$t?>" <?=$genType===$t?'selected':''?>><?=$t?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form__grupp">
      <label for="phase">Fassystem</label>
      <select id="phase" name="phase">
        <option <?=$phase==='1-fas'?'selected':''?>>1-fas</option>
        <option <?=$phase==='3-fas'?'selected':''?>>3-fas</option>
      </select>
      <small class="form__hint">Påverkar strömuttaget per fas i resultatet.</small>
    </div>
    <div class="form__grupp">
      <label for="swedeTown">Välj svensk ort (SMHI normal)</label>
      <select id="swedeTown" name="swedeTown">
        <?php foreach($smhi as $loc): ?>
          <option value="<?=$loc['name']?>" <?=$loc['name']===$swedeTown?'selected':''?>>
            <?=$loc['name']?> (<?=$loc['temp']?> °C / <?=$loc['alt']?> m)
          </option>
        <?php endforeach; ?>
      </select>
      <small class="form__hint">
        Temperatur och höjd avgör motorns derating. +5 °C över 25 °C ≈ +1 % mer bränsle;
        300 m över 1 000 m ≈ +1 % – justeras automatiskt i kalkylen.
      </small>
    </div>
    <div class="form__grupp">
      <label for="rating">Märkeffekt</label>
      <input id="rating" name="rating" type="number" min="1" step="0.1" value="<?=htmlspecialchars($rating)?>">
      <select id="ratingUnit" name="ratingUnit">
        <option value="kVA"<?=$ratingUnit==='kVA'?' selected':''?>>kVA</option>
        <option value="kW" <?=$ratingUnit==='kW'?' selected':''?>>kW</option>
      </select>
    </div>
    <div class="form__grupp">
      <label for="cosphi">Effektfaktor (cos φ)</label>
      <input id="cosphi" name="cosphi" type="number" min="0.4" max="1" step="0.01" value="<?=htmlspecialchars($cosphi)?>">
    </div>
    <div class="form__grupp">
      <label for="fuel">Drivmedel</label>
      <select id="fuel" name="fuel">
        <?php foreach($fuelFac as $k=>$v): ?>
          <option value="<?=$k?>" <?=$fuel===$k?'selected':''?>><?=ucfirst(strtolower($k))?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form__grupp">
      <label for="co2">CO₂-faktor (kg/L)</label>
      <input id="co2" name="co2" type="number" min="0" step="0.01" value="<?=htmlspecialchars($co2)?>">
    </div>
    <div class="form__grupp">
      <label for="price">Pris (kr/L)</label>
      <input id="price" name="price" type="number" min="0" step="0.1" value="<?=htmlspecialchars($price)?>">
    </div>
    <div class="form__grupp">
      <label for="provMin">Provkörningstid (min)</label>
      <input id="provMin" name="provMin" type="number" min="0" step="1" value="<?=htmlspecialchars($provMin)?>">
      <small class="form__hint">NFPA 110 kräver ≥ 30 min / månad.</small>
    </div>
    <div class="form__grupp">
      <label for="provEveryVal">Provkörnings­intervall</label>
      <input id="provEveryVal" name="provEveryVal" type="number" min="1" step="1" value="<?=htmlspecialchars($provEveryVal)?>">
      <select id="provEveryUnit" name="provEveryUnit">
        <?php foreach(['vecka','månad','kvartal'] as $u): ?>
          <option value="<?=$u?>" <?=$provEveryUnit===$u?'selected':''?>><?=$u?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form__grupp">
      <label for="runHrs">Planerade drifttimmar per år</label>
      <input id="runHrs" name="runHrs" type="number" min="0" step="1" value="<?=htmlspecialchars($runHrs)?>">
    </div>
    <div class="form__grupp">
      <label for="tankInt">Tanknings­intervall (mån)</label>
      <input id="tankInt" name="tankInt" type="number" min="1" step="1" value="<?=htmlspecialchars($tankInt)?>">
    </div>
    <div class="form__grupp">
      <label for="buffDays">Buffert (oavbruten drift)</label>
      <input id="buffDays" name="buffDays" type="number" min="1" step="1" value="<?=htmlspecialchars($buffDays)?>">
      dygn vid
      <input id="buffPct" name="buffPct" type="number" min="10" max="90" step="1" value="<?=htmlspecialchars($buffPct)?>"> %
    </div>

    <!-- Lastprofil -->
    <fieldset class="form__grupp">
      <legend>Lastprofil (enkel)</legend>
      <table id="lpTable" class="tabell">
        <thead>
          <tr>
            <th style="text-align:center">#</th>
            <th style="text-align:center">Tid (h)</th>
            <th style="text-align:center">Last-värde</th>
            <th style="text-align:center">Enhet</th>
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
            <td><button type="button" class="knapp knapp--fara" aria-label="Ta bort rad">🗑️</button></td>
          </tr>
        <?php endfor; ?>
        </tbody>
      </table>
      <div class="form__verktyg">
        <button id="addRow" type="button" class="knapp knapp--sekundär" data-tippy-content="Lägg till rad">+ Lägg till rad</button>
        <button type="submit" class="knapp" data-tippy-content="Beräkna lastprofil">Beräkna lastprofil</button>
      </div>
    </fieldset>
  </form>
  <!-- ********** SLUT Sektion: Formulär ********** -->

  <!-- ********** START Sektion: Resultat ********** -->
  <section id="output">
    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST') { ?>
    <details open>
      <summary><strong>Detaljerad resultat­rapport</strong></summary>

      <section>
        <h2 class="rubrik">Tekniska data</h2>
        <ul>
          <li>
            Märkeffekt nominellt: <?=$rating?> <?=$ratingUnit?>
            <?php if($ratingUnit==='kVA'): ?>
              (× cos φ <?=$cosphi?> ⇒ <?=number_format($ratingKW,1)?> kW)
            <?php else: ?>
              (<?=number_format($rating/$cosphi,1)?> kVA vid cos φ <?=$cosphi?>)
            <?php endif; ?>
          </li>
          <li>Justerad effekt efter derating: <?=number_format($ratingKW*$derate,1)?> kW</li>
          <li>cos φ = <?=$cosphi?> &nbsp;•&nbsp; <?=$phase?></li>
        </ul>
      </section>

      <hr>

      <?php if(count($lpRows) > 0) { ?>
      <section>
        <h2 class="rubrik">Lastprofil – summering</h2>
        <div class="tabell__wrapper">
          <table class="tabell">
            <thead>
              <tr>
                <th style="text-align:center">#</th>
                <th style="text-align:center">Tid&nbsp;(h)</th>
                <th style="text-align:center">Last&nbsp;kW</th>
                <th style="text-align:center">Last&nbsp;kVA</th>
                <th style="text-align:center">Liter</th>
                <th style="text-align:center">Kostnad&nbsp;(kr)</th>
                <th style="text-align:center">CO₂&nbsp;(kg)</th>
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
        </div>
        <div class="info__text" style="text-align:left; margin-top:0.5em;">
          <strong>Total tid:</strong> <?= number_format($totalHrs,1) ?> h<br>
          <strong>Medelbelastning:</strong> <?= number_format($avgKW,1) ?> kW (<?= number_format($avgKW/$cosphi,1) ?> kVA)
        </div>
      </section>
      <?php } ?>

      <hr>

      <section>
        <h2 class="rubrik">Förbrukning & buffert</h2>
        <ul>
          <li>Provkörningar/år: <?=round($testsPerYear,1)?> → <?=number_format($LprovTot,1)?> L</li>
          <li>Buffert <?=$buffDays?> dygn: <?=number_format($Lbuff,0)?> L</li>
          <li>Planerad drift (<?=$runHrs?> h): <?=number_format($Ldrift,0)?> L</li>
          <li>Netto tankvolym: <?=number_format($Lnett,0)?> L</li>
          <li>Brutto vid <?=$buffPct?> % fyllnad: <strong><?=number_format($Ltank,0)?> L</strong></li>
        </ul>
      </section>

      <hr>

      <section>
        <h2 class="rubrik">Ekonomi & miljö</h2>
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
        <div class="info__text" style="text-align:left; margin-top:0.5em;">
          <strong>Det motsvarar ungefär:</strong><br>
          • <?=$co2Y/1000>1?number_format($co2Y/1000,2):number_format($co2Y,0)?> ton CO₂, lika mycket som
          <?=number_format($co2Y/4600,1)?> årsutsläpp från en EU-genomsnittlig personbil (4,6 t/år).<br>
          • <?=$co2Y/BASE_SC/1000>1?number_format($co2Y/BASE_SC/1000,1):number_format($co2Y/BASE_SC,0)?>
          liter diesel förbrända i personbilar.
        </div>
      </section>
    </details>

    <h3 class="rubrik">Snabb­sammanfattning</h3>
    <div class="info__text" style="text-align:left; margin-bottom:1em;">
      <strong>Tank netto:</strong> <?=number_format($Lnett,0)?> L &nbsp;•
      <strong>bruttovolym:</strong> <?=number_format($Ltank,0)?> L &nbsp;•
      <strong>kostnad/år:</strong> <?=number_format($costY,0)?> kr.
    </div>
    <?php } ?>
  </section>
  <!-- ********** SLUT Sektion: Resultat ********** -->

  <!-- ********** START Sektion: Inmatade värden ********** -->
  <section>
    <h2 class="rubrik">Inmatade värden</h2>
    <div class="tabell__wrapper" style="margin-bottom:1em;">
      <table class="tabell">
        <tbody>
          <tr><th style="text-align:left">Elverkstyp</th><td><?=htmlspecialchars($genType)?></td></tr>
          <tr><th style="text-align:left">Fassystem</th><td><?=htmlspecialchars($phase)?></td></tr>
          <tr><th style="text-align:left">Ort</th><td><?=htmlspecialchars($swedeTown)?></td></tr>
          <tr><th style="text-align:left">Märkeffekt</th><td><?=htmlspecialchars($rating)?> <?=htmlspecialchars($ratingUnit)?></td></tr>
          <tr><th style="text-align:left">Effektfaktor (cos φ)</th><td><?=htmlspecialchars($cosphi)?></td></tr>
          <tr><th style="text-align:left">Drivmedel</th><td><?=htmlspecialchars($fuel)?></td></tr>
          <tr><th style="text-align:left">CO₂-faktor (kg/L)</th><td><?=htmlspecialchars($co2)?></td></tr>
          <tr><th style="text-align:left">Pris (kr/L)</th><td><?=htmlspecialchars($price)?></td></tr>
          <tr><th style="text-align:left">Provkörningstid (min)</th><td><?=htmlspecialchars($provMin)?></td></tr>
          <tr><th style="text-align:left">Provkörningsintervall</th><td><?=htmlspecialchars($provEveryVal)?> <?=htmlspecialchars($provEveryUnit)?></td></tr>
          <tr><th style="text-align:left">Planerade drifttimmar/år</th><td><?=htmlspecialchars($runHrs)?></td></tr>
          <tr><th style="text-align:left">Tankningsintervall (mån)</th><td><?=htmlspecialchars($tankInt)?></td></tr>
          <tr><th style="text-align:left">Buffert (dygn)</th><td><?=htmlspecialchars($buffDays)?></td></tr>
          <tr><th style="text-align:left">Buffertnivå (%)</th><td><?=htmlspecialchars($buffPct)?></td></tr>
          <tr>
            <th style="text-align:left;vertical-align:top">Lastprofil</th>
            <td>
              <table class="tabell" style="font-size:0.95em">
                <thead>
                  <tr>
                    <th style="text-align:center">#</th>
                    <th style="text-align:center">Tid (h)</th>
                    <th style="text-align:center">Last-värde</th>
                    <th style="text-align:center">Enhet</th>
                  </tr>
                </thead>
                <tbody>
                  <?php for ($i=0; $i<$rows; $i++): ?>
                    <tr>
                      <td style="text-align:center"><?=($i+1)?></td>
                      <td style="text-align:center"><?=htmlspecialchars($lpTime[$i]??'')?></td>
                      <td style="text-align:center"><?=htmlspecialchars($lpLoad[$i]??'')?></td>
                      <td style="text-align:center"><?=htmlspecialchars($lpUnit[$i]??'%')?></td>
                    </tr>
                  <?php endfor; ?>
                </tbody>
              </table>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>
  <!-- ********** SLUT Sektion: Inmatade värden ********** -->

  <!-- Export-knappar -->
  <div style="margin:2em 0 1em 0; text-align:right;">
    <button type="button" class="knapp" id="exportExcel">Exportera till Excel</button>
    <button type="button" class="knapp" id="exportPDF">Exportera till PDF</button>
  </div>
</main>

<?php include '../../includes/layout-end.php'; ?>
<script src="a2.js" defer></script>
