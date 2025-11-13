<?php
/**
 * check_links.php - Kontrollerar alla länkar från config/tools.php och andra viktiga sidor
 * Användning: php check_links.php
 */

$baseUrl = 'https://mackan.eu';
$tools = include __DIR__ . '/config/tools.php';

$urlsToCheck = [
    // Root-sidor
    '/',
    '/index.php',
    '/om.php',
    '/sitemap.php',
    '/tools/',
    '/tools/index.php',

    // Alla verktyg från config
];

// Lägg till alla verktyg från config
foreach ($tools as $tool) {
    $urlsToCheck[] = $tool['href'];
}

// Ytterligare sidor att kontrollera
$additionalUrls = [
    '/admin/',
    '/admin/index.php',
    '/admin/dashboard.php',
    '/admin/insight.php',
    '/admin/pro-analytics.php',
    '/admin/security-monitor.php',
    '/admin/kortlank-stats.php',
    '/admin/hantera-kortlankar.php',
    '/admin/skyddad-stats.php',
    '/admin/hantera-skyddad.php',
    '/admin/visits.php',
    '/admin/geo-country.php?ip=8.8.8.8', // Testa med IP-parameter
];

$urlsToCheck = array_merge($urlsToCheck, $additionalUrls);

// Ta bort duplicater och sortera
$urlsToCheck = array_unique($urlsToCheck);
sort($urlsToCheck);

echo "Kontrollerar " . count($urlsToCheck) . " länkar...\n\n";

$results = [
    'ok' => [],
    '404' => [],
    'other' => [],
    'errors' => []
];

foreach ($urlsToCheck as $url) {
    $fullUrl = $baseUrl . $url;

    // Initiera cURL
    $ch = curl_init($fullUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_NOBODY, true); // HEAD request
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mackan.eu Link Checker 1.0');

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        $results['errors'][] = [
            'url' => $url,
            'error' => $error
        ];
        echo "❌ ERROR: $url - $error\n";
    } elseif ($httpCode == 200) {
        $results['ok'][] = $url;
        echo "✅ OK: $url\n";
    } elseif ($httpCode == 404) {
        $results['404'][] = $url;
        echo "🔴 404: $url\n";
    } else {
        $results['other'][] = [
            'url' => $url,
            'code' => $httpCode
        ];
        echo "⚠️  $httpCode: $url\n";
    }

    // Lite delay för att inte överbelasta servern
    usleep(100000); // 0.1 sekund
}

// Sammanfattning
echo "\n" . str_repeat("=", 60) . "\n";
echo "SAMMANFATTNING\n";
echo str_repeat("=", 60) . "\n";
echo "✅ OK: " . count($results['ok']) . "\n";
echo "🔴 404: " . count($results['404']) . "\n";
echo "⚠️  Andra statuskoder: " . count($results['other']) . "\n";
echo "❌ Fel: " . count($results['errors']) . "\n";

if (!empty($results['404'])) {
    echo "\n🔴 404-FEL:\n";
    foreach ($results['404'] as $url) {
        echo "   - $url\n";
    }
}

if (!empty($results['other'])) {
    echo "\n⚠️  ANDRA STATUSKODER:\n";
    foreach ($results['other'] as $item) {
        echo "   - {$item['url']} ({$item['code']})\n";
    }
}

if (!empty($results['errors'])) {
    echo "\n❌ FEL:\n";
    foreach ($results['errors'] as $item) {
        echo "   - {$item['url']}: {$item['error']}\n";
    }
}

// Spara resultat till fil
$reportFile = __DIR__ . '/link_check_report_' . date('Y-m-d_H-i-s') . '.txt';
file_put_contents($reportFile,
    "Länkkontrollrapport - " . date('Y-m-d H:i:s') . "\n" .
    str_repeat("=", 60) . "\n\n" .
    "Totalt kontrollerade länkar: " . count($urlsToCheck) . "\n" .
    "✅ OK: " . count($results['ok']) . "\n" .
    "🔴 404: " . count($results['404']) . "\n" .
    "⚠️  Andra statuskoder: " . count($results['other']) . "\n" .
    "❌ Fel: " . count($results['errors']) . "\n\n" .
    (!empty($results['404']) ? "404-FEL:\n" . implode("\n", array_map(fn($u) => "  - $u", $results['404'])) . "\n\n" : "") .
    (!empty($results['other']) ? "ANDRA STATUSKODER:\n" . implode("\n", array_map(fn($i) => "  - {$i['url']} ({$i['code']})", $results['other'])) . "\n\n" : "")
);

echo "\n📄 Rapport sparad till: $reportFile\n";
?>


