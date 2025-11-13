<?php
// Ladda valideringsfunktioner
require_once __DIR__ . '/../../includes/tools-validator.php';

const COS_PHI = 0.8;
const BASE_SC = 0.25;
$fuelFac = ['DIESEL'=>1.00,'HVO100'=>1.04,'ECOPAR'=>0.93];
$ullage = 0.10;  $bottom = 0.05;
$avail  = 1 - $ullage - $bottom;

// Validera POST-data
$rating     = validateNumeric($_POST['rating'] ?? null, ['min' => 1, 'max' => 10000, 'default' => 100]);
$ratingUnit = validateEnum($_POST['ratingUnit'] ?? null, ['kVA', 'kW'], 'kVA');
$load       = validateNumeric($_POST['load'] ?? null, ['min' => 0, 'max' => 10000, 'default' => 50]);
$loadUnit   = validateEnum($_POST['loadUnit'] ?? null, ['kVA', 'kW'], 'kVA');
$days       = validateNumeric($_POST['days'] ?? null, ['min' => 0, 'max' => 365, 'default' => 0]);
$fuel       = validateEnum($_POST['fuel'] ?? null, ['DIESEL', 'HVO100', 'ECOPAR'], 'DIESEL');

$profile = [];
if (!empty($_POST['profileData'])) {
    $json = validateJson($_POST['profileData'], []);
    if (is_array($json) && !empty($json)) {
        foreach ($json as $seg) {
            if (isset($seg['hours']) && isset($seg['load'])) {
                $hours = validateNumeric($seg['hours'], ['min' => 0, 'max' => 8760, 'default' => 0]);
                $load = validateNumeric($seg['load'], ['min' => 0, 'max' => 10000, 'default' => 0]);
                if ($hours > 0 && $load >= 0) {
                    $profile[] = ['hours' => (float)$hours, 'loadkW' => (float)$load];
                }
            }
        }
    }
} else {
    for ($i = 1; $i <= 3; $i++) {
        $h = validateNumeric($_POST["hours_$i"] ?? null, ['min' => 0, 'max' => 8760, 'default' => 0]);
        $l = validateNumeric($_POST["load_$i"] ?? null, ['min' => 0, 'max' => 10000, 'default' => 0]);
        if ($h > 0 && $l >= 0) {
            $profile[] = ['hours' => (float)$h, 'loadkW' => (float)$l];
        }
    }
}

$result = null;
if ($rating > 0 && $load >= 0) {
  $ratingKW = $ratingUnit==='kW' ? $rating : $rating * COS_PHI;
  $loadKW   = $loadUnit==='kW'   ? $load   : $load   * COS_PHI;
  $loadPct  = $ratingKW ? $loadKW / $ratingKW * 100 : 0;

  $sc  = BASE_SC * $fuelFac[$fuel];
  $Lph = $loadKW * $sc;

  $hasDays = $days > 0;
  if ($hasDays) { $Lpd = $Lph * 24; $net = $Lpd * $days; $tank = $net / $avail; }
  else          { $Lpd = $net = $tank = null; }

  $gamma = $sc / BASE_SC;
  $pen   = max(0, $sc - BASE_SC) * $loadKW * 24;
  $class = $gamma<=1.2 ? 'kort--gron' : ($gamma<=1.6 ? 'kort--gul' : 'kort--rod');

  $effTxt = $gamma<=1.2 ? "🟢 Normal bränsleförbrukning."
         : ($gamma<=1.6 ? "🟡 Måttlig ineffektivitet (+" . round(($gamma-1)*100) . "%) – ≈ " . round($pen) . " L extra/dygn."
                        : "🔴 Kraftig ineffektivitet (+" . round(($gamma-1)*100) . "%) – > " . round($pen) . " L extra/dygn.");

  $lowWarn = $loadPct < 20
    ? "⚠️ Lasten är under 20 % av märkeffekten – hög specifik förbrukning och risk för sot (wet-stacking)."
    : "";

  $result = compact('ratingKW','loadKW','loadPct','sc','Lph',
                    'hasDays','Lpd','net','tank','class','effTxt','lowWarn');
}

$title = 'RKA-bränslekalkylator för reservkraftverk';
$metaDescription = 'Beräkna bränsleförbrukning och tankvolym för reservkraft (diesel, HVO, EcoPar) – snabbt och responsivt. Professionellt verktyg för elkonsulter.';
$keywords = 'RKA kalkylator, reservkraftverk, bränsleförbrukning, tankvolym, diesel, HVO100, EcoPar, generator, elkraft, reservkraftaggregat';
$canonical = 'https://mackan.eu/tools/rka/';

// Strukturerad data för sökmotorer
$extraHead = '
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebApplication",
  "name": "RKA-bränslekalkylator för reservkraftverk",
  "description": "' . htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8') . '",
  "url": "' . htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8') . '",
  "applicationCategory": "UtilityApplication",
  "operatingSystem": "Web Browser",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "SEK"
  },
  "featureList": [
    "Bränsleförbrukning",
    "Tankvolym",
    "Diesel, HVO, EcoPar",
    "Professionell kalkylator"
  ],
  "author": {
    "@type": "Organization",
    "name": "Mackan.eu"
  }
}
</script>';

include '../../includes/layout-start.php';
?>

<main class="layout__container">
  <!-- Breadcrumbs -->
  <nav class="breadcrumbs" aria-label="Du är här" style="margin-bottom: 1rem; font-size: 0.9rem; color: #6c757d;">
    <a href="/" style="color: #007bff; text-decoration: none;">🏠 Hem</a> ›
    <a href="/tools/" style="color: #007bff; text-decoration: none;">🔧 Verktyg</a> ›
    <span>⚡ RKA-kalkylatorer</span>
  </nav>

  <h1 class="rubrik"><?= $title ?? 'RKA-kalkylator' ?></h1>

  <!-- Länksamling till verktygssidor -->
  <nav class="menykort menykort--center mt-2 mb-2" aria-label="Verktygsnavigering">
    <a class="menykort__lank<?=basename(__FILE__)=='index.php'?' menykort__lank--aktiv':''?>" href="index.php" data-tippy-content="Snabb kalkyl för bränsle och tank">Snabbkalkyl</a>
    <a class="menykort__lank<?=basename(__FILE__)=='avancerad.php'?' menykort__lank--aktiv':''?>" href="avancerad.php" data-tippy-content="Avancerad kalkyl med miljö och ekonomi">Avancerad</a>
    <a class="menykort__lank<?=basename(__FILE__)=='a2.php'?' menykort__lank--aktiv':''?>" href="a2.php" data-tippy-content="Provkörnings-kalkylator">Avancerad 2</a>
    <a class="menykort__lank<?=basename(__FILE__)=='provkorning.php'?' menykort__lank--aktiv':''?>" href="provkorning.php" data-tippy-content="Provkörning & tankprognos">Provkörning & tankprognos</a>
  </nav>
  <!-- /Länksamling -->

  <!-- ********** START Sektion: Formulär ********** -->
  <form id="advForm" method="post" class="form" autocomplete="off">
    <div class="form__grupp">
      <label class="falt__etikett" for="rating">Märkeffekt</label>
      <div class="form__rad">
        <input id="rating" name="rating" type="number" min="1" step="0.1" class="falt__input" value="<?=htmlspecialchars($rating)?>" aria-label="Märkeffekt">
        <select id="ratingUnit" name="ratingUnit" class="falt__select" aria-label="Effektenhet">
          <option value="kVA"<?=$ratingUnit==='kVA'?' selected':''?>>kVA</option>
          <option value="kW" <?=$ratingUnit==='kW'?' selected':''?>>kW</option>
        </select>
      </div>
    </div>
    <div class="form__grupp">
      <label class="falt__etikett" for="load">Aktuell last</label>
      <div class="form__rad">
        <input id="load" name="load" type="number" min="0" step="0.1" class="falt__input" value="<?=htmlspecialchars($load)?>" aria-label="Aktuell last">
        <select id="loadUnit" name="loadUnit" class="falt__select" aria-label="Lastenhet">
          <option value="kVA"<?=$loadUnit==='kVA'?' selected':''?>>kVA</option>
          <option value="kW" <?=$loadUnit==='kW'?' selected':''?>>kW</option>
        </select>
      </div>
    </div>
    <div class="form__grupp">
      <label class="falt__etikett" for="days">Drifttid (dygn) <small>(valfritt)</small></label>
      <input id="days" name="days" type="number" min="0" step="0.1" class="falt__input" value="<?=htmlspecialchars($days)?>" aria-label="Drifttid i dygn">
    </div>
    <div class="form__grupp">
      <label class="falt__etikett" for="fuel">Bränsletyp</label>
      <select id="fuel" name="fuel" class="falt__select" aria-label="Bränsletyp">
        <option value="DIESEL" <?=$fuel==='DIESEL'?'selected':''?>>Diesel</option>
        <option value="HVO100" <?=$fuel==='HVO100'?'selected':''?>>HVO100</option>
        <option value="ECOPAR" <?=$fuel==='ECOPAR'?'selected':''?>>EcoPar</option>
      </select>
    </div>
    <div class="form__verktyg">
      <button type="submit" class="knapp" data-tippy-content="Beräkna och visa resultat">Beräkna</button>
    </div>
  </form>
  <!-- ********** SLUT Sektion: Formulär ********** -->

  <!-- ********** START Sektion: Resultat ********** -->
  <?php if($result): ?>
  <section class="kort mt-1 <?= $result['class'] ?>">
    <h2 class="kort__rubrik">Resultat</h2>
    <div class="kort__innehall">
      <ul>
        <li><strong><?=number_format($result['Lph'],1)?> L / timme</strong></li>
        <?php if($result['hasDays']): ?>
          <li><?=number_format($result['Lpd'],0)?> L / dygn</li>
          <li>Nettobehov (<?=$days?> dygn): <strong><?=number_format($result['net'],0)?> L</strong></li>
          <li>Tank (85 % fylld): <strong><?=number_format($result['tank'],0)?> L</strong></li>
        <?php endif; ?>
      </ul>
      <p><?=$result['effTxt']?></p>
      <?php if($result['lowWarn']) echo "<p>{$result['lowWarn']}</p>"; ?>
      <details><summary>Visa beräkningsdata</summary>
        <ul>
          <li>Märkeffekt: <?=$rating?> <?=$ratingUnit?> → <?=$result['ratingKW']?> kW</li>
          <li>Last: <?=$load?> <?=$loadUnit?> → <?=$result['loadKW']?> kW (<?=round($result['loadPct'])?> %)</li>
          <li>Schablon justerad: <?=$result['sc']?> L/kWh</li>
          <li>Tankmarginal: 10 % ullage + 5 % sump</li>
        </ul>
      </details>
    </div>
  </section>
  <?php endif; ?>
  <!-- ********** SLUT Sektion: Resultat ********** -->

  <section class="kort mt-1">
    <h2 class="kort__rubrik">Så fungerar beräkningen</h2>
    <div class="kort__innehall">
      <p>
        Kalkylatorn räknar bränsleförbrukning för <strong>diesel-, HVO- och EcoPar-drivna reservkraftaggregat</strong>
        (RKA) i storlekarna <em>100–300&nbsp;kVA</em>. Den bygger på ett svenskt
        <strong>schablonvärde&nbsp;0,25 L&nbsp;/ kWh</strong> för diesel-generatorer med last runt 75&nbsp;%.<br>
        <small>(källa 1 – produktblad, se nedan)</small>
      </p>
      <p>
        Anger du effekt i <abbr title="kilovoltampere">kVA</abbr>
        räknar verktyget automatiskt om till kilowatt (<abbr title="kilowatt">kW</abbr>)
        med <strong>cos φ = 0,8</strong> (standard för generatorer),
        eftersom bränsleåtgång alltid relaterar till aktiv effekt i kW.
      </p>
      <p>
        Förnybara eller paraffin­baserade bränslen skiljer sig i energi­innehåll och densitet.
        Därför multipliceras grund­schablonen med en enkel <strong>bränsle­faktor</strong>:
      </p>
      <ul>
        <li>Diesel (MK1) → × 1,00 <small>(referens)</small></li>
        <li>HVO100        → × 1,04 <small>(≈ 4 % <em>mer</em> volym / kWh)</small></li>
        <li>EcoPar A      → × 0,93 <small>(≈ 7 % <em>mindre</em> volym / kWh)</small></li>
      </ul>
      <p>
        Resultatet visas i <strong>liter per timme (L/h)</strong>,
        <strong>liter per dygn (L/24&nbsp;h)</strong> och — om du anger antal
        dygn — även <strong>tank­volym</strong> inkl.&nbsp;15 % marginal
        (10 % ullage + 5 % sump).
      </p>
    </div>
  </section>

  <section class="kort mt-1">
    <h2 class="kort__rubrik">Bränsledata i korthet</h2>
    <div class="tabell__wrapper">
      <table class="tabell" aria-describedby="sa-fungerar-berakningen">
        <thead>
          <tr>
            <th>Bränsle</th>
            <th>Energi MJ / L</th>
            <th>Faktor</th>
            <th>CO₂ kg / L</th>
            <th>Praktisk effekt</th>
          </tr>
        </thead>
        <tbody>
          <tr><td>Diesel (MK1)</td><td>36 – 41</td><td>1,00</td><td>2,67</td><td>Referens</td></tr>
          <tr><td>HVO100</td>       <td>≈ 34</td><td>1,04</td><td>0,10 – 0,40</td><td>≈ 4 % mer volym</td></tr>
          <tr><td>EcoPar A</td>     <td>≈ 35</td><td>0,93</td><td>2,60</td><td>5–10 % mindre volym</td></tr>
        </tbody>
      </table>
    </div>
    <p class="kort__innehall" style="font-size:.9rem;">
      <span style="font-size:0.8em;">*</span>
      Well-to-Wheel, beräknat med svensk elmix och officiella bränsle­deklarationer.
    </p>
  </section>
</main>

<?php include '../../includes/layout-end.php'; ?>
