<!-- Hospital Inventory - Enterprise -->
<div class="mb-6 fade-in">
    <p class="text-sm text-slate-500">View vaccine stock availability at <?php echo htmlspecialchars($hospital['name'] ?? 'your hospital'); ?></p>
</div>

<!-- Low Stock Alert -->
<?php
$lowItems = array_filter($inventory, fn($item) => $item['is_low'] || $item['is_out']);
if (!empty($lowItems)): ?>
<div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-6 fade-in">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <div>
            <p class="text-sm font-bold text-amber-800"><?php echo count($lowItems); ?> vaccine(s) need restocking</p>
            <p class="text-xs text-amber-600">Place an order to replenish stock</p>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
    <?php if (empty($inventory)): ?>
        <div class="col-span-full bg-white rounded-2xl p-16 text-center border border-slate-100 shadow-sm fade-in">
            <p class="text-slate-300 font-medium">No inventory records found</p>
        </div>
    <?php else: foreach ($inventory as $i => $item): ?>
        <div class="widget-card bg-white rounded-2xl p-4 sm:p-5 border border-slate-100 shadow-sm fade-in <?php echo $item['is_out'] ? 'border-red-200' : ($item['is_low'] ? 'border-amber-200' : ''); ?>" style="animation-delay:<?php echo $i*0.03; ?>s">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 <?php echo $item['is_out'] ? 'bg-red-50' : ($item['is_low'] ? 'bg-amber-50' : 'bg-blue-50'); ?> rounded-xl flex items-center justify-center transition-colors duration-200">
                        <svg class="w-5 h-5 <?php echo $item['is_out'] ? 'text-red-500' : ($item['is_low'] ? 'text-amber-600' : 'text-blue-600'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                    </div>
                    <div>
                        <p class="font-bold text-slate-800 text-sm"><?php echo htmlspecialchars($item['vaccine_name']); ?></p>
                        <p class="text-[11px] text-slate-400"><?php echo htmlspecialchars($item['targeted_disease'] ?? ''); ?></p>
                    </div>
                </div>
                <?php if ($item['is_out']): ?>
                    <span class="badge bg-red-100 text-red-700 text-[10px] font-bold px-2.5 py-1">Out of Stock</span>
                <?php elseif ($item['is_low']): ?>
                    <span class="badge bg-amber-100 text-amber-700 text-[10px] font-bold px-2.5 py-1">Low Stock</span>
                <?php else: ?>
                    <span class="badge badge-vaccinated">Available</span>
                <?php endif; ?>
            </div>

            <div class="pt-3 border-t border-slate-100 mb-3">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-[11px] text-slate-400">Stock Level</span>
                    <span class="text-[11px] font-bold <?php echo $item['is_out'] ? 'text-red-600' : ($item['is_low'] ? 'text-amber-600' : 'text-slate-600'); ?>"><?php echo $item['available_stock']; ?> doses</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2">
                    <?php
                    $maxStock = max($item['reorder_level'] * 3, 100);
                    $pct = min(100, ($item['available_stock'] / $maxStock) * 100);
                    $barColor = $item['is_out'] ? 'bg-red-500' : ($item['is_low'] ? 'bg-amber-500' : 'bg-blue-500');
                    ?>
                    <div class="<?php echo $barColor; ?> h-2 rounded-full transition-all duration-500" style="width:<?php echo $pct; ?>%"></div>
                </div>
                <div class="flex items-center justify-between mt-1">
                    <span class="text-[10px] text-slate-300">Reorder at: <?php echo $item['reorder_level']; ?></span>
                    <span class="text-[10px] text-slate-300"><?php echo $item['is_available'] ? 'Active' : 'Inactive'; ?></span>
                </div>
            </div>

            <div class="flex gap-2">
                <button onclick="openOrderModal(<?php echo $item['inventory_id']; ?>, '<?php echo htmlspecialchars(addslashes($item['vaccine_name'])); ?>', <?php echo $item['available_stock']; ?>)" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-blue-500 hover:bg-blue-400 text-white text-[11px] font-semibold rounded-xl transition-all duration-200 shadow-sm shadow-blue-500/20">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    Order
                </button>
                <button onclick="openRestockModal(<?php echo $item['inventory_id']; ?>, '<?php echo htmlspecialchars(addslashes($item['vaccine_name'])); ?>', <?php echo $item['available_stock']; ?>)" class="inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-[11px] font-semibold rounded-xl transition-all duration-200">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Restock
                </button>
            </div>
        </div>
    <?php endforeach; endif; ?>
</div>

<!-- Order History -->
<?php if (!empty($orders)): ?>
<div class="mt-8 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden fade-in">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <h3 class="text-sm font-bold text-slate-800">Order History</h3>
        <a href="<?php echo BASE_URL; ?>hospital/orders" class="text-[11px] font-semibold text-blue-600 hover:text-blue-700">View All</a>
    </div>
    <div class="table-responsive">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-slate-100">
                    <th class="px-6 py-3 text-[11px] font-bold text-slate-400 uppercase">#</th>
                    <th class="px-6 py-3 text-[11px] font-bold text-slate-400 uppercase">Vaccine</th>
                    <th class="px-6 py-3 text-[11px] font-bold text-slate-400 uppercase">Qty</th>
                    <th class="px-6 py-3 text-[11px] font-bold text-slate-400 uppercase">Status</th>
                    <th class="px-6 py-3 text-[11px] font-bold text-slate-400 uppercase">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php foreach (array_slice($orders, 0, 5) as $order):
                    $minutesLeft = (int)($order['minutes_left'] ?? 0);
                ?>
                <tr class="hover:bg-slate-50/80 transition">
                    <td class="px-6 py-3 text-xs text-slate-500">#<?php echo $order['order_id']; ?></td>
                    <td class="px-6 py-3 text-xs font-medium text-slate-700"><?php echo htmlspecialchars($order['vaccine_name']); ?></td>
                    <td class="px-6 py-3 text-xs text-slate-600"><?php echo $order['quantity_ordered']; ?></td>
                    <td class="px-6 py-3">
                        <?php
                        $statusColors = [
                            'pending' => 'bg-amber-100 text-amber-700',
                            'approved' => 'bg-blue-100 text-blue-700',
                            'delivered' => 'bg-emerald-100 text-emerald-700',
                            'cancelled' => 'bg-red-100 text-red-700'
                        ];
                        $color = $statusColors[$order['status']] ?? 'bg-slate-100 text-slate-600';
                        ?>
                        <div class="flex items-center gap-1.5">
                            <span class="badge <?php echo $color; ?> text-[10px] font-bold px-2.5 py-1"><?php echo ucfirst($order['status']); ?></span>
                            <?php if ($order['status'] === 'approved' && $minutesLeft > 0): ?>
                                <span class="text-[10px] text-blue-500 font-medium"><?php echo $minutesLeft; ?>m</span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="px-6 py-3 text-[11px] text-slate-400"><?php echo date('d M Y', strtotime($order['created_at'])); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<!-- Order Modal -->
<div id="orderModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeOrderModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 relative fade-up">
            <button onclick="closeOrderModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <h3 class="text-lg font-bold text-slate-900 mb-1">Order Vaccine</h3>
            <p class="text-sm text-slate-500 mb-5">Place a restocking order for admin approval</p>

            <div class="bg-blue-50 rounded-xl p-3 mb-4">
                <p class="text-xs font-semibold text-blue-800" id="orderVaccineName">—</p>
                <p class="text-[11px] text-blue-600 mt-0.5">Current stock: <span id="orderCurrentStock">0</span> doses</p>
            </div>

            <div class="bg-amber-50 rounded-xl p-3 mb-4 flex items-start gap-2">
                <svg class="w-4 h-4 text-amber-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <p class="text-[11px] font-semibold text-amber-800">Estimated Delivery: 20-30 minutes</p>
                    <p class="text-[10px] text-amber-600 mt-0.5">After admin approval, stock will arrive within 20-30 minutes</p>
                </div>
            </div>

            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Quantity to Order</label>
                    <input type="number" id="orderQuantity" min="1" max="1000" value="50" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:ring-0 focus:border-blue-500 outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Notes (optional)</label>
                    <textarea id="orderNotes" rows="2" placeholder="Any special instructions..." class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:ring-0 focus:border-blue-500 outline-none transition resize-none"></textarea>
                </div>
            </div>

            <div class="flex gap-3 mt-5">
                <button onclick="closeOrderModal()" class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl transition text-sm">Cancel</button>
                <button onclick="submitOrder()" class="flex-1 py-3 bg-blue-500 hover:bg-blue-400 text-white font-semibold rounded-xl transition text-sm shadow-sm shadow-blue-500/20">Place Order</button>
            </div>
        </div>
    </div>
</div>

<!-- Restock Modal -->
<div id="restockModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeRestockModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 relative fade-up">
            <button onclick="closeRestockModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <h3 class="text-lg font-bold text-slate-900 mb-1">Quick Restock</h3>
            <p class="text-sm text-slate-500 mb-5">Directly add stock (for received deliveries)</p>

            <div class="bg-emerald-50 rounded-xl p-3 mb-4">
                <p class="text-xs font-semibold text-emerald-800" id="restockVaccineName">—</p>
                <p class="text-[11px] text-emerald-600 mt-0.5">Current stock: <span id="restockCurrentStock">0</span> doses</p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Quantity to Add</label>
                <input type="number" id="restockQuantity" min="1" max="5000" value="50" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:ring-0 focus:border-emerald-500 outline-none transition">
            </div>

            <div class="flex gap-3 mt-5">
                <button onclick="closeRestockModal()" class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl transition text-sm">Cancel</button>
                <button onclick="submitRestock()" class="flex-1 py-3 bg-emerald-500 hover:bg-emerald-400 text-white font-semibold rounded-xl transition text-sm shadow-sm shadow-emerald-500/20">Add Stock</button>
            </div>
        </div>
    </div>
</div>

<script>
var CSRF_TOKEN = '<?php echo Security::generateCsrfToken(); ?>';
var currentOrderId = 0;
var currentRestockId = 0;

function openOrderModal(invId, name, stock) {
    currentOrderId = invId;
    document.getElementById('orderVaccineName').textContent = name;
    document.getElementById('orderCurrentStock').textContent = stock;
    document.getElementById('orderQuantity').value = stock < 10 ? 100 : 50;
    document.getElementById('orderNotes').value = '';
    document.getElementById('orderModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeOrderModal() {
    document.getElementById('orderModal').classList.add('hidden');
    document.body.style.overflow = '';
}

function openRestockModal(invId, name, stock) {
    currentRestockId = invId;
    document.getElementById('restockVaccineName').textContent = name;
    document.getElementById('restockCurrentStock').textContent = stock;
    document.getElementById('restockQuantity').value = stock < 10 ? 100 : 50;
    document.getElementById('restockModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeRestockModal() {
    document.getElementById('restockModal').classList.add('hidden');
    document.body.style.overflow = '';
}

async function submitOrder() {
    var qty = parseInt(document.getElementById('orderQuantity').value);
    var notes = document.getElementById('orderNotes').value;
    if (qty <= 0 || qty > 1000) { showToast('Quantity must be 1-1000', 'error'); return; }

    try {
        var r = await fetch('<?php echo BASE_URL; ?>hospital/inventory/order', {
            method: 'POST', headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ csrf_token: CSRF_TOKEN, inventory_id: currentOrderId, quantity: qty, notes: notes })
        });
        var d = await r.json();
        if (d.success) { showToast(d.message, 'success'); closeOrderModal(); setTimeout(function() { location.reload(); }, 800); }
        else showToast(d.message, 'error');
    } catch(e) { showToast('Network error', 'error'); }
}

async function submitRestock() {
    var qty = parseInt(document.getElementById('restockQuantity').value);
    if (qty <= 0 || qty > 5000) { showToast('Quantity must be 1-5000', 'error'); return; }

    try {
        var r = await fetch('<?php echo BASE_URL; ?>hospital/inventory/restock', {
            method: 'POST', headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ csrf_token: CSRF_TOKEN, inventory_id: currentRestockId, quantity: qty })
        });
        var d = await r.json();
        if (d.success) { showToast(d.message, 'success'); closeRestockModal(); setTimeout(function() { location.reload(); }, 800); }
        else showToast(d.message, 'error');
    } catch(e) { showToast('Network error', 'error'); }
}
</script>
