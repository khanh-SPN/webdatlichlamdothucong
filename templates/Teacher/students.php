<?php
/**
 * @var \App\Xem\AppXem $this
 * @var \App\Model\Entity\Teacher $instructor
 * @var array<int, array<string, mixed>> $studentRows
 */
$this$this->assign('title', 'Tiến độ Học viên');
?>
<div class="rounded-3xl border border-neutral-200/70 bg-white/90 p-6 shadow-lg shadow-neutral-900/5 backdrop-blur-xl md:p-10">
    <h1 class="font-serif text-3xl font-semibold text-neutral-900 md:text-4xl">Tiến độ Học viên</h1>
    <p class="mt-2 text-neutral-600">Học viên đã đặt ít nhất một hội thảo của bạn. Mở hồ sơ cho các chỉ số đặt chỗ và điểm danh.</p>

    <?php if (count($studentRows) === 0): ?>
        <p class="mt-8 rounded-2xl border border-dashed border-neutral-200 bg-neutral-50/80 px-6 py-10 text-center text-neutral-600">
            Chưa có đặt chỗ học viên nào cho hội thảo của bạn.
        </p>
    <?php else: ?>
        <div class="mt-8 overflow-hidden rounded-2xl border border-neutral-200/80">
            <table class="min-w-full divide-y divide-neutral-100 text-left text-sm">
                <thead class="bg-neutral-50/90 text-xs font-semibold uppercase tracking-wide text-neutral-500">
                    <tr>
                        <th class="px-4 py-3">Email Học viên</th>
                        <th class="px-4 py-3">Đặt chỗ</th>
                        <th class="px-4 py-3">Đã xác nhận</th>
                        <th class="px-4 py-3">Hội thảo</th>
                        <th class="px-4 py-3">Tỷ lệ điểm danh</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    <?php foreach ($studentRows as $row): ?>
                        <?php /** @var \App\Model\Entity\User $u */ $u = $row['user']; ?>
                        <tr class="hover:bg-primary-50/30">
                            <td class="px-4 py-3 font-medium text-neutral-900"><?= h($u->email ?? '') ?></td>
                            <td class="px-4 py-3 text-neutral-700"><?= (int) $row['booking_count'] ?></td>
                            <td class="px-4 py-3 text-neutral-700"><?= (int) $row['Đã xác nhận_count'] ?></td>
                            <td class="px-4 py-3 text-neutral-700"><?= (int) $row['distinct_Hội thảo'] ?></td>
                            <td class="px-4 py-3 text-neutral-700">
                                <?php if ($row['attendance_rate'] === null): ?>
                                    <span class="text-neutral-400">—</span>
                                <?php else: ?>
                                    <?= (int) $row['attendance_rate'] ?>%
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <?= $this->Html->link('Xem', '/teacher/students/Xem/' . (int) $u->id, [
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

