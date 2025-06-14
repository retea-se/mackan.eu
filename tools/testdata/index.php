<!-- tools/testdata/index.php - v9 -->
<?php
$title = 'Personinformation - testdata';
$metaDescription = 'Generera svenska testpersoner med namn, företag, telefonnummer, e-post, mobiltelefon och personnummer baserat på testdata.';
?>
<?php include '../../includes/layout-start.php'; ?>
<link rel="stylesheet" href="/css/ver250613.css">

<main class="layout__container">

  <!-- ********** START Sektion: Fältval ********** -->
  <div class="form__grupp">
    <label>Vilka fält ska genereras?</label>
    <div class="horizontal-tools" id="fältval" style="flex-wrap: wrap; gap: 0.5rem;">
      <?php
      $fält = [
        'fornamn' => 'Förnamn',
        'efternamn' => 'Efternamn',
        'kon' => 'Kön',
        'foretag' => 'Företag',
        'telefon' => 'Telefon',
        'mobiltelefon' => 'Mobiltelefon',
        'epost' => 'E-post',
        'personnummer' => 'Personnummer'
      ];
      foreach ($fält as $value => $label) {
        echo "<label class='checkbox' style='margin-right: 1rem; margin-bottom: 0.5rem; cursor: pointer;'><input type='checkbox' class='field-toggle' value='$value' checked> $label</label>";
      }
      ?>
    </div>
  </div>
  <!-- ********** SLUT Sektion: Fältval ********** -->

  <!-- ********** START Sektion: Formulär ********** -->
  <form class="form__grupp">
    <div class="form__grupp">
      <label for="antal">Antal personer att generera</label>
      <input type="number" id="antal" class="falt__input" value="1" min="1" max="100">
    </div>

    <div id="exportControls" class="utils--dold">
      <label for="exportFormat" style="white-space: nowrap;">Välj exportformat:</label>
      <select id="exportFormat" class="falt__select">
        <option value="json">JSON (visa)</option>
        <option value="csv">CSV (visa)</option>
        <option value="txt">TXT (visa)</option>
        <option value="xlsx">Excel (ladda ner)</option>
      </select>
      <button type="button" id="downloadBtn" class="knapp">Visa / Ladda ner</button>
    </div>

    <div class="horizontal-tools" style="flex-wrap: wrap; gap: 0.5rem; margin-top: 1rem;">
      <button type="button" id="generateBtn" class="knapp">Generera testperson</button>
      <button type="button" class="knapp utils--dold">Rensa</button>
    </div>
  </form>

  <div class="form__grupp">
    <button type="button" id="formatBtn" class="knapp utils--dold">Standardisera personnummer</button>
  </div>
  <!-- ********** SLUT Sektion: Formulär ********** -->

  <!-- ********** START Sektion: Resultat ********** -->
  <div class="tabell__wrapper">
    <div class="tabell__wrapper"><!-- TODO: osäker konvertering: table-responsive -->
      <table class="tabell" id="resultTable" style="table-layout: auto;">
        <thead>
          <tr>
            <th>Namn</th>
            <th>Telefon</th>
            <th>Personnummer</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td data-label="Namn">Anna Andersson</td>
            <td data-label="Telefon">070-1234567</td>
            <td data-label="Personnummer">19900101-1234</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <!-- ********** SLUT Sektion: Resultat ********** -->
</main>

<?php include '../../includes/layout-end.php'; ?>
<script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>
<script src="script.js" defer></script>
<script src="pnr-utils.js" defer></script>
<script src="export.js" defer></script>
