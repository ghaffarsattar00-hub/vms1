<!-- Admin Inventory - Enterprise -->
<div class="mb-6 fade-in">
    <p class="text-sm text-slate-500">Manage vaccine stock availability across all hospitals in Pakistan</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
    <?php if (empty($inventory)): ?>
        <div class="col-span-full bg-white rounded-2xl p-16 text-center border border-slate-100 shadow-sm fade-in">
            <p class="text-slate-300 font-medium">No inventory records found</p>
        </div>
    <?php else: foreach ($inventory as $i => $item): ?>
        <div class="widget-card bg-white rounded-2xl p-4 sm:p-5 border border-slate-100 shadow-sm fade-in" style="animation-delay:<?php echo $i*0.03; ?>s">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 <?php echo $item['is_available'] ? 'bg-emerald-50' : 'bg-red-50'; ?> rounded-xl flex items-center justify-center transition-colors duration-200">
                        <svg class="w-5 h-5 <?php echo $item['is_available'] ? 'text-emerald-600' : 'text-red-400'; ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                    </div>
                    <div><p class="font-bold text-slate-800 text-sm"><?php echo htmlspecialchars($item['vaccine_name']); ?></p><p class="text-[11px] text-slate-400"><?php echo htmlspecialchars($item['targeted_disease'] ?? ''); ?></p></div>
                </div>
                <!-- Toggle -->
                <button onclick="toggleInv(<?php echo $item['inventory_id']; ?>, <?php echo $item['is_available']; ?>)" id="btn-<?php echo $item['inventory_id']; ?>" class="toggle-track relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent <?php echo $item['is_available'] ? 'bg-emerald-500' : 'bg-slate-300'; ?> transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                    <span id="knob-<?php echo $item['inventory_id']; ?>" class="toggle-knob pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-md ring-0 <?php echo $item['is_available'] ? 'translate-x-5' : 'translate-x-0'; ?> transition-transform duration-200"></span>
                </button>
            </div>
            <div class="flex items-center gap-2 mb-3">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <span class="text-xs text-slate-500 font-medium"><?php echo htmlspecialchars($item['hospital_name']); ?></span>
            </div>
            <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                <div class="flex items-center gap-3 text-[11px] text-slate-400">
                    <span>Stock: <strong class="text-slate-600"><?php echo $item['available_stock']; ?></strong></span>
                    <span class="text-slate-200">|</span>
                    <span>Reorder: <strong class="text-slate-600"><?php echo $item['reorder_level']; ?></strong></span>
                </div>
                <span id="lbl-<?php echo $item['inventory_id']; ?>" class="badge <?php echo $item['is_available'] ? 'badge-vaccinated' : 'badge-rejected'; ?>"><?php echo $item['is_available'] ? 'Available' : 'Unavailable'; ?></span>
            </div>
        </div>
    <?php endforeach; endif; ?>
</div>

<script>
async function toggleInv(id, current) {
    var next = current ? 0 : 1;
    var btn = document.getElementById('btn-'+id);
    var knob = document.getElementById('knob-'+id);
    var lbl = document.getElementById('lbl-'+id);

    btn.className = btn.className.replace(current ? 'bg-emerald-500' : 'bg-slate-300', next ? 'bg-emerald-500' : 'bg-slate-300');
    knob.className = knob.className.replace(current ? 'translate-x-5' : 'translate-x-0', next ? 'translate-x-5' : 'translate-x-0');
    lbl.textContent = next ? 'Available' : 'Unavailable';
    lbl.className = 'badge ' + (next ? 'badge-vaccinated' : 'badge-rejected');

    try {
        var r = await fetch('<?php echo BASE_URL; ?>admin/inventory/toggle', {
            method: 'POST', headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ csrf_token: CSRF_TOKEN, inventory_id: id, is_available: next })
        });
        var d = await r.json();
        if (!d.success) { toggleInv(id, next); showToast(d.message, 'error'); }
        else showToast(d.message, 'success');
    } catch(e) { toggleInv(id, next); showToast('Network error', 'error'); }
}
</script>
