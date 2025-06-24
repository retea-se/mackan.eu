<?php
/* ======= KONSTANTER ======= */
const COS_PHI = 0.8;                 // kVA → kW
const BASE_SC = 0.25;                // L/kWh för diesel
$fuelFac = ['DIESEL'=>1.00,'HVO100'=>1.04,'ECOPAR'=>0.93];
$ullage = 0.10;  $bottom = 0.05;     // dött utrymme
$avail  = 1 - $ullage - $bottom;     // 85 % nyttovolym

/* ======= POSTDATA ======== */
$rating     = $_POST['rating']     ?? 100;
$ratingUnit = $_POST['ratingUnit'] ?? 'kVA';
$load       = $_POST['load']       ?? 50;
$loadUnit   = $_POST['loadUnit']   ?? 'kVA';
$days       = $_POST['days']       ?? '';
$fuel       = $_POST['fuel']       ?? 'DIESEL';

/* ======= PROFILDATA ======= */
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
} else {
    // fallback om någon laddar om sidan utan JS
    for ($i = 1; $i <= 3; $i++) {
        $h = $_POST["hours_$i"] ?? '';
        $l = $_POST["load_$i"]  ?? '';
        if (is_numeric($h) && is_numeric($l) && $h > 0 && $l >= 0) {
            $profile[] = ['hours'=>(float)$h, 'loadkW'=>(float)$l];
        }
    }
}

/* ======= BERÄKNA ======== */
$result = null;
if ($rating > 0 && $load >= 0) {
  $ratingKW = $ratingUnit==='kW' ? $rating : $rating * COS_PHI;
  $loadKW   = $loadUnit==='kW'   ? $load   : $load   * COS_PHI;
  $loadPct  = $ratingKW ? $loadKW / $ratingKW * 100 : 0;

  $sc  = BASE_SC * $fuelFac[$fuel]; // L/kWh
  $Lph = $loadKW * $sc;             // L/timme

  $hasDays = is_numeric($days) && $days > 0;
  if ($hasDays) { $Lpd = $Lph * 24; $net = $Lpd * $days; $tank = $net / $avail; }
  else          { $Lpd = $net = $tank = null; }

  $gamma = $sc / BASE_SC;
  $pen   = max(0, $sc - BASE_SC) * $loadKW * 24;
  $class = $gamma<=1.2 ? 'green' : ($gamma<=1.6 ? 'yellow' : 'red');

  $effTxt = $gamma<=1.2 ? "🟢 Normal bränsleförbrukning."
         : ($gamma<=1.6 ? "🟡 Måttlig ineffektivitet (+" . round(($gamma-1)*100) . "%) – ≈ " . round($pen) . " L extra/dygn."
                        : "🔴 Kraftig ineffektivitet (+" . round(($gamma-1)*100) . "%) – > " . round($pen) . " L extra/dygn.");

  $lowWarn = $loadPct < 20
    ? "⚠️ Lasten är under 20 % av märkeffekten – hög specifik förbrukning och risk för sot (wet-stacking)."
    : "";

  $result = compact('ratingKW','loadKW','loadPct','sc','Lph',
                    'hasDays','Lpd','net','tank','class','effTxt','lowWarn');
}
?>
<!doctype html>
<html lang="sv">
<head>
<meta charset="utf-8">
<title>Robust RKA-bränslekalkylator – reservkraft & drivmedel (Diesel | HVO | EcoPar)</title>
<meta name="description"
      content="Robust bränslekalkylator för RKA-reservkraft. Räkna drivmedel: diesel, HVO, EcoPar – liter per timme, dygn och tankvolym. Uppdateras direkt när du skriver.">
<meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="canonical" href="https://mackan.eu/tools/rka/">

<!-- Open Graph -->
<meta property="og:title" content="Robust RKA-bränslekalkylator för reservkraft">
<meta property="og:description" content="Beräkna drivmedelsförbrukning och tankstorlek för diesel, HVO och EcoPar – liveuppdatering.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://mackan.eu/tools/rka/">
<meta property="og:image" content="https://mackan.eu/tools/rka/og-branslekalkylator.jpg">

<!-- FAQ-schema (kort) -->
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"FAQPage",
 "mainEntity":[
  {"@type":"Question","name":"Hur beräknas bränsleförbrukningen?",
   "acceptedAnswer":{"@type":"Answer","text":"Last i kW multipliceras med schablonvärdet 0,25 L/kWh (diesel), 0,26 (HVO) eller 0,23 (EcoPar)."}},
  {"@type":"Question","name":"Hur stor tank behövs för 24 h drift?",
   "acceptedAnswer":{"@type":"Answer","text":"Multiplicera L/h × 24 och lägg till 15 % marginal (10 % ullage + 5 % sump)."}}
 ]}
</script>

<style>
body{font-family:system-ui,Arial,sans-serif;max-width:680px;margin:2rem auto;line-height:1.55}
h1{font-size:1.7rem;margin:0 0 .7rem}
h2{font-size:1.3rem;margin:2rem 0 .6rem}
label{display:block;margin:.9rem 0}
input,select{width:100%;padding:.45rem;margin-top:.25rem}
.row{display:flex;gap:.55rem}
#result{border:3px solid #ddd;border-radius:8px;padding:1rem;margin:1rem 0}
.green{border-color:#35a853;color:#35a853}.yellow{border-color:#fbbc04;color:#fbbc04}.red{border-color:#ea4335;color:#ea4335}
details summary{cursor:pointer;list-style:none;padding-left:1.2rem;position:relative}
details summary::before{content:"▸";position:absolute;left:0;transition:transform .2s}
details[open] summary::before{transform:rotate(90deg)}
table.striped{border-collapse:collapse;width:100%;margin:1.4rem 0}
table.striped th,td{border:1px solid #ccc;padding:.45rem;text-align:center}
table.striped tbody tr:nth-child(even){background:#f6f6f6}
button{display:none}
</style>
</head>
<body>

<h1>Robust RKA-bränslekalkylator för reservkraft och drivmedel</h1>

<form id="advForm" method="post">
  <label>Märkeffekt
    <div class="row">
      <input id="rating" name="rating" type="number" min="1" step="0.1" value="<?=htmlspecialchars($rating)?>">
      <select id="ratingUnit" name="ratingUnit">
        <option value="kVA"<?=$ratingUnit==='kVA'?' selected':''?>>kVA</option>
        <option value="kW" <?=$ratingUnit==='kW'?' selected':''?>>kW</option>
      </select>
    </div>
  </label>

  <label>Aktuell last
    <div class="row">
      <input id="load" name="load" type="number" min="0" step="0.1" value="<?=htmlspecialchars($load)?>">
      <select id="loadUnit" name="loadUnit">
        <option value="kVA"<?=$loadUnit==='kVA'?' selected':''?>>kVA</option>
        <option value="kW" <?=$loadUnit==='kW'?' selected':''?>>kW</option>
      </select>
    </div>
  </label>

  <label>Drifttid (dygn) <small>(valfritt)</small>
    <input id="days" name="days" type="number" min="0" step="0.1" value="<?=htmlspecialchars($days)?>">
  </label>

  <label>Bränsletyp
    <select id="fuel" name="fuel">
      <option value="DIESEL" <?=$fuel==='DIESEL'?'selected':''?>>Diesel</option>
      <option value="HVO100" <?=$fuel==='HVO100'?'selected':''?>>HVO100</option>
      <option value="ECOPAR" <?=$fuel==='ECOPAR'?'selected':''?>>EcoPar</option>
    </select>
  </label>
  <button type="submit">Beräkna</button><!-- döljs i CSS -->
</form>

<?php if($result): ?>
<div id="result" class="<?=$result['class']?>">
  <ul style="margin:0 0 .8rem 0">
    <li><strong><?=number_format($result['Lph'],1)?> L / timme</strong></li>
    <?php if($result['hasDays']): ?>
      <li><?=number_format($result['Lpd'],0)?> L / dygn</li>
      <li>Nettobehov (<?=$days?> dygn): <strong><?=number_format($result['net'],0)?> L</strong></li>
      <li>Tank (85 % fylld): <strong><?=number_format($result['tank'],0)?> L</strong></li>
    <?php endif; ?>
  </ul>
  <p style="margin:.4rem 0"><?=$result['effTxt']?></p>
  <?php if($result['lowWarn']) echo "<p style='margin:.4rem 0'>{$result['lowWarn']}</p>"; ?>

  <details><summary>Visa beräkningsdata</summary>
    <ul>
      <li>Märkeffekt: <?=$rating?> <?=$ratingUnit?> → <?=$result['ratingKW']?> kW</li>
      <li>Last: <?=$load?> <?=$loadUnit?> → <?=$result['loadKW']?> kW (<?=$result['loadPct']?> %)</li>
      <li>Schablon justerad: <?=$result['sc']?> L/kWh</li>
      <li>Tankmarginal: 10 % ullage + 5 % sump</li>
      <li style="text-align:left;font-size:0.97em;margin-top:1em;">
        <strong>Formel och värden:</strong><br>
        <code>
          Liter/timme = Effekt (kW) × schablon (L/kWh) × bränslefaktor<br>
          <br>
          Effekt (kW): <?=number_format($result['loadKW'],2)?> kW<br>
          cos φ: <?=defined('COS_PHI') ? COS_PHI : 'n/a'?><br>
          Schablon (L/kWh): <?=number_format($result['sc'] / $result['loadKW'], 3)?> (faktiskt: <?=number_format($result['sc'],3)?> L/kWh)<br>
          Bränslefaktor: <?=number_format($fuelFac[$fuel],2)?><br>
          <br>
          Uträkning:<br>
          <?=number_format($result['loadKW'],2)?> × <?=number_format($result['sc'],3)?> = <b><?=number_format($result['Lph'],2)?> L/timme</b>
        </code>
      </li>
    </ul>
  </details>
</div>
<?php endif; ?>

<figure style="text-align:center;margin:2rem 0 2.2rem">
  <img src="og-branslekalkylator.jpg"
       alt="Robust RKA-illustration av containeriserat reservkraftaggregat och drivmedelstank"
       style="max-width:100%;height:auto;border-radius:6px">
</figure>

<!-- ======= INFOBLOCK: så fungerar beräkningen – SEO-optimerad ======= -->
<h2 id="sa-fungerar-berakningen">Så fungerar beräkningen</h2>

<p>
  Kalkylatorn räknar bränsleförbrukning för <strong>diesel-,
  HVO- och EcoPar-drivna reservkraftaggregat</strong>
  (RKA) i storlekarna <em>100–300&nbsp;kVA</em>.
  Den bygger på ett svenskt
  <strong>schablonvärde&nbsp;0,25 L&nbsp;/ kWh</strong> för diesel-generatorer
  med last runt 75&nbsp;%<br>
  <small>(källa 1 – produktblad, se nedan)</small>.
  Anger du effekt i <abbr title="kilovoltampere">kVA</abbr>
  räknar verktyget automatiskt om till kilowatt (<abbr title="kilowatt">kW</abbr>)
  med <strong>cos φ = 0,8</strong> (standard för generatorer),
  eftersom bränsleåtgång alltid relaterar till aktiv effekt i kW.
</p>

<p>
  Förnybara eller paraffin­baserade bränslen skiljer sig i
  energi­innehåll och densitet. Därför multipliceras grund­schablonen
  med en enkel <strong>bränsle­faktor</strong>:
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
  (10 % ullage + 5 % sump). Det gör det snabbt att se
  hur stor IBC-tank, container eller dubbel­mantlad cistern
  du behöver för önskad autonomi-tid.
</p>

<h3 id="bransledata">Bränsledata i korthet</h3>
<table class="striped" aria-describedby="sa-fungerar-berakningen">
  <thead>
    <tr>
      <th scope="col">Bränsle</th>
      <th scope="col">Energi&nbsp;MJ / L</th>
      <th scope="col">Faktor</th>
      <th scope="col">CO₂&nbsp;kg / L <span style="font-size:0.8em;">*</span></th>
      <th scope="col">Praktisk effekt</th>
    </tr>
  </thead>
  <tbody>
    <tr><td>Diesel (MK1)</td><td>36 – 41</td><td>1,00</td><td>2,67</td><td>Referens</td></tr>
    <tr><td>HVO100</td>       <td>≈ 34</td><td>1,04</td><td>0,10 – 0,40</td><td>≈ 4 % mer volym</td></tr>
    <tr><td>EcoPar A</td>     <td>≈ 35</td><td>0,93</td><td>2,60</td><td>5–10 % mindre volym</td></tr>
  </tbody>
</table>
<p style="font-size:.9rem;">
  <span style="font-size:0.8em;">*</span>
  Well-to-Wheel, beräknat med svensk elmix och officiella
  bränsle­deklarationer.
</p>

<h3 id="faq">FAQ – vanliga frågor</h3>
<dl>
  <dt>💡 Hur räknar jag liter per timme?</dt>
  <dd>Ta last i kW × schablonvärdet (0,25 L/kWh för diesel). Exempel:
      60 kW × 0,25 = 15 L/h.</dd>

  <dt>💡 Hur stor tank behövs för 24 h drift?</dt>
  <dd>Multiplicera liter per timme med 24 och lägg till 15 %
      (10 % ullage + 5 % sump).</dd>

  <dt>💡 Varför behövs ullage (frigångsvolym)?</dt>
  <dd>Bränsle expanderar med temperatur. 10 % luftspalt gör att tanken
      inte svämmar över vid sommarvärme.</dd>

  <dt>💡 Vad händer under 20 % last?</dt>
  <dd>Verkningsgraden kan sjunka 60–90 % och diesel­motorn riskerar
      <em>wet-stacking</em> – oförbränd diesel i avgassystemet.</dd>
</dl>

<h3 id="kallor">Källor och vidare läsning</h3>
<ol>
  <li><a href="https://coromatic.se/76786_wp-uploads/2017/12/Produktblad-V650C2.pdf" target="_blank" rel="noopener">
      Coromatic V650C2 – produktblad (exempel 200 kVA)</a></li>
  <li><a href="https://www.msb.se/contentassets/ee7389c4f9d5435aa2e7a5a93b146486/verktygslada_for_reservkraftprocessen.pdf"
         target="_blank" rel="noopener">MSB – Verktygslåda för reservkraftprocessen</a></li>
  <li><a href="https://drivkraftsverige.se/wp-content/uploads/2023/11/DS-HVO-faktablad-Final.pdf"
         target="_blank" rel="noopener">Drivkraft Sverige – HVO100 Faktablad</a></li>
  <li><a href="https://www.ecopar.se/en/fuels/ecopar-a/" target="_blank" rel="noopener">
      EcoPar A – tekniskt datablad</a></li>
</ol>

<h3 id="snabblankar">Snabblänkar</h3>

<!-- ======= SLUT INFOBLOCK ======= -->
<script>
const form  = document.getElementById('advForm');
let   timer = null;
const delay = 600;

// Alla fält som ska trigga debounce-autosubmit vid ändring:
[
  'rating', 'cosphi', 'price', 'co2',
  'load', 'days'    // ← LÄGG TILL dessa!
].forEach(id => {
  const el = document.getElementById(id);
  if (el) el.addEventListener('input', () => {
    clearTimeout(timer);
    timer = setTimeout(() => form.requestSubmit(), delay);
  });
});

// Alla select/radio-fält som ska trigga direkt:
[
  'ratingUnit', 'fuel',
  'loadUnit' // ← LÄGG TILL denna!
].forEach(id => {
  const el = document.getElementById(id);
  if (el) el.addEventListener('change', () => form.requestSubmit());
});
</script>


</body>
</html>
