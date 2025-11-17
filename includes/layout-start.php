<?php
// layout-start.php - v8
// git commit: Infört Open Graph, Twitter Card, ARIA-support och bildoptimering

header(
  "Content-Security-Policy: " .
  "default-src 'self'; " .
  "script-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://cdn.jsdelivr.net https://unpkg.com https://cdn.sheetjs.com https://html2canvas.hertzen.com https://www.googletagmanager.com https://www.google-analytics.com; " .
  "connect-src 'self' https://skatteverket.entryscape.net https://www.google-analytics.com https://stats.g.doubleclick.net https://unpkg.com https://cdn.jsdelivr.net; " .
  "style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://cdn.jsdelivr.net https://unpkg.com https://fonts.googleapis.com; " .
  "img-src 'self' data: https://api.qrserver.com https://www.google-analytics.com https://stats.g.doubleclick.net https://*.tile.openstreetmap.org https://unpkg.com; " .
  "font-src 'self' https://cdnjs.cloudflare.com https://fonts.gstatic.com;"
);


$title = $title ?? 'Mackan.eu';
$metaDescription = $metaDescription ?? 'Onlineverktyg för nördar';
$keywords = $keywords ?? null;
$canonical = $canonical ?? "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$metaImage = $metaImage ?? 'https://mackan.eu/icon/android-chrome-512x512.png';
$ogType = $ogType ?? 'website';
?>
<!DOCTYPE html>
<html lang="sv" data-theme="light">
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>
  <meta name="description" content="<?= htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8') ?>">
  <?php if ($keywords): ?>
  <meta name="keywords" content="<?= htmlspecialchars($keywords, ENT_QUOTES, 'UTF-8') ?>">
  <?php endif; ?>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#0066cc">
  <meta name="author" content="Mackan.eu">
  <meta name="robots" content="index, follow">
  <link rel="canonical" href="<?= htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8') ?>">

  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="<?= htmlspecialchars($ogType, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:locale" content="sv_SE">
  <meta property="og:site_name" content="Mackan.eu">
  <meta property="og:title" content="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:description" content="<?= htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:url" content="<?= htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:image" content="<?= htmlspecialchars($metaImage, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  <meta property="og:image:alt" content="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>">
  <meta name="twitter:description" content="<?= htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8') ?>">
  <meta name="twitter:image" content="<?= htmlspecialchars($metaImage, ENT_QUOTES, 'UTF-8') ?>">
  <meta name="twitter:image:alt" content="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>">

  <!-- Favicon & Icons -->
  <link rel="apple-touch-icon" sizes="180x180" href="/icon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/icon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/icon/favicon-16x16.png">
  <link rel="manifest" href="/icon/site.webmanifest">
  <link rel="shortcut icon" href="/icon/favicon.ico">

  <!-- Preconnect för snabbare laddning -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preconnect" href="https://cdnjs.cloudflare.com">
  <link rel="dns-prefetch" href="//cdn.jsdelivr.net">
  <link rel="dns-prefetch" href="//unpkg.com">

  <!-- Critical CSS först -->
  <link rel="stylesheet" href="/css/main.css?v=20251117">

  <!-- FontAwesome Subset - only 34 icons, ~3KB instead of 112KB -->
  <link rel="stylesheet" href="/css/fontawesome-subset.css">

  <!-- Tippy.js - ladda synkront för garanterad tillgänglighet -->
  <script src="https://unpkg.com/@popperjs/core@2"></script>
  <script src="https://unpkg.com/tippy.js@6"></script>

  <!-- Schema markup för organisation -->
  <?php include __DIR__ . '/schema-organization.php'; ?>

  <?php if (isset($extraHead)): ?>
  <?= $extraHead ?>
  <?php endif; ?>

  <?php include_once __DIR__ . '/analyticstracking.php'; ?>
</head>
<body>
  <div class="layout">
    <?php if (!isset($skipHeader) || !$skipHeader): ?>
    <?php include __DIR__ . '/header.php'; ?>
    <?php endif; ?>
    <main class="layout__main">
