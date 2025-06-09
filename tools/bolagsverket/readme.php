<!-- tools/bolagsverket/readme.php - v2 -->
<?php
$title = 'Om Bolagsverket API';
$metaDescription = 'Information om hur verktyget hämtar företagsdata från Bolagsverkets API:er via OAuth2, struktur, exempel och syfte.';
?>
<?php include '../../includes/layout-start.php'; ?>

<main class="container">
  <h1 class="title">
    <?= $title ?>
       <?php include '../../includes/back-link.php'; ?>
    <a href="index.php" class="info-link-floating" title="Tillbaka till verktyget">&larr;</a>
  </h1>

  <article class="card readme">
    <h2>🧠 Syfte</h2>
    <p>
      Verktyget demonstrerar hur man autentiserar mot och hämtar strukturerad företagsinformation från <strong>Bolagsverkets API för värdefulla datamängder</strong>.
      Det fungerar som en MVP med stöd för <code>/organisationer</code> och <code>/dokumentlista</code>.
    </p>

    <h2>⚙️ Funktioner</h2>
    <ul>
      <li>Hämta företagsinformation via organisationsnummer</li>
      <li>Visa data grupperat (t.ex. Juridik, Adress, SNI, Status)</li>
      <li>OAuth2-autentisering med Client Credentials Grant</li>
      <li>Visning av årsredovisningsmetadata (dokumentId, datum, format)</li>
      <li>Responsiv och semantisk tabellpresentation</li>
      <li>Temastöd (ljus/mörk)</li>
    </ul>

    <h2>📥 Användning</h2>
    <p>
      Ange ett giltigt svenskt organisationsnummer (10 siffror, utan bindestreck) och klicka på <strong>Hämta företagsinfo</strong>.
      Om årsredovisningar finns tillgängliga, visas dessa nedanför huvudinformationen.
    </p>

    <h2>📦 Teknik & Struktur</h2>
    <ul>
      <li><code>getdata.js</code> – hanterar token, API-anrop och tabellrendering</li>
      <li><code>get_data.php</code> – serverproxy med .env-konfiguration för credentials</li>
      <li><code>arsredovisningar.js</code> – hämtar årsredovisningslista och bygger kort</li>
      <li><code>tools.css</code> – form-, tabell- och layoutstöd</li>
      <li>JSON-flattening med stöd för arraynoder och djupt nästlad data</li>
    </ul>

    <h2>🧪 Exempelutdata</h2>
    <pre class="terminal-output">
Organisationsnummer: 5564756467
Företagsnamn: Aktiebolaget STOKAB
Juridisk form: Övriga aktiebolag
SNI: 61100 – Trådbunden telekommunikation
Adress: Box 711, 12002 ÅRSTA
Beskrivning: Föremålet för och det kommunala ändamålet med bolagets verksamhet är...
    </pre>

    <h2>🔒 Autentisering</h2>
    <p>
      Verktyget använder OAuth2 <em>Client Credentials Grant</em> mot token endpoint:
      <code>https://portal.api.bolagsverket.se/oauth2/token</code>.
      </p>

    <h2>📚 Referenser</h2>
    <ul>
      <li><a href="https://portal.api.bolagsverket.se/devportal/apis" target="_blank">Bolagsverkets API-dokumentation</a></li>
      <li><a href="https://datatracker.ietf.org/doc/html/rfc6749#section-4.4" target="_blank">OAuth2 RFC 6749 – Section 4.4</a></li>
    </ul>
  </article>
</main>

<?php include '../../includes/layout-end.php'; ?>
