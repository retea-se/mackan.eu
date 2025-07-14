<?php
// Säker migrering av koordinatverktyget från verktyg till tools
// Steg-för-steg process med säkerhetskopior och tester

echo "<h1>Säker migrering: verktyg/koordinat → tools/koordinat</h1>\n";

$verktygeDir = 'c:\Users\marcu\OneDrive\Dokument\_arbetsmapp mackan_eu\verktyg\koordinat';
$toolsDir = 'c:\Users\marcu\OneDrive\Dokument\_arbetsmapp mackan_eu\tools\koordinat';
$backupDir = 'c:\Users\marcu\OneDrive\Dokument\_arbetsmapp mackan_eu\backup_koordinat_' . date('Ymd_His');

$errors = [];
$success = [];

// Steg 1: Skapa säkerhetskopia av tools-versionen
echo "<h2>Steg 1: Säkerhetskopia</h2>\n";
if (!is_dir($toolsDir)) {
    $errors[] = "Tools-katalogen finns inte: $toolsDir";
} else {
    // Använd robocopy för Windows
    $command = "robocopy \"$toolsDir\" \"$backupDir\" /E /COPY:DAT";
    $output = [];
    $returnVar = 0;
    exec($command . ' 2>&1', $output, $returnVar);
    
    if ($returnVar <= 3) { // Robocopy exit codes 0-3 är OK
        $success[] = "✅ Säkerhetskopia skapad: $backupDir";
    } else {
        $errors[] = "❌ Säkerhetskopia misslyckades: " . implode(' ', $output);
    }
}

if (!empty($errors)) {
    echo "<p style='color: red; font-weight: bold;'>❌ STOPPAR - Fel vid säkerhetskopia</p>\n";
    foreach ($errors as $error) {
        echo "<p>$error</p>\n";
    }
    exit;
}

// Steg 2: Kopiera nyare filer från verktyg till tools
echo "<h2>Steg 2: Kopiera nyare filer</h2>\n";

// Lista med filer som behöver kopieras baserat på vår jämförelse
$filesToCopy = [
    // Unika filer
    '/public/help2.php',
    '/public/impex _map.js',
    
    // Nyare filer från verktyg
    '/.htaccess',
    '/README.md',
    '/api/convert.php',
    '/api/export.php',
    '/api/parse_google.php',
    '/public/export.js',
    '/public/export_advanced.js',
    '/public/geocoding.js',
    '/public/help1.php',
    '/public/impex.js',
    '/public/impex.php',
    '/public/impex_map.js',
    '/public/impex_map.php',
    '/public/impex_map_help.php',
    '/public/impex_plot.js',
    '/public/map.js',
    '/public/script.js',
    '/src/CoordinateConverter.php',
    '/src/config.php',
    '/src/constants.php'
];

foreach ($filesToCopy as $file) {
    $sourceFile = $verktygeDir . str_replace('/', '\\', $file);
    $destFile = $toolsDir . str_replace('/', '\\', $file);
    
    if (file_exists($sourceFile)) {
        // Skapa destination directory om det inte finns
        $destDir = dirname($destFile);
        if (!is_dir($destDir)) {
            if (!mkdir($destDir, 0755, true)) {
                $errors[] = "❌ Kunde inte skapa katalog: $destDir";
                continue;
            }
        }
        
        if (copy($sourceFile, $destFile)) {
            $success[] = "✅ Kopierade: $file";
        } else {
            $errors[] = "❌ Misslyckades kopiera: $file";
        }
    } else {
        $errors[] = "❌ Källfil finns inte: $sourceFile";
    }
}

echo "<h2>Resultat av kopiering</h2>\n";
foreach ($success as $msg) {
    echo "<p>$msg</p>\n";
}
foreach ($errors as $msg) {
    echo "<p style='color: red;'>$msg</p>\n";
}

echo "<h2>Nästa steg</h2>\n";
echo "<p>1. Kontrollera att filerna kopierades korrekt</p>\n";
echo "<p>2. Uppdatera filerna för att använda den nya mallstrukturen</p>\n";
echo "<p>3. Testa funktionaliteten</p>\n";
echo "<p>4. Ta bort verktyg-versionen när allt fungerar</p>\n";

if (count($errors) === 0) {
    echo "<p style='color: green; font-weight: bold;'>✅ KOPIERING LYCKADES - Fortsätt med mallkonvertering</p>\n";
} else {
    echo "<p style='color: red; font-weight: bold;'>❌ KOPIERING MISSLYCKADES - Kontrollera fel ovan</p>\n";
}

echo "<hr>\n";
echo "<p><em>Migrering kördes: " . date('Y-m-d H:i:s') . "</em></p>\n";
echo "<p><em>Säkerhetskopia: $backupDir</em></p>\n";
?>
