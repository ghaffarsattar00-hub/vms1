<!-- Admin Hospitals - Enterprise -->
<?php if (isset($_SESSION['success']) && str_contains($_SESSION['success'], 'Login:')): ?>
<?php $credMsg = $_SESSION['success']; unset($_SESSION['success']); ?>
<div class="mb-6 p-4 sm:p-5 bg-emerald-50 border border-emerald-200 rounded-2xl fade-in">
    <div class="flex items-start gap-3">
        <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center flex-shrink-0"><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></div>
        <div class="flex-1 min-w-0">
            <p class="font-semibold text-emerald-800 text-sm">Hospital Created Successfully</p>
            <p class="text-emerald-700 text-xs mt-1 break-all"><?php echo htmlspecialchars($credMsg); ?></p>
            <p class="text-emerald-600 text-[11px] mt-2">Hospital staff can now login with these credentials. Default password: <code class="bg-emerald-100 px-1.5 py-0.5 rounded font-mono">hospital123</code></p>
        </div>
        <button onclick="this.parentElement.parentElement.remove()" class="text-emerald-400 hover:text-emerald-600 p-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
    </div>
</div>
<?php endif; ?>
<div class="flex flex-col sm:flex-row items-center justify-between gap-3 sm:gap-0 mb-6 fade-in">
    <p class="text-sm text-slate-500">Manage registered healthcare facilities across Pakistan</p>
    <button onclick="openModal('createModal')" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold rounded-xl shadow-lg shadow-slate-900/20 transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.99]">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
        Add Hospital
    </button>
</div>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden fade-in" style="animation-delay:0.1s">
    <div class="table-responsive">
        <table class="w-full text-left sticky-header">
            <thead>
                <tr class="border-b border-slate-100">
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Hospital</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">City</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Phone</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider hidden sm:table-cell">Email</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php if (empty($hospitals)): ?>
                    <tr><td colspan="6" class="px-6 py-16 text-center text-slate-300">No hospitals registered</td></tr>
                <?php else: foreach ($hospitals as $i => $h): ?>
                    <tr class="hover:bg-slate-50/80 transition" style="animation-delay:<?php echo $i*0.03; ?>s">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-teal-400 to-teal-600 rounded-xl flex items-center justify-center text-white text-sm font-bold shadow-sm flex-shrink-0"><?php echo strtoupper(substr($h['name'],0,2)); ?></div>
                                <div><p class="font-semibold text-slate-800 text-sm"><?php echo htmlspecialchars($h['name']); ?></p><p class="text-[11px] text-slate-400"><?php echo htmlspecialchars($h['address_line1'] ?? ''); ?></p></div>
                            </div>
                        </td>
                        <td class="px-6 py-4"><span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 text-slate-600"><?php echo htmlspecialchars($h['city'] ?? ''); ?></span></td>
                        <td class="px-6 py-4 text-sm text-slate-500 font-mono text-xs"><?php echo htmlspecialchars($h['phone'] ?? ''); ?></td>
                        <td class="px-6 py-4 text-sm text-slate-500 hidden sm:table-cell"><?php echo htmlspecialchars($h['email'] ?? ''); ?></td>
                        <td class="px-6 py-4"><span class="badge <?php echo $h['is_active'] ? 'badge-vaccinated' : 'badge-cancelled'; ?>"><?php echo $h['is_active'] ? 'Active' : 'Inactive'; ?></span></td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <button onclick='openEditModal(<?php echo json_encode($h); ?>)' class="p-2 text-slate-400 hover:text-teal-600 hover:bg-teal-50 rounded-lg transition" title="Edit"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                <a href="<?php echo BASE_URL; ?>admin/hospitals/delete/<?php echo $h['hospital_id']; ?>" onclick="return confirm('Deactivate this hospital?')" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition" title="Deactivate"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Create Hospital Modal -->
<div id="createModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeModal('createModal')"></div>
    <div class="modal-glass relative w-full max-w-lg rounded-2xl shadow-2xl p-0 border border-slate-200/50 mx-2 sm:mx-0" style="animation: fadeIn 0.2s ease-out forwards">
        <div class="flex items-center justify-between p-6 border-b border-slate-100">
            <div><h3 class="text-lg font-bold text-slate-900">Add New Hospital</h3><p class="text-xs text-slate-400 mt-0.5">Register a new healthcare facility</p></div>
            <button onclick="closeModal('createModal')" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
        <form method="POST" action="<?php echo BASE_URL; ?>admin/hospitals/create" class="p-6 space-y-4">
            <?php echo $csrfInput; ?>
            <div><label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Hospital Name *</label><input type="text" name="name" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-0 focus:border-teal-500 input-glow outline-none transition" placeholder="e.g. Aga Khan University Hospital"></div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div><label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Registration No.</label><input type="text" name="registration_number" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-0 focus:border-teal-500 input-glow outline-none transition" placeholder="AKU-REG-001"></div>
                <div><label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Phone</label><input type="text" name="phone" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-0 focus:border-teal-500 input-glow outline-none transition" placeholder="+92 21 34861000"></div>
            </div>
            <div><label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Email</label><input type="email" name="email" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-0 focus:border-teal-500 input-glow outline-none transition" placeholder="info@hospital.edu.pk"></div>
            <div><label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Address</label><input type="text" name="address_line1" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-0 focus:border-teal-500 input-glow outline-none transition" placeholder="Street address"></div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div><label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">City *</label><input type="text" name="city" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-0 focus:border-teal-500 input-glow outline-none transition" placeholder="Karachi"></div>
                <div><label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Province</label><input type="text" name="state" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-0 focus:border-teal-500 input-glow outline-none transition" placeholder="Sindh"></div>
                <div><label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Postal Code</label><input type="text" name="postal_code" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-0 focus:border-teal-500 input-glow outline-none transition" placeholder="74800"></div>
            </div>
            <div class="flex justify-end gap-3 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeModal('createModal')" class="px-5 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-100 rounded-xl transition">Cancel</button>
                <button type="submit" class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold rounded-xl shadow-lg shadow-slate-900/20 transition-all duration-200">Create Hospital</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Hospital Modal -->
<div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeModal('editModal')"></div>
    <div class="modal-glass relative w-full max-w-lg rounded-2xl shadow-2xl p-0 border border-slate-200/50 mx-2 sm:mx-0" style="animation: fadeIn 0.2s ease-out forwards">
        <div class="flex items-center justify-between p-6 border-b border-slate-100">
            <div><h3 class="text-lg font-bold text-slate-900">Edit Hospital</h3></div>
            <button onclick="closeModal('editModal')" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
        <form method="POST" action="<?php echo BASE_URL; ?>admin/hospitals/update" class="p-6 space-y-4">
            <?php echo $csrfInput; ?>
            <input type="hidden" name="hospital_id" id="editHospitalId">
            <div><label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Hospital Name</label><input type="text" name="name" id="editName" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-0 focus:border-teal-500 input-glow outline-none transition"></div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div><label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Phone</label><input type="text" name="phone" id="editPhone" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-0 focus:border-teal-500 input-glow outline-none transition"></div>
                <div><label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">City</label><input type="text" name="city" id="editCity" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-0 focus:border-teal-500 input-glow outline-none transition"></div>
            </div>
            <div><label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Address</label><input type="text" name="address_line1" id="editAddr" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-0 focus:border-teal-500 input-glow outline-none transition"></div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div><label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">State</label><input type="text" name="state" id="editState" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-0 focus:border-teal-500 input-glow outline-none transition"></div>
                <div><label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Postal Code</label><input type="text" name="postal_code" id="editZip" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-0 focus:border-teal-500 input-glow outline-none transition"></div>
            </div>
            <div class="flex justify-end gap-3 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeModal('editModal')" class="px-5 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-100 rounded-xl transition">Cancel</button>
                <button type="submit" class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold rounded-xl shadow-lg shadow-slate-900/20 transition-all duration-200">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(h) {
    document.getElementById('editHospitalId').value = h.hospital_id;
    document.getElementById('editName').value = h.name || '';
    document.getElementById('editPhone').value = h.phone || '';
    document.getElementById('editAddr').value = h.address_line1 || '';
    document.getElementById('editCity').value = h.city || '';
    document.getElementById('editState').value = h.state || '';
    document.getElementById('editZip').value = h.postal_code || '';
    openModal('editModal');
}
</script>
