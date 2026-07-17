<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Teacher $instructor
 * @var \App\Model\Entity\Workshop $workshop
 */
$this$this->assign('title', 'Hội thảo mới');
?>
<div class="rounded-3xl border border-neutral-200/70 bg-white/90 p-6 shadow-lg shadow-neutral-900/5 backdrop-blur-xl md:p-10">
    <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="font-serif text-3xl font-semibold text-neutral-900 md:text-4xl">Tạo hội thảo</h1>
            <p class="mt-1 text-sm text-neutral-500">Hội thảo mới xuất hiện trong danh sách của bạn và có thể được đặt theo cài đặt trang web.</p>
        </div>
        <?= $this->Html->link('← Quay lại danh sách', ['action' => 'workshops'], [
            'class' => 'text-sm font-semibold text-primary-700 hover:underline',
        ]) ?>
    </div>

    <?= $this->Form->create($workshop) ?>
    <div class="grid max-w-xl gap-5">
        <?= $this->Form->control('workshop_name', [
            'label' => 'Tên hội thảo',
            'required' => true,
            'class' => 'w-full rounded-xl border border-neutral-300 px-4 py-3 focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-200',
        ]) ?>
        <?= $this->Form->control('workshop_type', [
            'label' => 'Loại',
            'class' => 'w-full rounded-xl border border-neutral-300 px-4 py-3 focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-200',
        ]) ?>
        <?= $this->Form->control('description', [
            'type' => 'textarea',
            'label' => 'Mô tả',
            'rows' => 5,
            'class' => 'w-full rounded-xl border border-neutral-300 px-4 py-3 focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-200',
        ]) ?>
        <?= $this->Form->control('price', [
            'label' => 'Giá',
            'required' => true,
            'class' => 'w-full rounded-xl border border-neutral-300 px-4 py-3 focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-200',
        ]) ?>
        <?= $this->Form->control('capacity', [
            'label' => 'Sức chứa \(tối đa học viên\)',
            'type' => 'number',
            'min' => 1,
            'max' => 500,
            'class' => 'w-full rounded-xl border border-neutral-300 px-4 py-3 focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-200',
        ]) ?>
    </div>
    <div class="mt-8">
        <?= $this->Form->button('Tạo hội thảo', [
            'class' => 'rounded-full bg-primary-600 px-8 py-3 text-sm font-semibold text-white hover:bg-primary-700',
        ]) ?>
    </div>
    <?= $this->Form->end() ?>
</div>

