<!DOCTYPE html>
<html lang="en" class="antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Email | VMS</title>
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
        .fade-up { animation: fadeUp 0.6s ease-out forwards; opacity: 0; transform: translateY(20px); }
        @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-slate-900">

<div class="min-h-screen login-bg relative flex flex-col items-center justify-center p-6 overflow-hidden">
    <div class="grid-pattern absolute inset-0"></div>
    <div class="absolute top-10 right-10 w-48 h-48 bg-teal-500/10 rounded-full blur-3xl"></div>

    <div class="relative z-10 w-full max-w-lg">
        <!-- Logo -->
        <div class="flex items-center gap-3 mb-6 justify-center">
            <div class="w-11 h-11 rounded-xl flex items-center justify-center shadow-lg shadow-teal-500/30 overflow-hidden">
                <img src="<?php echo BASE_URL; ?>vms-logo.svg" alt="VMS" class="w-full h-full object-cover">
            </div>
            <span class="text-white text-2xl font-bold tracking-tight">VMS</span>
        </div>

        <!-- Dev badge -->
        <div class="flex justify-center mb-4">
            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-amber-300 bg-amber-500/10 border border-amber-400/20 px-3 py-1.5 rounded-full">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Mock email preview &mdash; mail() is used when APP_ENV=production
            </span>
        </div>

        <!-- Email card -->
        <div class="bg-white rounded-3xl shadow-2xl shadow-black/20 overflow-hidden fade-up">
            <!-- Email header -->
            <div class="bg-slate-50 border-b border-slate-100 px-6 py-4">
                <div class="flex items-center justify-between text-xs text-slate-500 mb-1">
                    <span><span class="font-semibold text-slate-600">From:</span> VMS &lt;noreply@localhost&gt;</span>
                    <span><?php echo date('d M Y, h:i A'); ?></span>
                </div>
                <div class="text-xs text-slate-500 mb-1"><span class="font-semibold text-slate-600">To:</span> <?php echo htmlspecialchars($toEmail); ?></div>
                <div class="text-sm font-bold text-slate-800"><span class="font-semibold text-slate-500">Subject:</span> VMS - Reset Your Password</div>
            </div>

            <!-- Email body -->
            <div class="p-6 sm:p-8">
                <div class="w-14 h-14 mx-auto mb-5 rounded-2xl bg-teal-50 flex items-center justify-center">
                    <svg class="w-7 h-7 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.029 5.912l-2.14 2.14A6 6 0 114 9V6a2 2 0 012-2h2a2 2 0 012 2v1a2 2 0 01-2 2h-1a1 1 0 00-1 1v1a1 1 0 001 1h.586a1 1 0 00.707-.293l.586-.586A6 6 0 1121 9z"/></svg>
                </div>

                <h2 class="text-xl font-extrabold text-slate-900 text-center">Reset your password</h2>
                <p class="text-slate-500 text-sm text-center mt-2">We received a request to reset the password for <span class="font-semibold text-slate-700"><?php echo htmlspecialchars($toEmail); ?></span>. Click the button below within 1 hour to choose a new one.</p>

                <div class="mt-6 text-center">
                    <a href="<?php echo htmlspecialchars($resetLink); ?>"
                       class="inline-flex items-center gap-2 px-7 py-3.5 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-xl shadow-lg shadow-teal-600/20 transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        Reset Password
                    </a>
                </div>

                <div class="mt-5 bg-slate-50 rounded-xl p-3 border border-slate-100">
                    <p class="text-[11px] text-slate-400 text-center mb-1">If the button doesn't work, copy this link:</p>
                    <p class="text-[11px] font-mono text-slate-500 break-all text-center"><?php echo htmlspecialchars($resetLink); ?></p>
                </div>

                <div class="mt-5 flex items-center justify-between text-[11px] text-slate-400 border-t border-slate-100 pt-4">
                    <span>Link expires: <span class="font-semibold text-slate-500"><?php echo date('d M Y, h:i A', strtotime($expiresAt)); ?></span></span>
                    <span>Token: <?php echo substr(htmlspecialchars($resetLink), 0, 0); ?><?php echo '...' . substr(parse_url($resetLink, PHP_URL_QUERY) ?? '', -12); ?></span>
                </div>

                <p class="text-[11px] text-slate-400 text-center mt-4">If you didn't request this, you can safely ignore this email.</p>
            </div>
        </div>

        <div class="text-center mt-6">
            <a href="<?php echo BASE_URL; ?>forgot-password" class="text-sm text-slate-400 hover:text-teal-300 transition">&larr; Back to Forgot Password</a>
        </div>
    </div>
</div>

</body>
</html>
