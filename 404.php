<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';

$basePath = rtrim((string)appEnv('BASE_URL', BASE_URL), '/');
$homeUrl = ($basePath === '' ? '' : $basePath) . '/index.php';
http_response_code(404);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>404 Not Found</title>
  <meta name="robots" content="noindex, nofollow">
  <link rel="stylesheet" href="access/css/styles.css">
</head>
<body class="text-slate-800 bg-slate-50">
  <main class="min-h-screen grid place-items-center px-4">
    <section class="glass-card p-8 md:p-10 text-center max-w-xl w-full">
      <p class="text-sm font-bold text-emerald-700">ERROR 404</p>
      <h1 class="mt-2 text-3xl md:text-4xl font-black text-slate-900">Page Not Found</h1>
      <p class="mt-4 text-slate-600">The page you requested could not be found. It may have been moved or deleted.</p>
      <div class="mt-7 flex justify-center gap-3">
        <a href="<?= htmlspecialchars($homeUrl) ?>" class="btn-primary">Go to Home</a>
        <a href="<?= htmlspecialchars($homeUrl . "#contact") ?>" class="btn-outline">Contact Support</a>
      </div>
    </section>
  </main>
</body>
</html>
