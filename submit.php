<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';
redirectToInstallerIfNeeded();

require_once __DIR__ . '/includes/db.php';

function redirectToContact(): never
{
    header('Location: index.php#contact');
    exit;
}

flashStartSession();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    flashPush('error', 'Invalid request method.', 'Request failed');
    redirectToContact();
}

if (!verifyCsrfToken($_POST['csrf'] ?? null)) {
    flashPush('error', 'Security validation failed. Please try again.', 'Security check');
    redirectToContact();
}

$clientIp = (string)($_SERVER['REMOTE_ADDR'] ?? 'unknown');
$rateLimitKey = 'contact_rate_limit_' . hash('sha256', $clientIp);
$lastRequestAt = (int)($_SESSION[$rateLimitKey] ?? 0);
$now = time();

if ($lastRequestAt > 0 && ($now - $lastRequestAt) < 20) {
    flashPush('warning', 'Please wait a few seconds before sending another message.', 'Too many attempts');
    redirectToContact();
}

$name = trim((string)($_POST['name'] ?? ''));
$email = trim((string)($_POST['email'] ?? ''));
$message = trim((string)($_POST['message'] ?? ''));

if ($name === '' || $message === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    flashPush('error', 'Validation failed. Please enter valid name, email and message.', 'Invalid input');
    redirectToContact();
}

if (mb_strlen($name) > 120 || mb_strlen($email) > 120 || mb_strlen($message) > 3000) {
    flashPush('warning', 'Input is too long. Please keep your message shorter.', 'Input limit');
    redirectToContact();
}

$connection = db();
if (!$connection) {
    flashPush('error', 'Service is temporarily unavailable. Please try again later.', 'Server busy');
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
    flashPush('success', 'আপনার মেসেজ সফলভাবে পাঠানো হয়েছে। আমরা দ্রুত যোগাযোগ করবো।', 'Message sent');
} catch (Throwable $exception) {
    flashPush('error', 'Unable to submit message right now. Please try again later.', 'Submit failed');
}

redirectToContact();
