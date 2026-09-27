<?php
// ============================================================
// app/views/products/index.php — Daftar Produk (FR-01)
// Dipanggil dari ProductController::index()
// ============================================================

declare(strict_types=1);

$pageTitle = 'Daftar Produk';

ob_start();
?>

<!-- Toolbar: judul & jumlah produk -->
<section class="toolbar" aria-label="Kontrol produk">
  <div class="toolbar-left">
    <h1 class="page-title">Katalog Produk</h1>
    <span class="product-count" id="productCount" aria-live="polite">
      <?= count($products) ?> produk
    </span>
  </div>
</section>

<!-- Filter Kategori -->
<?php
$categories = array_unique(array_column($products, 'category'));
sort($categories);
?>
<?php if ($categories !== []): ?>
<nav class="category-nav" aria-label="Filter kategori">
  <button class="cat-pill cat-pill--active" data-cat="all" id="catAll">Semua</button>
  <?php foreach ($categories as $cat): ?>
    <button class="cat-pill" data-cat="<?= e($cat) ?>"><?= e($cat) ?></button>
  <?php endforeach; ?>
</nav>
<?php endif; ?>

<!-- Grid Produk -->
<?php if ($products === []): ?>
  <!-- Empty State -->
  <div class="empty-state" id="emptyState" role="status">
    <div class="empty-icon" aria-hidden="true">
      <svg xmlns="http://www.w3.org/2000/svg" width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><polyline points="16 2 12 7 8 2"/></svg>
    </div>
    <h2>Belum ada produk</h2>
    <p>Tambahkan produk pertama untuk mulai mengelola katalog Anda.</p>
    <a href="index.php?action=create" class="btn btn-primary" id="btnTambahEmpty">+ Tambah Produk</a>
  </div>
<?php else: ?>
  <div class="product-grid" id="productGrid" role="list">
    <?php foreach ($products as $p): ?>
      <article class="product-card" role="listitem"
               data-name="<?= e(strtolower($p['name'])) ?>"
               data-category="<?= e(strtolower($p['category'])) ?>">

        <!-- Placeholder gambar produk -->
        <div class="card-image" aria-hidden="true">
          <span class="card-placeholder-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
          </span>
          <span class="card-category-badge"><?= e($p['category']) ?></span>
        </div>

        <div class="card-body">
          <h2 class="card-name" title="<?= e($p['name']) ?>"><?= e($p['name']) ?></h2>

          <p class="card-price"><?= e(formatRupiah($p['price'])) ?></p>

          <div class="card-meta">
            <?php $stockNum = (int) $p['stock']; ?>
            <span class="card-stock <?= $stockNum === 0 ? 'stock-empty' : ($stockNum <= 5 ? 'stock-low' : 'stock-ok') ?>">
              <?php if ($stockNum === 0): ?>
                Habis
              <?php elseif ($stockNum <= 5): ?>
                Stok: <?= e($stockNum) ?>
              <?php else: ?>
                Stok: <?= e($stockNum) ?>
              <?php endif; ?>
            </span>
          </div>
        </div>

        <div class="card-actions">
          <a href="index.php?action=edit&id=<?= e($p['id']) ?>"
             class="btn btn-edit"
             id="btnEdit<?= e($p['id']) ?>"
             aria-label="Edit produk <?= e($p['name']) ?>">
            ✏ Edit
          </a>

          <!-- Hapus via POST + CSRF -->
          <form method="POST" action="index.php?action=delete"
                class="form-delete"
                data-product-name="<?= e($p['name']) ?>"
                id="formDelete<?= e($p['id']) ?>">
            <?= csrfField() ?>
            <input type="hidden" name="id" value="<?= e($p['id']) ?>">
            <button type="submit"
                    class="btn btn-delete"
                    id="btnDelete<?= e($p['id']) ?>"
                    aria-label="Hapus produk <?= e($p['name']) ?>">
              Hapus
            </button>
          </form>
        </div>
      </article>
    <?php endforeach; ?>
  </div>

  <!-- No result after search/filter -->
  <div class="empty-state empty-state--hidden" id="noResult" role="status" aria-live="polite">
    <div class="empty-icon" aria-hidden="true">
      <svg xmlns="http://www.w3.org/2000/svg" width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    </div>
    <h2>Produk tidak ditemukan</h2>
    <p>Coba kata kunci atau kategori lain.</p>
  </div>
<?php endif; ?>

<?php
$content = ob_get_clean();
require dirname(__DIR__) . '/layout.php';
