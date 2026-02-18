<?php

declare(strict_types=1);

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/install.php';

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



function getClientIpAddress(): string
{
    $candidates = [
        (string)($_SERVER['HTTP_CF_CONNECTING_IP'] ?? ''),
        (string)($_SERVER['HTTP_X_FORWARDED_FOR'] ?? ''),
        (string)($_SERVER['HTTP_X_REAL_IP'] ?? ''),
        (string)($_SERVER['REMOTE_ADDR'] ?? ''),
    ];

    foreach ($candidates as $candidate) {
        if ($candidate === '') {
            continue;
        }

        foreach (explode(',', $candidate) as $part) {
            $ip = trim($part);
            if ($ip !== '' && filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }
    }

    return '0.0.0.0';
}

function ipMatchesCidr(string $ip, string $cidr): bool
{
    [$subnet, $mask] = array_pad(explode('/', $cidr, 2), 2, null);
    if ($subnet === null || $mask === null || !is_numeric($mask)) {
        return false;
    }

    $ipBin = @inet_pton($ip);
    $subnetBin = @inet_pton($subnet);
    if ($ipBin === false || $subnetBin === false || strlen($ipBin) !== strlen($subnetBin)) {
        return false;
    }

    $maskBits = (int)$mask;
    $maxBits = strlen($ipBin) * 8;
    if ($maskBits < 0 || $maskBits > $maxBits) {
        return false;
    }

    $fullBytes = intdiv($maskBits, 8);
    $remainingBits = $maskBits % 8;

    if ($fullBytes > 0 && substr($ipBin, 0, $fullBytes) !== substr($subnetBin, 0, $fullBytes)) {
        return false;
    }

    if ($remainingBits === 0) {
        return true;
    }

    $maskByte = (0xFF << (8 - $remainingBits)) & 0xFF;
    return ((ord($ipBin[$fullBytes]) & $maskByte) === (ord($subnetBin[$fullBytes]) & $maskByte));
}

function getAdminIpWhitelist(): array
{
    $connection = db();

    if ($connection) {
        try {
            $stmt = $connection->prepare('SELECT setting_value FROM settings WHERE setting_key = :key LIMIT 1');
            $stmt->execute([':key' => 'admin_ip_whitelist']);
            $stored = $stmt->fetchColumn();
            if (is_string($stored) && trim($stored) !== '') {
                return normalizeIpList($stored);
            }
        } catch (Throwable $exception) {
            // Fallback to env-based whitelist below.
        }
    }

    $raw = trim((string)appEnv('ADMIN_IP_WHITELIST', '127.0.0.1,::1'));
    return normalizeIpList($raw);
}

function isIpWhitelistedForAdmin(string $ip): bool
{
    $whitelist = getAdminIpWhitelist();
    if ($whitelist === []) {
        return false;
    }

    foreach ($whitelist as $allowed) {
        if (strpos($allowed, '/') !== false) {
            if (ipMatchesCidr($ip, $allowed)) {
                return true;
            }
            continue;
        }

        if ($ip === $allowed) {
            return true;
        }
    }

    return false;
}

function enforceAdminIpWhitelist(): void
{
    if (!isInstalled()) {
        header('Location: ' . rtrim((string)appEnv('BASE_URL', BASE_URL), '/') . '/install.php');
        exit;
    }

    $ip = getClientIpAddress();
    if (isIpWhitelistedForAdmin($ip)) {
        return;
    }

    http_response_code(403);
    $errorPage = __DIR__ . '/../403.php';
    if (is_file($errorPage)) {
        require $errorPage;
    } else {
        echo '403 Forbidden';
    }
    exit;
}

function isAdminLoggedIn(): bool
{
    startSecureSession();
    return !empty($_SESSION['admin_id']);
}

function requireAdmin(): void
{
    enforceAdminIpWhitelist();

    if (!isAdminLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function attemptAdminLogin(string $email, string $password): bool
{
    enforceAdminIpWhitelist();
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
