<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';

startSecureSession();
enforceAdminIpWhitelist();
$currentIp = getClientIpAddress();
$whitelist = getAdminIpWhitelist();
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf'] ?? null)) {
        $error = 'Invalid CSRF token';
    } else {
        $email = trim((string)($_POST['email'] ?? ''));
        $password = (string)($_POST['password'] ?? '');
        if (attemptAdminLogin($email, $password)) {
            header('Location: dashboard.php');
            exit;
        }
        $error = 'ইমেইল বা পাসওয়ার্ড ভুল';
    }
}
?>
<!doctype html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><script src="https://cdn.tailwindcss.com"></script></head>
<body class="min-h-screen bg-green-50 grid place-items-center">
<form method="post" class="bg-white p-7 rounded-xl shadow w-full max-w-sm space-y-3">
<h1 class="text-2xl font-bold text-green-700">Admin Login</h1>
<?php if ($error): ?><p class="text-red-600 text-sm"><?= htmlspecialchars($error) ?></p><?php endif; ?>
<?php if (isset($_GET['installed'])): ?><p class="text-green-700 text-sm bg-green-50 p-2 rounded">Installer complete. You can login now.</p><?php endif; ?>
<p class="text-xs text-slate-500">Your IP: <strong><?= htmlspecialchars($currentIp) ?></strong></p>
<p class="text-xs text-slate-500">Allowed IPs: <?= htmlspecialchars(implode(', ', $whitelist)) ?></p>
<input name="email" type="email" placeholder="Email" class="w-full border p-2 rounded" required>
<input name="password" type="password" placeholder="Password" class="w-full border p-2 rounded" required>
<input type="hidden" name="csrf" value="<?= htmlspecialchars(csrfToken()) ?>">
<button class="w-full bg-green-600 text-white rounded p-2">Login</button>
</form></body></html>
