<?php
$services = [
  ['icon' => '📘', 'title' => 'ফেসবুক মার্কেটিং', 'desc' => 'টার্গেটেড অডিয়েন্সে পৌঁছে লিড ও সেলস বাড়ানোর স্মার্ট ক্যাম্পেইন।'],
  ['icon' => '📷', 'title' => 'ইনস্টাগ্রাম গ্রোথ', 'desc' => 'রিলস, কনটেন্ট প্ল্যান এবং ব্র্যান্ড ভিজিবিলিটি বাড়ানোর কৌশল।'],
  ['icon' => '▶️', 'title' => 'ইউটিউব মার্কেটিং', 'desc' => 'ভিডিও SEO, রিটেনশন অপ্টিমাইজেশন এবং সাবস্ক্রাইবার গ্রোথ।'],
  ['icon' => '🎯', 'title' => 'লিড জেনারেশন', 'desc' => 'সেলস ফানেলে হাই-কোয়ালিটি লিড এনে কনভার্সন বাড়ানো।'],
  ['icon' => '⚙️', 'title' => 'এড ম্যানেজমেন্ট', 'desc' => 'বাজেট অপ্টিমাইজ করে ROI ফোকাসড বিজ্ঞাপন পরিচালনা।'],
  ['icon' => '💬', 'title' => 'কমিউনিটি ম্যানেজমেন্ট', 'desc' => 'ইনবক্স/কমেন্ট রিপ্লাই করে গ্রাহক ট্রাস্ট ও ব্র্যান্ড ভ্যালু বৃদ্ধি।'],
];

$stats = [
  ['value' => '৫+', 'label' => 'বছরের অভিজ্ঞতা'],
  ['value' => '৫০০+', 'label' => 'সফল প্রজেক্ট'],
  ['value' => '২৪/৭', 'label' => 'সাপোর্ট'],
  ['value' => '৯৮%', 'label' => 'সন্তুষ্ট ক্লায়েন্ট'],
];

$portfolio = [
  ['cat' => 'facebook', 'img' => 'https://images.unsplash.com/photo-1611262588024-d12430b98920?auto=format&fit=crop&w=900&q=80'],
  ['cat' => 'instagram', 'img' => 'https://images.unsplash.com/photo-1611162618071-b39a2ec055fb?auto=format&fit=crop&w=900&q=80'],
  ['cat' => 'design', 'img' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=900&q=80'],
  ['cat' => 'youtube', 'img' => 'https://images.unsplash.com/photo-1616469829581-73993eb86b02?auto=format&fit=crop&w=900&q=80'],
  ['cat' => 'facebook', 'img' => 'https://images.unsplash.com/photo-1563013544-824ae1b704d3?auto=format&fit=crop&w=900&q=80'],
  ['cat' => 'instagram', 'img' => 'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?auto=format&fit=crop&w=900&q=80'],
];
?>
<!doctype html>
<html lang="bn">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>বিশ্বাসযোগ্য | সোশ্যাল মিডিয়া মার্কেটিং এক্সপার্ট</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="css/style.css" />
  </head>
  <body>
    <header class="topbar">
      <div class="container topbar-inner">
        <a class="brand" href="#">বিশ্বাসযোগ্য এজেন্সি</a>
        <button id="menuBtn" class="menu-btn" type="button">☰</button>
        <nav class="nav-links">
          <a href="#about">আমার সম্পর্কে</a>
          <a href="#services">সার্ভিস</a>
          <a href="#portfolio">পোর্টফোলিও</a>
          <a href="#contact">যোগাযোগ</a>
        </nav>
      </div>
      <div class="container mobile-menu" id="mobileMenu">
        <nav>
          <a href="#about">আমার সম্পর্কে</a>
          <a href="#services">সার্ভিস</a>
          <a href="#portfolio">পোর্টফোলিও</a>
          <a href="#contact">যোগাযোগ</a>
        </nav>
      </div>
    </header>

    <main>
      <section class="hero">
        <div class="container hero-grid">
          <div>
            <span class="badge">✅ ১০০% Trusted মার্কেটিং সলিউশন</span>
            <h1>বিশ্বাসযোগ্য সোশ্যাল মিডিয়া বিক্রয়ি এক্সপার্ট</h1>
            <p class="lead">Facebook, Instagram এবং YouTube–এ আপনার ব্যবসার গ্রোথ বাড়াতে প্ল্যান, কনটেন্ট এবং এড ম্যানেজমেন্ট একসাথে করি।</p>
            <div class="hero-actions">
              <button class="btn btn-primary" type="button">যোগাযোগ করুন</button>
              <button class="btn btn-secondary" type="button">কোম্পানি প্রোফাইল</button>
            </div>
            <div class="hero-meta">
              <span>📍 ঢাকা, বাংলাদেশ</span>
              <span>📞 দ্রুত সাপোর্ট</span>
              <span>🕒 ২৪/৭</span>
            </div>
          </div>
          <div class="hero-card">
            <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=1000&q=80" alt="Marketing profile" />
            <span class="floating">প্রো মার্কেটিং কনসালটেন্ট</span>
          </div>
        </div>
      </section>

      <section class="section section-soft" id="about">
        <div class="container about-grid">
          <article class="card">
            <h2>আমার সম্পর্কে</h2>
            <p class="lead">আপনার ব্যবসার ডিজিটাল সফলতার জন্য প্র্যাক্টিক্যাল এবং ফলাফলভিত্তিক মার্কেটিং সাপোর্ট।</p>
            <h3>আপনার ব্র্যান্ড কেন আমি গ্রোথ করতে পারি</h3>
            <ul class="list">
              <li>মার্কেট রিসার্চ ভিত্তিক কাস্টম প্ল্যান</li>
              <li>ROI ফোকাসড ক্যাম্পেইন অপ্টিমাইজেশন</li>
              <li>ব্র্যান্ড ভয়েস ধরে কনটেন্ট ক্যালেন্ডার</li>
              <li>রেগুলার রিপোর্টিং এবং পারফরম্যান্স ট্র্যাকিং</li>
            </ul>
          </article>
          <div class="stats">
            <?php foreach ($stats as $item): ?>
              <article class="card stat">
                <h4><?= htmlspecialchars($item['value'], ENT_QUOTES, 'UTF-8') ?></h4>
                <p><?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?></p>
              </article>
            <?php endforeach; ?>
          </div>
        </div>
      </section>

      <section class="section" id="services">
        <div class="container">
          <h2 class="section-title">আমাদের সার্ভিসসমূহ</h2>
          <p class="section-sub">আপনার ব্যবসার জন্য সম্পূর্ণ ডিজিটাল মার্কেটিং সমাধান</p>
          <div class="services">
            <?php foreach ($services as $service): ?>
              <article class="card service">
                <span class="icon"><?= htmlspecialchars($service['icon'], ENT_QUOTES, 'UTF-8') ?></span>
                <h4><?= htmlspecialchars($service['title'], ENT_QUOTES, 'UTF-8') ?></h4>
                <p><?= htmlspecialchars($service['desc'], ENT_QUOTES, 'UTF-8') ?></p>
              </article>
            <?php endforeach; ?>
          </div>
        </div>
      </section>

      <section class="section section-soft" id="portfolio">
        <div class="container">
          <h2 class="section-title">পোর্টফোলিও</h2>
          <p class="section-sub">আমাদের সাম্প্রতিক কাজগুলো</p>
          <div class="filters">
            <button class="filter-btn active" data-filter="all" type="button">সব কাজ</button>
            <button class="filter-btn" data-filter="facebook" type="button">ফেসবুক</button>
            <button class="filter-btn" data-filter="instagram" type="button">ইনস্টাগ্রাম</button>
            <button class="filter-btn" data-filter="youtube" type="button">ইউটিউব</button>
            <button class="filter-btn" data-filter="design" type="button">ডিজাইন</button>
          </div>
          <div class="portfolio">
            <?php foreach ($portfolio as $item): ?>
              <img data-cat="<?= htmlspecialchars($item['cat'], ENT_QUOTES, 'UTF-8') ?>" src="<?= htmlspecialchars($item['img'], ENT_QUOTES, 'UTF-8') ?>" alt="Project image" />
            <?php endforeach; ?>
          </div>
        </div>
      </section>

      <section class="section" id="contact">
        <div class="container">
          <h2 class="section-title">যোগাযোগ করুন</h2>
          <p class="section-sub">প্রজেক্ট শুরু করতে আজই আমাদের সাথে কথা বলুন</p>
          <div class="contact">
            <form class="card">
              <div class="field"><label>আপনার নাম</label><input placeholder="আপনার নাম লিখুন" /></div>
              <div class="field"><label>ইমেইল</label><input placeholder="you@example.com" /></div>
              <div class="field"><label>ফোন নম্বর</label><input placeholder="+8801X-XXXXXXX" /></div>
              <div class="field"><label>মেসেজ</label><textarea placeholder="আপনার প্রজেক্ট সম্পর্কে লিখুন..."></textarea></div>
              <button class="btn btn-primary" type="button">বার্তা পাঠান</button>
            </form>
            <aside class="card">
              <h3>সরাসরি যোগাযোগ করুন</h3>
              <p>📞 +৮৮০ ১৭১২-৩৪৫৬৭৮</p>
              <p>✉️ contact@example.com</p>
              <p>📍 ঢাকা, বাংলাদেশ</p>
              <div class="note">দ্রুত রিপ্লাই এবং কাস্টম প্রপোজাল পেতে এখনই যোগাযোগ করুন।</div>
            </aside>
          </div>
        </div>
      </section>
    </main>

    <footer class="footer">
      <div class="container footer-inner">
        <p>© ২০২৬ বিশ্বাসযোগ্য এজেন্সি — প্রফেশনাল সোশ্যাল মিডিয়া মার্কেটিং</p>
        <div class="footer-links">
          <a href="#">ফেসবুক</a>
          <a href="#">ইনস্টাগ্রাম</a>
          <a href="#">হোয়াটসঅ্যাপ</a>
        </div>
      </div>
    </footer>

    <script src="javascript/app.js"></script>
  </body>
</html>
