<?php
// ============================================================
// app/models/Product.php — Operasi SQL dengan PDO prepared statements
// ============================================================

declare(strict_types=1);

class Product
{
    public function __construct(private readonly PDO $pdo) {}

    // ── Read: semua produk ───────────────────────────────────
    public function getAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM products ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }

    // ── Read: satu produk berdasarkan ID ─────────────────────
    public function findById(int $id): array|false
    {
        $stmt = $this->pdo->prepare('SELECT * FROM products WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // ── Create ───────────────────────────────────────────────
    /**
     * @return true|string  true jika berhasil, string pesan error jika duplikat/gagal
     */
    public function create(string $name, string $category, string $price, int $stock): true|string
    {
        try {
            $stmt = $this->pdo->prepare(
                'INSERT INTO products (name, category, price, stock) VALUES (:name, :category, :price, :stock)'
            );
            $stmt->execute([
                ':name'     => $name,
                ':category' => $category,
                ':price'    => $price,
                ':stock'    => $stock,
            ]);
            return true;
        } catch (PDOException $e) {
            // Kode 23000 = integrity constraint violation (termasuk UNIQUE)
            if ($e->getCode() === '23000') {
                return 'Nama produk sudah digunakan. Masukkan nama yang berbeda.';
            }
            error_log('[ProductManager] Create Error: ' . $e->getMessage());
            return 'Gagal menyimpan produk. Silakan coba lagi.';
        }
    }

    // ── Update ───────────────────────────────────────────────
    public function update(int $id, string $name, string $category, string $price, int $stock): true|string
    {
        try {
            $stmt = $this->pdo->prepare(
                'UPDATE products SET name = :name, category = :category,
                 price = :price, stock = :stock WHERE id = :id'
            );
            $stmt->execute([
                ':name'     => $name,
                ':category' => $category,
                ':price'    => $price,
                ':stock'    => $stock,
                ':id'       => $id,
            ]);
            return true;
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                return 'Nama produk sudah digunakan. Masukkan nama yang berbeda.';
            }
            error_log('[ProductManager] Update Error: ' . $e->getMessage());
            return 'Gagal memperbarui produk. Silakan coba lagi.';
        }
    }

    // ── Delete ───────────────────────────────────────────────
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM products WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount() > 0;
    }
}
