<?php
// ============================================================
// app/database/connection.php — PDO Connection (singleton)
// ============================================================

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';

function getConnection(): PDO
{
    static $pdo = null;

    if ($pdo !== null) {
        return $pdo;
    }

    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
        DB_HOST, DB_PORT, DB_NAME
    );

    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    } catch (PDOException $e) {
        // Catat error ke log; jangan tampilkan stack trace ke browser.
        error_log('[ProductManager] DB Connection Error: ' . $e->getMessage());
        http_response_code(500);
        exit('Koneksi ke database gagal. Periksa konfigurasi .env dan pastikan MySQL berjalan.');
    }

    return $pdo;
}
