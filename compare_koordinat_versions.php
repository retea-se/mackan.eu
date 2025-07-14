<?php
// Skript för att jämföra verktyg/koordinat och tools/koordinat
// och planera flytten

echo "<h1>Jämförelse: verktyg/koordinat vs tools/koordinat</h1>\n";

$verktygeDir = 'c:\Users\marcu\OneDrive\Dokument\_arbetsmapp mackan_eu\verktyg\koordinat';
$toolsDir = 'c:\Users\marcu\OneDrive\Dokument\_arbetsmapp mackan_eu\tools\koordinat';

function getFileList($dir, $baseDir = '') {
    $files = [];
    if (!is_dir($dir)) return $files;
    
    $items = scandir($dir);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        
        $path = $dir . '/' . $item;
        $relativePath = $baseDir . '/' . $item;
        
        if (is_dir($path)) {
            $files = array_merge($files, getFileList($path, $relativePath));
        } else {
            $files[$relativePath] = [
                'path' => $path,
                'size' => filesize($path),
                'modified' => filemtime($path)
            ];
        }
    }
    return $files;
}

$verktygeFiles = getFileList($verktygeDir);
$toolsFiles = getFileList($toolsDir);

echo "<h2>Filstruktur jämförelse</h2>\n";

// Filer som finns i verktyg men inte i tools
$onlyInVerktyg = array_diff_key($verktygeFiles, $toolsFiles);
echo "<h3>Filer som bara finns i verktyg/ (" . count($onlyInVerktyg) . "):</h3>\n";
foreach ($onlyInVerktyg as $file => $info) {
    echo "<p>📁 $file (storlek: " . $info['size'] . " bytes, ändrad: " . date('Y-m-d H:i:s', $info['modified']) . ")</p>\n";
}

// Filer som finns i tools men inte i verktyg
$onlyInTools = array_diff_key($toolsFiles, $verktygeFiles);
echo "<h3>Filer som bara finns i tools/ (" . count($onlyInTools) . "):</h3>\n";
foreach ($onlyInTools as $file => $info) {
    echo "<p>📁 $file (storlek: " . $info['size'] . " bytes, ändrad: " . date('Y-m-d H:i:s', $info['modified']) . ")</p>\n";
}

// Filer som finns i båda - jämför datum
$common = array_intersect_key($verktygeFiles, $toolsFiles);
echo "<h3>Gemensamma filer (" . count($common) . "):</h3>\n";

$newerInVerktyg = 0;
$newerInTools = 0;
$identical = 0;

foreach ($common as $file => $verktygeInfo) {
    $toolsInfo = $toolsFiles[$file];
    
    if ($verktygeInfo['modified'] > $toolsInfo['modified']) {
        echo "<p>🔄 $file - <strong style='color: orange;'>VERKTYG NYARE</strong> (verktyg: " . date('Y-m-d H:i:s', $verktygeInfo['modified']) . ", tools: " . date('Y-m-d H:i:s', $toolsInfo['modified']) . ")</p>\n";
        $newerInVerktyg++;
    } elseif ($toolsInfo['modified'] > $verktygeInfo['modified']) {
        echo "<p>✅ $file - <strong style='color: green;'>TOOLS NYARE</strong> (tools: " . date('Y-m-d H:i:s', $toolsInfo['modified']) . ", verktyg: " . date('Y-m-d H:i:s', $verktygeInfo['modified']) . ")</p>\n";
        $newerInTools++;
    } else {
        echo "<p>= $file - Identiska datum</p>\n";
        $identical++;
    }
}

echo "<h2>Sammanfattning</h2>\n";
echo "<p><strong>Bara i verktyg:</strong> " . count($onlyInVerktyg) . " filer</p>\n";
echo "<p><strong>Bara i tools:</strong> " . count($onlyInTools) . " filer</p>\n";
echo "<p><strong>Gemensamma:</strong> " . count($common) . " filer</p>\n";
echo "<p><strong>Verktyg nyare:</strong> $newerInVerktyg filer</p>\n";
echo "<p><strong>Tools nyare:</strong> $newerInTools filer</p>\n";
echo "<p><strong>Identiska:</strong> $identical filer</p>\n";

echo "<h2>Rekommendation</h2>\n";
if ($newerInVerktyg > 0 || count($onlyInVerktyg) > 0) {
    echo "<p style='color: orange; font-weight: bold;'>⚠️ UPPDATERING BEHÖVS - Det finns nyare/unika filer i verktyg/ som behöver kopieras till tools/</p>\n";
} else {
    echo "<p style='color: green; font-weight: bold;'>✅ TOOLS ÄR UPPDATERAT - Tools-versionen verkar vara den senaste</p>\n";
}

echo "<hr>\n";
echo "<p><em>Jämförelse kördes: " . date('Y-m-d H:i:s') . "</em></p>\n";
?>
