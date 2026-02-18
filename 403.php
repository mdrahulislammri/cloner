<?php
declare(strict_types=1);
http_response_code(403);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>403 Forbidden</title>
  <meta name="robots" content="noindex, nofollow">
  <link rel="stylesheet" href="access/css/styles.css">
</head>
<body class="text-slate-800 bg-slate-50">
  <main class="min-h-screen grid place-items-center px-4">
    <section class="glass-card p-8 md:p-10 text-center max-w-xl w-full">
      <p class="text-sm font-bold text-emerald-700">ERROR 403</p>
      <h1 class="mt-2 text-3xl md:text-4xl font-black text-slate-900">Access Forbidden</h1>
      <p class="mt-4 text-slate-600">You don’t have permission to access this page. Please go back to the homepage.</p>
      <div class="mt-7 flex justify-center gap-3">
        <a href="index.php" class="btn-primary">Go to Home</a>
        <a href="admin/login.php" class="btn-outline">Admin Login</a>
      </div>
    </section>
  </main>
</body>
</html>
