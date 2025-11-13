<!-- tools/addy/index.php - v1 -->
<?php $title = 'AnonAddy Address Generator'; ?>
<?php include '../../includes/layout-start.php'; ?>

<main class="layout__container">
  <form id="addressForm" class="form">
    <div class="form__grupp">
      <label for="fromAddress" id="fromLabel">Från</label>
      <input type="text" id="fromAddress" class="falt__input" placeholder="Ange avsändaradress...">
    </div>

    <div class="form__grupp">
      <label for="toAddress" id="toLabel">Till</label>
      <input type="text" id="toAddress" class="falt__input" placeholder="Ange mottagaradress...">
    </div>

    <div class="form__verktyg">
      <button type="button" class="knapp" id="generateButton" data-tippy-content="Generera en AnonAddy-adress">Generera adress</button>
      <button type="button" class="knapp knapp--liten hidden" id="copyButton" data-tippy-content="Kopiera adressen">Kopiera</button>
    </div>

    <div class="form__grupp">
      <label for="generatedAddress" id="resultLabel">Resultat</label>
      <input type="text" id="generatedAddress" class="falt__input" readonly placeholder="Din genererade adress...">
    </div>
  </form>

  <div class="form__verktyg">
    <button id="languageToggle" class="knapp knapp--liten" type="button" data-tippy-content="Byt språk på fälten">Sv/En</button>
  </div>
</main>

<?php include '../../includes/layout-end.php'; ?>
<script src="script.js" defer></script>
