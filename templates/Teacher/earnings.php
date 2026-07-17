<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Teacher $instructor
 * @var string $period
 * @var float $totalRevenue
 * @var array<string, float> $byMonth
 * @var array<int, array{name: string, total: float, count: int}> $byWorkshop
 * @var float $maxMonth
 * @var \Cake\I18n\FrozenDate|null $startDate
 * @var \Cake\I18n\FrozenDate|null $endDate
 */
$this$this->assign('title', 'Thu nhập');
$periodLabels = ['month' => 'Tháng này', 'quarter' => '3 tháng qua', 'all' => 'Tất cả thời gian'];
?>
<div class="space-y-5">
    <div class="rounded-2xl border border-neutral-200/70 bg-white/90 p-3 shadow-lg shadow-neutral-900/5 backdrop-blur-xl md:p-5">
        <h1 class="font-serif text-xl font-semibold text-neutral-900 md:text-lg">Thu nhập</h1>
        <p class="mt-2 text-neutral-600">
            Tổng chỉ sử dụng đặt chỗ <strong>đã xác nhận</strong> \(một giá hội thảo mỗi đặt chỗ\).
            <?php if ($startDate && $endDate): ?>
                Phạm vi: <?= h($startDate->format('Y-m-d')) ?> — <?= h($endDate->format('Y-m-d')) ?>.
            <?php elseif ($period === 'all'): ?>
                Phạm vi: all time.
            <?php endif; ?>
        </p>

        <div class="mt-3 flex flex-wrap gap-2">
            <?php foreach (['month', 'quarter', 'all'] as $p): ?>
                <?= $this->Html->link(
                    $periodLabels[$p],
                    ['action' => 'Thu nhập', '?' => ['period' => $p]],
                    ['class' => 'rounded-full px-4 py-2 text-sm font-semibold ' . ($period === $p
                        ? 'bg-primary-600 text-white'
                        : 'border border-neutral-200 bg-white text-neutral-700 hover:bg-neutral-50')]
                ) ?>
            <?php endforeach; ?>
        </div>

        <div class="mt-5 grid gap-3 sm:grid-cols-2">
            <div class="rounded-2xl border border-primary-100 bg-primary-50/50 p-3">
                <p class="text-xs font-semibold uppercase tracking-wide text-primary-800/80">Tổng doanh thu</p>
                <p class="mt-2 text-xl font-bold tabular-nums text-primary-900">$<?= number_format($totalRevenue, 2) ?></p>
            </div>
            <div class="rounded-2xl border border-neutral-200/80 p-3">
                <p class="text-xs font-semibold uppercase tracking-wide text-neutral-500">Xuất CSV</p>
                <p class="mt-2 text-sm text-neutral-600">Tải xuống đặt chỗ cho hội thảo của bạn với ngày tùy chọn.</p>
                <?php
                $csvQuery = array_filter([
                    'type' => 'bookings',
                    'from' => $startDate?->format('Y-m-d'),
                    'to' => $endDate?->format('Y-m-d'),
                ], static function ($v) {
                    return $v !== null && $v !== '';
                });
                ?>
                <?= $this->Html->link(
                    'Download bookings CSV',
                    ['action' => 'downloadReport', '?' => $csvQuery + ['type' => 'bookings']],
                    ['class' => 'mt-4 inline-flex rounded-full border border-primary-300 bg-white px-5 py-2 text-sm font-semibold text-primary-800 hover:bg-primary-50']
                ) ?>
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-neutral-200/70 bg-white/90 p-3 shadow-lg shadow-neutral-900/5 backdrop-blur-xl md:p-5">
        <h2 class="font-serif text-lg font-semibold text-neutral-900">Monthly breakdown</h2>
        <?php if ($byMonth === []): ?>
            <p class="mt-4 text-neutral-600">No confirmed bookings in this range.</p>
        <?php else: ?>
            <div class="mt-3 space-y-3">
                <?php foreach ($byMonth as $ym => $amt): ?>
                    <?php $pct = $maxMonth > 0 ? round(($amt / $maxMonth) * 100) : 0; ?>
                    <div>
                        <div class="flex justify-between text-sm">
                            <span class="font-medium text-neutral-800"><?= h($ym) ?></span>
                            <span class="tabular-nums text-neutral-600">$<?= number_format($amt, 2) ?></span>
                        </div>
                        <div class="mt-1 h-2 overflow-hidden rounded-full bg-neutral-100">
                            <div class="h-full rounded-full bg-primary-500" style="width: <?= (int) $pct ?>%"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="rounded-2xl border border-neutral-200/70 bg-white/90 p-3 shadow-lg shadow-neutral-900/5 backdrop-blur-xl md:p-5">
        <h2 class="font-serif text-lg font-semibold text-neutral-900">Per workshop</h2>
        <?php if ($byWorkshop === []): ?>
            <p class="mt-4 text-neutral-600">No data for this range.</p>
        <?php else: ?>
            <div class="mt-3 overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="text-xs font-semibold uppercase text-neutral-500">
                        <tr>
                            <th class="pb-2 pr-4">Workshop</th>
                            <th class="pb-2 pr-4">Bookings</th>
                            <th class="pb-2">Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        <?php foreach ($byWorkshop as $row): ?>
                            <tr>
                                <td class="py-3 pr-4 font-medium text-neutral-900"><?= h($row['name']) ?></td>
                                <td class="py-3 pr-4 tabular-nums"><?= (int) $row['count'] ?></td>
                                <td class="py-3 tabular-nums text-primary-800">$<?= number_format($row['total'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>


