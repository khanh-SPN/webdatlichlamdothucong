<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Teacher $instructor
 * @var iterable<\App\Model\Entity\Workshop>|null $workshops
 * @var \App\Model\Entity\Workshop|null $workshopEntity
 * @var \Cake\I18n\Date|null $sessionDate
 * @var iterable<\App\Model\Entity\Booking>|null $sessionBookings
 * @var array<string, string>|null $statusOptions
 * @var \App\Model\Entity\TeacherAvailabilitySlot|null $slot
 * @var iterable<\App\Model\Entity\Booking>|null $bookings
 * @var array<int, \App\Model\Entity\AttendanceRecord>|null $existingAttendance
 */
$this->assign('title', isset($slot) ? 'Đánh dấu điểm danh' : 'Điểm danh');
?>

<?php if (isset($slot)): ?>
    <?php
    $statusOptions = [
        'present' => 'Có mặt',
        'absent' => 'Vắng mặt',
        'late' => 'Đến muộn',
        'excused' => 'Được phép',
    ];
    $statusColors = [
        'present' => 'border-green-200 bg-green-50 text-green-800',
        'absent' => 'border-red-200 bg-red-50 text-red-800',
        'late' => 'border-yellow-200 bg-yellow-50 text-yellow-800',
        'excused' => 'border-blue-200 bg-blue-50 text-blue-800',
    ];
    $bookingsList = isset($bookings) ? (is_array($bookings) ? $bookings : iterator_to_array($bookings)) : [];
    $existingAttendance = $existingAttendance ?? [];
    $lockedCount = count(array_filter($existingAttendance, static fn ($record) => (bool)($record->is_locked ?? false)));
    $allLocked = $lockedCount > 0 && $lockedCount >= count($bookingsList);

    $now = new \DateTimeImmutable();
    $slotDate = $slot->session_date?->format('Y-m-d') ?? date('Y-m-d');
    $slotStart = new \DateTimeImmutable($slotDate . ' ' . ($slot->start_time ? $slot->start_time->format('H:i:s') : '00:00:00'));
    $slotEnd = new \DateTimeImmutable($slotDate . ' ' . ($slot->end_time ? $slot->end_time->format('H:i:s') : '23:59:59'));
    $canMarkAttendance = $now >= $slotStart;
    $slotStatusLabel = $now < $slotStart
        ? 'Sắp tới'
        : ($now <= $slotEnd ? 'Đang diễn ra' : 'Đã kết thúc');
    $slotStatusClass = $now < $slotStart
        ? 'border-blue-200 bg-blue-50 text-blue-800'
        : ($now <= $slotEnd ? 'border-green-200 bg-green-50 text-green-800' : 'border-neutral-200 bg-neutral-50 text-neutral-700');
    ?>

    <div class="min-h-[60vh] bg-gradient-to-b from-neutral-50 via-studio-ivory/40 to-studio-mist/25 pb-12 pt-4 md:pt-6">
        <div class="mx-auto max-w-4xl px-3 lg:px-4">
            <div class="mb-6">
                <p class="text-xs font-semibold uppercase tracking-[0.15em] text-primary-700/80">Attendance</p>
                <h1 class="mt-2 font-serif text-2xl font-semibold tracking-tight text-neutral-900">Mark attendance</h1>
                <p class="mt-1 text-neutral-600">
                    <?= h($slot->workshop->workshop_name ?? 'Workshop') ?> -
                    <?= h($slot->session_date?->format('F j, Y') ?? '') ?> -
                    <?= h($slot->start_time ? $slot->start_time->format('g:i A') : 'N/A') ?>
                    to <?= h($slot->end_time ? $slot->end_time->format('g:i A') : 'N/A') ?>
                </p>
            </div>

            <div class="mb-6 rounded-xl border p-4 <?= h($slotStatusClass) ?>">
                <span class="font-semibold">Workshop status:</span>
                <span><?= h($slotStatusLabel) ?></span>
                <?php if (!$canMarkAttendance): ?>
                    <p class="mt-2 text-sm">Attendance marking will be available when the workshop starts.</p>
                <?php endif; ?>
            </div>

            <?php if ($lockedCount > 0): ?>
                <div class="mb-6 rounded-xl border border-yellow-200 bg-yellow-50 p-4 text-sm text-yellow-800">
                    <?= (int)$lockedCount ?> attendance record(s) are locked and cannot be edited.
                </div>
            <?php endif; ?>

            <?php if ($bookingsList === []): ?>
                <div class="rounded-2xl border border-neutral-200/70 bg-white/90 p-8 text-center shadow-lg shadow-neutral-900/5 backdrop-blur-xl">
                    <p class="text-neutral-500">No confirmed bookings for this slot.</p>
                    <?= $this->Html->link('Back to slots', ['action' => 'slots'], [
                        'class' => 'mt-4 inline-flex rounded-lg bg-primary-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-primary-700',
                    ]) ?>
                </div>
            <?php elseif (!$canMarkAttendance): ?>
                <div class="rounded-2xl border border-neutral-200/70 bg-white/90 p-8 text-center shadow-lg shadow-neutral-900/5 backdrop-blur-xl">
                    <p class="text-neutral-500">Attendance marking is not available yet.</p>
                    <?= $this->Html->link('Back to slots', ['action' => 'slots'], [
                        'class' => 'mt-4 inline-flex rounded-lg bg-primary-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-primary-700',
                    ]) ?>
                </div>
            <?php else: ?>
                <?= $this->Form->create(null, ['class' => 'space-y-6']) ?>
                <?php if ($allLocked): ?>
                    <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm font-semibold text-red-700">
                        All attendance records are locked. No further edits are allowed.
                    </div>
                <?php endif; ?>

                <div class="overflow-hidden rounded-2xl border border-neutral-200/70 bg-white/90 shadow-lg shadow-neutral-900/5 backdrop-blur-xl">
                    <table class="min-w-full divide-y divide-neutral-100 text-left text-sm">
                        <thead class="bg-neutral-50/90 text-xs font-semibold uppercase tracking-wide text-neutral-500">
                            <tr>
                                <th class="px-4 py-4">Student</th>
                                <th class="px-4 py-4">Status</th>
                                <th class="px-4 py-4">Notes</th>
                                <th class="px-4 py-4 text-center">Previous</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-100">
                            <?php foreach ($bookingsList as $booking): ?>
                                <?php
                                $studentId = (int)($booking->user->id ?? 0);
                                $existing = $existingAttendance[$studentId] ?? null;
                                $isLocked = (bool)($existing?->is_locked ?? false);
                                $currentStatus = (string)($existing?->status ?? 'present');
                                ?>
                                <tr class="transition-colors hover:bg-primary-50/40 <?= $isLocked ? 'bg-neutral-50' : '' ?>">
                                    <td class="px-4 py-4 font-medium text-neutral-900">
                                        <?= h($booking->user->email ?? '') ?>
                                        <?php if ($isLocked): ?>
                                            <span class="mt-1 block text-xs text-yellow-700">Locked</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-4">
                                        <?php if ($isLocked): ?>
                                            <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold <?= h($statusColors[$currentStatus] ?? 'border-neutral-200 bg-neutral-100 text-neutral-700') ?>">
                                                <?= h($statusOptions[$currentStatus] ?? ucfirst($currentStatus)) ?>
                                            </span>
                                            <?= $this->Form->hidden("attendance.{$studentId}.status", ['value' => $currentStatus]) ?>
                                        <?php else: ?>
                                            <?= $this->Form->select("attendance.{$studentId}.status", $statusOptions, [
                                                'value' => $currentStatus,
                                                'class' => 'rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20',
                                            ]) ?>
                                        <?php endif; ?>
                                        <?= $this->Form->hidden("attendance.{$studentId}.booking_id", ['value' => $booking->id]) ?>
                                    </td>
                                    <td class="px-4 py-4">
                                        <?php if ($isLocked): ?>
                                            <span class="text-sm text-neutral-500"><?= h($existing?->notes) ?: '-' ?></span>
                                        <?php else: ?>
                                            <?= $this->Form->text("attendance.{$studentId}.notes", [
                                                'value' => $existing?->notes,
                                                'placeholder' => 'Optional notes...',
                                                'class' => 'w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20',
                                            ]) ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-4 text-center text-xs text-neutral-500">
                                        <?= $existing?->marked_at ? h($existing->marked_at->format('M j, g:i A')) : 'Not marked' ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="flex flex-wrap items-center gap-3 rounded-xl border border-neutral-200 bg-neutral-50 p-4">
                    <span class="text-sm font-semibold text-neutral-700">Mark all as:</span>
                    <?php foreach ($statusOptions as $status => $label): ?>
                        <button type="button" class="bulk-mark rounded-lg border border-neutral-300 bg-white px-3 py-1.5 text-xs font-semibold text-neutral-700 hover:bg-neutral-50" data-status="<?= h($status) ?>">
                            <?= h($label) ?>
                        </button>
                    <?php endforeach; ?>
                </div>

                <div class="flex items-center justify-between gap-3 border-t border-neutral-200 pt-4">
                    <?= $this->Html->link('Back to slots', ['action' => 'slots'], [
                        'class' => 'rounded-lg border border-neutral-300 bg-white px-5 py-2.5 text-sm font-semibold text-neutral-700 hover:bg-neutral-50',
                    ]) ?>
                    <?php if (!$allLocked): ?>
                        <?= $this->Form->button('Save attendance', [
                            'class' => 'rounded-lg bg-primary-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-primary-600/20 hover:bg-primary-700',
                        ]) ?>
                    <?php endif; ?>
                </div>
                <?= $this->Form->end() ?>

                <?php if (!$allLocked && $lockedCount === 0 && $existingAttendance !== []): ?>
                    <?= $this->Form->postLink('Lock attendance', ['action' => 'lockAttendance', $slot->id], [
                        'confirm' => 'Once locked, attendance cannot be edited. Are you sure?',
                        'class' => 'mt-2 inline-flex rounded-lg border border-yellow-300 bg-yellow-50 px-5 py-2.5 text-sm font-semibold text-yellow-700 hover:bg-yellow-100',
                    ]) ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
    document.querySelectorAll('.bulk-mark').forEach((button) => {
        button.addEventListener('click', () => {
            document.querySelectorAll('select[name^="attendance"][name$="[status]"]').forEach((select) => {
                select.value = button.dataset.status;
            });
        });
    });
    </script>
<?php else: ?>
    <?php
    $workshopOpts = collection($workshops ?? [])->combine('id', 'workshop_name')->toArray();
    $selWorkshop = $this->request->getQuery('workshop_id');
    $selDate = $this->request->getQuery('session_date');
    $sessionBookings = $sessionBookings ?? [];
    $statusOptions = $statusOptions ?? [];
    ?>

    <div class="space-y-10">
        <div class="rounded-3xl border border-neutral-200/70 bg-white/90 p-6 shadow-lg shadow-neutral-900/5 backdrop-blur-xl md:p-10">
            <h1 class="font-serif text-3xl font-semibold text-neutral-900 md:text-4xl">Workshop attendance</h1>
            <p class="mt-2 text-neutral-600">Choose one of your workshops and the workshop date. Load the roster, mark each student, then save.</p>

            <?= $this->Form->create(null, [
                'type' => 'get',
                'url' => ['action' => 'Điểm danh'],
                'class' => 'mt-6 flex flex-wrap items-end gap-4',
            ]) ?>
            <?= $this->Form->control('workshop_id', [
                'label' => 'Workshop',
                'options' => $workshopOpts,
                'empty' => 'Select workshop',
                'value' => $selWorkshop,
                'required' => false,
                'class' => 'min-w-[200px] rounded-xl border border-neutral-300 px-4 py-3 focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-200',
            ]) ?>
            <?= $this->Form->control('session_date', [
                'label' => 'Workshop date',
                'type' => 'date',
                'value' => $selDate,
                'class' => 'rounded-xl border border-neutral-300 px-4 py-3 focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-200',
            ]) ?>
            <?= $this->Form->button('Load bookings', [
                'class' => 'rounded-full bg-neutral-800 px-6 py-3 text-sm font-semibold text-white hover:bg-neutral-900',
            ]) ?>
            <?= $this->Form->end() ?>
        </div>

        <?php if ($workshopEntity && $sessionDate && count($sessionBookings) === 0): ?>
            <div class="rounded-2xl border border-amber-200/80 bg-amber-50/90 px-6 py-5 text-sm text-amber-950">
                No bookings found for <strong><?= h($workshopEntity->workshop_name) ?></strong> on <?= h($sessionDate->format('Y-m-d')) ?>.
            </div>
        <?php elseif ($workshopEntity && $sessionDate): ?>
            <div class="rounded-3xl border border-neutral-200/70 bg-white/90 p-6 shadow-lg shadow-neutral-900/5 backdrop-blur-xl md:p-10">
                <h2 class="font-serif text-xl font-semibold text-neutral-900">
                    <?= h($workshopEntity->workshop_name) ?> - <?= h($sessionDate->format('Y-m-d')) ?>
                </h2>

                <?= $this->Form->create(null, ['url' => ['action' => 'saveAttendance'], 'class' => 'mt-6']) ?>
                <?= $this->Form->hidden('workshop_id', ['value' => $workshopEntity->id]) ?>
                <?= $this->Form->hidden('session_date', ['value' => $sessionDate->format('Y-m-d')]) ?>

                <div class="overflow-hidden rounded-2xl border border-neutral-200/80">
                    <table class="min-w-full divide-y divide-neutral-100 text-left text-sm">
                        <thead class="bg-neutral-50/90 text-xs font-semibold uppercase tracking-wide text-neutral-500">
                            <tr>
                                <th class="px-4 py-3">Student</th>
                                <th class="px-4 py-3">Booking status</th>
                                <th class="px-4 py-3">Attendance</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-100">
                            <?php foreach ($sessionBookings as $b): ?>
                                <tr class="hover:bg-primary-50/30">
                                    <td class="px-4 py-3 font-medium text-neutral-900"><?= h($b->user->email ?? '-') ?></td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex rounded-full border border-primary-200 bg-primary-50 px-3 py-0.5 text-xs font-medium text-primary-900">
                                            <?= h($b->status ?? 'pending') ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <?= $this->Form->select('attendance.' . $b->id, $statusOptions, [
                                            'value' => $b->attendance_status ?? '',
                                            'class' => 'w-full max-w-xs rounded-xl border border-neutral-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-200',
                                        ]) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-8">
                    <?= $this->Form->button('Save attendance', [
                        'class' => 'rounded-full bg-primary-600 px-8 py-3 text-sm font-semibold text-white hover:bg-primary-700',
                    ]) ?>
                </div>
                <?= $this->Form->end() ?>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

