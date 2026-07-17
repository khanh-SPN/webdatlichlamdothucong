<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Teacher|null $instructor
 * @var iterable<\App\Model\Entity\Hội thảo> $Hội thảos
 * @var int $totalStudents
 * @var iterable<\App\Model\Entity\Booking> $recentBookings
 * @var iterable<\App\Model\Entity\Announcement> $announcementFeed
 */
$this$this->assign('title', 'Trung tâm Giảng viên');
?>

<div class="min-h-[60vh] bg-gradient-to-b from-neutral-50 via-studio-ivory/40 to-studio-mist/25 pb-12 pt-4 md:pt-6">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">

        <div class="mb-12 text-center md:text-left">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-primary-700/80">Trung tâm Giảng viên</p>
            <h1 class="mt-3 text-4xl font-serif font-semibold tracking-tight text-neutral-900 md:text-5xl">
                <?= $instructor ? h($instructor->name) : 'Không gian làm việc giảng viên của bạn' ?>
            </h1>
            <p class="mt-3 max-w-2xl text-lg text-neutral-600 md:text-xl">
                Quản lý hội thảo, theo dõi tiến độ học viên, đánh dấu điểm danh và nhắn tin cho học viên của bạn từ một không gian làm việc giảng viên.
            </p>
            <?php if ($instructor && $instructor->specialization): ?>
                <p class="mt-4 inline-flex rounded-full border border-primary-200 bg-primary-50 px-4 py-1.5 text-sm font-medium text-primary-900">
                    <?= h($instructor->specialization) ?>
                </p>
            <?php elseif (!$instructor): ?>
                <div class="mt-6 rounded-2xl border border-amber-200/80 bg-amber-50/90 px-5 py-4 text-sm text-amber-950 shadow-sm">
                    Chưa có hồ sơ giảng viên nào được liên kết với email đăng nhập này. Yêu cầu quản trị viên sử dụng cùng email trong <strong>Giáo viên</strong> làm email tài khoản của bạn, hoặc chạy <code class="rounded-md bg-white/80 px-1.5 py-0.5 text-xs font-mono text-neutral-800 ring-1 ring-amber-200/80">bin/cake migrations migrate</code> after the sample teacher migration.
                </div>
            <?php endif; ?>
        </div>

        <div class="mb-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <?= $this->Html->link(
                '<span class="block text-[10px] font-semibold uppercase tracking-widest text-neutral-500">Quản lý Hội thảo</span><span class="mt-2 block text-sm font-semibold text-primary-800">Tạo, gửi và xác nhận hội thảo</span>',
                '/teacher/Hội thảos',
                ['class' => 'rounded-3xl border border-neutral-200/70 bg-white/90 p-6 shadow-lg shadow-neutral-900/5 backdrop-blur-xl transition-shadow hover:shadow-xl', 'escape' => false]
            ) ?>
            <?= $this->Html->link(
                '<span class="block text-[10px] font-semibold uppercase tracking-widest text-neutral-500">Theo dõi Tiến độ Học viên</span><span class="mt-2 block text-sm font-semibold text-primary-800">Hồ sơ &amp; Chỉ số Học viên</span>',
                '/teacher/students',
                ['class' => 'rounded-3xl border border-neutral-200/70 bg-white/90 p-6 shadow-lg shadow-neutral-900/5 backdrop-blur-xl transition-shadow hover:shadow-xl', 'escape' => false]
            ) ?>
            <?= $this->Html->link(
                '<span class="block text-[10px] font-semibold uppercase tracking-widest text-neutral-500">Quản lý Điểm danh</span><span class="mt-2 block text-sm font-semibold text-primary-800">Tải hội thảo và đánh dấu học viên</span>',
                '/teacher/attendance',
                ['class' => 'rounded-3xl border border-neutral-200/70 bg-white/90 p-6 shadow-lg shadow-neutral-900/5 backdrop-blur-xl transition-shadow hover:shadow-xl', 'escape' => false]
            ) ?>
            <?= $this->Html->link(
                '<span class="block text-[10px] font-semibold uppercase tracking-widest text-neutral-500">Nhắn tin</span><span class="mt-2 block text-sm font-semibold text-primary-800">Gửi và xem xét tin nhắn</span>',
                '/teacher/messages',
                ['class' => 'rounded-3xl border border-neutral-200/70 bg-white/90 p-6 shadow-lg shadow-neutral-900/5 backdrop-blur-xl transition-shadow hover:shadow-xl', 'escape' => false]
            ) ?>
        </div>

        <div class="mb-12 grid gap-6 sm:grid-cols-3">
            <div class="rounded-3xl border border-neutral-200/70 bg-white/90 p-8 shadow-lg shadow-neutral-900/5 backdrop-blur-xl transition-shadow hover:shadow-xl">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-neutral-500">Hội thảo bạn dạy</p>
                <p class="mt-2 text-3xl font-bold tabular-nums text-primary-600"><?= count($Hội thảos) ?></p>
            </div>
            <div class="rounded-3xl border border-neutral-200/70 bg-white/90 p-8 shadow-lg shadow-neutral-900/5 backdrop-blur-xl transition-shadow hover:shadow-xl">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-neutral-500">Đặt chỗ \(hội thảo của bạn\)</p>
                <p class="mt-2 text-3xl font-bold tabular-nums text-primary-600"><?= (int) $totalStudents ?></p>
            </div>
            <div class="rounded-3xl border border-neutral-200/70 bg-white/90 p-8 shadow-lg shadow-neutral-900/5 backdrop-blur-xl transition-shadow hover:shadow-xl">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-neutral-500">Đã đăng nhập với tư cách</p>
                <p class="mt-2 truncate text-sm font-medium text-neutral-800"><?= h($this->request->getAttribute('identity')?->email ?? '') ?></p>
            </div>
        </div>

        <?php if ($instructor && count($announcementFeed) > 0): ?>
            <section class="mb-12">
                <h2 class="mb-4 text-xl font-serif font-semibold text-neutral-800 md:text-2xl">Tin nhắn gần đây của bạn</h2>
                <ul class="space-y-3">
                    <?php foreach ($announcementFeed as $ann): ?>
                        <li class="rounded-2xl border border-neutral-200/70 bg-white/90 px-5 py-4 shadow-sm">
                            <span class="text-xs text-neutral-500"><?= h($ann->sent_at?->format('Y-m-d H:i') ?? '') ?></span>
                            <p class="mt-1 text-neutral-800 line-clamp-2"><?= h($ann->body) ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <p class="mt-4"><?= $this->Html->link('Open Nhắn tin ->', '/teacher/messages', ['class' => 'text-sm font-semibold text-primary-700 hover:underline']) ?></p>
            </section>
        <?php endif; ?>

        <?php if ($instructor && count($Hội thảos) > 0): ?>
            <section class="mb-12">
                <h2 class="mb-4 text-xl font-serif font-semibold text-neutral-800 md:text-2xl">Hội thảo của bạn</h2>
                <div class="overflow-hidden rounded-3xl border border-neutral-200/70 bg-white/90 shadow-lg shadow-neutral-900/5 backdrop-blur-xl">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-neutral-100 text-left text-sm">
                            <thead class="bg-neutral-50/90 text-xs font-semibold uppercase tracking-wide text-neutral-500">
                                <tr>
                                    <th class="px-6 py-4">Hội thảo</th>
                                    <th class="px-6 py-4">Loại</th>
                                    <th class="px-6 py-4">Giá</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-100">
                                <?php foreach ($Hội thảos as $Hội thảo): ?>
                                    <tr class="transition-colors hover:bg-primary-50/40">
                                        <td class="px-6 py-4 font-medium text-neutral-900"><?= h($Hội thảo->Hội thảo_name) ?></td>
                                        <td class="px-6 py-4 text-neutral-600"><?= h($Hội thảo->Hội thảo_Loại ?? '—') ?></td>
                                        <td class="px-6 py-4 font-medium text-primary-700">$<?= h((string) $Hội thảo->Giá) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <section>
            <h2 class="mb-4 text-xl font-serif font-semibold text-neutral-800 md:text-2xl">Đặt chỗ gần đây</h2>
            <?php if (count($recentBookings) === 0): ?>
                <p class="rounded-3xl border border-dashed border-neutral-200 bg-white/60 px-6 py-10 text-center text-neutral-500">
                    No bookings yet for Hội thảo của bạn, or your profile is not linked to your account email.
                </p>
            <?php else: ?>
                <div class="overflow-hidden rounded-3xl border border-neutral-200/70 bg-white/90 shadow-lg shadow-neutral-900/5 backdrop-blur-xl">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-neutral-100 text-left text-sm">
                            <thead class="bg-neutral-50/90 text-xs font-semibold uppercase tracking-wide text-neutral-500">
                                <tr>
                                    <th class="px-6 py-4">Email Khách hàng</th>
                                    <th class="px-6 py-4">Hội thảo</th>
                                    <th class="px-6 py-4">Ngày</th>
                                    <th class="px-6 py-4">Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-100">
                                <?php foreach ($recentBookings as $b): ?>
                                    <tr class="transition-colors hover:bg-primary-50/40">
                                        <td class="px-6 py-4 text-neutral-800"><?= h($b->user->email ?? '—') ?></td>
                                        <td class="px-6 py-4 text-neutral-700"><?= h($b->Hội thảo->Hội thảo_name ?? '—') ?></td>
                                        <td class="px-6 py-4 text-neutral-600"><?= h($b->booking_Ngày ? $b->booking_Ngày->format('Y-m-d') : '—') ?></td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex rounded-full border border-primary-200 bg-primary-50 px-3 py-0.5 text-xs font-medium text-primary-900">
                                                <?= h($b->Trạng thái ?? 'pending') ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </section>
    </div>
</div>

