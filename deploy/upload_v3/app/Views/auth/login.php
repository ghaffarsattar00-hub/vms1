<!DOCTYPE html>
<html lang="en" class="antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In | VMS</title>
    <link rel="icon" type="image/svg+xml" href="<?php echo BASE_URL; ?>vms-logo.svg">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] },
                }
            }
        }
    </script>
    <style>
        * { font-family: 'Inter', system-ui, sans-serif; }
        .login-bg {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f766e 100%);
        }
        .glass { background: rgba(255,255,255,0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.08); }
        .floating-label { transition: all 0.2s ease; }
        .floating-label:focus-within { transform: translateY(-2px); }
        .input-glow:focus { box-shadow: 0 0 0 3px rgba(20,184,166,0.15); }
        .float-anim { animation: floatUp 6s ease-in-out infinite; }
        @keyframes floatUp { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-12px); } }
        .grid-pattern {
            background-image: radial-gradient(rgba(255,255,255,0.05) 1px, transparent 1px);
            background-size: 30px 30px;
        }
        .slide-in { animation: slideIn 0.3s ease-out forwards; }
        @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        .toast-exit { animation: toastOut 0.3s ease-in forwards; }
        @keyframes toastOut { to { transform: translateX(100%); opacity: 0; } }
        .fade-up { animation: fadeUp 0.6s ease-out forwards; opacity: 0; }
        @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }
        .fade-up { transform: translateY(20px); }
    </style>
</head>
<body class="bg-slate-900">

<!-- Toast Container -->
<div id="toast-container" class="fixed top-6 right-6 left-6 sm:left-auto z-[100] flex flex-col gap-3 sm:max-w-sm">
<?php if (isset($_SESSION['success'])): ?>
    <div class="toast slide-in bg-emerald-500 text-white px-5 py-3.5 rounded-2xl shadow-2xl shadow-emerald-500/20 flex items-center gap-3">
        <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg></div>
        <span class="font-medium text-sm"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></span>
    </div>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <div class="toast slide-in bg-red-500 text-white px-5 py-3.5 rounded-2xl shadow-2xl shadow-red-500/20 flex items-center gap-3">
        <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg></div>
        <span class="font-medium text-sm"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></span>
    </div>
<?php endif; ?>
</div>

<!-- Mobile: Full gradient background with centered card -->
<div class="md:hidden min-h-screen login-bg relative flex flex-col items-center justify-center p-6 overflow-hidden">
    <div class="grid-pattern absolute inset-0"></div>
    <div class="absolute top-10 right-10 w-48 h-48 bg-teal-500/10 rounded-full blur-3xl float-anim"></div>
    <div class="absolute bottom-20 left-10 w-32 h-32 bg-blue-500/10 rounded-full blur-2xl float-anim" style="animation-delay:2s"></div>

    <div class="relative z-10 w-full max-w-md">
        <!-- Mobile Logo -->
        <div class="flex items-center gap-3 mb-8">
            <div class="w-11 h-11 rounded-xl flex items-center justify-center shadow-lg shadow-teal-500/30 overflow-hidden">
                <img src="<?php echo BASE_URL; ?>vms-logo.svg" alt="VMS" class="w-full h-full object-cover">
            </div>
            <span class="text-white text-2xl font-bold tracking-tight">VMS</span>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-3xl shadow-2xl shadow-black/20 p-8 fade-up">
            <div class="mb-6">
                <h2 class="text-2xl font-extrabold text-slate-900">Welcome back</h2>
                <p class="text-slate-500 mt-1.5">Sign in to your VMS account</p>
            </div>

            <form method="POST" action="<?php echo BASE_URL; ?>login" class="space-y-4">
                <?php echo $csrfInput; ?>

                <div class="floating-label">
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Email Address</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"><svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg></span>
                        <input type="email" name="email" required placeholder="you@example.com"
                               class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:ring-0 focus:border-teal-500 input-glow outline-none transition placeholder:text-slate-400">
                    </div>
                </div>

                <div class="floating-label">
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"><svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg></span>
                        <input type="password" name="password" required placeholder="Enter your password"
                               class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:ring-0 focus:border-teal-500 input-glow outline-none transition placeholder:text-slate-400">
                    </div>
                </div>

                <div class="flex items-center justify-end">
                    <a href="<?php echo BASE_URL; ?>forgot-password" class="text-sm text-teal-600 hover:underline font-medium">Forgot password?</a>
                </div>

                <button type="submit" class="w-full py-3.5 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-xl shadow-lg shadow-slate-900/20 transition-all duration-200 transform hover:scale-[1.01] active:scale-[0.99]">
                    Sign In
                </button>
            </form>

            <div class="mt-5 text-center">
                <p class="text-sm text-slate-500">New parent? <a href="<?php echo BASE_URL; ?>register" class="text-teal-600 font-semibold hover:text-teal-700 transition">Create account</a></p>
            </div>
        </div>

        <p class="text-slate-500 text-xs text-center mt-6">&copy; 2026 VMS. Built for Pakistan's health infrastructure.</p>
    </div>
</div>

<!-- Desktop: Split-screen layout -->
<div class="hidden md:flex min-h-screen w-full bg-white overflow-hidden">
    <!-- Left Panel - Branding -->
    <div class="w-1/2 login-bg relative overflow-hidden flex flex-col justify-between">
        <div class="grid-pattern absolute inset-0"></div>
        <div class="relative z-10 flex flex-col justify-between p-8 lg:p-12 w-full h-full">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-lg shadow-teal-500/30 overflow-hidden">
                    <img src="<?php echo BASE_URL; ?>vms-logo.svg" alt="VMS" class="w-full h-full object-cover">
                </div>
                <span class="text-white text-xl font-bold tracking-tight">VMS</span>
            </div>
            <div class="max-w-lg">
                <h1 class="text-4xl lg:text-5xl font-black text-white leading-tight mb-6">VMS - Advanced <br><span class="text-teal-400">Immunization System</span></h1>
                <p class="text-slate-400 text-base lg:text-lg leading-relaxed">A comprehensive digital solution designed to streamline infant vaccination records. Connecting parents with healthcare providers like Aga Khan and Shifa International for seamless appointment scheduling, automated alerts, and real-time inventory tracking.</p>
                <div class="flex flex-wrap gap-4 mt-8 lg:mt-10">
                    <div class="float-anim"><p class="text-2xl lg:text-3xl font-extrabold text-white"><?php echo $hospitalCount ?? 0; ?></p><p class="text-sm text-slate-400 mt-1">Hospitals</p></div>
                    <div class="float-anim" style="animation-delay: 0.5s"><p class="text-2xl lg:text-3xl font-extrabold text-white"><?php echo $vaccineCount ?? 0; ?></p><p class="text-sm text-slate-400 mt-1">Vaccines</p></div>
                    <div class="float-anim" style="animation-delay: 1s"><p class="text-2xl lg:text-3xl font-extrabold text-teal-400">100%</p><p class="text-sm text-slate-400 mt-1">Digital</p></div>
                </div>
                <div class="mt-6 flex flex-wrap gap-2">
                    <span class="inline-flex items-center gap-1.5 text-xs text-slate-400 bg-white/5 px-3 py-1.5 rounded-full border border-white/10">
                        <svg class="w-3 h-3 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Zero Missed Doses
                    </span>
                    <span class="inline-flex items-center gap-1.5 text-xs text-slate-400 bg-white/5 px-3 py-1.5 rounded-full border border-white/10">
                        <svg class="w-3 h-3 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Real-Time Tracking
                    </span>
                    <span class="inline-flex items-center gap-1.5 text-xs text-slate-400 bg-white/5 px-3 py-1.5 rounded-full border border-white/10">
                        <svg class="w-3 h-3 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        100% Digital Records
                    </span>
                </div>
            </div>
            <p class="text-slate-600 text-sm">&copy; 2026 VMS. Built for Pakistan's health infrastructure.</p>
        </div>
        <div class="absolute top-20 right-20 w-72 h-72 bg-teal-500/10 rounded-full blur-3xl float-anim"></div>
        <div class="absolute bottom-32 right-40 w-48 h-48 bg-blue-500/10 rounded-full blur-2xl float-anim" style="animation-delay:2s"></div>
    </div>

    <!-- Right Panel - Login Form -->
    <div class="w-1/2 flex items-center justify-center bg-white p-12">
        <div class="w-full max-w-md fade-up">
            <div class="mb-8">
                <h2 class="text-2xl font-extrabold text-slate-900">Welcome back</h2>
                <p class="text-slate-500 mt-1.5">Sign in to your VMS account</p>
            </div>

            <form method="POST" action="<?php echo BASE_URL; ?>login" class="space-y-5">
                <?php echo $csrfInput; ?>

                <div class="floating-label">
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Email Address</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"><svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg></span>
                        <input type="email" name="email" required placeholder="you@example.com"
                               class="w-full pl-11 pr-4 py-3.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-0 focus:border-teal-500 input-glow outline-none transition placeholder:text-slate-400">
                    </div>
                </div>

                <div class="floating-label">
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"><svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg></span>
                        <input type="password" name="password" required placeholder="Enter your password"
                               class="w-full pl-11 pr-4 py-3.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-0 focus:border-teal-500 input-glow outline-none transition placeholder:text-slate-400">
                    </div>
                </div>

                <div class="flex items-center justify-end">
                    <a href="<?php echo BASE_URL; ?>forgot-password" class="text-sm text-teal-600 hover:underline font-medium">Forgot password?</a>
                </div>

                <button type="submit" class="w-full py-3.5 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-xl shadow-lg shadow-slate-900/20 hover:shadow-xl hover:shadow-slate-900/30 transition-all duration-200 transform hover:scale-[1.01] active:scale-[0.99]">
                    Sign In
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-slate-500">New parent? <a href="<?php echo BASE_URL; ?>register" class="text-teal-600 font-semibold hover:text-teal-700 transition">Create account</a></p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.toast').forEach(function(t) {
        setTimeout(function() { t.classList.add('toast-exit'); setTimeout(function() { t.remove(); }, 300); }, 4000);
    });
});
</script>
</body>
</html>
