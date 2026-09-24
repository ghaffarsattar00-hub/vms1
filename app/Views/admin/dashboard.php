<!-- Admin Dashboard - Enterprise Premium -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
    <!-- Hospitals -->
    <div class="widget-card bg-white rounded-2xl p-5 border border-slate-100 shadow-sm fade-in" style="animation-delay:0.05s">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 bg-teal-50 rounded-xl flex items-center justify-center"><svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></div>
            <span class="text-[11px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">Active</span>
        </div>
        <p class="text-2xl font-extrabold text-slate-900"><?php echo $totalHospitals; ?></p>
        <p class="text-xs text-slate-400 mt-1 font-medium">Registered Hospitals</p>
    </div>
    <!-- Vaccines -->
    <div class="widget-card bg-white rounded-2xl p-5 border border-slate-100 shadow-sm fade-in" style="animation-delay:0.1s">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 bg-blue-50 rounded-xl flex items-center justify-center"><svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg></div>
            <span class="text-[11px] font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-lg"><?php echo $activeVaccines; ?> active</span>
        </div>
        <p class="text-2xl font-extrabold text-slate-900"><?php echo $totalVaccines; ?></p>
        <p class="text-xs text-slate-400 mt-1 font-medium">Vaccine Types</p>
    </div>
    <!-- Children -->
    <div class="widget-card bg-white rounded-2xl p-5 border border-slate-100 shadow-sm fade-in" style="animation-delay:0.15s">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 bg-amber-50 rounded-xl flex items-center justify-center"><svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
        </div>
        <p class="text-2xl font-extrabold text-slate-900"><?php echo $totalChildren; ?></p>
        <p class="text-xs text-slate-400 mt-1 font-medium">Registered Children</p>
    </div>
    <!-- Appointments -->
    <div class="widget-card bg-white rounded-2xl p-5 border border-slate-100 shadow-sm fade-in" style="animation-delay:0.2s">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 bg-violet-50 rounded-xl flex items-center justify-center"><svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
        </div>
        <p class="text-2xl font-extrabold text-slate-900"><?php echo $totalAppointments; ?></p>
        <p class="text-xs text-slate-400 mt-1 font-medium">Total Appointments</p>
    </div>
</div>

<!-- Status Row + Chart -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-8">
    <!-- Status Cards -->
    <div class="space-y-3 fade-in" style="animation-delay:0.25s">
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm flex items-center gap-4 widget-card">
            <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center"><span class="pulse-dot w-2.5 h-2.5 bg-amber-500 rounded-full"></span></div>
            <div><p class="text-xs text-slate-400 font-medium">Pending Approval</p><p class="text-xl font-extrabold text-slate-900"><?php echo $pendingCount; ?></p></div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm flex items-center gap-4 widget-card">
            <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center"><span class="pulse-dot w-2.5 h-2.5 bg-blue-500 rounded-full"></span></div>
            <div><p class="text-xs text-slate-400 font-medium">Approved</p><p class="text-xl font-extrabold text-slate-900"><?php echo $approvedCount; ?></p></div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm flex items-center gap-4 widget-card">
            <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center"><svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
            <div><p class="text-xs text-slate-400 font-medium">Vaccinated</p><p class="text-xl font-extrabold text-slate-900"><?php echo $vaccinatedCount; ?></p></div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm flex items-center gap-4 widget-card">
            <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center"><svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></div>
            <div><p class="text-xs text-slate-400 font-medium">Rejected</p><p class="text-xl font-extrabold text-slate-900"><?php echo $rejectedCount; ?></p></div>
        </div>
    </div>
    <!-- Monthly Chart -->
    <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-slate-100 shadow-sm fade-in" style="animation-delay:0.3s">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-sm font-bold text-slate-800">Appointment Trends</h3>
            <span class="text-[11px] font-medium text-slate-400 bg-slate-50 px-2.5 py-1 rounded-lg"><?php echo date('Y'); ?></span>
        </div>
        <div class="h-[250px] lg:h-[280px]"><canvas id="monthlyChart"></canvas></div>
    </div>
</div>

<!-- Vaccine Donut + Quick Actions -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 fade-in" style="animation-delay:0.35s">
    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
        <h3 class="text-sm font-bold text-slate-800 mb-4">Vaccine Portfolio</h3>
        <div class="flex items-center justify-center" style="height:220px"><canvas id="vaccineDonut"></canvas></div>
    </div>
    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
        <h3 class="text-sm font-bold text-slate-800 mb-4">Quick Actions</h3>
        <div class="space-y-2.5">
            <a href="<?php echo BASE_URL; ?>admin/hospitals" onclick="closeSidebar()" class="flex items-center gap-4 p-3 sm:p-3.5 rounded-xl hover:bg-slate-50 border border-transparent hover:border-slate-100 transition group cursor-pointer">
                <div class="w-10 h-10 bg-teal-50 group-hover:bg-teal-100 rounded-xl flex items-center justify-center transition"><svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></div>
                <div><p class="text-sm font-semibold text-slate-800">Manage Hospitals</p><p class="text-[11px] text-slate-400">Add, edit or deactivate</p></div>
                <svg class="w-4 h-4 text-slate-300 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            <a href="<?php echo BASE_URL; ?>admin/inventory" onclick="closeSidebar()" class="flex items-center gap-4 p-3 sm:p-3.5 rounded-xl hover:bg-slate-50 border border-transparent hover:border-slate-100 transition group cursor-pointer">
                <div class="w-10 h-10 bg-blue-50 group-hover:bg-blue-100 rounded-xl flex items-center justify-center transition"><svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg></div>
                <div><p class="text-sm font-semibold text-slate-800">Vaccine Inventory</p><p class="text-[11px] text-slate-400">Toggle availability</p></div>
                <svg class="w-4 h-4 text-slate-300 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            <a href="<?php echo BASE_URL; ?>admin/appointments" onclick="closeSidebar()" class="flex items-center gap-4 p-3 sm:p-3.5 rounded-xl hover:bg-slate-50 border border-transparent hover:border-slate-100 transition group cursor-pointer">
                <div class="w-10 h-10 bg-amber-50 group-hover:bg-amber-100 rounded-xl flex items-center justify-center transition"><svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
                <div><p class="text-sm font-semibold text-slate-800">Appointments</p><p class="text-[11px] text-slate-400">Approve or reject bookings</p></div>
                <svg class="w-4 h-4 text-slate-300 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    new Chart(document.getElementById('monthlyChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
            datasets: [{ label: 'Appointments', data: <?php echo json_encode($monthlyStats); ?>, backgroundColor: 'rgba(20,184,166,0.7)', borderRadius: 6, borderSkipped: false, barPercentage: 0.6 }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 11 } }, grid: { color: 'rgba(0,0,0,0.04)' } }, x: { grid: { display: false }, ticks: { font: { size: 11 } } } } }
    });
    new Chart(document.getElementById('vaccineDonut').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Active','Inactive'],
            datasets: [{ data: [<?php echo $activeVaccines; ?>, <?php echo $inactiveVaccines; ?>], backgroundColor: ['#14b8a6','#f43f5e'], borderWidth: 0, hoverOffset: 6 }]
        },
        options: { responsive: true, maintainAspectRatio: false, cutout: '72%', plugins: { legend: { position: 'bottom', labels: { padding: 16, usePointStyle: true, pointStyleWidth: 8, font: { size: 11, weight: 600 } } } } }
    });
});
</script>
