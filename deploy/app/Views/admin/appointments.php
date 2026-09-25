<!-- Admin Appointments - Enterprise -->
<div class="flex-col sm:flex-row gap-3 sm:gap-0 items-start sm:items-center justify-between mb-6 fade-in">
    <p class="text-sm text-slate-500">View all vaccination appointments across Pakistan — approvals are handled by each hospital</p>
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
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider hidden md:table-cell">Hospital</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider hidden sm:table-cell">Scheduled</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50" id="aptTableBody">
                <?php if (empty($appointments)): ?>
                    <tr><td colspan="6" class="px-6 py-16 text-center text-slate-300">No appointments found</td></tr>
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
                        <td class="px-6 py-4 text-sm text-slate-500 hidden md:table-cell"><?php echo htmlspecialchars($apt['hospital_name'] ?? ''); ?></td>
                        <td class="px-6 py-4 hidden sm:table-cell">
                            <span class="text-xs font-medium text-slate-500 bg-slate-50 px-2.5 py-1 rounded-lg"><?php echo $apt['scheduled_date'] ? date('d M Y', strtotime($apt['scheduled_date'])) : '—'; ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <?php
                            $statusMap = [
                                'pending'       => 'badge-pending',
                                'approved'      => 'badge-approved',
                                'vaccinated'    => 'badge-vaccinated',
                                'rejected'      => 'badge-rejected',
                                'not_vaccinated' => 'badge-not-vaccinated',
                                'cancelled'     => 'badge-cancelled',
                            ];
                            $statusKey = strtolower($apt['status'] ?? '');
                            $badge = $statusMap[$statusKey] ?? 'badge-cancelled';
                            ?>
                            <span class="badge <?php echo $badge; ?>"><?php echo ucfirst(str_replace('_', ' ', $statusKey)); ?></span>
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
</script>
