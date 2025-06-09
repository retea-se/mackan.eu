<?php
header('Content-Type: application/json');
require_once 'db.php';

// Hjälpfunktion för att hämta en slumpad rad från en tabell
function randomRow($pdo, $table, $where = '') {
  $query = "SELECT * FROM `$table`";
  if ($where) {
    $query .= " WHERE $where";
  }
  $query .= " ORDER BY RAND() LIMIT 1";
  $stmt = $pdo->query($query);
  return $stmt->fetch();
}

// Slumpa förnamn + kön
$fornamn = randomRow($pdo, 'fornamn');
error_log("Förnamn: {$fornamn['namn']} ({$fornamn['kon']})");

// Slumpa efternamn
$efternamn = randomRow($pdo, 'efternamn');
error_log("Efternamn: {$efternamn['namn']}");

// Slumpa 2 företagsdelar och skapa namn
$stmt = $pdo->query("SELECT ord FROM foretagsdelar ORDER BY RAND() LIMIT 2");
$delar = $stmt->fetchAll(PDO::FETCH_COLUMN);
$foretag = ucfirst(implode('', $delar));
error_log("Företagsnamn: $foretag");

// Lista domäner att slumpa mellan
$domaner = ['exempel.se', 'dummy.nu', 'fejkbolag.com'];
$doman = $domaner[array_rand($domaner)];
error_log("Domän: $doman");

// Gör företagsnamnet domänvänligt: gemener, inga specialtecken
$foretagDoman = strtolower(preg_replace('/[^a-z0-9]/i', '', $foretag));

// Slumpa e-postformat
$epostFormat = randomRow($pdo, 'epostformat')['formatstrang'] ?? '{fnamn}@{doman}';
error_log("E-postformat: $epostFormat");

// Bygg e-post med ersättning av variabler
$epost = str_replace(
  ['{fnamn}', '{enamn}', '{doman}', '{foretag}'],
  [strtolower($fornamn['namn']), strtolower($efternamn['namn']), $doman, $foretagDoman],
  $epostFormat
);
error_log("E-post: $epost");

// Slumpa fasttelefon
$serieFast = randomRow($pdo, 'telefonserier', "kategori = 'fast'");
$nrFast = rand($serieFast['startnummer'], $serieFast['slutnummer']);
$telefon = "{$serieFast['riktnummer']}-$nrFast";
error_log("Telefon: $telefon");

// Slumpa mobiltelefon
$serieMobil = randomRow($pdo, 'telefonserier', "kategori = 'mobil'");
$nrMobil = rand($serieMobil['startnummer'], $serieMobil['slutnummer']);
$mobiltelefon = "{$serieMobil['riktnummer']}-$nrMobil";
error_log("Mobiltelefon: $mobiltelefon");

echo json_encode([
  'fornamn' => $fornamn['namn'],
  'efternamn' => $efternamn['namn'],
  'kon' => $fornamn['kon'],
  'foretag' => $foretag,
  'telefon' => $telefon,
  'mobiltelefon' => $mobiltelefon,
  'epost' => $epost
]);
?>
