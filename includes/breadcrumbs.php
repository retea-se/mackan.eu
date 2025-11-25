<?php
// includes/breadcrumbs.php - Global breadcrumbs för SEO-förbättring
// Version 1.0 - Skapad för bättre intern länkning och navigation

function generateBreadcrumbs($customPath = null) {
    $path = $customPath ?: $_SERVER['REQUEST_URI'];
    $segments = array_filter(explode('/', trim(parse_url($path, PHP_URL_PATH), '/')));

    // Ta bort index.php och andra filer från segmenten
    $cleanSegments = [];
    foreach ($segments as $segment) {
        if (!preg_match('/\.(php|html|htm)$/', $segment)) {
            $cleanSegments[] = $segment;
        }
    }

    echo '<nav class="breadcrumbs" aria-label="Du är här" style="margin-bottom: 1rem; font-size: 0.9rem; color: #6c757d;">';
    echo '<a href="/" style="color: #007bff; text-decoration: none;">🏠 Hem</a>';

    $currentPath = '';
    $breadcrumbMap = [
        'tools' => '🔧 Verktyg',
        'verktyg' => '🛠️ Verktyg',
        'koordinat' => '🗺️ Koordinatverktyg',
        'rka' => '⚡ RKA-kalkylatorer',
        'admin' => '⚙️ Administration',
        'retea' => '🏢 Retea',
        'qr_v1' => '📱 QR-kod v1',
        'qr_v2' => '📱 QR-kod v2',
        'passwordgenerator' => '🔐 Lösenordsgenerator',
        'converter' => '🔄 Konverterare',
        'pts' => '📋 PTS-sök',
        'bolagsverket' => '🏢 Bolagsverket',
        'faq' => '❓ Vanliga frågor',
        'howto' => '📖 How-to guides'
    ];

    foreach ($cleanSegments as $segment) {
        $currentPath .= '/' . $segment;
        $displayName = $breadcrumbMap[$segment] ?? ucfirst(str_replace(['-', '_'], ' ', $segment));

        echo ' › <a href="' . $currentPath . '/" style="color: #007bff; text-decoration: none;">' . $displayName . '</a>';
    }
    echo '</nav>';
}

function getRelatedTools($currentTool = '') {
    $tools = [
        'koordinat' => [
            'title' => '🗺️ Koordinatverktyg',
            'desc' => 'Konvertera mellan koordinatsystem (WGS84, SWEREF99, RT90)',
            'url' => '/tools/koordinat/'
        ],
        'rka' => [
            'title' => '⚡ RKA-kalkylatorer',
            'desc' => 'Dimensionera reservkraftverk och beräkna bränsleförbrukning',
            'url' => '/tools/rka/'
        ],
        'qr_v2' => [
            'title' => '📱 QR-kodgenerator',
            'desc' => 'Skapa anpassade QR-koder med logo och färger',
            'url' => '/tools/qr_v2/'
        ],
        'passwordgenerator' => [
            'title' => '🔐 Lösenordsgenerator',
            'desc' => 'Generera säkra lösenord med anpassade kriterier',
            'url' => '/tools/passwordgenerator/'
        ],
        'converter' => [
            'title' => '🔄 Enhetskonverterare',
            'desc' => 'Konvertera mellan olika måttenheter',
            'url' => '/tools/converter/'
        ],
        'pts' => [
            'title' => '📋 PTS-sökverktyg',
            'desc' => 'Sök i Post- och telestyrelsens register',
            'url' => '/tools/pts/'
        ]
    ];

    // Ta bort nuvarande verktyg från listan
    unset($tools[$currentTool]);

    if (empty($tools)) return '';

    $html = '<aside class="related-tools" style="margin-top: 2rem; padding: 1.5rem; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #28a745;">';
    $html .= '<h3 style="margin-top: 0; color: #28a745;">🔗 Relaterade verktyg</h3>';
    $html .= '<div class="tools-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem; margin-top: 1rem;">';

    $count = 0;
    foreach ($tools as $key => $tool) {
        if ($count >= 3) break; // Visa max 3 relaterade verktyg

        $html .= '<a href="' . $tool['url'] . '" style="text-decoration: none; padding: 1rem; border: 1px solid #dee2e6; border-radius: 6px; background: white; display: block; transition: all 0.2s; box-shadow: 0 1px 3px rgba(0,0,0,0.1);" onmouseover="this.style.transform=\'translateY(-2px)\'; this.style.boxShadow=\'0 4px 8px rgba(0,0,0,0.15)\'" onmouseout="this.style.transform=\'translateY(0)\'; this.style.boxShadow=\'0 1px 3px rgba(0,0,0,0.1)\'">';
        $html .= '<h4 style="margin: 0 0 0.5rem; color: #007bff; font-size: 1rem;">' . $tool['title'] . '</h4>';
        $html .= '<p style="margin: 0; color: #6c757d; font-size: 0.9rem; line-height: 1.4;">' . $tool['desc'] . '</p>';
        $html .= '</a>';
        $count++;
    }

    $html .= '</div>';

    // Lägg till länk till alla verktyg
    $html .= '<div style="margin-top: 1rem; text-align: center;">';
    $html .= '<a href="/tools/" style="color: #007bff; text-decoration: none; font-weight: 600;">→ Se alla verktyg</a>';
    $html .= '</div>';

    $html .= '</aside>';
    return $html;
}
?>
