<!-- tools/aptus/readme.php - v3 -->
<?php
$title = 'Om Aptus-verktyget';
$metaDescription = 'Konvertera hexadecimala RFID/EM-nummer till Aptus-kompatibelt decimalformat. Perfekt för kodbrickor och passersystem.';
?>
<?php include '../../includes/layout-start.php'; ?>

<main class="container">
  <section class="readme">
    <h2>Vad gör Aptus-verktyget?</h2>

    <p>
      Aptus-verktyget är skapat för att konvertera en lista med hexadecimala RFID-/EM-nummer till det decimala format som används av Aptus-system. Det är särskilt användbart vid programmering av passersystem eller kodbrickor.
    </p>

    <p>
      Du matar in varje hex-sträng (t.ex. från en etikett, logg eller databasutdrag) på en egen rad. Verktyget använder JavaScripts <code>BigInt</code>-funktionalitet för att tolka varje hex-värde korrekt, även om det är mycket långt.
    </p>

    <p>
      Endast de <strong>nio sista siffrorna</strong> av det decimala värdet används, vilket matchar hur Aptus hanterar ID-nummer internt. Resultatet visas i en tabell som du kan exportera till CSV eller kopiera till urklipp.
    </p>

    <p>
      Verktyget sparar ingen data och fungerar direkt i webbläsaren.
    </p>

    <div class="horizontal-tools">
      <a href="./index.php" class="button secondary" title="Tillbaka">
        <i class="fa-solid fa-arrow-left"></i> Tillbaka till verktyget
      </a>
    </div>
  </section>
</main>



<?php include '../../includes/layout-end.php'; ?>
