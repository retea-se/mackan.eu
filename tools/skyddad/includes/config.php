<?php
// config.php - v2
// git commit: Lägg till TOKEN_SECRET för länkverifiering

// 🔐 Kryptering (AES-256-CBC)
define('ENCRYPTION_KEY', '7f260289c15f5d39e7ec5fa0f99a2295a5d3d60bfd1ee7c6cbd9db0ae5f070e7');
define('ENCRYPTION_IV', substr(hash('sha256', '1cf22c32fda8ff2dfc1aab3e447697ef'), 0, 16));

// 🔑 Token-skydd för visningslänk
define('TOKEN_SECRET', 'e35cd3f123aa77db8f0e7f8fefc0aaf20c30f15e93eb3c7ab986e0dc147f2267');
?>
