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
              50: '#ecfdf5',
              100: '#d1fae5',
              400: '#34d399',
              500: '#10b981',
              600: '#059669',
              700: '#047857',
              900: '#064e3b'
            }
          },
          boxShadow: {
            soft: '0 15px 40px rgba(16,185,129,.14)',
            glow: '0 0 0 8px rgba(16,185,129,.09)'
          }
        }
      }
    }
  </script>
  <link rel="stylesheet" href="access/css/styles.css">
</head>
<body class="text-slate-800 bg-slate-50">
  <header class="sticky top-0 z-50 backdrop-blur bg-white/90 border-b border-emerald-100">
    <nav class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between gap-4">
      <a href="#home" class="flex items-center gap-2">
        <span class="w-10 h-10 rounded-2xl bg-gradient-to-br from-emerald-500 to-green-700 text-white grid place-items-center font-black">G</span>
        <span class="font-extrabold text-emerald-800"><?= htmlspecialchars($settings['site_title']) ?></span>
      </a>

      <button data-menu-toggle class="md:hidden text-emerald-700 text-2xl" aria-label="menu">☰</button>

      <ul class="hidden md:flex items-center gap-6 font-semibold text-sm">
        <li><a class="hover:text-emerald-700" href="#about">আমার সম্পর্কে</a></li>
        <li><a class="hover:text-emerald-700" href="#services">সার্ভিস</a></li>
        <li><a class="hover:text-emerald-700" href="#portfolio">পোর্টফোলিও</a></li>
        <li><a class="hover:text-emerald-700" href="#reviews">রিভিউ</a></li>
        <li><a class="hover:text-emerald-700" href="#contact">যোগাযোগ</a></li>
        <li><a href="admin/login.php" class="px-4 py-2 rounded-xl bg-emerald-600 text-white hover:bg-emerald-700">Admin</a></li>
      </ul>
    </nav>

    <div data-mobile-menu class="hidden md:hidden border-t border-emerald-100 bg-white px-4 py-3 space-y-2 text-sm">
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
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">🟢 White + Green Premium Strategy</span>
        <h1 class="mt-5 text-4xl md:text-5xl lg:text-6xl font-black leading-tight text-slate-900">
          বিশ্বাসযোগ্য <span class="text-emerald-600">সোশাল মিডিয়া বিক্রয়</span><br>এজেন্সি
        </h1>
        <p class="mt-5 text-slate-600 max-w-xl leading-relaxed">
          <?= htmlspecialchars($settings['hero_subtitle']) ?> আমরা Facebook, Instagram, YouTube ও TikTok-এ data-driven content, campaign এবং conversion strategy দিয়ে measurable growth এনে দিই।
        </p>
        <div class="mt-8 flex flex-wrap gap-3">
          <a href="#contact" class="btn-primary">এখনই অর্ডার</a>
          <a href="#services" class="btn-outline">সার্ভিস দেখুন</a>
        </div>
        <div class="mt-6 flex flex-wrap gap-5 text-sm text-slate-600">
          <span>✅ নিরাপদ প্রসেস</span><span>✅ দ্রুত ডেলিভারি</span><span>✅ 24/7 সাপোর্ট</span>
        </div>
      </div>

      <div class="relative">
        <div class="absolute -top-10 -right-4 w-40 h-40 rounded-full bg-emerald-200/60 blur-3xl"></div>
        <div class="hero-visual glass-card p-4">
          <img src="access/img/hero-illustration.svg" alt="Digital growth consultant" class="w-full h-[420px] object-cover rounded-[2rem] border border-emerald-100">
        </div>
      </div>
    </div>
  </section>

  <main>
    <section id="about" class="section-wrap">
      <div class="max-w-7xl mx-auto px-4 grid lg:grid-cols-2 gap-8 items-center">
        <div>
          <h2 class="section-title text-3xl lg:text-4xl">আমরা কেন আপনার জন্য বেস্ট টিম</h2>
          <p class="mt-4 text-slate-600 leading-relaxed">
            আমরা শুধুমাত্র follower count না, আপনার brand trust, engagement এবং sales boost করার জন্য end-to-end কাজ করি। প্রতিটি campaign real niche audience target করে execute করা হয়।
          </p>
          <ul class="mt-5 space-y-2 text-slate-700">
            <li>• বাস্তব ও niche-targeted audience strategy</li>
            <li>• analytics driven campaign রিপোর্টিং</li>
            <li>• business goal অনুযায়ী custom growth plan</li>
            <li>• premium support + on-time delivery</li>
          </ul>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <article class="stat-card">
            <p class="stat-icon">👥</p>
            <p class="stat-number">২k+</p>
            <p class="stat-label">সফল ক্লায়েন্ট</p>
          </article>
          <article class="stat-card">
            <p class="stat-icon">📈</p>
            <p class="stat-number">৯৯%</p>
            <p class="stat-label">সন্তুষ্টির হার</p>
          </article>
          <article class="stat-card">
            <p class="stat-icon">⚡</p>
            <p class="stat-number">২৪/৭</p>
            <p class="stat-label">লাইভ সাপোর্ট</p>
          </article>
          <article class="stat-card">
            <p class="stat-icon">🛡️</p>
            <p class="stat-number">১০০%</p>
            <p class="stat-label">নিরাপদ প্রসেস</p>
          </article>
        </div>
      </div>
    </section>

    <section id="services" class="section-wrap section-alt">
      <div class="max-w-7xl mx-auto px-4">
        <h2 class="section-title text-center text-3xl lg:text-4xl">আমাদের সার্ভিসসমূহ</h2>
        <p class="text-center text-slate-600 mt-3">Social media marketing থেকে conversion growth পর্যন্ত complete service suite</p>
        <div class="mt-9 grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
          <?php foreach ($services as $service): ?>
            <article class="glass-card p-6 hover:-translate-y-1 transition duration-300">
              <div class="service-icon"><?= htmlspecialchars($service['icon']) ?></div>
              <h3 class="mt-4 font-bold text-xl text-emerald-800"><?= htmlspecialchars($service['title']) ?></h3>
              <p class="mt-2 text-slate-600"><?= htmlspecialchars($service['description']) ?></p>
              <a href="#contact" class="mt-4 inline-block text-emerald-700 font-semibold">বিস্তারিত জানুন →</a>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <section id="portfolio" class="section-wrap">
      <div class="max-w-7xl mx-auto px-4">
        <h2 class="section-title text-center text-3xl lg:text-4xl">পোর্টফোলিও</h2>
        <p class="text-center text-slate-600 mt-3">আমাদের কিছু premium project showcase</p>
        <div class="mt-6 flex flex-wrap justify-center gap-2">
          <button data-filter="all" class="filter-btn active">সব কাজ</button>
          <button data-filter="Marketing" class="filter-btn">মার্কেটিং</button>
          <button data-filter="Social" class="filter-btn">সোশাল</button>
        </div>

        <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <?php foreach ($portfolio as $item): ?>
            <article data-item="<?= htmlspecialchars($item['category']) ?>" class="portfolio-item overflow-hidden">
              <img src="<?= htmlspecialchars($item['image_path']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" class="w-full h-48 object-cover group-hover:scale-105 transition duration-300">
              <div class="p-4">
                <p class="text-xs text-emerald-700 font-semibold"><?= htmlspecialchars($item['category']) ?></p>
                <h3 class="font-semibold mt-1"><?= htmlspecialchars($item['title']) ?></h3>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <section id="reviews" class="section-wrap section-alt">
      <div class="max-w-5xl mx-auto px-4">
        <h2 class="section-title text-center text-3xl lg:text-4xl">ক্লায়েন্ট রিভিউ</h2>
        <p class="text-center text-slate-600 mt-2">ক্লায়েন্ট ফিডব্যাক আমাদের সবচেয়ে বড় শক্তি</p>

        <div class="mt-8 glass-card p-8 text-center relative">
          <button data-prev class="slider-nav left">‹</button>
          <button data-next class="slider-nav right">›</button>

          <?php foreach ($testimonials as $idx => $review): ?>
            <blockquote data-testimonial class="<?= $idx > 0 ? 'hidden' : '' ?>">
              <div class="text-amber-400 mb-3 text-lg">★★★★★</div>
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
        <h2 class="section-title text-center text-3xl lg:text-4xl">যোগাযোগ করুন</h2>
        <p class="text-center text-slate-600 mt-2">আপনার project নিয়ে কথা বলতে এখনই message দিন</p>

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
              <div class="contact-item">
                <div class="contact-icon">📞</div>
                <div>
                  <p class="contact-label">ফোন</p>
                  <p class="contact-value"><?= htmlspecialchars($settings['phone']) ?></p>
                </div>
              </div>

              <div class="contact-item">
                <div class="contact-icon">✉️</div>
                <div>
                  <p class="contact-label">ইমেইল</p>
                  <p class="contact-value"><?= htmlspecialchars($settings['email']) ?></p>
                </div>
              </div>

              <div class="contact-item">
                <div class="contact-icon">📍</div>
                <div>
                  <p class="contact-label">ঠিকানা</p>
                  <p class="contact-value"><?= htmlspecialchars($settings['address']) ?></p>
                </div>
              </div>
            </div>

            <div class="mt-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-900">
              <p class="font-extrabold text-lg">দ্রুত সাড়া পেতে হোয়াটসঅ্যাপে মেসেজ করুন</p>
              <p class="mt-1 text-slate-600">আমরা সাধারণত ৪ ঘণ্টার মধ্যে রিপ্লাই দিই</p>
            </div>

            <h4 class="mt-7 text-lg font-bold text-emerald-800">সোশাল মিডিয়াতে ফলো করুন</h4>
            <div class="mt-3 flex flex-wrap gap-2 text-sm">
              <span class="social-chip">Facebook</span>
              <span class="social-chip">Instagram</span>
              <span class="social-chip">WhatsApp</span>
              <span class="social-chip">Telegram</span>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <footer class="bg-emerald-900 text-emerald-50 mt-8">
    <div class="max-w-7xl mx-auto px-4 py-10 grid md:grid-cols-3 gap-7">
      <div>
        <h3 class="font-bold text-xl"><?= htmlspecialchars($settings['site_title']) ?></h3>
        <p class="mt-2 text-emerald-100/90">Premium, user-friendly, SEO-ready social media growth platform.</p>
      </div>
      <div>
        <p class="font-semibold">Quick Links</p>
        <ul class="mt-2 space-y-1 text-sm text-emerald-100/90">
          <li><a href="#about">আমার সম্পর্কে</a></li>
          <li><a href="#services">সার্ভিস</a></li>
          <li><a href="#portfolio">পোর্টফোলিও</a></li>
          <li><a href="#contact">যোগাযোগ</a></li>
        </ul>
      </div>
      <div class="text-sm space-y-1">
        <p>© <?= date('Y') ?> All rights reserved.</p>
        <a class="underline" href="admin/login.php">Secure Admin Panel</a>
      </div>
    </div>
  </footer>

  <a href="https://wa.me/8801000000000" class="wa-float" target="_blank" rel="noopener" aria-label="WhatsApp Chat">
    <span class="wa-pulse"></span>
    <span class="wa-icon">💬</span>
  </a>

  <script src="access/javascript/main.js"></script>
</body>
</html>
