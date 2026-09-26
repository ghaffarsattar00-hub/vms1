<!DOCTYPE html>
<html lang="en" class="antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | VMS</title>
    <link rel="icon" type="image/svg+xml" href="<?php echo BASE_URL; ?>vms-logo.svg">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = { theme: { extend: { fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] } } } }
    </script>
    <style>
        * { font-family: 'Inter', system-ui, sans-serif; }
        .login-bg { background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f766e 100%); }
        .grid-pattern { background-image: radial-gradient(rgba(255,255,255,0.05) 1px, transparent 1px); background-size: 30px 30px; }
        .input-glow:focus { box-shadow: 0 0 0 3px rgba(20,184,166,0.15); }
        .fade-up { animation: fadeUp 0.6s ease-out forwards; opacity: 0; transform: translateY(20px); }
        @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }
        .slide-in { animation: slideIn 0.3s ease-out forwards; }
        @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        .toast-exit { animation: toastOut 0.3s ease-in forwards; }
        @keyframes toastOut { to { transform: translateX(100%); opacity: 0; } }
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

<div class="min-h-screen login-bg relative flex flex-col items-center justify-center p-6 overflow-hidden">
    <div class="grid-pattern absolute inset-0"></div>
    <div class="absolute top-10 right-10 w-48 h-48 bg-teal-500/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-20 left-10 w-32 h-32 bg-blue-500/10 rounded-full blur-2xl"></div>

    <div class="relative z-10 w-full max-w-md">
        <!-- Logo -->
        <div class="flex items-center gap-3 mb-8 justify-center">
            <div class="w-11 h-11 rounded-xl flex items-center justify-center shadow-lg shadow-teal-500/30 overflow-hidden">
                <img src="<?php echo BASE_URL; ?>vms-logo.svg" alt="VMS" class="w-full h-full object-cover">
            </div>
            <span class="text-white text-2xl font-bold tracking-tight">VMS</span>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-3xl shadow-2xl shadow-black/20 p-8 fade-up">
            <div class="mb-6 text-center">
                <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-teal-50 flex items-center justify-center">
                    <svg class="w-7 h-7 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <h2 class="text-2xl font-extrabold text-slate-900">Set new password</h2>
                <p class="text-slate-500 mt-1.5 text-sm">Choose a new password for <span class="font-semibold text-slate-700"><?php echo htmlspecialchars($userEmail); ?></span></p>
            </div>

            <form method="POST" action="<?php echo BASE_URL; ?>reset-password" class="space-y-4">
                <?php echo $csrfInput; ?>
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">New Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"><svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg></span>
                        <input type="password" name="password" required minlength="6" placeholder="Min. 6 characters"
                               class="w-full pl-11 pr-4 py-3.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-0 focus:border-teal-500 input-glow outline-none transition placeholder:text-slate-400">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Confirm Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"><svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg></span>
                        <input type="password" name="confirm_password" required minlength="6" placeholder="Re-enter new password"
                               class="w-full pl-11 pr-4 py-3.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-0 focus:border-teal-500 input-glow outline-none transition placeholder:text-slate-400">
                    </div>
                </div>

                <button type="submit" class="w-full py-3.5 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-xl shadow-lg shadow-teal-600/20 transition-all duration-200 transform hover:scale-[1.01] active:scale-[0.99]">
                    Update Password
                </button>
            </form>

            <div class="mt-5 text-center">
                <p class="text-sm text-slate-500"><a href="<?php echo BASE_URL; ?>login" class="text-teal-600 font-semibold hover:text-teal-700 transition">Back to Sign In</a></p>
            </div>
        </div>

        <p class="text-slate-500 text-xs text-center mt-6">&copy; 2026 VMS. Built for Pakistan's health infrastructure.</p>
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
