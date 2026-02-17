<?php

declare(strict_types=1);

const APP_NAME = 'GreenTech Boost';
const DB_HOST = '127.0.0.1';
const DB_PORT = '3306';
const DB_NAME = 'greentech_boost';
const DB_USER = 'root';
const DB_PASS = '';
const BASE_URL = '/';

function appEnv(string $key, ?string $default = null): ?string
{
    $value = getenv($key);
    return $value === false ? $default : $value;
}

function csrfToken(): string
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function verifyCsrfToken(?string $token): bool
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    return is_string($token) && isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
