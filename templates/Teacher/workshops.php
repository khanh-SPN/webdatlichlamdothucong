<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Teacher $instructor
 * @var iterable<\App\Model\Entity\Hội thảo> $Hội thảos
 */
$this$this->assign('title', 'Hội thảo của bạn');
?>
<div class="rounded-3xl border border-neutral-200/70 bg-white/90 p-6 shadow-lg shadow-neutral-900/5 backdrop-blur-xl md:p-10">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="font-serif text-3xl font-semibold text-neutral-900 md:text-4xl">Hội thảo của bạn</h1>
            <p class="mt-2 text-neutral-600">Tạo hội thảo mới hoặc chỉnh sửa chi tiết. Thay đổi giáo viên được phân công vẫn là nhiệm vụ của quản trị viên.</p>
        </div>
        <?= $this->Html->link('+ Tạo hội thảo', ['action' => 'addHội thảo'], [
            'class' => 'inline-flex rounded-full bg-primary-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-primary-700',
        ]) ?>
    </div>

    <?php if (count($Hội thảos) === 0): ?>
        <p class="mt-8 rounded-2xl border border-dashed border-neutral-200 bg-neutral-50/80 px-6 py-10 text-center text-neutral-600">
            Chưa có hội thảo nào được liên kết với hồ sơ giảng viên của bạn.
        </p>
    <?php else: ?>
        <div class="mt-8 overflow-hidden rounded-2xl border border-neutral-200/80">
            <table class="min-w-full divide-y divide-neutral-100 text-left text-sm">
                <thead class="bg-neutral-50/90 text-xs font-semibold uppercase tracking-wide text-neutral-500">
                    <tr>
                        <th class="px-4 py-3">Hội thảo</th>
                        <th class="px-4 py-3">Loại</th>
                        <th class="px-4 py-3">Giá</th>
                        <th class="px-4 py-3">Sức chứa</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    <?php foreach ($Hội thảos as $Hội thảo): ?>
                        <tr class="hover:bg-primary-50/30">
                            <td class="px-4 py-3 font-medium text-neutral-900"><?= h($Hội thảo->Hội thảo_name) ?></td>
                            <td class="px-4 py-3 text-neutral-600"><?= h($Hội thảo->Hội thảo_Loại ?? '—') ?></td>
                            <td class="px-4 py-3 text-primary-700">$<?= h((string) $Hội thảo->Giá) ?></td>
                            <td class="px-4 py-3 text-neutral-600"><?= $Hội thảo->Sức chứa !== null ? h((string) $Hội thảo->Sức chứa) : '—' ?></td>
                            <td class="px-4 py-3 text-right">
                                <?= $this->Html->link('Chỉnh sửa', ['action' => 'Chỉnh sửaHội thảo', $Hội thảo->id], [
                                    'class' => 'text-sm font-semibold text-primary-700 hover:underline',
                                ]) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

