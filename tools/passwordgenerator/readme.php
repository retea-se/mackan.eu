<?php
// tools/passwordgenerator/readme.php - v7
// Syfte: README för lösenordsgeneratorn, nu med readme.css-klasser och förbättrad struktur

include 'lang.php';
$lang = $_GET['lang'] ?? 'sv';
if (!isset($langs[$lang])) $lang = 'sv';
$t = $langs[$lang];

$title = $t['readme_title'];
$metaDescription = $t['purpose_text'];
include '../../includes/layout-start.php';
?>

<main class="readme">
  <div style="text-align:right;">
    <a href="?lang=sv">Svenska</a> | <a href="?lang=en">English</a>
  </div>
  <h1 class="readme__title">
    <?= $title ?>
    <?php include '../../includes/back-link.php'; ?>
  </h1>

  <article class="readme__section">
    <div class="readme__info">
      <i class="fa-solid fa-key"></i>
      <?= $t['readme_intro'] ?>
    </div>
  </article>

  <article class="readme__section">
    <h2 class="readme__subtitle"><?= $t['purpose_title'] ?></h2>
    <p class="readme__text">
      <?= $t['purpose_text'] ?>
    </p>
  </article>

  <article class="readme__section">
    <h2 class="readme__subtitle"><?= $t['how_title'] ?></h2>
    <ol class="readme__list">
      <?php foreach ($t['how_list'] as $item): ?>
        <li><?= $item ?></li>
      <?php endforeach; ?>
    </ol>
  </article>

  <article class="readme__section">
    <h2 class="readme__subtitle"><?= $t['preview_title'] ?></h2>
    <p class="readme__text">
      <?= $t['preview_text'] ?>
    </p>
  </article>

  <article class="readme__section">
    <h2 class="readme__subtitle"><?= $t['export_title'] ?></h2>
    <p class="readme__text">
      <?= $t['export_text'] ?>
    </p>
  </article>

  <article class="readme__section">
    <h2 class="readme__subtitle"><?= $t['strength_title'] ?></h2>
    <p class="readme__text">
      <?= $t['strength_text'] ?>
    </p>
    <ol class="readme__list">
      <?php foreach ($t['strength_list'] as $item): ?>
        <li><?= $item ?></li>
      <?php endforeach; ?>
    </ol>
    <div class="readme__info">
      <i class="fa-solid fa-shield-halved"></i>
      <?= $t['strength_info'] ?>
    </div>
    <table class="readme__table">
      <thead>
        <tr>
          <th><?= $t['strength_headers'][0] ?></th>
          <th><?= $t['strength_headers'][1] ?></th>
          <th><?= $t['strength_headers'][2] ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($t['strength_table'] as $i => $row): ?>
        <tr>
          <td><span class="tag tag--<?= strtolower($t['strength_tags'][$i]) ?>"><?= $t['strength_tags'][$i] ?></span></td>
          <td><?= $row[1] ?></td>
          <td><?= $row[2] ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <div class="readme__warning">
      <i class="fa-solid fa-triangle-exclamation"></i>
      <?= $t['strength_warning'] ?>
    </div>
  </article>

  <article class="readme__section">
    <h2 class="readme__subtitle"><?= $t['tips_title'] ?></h2>
    <ul class="readme__list">
      <?php foreach ($t['tips_list'] as $tip): ?>
        <li><?= $tip ?></li>
      <?php endforeach; ?>
    </ul>
  </article>

  <article class="readme__section">
    <h2 class="readme__subtitle"><?= $t['example_title'] ?></h2>
    <div class="readme__codeblock">
      <button class="readme__codecopy" title="<?= $t['copy_code'] ?>">
        <i class="fa-solid fa-copy"></i>
      </button>
      <pre><code><?= $t['example_code'] ?></code></pre>
    </div>
  </article>

  <article class="readme__section">
    <h2 class="readme__subtitle"><?= $t['passphrase_title'] ?></h2>
    <p class="readme__text">
      <?= $t['passphrase_text'] ?>
    </p>
    <ul class="readme__list">
      <?php foreach ($t['passphrase_list'] as $item): ?>
        <li><?= $item ?></li>
      <?php endforeach; ?>
    </ul>
    <div class="readme__info">
      <i class="fa-solid fa-lightbulb"></i>
      <?= $t['passphrase_info'] ?>
    </div>
  </article>

  <article class="readme__section">
    <h2 class="readme__subtitle"><?= $t['didyouknow_title'] ?></h2>
    <ul class="readme__list">
      <?php foreach ($t['didyouknow_list'] as $item): ?>
        <li><?= $item ?></li>
      <?php endforeach; ?>
    </ul>
  </article>

  <footer class="readme__meta"><?= $t['footer'] ?></footer>
</main>

<?php include '../../includes/layout-end.php'; ?>
