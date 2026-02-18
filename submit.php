<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verifyCsrfToken($_POST['csrf'] ?? null)) {
    http_response_code(403);
    exit('Invalid request');
}

$name = trim((string)($_POST['name'] ?? ''));
$email = trim((string)($_POST['email'] ?? ''));
$message = trim((string)($_POST['message'] ?? ''));

if ($name === '' || $message === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    exit('Validation failed');
}

$connection = db();
if ($connection) {
    $stmt = $connection->prepare('INSERT INTO contact_messages (name, email, message) VALUES (:name, :email, :message)');
    $stmt->execute([':name' => $name, ':email' => $email, ':message' => $message]);
}

header('Location: index.php#contact');
