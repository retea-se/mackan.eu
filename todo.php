<?php
<?php
/*
🟢 Snabbfixar (1–10 min)
4. Lägg till Open Graph-data och Twitter Cards i layout-start.php.

🟡 Medelnivå (10–30 min)
6. Skapa en helper.php med funktioner för gemensam logik.
7. Inför Content-Security-Policy header i layout-start.php.
8. Lägg till rate limiting med IP-baserad kontroll i dela-handler.php och visa-handler.php.
9. Gör adminpanelen responsiv med filter (t.ex. dag/vecka).
10. Lägg till favicons och manifest.json.

🔵 Större förbättringar (30+ min)
11. Implementera bruteforce-skydd och blockering efter 5 fel.
12. Lägg till möjlighet att välja TTL (giltighetstid) i delningsformuläret.
13. Skapa testfall och manuell teststrategi.
14. Inför valfritt PIN-skydd på visningslänken.
15. Gör gränssnittet flerspråkigt med språkväxlare.
16. Bygg QR-kodgenerator till visningslänken.

📈 Redan gjort
~~1. Escapa all utdata med htmlspecialchars()~~
~~2. Lägg till session_regenerate_id() vid inloggning~~
~~3. Visa ett enklare felmeddelande vid exception, och logga tekniskt fel separat~~
~~5. Ställ in robots.txt och sitemap.xml~~
~~✔ Engångslänkar med AES-256 och HMAC-token~~
~~✔ Adminpanel med händelseloggar och statistik~~
~~✔ Automatisk radering av visade hemligheter~~
~~✔ Integritetstext under delningsformuläret~~
*/