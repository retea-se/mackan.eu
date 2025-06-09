<!-- tools/testnummer/readme.php - v3 -->
<?php
$title = 'Testnummer – Generera svenska telefonnummer';
$metaDescription = 'Skapa testnummer inom mobil- och fastnät. Välj serier, format och exportera till CSV, text eller JSON.';
?>
<?php include '../../includes/layout-start.php'; ?>

<main class="container">
  <h1 class="title">
    <?= $title ?>
    <a href="index.php" class="info-link-floating" title="Tillbaka till verktyget">&larr;</a>
  </h1>

  <article class="card readme">
    <h2>Syfte</h2>
    <p>Detta verktyg används för att generera svenska testnummer i korrekt format. Perfekt för systemtestning, demo och utbildning utan att belasta riktiga nät.</p>

    <h2>Funktioner</h2>
    <ul>
      <li>Välj antal nummer (upp till 500)</li>
      <li>Kryssrutor för att välja mellan mobil- och fastnätsserier</li>
      <li>Stöd för internationellt format: ja, nej eller slumpmässigt</li>
      <li>Generering utan dubbletter</li>
      <li>Export till CSV, textfil eller JSON med unikt tidsbaserat filnamn</li>
    </ul>

    <h2>Användning</h2>
    <p>Fyll i hur många nummer du vill generera (max 500), välj vilka nummerblock som ska användas (t.ex. 070, 031), och välj om numren ska vara i internationellt format. Klicka sedan på <strong>Generera</strong>. Du ser resultatet direkt och kan därefter exportera det.</p>

    <h2>Exempel</h2>
    <pre class="terminal-output">
Antal: 3
Serier: [070, 031, 08]
Format: Nej

Resultat:
070-1740605
031-3900652
08-46500422
    </pre>
  </article>
</main>

<?php include '../../includes/layout-end.php'; ?>

<!-- 
======================
✅ SEO-CHECKLISTA
======================

1. $title
   - Testnummer – Generera svenska telefonnummer

2. $metaDescription
   - Skapa testnummer inom mobil- och fastnät. Välj serier, format och exportera till CSV, text eller JSON.

3. <h1> motsvarar titel

4. Inga externa länkar i denna sida

5. <main> och <article> används korrekt

6. Robotsstyrning via meta.php (default: index/follow)

7. Ingen JS laddas här, endast ren info

======================
-->
