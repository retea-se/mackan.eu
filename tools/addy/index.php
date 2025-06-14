<!-- tools/addy/index.php - v1 -->
<?php $title = 'AnonAddy Address Generator'; ?>
<?php include '../../includes/layout-start.php'; ?>

<main class="layout__container">
  <form id="addressForm" class="form__grupp">
    <div class="form__grupp">
      <label for="fromAddress" id="fromLabel">Från</label>
      <input type="text" id="fromAddress" class="falt__input" placeholder="Ange avsändaradress...">
    </div>

    <div class="form__grupp">
      <label for="toAddress" id="toLabel">Till</label>
      <input type="text" id="toAddress" class="falt__input" placeholder="Ange mottagaradress...">
    </div>

    <button type="button" class="knapp" id="generateButton" onclick="generateAddress()">Generera Adress</button>

    <div class="form__grupp">
      <label for="generatedAddress" id="resultLabel">Resultat</label>
      <input type="text" id="generatedAddress" class="falt__input" readonly placeholder="Din genererade adress...">
    </div>

    <button type="button" class="knapp utils--dold" id="copyButton" onclick="copyToClipboard()">Kopiera</button>
  </form>

  <div class="horizontal-tools"><!-- TODO: osäker konvertering -->
    <button id="languageToggle" class="knapp">Sv/En</button>
  </div>
</main>

<?php include '../../includes/layout-end.php'; ?>
<script src="script.js" defer></script>
