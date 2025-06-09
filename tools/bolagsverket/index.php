<?php
$title = 'Bolagsverket API';
$metaDescription = 'Hämta företagsdata från Bolagsverkets API baserat på organisationsnummer.';
?>
<?php include '../../includes/layout-start.php'; ?>



  <form id="dataForm" class="form-group">
    <label for="orgnr">Organisationsnummer</label>
    <input type="text" id="orgnr" class="input" placeholder="Ex: 556475-6467" required>
    <div class="horizontal-tools">
      <button type="submit" class="button">Hämta företagsinfo</button>
      <button type="button" class="button secondary hidden" id="exportBtn">Exportera</button>
    </div>
  </form>
  <!-- ********** Laddikon ********** -->
<div id="loadingSpinner" class="spinner hidden" aria-hidden="true" style="margin: 1rem auto;"></div>


  <section id="tableSection" class="hidden mt-2">
    <div class="table-wrapper">
      <table class="table compact-table" id="orgTable">
        <thead><tr><th>Fält</th><th>Värde</th></tr></thead>
        <tbody></tbody>
      </table>
    </div>
  </section>
</main>

<?php include '../../includes/layout-end.php'; ?>
<script src="getdata.js" defer></script>
