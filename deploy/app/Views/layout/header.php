<!DOCTYPE html>
<html lang="en" class="antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ($pageTitle ?? 'Dashboard') . ' | VMS'; ?></title>
    <link rel="icon" type="image/svg+xml" href="<?php echo BASE_URL; ?>vms-logo.svg">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        * { font-family: 'Inter', system-ui, sans-serif; box-sizing: border-box; }

        /* ===== SIDEBAR ===== */
        .sidebar {
            transition: transform 0.3s cubic-bezier(0.4,0,0.2,1), width 0.3s cubic-bezier(0.4,0,0.2,1);
            width: 280px;
            transform: translateX(-100%);
        }
        .sidebar.open { transform: translateX(0); }
        .sidebar.collapsed { width: 72px !important; transform: translateX(0); }
        .sidebar.collapsed .sidebar-label { display: none; }
        .sidebar.collapsed .sidebar-brand-text { display: none; }
        .sidebar.collapsed .sidebar-section-title { display: none; }
        .sidebar.collapsed .nav-item { justify-content: center; padding-left: 0; padding-right: 0; }
        .sidebar.collapsed .nav-item svg { margin: 0; }
        .sidebar.collapsed .sidebar-header { justify-content: center; }
        .sidebar.collapsed .user-info { display: none; }

        /* Backdrop */
        .sidebar-backdrop {
            position: fixed; inset: 0; z-index: 40;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(4px);
            opacity: 0; pointer-events: none;
            transition: opacity 0.3s ease;
        }
        .sidebar-backdrop.visible { opacity: 1; pointer-events: auto; }

        /* Main content */
        .main-content { transition: margin-left 0.3s cubic-bezier(0.4,0,0.2,1); margin-left: 0; }
        .main-content.shifted { margin-left: 280px; }
        .main-content.shifted-collapsed { margin-left: 72px; }

        /* ===== NAV ITEMS ===== */
        .nav-item { transition: all 0.15s ease; position: relative; display: flex; align-items: center; }
        .nav-item::before {
            content: ''; position: absolute; left: 0; top: 50%; transform: translateY(-50%);
            width: 3px; height: 0; background: #14b8a6; border-radius: 0 4px 4px 0; transition: height 0.2s ease;
        }
        .nav-item:hover::before, .nav-item.active::before { height: 24px; }
        .nav-item.active { background: rgba(20,184,166,0.08); color: #14b8a6; }
        .nav-item:hover { background: rgba(255,255,255,0.06); }
        body.role-hospital .nav-item.active { background: rgba(59,130,246,0.08); color: #3b82f6; }
        body.role-hospital .nav-item::before { background: #3b82f6; }
        body.role-hospital .sidebar { background: #0c1929; }
        body.role-hospital .sidebar .sidebar-header { border-color: rgba(59,130,246,0.1); }
        body.role-hospital .sidebar-header .w-9 { background: linear-gradient(135deg, #3b82f6, #2563eb) !important; box-shadow: 0 4px 12px rgba(59,130,246,0.3); }
        body.role-hospital .flex-shrink-0 > div:first-child .w-9 { background: linear-gradient(135deg, #3b82f6, #2563eb) !important; box-shadow: 0 4px 12px rgba(59,130,246,0.3); }
        body.role-hospital .topbar { background: rgba(239,246,255,0.85); border-bottom-color: rgba(59,130,246,0.08); }

        /* ===== TOPBAR ===== */
        .topbar { backdrop-filter: blur(12px); background: rgba(255,255,255,0.85); border-bottom: 1px solid rgba(0,0,0,0.04); }

        /* ===== ANIMATIONS ===== */
        .slide-in { animation: slideIn 0.3s ease-out forwards; }
        @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        .toast-exit { animation: toastOut 0.3s ease-in forwards; }
        @keyframes toastOut { to { transform: translateX(100%); opacity: 0; } }
        .fade-in { animation: fadeIn 0.4s ease-out forwards; opacity: 0; transform: translateY(8px); }
        @keyframes fadeIn { to { opacity: 1; transform: translateY(0); } }

        /* ===== CARDS ===== */
        .widget-card { transition: all 0.2s ease; }
        .widget-card:hover { transform: translateY(-2px); box-shadow: 0 8px 30px -8px rgba(0,0,0,0.12); }

        /* ===== GLASSMORPHISM ===== */
        .glass { background: rgba(255,255,255,0.7); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.8); }

        /* ===== STICKY TABLE HEADER ===== */
        .sticky-header thead th { position: sticky; top: 0; z-index: 10; background: #f8fafc; }

        /* ===== STATUS BADGES ===== */
        .badge { padding: 4px 10px; border-radius: 9999px; font-size: 11px; font-weight: 600; letter-spacing: 0.02em; white-space: nowrap; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-approved { background: #dbeafe; color: #1e40af; }
        .badge-vaccinated { background: #d1fae5; color: #065f46; }
        .badge-rejected { background: #fee2e2; color: #991b1b; }
        .badge-not-vaccinated { background: #f3e8ff; color: #6b21a8; }
        .badge-cancelled { background: #e2e8f0; color: #475569; }

        /* ===== PULSE ===== */
        .pulse-dot { animation: pulse 2s infinite; }
        @keyframes pulse { 0%,100% { opacity: 1; } 50% { opacity: 0.4; } }

        /* ===== MODAL GLASS ===== */
        .modal-glass { background: rgba(255,255,255,0.95); backdrop-filter: blur(24px); }

        /* ===== TOGGLE ===== */
        .toggle-track { transition: background-color 0.2s ease; }
        .toggle-knob { transition: transform 0.2s ease; }

        /* ===== SCROLLBAR ===== */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* ===== RESPONSIVE TABLES ===== */
        .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }

        /* ===== RESPONSIVE GRID GAPS ===== */
        @media (max-width: 640px) {
            .grid-gap-sm { gap: 0.75rem; }
        }

        /* ===== TOUCH-friendly tap targets ===== */
        @media (max-width: 768px) {
            .nav-item { padding-top: 0.75rem; padding-bottom: 0.75rem; min-height: 44px; }
            .topbar button, .topbar a { min-height: 44px; }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 <?php echo 'role-' . ($_SESSION['user_role'] ?? 'admin'); ?>">

<!-- Sidebar Backdrop (mobile) -->
<div id="sidebarBackdrop" class="sidebar-backdrop" onclick="closeSidebar()"></div>

<!-- Toast Container -->
<div id="toast-container" class="fixed top-4 right-4 left-4 sm:left-auto sm:w-96 z-[200] flex flex-col gap-3">
<?php if (isset($_SESSION['success'])): ?>
    <div class="toast slide-in bg-emerald-500 text-white px-4 py-3 sm:px-5 sm:py-3.5 rounded-2xl shadow-2xl shadow-emerald-500/25 flex items-center gap-3">
        <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg></div>
        <span class="font-medium text-sm"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></span>
    </div>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <div class="toast slide-in bg-red-500 text-white px-4 py-3 sm:px-5 sm:py-3.5 rounded-2xl shadow-2xl shadow-red-500/25 flex items-center gap-3">
        <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg></div>
        <span class="font-medium text-sm"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></span>
    </div>
<?php endif; ?>
</div>

<!-- Sidebar -->
<aside id="sidebar" class="sidebar fixed inset-y-0 left-0 bg-slate-900 text-white z-50 flex flex-col shadow-2xl shadow-slate-900/30 overflow-hidden">
    <!-- Brand -->
    <div class="sidebar-header flex items-center gap-3 px-5 h-16 border-b border-white/5 flex-shrink-0">
        <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 overflow-hidden">
            <img src="<?php echo BASE_URL; ?>vms-logo.svg" alt="VMS" class="w-full h-full">
        </div>
        <div class="sidebar-brand-text min-w-0"><p class="font-bold text-sm tracking-tight">VMS</p><p class="text-[10px] text-slate-500 -mt-0.5">Vaccination Management</p></div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 py-4 px-3 space-y-1 overflow-y-auto">
        <?php if ($_SESSION['user_role'] === 'admin'): ?>
            <p class="sidebar-section-title text-[10px] font-bold text-slate-600 uppercase tracking-widest px-3 mb-2">Administration</p>
            <a href="<?php echo BASE_URL; ?>admin/dashboard" onclick="closeSidebar()" class="nav-item gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-400 <?php echo str_ends_with($_SERVER['REQUEST_URI'],'admin/dashboard') ? 'active' : ''; ?>">
                <svg class="w-[18px] h-[18px] flex-shrink-0 sidebar-label" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                <span class="sidebar-label whitespace-nowrap">Dashboard</span>
            </a>
            <a href="<?php echo BASE_URL; ?>admin/hospitals" onclick="closeSidebar()" class="nav-item gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-400 <?php echo str_ends_with($_SERVER['REQUEST_URI'],'admin/hospitals') ? 'active' : ''; ?>">
                <svg class="w-[18px] h-[18px] flex-shrink-0 sidebar-label" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <span class="sidebar-label whitespace-nowrap">Hospitals</span>
            </a>
            <a href="<?php echo BASE_URL; ?>admin/inventory" onclick="closeSidebar()" class="nav-item gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-400 <?php echo str_ends_with($_SERVER['REQUEST_URI'],'admin/inventory') ? 'active' : ''; ?>">
                <svg class="w-[18px] h-[18px] flex-shrink-0 sidebar-label" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <span class="sidebar-label whitespace-nowrap">Inventory</span>
            </a>
            <a href="<?php echo BASE_URL; ?>admin/appointments" onclick="closeSidebar()" class="nav-item gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-400 <?php echo str_ends_with($_SERVER['REQUEST_URI'],'admin/appointments') ? 'active' : ''; ?>">
                <svg class="w-[18px] h-[18px] flex-shrink-0 sidebar-label" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span class="sidebar-label whitespace-nowrap">Appointments</span>
            </a>
            <a href="<?php echo BASE_URL; ?>admin/orders" onclick="closeSidebar()" class="nav-item gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-400 <?php echo str_ends_with($_SERVER['REQUEST_URI'],'admin/orders') ? 'active' : ''; ?>">
                <svg class="w-[18px] h-[18px] flex-shrink-0 sidebar-label" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <span class="sidebar-label whitespace-nowrap">Orders</span>
            </a>
        <?php elseif ($_SESSION['user_role'] === 'parent'): ?>
            <p class="sidebar-section-title text-[10px] font-bold text-slate-600 uppercase tracking-widest px-3 mb-2">Parent Portal</p>
            <a href="<?php echo BASE_URL; ?>parent/dashboard" onclick="closeSidebar()" class="nav-item gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-400 <?php echo str_ends_with($_SERVER['REQUEST_URI'],'parent/dashboard') ? 'active' : ''; ?>">
                <svg class="w-[18px] h-[18px] flex-shrink-0 sidebar-label" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z"/></svg>
                <span class="sidebar-label whitespace-nowrap">Dashboard</span>
            </a>
            <a href="<?php echo BASE_URL; ?>parent/book" onclick="closeSidebar()" class="nav-item gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-400 <?php echo str_ends_with($_SERVER['REQUEST_URI'],'parent/book') ? 'active' : ''; ?>">
                <svg class="w-[18px] h-[18px] flex-shrink-0 sidebar-label" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                <span class="sidebar-label whitespace-nowrap">Book Appointment</span>
            </a>
        <?php elseif ($_SESSION['user_role'] === 'hospital'): ?>
            <p class="sidebar-section-title text-[10px] font-bold text-slate-600 uppercase tracking-widest px-3 mb-2">Hospital Portal</p>
            <a href="<?php echo BASE_URL; ?>hospital/dashboard" onclick="closeSidebar()" class="nav-item gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-400 <?php echo str_ends_with($_SERVER['REQUEST_URI'],'hospital/dashboard') ? 'active' : ''; ?>">
                <svg class="w-[18px] h-[18px] flex-shrink-0 sidebar-label" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z"/></svg>
                <span class="sidebar-label whitespace-nowrap">Dashboard</span>
            </a>
            <a href="<?php echo BASE_URL; ?>hospital/appointments" onclick="closeSidebar()" class="nav-item gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-400 <?php echo str_ends_with($_SERVER['REQUEST_URI'],'hospital/appointments') ? 'active' : ''; ?>">
                <svg class="w-[18px] h-[18px] flex-shrink-0 sidebar-label" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span class="sidebar-label whitespace-nowrap">Appointments</span>
            </a>
            <a href="<?php echo BASE_URL; ?>hospital/inventory" onclick="closeSidebar()" class="nav-item gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-400 <?php echo str_ends_with($_SERVER['REQUEST_URI'],'hospital/inventory') ? 'active' : ''; ?>">
                <svg class="w-[18px] h-[18px] flex-shrink-0 sidebar-label" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <span class="sidebar-label whitespace-nowrap">My Inventory</span>
            </a>
            <a href="<?php echo BASE_URL; ?>hospital/orders" onclick="closeSidebar()" class="nav-item gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-400 <?php echo str_ends_with($_SERVER['REQUEST_URI'],'hospital/orders') ? 'active' : ''; ?>">
                <svg class="w-[18px] h-[18px] flex-shrink-0 sidebar-label" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <span class="sidebar-label whitespace-nowrap">My Orders</span>
            </a>
        <?php endif; ?>
    </nav>

    <!-- User Profile -->
    <div class="flex-shrink-0 border-t border-white/5 p-4">
        <div class="flex items-center gap-3 p-2 rounded-xl hover:bg-white/5 transition cursor-pointer" onclick="document.getElementById('userDropdown').classList.toggle('hidden')">
            <div class="w-9 h-9 bg-gradient-to-br from-teal-400 to-teal-600 rounded-xl flex items-center justify-center text-sm font-bold text-white flex-shrink-0 shadow-lg shadow-teal-500/20">
                <?php echo strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)); ?>
            </div>
            <div class="user-info flex-1 min-w-0">
                <p class="text-sm font-semibold text-white truncate"><?php echo $_SESSION['user_name'] ?? 'User'; ?></p>
                <p class="text-[11px] text-slate-500 capitalize"><?php echo $_SESSION['user_role'] ?? ''; ?></p>
            </div>
        </div>
        <div id="userDropdown" class="hidden mt-2 p-2 bg-slate-800 rounded-xl border border-white/5 w-full overflow-hidden">
            <a href="<?php echo BASE_URL; ?>logout" class="flex items-center gap-2 px-3 py-2 text-sm text-slate-400 hover:text-white hover:bg-white/5 rounded-lg transition w-full">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Sign Out
            </a>
        </div>
    </div>
</aside>

<!-- Main Content -->
<div id="mainContent" class="main-content min-h-screen">
    <!-- Sticky Topbar -->
    <header class="topbar sticky top-0 z-30 h-14 sm:h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-2 sm:gap-4 min-w-0">
            <button onclick="toggleSidebar()" class="p-2 -ml-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div class="min-w-0">
                <h1 class="text-base sm:text-lg font-bold text-slate-900 truncate"><?php echo $pageTitle ?? 'Dashboard'; ?></h1>
                <p class="text-[11px] sm:text-xs text-slate-400 -mt-0.5 truncate">Welcome back, <?php echo $_SESSION['user_name'] ?? 'User'; ?></p>
            </div>
        </div>
        <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">
            <span class="hidden sm:inline text-xs font-medium text-slate-400 bg-slate-100 px-3 py-1.5 rounded-lg"><?php echo date('d M Y'); ?></span>
        </div>
    </header>

    <!-- Page Content -->
    <div class="p-4 sm:p-6 lg:p-8 fade-in">
