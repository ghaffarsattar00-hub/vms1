<!-- Admin Orders - Enterprise -->
<div class="mb-6 fade-in">
    <p class="text-sm text-slate-500">Review and manage vaccine orders from all hospitals</p>
</div>

<!-- Stats -->
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
    <div class="widget-card bg-white rounded-2xl p-4 border border-slate-100 shadow-sm fade-in" style="animation-delay:0.05s">
        <p class="text-2xl font-extrabold text-slate-900"><?php echo $totalOrders; ?></p>
        <p class="text-[11px] text-slate-400 font-medium mt-1">Total Orders</p>
    </div>
    <div class="widget-card bg-white rounded-2xl p-4 border border-slate-100 shadow-sm fade-in" style="animation-delay:0.1s">
        <div class="flex items-center gap-2">
            <span class="pulse-dot w-2 h-2 bg-amber-500 rounded-full"></span>
            <p class="text-2xl font-extrabold text-amber-600"><?php echo $pendingCount; ?></p>
        </div>
        <p class="text-[11px] text-slate-400 font-medium mt-1">Pending</p>
    </div>
    <div class="widget-card bg-white rounded-2xl p-4 border border-slate-100 shadow-sm fade-in" style="animation-delay:0.15s">
        <p class="text-2xl font-extrabold text-blue-600"><?php echo $approvedCount; ?></p>
        <p class="text-[11px] text-slate-400 font-medium mt-1">Approved</p>
    </div>
    <div class="widget-card bg-white rounded-2xl p-4 border border-slate-100 shadow-sm fade-in" style="animation-delay:0.2s">
        <p class="text-2xl font-extrabold text-emerald-600"><?php echo $deliveredCount; ?></p>
        <p class="text-[11px] text-slate-400 font-medium mt-1">Delivered</p>
    </div>
</div>

<?php if (empty($orders)): ?>
    <div class="bg-white rounded-2xl p-16 text-center border border-slate-100 shadow-sm fade-in">
        <div class="inline-flex p-4 bg-slate-50 rounded-full mb-4">
            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        </div>
        <p class="text-slate-400 font-medium">No orders yet</p>
    </div>
<?php else: ?>
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden fade-in" style="animation-delay:0.25s">
    <div class="table-responsive">
        <table class="w-full text-left sticky-header">
            <thead>
                <tr class="border-b border-slate-100">
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Order</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Hospital</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Vaccine</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Qty</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Status</th>
                    <th class="hidden sm:table-cell px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Ordered By</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php foreach ($orders as $i => $order): ?>
                <tr class="hover:bg-slate-50/80 transition" style="animation-delay:<?php echo $i*0.02; ?>s">
                    <td class="px-6 py-4 text-xs font-bold text-slate-600">#<?php echo $order['order_id']; ?></td>
                    <td class="px-6 py-4">
                        <p class="text-sm font-semibold text-slate-800"><?php echo htmlspecialchars($order['hospital_name']); ?></p>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-700"><?php echo htmlspecialchars($order['vaccine_name']); ?></td>
                    <td class="px-6 py-4 text-sm font-medium text-slate-700"><?php echo $order['quantity_ordered']; ?></td>
                    <td class="px-6 py-4">
                        <?php
                        $statusColors = [
                            'pending' => 'bg-amber-100 text-amber-700',
                            'approved' => 'bg-blue-100 text-blue-700',
                            'delivered' => 'bg-emerald-100 text-emerald-700',
                            'cancelled' => 'bg-red-100 text-red-700'
                        ];
                        $color = $statusColors[$order['status']] ?? 'bg-slate-100 text-slate-600';
                        $minutesLeft = (int)($order['minutes_left'] ?? 0);
                        ?>
                        <div class="flex items-center gap-2">
                            <span class="badge <?php echo $color; ?> text-[10px] font-bold px-2.5 py-1"><?php echo ucfirst($order['status']); ?></span>
                            <?php if ($order['status'] === 'approved' && $minutesLeft > 0): ?>
                                <span class="text-[10px] text-blue-500 font-medium"><?php echo $minutesLeft; ?>m left</span>
                            <?php elseif ($order['status'] === 'delivered' && $order['actual_delivery']): ?>
                                <span class="text-[10px] text-slate-400"><?php echo date('d M, g:i A', strtotime($order['actual_delivery'])); ?></span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="hidden sm:table-cell px-6 py-4 text-[11px] text-slate-400"><?php echo htmlspecialchars($order['ordered_by_name']); ?></td>
                    <td class="px-6 py-4 text-right">
                        <?php if ($order['status'] === 'pending'): ?>
                        <div class="flex items-center justify-end gap-1.5">
                            <button onclick="updateOrder(<?php echo $order['order_id']; ?>, 'approved')" class="p-2 text-emerald-500 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition" title="Approve">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </button>
                            <button onclick="updateOrder(<?php echo $order['order_id']; ?>, 'cancelled')" class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Cancel">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        <?php elseif ($order['status'] === 'approved'): ?>
                        <button onclick="updateOrder(<?php echo $order['order_id']; ?>, 'delivered')" class="p-2 text-blue-500 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition" title="Mark Delivered">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </button>
                        <?php else: ?>
                        <span class="text-[11px] text-slate-300">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<script>
async function updateOrder(id, status) {
    try {
        var r = await fetch('<?php echo BASE_URL; ?>admin/orders/update', {
            method: 'POST', headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ csrf_token: CSRF_TOKEN, order_id: id, status: status })
        });
        var d = await r.json();
        if (d.success) { showToast(d.message, 'success'); setTimeout(function() { location.reload(); }, 600); }
        else showToast(d.message, 'error');
    } catch(e) { showToast('Network error', 'error'); }
}
</script>
