<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';

$basePath = rtrim((string)appEnv('BASE_URL', BASE_URL), '/');
$homeUrl = ($basePath === '' ? '' : $basePath) . '/index.php';
http_response_code(500);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>500 - Server Error | GreenTech</title>
  <meta name="robots" content="noindex, nofollow">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #ffffff;
      /* Grid line & dot combo */
      background-image: 
        radial-gradient(#22c55e15 1.5px, transparent 1.5px),
        linear-gradient(to right, #fcfdfe 1px, transparent 1px),
        linear-gradient(to bottom, #fcfdfe 1px, transparent 1px);
      background-size: 30px 30px, 60px 60px, 60px 60px;
    }
    .glass-card {
      background: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(34, 197, 94, 0.1);
      box-shadow: 0 40px 100px -20px rgba(0, 0, 0, 0.05);
    }
    .status-ping {
      position: relative;
      display: inline-flex;
    }
    .status-ping .dot {
      height: 8px;
      width: 8px;
      background-color: #eab308; /* Yellow for maintenance/error */
      border-radius: 50%;
    }
    .status-ping .ping {
      position: absolute;
      height: 100%;
      width: 100%;
      background-color: #eab308;
      border-radius: 50%;
      opacity: 0.75;
      animation: ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;
    }
    @keyframes ping {
      75%, 100% { transform: scale(2.5); opacity: 0; }
    }
    .number-font {
      font-feature-settings: "tnum";
      font-variant-numeric: tabular-nums;
    }
  </style>
</head>
<body class="text-slate-800 antialiased overflow-hidden">

  <main class="min-h-screen flex items-center justify-center px-6 relative">
    
    <div class="fixed top-0 left-0 w-full h-1 bg-slate-50 overflow-hidden">
        <div class="h-full bg-yellow-500 animate__animated animate__slideInLeft animate__infinite" style="width: 25%; animation-duration: 5s;"></div>
    </div>

    <div class="relative w-full max-w-2xl">
      <div class="absolute -top-32 -right-10 text-[18rem] font-black text-slate-50 select-none -z-10 animate__animated animate__fadeIn">500</div>

      <section class="glass-card rounded-[3.5rem] p-10 md:p-16 text-center animate__animated animate__fadeInUp">
        
        <div class="mb-8">
          <div class="inline-flex items-center gap-3 px-5 py-2 rounded-full bg-yellow-50 border border-yellow-100 shadow-sm">
            <span class="status-ping">
                <span class="ping"></span>
                <span class="dot"></span>
            </span>
            <span class="text-[10px] font-black text-yellow-700 uppercase tracking-[0.2em]">Server Interruption</span>
          </div>
        </div>

        <h1 class="text-9xl font-black text-slate-900 tracking-tighter mb-6 number-font">
          5<span class="text-green-500">0</span>0
        </h1>

        <div class="space-y-4 max-w-md mx-auto">
          <h2 class="text-2xl font-bold text-slate-800">সার্ভারে সমস্যা দেখা দিয়েছে</h2>
          <p class="text-slate-500 leading-relaxed">
            দুঃখিত, আমাদের সিস্টেমে সাময়িক বিভ্রাট ঘটেছে। আমাদের টিম এটি সমাধানে কাজ করছে। অনুগ্রহ করে কিছুক্ষণ পর আবার চেষ্টা করুন।
          </p>
        </div>

        <div class="mt-10 mb-10 h-px w-24 bg-gradient-to-r from-transparent via-slate-200 to-transparent mx-auto"></div>

        <div class="flex flex-col sm:flex-row justify-center gap-4">
          <a href="<?= htmlspecialchars($homeUrl) ?>" 
             class="flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white font-black px-10 py-4 rounded-2xl transition-all hover:shadow-xl hover:shadow-green-100 active:scale-95">
             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
             Return Home
          </a>
          <a href="<?= htmlspecialchars($homeUrl . "#contact") ?>" 
             class="flex items-center justify-center gap-2 bg-white hover:bg-slate-50 text-slate-700 border-2 border-slate-100 font-bold px-10 py-4 rounded-2xl transition-all active:scale-95">
             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
             Report Issue
          </a>
        </div>

        <div class="mt-14 flex justify-between items-center text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em]">
            <div class="text-left">
                <p>Error: <span class="text-slate-800">Internal_Server</span></p>
                <p>Node: <span class="text-green-600">Secure_Server_01</span></p>
            </div>
            <div class="text-right">
                <p>Time: <span class="text-slate-800"><?= date('H:i:s') ?></span></p>
                <p>Status: <span class="text-yellow-600">Pending Fix</span></p>
            </div>
        </div>
      </section>
    </div>
  </main>

  <footer class="py-12 text-center animate__animated animate__fadeIn animate__delay-1s">
    <div class="flex flex-col items-center justify-center gap-4">
      <div class="flex items-center gap-8">
        <a href="#" class="text-slate-400 hover:text-green-600 transition-colors text-[10px] font-black uppercase tracking-widest">Documentation</a>
        <div class="w-1.5 h-1.5 bg-green-200 rounded-full"></div>
        <a href="#" class="text-slate-400 hover:text-green-600 transition-colors text-[10px] font-black uppercase tracking-widest">System Status</a>
        <div class="w-1.5 h-1.5 bg-green-200 rounded-full"></div>
        <a href="#" class="text-slate-400 hover:text-green-600 transition-colors text-[10px] font-black uppercase tracking-widest">Community</a>
      </div>
      <p class="text-[9px] text-slate-400 font-medium uppercase tracking-[0.4em] mt-2">
        &copy; 2026 <span class="text-green-600 font-black">GreenTech</span> Boost. All rights reserved.
      </p>
    </div>
  </footer>

</body>
</html>
