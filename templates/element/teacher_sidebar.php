<?php
/**
 * @var \App\View\AppView $this
 */
$path = rtrim($this->getRequest()->getPath(), '/') ?: '/';
$link = function (string $href, string $label) use ($path): string {
    $base = strtok($href, '?') ?: $href;
    if ($base === '/teacher') {
        $active = ($path === '/teacher');
    } else {
        $active = str_starts_with($path, $base);
    }
    $cls = $active
        ? 'border-primary-200 bg-primary-50/90 text-primary-900 font-semibold shadow-sm'
        : 'border-transparent text-neutral-700 hover:border-neutral-200 hover:bg-white/80';

    return (string) $this->Html->link($label, $href, [
        'class' => 'block rounded-xl border px-4 py-3 text-sm transition ' . $cls,
        'escape' => false,
    ]);
};
?>
<aside class="w-full shrink-0 lg:w-56 xl:w-64" aria-label="Trung tâm Giảng viên">
    <div class="rounded-3xl border border-neutral-200/70 bg-white/90 p-4 shadow-lg shadow-neutral-900/5 backdrop-blur-xl lg:sticky lg:top-28">
        <p class="mb-3 px-2 text-[10px] font-semibold uppercase tracking-widest text-neutral-500">Trung tâm Giảng viên</p>
        <nav class="flex flex-col gap-1">
            <?= $link('/teacher', 'Tổng quan') ?>
            <?= $link('/teacher/Hồ sơ', 'Hồ sơ') ?>
            <?= $link('/teacher/Khả dụng', 'Khả dụng') ?>
            <?= $link('/teacher/slots', 'Slot của tôi') ?>
            <?= $link('/teacher/Lịch', 'Lịch') ?>
            <?= $link('/teacher/workshops', 'Quản lý Hội thảo') ?>
            <?= $link('/teacher/students', 'Tiến độ Học viên') ?>
            <?= $link('/teacher/Điểm danh', 'Điểm danh') ?>
            <?= $link('/teacher/messages', 'Nhắn tin') ?>
            <?= $link('/teacher/Thu nhập', 'Thu nhập') ?>
            <?= $link('/teacher/reports/download?type=bookings', 'Tải xuống CSV') ?>
        </nav>
    </div>
</aside>

