<?php
$services = [
  ['icon' => '📘', 'title' => 'ফেসবুক মার্কেটিং', 'desc' => 'টার্গেটেড অডিয়েন্সে পৌঁছে লিড ও সেলস বাড়ানোর স্মার্ট ক্যাম্পেইন।'],
  ['icon' => '📷', 'title' => 'ইনস্টাগ্রাম গ্রোথ', 'desc' => 'রিলস, কনটেন্ট প্ল্যান এবং ব্র্যান্ড ভিজিবিলিটি বাড়ানোর কৌশল।'],
  ['icon' => '▶️', 'title' => 'ইউটিউব মার্কেটিং', 'desc' => 'ভিডিও SEO, রিটেনশন অপ্টিমাইজেশন এবং সাবস্ক্রাইবার গ্রোথ।'],
  ['icon' => '🎯', 'title' => 'লিড জেনারেশন', 'desc' => 'সেলস ফানেলে হাই-কোয়ালিটি লিড এনে কনভার্সন বাড়ানো।'],
  ['icon' => '⚙️', 'title' => 'এড ম্যানেজমেন্ট', 'desc' => 'বাজেট অপ্টিমাইজ করে ROI ফোকাসড বিজ্ঞাপন পরিচালনা।'],
  ['icon' => '💬', 'title' => 'কমিউনিটি ম্যানেজমেন্ট', 'desc' => 'ইনবক্স/কমেন্ট রিপ্লাই করে গ্রাহক ট্রাস্ট ও ব্র্যান্ড ভ্যালু বৃদ্ধি।'],
];

$portfolio = [
  ['cat' => 'facebook', 'img' => 'https://images.unsplash.com/photo-1611262588024-d12430b98920?auto=format&fit=crop&w=900&q=80'],
  ['cat' => 'instagram', 'img' => 'https://images.unsplash.com/photo-1611162618071-b39a2ec055fb?auto=format&fit=crop&w=900&q=80'],
  ['cat' => 'design', 'img' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=900&q=80'],
  ['cat' => 'youtube', 'img' => 'https://images.unsplash.com/photo-1616469829581-73993eb86b02?auto=format&fit=crop&w=900&q=80'],
  ['cat' => 'facebook', 'img' => 'https://images.unsplash.com/photo-1563013544-824ae1b704d3?auto=format&fit=crop&w=900&q=80'],
  ['cat' => 'instagram', 'img' => 'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?auto=format&fit=crop&w=900&q=80'],
];

$stats = [
  ['value' => '৫+', 'label' => 'বছরের অভিজ্ঞতা'],
  ['value' => '৫০০+', 'label' => 'সফল প্রজেক্ট'],
  ['value' => '২৪/৭', 'label' => 'সাপোর্ট'],
  ['value' => '৯৮%', 'label' => 'সন্তুষ্ট ক্লায়েন্ট'],
];
?>
<!doctype html>
<html lang="bn">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>বিশ্বাসযোগ্য | সোশ্যাল মিডিয়া মার্কেটিং এক্সপার্ট</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              brand: {
                50: '#effff5',
                100: '#ddfce9',
                200: '#b8f5d1',
                500: '#22b963',
                600: '#169b50',
                700: '#127a41',
                900: '#143b27'
              }
            },
            boxShadow: {
              soft: '0 16px 35px rgba(18,122,65,.10)'
            }
          }
        }
      }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body{font-family:'Hind Siliguri',sans-serif}</style>
  </head>
  <body class="bg-brand-50 text-brand-900">
    <header class="sticky top-0 z-50 border-b border-brand-200/70 bg-white/90 backdrop-blur">
      <div class="mx-auto flex w-[min(1120px,92vw)] items-center justify-between py-4">
        <a class="font-bold text-brand-700 text-lg" href="#">বিশ্বাসযোগ্য এজেন্সি</a>
        <button id="menuBtn" class="rounded-lg border border-brand-200 px-3 py-1 text-brand-700 md:hidden">☰</button>
        <nav id="menu" class="hidden gap-6 text-sm font-semibold text-brand-700 md:flex">
          <a href="#about">আমার সম্পর্কে</a>
          <a href="#services">সার্ভিস</a>
          <a href="#portfolio">পোর্টফোলিও</a>
          <a href="#contact">যোগাযোগ</a>
        </nav>
      </div>
      <nav id="mobileMenu" class="mx-auto hidden w-[min(1120px,92vw)] pb-3 md:hidden">
        <div class="grid gap-2 rounded-xl border border-brand-100 bg-white p-3 text-sm font-semibold text-brand-700">
          <a href="#about">আমার সম্পর্কে</a>
          <a href="#services">সার্ভিস</a>
          <a href="#portfolio">পোর্টফোলিও</a>
          <a href="#contact">যোগাযোগ</a>
        </div>
      </nav>
    </header>

    <main>
      <section class="border-b border-brand-200 bg-gradient-to-br from-brand-100 via-white to-white py-14">
        <div class="mx-auto grid w-[min(1120px,92vw)] items-center gap-8 md:grid-cols-2">
          <div>
            <span class="inline-block rounded-full border border-brand-200 bg-white px-4 py-1 text-sm font-semibold text-brand-700">✅ ১০০% Trusted মার্কেটিং সলিউশন</span>
            <h1 class="mt-4 text-4xl font-bold leading-tight md:text-6xl">বিশ্বাসযোগ্য সোশ্যাল মিডিয়া বিক্রয়ি এক্সপার্ট</h1>
            <p class="mt-4 max-w-xl text-lg text-brand-700/80">Facebook, Instagram এবং YouTube–এ আপনার ব্যবসার গ্রোথ বাড়াতে প্ল্যান, কনটেন্ট এবং এড ম্যানেজমেন্ট একসাথে করি।</p>
            <div class="mt-6 flex flex-wrap gap-3">
              <button class="rounded-full bg-gradient-to-r from-brand-500 to-brand-700 px-6 py-3 font-semibold text-white shadow-soft">যোগাযোগ করুন</button>
              <button class="rounded-full border border-brand-200 bg-white px-6 py-3 font-semibold text-brand-700">আরো জানুন</button>
            </div>
            <div class="mt-4 flex flex-wrap gap-4 text-sm text-brand-700/80">
              <span>📍 ঢাকা, বাংলাদেশ</span><span>📞 দ্রুত সাপোর্ট</span><span>🕒 ২৪/৭</span>
            </div>
          </div>
          <div class="relative rounded-3xl border border-brand-200 bg-white p-4 shadow-soft">
            <img class="h-[430px] w-full rounded-2xl object-cover" src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=1000&q=80" alt="Profile">
            <span class="absolute bottom-8 right-8 rounded-full bg-brand-700/90 px-4 py-2 text-sm text-white">প্রো মার্কেটিং কনসালটেন্ট</span>
          </div>
        </div>
      </section>

      <section id="about" class="py-16">
        <div class="mx-auto grid w-[min(1120px,92vw)] gap-4 md:grid-cols-[1.1fr_.9fr]">
          <div class="rounded-2xl border border-brand-100 bg-white p-6 shadow-soft">
            <h2 class="text-3xl font-bold">আমার সম্পর্কে</h2>
            <p class="mt-2 text-brand-700/80">আপনার ব্যবসার ডিজিটাল সফলতার জন্য প্র্যাক্টিক্যাল এবং ফলাফলভিত্তিক মার্কেটিং সাপোর্ট।</p>
            <h3 class="mt-4 text-2xl font-semibold">আপনার ব্র্যান্ড কেন আমি গ্রোথ করতে পারি</h3>
            <ul class="mt-3 grid gap-2 text-brand-700">
              <li>✔ মার্কেট রিসার্চ ভিত্তিক কাস্টম প্ল্যান</li>
              <li>✔ ROI ফোকাসড ক্যাম্পেইন অপ্টিমাইজেশন</li>
              <li>✔ ব্র্যান্ড ভয়েস ধরে কনটেন্ট ক্যালেন্ডার</li>
              <li>✔ রেগুলার রিপোর্টিং এবং পারফরম্যান্স ট্র্যাকিং</li>
            </ul>
          </div>
          <div class="grid grid-cols-2 gap-3">
            <?php foreach ($stats as $item): ?>
              <article class="rounded-2xl border border-brand-100 bg-white p-6 text-center shadow-soft">
                <h4 class="text-3xl font-bold text-brand-700"><?= $item['value'] ?></h4>
                <p class="mt-2 text-brand-700/80"><?= $item['label'] ?></p>
              </article>
            <?php endforeach; ?>
          </div>
        </div>
      </section>

      <section id="services" class="bg-white py-16">
        <div class="mx-auto w-[min(1120px,92vw)]">
          <h2 class="text-center text-3xl font-bold">আমাদের সার্ভিসসমূহ</h2>
          <p class="mt-2 text-center text-brand-700/80">আপনার ব্যবসার জন্য সম্পূর্ণ ডিজিটাল মার্কেটিং সমাধান</p>
          <div class="mt-8 grid gap-3 md:grid-cols-3">
            <?php foreach ($services as $service): ?>
              <article class="rounded-2xl border border-brand-100 bg-brand-50 p-5 shadow-soft">
                <span class="text-2xl"><?= $service['icon'] ?></span>
                <h4 class="mt-2 text-xl font-semibold"><?= $service['title'] ?></h4>
                <p class="mt-2 text-brand-700/80"><?= $service['desc'] ?></p>
              </article>
            <?php endforeach; ?>
          </div>
        </div>
      </section>

      <section id="portfolio" class="py-16">
        <div class="mx-auto w-[min(1120px,92vw)]">
          <h2 class="text-center text-3xl font-bold">পোর্টফোলিও</h2>
          <p class="mt-2 text-center text-brand-700/80">আমাদের সাম্প্রতিক কাজগুলো</p>
          <div id="filters" class="mt-6 flex flex-wrap justify-center gap-2">
            <button data-filter="all" class="filter-btn rounded-full bg-brand-700 px-4 py-2 text-sm font-semibold text-white">সব কাজ</button>
            <button data-filter="facebook" class="filter-btn rounded-full border border-brand-200 bg-white px-4 py-2 text-sm font-semibold text-brand-700">ফেসবুক</button>
            <button data-filter="instagram" class="filter-btn rounded-full border border-brand-200 bg-white px-4 py-2 text-sm font-semibold text-brand-700">ইনস্টাগ্রাম</button>
            <button data-filter="youtube" class="filter-btn rounded-full border border-brand-200 bg-white px-4 py-2 text-sm font-semibold text-brand-700">ইউটিউব</button>
            <button data-filter="design" class="filter-btn rounded-full border border-brand-200 bg-white px-4 py-2 text-sm font-semibold text-brand-700">ডিজাইন</button>
          </div>
          <div class="mt-6 grid gap-3 sm:grid-cols-2 md:grid-cols-3">
            <?php foreach ($portfolio as $item): ?>
              <img data-cat="<?= $item['cat'] ?>" class="portfolio-item h-52 w-full rounded-xl object-cover shadow-soft" src="<?= $item['img'] ?>" alt="work">
            <?php endforeach; ?>
          </div>
        </div>
      </section>

      <section id="contact" class="bg-white py-16">
        <div class="mx-auto w-[min(1120px,92vw)]">
          <h2 class="text-center text-3xl font-bold">যোগাযোগ করুন</h2>
          <p class="mt-2 text-center text-brand-700/80">প্রজেক্ট শুরু করতে আজই আমাদের সাথে কথা বলুন</p>
          <div class="mt-8 grid gap-4 md:grid-cols-2">
            <form class="rounded-2xl border border-brand-100 bg-brand-50 p-6 shadow-soft">
              <label class="mb-1 block font-semibold text-brand-700">আপনার নাম</label>
              <input class="mb-3 w-full rounded-xl border border-brand-200 bg-white p-3 outline-none focus:ring-2 focus:ring-brand-200" placeholder="আপনার নাম লিখুন" />
              <label class="mb-1 block font-semibold text-brand-700">ইমেইল</label>
              <input class="mb-3 w-full rounded-xl border border-brand-200 bg-white p-3 outline-none focus:ring-2 focus:ring-brand-200" placeholder="you@example.com" />
              <label class="mb-1 block font-semibold text-brand-700">ফোন নম্বর</label>
              <input class="mb-3 w-full rounded-xl border border-brand-200 bg-white p-3 outline-none focus:ring-2 focus:ring-brand-200" placeholder="+8801X-XXXXXXX" />
              <label class="mb-1 block font-semibold text-brand-700">মেসেজ</label>
              <textarea class="h-28 w-full rounded-xl border border-brand-200 bg-white p-3 outline-none focus:ring-2 focus:ring-brand-200" placeholder="আপনার প্রজেক্ট সম্পর্কে লিখুন..."></textarea>
              <button type="button" class="mt-4 w-full rounded-full bg-gradient-to-r from-brand-500 to-brand-700 px-5 py-3 font-semibold text-white">বার্তা পাঠান</button>
            </form>
            <div class="rounded-2xl border border-brand-100 bg-brand-50 p-6 shadow-soft">
              <h3 class="text-2xl font-semibold">সরাসরি যোগাযোগ করুন</h3>
              <div class="mt-4 space-y-2 text-brand-700/90">
                <p>📞 +৮৮০ ১৭১২-৩৪৫৬৭৮</p>
                <p>✉️ contact@example.com</p>
                <p>📍 ঢাকা, বাংলাদেশ</p>
              </div>
              <div class="mt-5 rounded-xl border border-brand-200 bg-white p-4 text-brand-700">দ্রুত রিপ্লাই এবং কাস্টম প্রপোজাল পেতে এখনই যোগাযোগ করুন।</div>
            </div>
          </div>
        </div>
      </section>
    </main>

    <footer class="border-t border-brand-200 bg-brand-100 py-7">
      <div class="mx-auto flex w-[min(1120px,92vw)] flex-col items-start justify-between gap-3 md:flex-row md:items-center">
        <div>
          <h4 class="font-bold text-brand-700">বিশ্বাসযোগ্য এজেন্সি</h4>
          <p class="text-sm text-brand-700/80">সোশ্যাল মিডিয়া গ্রোথ এবং বিজ্ঞাপন সলিউশন</p>
        </div>
        <div class="flex gap-4 text-sm font-semibold text-brand-700">
          <a href="#">ফেসবুক</a><a href="#">ইনস্টাগ্রাম</a><a href="#">হোয়াটসঅ্যাপ</a>
        </div>
      </div>
    </footer>

    <script src="app.js"></script>
  </body>
</html>
