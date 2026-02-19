<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';
redirectToInstallerIfNeeded();

require_once __DIR__ . '/includes/content.php';

$settings = getSiteSettings();
$services = getServices();
$portfolio = getPortfolio();
$testimonials = getTestimonials();

$navLogo = trim((string)($settings['nav_logo'] ?? '')) ?: 'access/img/site-logo.svg';
$favicon = trim((string)($settings['favicon'] ?? '')) ?: 'access/img/favicon.svg';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$flash = $_SESSION['flash_message'] ?? null;
unset($_SESSION['flash_message']);

$whatsappNumber = preg_replace('/\D+/', '', (string)($settings['whatsapp_number'] ?? '')) ?: '8801000000000';
$chatEnabled = (($settings['chat_toggle_enabled'] ?? '1') === '1');
$channels = [];


$tradeLicenseNumber = trim((string)($settings['trade_license_number'] ?? ''));
$tradeLicenseQr = trim((string)($settings['trade_license_qr'] ?? ''));

$toastEnabled = (($settings['toast_enabled'] ?? '1') === '1');
$toastPosition = (string)($settings['toast_position'] ?? 'top-right');
$toastDuration = (int)($settings['toast_duration_ms'] ?? 4000);
$allowedToastPositions = ['top-right', 'top-left', 'bottom-right', 'bottom-left'];
if (!in_array($toastPosition, $allowedToastPositions, true)) {
    $toastPosition = 'top-right';
}
if ($toastDuration < 1000 || $toastDuration > 15000) {
    $toastDuration = 4000;
}

if (($settings['chat_messenger_enabled'] ?? '1') === '1' && trim((string)($settings['messenger_url'] ?? '')) !== '') {
    $channels[] = ['label' => 'Messenger', 'icon' => '💬', 'url' => (string)$settings['messenger_url']];
}

if (($settings['chat_telegram_enabled'] ?? '1') === '1' && trim((string)($settings['telegram_url'] ?? '')) !== '') {
    $channels[] = ['label' => 'Telegram', 'icon' => '📨', 'url' => (string)$settings['telegram_url']];
}

if (($settings['chat_whatsapp_enabled'] ?? '1') === '1') {
    $channels[] = ['label' => 'WhatsApp', 'icon' => '🟢', 'url' => 'https://wa.me/' . $whatsappNumber];
}


$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = (string)($_SERVER['HTTP_HOST'] ?? 'localhost');
$baseUrl = $scheme . '://' . $host;
$canonicalUrl = $baseUrl . '/index.php';
$ogImage = $baseUrl . '/' . ltrim((string)($settings['hero_image'] ?? 'access/img/hero-illustration.svg'), '/');
$orgJsonLd = [
    '@context' => 'https://schema.org',
    '@type' => 'Organization',
    'name' => (string)$settings['site_title'],
    'url' => $baseUrl,
    'logo' => $baseUrl . '/' . ltrim($navLogo, '/'),
    'contactPoint' => [[
        '@type' => 'ContactPoint',
        'telephone' => (string)($settings['phone'] ?? ''),
        'contactType' => 'customer support',
        'email' => (string)($settings['email'] ?? ''),
        'areaServed' => 'BD',
        'availableLanguage' => ['bn', 'en'],
    ]],
];
$websiteJsonLd = [
    '@context' => 'https://schema.org',
    '@type' => 'WebSite',
    'name' => (string)$settings['site_title'],
    'url' => $baseUrl,
    'inLanguage' => 'bn-BD',
];

$callNumber = trim((string)($settings['call_number'] ?? ($settings['phone'] ?? '')));
if (($settings['chat_call_enabled'] ?? '1') === '1' && $callNumber !== '') {
    $channels[] = ['label' => 'Call', 'icon' => '📞', 'url' => 'tel:' . preg_replace('/[^\d+]/', '', $callNumber)];
}
?>
<!doctype html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($settings['site_title']) ?></title>
  <meta name="description" content="<?= htmlspecialchars($settings['hero_subtitle']) ?>">
  <meta name="robots" content="index,follow,max-image-preview:large">
  <meta name="author" content="<?= htmlspecialchars($settings['site_title']) ?>">
  <meta name="theme-color" content="#10b981">
  <link rel="icon" type="image/svg+xml" href="<?= htmlspecialchars($favicon) ?>">
  <link rel="shortcut icon" href="<?= htmlspecialchars($favicon) ?>">
  <link rel="canonical" href="<?= htmlspecialchars($canonicalUrl) ?>">

  <meta property="og:type" content="website">
  <meta property="og:locale" content="bn_BD">
  <meta property="og:title" content="<?= htmlspecialchars($settings['site_title']) ?>">
  <meta property="og:description" content="<?= htmlspecialchars($settings['hero_subtitle']) ?>">
  <meta property="og:url" content="<?= htmlspecialchars($canonicalUrl) ?>">
  <meta property="og:image" content="<?= htmlspecialchars($ogImage) ?>">

  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= htmlspecialchars($settings['site_title']) ?>">
  <meta name="twitter:description" content="<?= htmlspecialchars($settings['hero_subtitle']) ?>">
  <meta name="twitter:image" content="<?= htmlspecialchars($ogImage) ?>">

  <script type="application/ld+json"><?= json_encode($orgJsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
  <script type="application/ld+json"><?= json_encode($websiteJsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>

  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="access/css/styles.css">
</head>
<body class="text-slate-800 bg-slate-50">
  <header class="sticky top-0 z-50 backdrop-blur bg-white/90 border-b border-emerald-100">
    <nav class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between gap-4">
      <a href="#home" class="flex items-center gap-2">
        <?php if ($navLogo !== ''): ?>
          <img src="<?= htmlspecialchars($navLogo) ?>" alt="<?= htmlspecialchars($settings['site_title']) ?> logo" class="w-10 h-10 rounded-2xl object-cover border border-emerald-100 bg-white" loading="eager" decoding="async">
        <?php else: ?>
          <span class="w-10 h-10 rounded-2xl bg-gradient-to-br from-emerald-500 to-green-700 text-white grid place-items-center font-black">G</span>
        <?php endif; ?>
        <span class="font-extrabold text-emerald-800"><?= htmlspecialchars($settings['site_title']) ?></span>
      </a>

      <button data-menu-toggle class="md:hidden mobile-menu-btn" aria-label="menu" aria-expanded="false" aria-controls="mobile-menu"><span data-menu-icon>☰</span></button>

      <ul class="hidden md:flex items-center gap-6 font-semibold text-sm">
        <li><a class="hover:text-emerald-700" href="#about">আমার সম্পর্কে</a></li>
        <li><a class="hover:text-emerald-700" href="#services">সার্ভিস</a></li>
        <li><a class="hover:text-emerald-700" href="#portfolio">পোর্টফোলিও</a></li>
        <li><a class="hover:text-emerald-700" href="#reviews">রিভিউ</a></li>
        <li><a class="hover:text-emerald-700" href="#contact">যোগাযোগ</a></li>
        <li><a href="admin/login.php" class="px-4 py-2 rounded-xl bg-emerald-600 text-white hover:bg-emerald-700">Admin</a></li>
      </ul>
    </nav>

    <div id="mobile-menu" data-mobile-menu class="mobile-menu hidden md:hidden border-t border-emerald-100 bg-white px-4 py-3 space-y-2 text-sm">
      <a class="block" href="#about">আমার সম্পর্কে</a>
      <a class="block" href="#services">সার্ভিস</a>
      <a class="block" href="#portfolio">পোর্টফোলিও</a>
      <a class="block" href="#reviews">রিভিউ</a>
      <a class="block" href="#contact">যোগাযোগ</a>
      <a class="inline-block mt-2 px-3 py-2 rounded-lg bg-emerald-600 text-white" href="admin/login.php">Admin</a>
    </div>
  </header>

  <section id="home" class="hero-tech overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 py-16 lg:py-24 grid lg:grid-cols-2 gap-10 items-center">
      <div>
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200"><?= htmlspecialchars($settings['hero_badge']) ?></span>
        <h1 class="mt-5 text-4xl md:text-5xl lg:text-6xl font-black leading-tight text-slate-900"><?= nl2br(htmlspecialchars($settings['hero_title'])) ?></h1>
        <p class="mt-5 text-slate-600 max-w-xl leading-relaxed"><?= htmlspecialchars($settings['hero_subtitle']) ?></p>
        <div class="mt-8 flex flex-wrap gap-3">
          <a href="#contact" class="btn-primary"><?= htmlspecialchars($settings['hero_cta_primary']) ?></a>
          <a href="#services" class="btn-outline"><?= htmlspecialchars($settings['hero_cta_secondary']) ?></a>
        </div>
      </div>

      <div class="relative">
        <div class="absolute -top-10 -right-4 w-40 h-40 rounded-full bg-emerald-200/60 blur-3xl"></div>
        <div class="hero-visual glass-card p-4">
          <img src="<?= htmlspecialchars($settings['hero_image']) ?>" alt="<?= htmlspecialchars($settings['site_title']) ?> hero showcase" class="w-full h-[420px] object-cover rounded-[2rem] border border-emerald-100" fetchpriority="high" decoding="async">
        </div>
      </div>
    </div>
  </section>

  <main>
    <section id="about" class="section-wrap">
      <div class="max-w-7xl mx-auto px-4 grid lg:grid-cols-2 gap-8 items-center">
        <div>
          <h2 class="section-title text-3xl lg:text-4xl"><?= htmlspecialchars($settings['about_title']) ?></h2>
          <p class="mt-4 text-slate-600 leading-relaxed"><?= nl2br(htmlspecialchars($settings['about_description'])) ?></p>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <article class="stat-card"><p class="stat-number">২k+</p><p class="stat-label">সফল ক্লায়েন্ট</p></article>
          <article class="stat-card"><p class="stat-number">৯৯%</p><p class="stat-label">সন্তুষ্টির হার</p></article>
          <article class="stat-card"><p class="stat-number">২৪/৭</p><p class="stat-label">লাইভ সাপোর্ট</p></article>
          <article class="stat-card"><p class="stat-number">১০০%</p><p class="stat-label">নিরাপদ প্রসেস</p></article>
        </div>
      </div>
    </section>

    <section id="services" class="section-wrap section-alt">
      <div class="max-w-7xl mx-auto px-4">
        <h2 class="section-title text-center text-3xl lg:text-4xl">আমাদের সার্ভিসসমূহ</h2>
        <div class="mt-9 grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
          <?php foreach ($services as $service): ?>
            <article class="glass-card p-6 hover:-translate-y-1 transition duration-300">
              <div class="service-icon"><?= htmlspecialchars($service['icon']) ?></div>
              <h3 class="mt-4 font-bold text-xl text-emerald-800"><?= htmlspecialchars($service['title']) ?></h3>
              <p class="mt-2 text-slate-600"><?= htmlspecialchars($service['description']) ?></p>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <section id="portfolio" class="section-wrap">
      <div class="max-w-7xl mx-auto px-4">
        <h2 class="section-title text-center text-3xl lg:text-4xl"><?= htmlspecialchars($settings['portfolio_title']) ?></h2>
        <p class="text-center text-slate-600 mt-3"><?= htmlspecialchars($settings['portfolio_subtitle']) ?></p>
        <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <?php foreach ($portfolio as $item): ?>
            <article class="portfolio-item overflow-hidden">
              <img src="<?= htmlspecialchars($item['image_path']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" class="w-full h-48 object-cover" loading="lazy" decoding="async">
              <div class="p-4"><p class="text-xs text-emerald-700 font-semibold"><?= htmlspecialchars($item['category']) ?></p><h3 class="font-semibold mt-1"><?= htmlspecialchars($item['title']) ?></h3></div>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <section id="reviews" class="section-wrap section-alt">
      <div class="max-w-5xl mx-auto px-4">
        <h2 class="section-title text-center text-3xl lg:text-4xl"><?= htmlspecialchars($settings['reviews_title']) ?></h2>
        <div class="mt-8 glass-card p-8 text-center relative">
          <button data-prev class="slider-nav left">‹</button><button data-next class="slider-nav right">›</button>
          <?php foreach ($testimonials as $idx => $review): ?>
            <blockquote data-testimonial class="<?= $idx > 0 ? 'hidden' : '' ?>">
              <div class="text-amber-400 mb-3 text-lg"><?= str_repeat('★', max(1, min(5, (int)($review['rating'] ?? 5)))) ?></div>
              <p class="text-lg md:text-xl font-medium leading-relaxed">“<?= htmlspecialchars($review['content']) ?>”</p>
              <footer class="mt-5 text-emerald-700 font-bold"><?= htmlspecialchars($review['client_name']) ?> <span class="text-slate-500 font-medium">— <?= htmlspecialchars($review['designation']) ?></span></footer>
            </blockquote>
          <?php endforeach; ?>
          <div class="mt-6 flex justify-center gap-2" data-slider-dots></div>
        </div>
      </div>
    </section>

    <section id="contact" class="section-wrap">
      <div class="max-w-7xl mx-auto px-4">
        <h2 class="section-title text-center text-3xl lg:text-4xl"><?= htmlspecialchars($settings['contact_title']) ?></h2>
        <p class="text-center text-slate-600 mt-2"><?= htmlspecialchars($settings['contact_subtitle']) ?></p>

        <?php if ($toastEnabled && is_array($flash) && isset($flash['type'], $flash['message'])): ?>
          <div class="toast-stack toast-<?= htmlspecialchars($toastPosition) ?>" data-toast-stack data-toast-duration="<?= (int)$toastDuration ?>">
            <div class="toast-item <?= $flash['type'] === 'success' ? 'toast-success' : 'toast-error' ?>" data-toast role="status" aria-live="polite">
              <span class="toast-dot" aria-hidden="true"></span>
              <span><?= htmlspecialchars((string)$flash['message']) ?></span>
              <button type="button" class="toast-close" data-toast-close aria-label="Close notification">×</button>
            </div>
          </div>
        <?php endif; ?>

        <div class="mt-8 grid lg:grid-cols-2 gap-6">
          <form action="submit.php" method="post" class="glass-card p-6 space-y-3">
            <label class="block text-sm font-semibold text-slate-700">আপনার নাম *</label>
            <input class="input-field" name="name" placeholder="আপনার নাম লিখুন" required>
            <label class="block text-sm font-semibold text-slate-700">ইমেইল *</label>
            <input class="input-field" name="email" type="email" placeholder="your@email.com" required>
            <label class="block text-sm font-semibold text-slate-700">মেসেজ *</label>
            <textarea class="input-field min-h-28" name="message" rows="5" placeholder="আপনার প্রজেক্ট সম্পর্কে লিখুন..." required></textarea>
            <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrfToken()) ?>">
            <button class="btn-primary w-full" type="submit">✈ মেসেজ পাঠান</button>
          </form>

          <div class="glass-card p-6">
            <h3 class="text-2xl font-bold text-emerald-800">সরাসরি যোগাযোগ করুন</h3>
            <div class="mt-5 space-y-4">
              <div class="contact-item"><div class="contact-icon">📞</div><div><p class="contact-label">ফোন</p><p class="contact-value"><?= htmlspecialchars($settings['phone']) ?></p></div></div>
              <div class="contact-item"><div class="contact-icon">✉️</div><div><p class="contact-label">ইমেইল</p><p class="contact-value"><?= htmlspecialchars($settings['email']) ?></p></div></div>
              <div class="contact-item"><div class="contact-icon">📍</div><div><p class="contact-label">ঠিকানা</p><p class="contact-value"><?= htmlspecialchars($settings['address']) ?></p></div></div>
            </div>
            <div class="mt-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-900">
              <p class="font-extrabold text-lg"><?= htmlspecialchars($settings['whatsapp_notice_title']) ?></p>
              <p class="mt-1 text-slate-600"><?= htmlspecialchars($settings['whatsapp_notice_text']) ?></p>
            </div>

            <?php if ($tradeLicenseNumber !== '' || $tradeLicenseQr !== ''): ?>
              <div class="mt-4 p-4 rounded-2xl bg-white border border-emerald-100">
                <h4 class="font-bold text-emerald-800">Trade License</h4>
                <p class="text-sm text-slate-700 mt-2">License No: <span class="font-semibold"><?= htmlspecialchars($tradeLicenseNumber !== '' ? $tradeLicenseNumber : 'Not provided yet') ?></span></p>
                <div class="mt-3">
                  <?php if ($tradeLicenseQr !== ''): ?>
                    <img src="<?= htmlspecialchars($tradeLicenseQr) ?>" alt="Trade license QR code" class="w-28 h-28 rounded border border-emerald-100 object-cover" loading="lazy" decoding="async">
                  <?php else: ?>
                    <div class="w-28 h-28 rounded border border-dashed border-slate-300 text-xs text-slate-500 grid place-items-center">QR not uploaded</div>
                  <?php endif; ?>
                </div>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </section>
  </main>

  <footer class="bg-emerald-900 text-emerald-50 mt-8">
    <div class="max-w-7xl mx-auto px-4 py-10 grid md:grid-cols-3 gap-7">
      <div><h3 class="font-bold text-xl"><?= htmlspecialchars($settings['site_title']) ?></h3><p class="mt-2 text-emerald-100/90"><?= htmlspecialchars($settings['footer_text']) ?></p></div>
      <div><a class="underline" href="admin/login.php">Secure Admin Panel</a></div>
      <div class="text-sm">© <?= date('Y') ?> All rights reserved.</div>
    </div>
  </footer>

  <?php if ($chatEnabled && $channels !== []): ?>
    <div class="chat-float" data-chat-widget>
      <div class="chat-menu hidden" data-chat-menu>
        <p class="chat-menu-title"><?= htmlspecialchars($settings['chat_widget_title']) ?></p>
        <p class="chat-menu-subtitle"><?= htmlspecialchars($settings['chat_widget_subtitle']) ?></p>
        <div class="chat-links">
          <?php foreach ($channels as $channel): ?>
            <a href="<?= htmlspecialchars($channel['url']) ?>" class="chat-link" target="_blank" rel="noopener">
              <span class="chat-link-icon"><?= htmlspecialchars($channel['icon']) ?></span>
              <span><?= htmlspecialchars($channel['label']) ?></span>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
      <button type="button" class="wa-float" data-chat-toggle aria-expanded="false" aria-label="Open contact options">
        <span class="wa-pulse"></span><span class="wa-icon" data-chat-icon>💬</span>
      </button>
    </div>
  <?php endif; ?>
  <script src="access/javascript/main.js"></script>
</body>
</html>
