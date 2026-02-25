<?php

declare(strict_types=1);

function flashStartSession(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

function flashPush(string $type, string $message, string $title = ''): void
{
    flashStartSession();

    $allowedTypes = ['success', 'error', 'info', 'warning'];
    if (!in_array($type, $allowedTypes, true)) {
        $type = 'info';
    }

    if (!isset($_SESSION['flash_messages']) || !is_array($_SESSION['flash_messages'])) {
        $_SESSION['flash_messages'] = [];
    }

    $_SESSION['flash_messages'][] = [
        'type' => $type,
        'title' => trim($title),
        'message' => trim($message),
    ];

    if (count($_SESSION['flash_messages']) > 5) {
        $_SESSION['flash_messages'] = array_slice($_SESSION['flash_messages'], -5);
    }
}

/**
 * @return array<int, array{type:string,title:string,message:string}>
 */
function flashConsume(): array
{
    flashStartSession();

    $items = $_SESSION['flash_messages'] ?? [];

    if (isset($_SESSION['flash_message']) && is_array($_SESSION['flash_message'])) {
        $legacy = $_SESSION['flash_message'];
        $items[] = [
            'type' => (string)($legacy['type'] ?? 'info'),
            'title' => '',
            'message' => (string)($legacy['message'] ?? ''),
        ];
    }

    unset($_SESSION['flash_messages'], $_SESSION['flash_message']);

    $normalized = [];
    foreach ($items as $item) {
        if (!is_array($item)) {
            continue;
        }

        $type = (string)($item['type'] ?? 'info');
        if (!in_array($type, ['success', 'error', 'info', 'warning'], true)) {
            $type = 'info';
        }

        $message = trim((string)($item['message'] ?? ''));
        if ($message === '') {
            continue;
        }

        $normalized[] = [
            'type' => $type,
            'title' => trim((string)($item['title'] ?? '')),
            'message' => $message,
        ];
    }

    return $normalized;
}
