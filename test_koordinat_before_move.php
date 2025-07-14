<?php
// Test script för koordinatverktyget INNAN flytt från verktyg till tools
// Detta script testar alla viktiga funktioner för att säkerställa att de fungerar

echo "<h1>Test av Koordinatverktyget - INNAN flytt</h1>\n";
echo "<p>Testar alla viktiga funktioner för att säkerställa funktionalitet innan flytt från verktyg/ till tools/</p>\n";

$errors = [];
$warnings = [];
$success = [];

// Definiera vilka filer som ska testas
$basePathVerktyg = 'c:\Users\marcu\OneDrive\Dokument\_arbetsmapp mackan_eu\verktyg\koordinat';
$basePathTools = 'c:\Users\marcu\OneDrive\Dokument\_arbetsmapp mackan_eu\tools\koordinat';

// Test 1: Kontrollera att alla viktiga filer finns
echo "<h2>Test 1: Filstruktur</h2>\n";
$requiredFiles = [
    '/public/index.php',
    '/public/script.js',
    '/public/impex.php',
    '/public/impex_map.php',
    '/public/help1.php',
    '/api/convert.php',
    '/config/config.php'
];

foreach ($requiredFiles as $file) {
    $verktygeFile = $basePathVerktyg . $file;
    $toolsFile = $basePathTools . $file;
    
    if (file_exists($verktygeFile)) {
        $success[] = "✅ Verktyg: $file finns";
    } else {
        $errors[] = "❌ Verktyg: $file saknas";
    }
    
    if (file_exists($toolsFile)) {
        $warnings[] = "⚠️ Tools: $file finns redan";
    }
}

// Test 2: Testa att PHP-filerna kan parsas
echo "<h2>Test 2: PHP Syntax</h2>\n";
$phpFiles = [
    $basePathVerktyg . '/public/index.php',
    $basePathVerktyg . '/public/impex.php',
    $basePathVerktyg . '/public/impex_map.php',
    $basePathVerktyg . '/public/help1.php'
];

foreach ($phpFiles as $phpFile) {
    if (file_exists($phpFile)) {
        $output = [];
        $returnVar = 0;
        exec("php -l \"$phpFile\" 2>&1", $output, $returnVar);
        
        if ($returnVar === 0) {
            $success[] = "✅ PHP Syntax OK: " . basename($phpFile);
        } else {
            $errors[] = "❌ PHP Syntax Error: " . basename($phpFile) . " - " . implode(' ', $output);
        }
    }
}

// Test 3: Kontrollera JavaScript-filer
echo "<h2>Test 3: JavaScript-filer</h2>\n";
$jsFiles = [
    $basePathVerktyg . '/public/script.js',
    $basePathVerktyg . '/public/impex.js',
    $basePathVerktyg . '/public/map.js'
];

foreach ($jsFiles as $jsFile) {
    if (file_exists($jsFile)) {
        $content = file_get_contents($jsFile);
        
        // Enkel syntax-kontroll
        if (substr_count($content, '{') === substr_count($content, '}')) {
            $success[] = "✅ JavaScript struktur OK: " . basename($jsFile);
        } else {
            $warnings[] = "⚠️ Potentiell JavaScript syntax issue: " . basename($jsFile);
        }
        
        // Kontrollera att viktiga funktioner finns
        if (basename($jsFile) === 'script.js') {
            if (strpos($content, 'initMap') !== false) {
                $success[] = "✅ initMap funktion hittad i script.js";
            } else {
                $warnings[] = "⚠️ initMap funktion inte hittad i script.js";
            }
        }
    } else {
        $warnings[] = "⚠️ JavaScript fil saknas: " . basename($jsFile);
    }
}

// Test 4: Kontrollera CSS-länkar och externa resurser
echo "<h2>Test 4: Externa resurser och CSS</h2>\n";
$indexContent = '';
if (file_exists($basePathVerktyg . '/public/index.php')) {
    $indexContent = file_get_contents($basePathVerktyg . '/public/index.php');
    
    // Kontrollera Leaflet
    if (strpos($indexContent, 'leaflet') !== false) {
        $success[] = "✅ Leaflet CSS/JS referenser hittade";
    } else {
        $warnings[] = "⚠️ Leaflet referenser inte hittade";
    }
    
    // Kontrollera Proj4
    if (strpos($indexContent, 'proj4') !== false) {
        $success[] = "✅ Proj4 referenser hittade";
    } else {
        $warnings[] = "⚠️ Proj4 referenser inte hittade";
    }
    
    // Kontrollera CSS-struktur
    if (strpos($indexContent, '/verktyg/assets/css/') !== false) {
        $warnings[] = "⚠️ Använder gamla verktyg CSS-struktur (behöver uppdateras)";
    }
    
    if (strpos($indexContent, '/css/main.css') !== false) {
        $success[] = "✅ Använder ny CSS-struktur";
    }
}

// Test 5: Kontrollera API-funktionalitet
echo "<h2>Test 5: API-struktur</h2>\n";
if (file_exists($basePathVerktyg . '/api/convert.php')) {
    $apiContent = file_get_contents($basePathVerktyg . '/api/convert.php');
    
    if (strpos($apiContent, 'function') !== false || strpos($apiContent, 'class') !== false) {
        $success[] = "✅ API innehåller funktioner/klasser";
    } else {
        $warnings[] = "⚠️ API-struktur oklart";
    }
}

// Test 6: Kontrollera konfiguration
echo "<h2>Test 6: Konfiguration</h2>\n";
if (file_exists($basePathVerktyg . '/config/config.php')) {
    $success[] = "✅ Config-fil finns";
    
    $configContent = file_get_contents($basePathVerktyg . '/config/config.php');
    if (strpos($configContent, 'verktyg') !== false) {
        $warnings[] = "⚠️ Config innehåller 'verktyg' referenser (behöver uppdateras)";
    }
} else {
    $warnings[] = "⚠️ Config-fil saknas";
}

// Visa resultat
echo "<h2>Testresultat</h2>\n";

if (!empty($success)) {
    echo "<h3 style='color: green;'>Framgångar (" . count($success) . "):</h3>\n";
    foreach ($success as $msg) {
        echo "<p>$msg</p>\n";
    }
}

if (!empty($warnings)) {
    echo "<h3 style='color: orange;'>Varningar (" . count($warnings) . "):</h3>\n";
    foreach ($warnings as $msg) {
        echo "<p>$msg</p>\n";
    }
}

if (!empty($errors)) {
    echo "<h3 style='color: red;'>Fel (" . count($errors) . "):</h3>\n";
    foreach ($errors as $msg) {
        echo "<p>$msg</p>\n";
    }
}

echo "<h2>Sammanfattning</h2>\n";
echo "<p><strong>Totalt:</strong> " . count($success) . " framgångar, " . count($warnings) . " varningar, " . count($errors) . " fel</p>\n";

if (count($errors) === 0) {
    echo "<p style='color: green; font-weight: bold;'>✅ KLART FÖR FLYTT - Inga kritiska fel funna!</p>\n";
} else {
    echo "<p style='color: red; font-weight: bold;'>❌ VÄNTA MED FLYTT - Kritiska fel måste åtgärdas först!</p>\n";
}

echo "<hr>\n";
echo "<p><em>Test kördes: " . date('Y-m-d H:i:s') . "</em></p>\n";
?>
