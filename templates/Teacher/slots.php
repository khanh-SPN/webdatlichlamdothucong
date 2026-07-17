<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Teacher $instructor
 * @var iterable<\App\Model\Entity\TeacherAvailabilitySlot> $slots
 * @var iterable<\App\Model\Entity\Workshop> $workshops
 * @var array $stats
 * @var string|null $status
 * @var string|null $fromDate
 * @var string|null $toDate
 * @var string|null $workshopId
 */

$statusColors = [
    'available' => 'bg-green-100 text-green-800 border-green-200',
    'reserved' => 'bg-blue-100 text-blue-800 border-blue-200',
    'blocked' => 'bg-gray-100 text-gray-800 border-gray-200',
    'expired' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
    'cancelled' => 'bg-red-100 text-red-800 border-red-200',
];

$statusLabels = [
    'available' => 'Có sẵn',
    'reserved' => 'Đã đặt',
    'blocked' => 'Đã chặn',
    'expired' => 'Hết hạn',
    'cancelled' => 'Đã hủy',
];
?>

<div class="min-h-[60vh] bg-gradient-to-b from-neutral-50 via-studio-ivory/40 to-studio-mist/25 pb-12 pt-4 md:pt-6">
    <div class="mx-auto max-w-screen-2xl px-3 lg:px-4">
        
        <!-- Header -->
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.15em] text-primary-700/80">Quản lý Slot</p>
                <h1 class="mt-2 text-2xl font-serif font-semibold tracking-tight text-neutral-900">
                    Quản lý Slot của bạn
                </h1>
                <p class="mt-1 text-neutral-600">Tạo, chỉnh sửa và quản lý các buổi hội thảo của bạn</p>
            </div>
            <?= $this->Html->link('Tạo Slot Mới', ['action' => 'createSlot'], [
                'class' => 'inline-flex items-center gap-2 rounded-xl bg-primary-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-primary-600/20 transition-all hover:bg-primary-700 hover:shadow-xl',
            ]) ?>
        </div>

        <!-- Statistics Cards -->
        <div class="mb-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-2xl border border-neutral-200/70 bg-white/90 p-4 shadow-lg shadow-neutral-900/5 backdrop-blur-xl">
                <p class="text-xs font-semibold uppercase tracking-[0.1em] text-neutral-500">Total Slots</p>
                <p class="mt-2 text-2xl font-bold tabular-nums text-primary-600"><?= $stats['total_slots'] ?></p>
            </div>
            <div class="rounded-2xl border border-neutral-200/70 bg-white/90 p-4 shadow-lg shadow-neutral-900/5 backdrop-blur-xl">
                <p class="text-xs font-semibold uppercase tracking-[0.1em] text-neutral-500">Available</p>
                <p class="mt-2 text-2xl font-bold tabular-nums text-green-600"><?= $stats['available'] ?></p>
            </div>
            <div class="rounded-2xl border border-neutral-200/70 bg-white/90 p-4 shadow-lg shadow-neutral-900/5 backdrop-blur-xl">
                <p class="text-xs font-semibold uppercase tracking-[0.1em] text-neutral-500">Reserved</p>
                <p class="mt-2 text-2xl font-bold tabular-nums text-blue-600"><?= $stats['reserved'] ?></p>
            </div>
            <div class="rounded-2xl border border-neutral-200/70 bg-white/90 p-4 shadow-lg shadow-neutral-900/5 backdrop-blur-xl">
                <p class="text-xs font-semibold uppercase tracking-[0.1em] text-neutral-500">Fill Rate</p>
                <p class="mt-2 text-2xl font-bold tabular-nums text-primary-600"><?= $stats['average_fill_rate'] ?>%</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="mb-6 rounded-2xl border border-neutral-200/70 bg-white/90 p-4 shadow-lg shadow-neutral-900/5 backdrop-blur-xl">
            <?= $this->Form->create(null, ['type' => 'get', 'class' => 'flex flex-wrap items-end gap-3']) ?>
                <div class="flex-1 min-w-[140px]">
                    <label class="block text-xs font-semibold text-neutral-600 mb-1">Status</label>
                    <?= $this->Form->select('status', [
                        '' => 'All Statuses',
                        'available' => 'Có sẵn',
                        'reserved' => 'Đã đặt',
                        'blocked' => 'Đã chặn',
                        'expired' => 'Hết hạn',
                        'cancelled' => 'Đã hủy',
                    ], [
                        'value' => $status,
                        'class' => 'w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20',
                    ]) ?>
                </div>
                <div class="flex-1 min-w-[140px]">
                    <label class="block text-xs font-semibold text-neutral-600 mb-1">Workshop</label>
                    <?= $this->Form->select('workshop_id', 
                        array_merge(['' => 'All Workshops'], collection($workshops)->combine('id', 'workshop_name')->toArray()),
                        [
                            'value' => $workshopId,
                            'class' => 'w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20',
                        ]
                    ) ?>
                </div>
                <div class="flex-1 min-w-[140px]">
                    <label class="block text-xs font-semibold text-neutral-600 mb-1">From Date</label>
                    <?= $this->Form->date('from_date', [
                        'value' => $fromDate,
                        'class' => 'w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20',
                    ]) ?>
                </div>
                <div class="flex-1 min-w-[140px]">
                    <label class="block text-xs font-semibold text-neutral-600 mb-1">To Date</label>
                    <?= $this->Form->date('to_date', [
                        'value' => $toDate,
                        'class' => 'w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20',
                    ]) ?>
                </div>
                <div class="flex gap-2">
                    <?= $this->Form->button('Filter', [
                        'class' => 'rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700',
                    ]) ?>
                    <?= $this->Html->link('Reset', ['action' => 'slots'], [
                        'class' => 'rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm font-semibold text-neutral-700 hover:bg-neutral-50',
                    ]) ?>
                </div>
            <?= $this->Form->end() ?>
        </div>

        <!-- Slots Table -->
        <div class="rounded-2xl border border-neutral-200/70 bg-white/90 shadow-lg shadow-neutral-900/5 backdrop-blur-xl overflow-hidden">
            <?php if ($slots->isEmpty()): ?>
                <div class="p-8 text-center">
                    <p class="text-neutral-500">No slots found. Create your first slot to get started.</p>
                    <?= $this->Html->link('Create Slot', ['action' => 'createSlot'], [
                        'class' => 'mt-4 inline-flex items-center gap-2 rounded-xl bg-primary-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-primary-700',
                    ]) ?>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-neutral-100 text-left text-sm">
                        <thead class="bg-neutral-50/90 text-xs font-semibold uppercase tracking-[0.02em] text-neutral-500">
                            <tr>
                                <th class="px-4 py-4">Date & Time</th>
                                <th class="px-4 py-4">Class</th>
                                <th class="px-4 py-4">Location</th>
                                <th class="px-4 py-4">Status</th>
                                <th class="px-4 py-4">Capacity</th>
                                <th class="px-4 py-4">Booked</th>
                                <th class="px-4 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-100">
                            <?php foreach ($slots as $slot): ?>
                                <?php
                                    $statusClass = $statusColors[$slot->status] ?? 'bg-neutral-100 text-neutral-800';
                                    $statusLabel = $statusLabels[$slot->status] ?? ucfirst($slot->status);
                                    $fillPercent = $slot->capacity > 0 ? round(($slot->seats_booked / $slot->capacity) * 100) : 0;
                                ?>
                                <tr class="transition-colors hover:bg-primary-50/40">
                                    <td class="px-4 py-4">
                                        <div class="font-medium text-neutral-900">
                                            <?= $slot->session_date->format('M j, Y') ?>
                                        </div>
                                        <div class="text-sm text-neutral-600">
                                            <?= $slot->start_time ? $slot->start_time->format('g:i A') : 'N/A' ?> - <?= $slot->end_time ? $slot->end_time->format('g:i A') : '' ?>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="font-medium text-neutral-900">
                                            <?= h($slot->workshop->workshop_name ?? '—') ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-neutral-600">
                                        <?= h($slot->location ?? '—') ?>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold <?= $statusClass ?>">
                                            <?= $statusLabel ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="font-medium text-neutral-900"><?= $slot->capacity ?></span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-2">
                                            <span class="font-medium text-neutral-900"><?= $slot->seats_booked ?></span>
                                            <div class="w-16 h-1.5 rounded-full bg-neutral-200 overflow-hidden">
                                                <div class="h-full rounded-full <?= $fillPercent >= 90 ? 'bg-red-500' : ($fillPercent >= 70 ? 'bg-yellow-500' : 'bg-green-500') ?>" style="width: <?= $fillPercent ?>%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <?php
                                        $hasBookings = $slot->seats_booked > 0;
                                        $isCancelled = $slot->status === 'cancelled';
                                        $canEdit = !$isCancelled && !$hasBookings;
                                        $canCancel = !$isCancelled && !$hasBookings;
                                    ?>
                                    <td class="px-4 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <?php if ($canEdit): ?>
                                                <?= $this->Html->link('Edit', ['action' => 'editSlot', $slot->id], [
                                                    'class' => 'text-sm font-semibold text-primary-600 hover:text-primary-700',
                                                ]) ?>
                                            <?php elseif ($hasBookings && !$isCancelled): ?>
                                                <span class="text-xs text-neutral-400" title="Cannot edit: has bookings">Edit</span>
                                            <?php endif; ?>

                                            <?php if ($canCancel): ?>
                                                <?= $this->Form->postLink('Cancel', ['action' => 'cancelSlot', $slot->id], [
                                                    'confirm' => 'Are you sure you want to cancel this slot?',
                                                    'class' => 'text-sm font-semibold text-red-600 hover:text-red-700',
                                                ]) ?>
                                            <?php elseif ($hasBookings && !$isCancelled): ?>
                                                <span class="text-xs text-neutral-400" title="Cannot cancel: has bookings">Cancel</span>
                                            <?php endif; ?>

                                            <?php if ($hasBookings): ?>
                                                <?= $this->Html->link('Attendance', ['action' => 'attendance', $slot->id], [
                                                    'class' => 'text-sm font-semibold text-green-600 hover:text-green-700',
                                                ]) ?>
                                            <?php endif; ?>
                                        </div>
                                        <?php if ($hasBookings): ?>
                                            <p class="mt-1 text-xs text-neutral-400">Has bookings</p>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

