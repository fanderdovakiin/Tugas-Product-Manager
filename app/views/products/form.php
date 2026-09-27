<?php
// ============================================================
// app/views/products/form.php — Form Tambah / Edit (FR-02, FR-05)
// Variabel yang harus ada dari controller:
//   $old    array  (nilai lama / data produk)
//   $errors array  (pesan error per field)
//   $product array (produk yang diedit, atau [])
// ============================================================

declare(strict_types=1);

$isEdit    = isset($product) && !empty($product['id']);
$pageTitle = $isEdit ? 'Edit Produk' : 'Tambah Produk';
$formAction = $isEdit
    ? 'index.php?action=edit&id=' . e($product['id'])
    : 'index.php?action=create';

// Ambil nilai lama atau nilai produk yang diedit
$val = function (string $key) use ($old, $product): string {
    if (isset($old[$key])) return htmlspecialchars((string) $old[$key], ENT_QUOTES, 'UTF-8');
    if (isset($product[$key])) return htmlspecialchars((string) $product[$key], ENT_QUOTES, 'UTF-8');
    return '';
};

ob_start();
?>

<section class="form-section">
  <div class="form-card">
    <div class="form-header">
      <a href="index.php" class="btn-back" aria-label="Kembali ke daftar produk">← Kembali</a>
      <h1 class="form-title"><?= e($pageTitle) ?></h1>
    </div>

    <form method="POST"
          action="<?= $formAction ?>"
          novalidate
          id="productForm"
          aria-label="Form <?= e($pageTitle) ?>">
      <?= csrfField() ?>

      <!-- Nama -->
      <div class="form-group <?= isset($errors['name']) ? 'has-error' : '' ?>">
        <label for="inputName" class="form-label">Nama Produk <span class="required" aria-hidden="true">*</span></label>
        <input
          type="text"
          id="inputName"
          name="name"
          class="form-control"
          value="<?= $val('name') ?>"
          required
          minlength="3"
          maxlength="150"
          placeholder="Masukkan nama produk…"
          aria-required="true"
          aria-describedby="nameError"
          autocomplete="off"
        >
        <?php if (isset($errors['name'])): ?>
          <p class="form-error" id="nameError" role="alert"><?= e($errors['name']) ?></p>
        <?php endif; ?>
      </div>

      <!-- Kategori -->
      <div class="form-group <?= isset($errors['category']) ? 'has-error' : '' ?>">
        <label for="inputCategory" class="form-label">Kategori <span class="required" aria-hidden="true">*</span></label>
        <input
          type="text"
          id="inputCategory"
          name="category"
          class="form-control"
          value="<?= $val('category') ?>"
          required
          maxlength="100"
          placeholder="Contoh: Elektronik, Pakaian, Makanan…"
          aria-required="true"
          aria-describedby="categoryError"
          autocomplete="off"
        >
        <?php if (isset($errors['category'])): ?>
          <p class="form-error" id="categoryError" role="alert"><?= e($errors['category']) ?></p>
        <?php endif; ?>
      </div>

      <!-- Harga -->
      <div class="form-group <?= isset($errors['price']) ? 'has-error' : '' ?>">
        <label for="inputPrice" class="form-label">Harga (Rp) <span class="required" aria-hidden="true">*</span></label>
        <div class="input-prefix-wrapper">
          <span class="input-prefix" aria-hidden="true">Rp</span>
          <input
            type="number"
            id="inputPrice"
            name="price"
            class="form-control"
            value="<?= $val('price') ?>"
            required
            min="1"
            step="any"
            placeholder="Contoh: 150000"
            aria-required="true"
            aria-describedby="priceError"
          >
        </div>
        <?php if (isset($errors['price'])): ?>
          <p class="form-error" id="priceError" role="alert"><?= e($errors['price']) ?></p>
        <?php endif; ?>
      </div>

      <!-- Stok -->
      <div class="form-group <?= isset($errors['stock']) ? 'has-error' : '' ?>">
        <label for="inputStock" class="form-label">Stok <span class="required" aria-hidden="true">*</span></label>
        <input
          type="number"
          id="inputStock"
          name="stock"
          class="form-control"
          value="<?= $val('stock') ?>"
          required
          min="0"
          step="1"
          placeholder="Contoh: 50"
          aria-required="true"
          aria-describedby="stockError"
        >
        <?php if (isset($errors['stock'])): ?>
          <p class="form-error" id="stockError" role="alert"><?= e($errors['stock']) ?></p>
        <?php endif; ?>
      </div>

      <div class="form-actions">
        <a href="index.php" class="btn btn-secondary" id="btnBatal">Batal</a>
        <button type="submit" class="btn btn-primary" id="btnSimpan">
          <?= $isEdit ? 'Perbarui Produk' : 'Simpan Produk' ?>
        </button>
      </div>
    </form>
  </div>
</section>

<?php
$content = ob_get_clean();
require dirname(__DIR__) . '/layout.php';
