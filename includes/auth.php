<?php

declare(strict_types=1);

require_once __DIR__ . '/db.php';

function startSecureSession(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => isset($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

function isAdminLoggedIn(): bool
{
    startSecureSession();
    return !empty($_SESSION['admin_id']);
}

function requireAdmin(): void
{
    if (!isAdminLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function attemptAdminLogin(string $email, string $password): bool
{
    startSecureSession();
    $admin = fetchOneRow('SELECT id, name, password_hash FROM admins WHERE email = :email LIMIT 1', [':email' => $email]);

    if (!$admin || !password_verify($password, $admin['password_hash'])) {
        return false;
    }

    session_regenerate_id(true);
    $_SESSION['admin_id'] = (int)$admin['id'];
    $_SESSION['admin_name'] = $admin['name'];

    return true;
}

function adminLogout(): void
{
    startSecureSession();
    $_SESSION = [];
    session_destroy();
}
