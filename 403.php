<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';

$basePath = rtrim((string)appEnv('BASE_URL', BASE_URL), '/');
$homeUrl = ($basePath === '' ? '' : $basePath) . '/index.php';
http_response_code(403);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>403 - Restricted Access | GreenTech</title>
  <meta name="robots" content="noindex, nofollow">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #ffffff;
      /* Grid line & dot design */
      background-image: radial-gradient(#22c55e22 1px, transparent 1px), 
                        linear-gradient(to right, #f8fafc 1px, transparent 1px),
                        linear-gradient(to bottom, #f8fafc 1px, transparent 1px);
      background-size: 20px 20px, 40px 40px, 40px 40px;
    }
    .glass-card {
      background: rgba(255, 255, 255, 0.85);
      backdrop-filter: blur(15px);
      border: 1px solid rgba(34, 197, 94, 0.2);
      box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.05);
    }
    .status-badge {
      animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    @keyframes pulse {
      0%, 100% { opacity: 1; }
      50% { opacity: .5; }
    }
    .number-display {
      font-feature-settings: "tnum";
      font-variant-numeric: tabular-nums;
    }
  </style>
</head>
<body class="text-slate-800 antialiased overflow-hidden">

  <main class="min-h-screen flex flex-col items-center justify-center px-6 relative">
    
    <div class="fixed top-0 left-0 w-full h-1 bg-slate-50 overflow-hidden">
        <div class="h-full bg-green-500 animate__animated animate__slideInLeft animate__infinite" style="width: 40%; animation-duration: 4s;"></div>
    </div>

    <section class="glass-card relative z-10 rounded-[3rem] p-10 md:p-16 text-center max-w-2xl w-full animate__animated animate__zoomIn">
      
      <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-red-50 text-red-600 text-[10px] font-black uppercase tracking-[0.2em] mb-8 border border-red-100 shadow-sm">
        <span class="status-badge h-2 w-2 bg-red-600 rounded-full"></span> 
        Access Restricted
      </div>

      <h1 class="text-9xl font-black text-slate-900 tracking-tighter mb-4 number-display">
        4<span class="text-green-500">0</span>3
      </h1>
      
      <div class="space-y-4 max-w-md mx-auto">
        <h2 class="text-2xl font-bold text-slate-800">অ্যাক্সেস অনুমতি নেই</h2>
        <p class="text-slate-500 leading-relaxed">
          দুঃখিত, এই পেজটি আপনার দেখার অনুমতি নেই। আপনি যদি মনে করেন এটি কোনো ভুল, তবে অ্যাডমিনের সাথে যোগাযোগ করুন।
        </p>
      </div>

      <div class="mt-12 flex flex-col sm:flex-row justify-center gap-4">
        <a href="<?= htmlspecialchars($homeUrl) ?>" 
           class="flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white font-black px-8 py-4 rounded-2xl transition-all hover:-translate-y-1 shadow-lg shadow-green-100 active:scale-95">
           <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
           Go to Home
        </a>
        <a href="<?= htmlspecialchars(($basePath === "" ? "" : $basePath) . "/admin/login.php") ?>" 
           class="flex items-center justify-center gap-2 bg-white hover:bg-slate-50 text-slate-700 border-2 border-slate-100 font-bold px-8 py-4 rounded-2xl transition-all hover:-translate-y-1 active:scale-95">
           <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
           Admin Login
        </a>
      </div>

      <div class="mt-12 pt-8 border-t border-slate-50 flex justify-between items-center text-[10px] text-slate-400 font-bold uppercase tracking-widest">
          <span>Error Code: 00403</span>
          <span class="text-green-500">Security: High</span>
      </div>
    </section>

    <div class="absolute -bottom-10 -left-10 text-[20rem] font-black text-slate-50 select-none -z-10 opacity-50">403</div>
  </main>

  <footer class="py-8 text-center animate__animated animate__fadeIn animate__delay-1s">
    <div class="flex flex-col items-center justify-center gap-3">
      <div class="flex items-center gap-4">
        <a href="#" class="text-slate-400 hover:text-green-600 transition-colors text-[10px] font-bold uppercase tracking-widest">Privacy</a>
        <div class="w-1 h-1 bg-slate-200 rounded-full"></div>
        <a href="#" class="text-slate-400 hover:text-green-600 transition-colors text-[10px] font-bold uppercase tracking-widest">Terms</a>
        <div class="w-1 h-1 bg-slate-200 rounded-full"></div>
        <a href="#" class="text-slate-400 hover:text-green-600 transition-colors text-[10px] font-bold uppercase tracking-widest">Help</a>
      </div>
      <p class="text-[9px] text-slate-400 font-medium uppercase tracking-[0.4em]">
        &copy; 2026 <span class="text-green-600 font-black">GreenTech</span> Boost. System Secure.
      </p>
    </div>
  </footer>

</body>
</html>
