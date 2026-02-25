<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

function db(): ?PDO
{
    static $pdo = false;

    if ($pdo !== false) {
        return $pdo;
    }

    $host = appEnv('DB_HOST', DB_HOST);
    $port = appEnv('DB_PORT', DB_PORT);
    $name = appEnv('DB_NAME', DB_NAME);
    $user = appEnv('DB_USER', DB_USER);
    $pass = appEnv('DB_PASS', DB_PASS);

    $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $host, $port, $name);

    try {
        $pdo = new PDO($dsn, (string)$user, (string)$pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } catch (Throwable $exception) {
        $pdo = null;
    }

    return $pdo;
}

function fetchAllRows(string $query, array $params = []): array
{
    $connection = db();
    if (!$connection) {
        return [];
    }

    $statement = $connection->prepare($query);
    $statement->execute($params);

    return $statement->fetchAll();
}

function fetchOneRow(string $query, array $params = []): ?array
{
    $connection = db();
    if (!$connection) {
        return null;
    }

    $statement = $connection->prepare($query);
    $statement->execute($params);

    $row = $statement->fetch();

    return $row ?: null;
}
