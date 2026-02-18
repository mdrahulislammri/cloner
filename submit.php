<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';

function startSessionIfNeeded(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

function setFlash(string $type, string $message): void
{
    startSessionIfNeeded();
    $_SESSION['flash_message'] = ['type' => $type, 'message' => $message];
}

function redirectToContact(): never
{
    header('Location: index.php#contact');
    exit;
}

startSessionIfNeeded();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    setFlash('error', 'Invalid request method.');
    redirectToContact();
}

if (!verifyCsrfToken($_POST['csrf'] ?? null)) {
    setFlash('error', 'Security validation failed. Please try again.');
    redirectToContact();
}

$clientIp = (string)($_SERVER['REMOTE_ADDR'] ?? 'unknown');
$rateLimitKey = 'contact_rate_limit_' . hash('sha256', $clientIp);
$lastRequestAt = (int)($_SESSION[$rateLimitKey] ?? 0);
$now = time();

if ($lastRequestAt > 0 && ($now - $lastRequestAt) < 20) {
    setFlash('error', 'Please wait a few seconds before sending another message.');
    redirectToContact();
}

$name = trim((string)($_POST['name'] ?? ''));
$email = trim((string)($_POST['email'] ?? ''));
$message = trim((string)($_POST['message'] ?? ''));

if ($name === '' || $message === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    setFlash('error', 'Validation failed. Please enter valid name, email and message.');
    redirectToContact();
}

if (mb_strlen($name) > 120 || mb_strlen($email) > 120 || mb_strlen($message) > 3000) {
    setFlash('error', 'Input is too long. Please keep your message shorter.');
    redirectToContact();
}

$connection = db();
if (!$connection) {
    setFlash('error', 'Service is temporarily unavailable. Please try again later.');
    redirectToContact();
}

try {
    $stmt = $connection->prepare('INSERT INTO contact_messages (name, email, message) VALUES (:name, :email, :message)');
    $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':message' => $message,
    ]);

    $_SESSION[$rateLimitKey] = $now;
    setFlash('success', 'আপনার মেসেজ সফলভাবে পাঠানো হয়েছে। আমরা দ্রুত যোগাযোগ করবো।');
} catch (Throwable $exception) {
    setFlash('error', 'Unable to submit message right now. Please try again later.');
}

redirectToContact();
