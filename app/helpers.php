<?php
// ============================================================
// app/helpers.php — Helper: escape, redirect, flash, CSRF
// ============================================================

declare(strict_types=1);

// ── Pastikan sesi aktif ──────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ── Escape HTML output ───────────────────────────────────────
function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// ── Redirect HTTP ────────────────────────────────────────────
function redirect(string $url): never
{
    header('Location: ' . $url);
    exit();
}

// ── Flash Messages ───────────────────────────────────────────
function flashSet(string $type, string $message): void
{
    $_SESSION['flash'][$type][] = $message;
}

function flashGet(): array
{
    $messages = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $messages;
}

// ── CSRF Token ───────────────────────────────────────────────
function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrfField(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrfToken()) . '">';
}

function csrfVerify(): bool
{
    $token      = $_POST['csrf_token'] ?? '';
    $sessionTok = $_SESSION['csrf_token'] ?? '';
    return $sessionTok !== '' && hash_equals($sessionTok, $token);
}

// ── Format Rupiah ────────────────────────────────────────────
function formatRupiah(mixed $value): string
{
    return 'Rp ' . number_format((float) $value, 0, ',', '.');
}

// ── Ambil URL dasar (untuk redirect) ─────────────────────────
function baseUrl(string $path = ''): string
{
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $base   = '';

    // Hitung sub-direktori relatif terhadap document root
    $scriptDir  = str_replace('\\', '/', dirname($_SERVER['SCRIPT_FILENAME'] ?? ''));
    $docRoot    = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] ?? '');
    if ($docRoot !== '' && str_starts_with($scriptDir, $docRoot)) {
        $base = rtrim(substr($scriptDir, strlen($docRoot)), '/');
    }

    return $scheme . '://' . $host . $base . '/' . ltrim($path, '/');
}
