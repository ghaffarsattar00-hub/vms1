<!-- Hospital Orders - Enterprise -->
<div class="mb-6 fade-in">
    <p class="text-sm text-slate-500">Track and manage your vaccine orders at <?php echo htmlspecialchars($hospital['name'] ?? 'your hospital'); ?></p>
</div>

<?php if (empty($orders)): ?>
    <div class="bg-white rounded-2xl p-16 text-center border border-slate-100 shadow-sm fade-in">
        <div class="inline-flex p-4 bg-slate-50 rounded-full mb-4">
            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        </div>
        <p class="text-slate-400 font-medium">No orders placed yet</p>
        <p class="text-xs text-slate-300 mt-1">Go to Inventory to place your first order</p>
    </div>
<?php else: ?>
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden fade-in">
    <div class="table-responsive">
        <table class="w-full text-left sticky-header">
            <thead>
                <tr class="border-b border-slate-100">
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Order</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Vaccine</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Qty</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">ETA / Status</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php foreach ($orders as $i => $order):
                    $isPending = $order['status'] === 'pending';
                    $isApproved = $order['status'] === 'approved';
                    $minutesLeft = (int)($order['minutes_left'] ?? 0);
                    $etaExpired = $minutesLeft <= 0;
                ?>
                <tr class="hover:bg-slate-50/80 transition" style="animation-delay:<?php echo $i*0.02; ?>s">
                    <td class="px-6 py-4 text-xs font-bold text-slate-600">#<?php echo $order['order_id']; ?></td>
                    <td class="px-6 py-4">
                        <p class="text-sm font-semibold text-slate-800"><?php echo htmlspecialchars($order['vaccine_name']); ?></p>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-slate-700"><?php echo $order['quantity_ordered']; ?> doses</td>
                    <td class="px-6 py-4">
                        <?php if ($order['status'] === 'delivered'): ?>
                            <div class="flex items-center gap-2">
                                <span class="badge bg-emerald-100 text-emerald-700 text-[10px] font-bold px-2.5 py-1">Delivered</span>
                                <?php if ($order['actual_delivery']): ?>
                                    <span class="text-[10px] text-slate-400"><?php echo date('d M, g:i A', strtotime($order['actual_delivery'])); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php elseif ($order['status'] === 'cancelled'): ?>
                            <span class="badge bg-red-100 text-red-700 text-[10px] font-bold px-2.5 py-1">Cancelled</span>
                        <?php elseif ($isPending): ?>
                            <div class="flex items-center gap-2">
                                <span class="badge bg-amber-100 text-amber-700 text-[10px] font-bold px-2.5 py-1">Pending</span>
                                <div class="flex items-center gap-1 text-[10px] text-amber-600">
                                    <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                    <span>Awaiting approval</span>
                                </div>
                            </div>
                        <?php elseif ($isApproved): ?>
                            <div class="flex items-center gap-2">
                                <span class="badge bg-blue-100 text-blue-700 text-[10px] font-bold px-2.5 py-1">Approved</span>
                                <?php if (!$etaExpired && $minutesLeft > 0): ?>
                                    <div class="flex items-center gap-1.5 bg-blue-50 px-2.5 py-1 rounded-lg" id="eta-<?php echo $order['order_id']; ?>">
                                        <svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span class="text-[10px] font-bold text-blue-700 countdown" data-seconds="<?php echo $minutesLeft * 60; ?>">
                                            <?php echo $minutesLeft; ?>m remaining
                                        </span>
                                    </div>
                                <?php else: ?>
                                    <span class="text-[10px] font-semibold text-emerald-600 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Arriving any moment
                                    </span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-[11px] text-slate-400">
                        <div><?php echo date('d M Y', strtotime($order['created_at'])); ?></div>
                        <?php if ($order['estimated_delivery']): ?>
                            <div class="text-[10px] text-slate-300 mt-0.5">ETA: <?php echo date('g:i A', strtotime($order['estimated_delivery'])); ?></div>
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
(function() {
    var countdowns = document.querySelectorAll('.countdown');
    countdowns.forEach(function(el) {
        var seconds = parseInt(el.getAttribute('data-seconds'));
        if (seconds <= 0) return;

        var interval = setInterval(function() {
            seconds--;
            if (seconds <= 0) {
                clearInterval(interval);
                el.textContent = 'Arriving any moment';
                el.classList.remove('text-blue-700');
                el.classList.add('text-emerald-600');
                var parent = el.closest('.bg-blue-50');
                if (parent) {
                    parent.classList.remove('bg-blue-50');
                    parent.classList.add('bg-emerald-50');
                }
                return;
            }
            var m = Math.floor(seconds / 60);
            var s = seconds % 60;
            el.textContent = m + 'm ' + (s < 10 ? '0' : '') + s + 's';
        }, 1000);
    });
})();
</script>
