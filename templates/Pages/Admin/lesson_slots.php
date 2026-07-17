<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Workshop $workshop
 * @var array<string, \App\Model\Entity\TeacherAvailabilitySlot> $slotsByDate
 * @var array<int, array{start_time: string, end_time: string}> $workDayMap
 * @var bool $hasWorkDays
 * @var iterable<\App\Model\Entity\TeacherAvailability> $workDays
 */
$this->assign('title', 'Quản lý Slot: ' . h($workshop->workshop_name));

$dayNames = ['Chủ Nhật', 'Thứ Hai', 'Thứ Ba', 'Thứ Tư', 'Thứ Năm', 'Thứ Sáu', 'Thứ Bảy'];
$shortDayNames = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];

// Generate calendar for next 3 months
$currentMonth = new DateTime();
$calendars = [];
for ($m = 0; $m < 3; $m++) {
    $month = clone $currentMonth;
    $month->modify("+{$m} months");
    $calendars[] = $month;
}
?>

<div class="py-5 px-3 lg:px-4 max-w-screen-2xl mx-auto">
    <!-- Header -->
    <div class="mb-4">
        <div class="flex items-center gap-2 text-sm text-neutral-500 mb-2">
            <?= $this->Html->link('Hội thảo', ['action' => 'Hội thảo'], ['class' => 'hover:text-primary-600']) ?>
            <span>/</span>
            <span>Quản lý Slot</span>
        </div>
        <h1 class="text-xl md:text-lg font-serif font-bold text-neutral-900 mb-2">
            <?= h($workshop->workshop_name) ?>
        </h1>
        <p class="text-lg text-neutral-600">
            Giáo viên: <span class="font-semibold text-neutral-900"><?= h($workshop->teacher->name ?? 'Chưa phân công') ?></span>
            <?php if (!empty($workshop->capacity)): ?>
                · Sức chứa mặc định: <span class="font-semibold text-neutral-900"><?= (int)$workshop->capacity ?> chỗ</span>
            <?php endif; ?>
        </p>
    </div>

    <!-- Warning if teacher has no work days -->
    <?php if (!$hasWorkDays): ?>
        <div class="mb-4 rounded-2xl border border-amber-200 bg-amber-50 p-3">
            <div class="flex items-start gap-4">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-amber-900">Teacher Work Days Not Configured</h3>
                    <p class="mt-1 text-amber-800">
                        This teacher hasn't set their weekly availability yet. 
                        Slots can only be created on days when the teacher is available.
                    </p>
                    <p class="mt-3 text-sm text-amber-700">
                        Please ask the teacher to set their availability in their portal, 
                        or configure it manually in the Teacher management section.
                    </p>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Show teacher work days -->
        <div class="mb-4 rounded-2xl border border-primary-100 bg-primary-50 p-3">
            <h3 class="font-semibold text-primary-900 mb-3">Teacher Work Schedule</h3>
            <div class="flex flex-wrap gap-2">
                <?php foreach ($workDays as $wd): ?>
                    <span class="inline-flex items-center gap-1 rounded-full bg-white px-3 py-1 text-sm font-medium text-primary-800 ring-1 ring-primary-200">
                        <?= $dayNames[$wd->day_of_week] ?>
                        <span class="text-primary-500"><?= h($wd->start_time) ?>-<?= h($wd->end_time) ?></span>
                    </span>
                <?php endforeach; ?>
            </div>
            <p class="mt-3 text-sm text-primary-700">
                Slots can only be created on these days, within the teacher's work hours.
            </p>
        </div>
    <?php endif; ?>

    <!-- Calendar Form -->
    <?= $this->Form->create(null, [
        'url' => ['action' => 'saveHội thảolots', $workshop->id],
        'class' => 'space-y-3',
        'id' => 'slots-form'
    ]) ?>

    <div class="grid gap-3 lg:grid-cols-3">
        <?php foreach ($calendars as $month): 
            $year = (int)$month->format('Y');
            $mon = (int)$month->format('n');
            $firstDay = new DateTime("{$year}-{$mon}-01");
            $daysInMonth = (int)$month->format('t');
            $startDayOfWeek = (int)$firstDay->format('w'); // 0=Sun
        ?>
            <div class="rounded-2xl border border-neutral-200 bg-white overflow-hidden">
                <div class="bg-neutral-50 px-4 py-3 border-b border-neutral-200">
                    <h3 class="font-semibold text-neutral-900">
                        <?= $month->format('F Y') ?>
                    </h3>
                </div>
                
                <!-- Day headers -->
                <div class="grid grid-cols-7 text-center text-xs font-medium text-neutral-500 py-2 border-b border-neutral-100">
                    <?php foreach ($shortDayNames as $name): ?>
                        <div><?= $name ?></div>
                    <?php endforeach; ?>
                </div>

                <!-- Calendar days -->
                <div class="grid grid-cols-7">
                    <?php 
                    // Empty cells before start of month
                    for ($i = 0; $i < $startDayOfWeek; $i++): 
                        echo '<div class="aspect-square border-b border-r border-neutral-100 bg-neutral-50/50"></div>';
                    endfor;

                    // Days of month
                    for ($day = 1; $day <= $daysInMonth; $day++):
                        $dateStr = sprintf('%04d-%02d-%02d', $year, $mon, $day);
                        $dayOfWeek = ($startDayOfWeek + $day - 1) % 7;
                        $isWorkDay = isset($workDayMap[$dayOfWeek]);
                        $existingSlot = $slotsByDate[$dateStr] ?? null;
                        $hasSlot = $existingSlot !== null;
                        
                        $cellClass = 'aspect-square border-b border-r border-neutral-100 p-2 relative ';
                        if (!$isWorkDay) {
                            $cellClass .= 'bg-neutral-50/30 text-neutral-400';
                        } elseif ($hasSlot) {
                            $cellClass .= 'bg-primary-50/50 hover:bg-primary-50';
                        } else {
                            $cellClass .= 'hover:bg-neutral-50';
                        }
                    ?>
                        <div class="<?= $cellClass ?>">
                            <div class="text-sm font-medium <?= $isWorkDay ? 'text-neutral-900' : 'text-neutral-400' ?>">
                                <?= $day ?>
                            </div>
                            
                            <?php if ($isWorkDay): ?>
                                <label class="absolute inset-0 cursor-pointer" title="Click to add slot">
                                    <input type="checkbox" 
                                           name="slots[<?= $dateStr ?>][has_slot]" 
                                           value="1"
                                           class="day-checkbox sr-only"
                                           data-date="<?= $dateStr ?>"
                                           data-day="<?= $dayOfWeek ?>"
                                           <?= $hasSlot ? 'checked' : '' ?>
                                           <?= !$hasWorkDays ? 'disabled' : '' ?>>
                                </label>
                                
                                <!-- Slot indicator dot -->
                                <div class="slot-indicator absolute bottom-1 left-1/2 -translate-x-1/2 w-2 h-2 rounded-full <?= $hasSlot ? 'bg-primary-500' : 'bg-transparent' ?>"></div>
                                
                                <!-- Existing slot info -->
                                <?php if ($hasSlot): ?>
                                    <div class="slot-info absolute top-1 right-1 text-xs text-primary-700 font-medium">
                                        <?= $existingSlot->seats_booked ?>/<?= $existingSlot->capacity ?? $workshop->capacity ?? '-' ?>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="absolute inset-0 flex flex-col items-center justify-center" title="Teacher does not work on <?= $dayNames[$dayOfWeek] ?>">
                                    <span class="text-xs text-neutral-300 font-medium">Off</span>
                                    <span class="text-xs text-neutral-200">no work day</span>
                                </div>
                                <!-- Block click on non-work days -->
                                <div class="absolute inset-0 cursor-not-allowed bg-neutral-100/50"></div>
                            <?php endif; ?>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Slot Details Panel (shown when days are selected) -->
    <div id="slot-details-panel" class="rounded-2xl border border-neutral-200 bg-white p-3 <?= $hasWorkDays ? '' : 'opacity-50 pointer-events-none' ?>">
        <h3 class="font-semibold text-neutral-900 mb-4">Configure Selected Slots</h3>
        <p class="text-sm text-neutral-500 mb-4">
            Click on calendar dates above to select days. Then configure time and capacity for each selected day.
        </p>
        
        <div id="selected-slots-container" class="space-y-3 max-h-96 overflow-y-auto">
            <!-- Populated by JavaScript -->
            <p class="text-neutral-400 italic text-sm">No dates selected. Click on calendar dates above.</p>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex items-center gap-3">
        <?= $this->Form->button('Save Slots', [
            'class' => 'inline-flex items-center rounded-full bg-primary-600 px-3 py-3 text-sm font-semibold text-white hover:bg-primary-700 disabled:opacity-50 disabled:cursor-not-allowed',
            'disabled' => !$hasWorkDays
        ]) ?>
        
        <?= $this->Html->link('Back to Hội thảo', ['action' => 'Hội thảo'], [
            'class' => 'inline-flex items-center rounded-full border border-neutral-300 bg-white px-3 py-3 text-sm font-semibold text-neutral-700 hover:bg-neutral-50'
        ]) ?>
    </div>

    <?= $this->Form->end() ?>

    <!-- Existing Slots List -->
    <?php if (!empty($slotsByDate)): ?>
        <div class="mt-5 rounded-2xl border border-neutral-200 bg-white overflow-hidden">
            <div class="bg-neutral-50 px-3 py-4 border-b border-neutral-200">
                <h3 class="font-semibold text-neutral-900">Existing Slots</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-neutral-50 text-neutral-500">
                        <tr>
                            <th class="px-3 py-3">Date</th>
                            <th class="px-3 py-3">Time</th>
                            <th class="px-3 py-3">Capacity</th>
                            <th class="px-3 py-3">Booked</th>
                            <th class="px-3 py-3">Available</th>
                            <th class="px-3 py-3">Status</th>
                            <th class="px-3 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        <?php foreach ($slotsByDate as $date => $slot): ?>
                            <?php
                            $slotCapacity = $slot->capacity ?? null;
                            $workshopCapacity = $workshop->capacity ?? null;
                            $cap = $slotCapacity ?? $workshopCapacity ?? 0;
                            $booked = (int)($slot->seats_booked ?? 0);
                            $avail = max(0, (int)$cap - $booked);
                            $isActive = !isset($slot->is_active) || $slot->is_active === true || $slot->is_active === 1 || $slot->is_active === '1';
                            ?>
                            <tr>
                                <td class="px-3 py-3 font-medium"><?= h($date) ?></td>
                                <td class="px-3 py-3"><?= h($slot->time_label ?? 'N/A') ?></td>
                                <td class="px-3 py-3">
                                    <?= $cap > 0 ? (int)$cap : 'Not configured' ?>
                                    <?php if ($slotCapacity === null && $workshopCapacity !== null): ?>
                                        <span class="text-xs text-neutral-400">(workshop default)</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-3 py-3"><?= $booked ?></td>
                                <td class="px-3 py-3">
                                    <span class="<?= $avail > 0 ? 'text-green-600' : 'text-red-600' ?> font-medium">
                                        <?= $avail ?>
                                    </span>
                                </td>
                                <td class="px-3 py-3">
                                    <?php if ($isActive): ?>
                                        <span class="inline-flex rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700">Active</span>
                                    <?php else: ?>
                                        <span class="inline-flex rounded-full bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-3 py-3 text-right">
                                    <?php if ($booked === 0): ?>
                                        <?= $this->Form->postLink('Delete', ['action' => 'deleteHội thảolot', $slot->id], [
                                            'confirm' => 'Delete this slot?',
                                            'class' => 'text-sm font-medium text-red-600 hover:text-red-800'
                                        ]) ?>
                                    <?php else: ?>
                                        <span class="text-xs text-neutral-400" title="Has bookings">Locked</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
(function() {
    const workDayMap = <?= json_encode($workDayMap) ?>;
    const existingSlots = <?= json_encode($slotsByDate) ?>;
    const dayNames = <?= json_encode($dayNames) ?>;
    
    const checkboxes = document.querySelectorAll('.day-checkbox');
    const container = document.getElementById('selected-slots-container');

    function normalizeTime(value, fallback = '09:00') {
        const raw = String(value || '').substring(0, 5);
        if (!/^\d{2}:\d{2}$/.test(raw)) {
            return fallback;
        }

        let [hour, minute] = raw.split(':').map(Number);
        minute = minute < 15 ? 0 : (minute < 45 ? 30 : 0);
        if (Number(raw.substring(3, 5)) >= 45) {
            hour = (hour + 1) % 24;
        }

        return String(hour).padStart(2, '0') + ':' + String(minute).padStart(2, '0');
    }

    function timeLabel(value) {
        const [hour, minute] = value.split(':').map(Number);
        const suffix = hour >= 12 ? 'PM' : 'AM';
        const displayHour = hour % 12 || 12;

        return `${displayHour}:${String(minute).padStart(2, '0')} ${suffix}`;
    }

    function buildTimeOptions(start, end, selected) {
        const options = [];
        const startMinutes = toMinutes(start);
        const endMinutes = toMinutes(end);
        const selectedValue = normalizeTime(selected, start);

        for (let minutes = startMinutes; minutes <= endMinutes; minutes += 30) {
            const value = fromMinutes(minutes);
            options.push(`<option value="${value}" ${value === selectedValue ? 'selected' : ''}>${timeLabel(value)}</option>`);
        }

        return options.join('');
    }

    function toMinutes(value) {
        const [hour, minute] = normalizeTime(value).split(':').map(Number);

        return hour * 60 + minute;
    }

    function fromMinutes(minutes) {
        const hour = Math.floor(minutes / 60) % 24;
        const minute = minutes % 60;

        return String(hour).padStart(2, '0') + ':' + String(minute).padStart(2, '0');
    }

    function collectCurrentSlotValues() {
        const values = {};
        container.querySelectorAll('[data-slot-date]').forEach(slotEl => {
            const date = slotEl.dataset.slotDate;
            if (!date) {
                return;
            }

            values[date] = {
                start_time: slotEl.querySelector(`[name="slots[${date}][start_time]"]`)?.value || '',
                end_time: slotEl.querySelector(`[name="slots[${date}][end_time]"]`)?.value || '',
                capacity: slotEl.querySelector(`[name="slots[${date}][capacity]"]`)?.value || '',
            };
        });

        return values;
    }
    
    function updateSlotDetails() {
        const currentValues = collectCurrentSlotValues();
        const checked = Array.from(checkboxes).filter(cb => cb.checked);
        
        if (checked.length === 0) {
            container.innerHTML = '<p class="text-neutral-400 italic text-sm">No dates selected. Click on calendar dates above.</p>';
            return;
        }
        
        // Filter out non-work days (safety check)
        const validChecked = checked.filter(cb => {
            const dayOfWeek = parseInt(cb.dataset.day);
            if (!workDayMap[dayOfWeek]) {
                // Auto-uncheck if not a work day
                cb.checked = false;
                const cell = cb.closest('.aspect-square');
                const indicator = cell.querySelector('.slot-indicator');
                indicator.classList.remove('bg-primary-500');
                indicator.classList.add('bg-transparent');
                return false;
            }
            return true;
        });
        
        if (validChecked.length === 0) {
            container.innerHTML = '<p class="text-amber-600 italic text-sm">Selected day(s) are not in teacher\'s work schedule. Please select work days only.</p>';
            return;
        }
        
        container.innerHTML = validChecked.map(cb => {
            const date = cb.dataset.date;
            const dayOfWeek = parseInt(cb.dataset.day);
            const workHours = workDayMap[dayOfWeek] || { start_time: '09:00', end_time: '17:00' };
            const existing = existingSlots[date] || {};
            const current = currentValues[date] || {};
            
            // Fix corrupted work hours (end before start)
            let workStart = normalizeTime(workHours.start_time, '09:00');
            let workEnd = normalizeTime(workHours.end_time, '17:00');
            if (workEnd < workStart) {
                let hour = parseInt(workEnd.substring(0, 2));
                if (hour < 12) hour += 12;
                workEnd = ('0' + hour).slice(-2) + workEnd.substring(2);
            }
            const existingStart = current.start_time || (existing.time_label ? existing.time_label.split(' to ')[0] : workStart);
            const existingEnd = current.end_time || (existing.time_label ? existing.time_label.split(' to ')[1] : workEnd);
            const capacity = current.capacity !== undefined && current.capacity !== ''
                ? current.capacity
                : (existing.capacity || '');
            
            return `
                <div class="rounded-xl border border-neutral-200 bg-neutral-50 p-4" data-slot-date="${date}">
                    <div class="flex items-center justify-between mb-3">
                        <div class="font-semibold text-neutral-900">
                            ${date} (${dayNames[dayOfWeek]})
                        </div>
                        <span class="text-xs text-neutral-500">Work hours: ${workStart}-${workEnd}</span>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-3">
                        <div>
                            <label class="block text-xs font-medium text-neutral-600 mb-1">Start Time</label>
                            <select name="slots[${date}][start_time]" 
                                    class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm"
                                    required>
                                ${buildTimeOptions(workStart, workEnd, existingStart)}
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-neutral-600 mb-1">End Time</label>
                            <select name="slots[${date}][end_time]" 
                                    class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm"
                                    required>
                                ${buildTimeOptions(workStart, workEnd, existingEnd)}
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-neutral-600 mb-1">Capacity (optional)</label>
                            <input type="number" name="slots[${date}][capacity]" 
                                   value="${capacity}" 
                                   placeholder="Default"
                                   min="1" max="100"
                                   class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm">
                            <p class="text-xs text-neutral-400 mt-1">Leave empty to use workshop default</p>
                        </div>
                    </div>
                    <input type="hidden" name="slots[${date}][has_slot]" value="1">
                </div>
            `;
        }).join('');
    }
    
    checkboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            const dayOfWeek = parseInt(this.dataset.day);
            
            // Realtime check: prevent selecting non-work days
            if (this.checked && !workDayMap[dayOfWeek]) {
                this.checked = false;
                alert('Teacher does not work on ' + dayNames[dayOfWeek] + 's. Cannot create slot for this day.');
                return;
            }
            
            // Update visual indicator
            const cell = this.closest('.aspect-square');
            const indicator = cell.querySelector('.slot-indicator');
            if (this.checked) {
                indicator.classList.remove('bg-transparent');
                indicator.classList.add('bg-primary-500');
            } else {
                indicator.classList.remove('bg-primary-500');
                indicator.classList.add('bg-transparent');
            }
            updateSlotDetails();
        });
    });
    
    // Initialize on load
    updateSlotDetails();
})();
</script>

<style>
.day-checkbox:checked + .slot-indicator {
    background-color: #6366f1;
}
.aspect-square {
    position: relative;
}
</style>


