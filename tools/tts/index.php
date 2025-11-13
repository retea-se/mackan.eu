<?php
// tools/tts/index.php - v1
$title = 'Text-to-Speech';
$subtitle = 'Skriv text och spela upp med vald röst';
include '../../includes/layout-start.php';
?>

<main class="layout__container">
  <h1 class="title"><!-- TODO: osäker konvertering: title -->
    <?= $title ?>
  </h1>
  <?php if (!empty($subtitle)): ?>
    <p class="subtitle"><!-- TODO: osäker konvertering: subtitle --><?= $subtitle ?></p>
  <?php endif; ?>

  <div class="form__grupp">
    <label for="textInput">Klistra in text:</label>
    <textarea id="textInput" class="falt__textarea" rows="6"></textarea>
  </div>

  <div class="form__grupp">
    <label for="voiceSelect">Välj röst:</label>
    <select id="voiceSelect" class="falt__input"></select>
  </div>

  <div class="form__grupp">
    <button id="playBtn" class="knapp">Spela upp</button>
    <button id="downloadBtn" class="knapp">Ladda ner</button>
  </div>
</main>

<?php include '../../includes/layout-end.php'; ?>
<script src="script.js"></script>
