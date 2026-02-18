<?php
declare(strict_types=1);
http_response_code(500);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>500 Server Error</title>
  <meta name="robots" content="noindex, nofollow">
  <link rel="stylesheet" href="access/css/styles.css">
</head>
<body class="text-slate-800 bg-slate-50">
  <main class="min-h-screen grid place-items-center px-4">
    <section class="glass-card p-8 md:p-10 text-center max-w-xl w-full">
      <p class="text-sm font-bold text-emerald-700">ERROR 500</p>
      <h1 class="mt-2 text-3xl md:text-4xl font-black text-slate-900">Internal Server Error</h1>
      <p class="mt-4 text-slate-600">Something went wrong on our side. Please try again in a few minutes.</p>
      <div class="mt-7 flex justify-center gap-3">
        <a href="index.php" class="btn-primary">Return Home</a>
        <a href="index.php#contact" class="btn-outline">Report Issue</a>
      </div>
    </section>
  </main>
</body>
</html>
