<!-- Hospital Dashboard - Enterprise -->
<!-- Welcome -->
<div class="bg-gradient-to-r from-blue-900 via-blue-800 to-blue-900 rounded-2xl p-6 mb-8 text-white shadow-xl shadow-blue-900/20 fade-in">
    <div class="flex flex-col sm:flex-row gap-4 sm:gap-4 items-start sm:items-center">
        <div class="w-12 h-12 bg-blue-500 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/30">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
        </div>
        <div>
            <p class="text-sm text-slate-400">Hospital Portal</p>
            <h2 class="text-xl font-extrabold"><?php echo htmlspecialchars($hospital['name'] ?? 'Hospital'); ?></h2>
        </div>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
    <div class="widget-card bg-white rounded-2xl p-4 sm:p-5 border border-slate-100 shadow-sm fade-in" style="animation-delay:0.05s">
        <div class="flex items-center gap-4">
            <div class="w-11 h-11 bg-amber-50 rounded-xl flex items-center justify-center"><span class="pulse-dot w-2.5 h-2.5 bg-amber-500 rounded-full"></span></div>
            <div><p class="text-2xl font-extrabold text-slate-900"><?php echo $pendingCount; ?></p><p class="text-xs text-slate-400 font-medium">Pending Approval</p></div>
        </div>
    </div>
    <div class="widget-card bg-white rounded-2xl p-4 sm:p-5 border border-slate-100 shadow-sm fade-in" style="animation-delay:0.1s">
        <div class="flex items-center gap-4">
            <div class="w-11 h-11 bg-blue-50 rounded-xl flex items-center justify-center"><svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
            <div><p class="text-2xl font-extrabold text-slate-900"><?php echo $approvedCount; ?></p><p class="text-xs text-slate-400 font-medium">Approved</p></div>
        </div>
    </div>
    <div class="widget-card bg-white rounded-2xl p-4 sm:p-5 border border-slate-100 shadow-sm fade-in" style="animation-delay:0.15s">
        <div class="flex items-center gap-4">
            <div class="w-11 h-11 bg-emerald-50 rounded-xl flex items-center justify-center"><svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
            <div><p class="text-2xl font-extrabold text-slate-900"><?php echo $vaccinatedCount; ?></p><p class="text-xs text-slate-400 font-medium">Completed</p></div>
        </div>
    </div>
</div>

<!-- Pending Appointments Table -->
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-8 fade-in" style="animation-delay:0.2s">
    <div class="px-6 py-4 border-b border-slate-100">
        <h3 class="text-sm font-bold text-slate-800">Pending Approvals</h3>
    </div>
    <div class="table-responsive">
        <table class="w-full text-left sticky-header">
            <thead>
                <tr class="border-b border-slate-100">
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Child</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Vaccine / Dose</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Scheduled</th>
                    <th class="hidden sm:table-cell px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Parent</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php if (empty($pendingAppointments)): ?>
                    <tr><td colspan="5" class="px-6 py-12 text-center text-slate-300 text-sm">No pending approvals</td></tr>
                <?php else: foreach ($pendingAppointments as $i => $apt): ?>
                    <tr class="hover:bg-slate-50/80 transition" style="animation-delay:<?php echo $i*0.02; ?>s">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-amber-400 to-orange-500 rounded-lg flex items-center justify-center text-white text-xs font-bold shadow-sm flex-shrink-0"><?php echo strtoupper(substr($apt['first_name'],0,1)); ?></div>
                                <span class="font-semibold text-slate-800 text-sm"><?php echo htmlspecialchars($apt['first_name'] . ' ' . $apt['last_name']); ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-slate-700"><?php echo htmlspecialchars($apt['vaccine_name']); ?></p>
                            <p class="text-[11px] text-slate-400">Dose <?php echo $apt['dose_number']; ?></p>
                        </td>
                        <td class="px-6 py-4"><span class="text-xs font-medium text-slate-500 bg-slate-50 px-2.5 py-1 rounded-lg"><?php echo date('d M Y', strtotime($apt['scheduled_date'])); ?></span></td>
                        <td class="hidden sm:table-cell px-6 py-4 text-sm text-slate-500"><?php echo htmlspecialchars($apt['parent_name']); ?></td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <button onclick="approveApt(<?php echo $apt['appointment_id']; ?>)" class="p-2 text-emerald-500 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition" title="Approve"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></button>
                                <button onclick="rejectApt(<?php echo $apt['appointment_id']; ?>)" class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Reject"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Approved - Ready to Vaccinate -->
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden fade-in" style="animation-delay:0.25s">
    <div class="px-6 py-4 border-b border-slate-100">
        <h3 class="text-sm font-bold text-slate-800">Approved — Ready to Vaccinate</h3>
    </div>
    <div class="table-responsive">
        <table class="w-full text-left sticky-header">
            <thead>
                <tr class="border-b border-slate-100">
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Child</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Vaccine / Dose</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Scheduled</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php if (empty($approvedAppointments)): ?>
                    <tr><td colspan="4" class="px-6 py-12 text-center text-slate-300 text-sm">No approved appointments</td></tr>
                <?php else: foreach ($approvedAppointments as $i => $apt): ?>
                    <tr class="hover:bg-slate-50/80 transition" style="animation-delay:<?php echo $i*0.02; ?>s">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-amber-400 to-orange-500 rounded-lg flex items-center justify-center text-white text-xs font-bold shadow-sm flex-shrink-0"><?php echo strtoupper(substr($apt['first_name'],0,1)); ?></div>
                                <span class="font-semibold text-slate-800 text-sm"><?php echo htmlspecialchars($apt['first_name'] . ' ' . $apt['last_name']); ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-slate-700"><?php echo htmlspecialchars($apt['vaccine_name']); ?></p>
                            <p class="text-[11px] text-slate-400">Dose <?php echo $apt['dose_number']; ?></p>
                        </td>
                        <td class="px-6 py-4"><span class="text-xs font-medium text-slate-500 bg-slate-50 px-2.5 py-1 rounded-lg"><?php echo date('d M Y', strtotime($apt['scheduled_date'])); ?></span></td>
                        <td class="px-6 py-4 text-right">
                            <button onclick="vaccinateApt(<?php echo $apt['appointment_id']; ?>, <?php echo $apt['dose_id']; ?>, <?php echo $apt['child_id']; ?>)" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-500 hover:bg-blue-400 text-white text-xs font-semibold rounded-lg shadow-sm shadow-blue-500/20 transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.99]">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Vaccinate
                            </button>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
async function approveApt(id) {
    try {
        var r = await fetch('<?php echo BASE_URL; ?>hospital/appointments/approve', {
            method: 'POST', headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ csrf_token: CSRF_TOKEN, appointment_id: id })
        });
        var d = await r.json();
        if (d.success) { showToast(d.message, 'success'); setTimeout(function() { location.reload(); }, 600); }
        else showToast(d.message, 'error');
    } catch(e) { showToast('Network error', 'error'); }
}
async function rejectApt(id) {
    try {
        var r = await fetch('<?php echo BASE_URL; ?>hospital/appointments/reject', {
            method: 'POST', headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ csrf_token: CSRF_TOKEN, appointment_id: id })
        });
        var d = await r.json();
        if (d.success) { showToast(d.message, 'success'); setTimeout(function() { location.reload(); }, 600); }
        else showToast(d.message, 'error');
    } catch(e) { showToast('Network error', 'error'); }
}
async function vaccinateApt(aptId, doseId, childId) {
    try {
        var r = await fetch('<?php echo BASE_URL; ?>hospital/appointments/vaccinate', {
            method: 'POST', headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ csrf_token: CSRF_TOKEN, appointment_id: aptId, dose_id: doseId, child_id: childId })
        });
        var d = await r.json();
        if (d.success) { showToast(d.message, 'success'); setTimeout(function() { location.reload(); }, 600); }
        else showToast(d.message, 'error');
    } catch(e) { showToast('Network error', 'error'); }
}
</script>
