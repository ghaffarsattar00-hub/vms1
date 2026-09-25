    </div><!-- End page content -->
</div><!-- End main content -->

<script>
// CSRF Token
const CSRF_TOKEN = '<?php echo $csrfToken; ?>';
const MOBILE_BREAKPOINT = 768;

// Sidebar Toggle — same behavior on all screen sizes
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const main = document.getElementById('mainContent');
    const backdrop = document.getElementById('sidebarBackdrop');
    const isOpen = sidebar.classList.contains('open');

    if (isOpen) {
        closeSidebar();
    } else {
        sidebar.classList.add('open');
        backdrop.classList.add('visible');
        document.body.classList.add('overflow-hidden');
        document.body.classList.add('sidebar-open');
    }
}

function closeSidebar() {
    const sidebar = document.getElementById('sidebar');
    const backdrop = document.getElementById('sidebarBackdrop');
    const main = document.getElementById('mainContent');
    sidebar.classList.remove('open', 'collapsed');
    backdrop.classList.remove('visible');
    main.classList.remove('shifted', 'shifted-collapsed');
    document.body.classList.remove('overflow-hidden');
    document.body.classList.remove('sidebar-open');
}

// Close dropdown on outside click
document.addEventListener('click', function(e) {
    const dd = document.getElementById('userDropdown');
    if (dd && !dd.classList.contains('hidden') && !e.target.closest('[onclick*="userDropdown"]')) {
        dd.classList.add('hidden');
    }
});

// Auto-dismiss toasts
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.toast').forEach(function(t) {
        setTimeout(function() { t.classList.add('toast-exit'); setTimeout(function() { t.remove(); }, 300); }, 4000);
    });
});

// Global toast function
function showToast(message, type) {
    var container = document.getElementById('toast-container');
    if (!container) return;
    var colors = type === 'success' ? 'bg-emerald-500 shadow-emerald-500/25' : 'bg-red-500 shadow-red-500/25';
    var icon = type === 'success'
        ? '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>'
        : '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>';
    var toast = document.createElement('div');
    toast.className = colors + ' text-white px-4 py-3 sm:px-5 sm:py-3.5 rounded-2xl shadow-2xl flex items-center gap-3';
    toast.style.animation = 'slideIn 0.3s ease-out forwards';
    toast.innerHTML = '<div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">' + icon + '</div><span class="font-medium text-sm">' + message + '</span>';
    container.appendChild(toast);
    setTimeout(function() { toast.style.animation = 'toastOut 0.3s ease-in forwards'; setTimeout(function() { toast.remove(); }, 300); }, 3000);
}

// Modal helpers
function openModal(id) {
    var m = document.getElementById(id);
    if (m) { m.classList.remove('hidden'); m.classList.add('flex'); m.querySelector('.modal-panel') && (m.querySelector('.modal-panel').style.animation = 'fadeIn 0.2s ease-out forwards'); document.body.classList.add('overflow-hidden'); }
}
function closeModal(id) {
    var m = document.getElementById(id);
    if (m) { m.classList.add('hidden'); m.classList.remove('flex'); document.body.classList.remove('overflow-hidden'); }
}

// Escape key closes sidebar (mobile) and modals
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeSidebar();
        document.querySelectorAll('[id$="Modal"]').forEach(function(m) { m.classList.add('hidden'); m.classList.remove('flex'); });
        document.body.classList.remove('overflow-hidden');
    }
});
</script>
</body>
</html>
