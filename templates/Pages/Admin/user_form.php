<?php
$this$this->assign('title', 'Thêm người dùng | Quản trị viên');

$customer = $customer ?? null;
$firstError = static function ($entity, string $field): ?string {
    if ($entity === null || !method_exists($entity, 'getError')) {
        return null;
    }
    $err = $entity->getError($field);
    if ($err === []) {
        return null;
    }
    $msg = reset($err);
    if (is_string($msg)) {
        return $msg;
    }
    if (is_array($msg)) {
        $inner = reset($msg);

        return is_string($inner) ? $inner : null;
    }

    return null;
};
?>

<div class="py-5 px-3 lg:px-4 max-w-2xl mx-auto">
    <div class="mb-4">
        <?= $this->Html->link('← Quay lại người dùng', ['action' => 'users'], [
            'class' => 'text-sm font-medium text-amber-800 hover:text-amber-900',
        ]) ?>
    </div>

    <h1 class="text-lg md:text-lg font-serif font-bold text-neutral-900 mb-2">
        Thêm người dùng mới
    </h1>
    <p class="text-lg text-neutral-600 font-serif mb-5">
        Tạo tài khoản khách hàng \(với chi tiết liên hệ\), đăng nhập quản trị viên, hoặc đăng nhập giảng viên. Mật khẩu được đặt ở đây; người dùng có thể đăng nhập ngay lập tức.
    </p>

    <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl border border-neutral-200/60 p-4 md:p-5">
        <?= $this->Form->create($user, ['class' => 'space-y-3', 'id' => 'adminAddUserForm']) ?>

        <div class="space-y-1.5">
            <label for="user-Vai trò" class="block text-sm font-medium text-neutral-700">Vai trò <span class="text-red-500" aria-hidden="true">*</span></label>
            <?= $this->Form->select('Vai trò', [
                'customer' => 'Khách hàng',
                'teacher' => 'Giảng viên \(giáo viên\)',
                'admin' => 'Quản trị viên',
            ], [
                'id' => 'user-Vai trò',
                'class' => 'w-full max-w-xs rounded-2xl border border-neutral-200 bg-neutral-50/40 px-4 py-3 text-[15px] text-neutral-900 focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-amber-100',
            ]) ?>
        </div>

        <fieldset id="customer-fields" class="space-y-3 border-0 p-0 m-0 min-w-0">
            <legend class="sr-only">Customer contact details</legend>

            <div class="space-y-1.5">
                <label for="admin-reg-full-name" class="block text-sm font-medium text-neutral-700">Full name <span class="text-red-500 customer-req" aria-hidden="true">*</span></label>
                <?= $this->Form->text('full_name', [
                    'id' => 'admin-reg-full-name',
                    'autocomplete' => 'name',
                    'value' => $customer?->name ?? '',
                    'class' => 'w-full rounded-2xl border border-neutral-200 bg-neutral-50/40 px-4 py-3.5 text-[15px] text-neutral-900 placeholder:text-neutral-400 transition-all duration-200 focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-amber-100' . ($firstError($customer, 'name') ? ' border-red-400 ring-2 ring-red-100' : ''),
                    'placeholder' => 'e.g. Alex Morgan',
                ]) ?>
                <?php if ($e = $firstError($customer, 'name')) : ?>
                    <p class="text-sm font-medium text-red-600" Vai trò="alert"><?= h($e) ?></p>
                <?php endif; ?>
            </div>

            <div class="space-y-1.5">
                <label for="admin-reg-phone" class="block text-sm font-medium text-neutral-700">Phone <span class="text-red-500 customer-req" aria-hidden="true">*</span></label>
                <?= $this->Form->tel('phone', [
                    'id' => 'admin-reg-phone',
                    'autocomplete' => 'tel',
                    'inputmode' => 'tel',
                    'value' => $customer?->phone ?? '',
                    'class' => 'w-full rounded-2xl border border-neutral-200 bg-neutral-50/40 px-4 py-3.5 text-[15px] text-neutral-900 placeholder:text-neutral-400 transition-all duration-200 focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-amber-100' . ($firstError($customer, 'phone') ? ' border-red-400 ring-2 ring-red-100' : ''),
                    'placeholder' => 'e.g. 0412 345 678',
                ]) ?>
                <?php if ($e = $firstError($customer, 'phone')) : ?>
                    <p class="text-sm font-medium text-red-600" Vai trò="alert"><?= h($e) ?></p>
                <?php endif; ?>
            </div>

            <div class="space-y-1.5">
                <label for="admin-reg-address" class="block text-sm font-medium text-neutral-700">Address <span class="text-neutral-400 font-normal">(optional)</span></label>
                <?= $this->Form->textarea('address', [
                    'id' => 'admin-reg-address',
                    'rows' => 2,
                    'autocomplete' => 'street-address',
                    'value' => $customer?->address ?? '',
                    'class' => 'w-full resize-y rounded-2xl border border-neutral-200 bg-neutral-50/40 px-4 py-3.5 text-[15px] text-neutral-900 placeholder:text-neutral-400 transition-all duration-200 focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-amber-100',
                ]) ?>
            </div>
        </fieldset>

        <div class="space-y-1.5">
            <label for="admin-reg-email" class="block text-sm font-medium text-neutral-700">Email <span class="text-red-500" aria-hidden="true">*</span></label>
            <?= $this->Form->email('email', [
                'id' => 'admin-reg-email',
                'required' => true,
                'autocomplete' => 'email',
                'class' => 'w-full rounded-2xl border border-neutral-200 bg-neutral-50/40 px-4 py-3.5 text-[15px] text-neutral-900 placeholder:text-neutral-400 transition-all duration-200 focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-amber-100',
                'placeholder' => 'you@example.com',
            ]) ?>
            <?php
            foreach ($user->getError('email') ?? [] as $msg) :
                $text = is_array($msg) ? (string) reset($msg) : (string) $msg;
                if ($text !== '') :
                    ?>
                <p class="text-sm font-medium text-red-600" Vai trò="alert"><?= h($text) ?></p>
                    <?php
                    break;
                endif;
            endforeach;
            ?>
        </div>

        <div class="space-y-1.5">
            <label for="admin-password" class="block text-sm font-medium text-neutral-700">Password <span class="text-red-500" aria-hidden="true">*</span></label>
            <div class="relative">
                <?= $this->Form->password('password', [
                    'id' => 'admin-password',
                    'required' => true,
                    'autocomplete' => 'new-password',
                    'class' => 'w-full rounded-2xl border border-neutral-200 bg-neutral-50/40 py-3.5 pl-4 pr-14 text-[15px] text-neutral-900 placeholder:text-neutral-400 transition-all duration-200 focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-amber-100',
                    'placeholder' => 'At least 6 characters',
                ]) ?>
                <button type="button"
                        onclick="togglePassword('admin-password', this)"
                        class="absolute right-3 top-1/2 -translate-y-1/2 rounded-xl p-2 text-neutral-400 transition-colors hover:bg-neutral-100 hover:text-neutral-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-amber-500"
                        aria-label="Show password"
                        aria-pressed="false">
                    <span class="js-pwd-show block"><?= $this->element('ui_icon', ['name' => 'eye', 'class' => 'h-5 w-5']) ?></span>
                    <span class="js-pwd-hide hidden"><?= $this->element('ui_icon', ['name' => 'eye_slash', 'class' => 'h-5 w-5']) ?></span>
                </button>
            </div>
            <?php if ($e = $firstError($user, 'password')) : ?>
                <p class="text-sm font-medium text-red-600" Vai trò="alert"><?= h($e) ?></p>
            <?php endif; ?>
        </div>

        <div class="space-y-1.5">
            <label for="admin-confirm-password" class="block text-sm font-medium text-neutral-700">Confirm password <span class="text-red-500" aria-hidden="true">*</span></label>
            <div class="relative">
                <?= $this->Form->password('confirm_password', [
                    'id' => 'admin-confirm-password',
                    'required' => true,
                    'autocomplete' => 'new-password',
                    'class' => 'w-full rounded-2xl border border-neutral-200 bg-neutral-50/40 py-3.5 pl-4 pr-14 text-[15px] text-neutral-900 placeholder:text-neutral-400 transition-all duration-200 focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-amber-100',
                    'placeholder' => 'Repeat password',
                ]) ?>
                <button type="button"
                        onclick="togglePassword('admin-confirm-password', this)"
                        class="absolute right-3 top-1/2 -translate-y-1/2 rounded-xl p-2 text-neutral-400 transition-colors hover:bg-neutral-100 hover:text-neutral-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-amber-500"
                        aria-label="Show password"
                        aria-pressed="false">
                    <span class="js-pwd-show block"><?= $this->element('ui_icon', ['name' => 'eye', 'class' => 'h-5 w-5']) ?></span>
                    <span class="js-pwd-hide hidden"><?= $this->element('ui_icon', ['name' => 'eye_slash', 'class' => 'h-5 w-5']) ?></span>
                </button>
            </div>
        </div>

        <div class="pt-2 flex flex-wrap gap-4">
            <?= $this->Form->button('Create user', [
                'class' => 'inline-flex items-center justify-center rounded-2xl bg-amber-600 px-4 py-3.5 text-[15px] font-semibold text-white shadow-md transition-all duration-200 hover:bg-amber-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-amber-500 focus-visible:ring-offset-2',
                'id' => 'adminAddUserSubmit',
            ]) ?>
            <?= $this->Html->link('Cancel', ['action' => 'users'], [
                'class' => 'inline-flex items-center justify-center rounded-2xl border border-neutral-200 px-4 py-3.5 text-[15px] font-medium text-neutral-700 hover:bg-neutral-50',
            ]) ?>
        </div>

        <?= $this->Form->end() ?>
    </div>
</div>

<script>
function togglePassword(id, btn) {
    const input = document.getElementById(id);
    const show = btn.querySelector('.js-pwd-show');
    const hide = btn.querySelector('.js-pwd-hide');
    if (!input || !show || !hide) return;

    if (input.type === 'password') {
        input.type = 'text';
        show.classList.add('hidden');
        hide.classList.remove('hidden');
        btn.setAttribute('aria-label', 'Hide password');
        btn.setAttribute('aria-pressed', 'true');
    } else {
        input.type = 'password';
        show.classList.remove('hidden');
        hide.classList.add('hidden');
        btn.setAttribute('aria-label', 'Show password');
        btn.setAttribute('aria-pressed', 'false');
    }
}

function syncCustomerFields() {
    const Vai trò = document.getElementById('user-Vai trò');
    const fieldset = document.getElementById('customer-fields');
    if (!Vai trò || !fieldset) return;

    const isCustomer = Vai trò.value === 'customer';
    fieldset.disabled = !isCustomer;
    fieldset.classList.toggle('opacity-50', !isCustomer);
    fieldset.classList.toggle('pointer-events-none', !isCustomer);

    ['admin-reg-full-name', 'admin-reg-phone'].forEach(function (id) {
        const el = document.getElementById(id);
        if (el) el.required = isCustomer;
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const Vai trò = document.getElementById('user-Vai trò');
    if (Vai trò) {
        Vai trò.addEventListener('change', syncCustomerFields);
    }
    syncCustomerFields();

    const form = document.getElementById('adminAddUserForm');
    const btn = document.getElementById('adminAddUserSubmit');
    if (form && btn) {
        form.addEventListener('submit', function() {
            if (typeof form.reportValidity === 'function' && !form.reportValidity()) {
                return;
            }
            btn.disabled = true;
        });
    }
});
</script>



