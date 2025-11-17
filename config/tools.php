<?php
/**
 * Verktygsregister - Centraliserad lista över alla verktyg
 *
 * =====================================================
 * HUR MAN LÄGGER TILL ETT NYTT VERKTYG:
 * =====================================================
 *
 * 1. Kopiera en befintlig verktygsarray
 * 2. Uppdatera följande fält:
 *    - 'title' => Verktygets namn (visas på kortet)
 *    - 'href' => Sökväg till verktyget (t.ex. '/tools/mitt-verktyg/index.php')
 *    - 'icon' => FontAwesome ikon-klass (t.ex. 'fa-star')
 *    - 'desc' => Kort beskrivning (visas på kortet)
 *    - 'category' => Välj en av: 'konvertering', 'generatorer', 'geo', 'sakerhet', 'ovrigt'
 *    - 'featured' => true/false (valfritt, endast för featured tools i hero)
 *
 * 3. Lägg till arrayen i listan nedan
 * 4. Spara filen - klart!
 *
 * Exempel:
 * [
 *     'title' => 'Mitt Nya Verktyg',
 *     'href' => '/tools/mitt-verktyg/index.php',
 *     'icon' => 'fa-rocket',
 *     'desc' => 'Gör något coolt med raketer.',
 *     'category' => 'generatorer',
 *     'featured' => false,  // Sätt till true för att visa i hero
 * ],
 *
 * KATEGORIER:
 * - 'konvertering' = Konvertering & Format
 * - 'generatorer' = Generatorer
 * - 'geo' = Geo & Koordinater
 * - 'sakerhet' = Säkerhet & Delning
 * - 'ovrigt' = Övrigt
 *
 * FEATURED TOOLS:
 * Max 3 verktyg ska ha 'featured' => true
 * Dessa visas i hero-sektionen på startsidan
 * =====================================================
 */

return [
    [
        'title' => 'Addy',
        'href' => '/tools/addy/index.php',
        'icon' => 'fa-envelope',
        'desc' => 'Skapa vidarebefordringsadresser för AnonAddy på sekunder.',
        'category' => 'sakerhet',
    ],
    [
        'title' => 'Aptus',
        'href' => '/tools/aptus/index.php',
        'icon' => 'fa-key',
        'desc' => 'Konvertera Aptus HEX-nycklar till decimalvärden och exportera resultat.',
        'category' => 'ovrigt',
    ],
    [
        'title' => 'Bolagsverket',
        'href' => '/tools/bolagsverket/index.php',
        'icon' => 'fa-industry',
        'desc' => 'Hämta, analysera och exportera företagsdata direkt via Bolagsverkets API.',
        'category' => 'ovrigt',
    ],
    [
        'title' => 'CSS till JSON',
        'href' => '/tools/css2json/index.php',
        'icon' => 'fa-right-left',
        'desc' => 'Ladda upp en eller flera CSS-filer och få strukturerad JSON för vidare användning.',
        'category' => 'konvertering',
    ],
    [
        'title' => 'CSV till JSON',
        'href' => '/tools/csv2json/index.php',
        'icon' => 'fa-file-csv',
        'desc' => 'Klistra in eller ladda upp CSV-data och konvertera till JSON med live-preview.',
        'category' => 'konvertering',
    ],
    [
        'title' => 'GeoParser & Plotter',
        'href' => '/tools/koordinat/impex_map.php',
        'icon' => 'fa-map-location-dot',
        'desc' => 'Parsea och plotta koordinater med kartstöd, adresser och CSV-export.',
        'category' => 'geo',
    ],
    [
        'title' => 'JSON Converter',
        'href' => '/tools/converter/index.php',
        'icon' => 'fa-arrows-rotate',
        'desc' => 'Arbeta i flikar för formattering, validering och konvertering mellan dataformat.',
        'category' => 'konvertering',
    ],
    [
        'title' => 'Koordinatkonverterare',
        'href' => '/tools/koordinat/index.php',
        'icon' => 'fa-compass',
        'desc' => 'Konvertera mellan WGS84, SWEREF99 och RT90 med karta och batchstöd.',
        'category' => 'geo',
    ],
    [
        'title' => 'Koordinater Impex',
        'href' => '/tools/koordinat/impex.php',
        'icon' => 'fa-map',
        'desc' => 'Avancerad konvertering av koordinatlistor med import/export i flera format.',
        'category' => 'geo',
    ],
    [
        'title' => 'Kortlänk',
        'href' => '/tools/kortlank/skapa-lank.php',
        'icon' => 'fa-link',
        'desc' => 'Förkorta länkar på ett säkert sätt och dela direkt eller via QR-kod.',
        'category' => 'sakerhet',
    ],
    [
        'title' => 'Lösenordsgenerator',
        'href' => '/tools/passwordgenerator/index.php',
        'icon' => 'fa-key',
        'desc' => 'Generera säkra lösenord, passfraser och exportera resultat med ett klick.',
        'category' => 'generatorer',
        'featured' => true,
    ],
    [
        'title' => 'Persontestdata',
        'href' => '/tools/testdata/index.php',
        'icon' => 'fa-address-book',
        'desc' => 'Generera realistiska testpersoner med personnummer, adresser och metadata.',
        'category' => 'generatorer',
    ],
    [
        'title' => 'PTS Diarium',
        'href' => '/tools/pts/index.php',
        'icon' => 'fa-file-lines',
        'desc' => 'Sök i Post- och telestyrelsens diarium och exportera resultat för vidare analys.',
        'category' => 'ovrigt',
    ],
    [
        'title' => 'QR-kodgenerator',
        'href' => '/tools/qr_v2/index.php',
        'icon' => 'fa-qrcode',
        'desc' => 'Skapa QR-koder för URL:er, kontaktkort och WiFi med egna färger och logotyper.',
        'category' => 'generatorer',
        'featured' => true,
    ],
    [
        'title' => 'QR-kodverkstad',
        'href' => '/tools/qr_v3/index.php',
        'icon' => 'fa-qrcode',
        'desc' => 'Avancerad QR-verkstad med stöd för felanmälningar, metadata och offline-utskrift.',
        'category' => 'generatorer',
    ],
    [
        'title' => 'RKA-kalkylator',
        'href' => '/tools/rka/index.php',
        'icon' => 'fa-gears',
        'desc' => 'Beräkna reservkraft, bränsleförbrukning och provkörningsscheman med RKA-profiler.',
        'category' => 'ovrigt',
    ],
    [
        'title' => 'Skyddad delning',
        'href' => '/tools/skyddad/index.php',
        'icon' => 'fa-shield-halved',
        'desc' => 'Skapa lösenordsskyddade länkar och dela känslig information på ett säkert sätt.',
        'category' => 'sakerhet',
    ],
    [
        'title' => 'Stötta – kontrollera personnummer',
        'href' => '/tools/stotta/index.php',
        'icon' => 'fa-id-card',
        'desc' => 'Validera svenska personnummer och organisationsnummer enligt gällande regler.',
        'category' => 'ovrigt',
    ],
    [
        'title' => 'Telefonnummergenerator',
        'href' => '/tools/tfngen/index.php',
        'icon' => 'fa-phone',
        'desc' => 'Generera testnummer för mobil och fast nät med valbara riktnummer.',
        'category' => 'generatorer',
    ],
    [
        'title' => 'Test-ID',
        'href' => '/tools/testid/index.php',
        'icon' => 'fa-id-badge',
        'desc' => 'Skapa testidentiteter med tillhörande metadata för olika scenarier.',
        'category' => 'generatorer',
    ],
    [
        'title' => 'Text till tal',
        'href' => '/tools/tts/index.php',
        'icon' => 'fa-volume-high',
        'desc' => 'Omvandla text till naturligt tal på flera språk direkt i webbläsaren.',
        'category' => 'ovrigt',
    ],
    [
        'title' => 'Timer & klocka',
        'href' => 'https://mackan.eu/timer',
        'icon' => 'fa-clock',
        'desc' => 'Digital klocka och timer med olika teman och presentationslägen.',
        'category' => 'ovrigt',
    ],
    [
        'title' => 'Flow Builder',
        'href' => '/tools/flow/index.php',
        'icon' => 'fa-diagram-project',
        'desc' => 'Skapa professionella flödesdiagram med drag-and-drop direkt i webbläsaren.',
        'category' => 'ovrigt',
    ],
    [
        'title' => 'QR-kodgenerator (v1)',
        'href' => '/tools/qr_v1/index.php',
        'icon' => 'fa-qrcode',
        'desc' => 'Enklare QR-kodgenerator för felanmälningar och länkar med batch-export.',
        'category' => 'generatorer',
    ],
    [
        'title' => 'Bildkonverterare',
        'href' => '/tools/bildconverter/index.php',
        'icon' => 'fa-image',
        'desc' => 'Konvertera bilder mellan WEBP, PNG och JPEG. Ladda upp filer eller ange URL.',
        'category' => 'konvertering',
        'featured' => true,
    ],

];
