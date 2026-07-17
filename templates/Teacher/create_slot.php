<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Teacher $instructor
 * @var iterable<\App\Model\Entity\Hội thảo> $Hội thảos
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

// Get date from query param
$prefillDate = $this->request->getQuery('date', date('Y-m-d'));
?>

<div class="min-h-[60vh] bg-gradient-to-b from-neutral-50 via-studio-ivory/40 to-studio-mist/25 pb-12 pt-4 md:pt-6">
    <div class="mx-auto max-w-2xl px-3 lg:px-4">
        
        <!-- Header -->
        <div class="mb-6">
            <p class="text-xs font-semibold uppercase tracking-[0.15em] text-primary-700/80">Tạo Slot</p>
            <h1 class="mt-2 text-2xl font-serif font-semibold tracking-tight text-neutral-900">
                Tạo Slot Mới
            </h1>
            <p class="mt-1 text-neutral-600">Lên lịch một buổi hội thảo mới</p>
        </div>

        <!-- Form -->
        <div class="rounded-2xl border border-neutral-200/70 bg-white/90 p-6 shadow-lg shadow-neutral-900/5 backdrop-blur-xl">
            <?= $this->Form->create(null, ['class' => 'space-y-5']) ?>
                
                <!-- Hội thảo Selection -->
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 mb-1">
                        Hội thảo <span class="text-red-500">*</span>
                    </label>
                    <?= $this->Form->select('Hội thảo_id', 
                        collection($Hội thảos)->combine('id', function($Hội thảo) {
                            return $Hội thảo->Hội thảo_name . ' ($' . $Hội thảo->price . ')';
                        })->toArray(),
                        [
                            'empty' => 'Select a Hội thảo...',
                            'required' => true,
                            'class' => 'w-full rounded-lg border border-neutral-300 bg-white px-3 py-2.5 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20',
                        ]
                    ) ?>
                </div>

                <!-- Date -->
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 mb-1">
                        Date <span class="text-red-500">*</span>
                    </label>
                    <?= $this->Form->date('session_date', [
                        'value' => $prefillDate,
                        'required' => true,
                        'min' => date('Y-m-d'),
                        'class' => 'w-full rounded-lg border border-neutral-300 bg-white px-3 py-2.5 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20',
                    ]) ?>
                </div>

                <!-- Time Range -->
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-semibold text-neutral-700 mb-1">
                            Start Time <span class="text-red-500">*</span>
                        </label>
                        <?= $this->Form->select('start_time', $timeOptions, [
                            'value' => '09:00',
                            'required' => true,
                            'class' => 'w-full rounded-lg border border-neutral-300 bg-white px-3 py-2.5 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20',
                        ]) ?>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-neutral-700 mb-1">
                            End Time <span class="text-red-500">*</span>
                        </label>
                        <?= $this->Form->select('end_time', $timeOptions, [
                            'value' => '12:00',
                            'required' => true,
                            'class' => 'w-full rounded-lg border border-neutral-300 bg-white px-3 py-2.5 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20',
                        ]) ?>
                    </div>
                </div>

                <!-- Capacity and Location -->
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-semibold text-neutral-700 mb-1">
                            Capacity <span class="text-red-500">*</span>
                        </label>
                        <?= $this->Form->number('capacity', [
                            'value' => 10,
                            'min' => 1,
                            'max' => 100,
                            'required' => true,
                            'class' => 'w-full rounded-lg border border-neutral-300 bg-white px-3 py-2.5 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20',
                        ]) ?>
                        <p class="mt-1 text-xs text-neutral-500">Maximum number of students</p>
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

                <!-- Validation Info -->
                <div class="rounded-xl bg-blue-50 border border-blue-200 p-4">
                    <p class="text-sm text-blue-800">
                        <strong>Note:</strong> Slots cannot overlap with existing slots on the same date.
                        Please ensure your time slot doesn't conflict with other scheduled slots.
                    </p>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-neutral-200">
                    <?= $this->Html->link('Cancel', ['action' => 'slots'], [
                        'class' => 'rounded-lg border border-neutral-300 bg-white px-5 py-2.5 text-sm font-semibold text-neutral-700 hover:bg-neutral-50',
                    ]) ?>
                    <?= $this->Form->button('Tạo Slot', [
                        'class' => 'rounded-lg bg-primary-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-primary-600/20 hover:bg-primary-700',
                    ]) ?>
                </div>

            <?= $this->Form->end() ?>
        </div>

    </div>
</div>

