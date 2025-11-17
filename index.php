<?php
/**
 * index.php - v6 - Ny landningssida med kategorier
 *
 * =====================================================
 * HUR DET FUNGERAR:
 * =====================================================
 *
 * 1. Läser in alla verktyg från config/tools.php
 * 2. Filtrerar ut "featured" verktyg (de med 'featured' => true)
 * 3. Grupperar resterande verktyg efter kategori
 * 4. Renderar:
 *    - Navigation med temaväxling
 *    - Hero-sektion med featured tools
 *    - Kategori-sektioner med verktyg
 *    - Footer
 *
 * LÄGG TILL NYTT VERKTYG:
 * - Öppna config/tools.php och följ instruktionerna där
 * - Verktyget dyker automatiskt upp på rätt plats här!
 *
 * LÄGG TILL NY KATEGORI:
 * - Uppdatera $categories-arrayen nedan
 * - Lägg till kategori-key, titel och FontAwesome-ikon
 * =====================================================
 */

ini_set('display_errors', 1);
error_reporting(E_ALL);
$title = 'Verktyg';
$metaDescription = 'Utforska kostnadsfria onlineverktyg för utvecklare och tekniker. Generera, konvertera och analysera data snabbt och enkelt.';
?>
<?php include 'includes/layout-start.php'; ?>

<?php
// Läs in alla verktyg från centraliserad fil
$tools = include __DIR__ . '/config/tools.php';

// Filtrera ut featured tools
$featuredTools = array_filter($tools, function($tool) {
    return isset($tool['featured']) && $tool['featured'] === true;
});

// Kategoridefinitioner
$categories = [
    'konvertering' => [
        'title' => 'Konvertering & Format',
        'icon' => 'fa-arrows-rotate'
    ],
    'generatorer' => [
        'title' => 'Generatorer',
        'icon' => 'fa-wand-magic-sparkles'
    ],
    'geo' => [
        'title' => 'Geo & Koordinater',
        'icon' => 'fa-map'
    ],
    'sakerhet' => [
        'title' => 'Säkerhet & Delning',
        'icon' => 'fa-shield'
    ],
    'ovrigt' => [
        'title' => 'Övrigt',
        'icon' => 'fa-toolbox'
    ]
];

// Gruppera verktyg per kategori
$toolsByCategory = [];
foreach ($tools as $tool) {
    $category = $tool['category'] ?? 'ovrigt';
    $toolsByCategory[$category][] = $tool;
}

// Sortera verktyg inom varje kategori alfabetiskt
foreach ($toolsByCategory as $cat => $catTools) {
    usort($catTools, function($a, $b) {
        return strcasecmp($a['title'], $b['title']);
    });
    $toolsByCategory[$cat] = $catTools;
}
?>

<!-- Navigation -->
<nav class="landing-nav">
  <div class="landing-nav__content">
    <a href="/" class="landing-nav__logo">mackan.eu</a>
    <div class="landing-nav__right">
      <button class="landing-theme-toggle" onclick="toggleTheme()">
        <i class="fas fa-moon"></i>
        <span id="theme-text">Mörkt</span>
      </button>
    </div>
  </div>
</nav>

<!-- Hero Section -->
<div class="landing-hero">
  <div class="landing-hero__content">
    <h1 class="landing-hero__title">Kraftfulla verktyg för utvecklare</h1>
    <p class="landing-hero__description">Snabba, säkra och kostnadsfria onlineverktyg för konvertering, generering och testning. Allt körs lokalt i din webbläsare.</p>

    <!-- Featured Tools -->
    <div class="landing-featured">
      <?php foreach ($featuredTools as $tool): ?>
        <a href="<?= htmlspecialchars($tool['href']) ?>" class="landing-featured__card">
          <?php if (!empty($tool['icon'])): ?>
            <div class="landing-featured__icon">
              <i class="fa-solid <?= htmlspecialchars($tool['icon']) ?>"></i>
            </div>
          <?php endif; ?>
          <div class="landing-featured__title"><?= htmlspecialchars($tool['title']) ?></div>
          <?php if (!empty($tool['desc'])): ?>
            <div class="landing-featured__desc"><?= htmlspecialchars($tool['desc']) ?></div>
          <?php endif; ?>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- Main Content - Kategorier -->
<div class="landing-main">
  <?php foreach ($categories as $catKey => $catInfo): ?>
    <?php if (isset($toolsByCategory[$catKey]) && count($toolsByCategory[$catKey]) > 0): ?>
      <div class="landing-category">
        <div class="landing-category__header">
          <div class="landing-category__title-wrap">
            <div class="landing-category__icon">
              <i class="fas <?= htmlspecialchars($catInfo['icon']) ?>"></i>
            </div>
            <h2 class="landing-category__title"><?= htmlspecialchars($catInfo['title']) ?></h2>
          </div>
          <div class="landing-view-toggle">
            <button class="landing-view-toggle__button landing-view-toggle__button--active" onclick="setView(this, 'grid')">
              <i class="fas fa-grip"></i>
            </button>
            <button class="landing-view-toggle__button" onclick="setView(this, 'list')">
              <i class="fas fa-list"></i>
            </button>
          </div>
        </div>

        <div class="landing-tools">
          <?php foreach ($toolsByCategory[$catKey] as $tool): ?>
            <a href="<?= htmlspecialchars($tool['href']) ?>" class="landing-tool">
              <?php if (!empty($tool['icon'])): ?>
                <i class="fa-solid <?= htmlspecialchars($tool['icon']) ?> landing-tool__icon"></i>
              <?php endif; ?>
              <div class="landing-tool__content">
                <div class="landing-tool__title"><?= htmlspecialchars($tool['title']) ?></div>
                <?php if (!empty($tool['desc'])): ?>
                  <div class="landing-tool__desc"><?= htmlspecialchars($tool['desc']) ?></div>
                <?php endif; ?>
              </div>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>
  <?php endforeach; ?>
</div>

<!-- Footer -->
<footer class="landing-footer">
  <div class="landing-footer__content">
    <p><strong>mackan.eu</strong> - Alla verktyg körs lokalt i din webbläsare</p>
    <p style="margin-top: 0.5rem; font-size: 0.85rem;">Ingen data skickas till våra servrar. Öppen källkod. Gratis för alltid.</p>
  </div>
</footer>

<!-- Include JS for theme toggle and view toggle -->
<script src="/js/theme-toggle.js"></script>
<script src="/js/view-toggle.js"></script>

<?php include 'includes/layout-end.php'; ?>
