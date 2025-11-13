<?php
// ********** START Header **********
?>
<header class="nav">
  <div class="nav__vänster">
    <a href="/index.php"
       class="sidfot__ikonlank touch-target"
       aria-label="Startsida"
       title="Gå till startsida">
      <i class="fa-solid fa-house" aria-hidden="true"></i>
    </a>
  </div>

  <div class="nav__titel">
    <?= $title ?? 'Mackan.eu' ?>
  </div>

  <div class="nav__höger">
    <?php
    // Hitta readme-fil för aktuellt verktyg
    require_once __DIR__ . '/find-readme.php';
    $readmePath = findReadmeFile();

    // Visa readme-länk om den finns
    if ($readmePath) {
      ?>
      <a href="<?= htmlspecialchars($readmePath, ENT_QUOTES, 'UTF-8') ?>"
         class="knapp__ikon"
         aria-label="Om verktyget"
         data-tippy-content="Om verktyget"
         style="text-decoration: none; margin-right: 0.5rem;">
        <i class="fa-solid fa-circle-info"></i>
      </a>
      <?php
    }
    ?>
    <button id="themeToggle"
            class="knapp__ikon touch-target"
            aria-label="Byt tema"
            aria-pressed="false"
            title="Växla mellan ljust och mörkt tema">
      <i class="fa-solid fa-moon" aria-hidden="true"></i>
    </button>
  </div>
</header>
<script src="/js/theme-toggle.js"></script>
<?php
// ********** SLUT Header **********
?>
