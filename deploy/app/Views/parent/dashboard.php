<!-- Parent Dashboard - Enterprise -->
<!-- Welcome Banner -->
<div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 rounded-2xl p-6 mb-8 text-white shadow-xl shadow-slate-900/20 fade-in">
    <div class="flex flex-col sm:flex-row gap-4 sm:gap-0 items-start sm:items-center w-full sm:justify-between">
        <div class="min-w-0">
            <p class="text-sm text-slate-300 mb-1">Welcome back</p>
            <h2 class="text-2xl font-extrabold"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Parent'); ?></h2>
            <p class="text-sm text-slate-300 mt-2">Keep your children's vaccinations up to date. Book appointments and track progress below.</p>
        </div>
        <a href="<?php echo BASE_URL; ?>parent/book" class="inline-flex w-fit shrink-0 items-center justify-center gap-2 px-5 py-3 bg-teal-500 hover:bg-teal-400 text-white text-sm font-semibold rounded-xl shadow-lg shadow-teal-500/30 transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.99]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Book Vaccination
        </a>
    </div>
</div>

<!-- Stats Row -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
    <div class="widget-card bg-white rounded-2xl p-4 sm:p-5 border border-slate-100 shadow-sm fade-in" style="animation-delay:0.05s">
        <div class="flex items-center gap-4">
            <div class="w-11 h-11 bg-amber-50 rounded-xl flex items-center justify-center"><svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
            <div><p class="text-2xl font-extrabold text-slate-900"><?php echo count($children); ?></p><p class="text-xs text-slate-400 font-medium">My Children</p></div>
        </div>
    </div>
    <div class="widget-card bg-white rounded-2xl p-4 sm:p-5 border border-slate-100 shadow-sm fade-in" style="animation-delay:0.1s">
        <div class="flex items-center gap-4">
            <div class="w-11 h-11 bg-amber-50 rounded-xl flex items-center justify-center"><span class="pulse-dot w-2.5 h-2.5 bg-amber-500 rounded-full"></span></div>
            <div><p class="text-2xl font-extrabold text-slate-900"><?php echo $pendingCount; ?></p><p class="text-xs text-slate-400 font-medium">Pending</p></div>
        </div>
    </div>
    <div class="widget-card bg-white rounded-2xl p-4 sm:p-5 border border-slate-100 shadow-sm fade-in" style="animation-delay:0.15s">
        <div class="flex items-center gap-4">
            <div class="w-11 h-11 bg-emerald-50 rounded-xl flex items-center justify-center"><svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
            <div><p class="text-2xl font-extrabold text-slate-900"><?php echo $vaccinatedCount; ?></p><p class="text-xs text-slate-400 font-medium">Vaccinated</p></div>
        </div>
    </div>
</div>

<!-- Children + Appointments -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
    <!-- Children List -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden fade-in" style="animation-delay:0.2s">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-800">My Children</h3>
            <button onclick="openModal('addChildModal')" class="p-1.5 text-slate-400 hover:text-teal-600 hover:bg-teal-50 rounded-lg transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg></button>
        </div>
        <div class="divide-y divide-slate-50">
            <?php if (empty($children)): ?>
                <p class="p-8 text-center text-slate-300 text-sm">No children registered yet</p>
            <?php else: foreach ($children as $i => $child): ?>
                <div class="px-5 py-4 hover:bg-slate-50/80 transition flex items-center gap-3" style="animation-delay:<?php echo $i*0.03; ?>s">
                    <div class="w-10 h-10 bg-gradient-to-br from-amber-400 to-orange-500 rounded-xl flex items-center justify-center text-white text-sm font-bold shadow-sm flex-shrink-0"><?php echo strtoupper(substr($child['first_name'],0,1)); ?></div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-slate-800 text-sm truncate"><?php echo htmlspecialchars($child['first_name'] . ' ' . $child['last_name']); ?></p>
                        <p class="text-[11px] text-slate-400">DOB: <?php echo date('d M Y', strtotime($child['date_of_birth'])); ?></p>
                    </div>
                    <span class="badge badge-vaccinated text-[10px]"><?php echo $child['vaccinated_count']; ?> doses</span>
                </div>
            <?php endforeach; endif; ?>
        </div>
    </div>

    <!-- Appointments -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden fade-in" style="animation-delay:0.25s">
        <div class="px-5 py-4 border-b border-slate-100">
            <h3 class="text-sm font-bold text-slate-800">My Appointments</h3>
        </div>
        <div class="table-responsive">
            <div class="overflow-x-auto">
            <table class="w-full text-left sticky-header">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="px-5 py-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Child</th>
                        <th class="px-5 py-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Vaccine</th>
                        <th class="px-5 py-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider hidden sm:table-cell">Hospital</th>
                        <th class="px-5 py-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider hidden md:table-cell">Date</th>
                        <th class="px-5 py-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if (empty($appointments)): ?>
                        <tr><td colspan="5" class="px-5 py-12 text-center text-slate-300">No appointments yet</td></tr>
                    <?php else: foreach ($appointments as $i => $apt): ?>
                        <tr class="hover:bg-slate-50/80 transition" style="animation-delay:<?php echo $i*0.02; ?>s">
                            <td class="px-5 py-3.5">
                                <span class="font-medium text-slate-700 text-sm"><?php echo htmlspecialchars($apt['first_name'] . ' ' . $apt['last_name']); ?></span>
                            </td>
                            <td class="px-5 py-3.5">
                                <p class="text-sm text-slate-600"><?php echo htmlspecialchars($apt['vaccine_name']); ?></p>
                                <p class="text-[11px] text-slate-400">Dose <?php echo $apt['dose_number']; ?></p>
                            </td>
                            <td class="px-5 py-3.5 text-sm text-slate-500 hidden sm:table-cell"><?php echo htmlspecialchars($apt['hospital_name']); ?></td>
                            <td class="px-5 py-3.5 hidden md:table-cell"><span class="text-xs font-medium text-slate-500 bg-slate-50 px-2.5 py-1 rounded-lg"><?php echo date('d M Y', strtotime($apt['scheduled_date'])); ?></span></td>
                            <td class="px-5 py-3.5">
                                <?php
                                $statusMap = ['pending'=>'badge-pending','approved'=>'badge-approved','vaccinated'=>'badge-vaccinated','rejected'=>'badge-rejected','cancelled'=>'badge-cancelled'];
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
</div>

<!-- Add Child Modal -->
<div id="addChildModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeModal('addChildModal')"></div>
    <div class="modal-glass relative w-full max-w-md mx-2 sm:mx-0 rounded-2xl shadow-2xl p-0 border border-slate-200/50" style="animation: fadeIn 0.2s ease-out forwards">
        <div class="flex items-center justify-between p-6 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-900">Add Child</h3>
            <button onclick="closeModal('addChildModal')" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
        <form method="POST" action="<?php echo BASE_URL; ?>parent/dashboard/add-child" class="p-6 space-y-4">
            <?php echo $csrfInput; ?>
            <div><label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">First Name *</label><input type="text" name="first_name" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-0 focus:border-teal-500 outline-none transition" placeholder="Ahmed"></div>
            <div><label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Last Name *</label><input type="text" name="last_name" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-0 focus:border-teal-500 outline-none transition" placeholder="Khan"></div>
            <div><label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Date of Birth *</label><input type="date" name="date_of_birth" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-0 focus:border-teal-500 outline-none transition"></div>
            <div class="flex justify-end gap-3 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeModal('addChildModal')" class="px-5 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-100 rounded-xl transition">Cancel</button>
                <button type="submit" class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold rounded-xl shadow-lg shadow-slate-900/20 transition-all duration-200">Add Child</button>
            </div>
        </form>
    </div>
</div>
