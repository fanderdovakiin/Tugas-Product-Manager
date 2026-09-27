<?php
// ============================================================
// public/index.php — Entry Point & Simple Router
// ============================================================

declare(strict_types=1);

// Mulai sesi sebelum output apapun
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Autoload dependencies
require_once dirname(__DIR__) . '/app/helpers.php';
require_once dirname(__DIR__) . '/app/controllers/ProductController.php';

// Routing: ambil ?action= dan pastikan hanya string yang diizinkan
$allowedActions = ['', 'create', 'edit', 'delete'];
$action = $_GET['action'] ?? '';

if (!in_array($action, $allowedActions, true)) {
    flashSet('error', 'Halaman tidak ditemukan.');
    redirect('index.php');
}

$controller = new ProductController();
$controller->dispatch($action);
