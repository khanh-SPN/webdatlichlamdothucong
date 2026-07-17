<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Teacher $instructor
 * @var iterable<\App\Model\Entity\Announcement> $announcements
 * @var iterable<\App\Model\Entity\Workshop> $workshops
 */
$this$this->assign('title', 'Nhắn tin');
$workshopOptions = collection($workshops)->combine('id', 'workshop_name')->toArray();
$announcementCount = count($announcements);
?>
<div class="space-y-10">
    <div class="rounded-3xl border border-neutral-200/70 bg-white/90 p-6 shadow-lg shadow-neutral-900/5 backdrop-blur-xl md:p-10">
        <h1 class="font-serif text-3xl font-semibold text-neutral-900 md:text-4xl">Tin nhắn mới</h1>
        <p class="mt-2 text-neutral-600">Học viên đã đặt hội thảo của bạn sẽ thấy các tin nhắn này trên trang tài khoản của họ và nhận được bản sao email. Tin nhắn đã gửi xuất hiện bên dưới để bạn có thể xác nhận giao hàng.</p>

        <?= $this->Form->create(null, ['url' => ['action' => 'sendMessage'], 'class' => 'mt-6 space-y-5 max-w-2xl']) ?>
        <?= $this->Form->control('workshop_id', [
            'type' => 'select',
            'label' => 'Khán giả',
            'options' => $workshopOptions,
            'empty' => 'Tất cả học viên của tôi \(bất kỳ hội thảo\),',
            'class' => 'w-full rounded-xl border border-neutral-300 px-4 py-3 focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-200',
        ]) ?>
        <?= $this->Form->control('subject', [
            'type' => 'text',
            'label' => 'Chủ đề',
            'required' => true,
            'maxlength' => 100,
            'placeholder' => 'ví dụ: Lớp được dời lại, Cần vật liệu mới, v.v.',
            'class' => 'w-full rounded-xl border border-neutral-300 px-4 py-3 focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-200',
        ]) ?>
        <?= $this->Form->control('body', [
            'type' => 'textarea',
            'label' => 'Tin nhắn',
            'rows' => 5,
            'required' => true,
            'placeholder' => 'Nhập tin nhắn của bạn ở đây...',
            'class' => 'w-full rounded-xl border border-neutral-300 px-4 py-3 focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-200',
        ]) ?>
        <?= $this->Form->button('Gửi tin nhắn', [
            'class' => 'rounded-full bg-primary-600 px-8 py-3 text-sm font-semibold text-white hover:bg-primary-700',
        ]) ?>
        <?= $this->Form->end() ?>
    </div>

    <div class="rounded-3xl border border-neutral-200/70 bg-white/90 p-6 shadow-lg shadow-neutral-900/5 backdrop-blur-xl md:p-10">
        <h2 class="font-serif text-2xl font-semibold text-neutral-900">Tin nhắn gần đây</h2>
        <?php if ($announcementCount === 0): ?>
            <p class="mt-6 text-neutral-600">Chưa có tin nhắn nào.</p>
        <?php else: ?>
            <ul class="mt-6 space-y-4">
                <?php foreach ($announcements as $a): ?>
                    <li class="rounded-2xl border border-neutral-100 bg-neutral-50/60 px-5 py-4">
                        <div class="flex flex-wrap items-center justify-between gap-2 text-xs text-neutral-500">
                            <span><?= h($a->sent_at?->format('Y-m-d H:i') ?? '') ?></span>
                            <span class="rounded-full bg-white px-3 py-0.5 font-medium text-primary-800 ring-1 ring-primary-100">
                                <?= $a->workshop_id === null ? 'All workshops' : h($a->workshop->workshop_name ?? 'Workshop') ?>
                            </span>
                        </div>
                        <?php if (!empty($a->subject)): ?>
                            <h4 class="mt-2 font-semibold text-neutral-900"><?= h($a->subject) ?></h4>
                        <?php endif; ?>
                        <p class="mt-2 whitespace-pre-wrap text-neutral-800"><?= h($a->body) ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>

