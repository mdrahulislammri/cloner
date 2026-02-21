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
  <title>404 - Page Not Found | GreenTech</title>
  <meta name="robots" content="noindex, nofollow">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #ffffff;
      /* Grid line design */
      background-image: 
        linear-gradient(to right, #f1f5f9 1px, transparent 1px),
        linear-gradient(to bottom, #f1f5f9 1px, transparent 1px);
      background-size: 45px 45px;
    }
    .glass-card {
      background: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(34, 197, 94, 0.15);
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.05);
    }
    .dot-pattern {
      background-image: radial-gradient(#22c55e 1px, transparent 1px);
      background-size: 15px 15px;
    }
    .number-glitch {
      font-feature-settings: "tnum";
      font-variant-numeric: tabular-nums;
    }
  </style>
</head>
<body class="text-slate-800 antialiased overflow-hidden">

  <main class="min-h-screen flex items-center justify-center px-6 relative">
    
    <div class="absolute inset-0 dot-pattern opacity-[0.03] -z-10"></div>
    
    <div class="relative w-full max-w-2xl">
      <div class="absolute -top-24 -left-12 text-[15rem] font-black text-slate-50 select-none -z-10 animate__animated animate__fadeIn">404</div>

      <section class="glass-card rounded-[3rem] p-10 md:p-16 text-center animate__animated animate__fadeInUp">
        
        <div class="mb-6">
          <span class="inline-block px-5 py-1.5 bg-green-50 text-green-600 text-[10px] font-black uppercase tracking-[0.3em] rounded-full border border-green-100">
            Lost in Space
          </span>
        </div>

        <h1 class="text-9xl font-black text-slate-900 tracking-tighter mb-4 number-glitch">
          4<span class="text-green-500">0</span>4
        </h1>

        <div class="space-y-4">
          <h2 class="text-2xl font-bold text-slate-800">পেজটি খুঁজে পাওয়া যায়নি!</h2>
          <p class="text-slate-500 max-w-sm mx-auto leading-relaxed">
            আপনি যে পেজটি খুঁজছেন তা হয়তো ডিলিট করা হয়েছে অথবা ভুল লিঙ্কে ক্লিক করেছেন। চিন্তার কিছু নেই, আপনি মূল পাতায় ফিরে যেতে পারেন।
          </p>
        </div>

        <div class="mt-10 mb-10 flex justify-center items-center gap-1 opacity-40">
            <div class="h-1 w-8 bg-green-500 rounded-full"></div>
            <div class="h-1 w-2 bg-slate-200 rounded-full"></div>
            <div class="h-1 w-2 bg-slate-200 rounded-full"></div>
        </div>

        <div class="flex flex-col sm:flex-row justify-center gap-4">
          <a href="<?= htmlspecialchars($homeUrl) ?>" 
             class="flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white font-black px-8 py-4 rounded-2xl transition-all hover:scale-105 shadow-xl shadow-green-100 active:scale-95">
             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
             Go to Home
          </a>
          <a href="<?= htmlspecialchars($homeUrl . "#contact") ?>" 
             class="flex items-center justify-center gap-2 bg-white hover:bg-slate-50 text-slate-700 border-2 border-slate-100 font-bold px-8 py-4 rounded-2xl transition-all hover:scale-105 active:scale-95">
             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
             Contact Support
          </a>
        </div>

        <div class="mt-12 flex justify-between border-t border-slate-50 pt-8 text-[10px] text-slate-400 font-black uppercase tracking-widest">
            <div class="flex flex-col items-start italic">
                <span>System: Online</span>
                <span class="text-green-500">Node: 01-Alpha</span>
            </div>
            <div class="flex flex-col items-end italic">
                <span>Error Log: #<?= rand(1000, 9999) ?></span>
                <span class="text-red-400 font-mono">Status: 404</span>
            </div>
        </div>
      </section>
    </div>
  </main>

  <footer class="py-10 text-center animate__animated animate__fadeIn animate__delay-1s">
    <div class="flex flex-col items-center justify-center gap-4">
      <div class="flex items-center gap-6">
        <a href="#" class="text-slate-400 hover:text-green-600 transition-colors text-[10px] font-bold uppercase tracking-[0.2em]">Security</a>
        <div class="w-1 h-1 bg-slate-200 rounded-full"></div>
        <a href="#" class="text-slate-400 hover:text-green-600 transition-colors text-[10px] font-bold uppercase tracking-[0.2em]">Privacy</a>
        <div class="w-1 h-1 bg-slate-200 rounded-full"></div>
        <a href="#" class="text-slate-400 hover:text-green-600 transition-colors text-[10px] font-bold uppercase tracking-[0.2em]">FAQ</a>
      </div>
      <p class="text-[9px] text-slate-400 font-medium uppercase tracking-[0.5em] leading-loose">
        &copy; 2026 <span class="text-green-600 font-black">GreenTech</span> Boost. All rights reserved.
      </p>
    </div>
  </footer>

</body>
</html>
