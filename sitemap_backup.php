<?php
// sitemap.php - v2
// git commit: Lägg till automatisk lastmod och filupptäckt för publika PHP-sidor

header('Content-Type: application/xml; charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';
echo "\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";

$baseUrl = "https://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/";
$dir = __DIR__;

$files = scandir($dir);

foreach ($files as $file) {
    if (preg_match('/^(index|readme|visa|dela|skapa)\.php$/', $file)) {
        $url = $baseUrl . $file;
        $lastmod = date('Y-m-d', filemtime($file));
        echo "\n  <url>";
        echo "\n    <loc>$url</loc>";
        echo "\n    <lastmod>$lastmod</lastmod>";
        echo "\n    <changefreq>weekly</changefreq>";
        echo "\n    <priority>0.8</priority>";
        echo "\n  </url>";
    }
}

echo "\n</urlset>";
