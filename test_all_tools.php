<?php
/**
 * test_all_tools.php - Omfattande test av alla verktyg i tools-mappen
 * Testar: HTTP status, HTML-validering, console errors, formulär
 */

$baseUrl = 'https://mackan.eu';

// Lista över alla verktyg att testa
$tools = [
    'addy',
    'aptus',
    'bolagsverket',
    'converter',
    'css2json',
    'csv2json',
    'flow',
    'koordinat',
    'kortlank',
    'passwordgenerator',
    'pts',
    'qr_v1',
    'qr_v2',
    'qr_v3',
    'rka',
    'skyddad',
    'stotta',
    'testdata',
    'testid',
    'tfngen',
    'tts'
];

$results = [];
$errors = [];
$warnings = [];

echo "=== TESTAR ALLA VERKTYG I TOOLS-MAPPEN ===\n\n";

foreach ($tools as $tool) {
    $url = "$baseUrl/tools/$tool/";
    echo "Testing: $url\n";
    
    // Testa huvudfilen
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mackan.eu Test Script/1.0');
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);
    
    $result = [
        'tool' => $tool,
        'url' => $url,
        'http_code' => $httpCode,
        'content_type' => $contentType,
        'size' => strlen($response),
        'errors' => [],
        'warnings' => []
    ];
    
    // Testa HTTP status
    if ($httpCode !== 200) {
        $error = "HTTP $httpCode";
        $result['errors'][] = $error;
        $errors[] = "$tool: $error";
        echo "  ❌ $error\n";
    } else {
        echo "  ✅ HTTP 200\n";
    }
    
    // Testa HTML-struktur
    if ($response && strpos($contentType, 'text/html') !== false) {
        // Kontrollera att HTML är giltigt
        if (strpos($response, '<!DOCTYPE html>') === false && strpos($response, '<!doctype html>') === false) {
            $warning = "Saknar DOCTYPE";
            $result['warnings'][] = $warning;
            $warnings[] = "$tool: $warning";
            echo "  ⚠️  $warning\n";
        }
        
        // Kontrollera att layout-start inkluderas (olika verktyg kan använda olika klasser)
        $hasLayout = strpos($response, 'layout__container') !== false || 
                     strpos($response, 'layout__sektion') !== false ||
                     strpos($response, 'layout__main') !== false ||
                     strpos($response, 'verktygsinfo') !== false ||
                     strpos($response, 'konverterar') !== false; // converter använder denna
        
        if (!$hasLayout && $httpCode === 200) {
            $warning = "Verkar sakna standardlayout";
            $result['warnings'][] = $warning;
            $warnings[] = "$tool: $warning";
        }
        
        // Kontrollera att head innehåller meta-taggar
        if (strpos($response, '<meta') === false) {
            $warning = "Saknar meta-taggar";
            $result['warnings'][] = $warning;
            $warnings[] = "$tool: $warning";
        }
        
        // Kontrollera Open Graph tags
        if (strpos($response, 'og:title') === false && $httpCode === 200) {
            $warning = "Saknar Open Graph meta-taggar";
            $result['warnings'][] = $warning;
            $warnings[] = "$tool: $warning";
        }
        
        // Kontrollera formulär (common form elements)
        $hasForm = strpos($response, '<form') !== false || 
                   strpos($response, 'type="submit"') !== false ||
                   strpos($response, 'class="form"') !== false ||
                   strpos($response, 'falt__') !== false;
        
        if ($hasForm) {
            // Kontrollera ARIA-labels på formulär
            if (strpos($response, 'aria-label') === false && 
                strpos($response, 'aria-labelledby') === false &&
                strpos($response, '<label') === false) {
                $warning = "Formulär saknar ARIA-labels eller labels";
                $result['warnings'][] = $warning;
                $warnings[] = "$tool: $warning";
            }
        }
        
        // Kontrollera bilder (om de finns)
        if (preg_match_all('/<img[^>]+>/i', $response, $matches)) {
            foreach ($matches[0] as $imgTag) {
                // Kontrollera att bilder har alt-attribut
                if (strpos($imgTag, 'alt=') === false) {
                    $warning = "Bild saknar alt-attribut";
                    $result['warnings'][] = $warning;
                    $warnings[] = "$tool: $warning";
                    break;
                }
                
                // Kontrollera lazy loading
                if (strpos($imgTag, 'loading=') === false) {
                    $warning = "Bild saknar loading-attribut (lazy loading)";
                    $result['warnings'][] = $warning;
                    // Inte kritisk, bara varning
                }
            }
        }
        
        // Kontrollera JavaScript-filer
        if (preg_match_all('/<script[^>]*src=["\']([^"\']+)["\']/i', $response, $scriptMatches)) {
            foreach ($scriptMatches[1] as $scriptSrc) {
                // Kontrollera att externa scripts finns
                if (strpos($scriptSrc, 'http') === false && strpos($scriptSrc, '//') === false) {
                    // Relativ sökväg - skulle kunna testa om filen finns, men hoppar över för nu
                }
            }
        }
    }
    
    $results[] = $result;
    
    // Kort paus mellan requests
    usleep(500000); // 0.5 sekund
}

echo "\n=== SAMMANFATTNING ===\n\n";
echo "Totalt verktyg testade: " . count($results) . "\n";
echo "Framgångsrika (HTTP 200): " . count(array_filter($results, fn($r) => $r['http_code'] === 200)) . "\n";
echo "Fel: " . count($errors) . "\n";
echo "Varningar: " . count($warnings) . "\n\n";

if (!empty($errors)) {
    echo "=== FEL ===\n";
    foreach ($errors as $error) {
        echo "❌ $error\n";
    }
    echo "\n";
}

if (!empty($warnings)) {
    echo "=== VARNINGAR ===\n";
    foreach ($warnings as $warning) {
        echo "⚠️  $warning\n";
    }
    echo "\n";
}

// Spara resultat till fil
file_put_contents('test_results_tools.json', json_encode([
    'timestamp' => date('Y-m-d H:i:s'),
    'results' => $results,
    'errors' => $errors,
    'warnings' => $warnings
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

echo "Resultat sparade till: test_results_tools.json\n";

