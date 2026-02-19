<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

function installRequirements(): array
{
    $uploadDir = dirname(__DIR__) . '/access/img/uploads';

    return [
        ['label' => 'PHP 8.1+', 'ok' => version_compare(PHP_VERSION, '8.1.0', '>=')],
        ['label' => 'PDO extension', 'ok' => extension_loaded('pdo')],
        ['label' => 'PDO MySQL extension', 'ok' => extension_loaded('pdo_mysql')],
        ['label' => 'Upload directory writable', 'ok' => is_dir($uploadDir) ? is_writable($uploadDir) : is_writable(dirname($uploadDir))],
    ];
}


function installRequirementsPassed(): bool
{
    foreach (installRequirements() as $requirement) {
        if (empty($requirement['ok'])) {
            return false;
        }
    }

    return true;
}

function isInstalled(): bool
{
    $connection = db();
    if (!$connection) {
        return false;
    }

    try {
        $stmt = $connection->prepare('SELECT setting_value FROM settings WHERE setting_key = :key LIMIT 1');
        $stmt->execute([':key' => 'app_installed']);
        $value = $stmt->fetchColumn();

        return $value === '1';
    } catch (Throwable $exception) {
        return false;
    }
}

function upsertSettingWithConnection(PDO $connection, string $key, string $value): bool
{
    $stmt = $connection->prepare('INSERT INTO settings (setting_key, setting_value) VALUES (:k, :v) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)');
    return $stmt->execute([':k' => $key, ':v' => $value]);
}

function upsertSetting(string $key, string $value): bool
{
    $connection = db();
    if (!$connection) {
        return false;
    }

    try {
        return upsertSettingWithConnection($connection, $key, $value);
    } catch (Throwable $exception) {
        return false;
    }
}


function isStrongAdminPassword(string $password): bool
{
    if (strlen($password) < 8) {
        return false;
    }

    if (!preg_match('/[A-Z]/', $password)) {
        return false;
    }

    if (!preg_match('/[a-z]/', $password)) {
        return false;
    }

    if (!preg_match('/\d/', $password)) {
        return false;
    }

    return true;
}

function normalizeIpList(string $rawList): array
{
    $items = preg_split('/[\r\n,]+/', $rawList) ?: [];
    $normalized = [];

    foreach ($items as $item) {
        $candidate = trim($item);
        if ($candidate === '') {
            continue;
        }

        if (strpos($candidate, '/') !== false) {
            [$ip, $mask] = array_pad(explode('/', $candidate, 2), 2, '');
            if (filter_var($ip, FILTER_VALIDATE_IP) && ctype_digit($mask)) {
                $maskValue = (int)$mask;
                $maxMask = str_contains($ip, ':') ? 128 : 32;
                if ($maskValue >= 0 && $maskValue <= $maxMask) {
                    $normalized[] = $ip . '/' . $maskValue;
                }
            }
            continue;
        }

        if (filter_var($candidate, FILTER_VALIDATE_IP)) {
            $normalized[] = $candidate;
        }
    }

    return array_values(array_unique($normalized));
}

function installerConnect(array $dbConfig): PDO
{
    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
        $dbConfig['host'],
        $dbConfig['port'],
        $dbConfig['name']
    );

    return new PDO($dsn, $dbConfig['user'], $dbConfig['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
}

function importSchema(PDO $connection): void
{
    $schemaPath = dirname(__DIR__) . '/database/schema.sql';
    $sql = file_get_contents($schemaPath);
    if (!is_string($sql) || trim($sql) === '') {
        throw new RuntimeException('Schema file missing or empty.');
    }

    $connection->exec($sql);
}

function writeConfigPhp(array $dbConfig, string $baseUrl): void
{
    $configPath = __DIR__ . '/config.php';
    $content = file_get_contents($configPath);
    if (!is_string($content) || $content === '') {
        throw new RuntimeException('Unable to read includes/config.php.');
    }

    $replacements = [
        'DB_HOST' => (string)$dbConfig['host'],
        'DB_PORT' => (string)$dbConfig['port'],
        'DB_NAME' => (string)$dbConfig['name'],
        'DB_USER' => (string)$dbConfig['user'],
        'DB_PASS' => (string)$dbConfig['pass'],
        'BASE_URL' => (string)$baseUrl,
    ];

    foreach ($replacements as $key => $value) {
        $escaped = str_replace(["\\", "'"], ["\\\\", "\\'"], $value);
        $pattern = "/const\s+" . preg_quote($key, '/') . "\s*=\s*'[^']*';/";
        $replacement = "const {$key} = '{$escaped}';";
        $content = preg_replace($pattern, $replacement, $content, 1) ?? $content;
    }

    $tmpPath = $configPath . '.tmp';
    if (file_put_contents($tmpPath, $content, LOCK_EX) === false) {
        throw new RuntimeException('Unable to write temporary config.php file. Check file permissions.');
    }

    if (!@rename($tmpPath, $configPath)) {
        @unlink($tmpPath);
        throw new RuntimeException('Unable to update includes/config.php. Check file permissions.');
    }
}

function upsertAdmin(PDO $connection, string $name, string $email, string $password): void
{
    $hash = password_hash($password, PASSWORD_DEFAULT);
    if ($hash === false) {
        throw new RuntimeException('Failed to hash admin password.');
    }

    $stmt = $connection->prepare('INSERT INTO admins (name, email, password_hash) VALUES (:name, :email, :hash) ON DUPLICATE KEY UPDATE name = VALUES(name), password_hash = VALUES(password_hash)');
    $stmt->execute([':name' => $name, ':email' => $email, ':hash' => $hash]);
}
