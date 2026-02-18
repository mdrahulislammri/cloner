<?php

declare(strict_types=1);

const APP_NAME = 'GreenTech Boost';
const DB_HOST = '127.0.0.1';
const DB_PORT = '3306';
const DB_NAME = 'greentech_boost';
const DB_USER = 'root';
const DB_PASS = '';
const BASE_URL = '/';

function envFromFile(string $key): ?string
{
    static $cache = null;

    if ($cache === null) {
        $cache = [];
        $envPath = dirname(__DIR__) . '/.env';
        if (is_file($envPath)) {
            $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
            foreach ($lines as $line) {
                $line = trim($line);
                if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
                    continue;
                }

                [$k, $v] = explode('=', $line, 2);
                $k = trim($k);
                $v = trim($v);
                if ($k === '') {
                    continue;
                }

                if ((str_starts_with($v, '"') && str_ends_with($v, '"')) || (str_starts_with($v, "'") && str_ends_with($v, "'"))) {
                    $v = substr($v, 1, -1);
                }

                $cache[$k] = $v;
            }
        }
    }

    return $cache[$key] ?? null;
}

function appEnv(string $key, ?string $default = null): ?string
{
    $value = getenv($key);
    if ($value !== false) {
        return $value;
    }

    $fileValue = envFromFile($key);
    return $fileValue ?? $default;
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
