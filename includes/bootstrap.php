<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/install.php';
require_once __DIR__ . '/toast.php';

function installUrl(): string
{
    return rtrim((string)appEnv('BASE_URL', BASE_URL), '/') . '/install/index.php';
}

function isInstallRoute(): bool
{
    $script = str_replace('\\', '/', (string)($_SERVER['SCRIPT_NAME'] ?? ''));
    return str_ends_with($script, '/install/index.php') || $script === '/install/index.php';
}

function redirectToInstallerIfNeeded(): void
{
    if (isInstallRoute() || isInstalled()) {
        return;
    }

    header('Location: ' . installUrl());
    exit;
}
