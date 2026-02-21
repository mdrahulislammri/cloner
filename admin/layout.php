<?php

declare(strict_types=1);

function adminNavItems(): array
{
    return [
        'dashboard'    => ['label' => 'Dashboard', 'href' => 'dashboard.php', 'icon' => '🏠'],
        'services'     => ['label' => 'Services', 'href' => 'services.php', 'icon' => '🧩'],
        'portfolio'    => ['label' => 'Portfolio', 'href' => 'portfolio.php', 'icon' => '🖼️'],
        'testimonials' => ['label' => 'Testimonials', 'href' => 'testimonials.php', 'icon' => '⭐'],
        'settings'     => ['label' => 'Settings', 'href' => 'settings.php', 'icon' => '⚙️'],
        'messages'     => ['label' => 'Messages', 'href' => 'messages.php', 'icon' => '✉️'],
        'monitoring'   => ['label' => 'Monitoring', 'href' => 'monitoring.php', 'icon' => '📊'],
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
  <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
  <style>
    /* Grid Line Background */
    .bg-grid-line {
      background-color: #ffffff;
      background-image: 
        linear-gradient(to right, #f1f5f9 1px, transparent 1px),
        linear-gradient(to bottom, #f1f5f9 1px, transparent 1px);
      background-size: 40px 40px;
    }
    
    /* Custom Sidebar Glassmorphism */
    .sidebar-glass {
      background: rgba(6, 78, 59, 0.98); /* emerald-950 approx */
      backdrop-filter: blur(10px);
    }

    /* Lazy Loading Animation Bar */
    .lazy-loader {
      height: 3px;
      background: linear-gradient(90deg, transparent, #10b981, transparent);
      position: fixed;
      top: 0; left: 0; width: 100%;
      z-index: 100;
      animation: lazyMove 2.5s infinite linear;
    }
    @keyframes lazyMove {
      0% { transform: translateX(-100%); }
      100% { transform: translateX(100%); }
    }
  </style>
</head>
<body class="bg-grid-line text-slate-800 antialiased font-sans">

  <div class="lazy-loader"></div>

  <div class="min-h-screen md:flex relative">
    
    <div data-admin-backdrop class="fixed inset-0 bg-slate-900/50 z-30 hidden md:hidden backdrop-blur-sm transition-opacity"></div>

    <aside data-admin-sidebar class="fixed md:static inset-y-0 left-0 z-40 w-72 sidebar-glass text-emerald-50 p-6 transform -translate-x-full md:translate-x-0 transition-all duration-300 ease-in-out shadow-2xl md:shadow-none border-r border-emerald-800/50">
      
      <div class="flex items-center justify-between gap-3 mb-10 px-2">
        <div class="flex items-center gap-3">
            <div class="h-9 w-9 bg-emerald-500 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-900/50 rotate-3">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <h1 class="text-xl font-black tracking-tighter uppercase italic">Admin<span class="text-emerald-400">Panel</span></h1>
        </div>
        <button type="button" data-admin-close class="md:hidden text-emerald-300 hover:text-white text-3xl transition-colors">&times;</button>
      </div>

      <p class="text-[10px] font-black text-emerald-500/60 uppercase tracking-[0.3em] mb-4 px-3 italic">Main Navigation</p>
      
      <nav class="space-y-1">
        <?php foreach ($items as $key => $item): ?>
          <?php $isActive = $active === $key; ?>
          <a href="<?= htmlspecialchars($item['href']) ?>" 
             class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-bold transition-all duration-200 <?= $isActive ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-900/40' : 'text-emerald-100/70 hover:bg-emerald-800/50 hover:text-white' ?>">
            <span class="text-lg group-hover:scale-125 transition-transform"><?= htmlspecialchars($item['icon']) ?></span>
            <span><?= htmlspecialchars($item['label']) ?></span>
          </a>
        <?php endforeach; ?>
      </nav>

      <div class="mt-10 pt-10 border-t border-emerald-800/50">
        <a href="logout.php" class="flex w-full items-center justify-center gap-2 rounded-xl bg-red-500/10 border border-red-500/20 py-3 text-sm font-black text-red-400 hover:bg-red-500 hover:text-white transition-all duration-300 shadow-sm shadow-red-950">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            Logout
        </a>
      </div>
    </aside>

    <main class="flex-1 p-4 md:p-8 lg:p-12 transition-all">
      <div class="mb-6 md:hidden">
        <button type="button" data-admin-open class="flex items-center gap-2 rounded-xl border border-emerald-200 bg-white/80 backdrop-blur-md px-4 py-2 text-xs font-black text-emerald-700 shadow-sm uppercase tracking-widest">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
            Menu
        </button>
      </div>

      <div class="max-w-6xl mx-auto space-y-8 animate__animated animate__fadeIn">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-200 pb-6">
            <div>
                <h2 class="text-3xl md:text-4xl font-black text-emerald-950 tracking-tighter italic">
                    <?= htmlspecialchars($title) ?>
                </h2>
                <div class="h-1 w-12 bg-emerald-500 mt-2 rounded-full"></div>
            </div>
            <div class="text-right">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Terminal Active</p>
                <p class="text-xs font-mono text-emerald-600 font-bold"><?= date('l, d M Y') ?></p>
            </div>
        </div>
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
        document.body.classList.add('overflow-hidden');
      };
      const close = () => {
        sidebar.classList.add('-translate-x-full');
        if (backdrop) backdrop.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
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
