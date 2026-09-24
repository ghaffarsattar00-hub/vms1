<!DOCTYPE html>
<html lang="en" class="antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account | VMS</title>
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
        .login-bg { background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f766e 100%); }
        .grid-pattern { background-image: radial-gradient(rgba(255,255,255,0.05) 1px, transparent 1px); background-size: 30px 30px; }
        .input-glow:focus { box-shadow: 0 0 0 3px rgba(20,184,166,0.15); }
        .slide-in { animation: slideIn 0.3s ease-out forwards; }
        @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        .toast-exit { animation: toastOut 0.3s ease-in forwards; }
        @keyframes toastOut { to { transform: translateX(100%); opacity: 0; } }
        .fade-up { animation: fadeUp 0.6s ease-out forwards; opacity: 0; transform: translateY(20px); }
        @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }
        .float-anim { animation: floatUp 6s ease-in-out infinite; }
        @keyframes floatUp { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-12px); } }
    </style>
</head>
<body class="bg-slate-900">

<!-- Toast Container -->
<div id="toast-container" class="fixed top-6 right-6 left-6 sm:left-auto z-[100] flex flex-col gap-3 sm:max-w-sm">
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

        <!-- Register Card -->
        <div class="bg-white rounded-3xl shadow-2xl shadow-black/20 p-8 fade-up">
            <div class="mb-6">
                <h2 class="text-2xl font-extrabold text-slate-900">Create your account</h2>
                <p class="text-slate-500 mt-1.5">Join as a parent to start booking vaccinations</p>
            </div>
            <form method="POST" action="<?php echo BASE_URL; ?>register" class="space-y-4">
                <?php echo $csrfInput; ?>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Full Name</label>
                    <input type="text" name="full_name" required placeholder="e.g. Ali Raza"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:ring-0 focus:border-teal-500 input-glow outline-none transition placeholder:text-slate-400">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Email Address</label>
                    <input type="email" name="email" required placeholder="you@example.com"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:ring-0 focus:border-teal-500 input-glow outline-none transition placeholder:text-slate-400">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Phone (Optional)</label>
                    <input type="text" name="phone" placeholder="+92 300 1234567"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:ring-0 focus:border-teal-500 input-glow outline-none transition placeholder:text-slate-400">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Password</label>
                    <input type="password" name="password" required minlength="6" placeholder="Min 6 characters"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:ring-0 focus:border-teal-500 input-glow outline-none transition placeholder:text-slate-400">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Confirm Password</label>
                    <input type="password" name="confirm_password" required minlength="6" placeholder="Repeat password"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:ring-0 focus:border-teal-500 input-glow outline-none transition placeholder:text-slate-400">
                </div>
                <button type="submit" class="w-full py-3.5 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-xl shadow-lg shadow-slate-900/20 transition-all duration-200 transform hover:scale-[1.01] active:scale-[0.99] mt-2">
                    Create Account
                </button>
            </form>
            <div class="mt-5 text-center">
                <p class="text-sm text-slate-500">Already registered? <a href="<?php echo BASE_URL; ?>login" class="text-teal-600 font-semibold hover:text-teal-700 transition">Sign in</a></p>
            </div>
        </div>

        <p class="text-slate-500 text-xs text-center mt-6">&copy; 2026 VMS.</p>
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
                <h1 class="text-4xl lg:text-5xl font-black text-white leading-tight mb-6">Secure Your Child's <br><span class="text-teal-400">Health Journey</span></h1>
                <p class="text-slate-400 text-base lg:text-lg leading-relaxed">Register now to schedule vaccinations, track immunization records, and never miss a life-saving dose.</p>
            </div>
            <p class="text-slate-600 text-sm">&copy; 2026 VMS.</p>
        </div>
        <div class="absolute top-20 right-20 w-72 h-72 bg-teal-500/10 rounded-full blur-3xl float-anim"></div>
    </div>

    <!-- Right Panel - Register Form -->
    <div class="w-1/2 flex items-center justify-center bg-white p-12">
        <div class="w-full max-w-md fade-up">
            <div class="mb-8">
                <h2 class="text-2xl font-extrabold text-slate-900">Create your account</h2>
                <p class="text-slate-500 mt-1.5">Join as a parent to start booking vaccinations</p>
            </div>
            <form method="POST" action="<?php echo BASE_URL; ?>register" class="space-y-4">
                <?php echo $csrfInput; ?>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Full Name</label>
                    <input type="text" name="full_name" required placeholder="e.g. Ali Raza"
                           class="w-full px-4 py-3.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-0 focus:border-teal-500 input-glow outline-none transition placeholder:text-slate-400">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Email Address</label>
                    <input type="email" name="email" required placeholder="you@example.com"
                           class="w-full px-4 py-3.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-0 focus:border-teal-500 input-glow outline-none transition placeholder:text-slate-400">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Phone (Optional)</label>
                    <input type="text" name="phone" placeholder="+92 300 1234567"
                           class="w-full px-4 py-3.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-0 focus:border-teal-500 input-glow outline-none transition placeholder:text-slate-400">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Password</label>
                    <input type="password" name="password" required minlength="6" placeholder="Min 6 characters"
                           class="w-full px-4 py-3.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-0 focus:border-teal-500 input-glow outline-none transition placeholder:text-slate-400">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Confirm Password</label>
                    <input type="password" name="confirm_password" required minlength="6" placeholder="Repeat password"
                           class="w-full px-4 py-3.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-0 focus:border-teal-500 input-glow outline-none transition placeholder:text-slate-400">
                </div>
                <button type="submit" class="w-full py-3.5 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-xl shadow-lg shadow-slate-900/20 hover:shadow-xl transition-all duration-200 transform hover:scale-[1.01] active:scale-[0.99] mt-2">
                    Create Account
                </button>
            </form>
            <div class="mt-6 text-center">
                <p class="text-sm text-slate-500">Already registered? <a href="<?php echo BASE_URL; ?>login" class="text-teal-600 font-semibold hover:text-teal-700 transition">Sign in</a></p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.toast').forEach(t => {
        setTimeout(() => { t.classList.add('toast-exit'); setTimeout(() => t.remove(), 300); }, 4000);
    });
});
</script>
</body>
</html>
