<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Teacher $instructor
 * @var \App\Model\Entity\TeacherAvailabilitySlot $slot
 * @var iterable<\App\Model\Entity\Workshop> $workshops
 */

// Build time options with 10-minute increments
$timeOptions = [];
for ($h = 0; $h < 24; $h++) {
    for ($m = 0; $m < 60; $m += 10) {
        $timeValue = sprintf('%02d:%02d', $h, $m);
        $ampm = $h < 12 ? 'AM' : 'PM';
        $displayH = $h % 12;
        $displayH = $displayH === 0 ? 12 : $displayH;
        $displayTime = sprintf('%d:%02d %s', $displayH, $m, $ampm);
        $timeOptions[$timeValue] = $displayTime;
    }
}

$hasBookings = !empty(array_filter($slot->bookings ?? [], function($b) {
    return $b->status === 'confirmed';
}));
?>

<div class="min-h-[60vh] bg-gradient-to-b from-neutral-50 via-studio-ivory/40 to-studio-mist/25 pb-12 pt-4 md:pt-6">
    <div class="mx-auto max-w-2xl px-3 lg:px-4">
        
        <!-- Header -->
        <div class="mb-6">
            <p class="text-xs font-semibold uppercase tracking-[0.15em] text-primary-700/80">Chỉnh sửa Slot</p>
            <h1 class="mt-2 text-2xl font-serif font-semibold tracking-tight text-neutral-900">
                Chỉnh sửa Slot
            </h1>
            <p class="mt-1 text-neutral-600">
                <?= $slot->session_date->format('F j, Y') ?> • 
                <?= $slot->start_time ? $slot->start_time->format('g:i A') : 'N/A' ?> - <?= $slot->end_time ? $slot->end_time->format('g:i A') : '' ?>
            </p>
        </div>

        <?php if ($hasBookings): ?>
            <div class="mb-6 rounded-xl bg-yellow-50 border border-yellow-200 p-4">
                <p class="text-sm text-yellow-800">
                    <strong>⚠️ Slot này có các đặt chỗ đã xác nhận.</strong><br>
                    Để bảo vệ học viên hiện có, bạn chỉ có thể chỉnh sửa ghi chú và địa điểm. 
                    Để thay đổi khác, vui lòng hủy slot này và tạo một slot mới.
                </p>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <div class="rounded-2xl border border-neutral-200/70 bg-white/90 p-6 shadow-lg shadow-neutral-900/5 backdrop-blur-xl">
            <?= $this->Form->create($slot, ['class' => 'space-y-5']) ?>
                
                <!-- Workshop Selection -->
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 mb-1">
                        Workshop <span class="text-red-500">*</span>
                    </label>
                    <?= $this->Form->select('workshop_id', 
                        collection($workshops)->combine('id', function($workshop) {
                            return $workshop->workshop_name . ' ($' . $workshop->price . ')';
                        })->toArray(),
                        [
                            'empty' => 'Select a workshop...',
                            'required' => true,
                            'disabled' => $hasBookings,
                            'class' => 'w-full rounded-lg border border-neutral-300 bg-white px-3 py-2.5 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20 ' . ($hasBookings ? 'bg-neutral-100' : ''),
                        ]
                    ) ?>
                    <?php if ($hasBookings): ?>
                        <?= $this->Form->hidden('workshop_id', ['value' => $slot->workshop_id]) ?>
                    <?php endif; ?>
                </div>

                <!-- Date -->
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 mb-1">
                        Date <span class="text-red-500">*</span>
                    </label>
                    <?= $this->Form->date('session_date', [
                        'required' => true,
                        'disabled' => $hasBookings,
                        'class' => 'w-full rounded-lg border border-neutral-300 bg-white px-3 py-2.5 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20 ' . ($hasBookings ? 'bg-neutral-100' : ''),
                    ]) ?>
                    <?php if ($hasBookings): ?>
                        <?= $this->Form->hidden('session_date', ['value' => $slot->session_date->format('Y-m-d')]) ?>
                    <?php endif; ?>
                </div>

                <!-- Time Range -->
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-semibold text-neutral-700 mb-1">
                            Start Time <span class="text-red-500">*</span>
                        </label>
                        <?= $this->Form->select('start_time', $timeOptions, [
                            'value' => $slot->start_time ? $slot->start_time->format('H:i') : '09:00',
                            'required' => true,
                            'disabled' => $hasBookings,
                            'class' => 'w-full rounded-lg border border-neutral-300 bg-white px-3 py-2.5 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20 ' . ($hasBookings ? 'bg-neutral-100' : ''),
                        ]) ?>
                        <?php if ($hasBookings): ?>
                            <?= $this->Form->hidden('start_time', ['value' => $slot->start_time ? $slot->start_time->format('H:i:s') : '09:00:00']) ?>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-neutral-700 mb-1">
                            End Time <span class="text-red-500">*</span>
                        </label>
                        <?= $this->Form->select('end_time', $timeOptions, [
                            'value' => $slot->end_time ? $slot->end_time->format('H:i') : '17:00',
                            'required' => true,
                            'disabled' => $hasBookings,
                            'class' => 'w-full rounded-lg border border-neutral-300 bg-white px-3 py-2.5 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20 ' . ($hasBookings ? 'bg-neutral-100' : ''),
                        ]) ?>
                        <?php if ($hasBookings): ?>
                            <?= $this->Form->hidden('end_time', ['value' => $slot->end_time ? $slot->end_time->format('H:i:s') : '17:00:00']) ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Capacity and Location -->
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-semibold text-neutral-700 mb-1">
                            Capacity <span class="text-red-500">*</span>
                        </label>
                        <?= $this->Form->number('capacity', [
                            'min' => $slot->seats_booked,
                            'max' => 100,
                            'required' => true,
                            'disabled' => $hasBookings,
                            'class' => 'w-full rounded-lg border border-neutral-300 bg-white px-3 py-2.5 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20 ' . ($hasBookings ? 'bg-neutral-100' : ''),
                        ]) ?>
                        <p class="mt-1 text-xs text-neutral-500">
                            <?= $slot->seats_booked ?> seats currently booked
                            <?php if ($hasBookings): ?>
                                <?= $this->Form->hidden('capacity', ['value' => $slot->capacity]) ?>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-neutral-700 mb-1">
                            Location
                        </label>
                        <?= $this->Form->text('location', [
                            'placeholder' => 'e.g., Room 101, Studio A',
                            'class' => 'w-full rounded-lg border border-neutral-300 bg-white px-3 py-2.5 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20',
                        ]) ?>
                    </div>
                </div>

                <!-- Notes -->
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 mb-1">
                        Notes
                    </label>
                    <?= $this->Form->textarea('notes', [
                        'rows' => 3,
                        'placeholder' => 'Any special instructions or notes...',
                        'class' => 'w-full rounded-lg border border-neutral-300 bg-white px-3 py-2.5 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20',
                    ]) ?>
                </div>

                <!-- Current Bookings Info -->
                <?php if (!empty($slot->bookings)): ?>
                    <div class="rounded-xl bg-neutral-50 border border-neutral-200 p-4">
                        <h3 class="font-semibold text-neutral-900 mb-3">Current Bookings (<?= count($slot->bookings) ?>)</h3>
                        <ul class="space-y-1 text-sm">
                            <?php foreach ($slot->bookings as $booking): ?>
                                <li class="flex justify-between">
                                    <span class="text-neutral-700"><?= h($booking->user->email ?? 'Unknown') ?></span>
                                    <span class="text-neutral-500"><?= ucfirst($booking->status) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-neutral-200">
                    <?= $this->Html->link('Cancel', ['action' => 'slots'], [
                        'class' => 'rounded-lg border border-neutral-300 bg-white px-5 py-2.5 text-sm font-semibold text-neutral-700 hover:bg-neutral-50',
                    ]) ?>
                    <?= $this->Form->button('Save Changes', [
                        'class' => 'rounded-lg bg-primary-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-primary-600/20 hover:bg-primary-700',
                    ]) ?>
                </div>

            <?= $this->Form->end() ?>
        </div>

    </div>
</div>

