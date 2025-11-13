<?php
/**
 * test_browser_console.php - Testa console errors via curl och validering
 * Detta är en enklare version som kollar strukturella problem
 */

$baseUrl = 'https://mackan.eu';
$tools = [
    'addy', 'aptus', 'bolagsverket', 'converter', 'css2json', 'csv2json',
    'flow', 'koordinat', 'kortlank', 'passwordgenerator', 'pts',
    'qr_v1', 'qr_v2', 'qr_v3', 'rka', 'skyddad', 'stotta',
    'testdata', 'testid', 'tfngen', 'tts'
];

$errors = [];
$warnings = [];

echo "=== TESTAR CONSOLE ERRORS OCH STRUKTUR ===\n\n";

foreach ($tools as $tool) {
    $url = "$baseUrl/tools/$tool/";
    echo "Testing: $tool\n";
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mackan.eu Test Script/1.0');
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode !== 200) {
        $errors[] = "$tool: HTTP $httpCode";
        echo "  ❌ HTTP $httpCode\n";
        continue;
    }
    
    // Kontrollera JavaScript-fel (vanliga problem)
    $jsErrors = [];
    
    // Kontrollera undefined variabler
    if (preg_match('/undefined\s+/i', $response) || preg_match('/is not defined/i', $response)) {
        $jsErrors[] = "Potentiellt undefined variabel";
    }
    
    // Kontrollera saknade script-filer
    if (preg_match_all('/<script[^>]*src=["\']([^"\']+)["\']/i', $response, $matches)) {
        foreach ($matches[1] as $scriptSrc) {
            if (strpos($scriptSrc, 'http') === false && strpos($scriptSrc, '//') === false) {
                // Relativ sökväg - kontrollera om filen finns lokalt
                $localPath = str_replace('/tools/', 'tools/', $scriptSrc);
                $localPath = ltrim($localPath, '/');
                if (!file_exists($localPath) && !file_exists("tools/$tool/$localPath")) {
                    $jsErrors[] = "Script saknas: $scriptSrc";
                }
            }
        }
    }
    
    // Kontrollera HTML-struktur
    if (strpos($response, '</html>') === false && strpos($response, '</body>') === false) {
        $errors[] = "$tool: HTML verkar ofullständig (saknar </body> eller </html>)";
        echo "  ❌ HTML ofullständig\n";
    }
    
    // Kontrollera att layout-end inkluderas
    if (strpos($response, 'layout-end') === false && strpos($response, '</body>') === false) {
        $warnings[] = "$tool: Verkar sakna layout-end include";
    }
    
    if (!empty($jsErrors)) {
        foreach ($jsErrors as $err) {
            $warnings[] = "$tool: $err";
            echo "  ⚠️  $err\n";
        }
    } else {
        echo "  ✅ OK\n";
    }
}

echo "\n=== SAMMANFATTNING ===\n";
echo "Fel: " . count($errors) . "\n";
echo "Varningar: " . count($warnings) . "\n\n";

if (!empty($errors)) {
    echo "=== FEL ===\n";
    foreach ($errors as $error) {
        echo "❌ $error\n";
    }
}

if (!empty($warnings)) {
    echo "\n=== VARNINGAR ===\n";
    foreach ($warnings as $warning) {
        echo "⚠️  $warning\n";
    }
}

