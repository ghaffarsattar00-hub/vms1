<!-- Parent Book Appointment - Enterprise -->
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8 fade-in">
        <p class="text-sm text-slate-500">Schedule a vaccination appointment for your child at a hospital near you</p>
    </div>

    <form method="POST" action="<?php echo BASE_URL; ?>parent/book/create" class="space-y-6" id="bookForm">
        <?php echo $csrfInput; ?>

        <!-- Step 1: Select Child -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 fade-in" style="animation-delay:0.05s">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-8 h-8 bg-teal-50 rounded-lg flex items-center justify-center"><span class="text-sm font-bold text-teal-600">1</span></div>
                <h3 class="text-sm font-bold text-slate-800">Select Child</h3>
            </div>
            <?php if (empty($children)): ?>
                <div class="text-center py-8">
                    <p class="text-slate-400 text-sm mb-3">No children registered</p>
                    <a href="<?php echo BASE_URL; ?>parent/dashboard" class="text-sm text-teal-600 font-semibold hover:underline">Add a child first</a>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <?php foreach ($children as $child): ?>
                        <label class="child-option relative flex items-center gap-4 p-4 rounded-xl border-2 border-slate-200 hover:border-teal-300 hover:bg-teal-50/30 cursor-pointer transition-all duration-200">
                            <input type="radio" name="child_id" value="<?php echo $child['child_id']; ?>" required class="sr-only peer">
                            <div class="w-10 h-10 bg-gradient-to-br from-amber-400 to-orange-500 rounded-xl flex items-center justify-center text-white text-sm font-bold shadow-sm flex-shrink-0 peer-checked:ring-2 peer-checked:ring-teal-500 peer-checked:ring-offset-2"><?php echo strtoupper(substr($child['first_name'],0,1)); ?></div>
                            <div>
                                <p class="font-semibold text-slate-800 text-sm peer-checked:text-teal-700"><?php echo htmlspecialchars($child['first_name'] . ' ' . $child['last_name']); ?></p>
                                <p class="text-[11px] text-slate-400">DOB: <?php echo date('d M Y', strtotime($child['date_of_birth'])); ?></p>
                            </div>
                            <div class="absolute top-3 right-3 w-5 h-5 rounded-full border-2 border-slate-300 peer-checked:border-teal-500 peer-checked:bg-teal-500 transition flex items-center justify-center">
                                <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Step 2: Select Vaccine + Dose -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 fade-in" style="animation-delay:0.1s">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-8 h-8 bg-teal-50 rounded-lg flex items-center justify-center"><span class="text-sm font-bold text-teal-600">2</span></div>
                <h3 class="text-sm font-bold text-slate-800">Select Vaccine & Dose</h3>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Vaccine *</label>
                    <select name="vaccine_id" id="vaccineSelect" required onchange="filterDoses()" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-0 focus:border-teal-500 outline-none transition appearance-none">
                        <option value="">Select vaccine...</option>
                        <?php foreach ($vaccines as $v): ?>
                            <option value="<?php echo $v['vaccine_id']; ?>"><?php echo htmlspecialchars($v['name'] . ' (' . $v['targeted_disease'] . ')'); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Dose *</label>
                    <select name="dose_id" id="doseSelect" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-0 focus:border-teal-500 outline-none transition appearance-none">
                        <option value="">Select vaccine first...</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Step 3: Select Hospital + Date -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 fade-in" style="animation-delay:0.15s">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-8 h-8 bg-teal-50 rounded-lg flex items-center justify-center"><span class="text-sm font-bold text-teal-600">3</span></div>
                <h3 class="text-sm font-bold text-slate-800">Hospital & Date</h3>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Hospital *</label>
                    <select name="hospital_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-0 focus:border-teal-500 outline-none transition appearance-none">
                        <option value="">Select hospital...</option>
                        <?php foreach ($hospitals as $h): ?>
                            <option value="<?php echo $h['hospital_id']; ?>"><?php echo htmlspecialchars($h['name'] . ' — ' . ($h['city'] ?? '')); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Scheduled Date *</label>
                    <input type="date" name="scheduled_date" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-0 focus:border-teal-500 outline-none transition">
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="flex justify-center sm:justify-end fade-in" style="animation-delay:0.2s">
            <button type="submit" class="inline-flex items-center justify-center gap-2 w-full sm:w-auto px-8 py-3.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold rounded-xl shadow-lg shadow-slate-900/20 transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.99]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Book Appointment
            </button>
        </div>
    </form>
</div>

<script>
var allDoses = <?php echo json_encode($doses); ?>;
function filterDoses() {
    var vid = document.getElementById('vaccineSelect').value;
    var sel = document.getElementById('doseSelect');
    sel.innerHTML = '<option value="">Select dose...</option>';
    allDoses.filter(function(d) { return d.vaccine_id == vid; }).forEach(function(d) {
        var opt = document.createElement('option');
        opt.value = d.dose_id;
        opt.textContent = 'Dose ' + d.dose_number + (d.description ? ' — ' + d.description : '');
        sel.appendChild(opt);
    });
}
</script>
