<!-- Hospital Appointments - Enterprise -->
<div class="mb-6 fade-in">
    <p class="text-sm text-slate-500">Incoming bookings for <?php echo htmlspecialchars($hospital['name'] ?? 'your hospital') ?> — approve, reject, or mark vaccinated</p>
</div>

<!-- Filter Tabs -->
<div class="flex items-center gap-2 mb-5 fade-in overflow-x-auto pb-1" style="animation-delay:0.05s">
    <button onclick="filterApts('all')" class="filter-tab active px-4 py-2 rounded-xl text-xs font-semibold transition-all bg-slate-900 text-white shadow-sm flex-shrink-0" data-filter="all">All <span class="ml-1 opacity-70"><?php echo $totalAppointments; ?></span></button>
    <button onclick="filterApts('pending')" class="filter-tab px-4 py-2 rounded-xl text-xs font-semibold transition-all bg-white text-slate-500 border border-slate-200 hover:border-slate-300 flex-shrink-0" data-filter="pending">Pending <span class="ml-1 opacity-70"><?php echo $pendingCount; ?></span></button>
    <button onclick="filterApts('approved')" class="filter-tab px-4 py-2 rounded-xl text-xs font-semibold transition-all bg-white text-slate-500 border border-slate-200 hover:border-slate-300 flex-shrink-0" data-filter="approved">Approved <span class="ml-1 opacity-70"><?php echo $approvedCount; ?></span></button>
    <button onclick="filterApts('vaccinated')" class="filter-tab px-4 py-2 rounded-xl text-xs font-semibold transition-all bg-white text-slate-500 border border-slate-200 hover:border-slate-300 flex-shrink-0" data-filter="vaccinated">Vaccinated <span class="ml-1 opacity-70"><?php echo $vaccinatedCount; ?></span></button>
    <button onclick="filterApts('rejected')" class="filter-tab px-4 py-2 rounded-xl text-xs font-semibold transition-all bg-white text-slate-500 border border-slate-200 hover:border-slate-300 flex-shrink-0" data-filter="rejected">Rejected <span class="ml-1 opacity-70"><?php echo $rejectedCount; ?></span></button>
</div>

<div class="table-responsive">
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden fade-in" style="animation-delay:0.1s">
    <div class="overflow-x-auto">
        <table class="w-full text-left sticky-header">
            <thead>
                <tr class="border-b border-slate-100">
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Child</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider hidden sm:table-cell">Parent</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Vaccine / Dose</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider hidden md:table-cell">Scheduled</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50" id="aptTableBody">
                <?php if (empty($appointments)): ?>
                    <tr><td colspan="6" class="px-6 py-16 text-center text-slate-300">No appointments for this hospital yet</td></tr>
                <?php else: foreach ($appointments as $i => $apt): ?>
                    <tr class="hover:bg-slate-50/80 transition apt-row" data-status="<?php echo htmlspecialchars(strtolower($apt['status'] ?? '')); ?>" style="animation-delay:<?php echo $i*0.02; ?>s">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-amber-400 to-orange-500 rounded-lg flex items-center justify-center text-white text-xs font-bold shadow-sm flex-shrink-0"><?php echo strtoupper(substr($apt['first_name'] ?? '', 0, 1)); ?></div>
                                <span class="font-semibold text-slate-800 text-sm"><?php echo htmlspecialchars(($apt['first_name'] ?? '') . ' ' . ($apt['last_name'] ?? '')); ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-500 hidden sm:table-cell"><?php echo htmlspecialchars($apt['parent_name'] ?? ''); ?></td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-slate-700"><?php echo htmlspecialchars($apt['vaccine_name'] ?? ''); ?></p>
                            <p class="text-[11px] text-slate-400">Dose <?php echo $apt['dose_number'] ?? ''; ?></p>
                        </td>
                        <td class="px-6 py-4 hidden md:table-cell">
                            <span class="text-xs font-medium text-slate-500 bg-slate-50 px-2.5 py-1 rounded-lg"><?php echo $apt['scheduled_date'] ? date('d M Y', strtotime($apt['scheduled_date'])) : '—'; ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <?php
                            $statusMap = [
                                'pending'    => 'badge-pending',
                                'approved'   => 'badge-approved',
                                'vaccinated' => 'badge-vaccinated',
                                'rejected'   => 'badge-rejected',
                                'cancelled'  => 'badge-cancelled',
                            ];
                            $statusKey = strtolower($apt['status'] ?? '');
                            $badge = $statusMap[$statusKey] ?? 'badge-cancelled';
                            ?>
                            <span class="badge <?php echo $badge; ?>"><?php echo ucfirst(str_replace('_', ' ', $statusKey)); ?></span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <?php if ($statusKey === 'pending'): ?>
                                <div class="flex items-center justify-end gap-1.5">
                                    <button onclick="approveApt(<?php echo (int)($apt['appointment_id'] ?? 0); ?>)" class="p-2 text-emerald-500 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition" title="Approve"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></button>
                                    <button onclick="rejectApt(<?php echo (int)($apt['appointment_id'] ?? 0); ?>)" class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Reject"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                </div>
                            <?php elseif ($statusKey === 'approved'): ?>
                                <div class="flex items-center justify-end gap-1.5">
                                    <button onclick="vaccinateApt(<?php echo (int)($apt['appointment_id'] ?? 0); ?>)" class="p-2 text-teal-500 hover:text-teal-700 hover:bg-teal-50 rounded-lg transition" title="Mark Vaccinated"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></button>
                                </div>
                            <?php else: ?>
                                <span class="text-xs text-slate-300 font-medium">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
</div>

<script>
function filterApts(status) {
    document.querySelectorAll('.filter-tab').forEach(function(t) {
        var isActive = t.dataset.filter === status;
        t.className = 'filter-tab px-4 py-2 rounded-xl text-xs font-semibold transition-all ' +
            (isActive ? 'bg-slate-900 text-white shadow-sm' : 'bg-white text-slate-500 border border-slate-200 hover:border-slate-300');
    });
    document.querySelectorAll('.apt-row').forEach(function(r) {
        r.style.display = (status === 'all' || r.dataset.status === status) ? '' : 'none';
    });
}

async function aptAction(path, id) {
    try {
        var r = await fetch('<?php echo BASE_URL; ?>hospital/appointments/' + path, {
            method: 'POST', headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ csrf_token: CSRF_TOKEN, appointment_id: id })
        });
        var d = await r.json();
        if (d.success) { showToast(d.message, 'success'); setTimeout(function() { location.reload(); }, 600); }
        else showToast(d.message, 'error');
    } catch(e) { showToast('Network error', 'error'); }
}
function approveApt(id) { aptAction('approve', id); }
function rejectApt(id) { aptAction('reject', id); }
function vaccinateApt(id) { aptAction('vaccinate', id); }
</script>
