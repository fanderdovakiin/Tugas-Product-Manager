<?php
// ============================================================
// app/views/layout.php — Master layout
// Variabel yang harus disiapkan oleh view pemanggil:
//   $pageTitle  string
//   $content    string (output buffered content)
//   $flashMessages array
// ============================================================

declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Product Manager — Kelola data produk Anda dengan mudah, aman, dan cepat.">
  <title><?= e($pageTitle ?? 'Product Manager') ?> | Product Manager</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- ── HEADER ─────────────────────────────────────────────── -->
<header class="site-header">
  <div class="header-inner">
    <a href="index.php" class="brand" aria-label="Product Manager — Beranda">
      <span class="brand-icon" aria-hidden="true">⬡</span>
      <span class="brand-name">Product<strong>Manager</strong></span>
    </a>

    <!-- Pencarian (FR: kolom pencarian produk — filter klien) -->
    <div class="header-search" role="search">
      <label for="searchInput" class="sr-only">Cari produk</label>
      <span class="search-icon" aria-hidden="true">
        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      </span>
      <input
        id="searchInput"
        type="search"
        placeholder="Cari nama atau kategori produk…"
        autocomplete="off"
        aria-label="Cari produk"
      >
    </div>

    <a href="index.php?action=create" class="btn btn-primary btn-header" id="btnTambahHeader">
      <span aria-hidden="true">+</span> Tambah Produk
    </a>
  </div>
</header>

<!-- ── MAIN ───────────────────────────────────────────────── -->
<main class="main-content" id="mainContent">

  <!-- Flash Messages -->
  <?php if (!empty($flashMessages)): ?>
    <div class="flash-container" role="alert" aria-live="polite">
      <?php foreach ($flashMessages as $type => $msgs): ?>
        <?php foreach ($msgs as $msg): ?>
          <div class="flash flash-<?= e($type) ?>">
            <span class="flash-icon"><?= $type === 'success' ? '✔' : '⚠' ?></span>
            <?= e($msg) ?>
            <button class="flash-close" aria-label="Tutup notifikasi" onclick="this.parentElement.remove()">✕</button>
          </div>
        <?php endforeach; ?>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?= $content ?>
</main>

<!-- ── FOOTER ─────────────────────────────────────────────── -->
<footer class="site-footer">
  <p>© <?= date('Y') ?> Product Manager — Tugas Product Manager</p>
</footer>

<script src="assets/js/app.js"></script>
</body>
</html>
