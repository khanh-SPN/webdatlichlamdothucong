<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Teacher $instructor
 * @var iterable<\App\Model\Entity\TeacherAvailability> $slots
 */
$this$this->assign('title', 'Khả dụng hàng tuần');
$dayNames = ['Chủ Nhật', 'Thứ Hai', 'Thứ Ba', 'Thứ Tư', 'Thứ Năm', 'Thứ Sáu', 'Thứ Bảy'];
$timeOptions = [];
for ($hour = 0; $hour < 24; $hour++) {
    foreach ([0, 30] as $minute) {
        $value = sprintf('%02d:%02d', $hour, $minute);
        $timeOptions[$value] = (new \DateTimeImmutable($value))->format('g:i A');
    }
}
$normalizeTime = static function (mixed $time, ?string $startTime = null): string {
    if ($time instanceof \DateTimeInterface) {
        $value = $time->format('H:i');
    } else {
        $value = substr((string)$time, 0, 5);
    }

    if (!preg_match('/^\d{2}:\d{2}$/', $value)) {
        return $startTime === null ? '09:00' : '17:00';
    }

    [$hour, $minute] = array_map('intval', explode(':', $value));
    $minute = $minute < 15 ? 0 : ($minute < 45 ? 30 : 0);
    if ($minute === 0 && (int)substr($value, 3, 2) >= 45) {
        $hour++;
    }
    $hour %= 24;
    $value = sprintf('%02d:%02d', $hour, $minute);

    if ($startTime !== null && $value <= $startTime && $hour < 12) {
        $value = sprintf('%02d:%02d', $hour + 12, $minute);
    }

    return $value;
};
?>
<div class="rounded-2xl border border-neutral-200/70 bg-white/90 p-3 shadow-lg shadow-neutral-900/5 backdrop-blur-xl md:p-5">
    <h1 class="font-serif text-xl font-semibold text-neutral-900 md:text-lg">Khả dụng hàng tuần</h1>
    <p class="mt-2 max-w-2xl text-neutral-600">
        Đặt thời gian bạn thường có sẵn. Điều này tách biệt với các slot hội thảo đã xuất bản được quản lý bởi quản trị viên.
    </p>

    <?= $this->Form->create(null, ['url' => ['action' => 'saveAvailability'], 'id' => 'availability-form']) ?>
    <div class="mt-4 overflow-x-auto">
        <table class="min-w-full divide-y divide-neutral-200 text-left text-sm">
            <thead>
                <tr class="text-xs font-semibold uppercase tracking-wide text-neutral-500">
                    <th class="py-3 pr-4">Day</th>
                    <th class="py-3 pr-4">Start</th>
                    <th class="py-3 pr-4">End</th>
                    <th class="py-3 pr-4">Active</th>
                    <th class="py-3"></th>
                </tr>
            </thead>
            <tbody id="availability-rows" class="divide-y divide-neutral-100">
                <?php
                $i = 0;
                foreach ($slots as $s) :
                    ?>
                    <tr class="availability-row">
                        <td class="py-3 pr-2">
                            <select name="slots[<?= (int) $i ?>][day_of_week]" class="rounded-lg border border-neutral-300 px-2 py-2">
                                <?php for ($d = 0; $d <= 6; $d++): ?>
                                    <option value="<?= $d ?>" <?= ((int) $s->day_of_week === $d) ? 'selected' : '' ?>><?= h($dayNames[$d]) ?></option>
                                <?php endfor; ?>
                            </select>
                        </td>
                        <td class="py-3 pr-2">
                            <?php
                            $stVal = $normalizeTime($s->start_time);
                            $etVal = $normalizeTime($s->end_time, $stVal);
                            ?>
                            <select name="slots[<?= (int) $i ?>][start_time]" class="rounded-lg border border-neutral-300 px-2 py-2" required>
                                <?php foreach ($timeOptions as $value => $label): ?>
                                    <option value="<?= h($value) ?>" <?= $value === $stVal ? 'selected' : '' ?>><?= h($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td class="py-3 pr-2">
                            <select name="slots[<?= (int) $i ?>][end_time]" class="rounded-lg border border-neutral-300 px-2 py-2" required>
                                <?php foreach ($timeOptions as $value => $label): ?>
                                    <option value="<?= h($value) ?>" <?= $value === $etVal ? 'selected' : '' ?>><?= h($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td class="py-3 pr-2">
                            <input type="checkbox" name="slots[<?= (int) $i ?>][is_active]" value="1" <?= !empty($s->is_active) ? 'checked' : '' ?>>
                        </td>
                        <td class="py-3">
                            <button type="button" class="remove-slot text-sm text-red-600 hover:underline">Remove</button>
                        </td>
                    </tr>
                    <?php
                    $i++;
                endforeach;
                $slotIndex = $i;
                ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4 flex flex-wrap gap-3">
        <button type="button" id="add-slot-row" class="rounded-full border border-neutral-300 bg-white px-5 py-2 text-sm font-semibold text-neutral-800 hover:bg-neutral-50">
            Add row
        </button>
        <?= $this->Form->button('Save availability', [
            'class' => 'rounded-full bg-primary-600 px-3 py-2 text-sm font-semibold text-white hover:bg-primary-700',
        ]) ?>
    </div>
    <?= $this->Form->end() ?>

    <template id="slot-row-template">
        <tr class="availability-row">
            <td class="py-3 pr-2">
                <select name="slots[__I__][day_of_week]" class="rounded-lg border border-neutral-300 px-2 py-2">
                    <?php for ($d = 0; $d <= 6; $d++): ?>
                        <option value="<?= $d ?>"><?= h($dayNames[$d]) ?></option>
                    <?php endfor; ?>
                </select>
            </td>
            <td class="py-3 pr-2">
                <select name="slots[__I__][start_time]" class="rounded-lg border border-neutral-300 px-2 py-2" required>
                    <?php foreach ($timeOptions as $value => $label): ?>
                        <option value="<?= h($value) ?>" <?= $value === '09:00' ? 'selected' : '' ?>><?= h($label) ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td class="py-3 pr-2">
                <select name="slots[__I__][end_time]" class="rounded-lg border border-neutral-300 px-2 py-2" required>
                    <?php foreach ($timeOptions as $value => $label): ?>
                        <option value="<?= h($value) ?>" <?= $value === '17:00' ? 'selected' : '' ?>><?= h($label) ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td class="py-3 pr-2">
                <input type="checkbox" name="slots[__I__][is_active]" value="1" checked>
            </td>
            <td class="py-3">
                <button type="button" class="remove-slot text-sm text-red-600 hover:underline">Remove</button>
            </td>
        </tr>
    </template>
</div>

<script>
(function () {
    var tbody = document.getElementById('availability-rows');
    var template = document.getElementById('slot-row-template');
    var addBtn = document.getElementById('add-slot-row');
    var idx = <?= (int) ($slotIndex ?? 0) ?>;

    function bindRemove(row) {
        row.querySelector('.remove-slot').addEventListener('click', function () {
            row.remove();
        });
    }

    tbody.querySelectorAll('.availability-row').forEach(bindRemove);

    addBtn.addEventListener('click', function () {
        var html = template.innerHTML.replace(/__I__/g, String(idx++));
        var wrap = document.createElement('tbody');
        wrap.innerHTML = html.trim();
        var row = wrap.firstElementChild;
        tbody.appendChild(row);
        bindRemove(row);
    });
})();
</script>


