<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Teacher $instructor
 * @var iterable<\App\Model\Entity\TeacherAvailabilitySlot> $slots
 * @var iterable<\App\Model\Entity\Workshop> $workshops
 * @var array $calendarData
 * @var int $month
 * @var int $year
 * @var string $firstDay
 * @var string $lastDay
 */

$workshopColors = [];
$colorPalette = [
    'bg-blue-100 border-blue-300 text-blue-900',
    'bg-green-100 border-green-300 text-green-900',
    'bg-purple-100 border-purple-300 text-purple-900',
    'bg-orange-100 border-orange-300 text-orange-900',
    'bg-pink-100 border-pink-300 text-pink-900',
    'bg-teal-100 border-teal-300 text-teal-900',
];

foreach ($workshops as $index => $workshop) {
    $workshopColors[$workshop->id] = $colorPalette[$index % count($colorPalette)];
}

// Calendar navigation
$prevMonth = $month - 1;
$prevYear = $year;
if ($prevMonth < 1) {
    $prevMonth = 12;
    $prevYear--;
}

$nextMonth = $month + 1;
$nextYear = $year;
if ($nextMonth > 12) {
    $nextMonth = 1;
    $nextYear++;
}

// Build calendar
$daysInMonth = date('t', strtotime($firstDay));
$firstDayOfWeek = date('w', strtotime($firstDay)); // 0 = Sunday
$today = date('Y-m-d');

$weekDays = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];
?>

<div class="min-h-[60vh] bg-gradient-to-b from-neutral-50 via-studio-ivory/40 to-studio-mist/25 pb-12 pt-4 md:pt-6">
    <div class="mx-auto max-w-screen-xl px-3 lg:px-4">
        
        <!-- Header -->
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.15em] text-primary-700/80">Calendar View</p>
                <h1 class="mt-2 text-2xl font-serif font-semibold tracking-tight text-neutral-900">
                    <?= date('F Y', strtotime($firstDay)) ?>
                </h1>
            </div>
            <div class="flex items-center gap-3">
                <?= $this->Html->link('← Previous', ['action' => 'calendar', '?' => ['month' => $prevMonth, 'year' => $prevYear]], [
                    'class' => 'rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm font-semibold text-neutral-700 hover:bg-neutral-50',
                ]) ?>
                <?= $this->Html->link('Today', ['action' => 'calendar'], [
                    'class' => 'rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm font-semibold text-neutral-700 hover:bg-neutral-50',
                ]) ?>
                <?= $this->Html->link('Next →', ['action' => 'calendar', '?' => ['month' => $nextMonth, 'year' => $nextYear]], [
                    'class' => 'rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm font-semibold text-neutral-700 hover:bg-neutral-50',
                ]) ?>
            </div>
        </div>

        <!-- Legend -->
        <?php if (!empty($workshops)): ?>
            <div class="mb-4 flex flex-wrap gap-2">
                <?php foreach ($workshops as $workshop): ?>
                    <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs <?= $workshopColors[$workshop->id] ?? 'bg-neutral-100 border-neutral-300' ?>">
                        <span class="h-2 w-2 rounded-full bg-current"></span>
                        <?= h($workshop->workshop_name) ?>
                    </span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Calendar Grid -->
        <div class="rounded-2xl border border-neutral-200/70 bg-white/90 shadow-lg shadow-neutral-900/5 backdrop-blur-xl overflow-hidden">
            <!-- Weekday Headers -->
            <div class="grid grid-cols-7 border-b border-neutral-200 bg-neutral-50">
                <?php foreach ($weekDays as $day): ?>
                    <div class="px-2 py-3 text-center text-xs font-semibold uppercase tracking-wider text-neutral-600">
                        <?= $day ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Calendar Days -->
            <div class="grid grid-cols-7">
                <?php
                // Empty cells before first day
                for ($i = 0; $i < $firstDayOfWeek; $i++):
                ?>
                    <div class="min-h-[100px] border-b border-r border-neutral-100 bg-neutral-50/50"></div>
                <?php endfor; ?>

                <?php for ($day = 1; $day <= $daysInMonth; $day++): 
                    $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $day);
                    $isToday = $dateStr === $today;
                    $isPast = $dateStr < $today;
                    $daySlots = $calendarData[$dateStr] ?? [];
                    $isWeekend = date('w', strtotime($dateStr)) === 0 || date('w', strtotime($dateStr)) === 6;
                ?>
                    <div class="min-h-[100px] border-b border-r border-neutral-100 p-2 <?= $isWeekend ? 'bg-neutral-50/30' : '' ?> <?= $isToday ? 'bg-primary-50/30' : '' ?> <?= $isPast ? 'opacity-60' : '' ?>">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-semibold <?= $isToday ? 'text-primary-600 bg-primary-100 rounded-full w-7 h-7 flex items-center justify-center' : ($isPast ? 'text-neutral-400' : 'text-neutral-700') ?>">
                                <?= $day ?>
                            </span>
                            <?php if (!empty($daySlots)): ?>
                                <span class="text-xs text-neutral-500"><?= count($daySlots) ?> slots</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="space-y-1">
                            <?php foreach ($daySlots as $slot): 
                                $colorClass = $workshopColors[$slot->workshop_id] ?? 'bg-neutral-100 border-neutral-300';
                                $statusBadge = match($slot->status) {
                                    'available' => '',
                                    'reserved' => ' • Reserved',
                                    'cancelled' => ' • Cancelled',
                                    default => '',
                                };
                            ?>
                                <a href="<?= $this->Url->build(['action' => 'editSlot', $slot->id]) ?>" 
                                   class="block rounded border p-1.5 text-xs hover:shadow-md transition-shadow <?= $colorClass ?>">
                                    <div class="font-medium truncate">
                                        <?= $slot->start_time ? $slot->start_time->format('g:i A') : '?' ?> - <?= $slot->end_time ? $slot->end_time->format('g:i A') : '?' ?>
                                    </div>
                                    <div class="truncate opacity-75">
                                        <?= h($slot->workshop->workshop_name ?? 'No Workshop') ?><?= $statusBadge ?>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>

                        <!-- Add Slot Button (hover) - only for future dates -->
                        <?php if (!$isPast): ?>
                            <div class="mt-1 opacity-0 hover:opacity-100 transition-opacity">
                                <?= $this->Html->link('+ Add', ['action' => 'createSlot', '?' => ['date' => $dateStr]], [
                                    'class' => 'block text-center text-xs text-primary-600 hover:text-primary-700 font-medium py-1',
                                ]) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endfor; ?>

                <?php
                // Empty cells after last day to fill grid
                $remainingCells = (7 - (($firstDayOfWeek + $daysInMonth) % 7)) % 7;
                for ($i = 0; $i < $remainingCells; $i++):
                ?>
                    <div class="min-h-[100px] border-b border-r border-neutral-100 bg-neutral-50/50"></div>
                <?php endfor; ?>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-6 flex justify-center">
            <?= $this->Html->link('List View', ['action' => 'slots'], [
                'class' => 'rounded-lg border border-neutral-300 bg-white px-5 py-2.5 text-sm font-semibold text-neutral-700 hover:bg-neutral-50',
            ]) ?>
        </div>

    </div>
</div>

