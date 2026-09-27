<?php
// ============================================================
// app/controllers/ProductController.php
// Menangani seluruh aksi CRUD produk
// ============================================================

declare(strict_types=1);

require_once dirname(__DIR__) . '/helpers.php';
require_once dirname(__DIR__) . '/database/connection.php';
require_once dirname(__DIR__) . '/models/Product.php';
require_once dirname(__DIR__) . '/validators.php';

class ProductController
{
    private Product $model;

    public function __construct()
    {
        $this->model = new Product(getConnection());
    }

    // ── Enum-like action dispatcher ──────────────────────────
    public function dispatch(string $action): void
    {
        match ($action) {
            'create'  => $this->create(),
            'edit'    => $this->edit(),
            'delete'  => $this->delete(),
            default   => $this->index(),
        };
    }

    // ── FR-01: Daftar produk ─────────────────────────────────
    private function index(): void
    {
        $products     = $this->model->getAll();
        $flashMessages = flashGet();
        require dirname(__DIR__) . '/views/products/index.php';
    }

    // ── FR-02: Tampilkan form tambah ─────────────────────────
    private function create(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleCreate();
            return;
        }
        $old          = [];
        $errors       = [];
        $product      = [];
        $flashMessages = flashGet();
        require dirname(__DIR__) . '/views/products/form.php';
    }

    // ── Proses POST tambah produk ────────────────────────────
    private function handleCreate(): void
    {
        // CSRF
        if (!csrfVerify()) {
            flashSet('error', 'Token keamanan tidak valid. Silakan coba lagi.');
            redirect('index.php?action=create');
        }

        $pdo    = getConnection();
        $result = validateProduct($_POST, null, $pdo);
        $errors = $result['errors'];
        $clean  = $result['clean'];

        if ($errors !== []) {
            $old          = $_POST;
            $product      = [];
            $flashMessages = flashGet();
            require dirname(__DIR__) . '/views/products/form.php';
            return;
        }

        $saveResult = $this->model->create(
            $clean['name'],
            $clean['category'],
            $clean['price'],
            $clean['stock']
        );

        if ($saveResult === true) {
            flashSet('success', 'Produk "' . htmlspecialchars($clean['name'], ENT_QUOTES, 'UTF-8') . '" berhasil ditambahkan.');
            redirect('index.php');
        } else {
            $errors['name'] = $saveResult;
            $old = $_POST;
            $flashMessages = [];
            require dirname(__DIR__) . '/views/products/form.php';
        }
    }

    // ── FR-05: Tampilkan form edit ───────────────────────────
    private function edit(): void
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

        if (!$id) {
            flashSet('error', 'ID produk tidak valid.');
            redirect('index.php');
        }

        $product = $this->model->findById((int) $id);
        if (!$product) {
            flashSet('error', 'Produk tidak ditemukan.');
            redirect('index.php');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleUpdate((int) $id, $product);
            return;
        }

        $old          = $product;
        $errors       = [];
        $flashMessages = flashGet();
        require dirname(__DIR__) . '/views/products/form.php';
    }

    // ── Proses POST update produk ────────────────────────────
    private function handleUpdate(int $id, array $product): void
    {
        if (!csrfVerify()) {
            flashSet('error', 'Token keamanan tidak valid. Silakan coba lagi.');
            redirect('index.php?action=edit&id=' . $id);
        }

        $pdo    = getConnection();
        $result = validateProduct($_POST, $id, $pdo);
        $errors = $result['errors'];
        $clean  = $result['clean'];

        if ($errors !== []) {
            $old          = $_POST;
            $flashMessages = [];
            // $product sudah di-set oleh caller (handleUpdate)
            require dirname(__DIR__) . '/views/products/form.php';
            return;
        }

        $saveResult = $this->model->update(
            $id,
            $clean['name'],
            $clean['category'],
            $clean['price'],
            $clean['stock']
        );

        if ($saveResult === true) {
            flashSet('success', 'Produk "' . htmlspecialchars($clean['name'], ENT_QUOTES, 'UTF-8') . '" berhasil diperbarui.');
            redirect('index.php');
        } else {
            $errors['name'] = $saveResult;
            $old = $_POST;
            $flashMessages = [];
            // $product masih valid (di-set oleh caller)
            require dirname(__DIR__) . '/views/products/form.php';
        }
    }

    // ── FR-06: Hapus produk (POST + CSRF) ───────────────────
    private function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            flashSet('error', 'Aksi hapus harus melalui metode POST.');
            redirect('index.php');
        }

        if (!csrfVerify()) {
            flashSet('error', 'Token keamanan tidak valid.');
            redirect('index.php');
        }

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if (!$id) {
            flashSet('error', 'ID produk tidak valid.');
            redirect('index.php');
        }

        $deleted = $this->model->delete((int) $id);
        if ($deleted) {
            flashSet('success', 'Produk berhasil dihapus.');
        } else {
            flashSet('error', 'Produk tidak ditemukan atau sudah dihapus.');
        }

        redirect('index.php');
    }
}
