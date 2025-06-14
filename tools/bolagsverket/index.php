<?php
$title = 'Bolagsverket API';
$metaDescription = 'Hämta företagsdata från Bolagsverkets API baserat på organisationsnummer.';
?>
<?php include '../../includes/layout-start.php'; ?>

<main class="layout__container">
  <form id="dataForm" class="form__grupp">
    <div class="form__grupp">
      <label for="orgnr" class="falt">Organisationsnummer</label>
      <input type="text" id="orgnr" class="falt__input" placeholder="Ex: 556475-6467" required>
    </div>
    <div class="form__grupp">
      <button type="submit" class="knapp">Hämta företagsinfo</button>
      <button type="button" class="knapp utils--dold" id="exportBtn">Exportera</button>
    </div>
  </form>
  <!-- ********** Laddikon ********** -->
  <div id="loadingSpinner" class="utils--dold utils--mt-1" aria-hidden="true" style="margin: 1rem auto;"></div>

  <section id="tableSection" class="utils--dold utils--mt-2">
    <div>
      <table class="tabell tabell--kompakt" id="orgTable">
        <thead>
          <tr>
            <th class="tabell__huvud">Fält</th>
            <th class="tabell__huvud">Värde</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </section>
</main>

<?php include '../../includes/layout-end.php'; ?>
<script src="getdata.js" defer></script>
