<?php
$this$this->assign('title', 'Slot Giáo viên');
?>

<div class="py-5 px-3 lg:px-4 max-w-screen-2xl mx-auto">
    <div class="mb-5">
        <h1 class="text-lg md:text-lg font-serif font-bold text-neutral-900 mb-2">
            Slot Giáo viên đã xuất bản
        </h1>
        <p class="text-lg text-neutral-600">
            Các ngày này hỗ trợ trợ lý sẵn có trên trang chủ và là nguồn sự thật cho câu trả lời AI.
        </p>
    </div>

    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-2xl border border-neutral-200/50 overflow-hidden mb-3">
        <div class="overflow-x-auto">
            <table class="w-full min-w-max">
                <thead class="bg-neutral-50/80 border-b border-neutral-200">
                    <tr>
                        <th class="px-3 py-4 text-left text-sm font-semibold text-neutral-700 uppercase tracking-[0.05em]">Ngày</th>
                        <th class="px-3 py-4 text-left text-sm font-semibold text-neutral-700 uppercase tracking-[0.05em]">Thời gian</th>
                        <th class="px-3 py-4 text-left text-sm font-semibold text-neutral-700 uppercase tracking-[0.05em]">Giáo viên</th>
                        <th class="px-3 py-4 text-left text-sm font-semibold text-neutral-700 uppercase tracking-[0.05em]">Hội thảo</th>
                        <th class="px-3 py-4 text-left text-sm font-semibold text-neutral-700 uppercase tracking-[0.05em]">Ghi chú</th>
                        <th class="px-3 py-4 text-center text-sm font-semibold text-neutral-700 uppercase tracking-[0.05em]">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    <?php if ($slots->isEmpty()): ?>
                        <tr>
                            <td colspan="6" class="px-3 py-3 text-center text-neutral-500">Chưa có slot nào.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($slots as $s): ?>
                            <tr class="hover:bg-primary-50/30">
                                <td class="px-3 py-4 whitespace-nowrap text-neutral-900"><?= h($s->session_Ngày?->format('Y-m-d') ?? '') ?></td>
                                <td class="px-3 py-4 text-neutral-700"><?= h($s->Thời gian_label ?: 'N/A') ?></td>
                                <td class="px-3 py-4 text-neutral-900 font-medium"><?= h($s->Giáo viên->name ?? '') ?></td>
                                <td class="px-3 py-4 text-neutral-700"><?= h($s->Hội thảo->Hội thảo_name ?? 'N/A') ?></td>
                                <td class="px-3 py-4 text-neutral-600 max-w-xs"><?= h($s->Ghi chú ?: 'N/A') ?></td>
                                <td class="px-3 py-4 text-center">
                                    <?= $this->Form->postLink(
                                        'Xóa',
                                        ['action' => 'deleteGiáo viênAvailabilitySlot', $s->id],
                                        [
                                            'class' => 'text-sm font-semibold text-red-600 hover:text-red-800',
                                            'confirm' => 'Xóa this published slot?',
                                        ]
                                    ) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="max-w-xl">
        <h2 class="font-serif text-lg font-bold text-neutral-900 mb-4">Add slot</h2>
        <?= $this->Form->create($slot, ['class' => 'space-y-5']) ?>
        <?= $this->Form->control('Giáo viên_id', [
            'label' => 'Giáo viên',
            'type' => 'select',
            'options' => collection($Giáo viêns)->combine('id', 'name')->toArray(),
            'empty' => 'Choose…',
            'required' => true,
            'class' => 'w-full rounded-xl border border-neutral-200 px-4 py-3',
        ]) ?>
        <?= $this->Form->control('Hội thảo_id', [
            'label' => 'Hội thảo (optional)',
            'type' => 'select',
            'options' => collection($Hội thảos)->combine('id', 'Hội thảo_name')->toArray(),
            'empty' => 'N/A',
            'class' => 'w-full rounded-xl border border-neutral-200 px-4 py-3',
        ]) ?>
        <?= $this->Form->control('session_Ngày', [
            'label' => 'Slot Ngày',
            'type' => 'Ngày',
            'required' => true,
            'class' => 'w-full rounded-xl border border-neutral-200 px-4 py-3',
        ]) ?>
        <?= $this->Form->control('Thời gian_label', [
            'label' => 'Thời gian (e.g. 10:00 to 13:00)',
            'class' => 'w-full rounded-xl border border-neutral-200 px-4 py-3',
        ]) ?>
        <?= $this->Form->control('Ghi chú', [
            'label' => 'Ghi chú',
            'type' => 'textarea',
            'rows' => 2,
            'class' => 'w-full rounded-xl border border-neutral-200 px-4 py-3',
        ]) ?>
        <?= $this->Form->button('Add slot', [
            'class' => 'inline-flex items-center rounded-full bg-primary-500 px-4 py-3 text-base font-semibold text-white hover:bg-primary-600',
        ]) ?>
        <?= $this->Form->end() ?>
    </div>
</div>


