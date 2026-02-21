<?php

declare(strict_types=1);

function adminNavItems(): array
{
    return [
        'dashboard' => ['label' => 'Dashboard', 'href' => 'dashboard.php', 'icon' => '🏠'],
        'services' => ['label' => 'Services', 'href' => 'services.php', 'icon' => '🧩'],
        'portfolio' => ['label' => 'Portfolio', 'href' => 'portfolio.php', 'icon' => '🖼️'],
        'testimonials' => ['label' => 'Testimonials', 'href' => 'testimonials.php', 'icon' => '⭐'],
        'settings' => ['label' => 'Settings', 'href' => 'settings.php', 'icon' => '⚙️'],
        'messages' => ['label' => 'Messages', 'href' => 'messages.php', 'icon' => '✉️'],
        'monitoring' => ['label' => 'Monitoring', 'href' => 'monitoring.php', 'icon' => '📊'],
    ];
}

function adminLayoutStart(string $title, string $active = ''): void
{
    $items = adminNavItems();
    ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($title) ?> | Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-slate-800">
  <div class="min-h-screen md:flex">
    <div data-admin-backdrop class="fixed inset-0 bg-slate-900/35 z-30 hidden md:hidden"></div>

    <aside data-admin-sidebar class="fixed md:static inset-y-0 left-0 z-40 w-72 bg-emerald-900 text-emerald-50 p-4 transform -translate-x-full md:translate-x-0 transition-transform duration-200 ease-out">
      <div class="flex items-center justify-between md:justify-start gap-2 mb-6">
        <h1 class="text-lg font-bold">Admin Panel</h1>
        <button type="button" data-admin-close class="md:hidden text-2xl leading-none">×</button>
      </div>
      <nav class="space-y-1">
        <?php foreach ($items as $key => $item): ?>
          <?php $isActive = $active === $key; ?>
          <a href="<?= htmlspecialchars($item['href']) ?>" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold <?= $isActive ? 'bg-emerald-700 text-white' : 'text-emerald-100 hover:bg-emerald-800' ?>">
            <span><?= htmlspecialchars($item['icon']) ?></span>
            <span><?= htmlspecialchars($item['label']) ?></span>
          </a>
        <?php endforeach; ?>
      </nav>
      <a href="logout.php" class="mt-6 inline-flex w-full items-center justify-center rounded-lg bg-red-600 px-3 py-2 text-sm font-bold text-white hover:bg-red-700">Logout</a>
    </aside>

    <main class="flex-1 md:ml-0 p-4 md:p-6">
      <div class="mb-4 md:hidden">
        <button type="button" data-admin-open class="rounded-lg border border-emerald-200 bg-white px-3 py-2 text-sm font-semibold text-emerald-700">☰ Menu</button>
      </div>
      <div class="max-w-6xl mx-auto space-y-4">
        <h2 class="text-2xl md:text-3xl font-extrabold text-emerald-800"><?= htmlspecialchars($title) ?></h2>
<?php
}

function adminLayoutEnd(): void
{
    ?>
      </div>
    </main>
  </div>

  <script src="../access/javascript/admin-confirm.js"></script>
  <script>
    (function () {
      const sidebar = document.querySelector('[data-admin-sidebar]');
      const openBtn = document.querySelector('[data-admin-open]');
      const closeBtn = document.querySelector('[data-admin-close]');
      const backdrop = document.querySelector('[data-admin-backdrop]');
      if (!sidebar) return;

      const open = () => {
        sidebar.classList.remove('-translate-x-full');
        if (backdrop) backdrop.classList.remove('hidden');
      };
      const close = () => {
        sidebar.classList.add('-translate-x-full');
        if (backdrop) backdrop.classList.add('hidden');
      };

      openBtn?.addEventListener('click', open);
      closeBtn?.addEventListener('click', close);
      backdrop?.addEventListener('click', close);
    })();
  </script>
</body>
</html>
<?php
}
