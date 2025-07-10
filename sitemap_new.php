<?php
// sitemap.php - v3 - Utökad för att inkludera alla publika verktyg
// Förbättrad SEO-sitemap som hittar alla viktiga sidor

header('Content-Type: application/xml; charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';
echo "\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";

$baseUrl = "https://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/";
$rootDir = __DIR__;

function addUrlToSitemap($url, $lastmod, $priority = '0.8', $changefreq = 'weekly') {
    echo "\n  <url>";
    echo "\n    <loc>$url</loc>";
    echo "\n    <lastmod>$lastmod</lastmod>";
    echo "\n    <changefreq>$changefreq</changefreq>";
    echo "\n    <priority>$priority</priority>";
    echo "\n  </url>";
}

function scanDirectoryRecursive($dir, $baseUrl, $rootDir) {
    $urls = [];
    
    try {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS)
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $relativePath = str_replace($rootDir . DIRECTORY_SEPARATOR, '', $file->getPathname());
                $relativePath = str_replace('\\', '/', $relativePath); // Windows-kompatibilitet
                
                // Skippa admin, includes, privata filer och vendor
                if (preg_match('/^(admin|includes|vendor|backups|cgi-bin)\//', $relativePath) ||
                    strpos($relativePath, '_old') !== false ||
                    strpos($relativePath, 'config/') === 0) {
                    continue;
                }
                
                // Inkludera endast publika PHP-filer
                if (preg_match('/\.(php)$/', $relativePath)) {
                    $urls[] = [
                        'url' => $baseUrl . $relativePath,
                        'lastmod' => date('Y-m-d', $file->getMTime()),
                        'priority' => getPriority($relativePath)
                    ];
                }
            }
        }
    } catch (Exception $e) {
        // Fallback till enkel skanning om rekursiv iterering misslyckas
        error_log("Sitemap: Fel vid rekursiv skanning: " . $e->getMessage());
    }
    
    return $urls;
}

function getPriority($path) {
    // Prioritera viktiga sidor högre
    if ($path === 'index.php') return '1.0';
    if (preg_match('/^(om|verktyg)\.php$/', basename($path))) return '0.9';
    if (strpos($path, 'tools/') === 0) return '0.8';
    if (strpos($path, 'readme') !== false) return '0.6';
    return '0.7';
}

// Lägg till root-filer först
$rootFiles = ['index.php', 'om.php', 'sitemap.php', 'todo.php'];
foreach ($rootFiles as $file) {
    if (file_exists($rootDir . '/' . $file)) {
        addUrlToSitemap(
            $baseUrl . $file,
            date('Y-m-d', filemtime($rootDir . '/' . $file)),
            getPriority($file),
            $file === 'index.php' ? 'daily' : 'weekly'
        );
    }
}

// Skanna alla undermappar rekursivt
$allUrls = scanDirectoryRecursive($rootDir, $baseUrl, $rootDir);

// Sortera URLs alfabetiskt för konsistens
usort($allUrls, function($a, $b) {
    return strcmp($a['url'], $b['url']);
});

// Lägg till alla hittade URLs
foreach ($allUrls as $urlData) {
    addUrlToSitemap(
        $urlData['url'],
        $urlData['lastmod'],
        $urlData['priority']
    );
}

echo "\n</urlset>";
?>
