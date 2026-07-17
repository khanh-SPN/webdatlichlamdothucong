<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Teacher $instructor
 * @var \App\Model\Entity\User $user
 * @var iterable<\App\Model\Entity\Booking> $bookings
 * @var array<string, int|string|null> $metrics
 */
$this->assign('title', 'Chi tiết Học viên');
?>
<div class="space-y-10">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="font-serif text-3xl font-semibold text-neutral-900 md:text-4xl">Hồ sơ Học viên</h1>
            <p class="mt-1 text-lg text-neutral-600"><?= h($user->email ?? '') ?></p>
        </div>
        <?= $this->Html->link('← Tất cả học viên', ['action' => 'students'], [
            'class' => 'text-sm font-semibold text-primary-700 hover:underline',
        ]) ?>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-2xl border border-neutral-200/70 bg-white/90 p-6 shadow-sm">
            <p class="text-[10px] font-semibold uppercase tracking-widest text-neutral-500">Tổng đặt chỗ</p>
            <p class="mt-2 text-2xl font-bold text-primary-600"><?= (int) $metrics['total_bookings'] ?></p>
        </div>
        <div class="rounded-2xl border border-neutral-200/70 bg-white/90 p-6 shadow-sm">
            <p class="text-[10px] font-semibold uppercase tracking-widest text-neutral-500">Đã xác nhận</p>
            <p class="mt-2 text-2xl font-bold text-primary-600"><?= (int) $metrics['confirmed'] ?></p>
        </div>
        <div class="rounded-2xl border border-neutral-200/70 bg-white/90 p-6 shadow-sm">
            <p class="text-[10px] font-semibold uppercase tracking-widest text-neutral-500">Hội thảo khác nhau</p>
            <p class="mt-2 text-2xl font-bold text-primary-600"><?= (int) $metrics['distinct_workshops'] ?></p>
        </div>
        <div class="rounded-2xl border border-neutral-200/70 bg-white/90 p-6 shadow-sm">
            <p class="text-[10px] font-semibold uppercase tracking-widest text-neutral-500">Điểm danh \(đã đánh dấu\)</p>
            <p class="mt-2 text-2xl font-bold text-primary-600">
                <?php if ($metrics['attendance_rate'] === null): ?>
                    <span class="text-neutral-400">—</span>
                <?php else: ?>
                    <?= (int) $metrics['attendance_rate'] ?>%
                    <span class="block text-xs font-normal text-neutral-500"><?= (int) $metrics['attendance_present'] ?> có mặt / <?= (int) $metrics['attendance_marked'] ?> đã đánh dấu</span>
                <?php endif; ?>
            </p>
        </div>
    </div>

    <div class="rounded-3xl border border-neutral-200/70 bg-white/90 p-6 shadow-lg shadow-neutral-900/5 backdrop-blur-xl md:p-10">
        <h2 class="font-serif text-xl font-semibold text-neutral-900">Lịch sử Đặt chỗ &amp; Điểm danh</h2>
        <div class="mt-6 overflow-hidden rounded-2xl border border-neutral-200/80">
            <table class="min-w-full divide-y divide-neutral-100 text-left text-sm">
                <thead class="bg-neutral-50/90 text-xs font-semibold uppercase tracking-wide text-neutral-500">
                    <tr>
                        <th class="px-4 py-3">Hội thảo</th>
                        <th class="px-4 py-3">Ngày hội thảo</th>
                        <th class="px-4 py-3">Trạng thái đặt chỗ</th>
                        <th class="px-4 py-3">Điểm danh</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    <?php foreach ($bookings as $b): ?>
                        <tr class="hover:bg-primary-50/30">
                            <td class="px-4 py-3 font-medium text-neutral-900"><?= h($b->workshop->workshop_name ?? '—') ?></td>
                            <td class="px-4 py-3 text-neutral-600"><?= h($b->booking_date ? $b->booking_date->format('Y-m-d') : '—') ?></td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full border border-primary-200 bg-primary-50 px-3 py-0.5 text-xs font-medium text-primary-900">
                                    <?= h($b->status ?? 'pending') ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-neutral-700">
                                <?= $b->attendance_status ? h(ucfirst((string) $b->attendance_status)) : '—' ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

