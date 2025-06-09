<!-- tools/addy/index.php - v1 -->
<?php $title = 'AnonAddy Address Generator'; ?>
<?php include '../../includes/layout-start.php'; ?>

<main class="container">
  <form id="addressForm" class="form-group">
    <div class="form-group">
      <label for="fromAddress" id="fromLabel">Från</label>
      <input type="text" id="fromAddress" class="input" placeholder="Ange avsändaradress...">
    </div>

    <div class="form-group">
      <label for="toAddress" id="toLabel">Till</label>
      <input type="text" id="toAddress" class="input" placeholder="Ange mottagaradress...">
    </div>

    <button type="button" class="button" id="generateButton" onclick="generateAddress()">Generera Adress</button>

    <div class="form-group">
      <label for="generatedAddress" id="resultLabel">Resultat</label>
      <input type="text" id="generatedAddress" class="input" readonly placeholder="Din genererade adress...">
    </div>

    <button type="button" class="button hidden" id="copyButton" onclick="copyToClipboard()">Kopiera</button>
  </form>

  <div class="horizontal-tools">

    <button id="languageToggle" class="button">Sv/En</button>
  </div>
</main>

<?php include '../../includes/layout-end.php'; ?>
<script src="script.js" defer></script>
