<?php
// layout-start.php - v7
// git commit: Infört sticky-footer-stöd via .layout och .layout__main

header(
  "Content-Security-Policy: " .
  "default-src 'self'; " .
  "script-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://cdn.jsdelivr.net https://unpkg.com https://cdn.sheetjs.com https://html2canvas.hertzen.com https://www.googletagmanager.com https://www.google-analytics.com; " .
  "connect-src 'self' https://skatteverket.entryscape.net https://www.google-analytics.com https://stats.g.doubleclick.net; " .
  "style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://cdn.jsdelivr.net https://fonts.googleapis.com; " .
  "img-src 'self' data: https://api.qrserver.com https://www.google-analytics.com https://stats.g.doubleclick.net; " .
  "font-src 'self' https://cdnjs.cloudflare.com https://fonts.gstatic.com;"
);


$title = $title ?? 'Mackan.eu';
$metaDescription = $metaDescription ?? 'Onlineverktyg för nördar';
$keywords = $keywords ?? null;
$canonical = $canonical ?? "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html lang="sv">
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>
  <meta name="description" content="<?= htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8') ?>">
  <?php if ($keywords): ?>
  <meta name="keywords" content="<?= htmlspecialchars($keywords, ENT_QUOTES, 'UTF-8') ?>">
  <?php endif; ?>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="canonical" href="<?= htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8') ?>">
  <link rel="stylesheet" href="/css/main.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- Schema markup för organisation -->
  <?php include __DIR__ . '/schema-organization.php'; ?>

  <?php if (isset($extraHead)): ?>
  <?= $extraHead ?>
  <?php endif; ?>

  <?php include_once __DIR__ . '/analyticstracking.php'; ?>
</head>
<body>
  <div class="layout">
    <?php include __DIR__ . '/header.php'; ?>
    <main class="layout__main">
