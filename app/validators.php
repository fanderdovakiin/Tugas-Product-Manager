<?php
// ============================================================
// app/validators.php — Validasi Input Produk (server-side)
// ============================================================

declare(strict_types=1);

/**
 * Validasi data produk.
 *
 * @param  array $data   Data input mentah ($_POST)
 * @param  int|null $excludeId  ID produk saat ini (untuk edit, agar nama sendiri tidak dianggap duplikat)
 * @param  PDO   $pdo    Koneksi database
 * @return array{errors: string[], clean: array}
 */
function validateProduct(array $data, ?int $excludeId, PDO $pdo): array
{
    $errors = [];
    $clean  = [];

    // ── Nama ─────────────────────────────────────────────────
    $name = trim($data['name'] ?? '');
    if ($name === '') {
        $errors['name'] = 'Nama produk wajib diisi.';
    } elseif (mb_strlen($name) < 3) {
        $errors['name'] = 'Nama produk minimal 3 karakter.';
    } elseif (mb_strlen($name) > 150) {
        $errors['name'] = 'Nama produk maksimal 150 karakter.';
    } else {
        // Cek duplikat (case-insensitive via collation DB)
        $sql = 'SELECT id FROM products WHERE name = :name';
        $params = [':name' => $name];
        if ($excludeId !== null) {
            $sql .= ' AND id != :eid';
            $params[':eid'] = $excludeId;
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        if ($stmt->fetch()) {
            $errors['name'] = 'Nama produk sudah digunakan. Masukkan nama yang berbeda.';
        }
        $clean['name'] = $name;
    }

    // ── Kategori ─────────────────────────────────────────────
    $category = trim($data['category'] ?? '');
    if ($category === '') {
        $errors['category'] = 'Kategori wajib diisi.';
    } elseif (mb_strlen($category) > 100) {
        $errors['category'] = 'Kategori maksimal 100 karakter.';
    } else {
        $clean['category'] = $category;
    }

    // ── Harga ─────────────────────────────────────────────────
    $rawPrice = trim($data['price'] ?? '');
    if ($rawPrice === '' || !is_numeric($rawPrice)) {
        $errors['price'] = 'Harga harus berupa angka.';
    } elseif ((float) $rawPrice <= 0) {
        $errors['price'] = 'Harga harus lebih besar dari 0.';
    } else {
        $clean['price'] = number_format((float) $rawPrice, 2, '.', '');
    }

    // ── Stok ──────────────────────────────────────────────────
    $rawStock = trim($data['stock'] ?? '');
    if ($rawStock === '' || !ctype_digit($rawStock)) {
        $errors['stock'] = 'Stok harus bilangan bulat dan tidak boleh negatif.';
    } else {
        $clean['stock'] = (int) $rawStock;
    }

    return ['errors' => $errors, 'clean' => $clean];
}
