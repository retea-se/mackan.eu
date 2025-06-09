<?php
// tools/tts/index.php - v1
$title = 'Text-to-Speech';
$subtitle = 'Skriv text och spela upp med vald röst';
include '../../includes/layout-start.php';
?>

<main class="container">
  <h1 class="title">
    <?= $title ?>
    <a href="readme.php" class="info-link-floating" title="Om verktyget">ⓘ</a>
  </h1>
  <?php if (!empty($subtitle)): ?>
    <p class="subtitle"><?= $subtitle ?></p>
  <?php endif; ?>

  <div class="form-group">
    <label for="textInput">Klistra in text:</label>
    <textarea id="textInput" class="textarea" rows="6"></textarea>
  </div>

  <div class="form-group">
    <label for="voiceSelect">Välj röst:</label>
    <select id="voiceSelect" class="input"></select>
  </div>

  <div class="form-group">
    <button id="playBtn" class="button">Spela upp</button>
    <button id="downloadBtn" class="button">Ladda ner</button>
  </div>
</main>

<?php include '../../includes/layout-end.php'; ?>
<script src="script.js"></script>
