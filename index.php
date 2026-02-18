<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/content.php';

$settings = getSiteSettings();
$services = getServices();
$portfolio = getPortfolio();
$testimonials = getTestimonials();
?>
<!doctype html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($settings['site_title']) ?></title>
  <meta name="description" content="প্রিমিয়াম সোশাল মিডিয়া মার্কেটিং সার্ভিস | White + Green Tech Vibe">
  <meta property="og:title" content="<?= htmlspecialchars($settings['site_title']) ?>">
  <meta property="og:description" content="বিশ্বাসযোগ্য সোশাল মিডিয়া গ্রোথ, প্রিমিয়াম স্ট্র্যাটেজি ও ফলাফল">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: {
              50: '#ecfdf3',
              100: '#d1fae5',
              500: '#10b981',
              600: '#059669',
              700: '#047857',
              900: '#064e3b'
            }
          },
          boxShadow: {
            soft: '0 12px 40px rgba(16,185,129,.14)'
          }
        }
      }
    }
  </script>
  <link rel="stylesheet" href="access/css/styles.css">
</head>
<body class="text-slate-800">
  <header class="sticky top-0 z-50 backdrop-blur bg-white/85 border-b border-emerald-100">
    <nav class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
      <a href="#home" class="flex items-center gap-2">
        <span class="w-9 h-9 rounded-xl bg-emerald-500 text-white grid place-items-center font-bold">G</span>
        <span class="font-extrabold text-emerald-800"><?= htmlspecialchars($settings['site_title']) ?></span>
      </a>

      <button data-menu-toggle class="md:hidden text-emerald-700 text-2xl" aria-label="menu">☰</button>

      <ul class="hidden md:flex items-center gap-6 font-medium text-sm">
        <li><a class="hover:text-emerald-700" href="#about">আমাদের সম্পর্কে</a></li>
        <li><a class="hover:text-emerald-700" href="#services">সার্ভিস</a></li>
        <li><a class="hover:text-emerald-700" href="#portfolio">পোর্টফোলিও</a></li>
        <li><a class="hover:text-emerald-700" href="#contact">যোগাযোগ</a></li>
        <li><a href="admin/login.php" class="px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">Admin</a></li>
      </ul>
    </nav>

    <div data-mobile-menu class="hidden md:hidden border-t border-emerald-100 bg-white px-4 py-3 space-y-2 text-sm">
      <a class="block" href="#about">আমাদের সম্পর্কে</a>
      <a class="block" href="#services">সার্ভিস</a>
      <a class="block" href="#portfolio">পোর্টফোলিও</a>
      <a class="block" href="#contact">যোগাযোগ</a>
      <a class="inline-block mt-2 px-3 py-2 rounded-lg bg-emerald-600 text-white" href="admin/login.php">Admin</a>
    </div>
  </header>

  <section id="home" class="hero-tech overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 py-16 lg:py-24 grid lg:grid-cols-2 gap-10 items-center">
      <div>
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">🚀 Premium Digital Growth</span>
        <h1 class="mt-5 text-4xl md:text-5xl lg:text-6xl font-black leading-tight text-slate-900">
          বিশ্বাসযোগ্য <span class="text-emerald-600">সোশাল মিডিয়া গ্রোথ</span><br>আপনার ব্র্যান্ডের জন্য
        </h1>
        <p class="mt-5 text-slate-600 max-w-xl">
          <?= htmlspecialchars($settings['hero_subtitle']) ?> আমরা ডাটা-ড্রিভেন স্ট্র্যাটেজি দিয়ে Facebook, Instagram, YouTube ও TikTok-এ measurable result এনে দিই।
        </p>
        <div class="mt-8 flex flex-wrap gap-3">
          <a href="#contact" class="btn-primary">এখনই অর্ডার</a>
          <a href="#services" class="btn-outline">আমাদের সার্ভিস</a>
        </div>
        <div class="mt-6 flex gap-6 text-sm text-slate-600">
          <span>✅ নিরাপদ প্রসেস</span><span>✅ দ্রুত ডেলিভারি</span><span>✅ 24/7 সাপোর্ট</span>
        </div>
      </div>

      <div class="relative">
        <div class="absolute -top-8 -left-8 w-28 h-28 rounded-full bg-emerald-200/50 blur-2xl"></div>
        <div class="glass-card p-3 shadow-soft">
          <img src="access/img/hero-illustration.svg" alt="Digital growth consultant" class="rounded-2xl w-full h-[430px] object-cover">
        </div>
      </div>
    </div>
  </section>

  <main class="max-w-7xl mx-auto px-4">
    <section id="about" class="py-14 lg:py-20 grid lg:grid-cols-2 gap-8 items-center">
      <div>
        <h2 class="section-title text-3xl lg:text-4xl">আমরা কেন আপনার জন্য সেরা?</h2>
        <p class="mt-4 text-slate-600 leading-relaxed">
          আমরা শুধু followers বাড়াই না—আপনার ব্র্যান্ড authority, trust এবং conversion বাড়ানোর জন্য full-funnel strategy তৈরি করি। প্রতিটি ক্যাম্পেইন custom plan অনুযায়ী execute করা হয়।
        </p>
        <ul class="mt-5 space-y-2 text-slate-700">
          <li>• রিয়েল ও niche-targeted audience</li>
          <li>• মাসিক রিপোর্টিং ও analytics support</li>
          <li>• AI + human optimization workflow</li>
          <li>• Bangla ও International market experience</li>
        </ul>
      </div>
      <div class="grid sm:grid-cols-2 gap-4">
        <article class="glass-card p-6 text-center"><h3 class="text-3xl font-black text-emerald-600">5K+</h3><p class="text-slate-600 mt-1">সন্তুষ্ট ক্লায়েন্ট</p></article>
        <article class="glass-card p-6 text-center"><h3 class="text-3xl font-black text-emerald-600">99%</h3><p class="text-slate-600 mt-1">সফল ক্যাম্পেইন</p></article>
        <article class="glass-card p-6 text-center"><h3 class="text-3xl font-black text-emerald-600">24/7</h3><p class="text-slate-600 mt-1">সাপোর্ট টিম</p></article>
        <article class="glass-card p-6 text-center"><h3 class="text-3xl font-black text-emerald-600">10+</h3><p class="text-slate-600 mt-1">বছরের অভিজ্ঞতা</p></article>
      </div>
    </section>

    <section id="services" class="py-14 lg:py-20">
      <h2 class="section-title text-center text-3xl lg:text-4xl">আমাদের সার্ভিসসমূহ</h2>
      <p class="text-center text-slate-600 mt-3">প্ল্যাটফর্মভিত্তিক premium growth solutions</p>
      <div class="mt-9 grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
        <?php foreach ($services as $service): ?>
          <article class="glass-card p-6 hover:-translate-y-1 transition">
            <div class="w-12 h-12 rounded-xl grid place-items-center bg-emerald-50 text-2xl border border-emerald-100"><?= htmlspecialchars($service['icon']) ?></div>
            <h3 class="mt-4 font-bold text-xl text-emerald-800"><?= htmlspecialchars($service['title']) ?></h3>
            <p class="mt-2 text-slate-600"><?= htmlspecialchars($service['description']) ?></p>
            <a href="#contact" class="mt-4 inline-block text-emerald-700 font-semibold">বিস্তারিত জানুন →</a>
          </article>
        <?php endforeach; ?>
      </div>
    </section>

    <section id="portfolio" class="py-14 lg:py-20">
      <h2 class="section-title text-center text-3xl lg:text-4xl">পোর্টফোলিও</h2>
      <p class="text-center text-slate-600 mt-3">সাম্প্রতিক প্রজেক্ট ও ক্যাম্পেইন</p>
      <div class="mt-6 flex flex-wrap justify-center gap-2">
        <button data-filter="all" class="filter-btn active">সব</button>
        <button data-filter="Marketing" class="filter-btn">Marketing</button>
        <button data-filter="Social" class="filter-btn">Social</button>
      </div>
      <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <?php foreach ($portfolio as $item): ?>
          <article data-item="<?= htmlspecialchars($item['category']) ?>" class="portfolio-item group rounded-2xl overflow-hidden border border-emerald-100 bg-white shadow-sm">
            <img src="<?= htmlspecialchars($item['image_path']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" class="w-full h-44 object-cover group-hover:scale-105 transition duration-300">
            <div class="p-3">
              <p class="text-xs text-emerald-700 font-semibold"><?= htmlspecialchars($item['category']) ?></p>
              <h3 class="font-semibold"><?= htmlspecialchars($item['title']) ?></h3>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </section>

    <section class="py-14 lg:py-20">
      <h2 class="section-title text-center text-3xl lg:text-4xl">ক্লায়েন্ট রিভিউ</h2>
      <div class="mt-8 max-w-3xl mx-auto glass-card p-8 text-center relative">
        <?php foreach ($testimonials as $idx => $review): ?>
          <blockquote data-testimonial class="<?= $idx > 0 ? 'hidden' : '' ?>">
            <div class="text-amber-400 mb-3">★★★★★</div>
            <p class="text-lg md:text-xl font-medium leading-relaxed">“<?= htmlspecialchars($review['content']) ?>”</p>
            <footer class="mt-5 text-emerald-700 font-bold"><?= htmlspecialchars($review['client_name']) ?> <span class="text-slate-500 font-medium">— <?= htmlspecialchars($review['designation']) ?></span></footer>
          </blockquote>
        <?php endforeach; ?>
        <div class="mt-6 flex justify-center gap-2" data-slider-dots></div>
      </div>
    </section>

    <section id="contact" class="py-14 lg:py-20">
      <h2 class="section-title text-center text-3xl lg:text-4xl">যোগাযোগ করুন</h2>
      <div class="mt-8 grid lg:grid-cols-2 gap-6">
        <form action="submit.php" method="post" class="glass-card p-6 space-y-3">
          <input class="w-full border border-emerald-100 rounded-xl p-3" name="name" placeholder="আপনার নাম" required>
          <input class="w-full border border-emerald-100 rounded-xl p-3" name="email" type="email" placeholder="ইমেইল" required>
          <textarea class="w-full border border-emerald-100 rounded-xl p-3" name="message" rows="5" placeholder="আপনার মেসেজ" required></textarea>
          <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrfToken()) ?>">
          <button class="btn-primary w-full" type="submit">মেসেজ পাঠান</button>
        </form>

        <div class="glass-card p-6">
          <h3 class="text-2xl font-bold text-emerald-800">সরাসরি যোগাযোগ</h3>
          <p class="mt-4 text-slate-600">📞 <?= htmlspecialchars($settings['phone']) ?></p>
          <p class="mt-1 text-slate-600">✉️ <?= htmlspecialchars($settings['email']) ?></p>
          <p class="mt-1 text-slate-600">📍 <?= htmlspecialchars($settings['address']) ?></p>
          <div class="mt-6 p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-800">
            দ্রুত response পেতে WhatsApp/Telegram এও যোগাযোগ করতে পারেন।
          </div>
        </div>
      </div>
    </section>
  </main>

  <footer class="mt-8 bg-emerald-900 text-emerald-50">
    <div class="max-w-7xl mx-auto px-4 py-10 flex flex-col md:flex-row justify-between gap-5">
      <div>
        <h3 class="font-bold text-xl"><?= htmlspecialchars($settings['site_title']) ?></h3>
        <p class="mt-2 text-emerald-100/90">Premium, user-friendly, SEO-ready social media growth platform.</p>
      </div>
      <div class="text-sm space-y-1">
        <p>© <?= date('Y') ?> All rights reserved.</p>
        <a class="underline" href="admin/login.php">Secure Admin Panel</a>
      </div>
    </div>
  </footer>

  <script src="access/javascript/main.js"></script>
</body>
</html>
