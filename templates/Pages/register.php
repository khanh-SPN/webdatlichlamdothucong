<?php
$this->assign('title', 'Tạo tài khoản | Hội Nghệ Thuật Nến');

$redirectTarget = $redirectTarget ?? '';
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

<div class="relative isolate flex min-h-[calc(100dvh-5.5rem)] items-center justify-center overflow-hidden bg-gradient-to-br from-neutral-50 via-primary-50/35 to-neutral-100 py-3 px-4 sm:px-3">
    <div class="pointer-events-none absolute inset-0 overflow-hidden" aria-hidden="true">
        <div class="absolute -left-32 top-1/4 h-72 w-72 rounded-full bg-primary-200/30 blur-3xl"></div>
        <div class="absolute -right-24 bottom-1/4 h-80 w-80 rounded-full bg-primary-300/20 blur-3xl"></div>
        <div class="absolute left-1/2 top-0 h-px w-[min(100%,48rem)] -translate-x-1/2 bg-gradient-to-r from-transparent via-primary-200/80 to-transparent"></div>
    </div>

    <div class="relative z-10 w-full max-w-[480px] animate-fade-in-up">
        <div class="rounded-2xl border border-neutral-200/90 bg-white/95 p-4 shadow-lift shadow-neutral-900/[0.06] backdrop-blur-xl sm:p-5 md:p-11">

            <div class="text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-primary-100 to-primary-50 text-primary-600 ring-1 ring-primary-200/70">
                    <?= $this->element('ui_icon', ['name' => 'sparkles', 'class' => 'h-7 w-7']) ?>
                </div>
                <h1 class="mt-3 font-serif text-xl font-light tracking-tight text-ink-900 sm:text-lg">
                    Tạo tài khoản của bạn
                </h1>
                <p class="mt-2 text-[15px] leading-relaxed text-neutral-600">
                    Thêm chi tiết liên hệ của bạn để chúng tôi có thể liên hệ về đặt chỗ và cập nhật lớp. Thông tin của bạn chỉ hiển thị cho quản trị viên để hỗ trợ.
                </p>
            </div>

            <?= $this->Form->create($user, ['class' => 'mt-4 space-y-5', 'id' => 'registerForm']) ?>
            <?php if ($redirectTarget !== '') : ?>
                <?= $this->Form->hidden('redirect', ['value' => $redirectTarget]) ?>
            <?php endif; ?>

            <div class="space-y-1.5">
                <label for="reg-full-name" class="block text-sm font-medium text-neutral-700">Họ và tên <span class="text-red-500" aria-hidden="true">*</span></label>
                <?= $this->Form->text('full_name', [
                    'id' => 'reg-full-name',
                    'required' => true,
                    'autocomplete' => 'name',
                    'value' => $customer?->name ?? '',
                    'class' => 'w-full rounded-2xl border border-neutral-200 bg-neutral-50/40 px-4 py-3.5 text-[15px] text-neutral-900 placeholder:text-neutral-400 transition-all duration-200 focus:border-primary-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary-100' . ($firstError($customer, 'name') ? ' border-red-400 ring-2 ring-red-100' : ''),
                    'placeholder' => 'ví dụ: Nguyễn Văn A',
                ]) ?>
                <?php if ($e = $firstError($customer, 'name')) : ?>
                    <p class="text-sm font-medium text-red-600" role="alert"><?= h($e) ?></p>
                <?php endif; ?>
            </div>

            <div class="space-y-1.5">
                <label for="reg-phone" class="block text-sm font-medium text-neutral-700">Số điện thoại <span class="text-red-500" aria-hidden="true">*</span></label>
                <?= $this->Form->tel('phone', [
                    'id' => 'reg-phone',
                    'required' => true,
                    'autocomplete' => 'tel',
                    'inputmode' => 'tel',
                    'value' => $customer?->phone ?? '',
                    'class' => 'w-full rounded-2xl border border-neutral-200 bg-neutral-50/40 px-4 py-3.5 text-[15px] text-neutral-900 placeholder:text-neutral-400 transition-all duration-200 focus:border-primary-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary-100' . ($firstError($customer, 'phone') ? ' border-red-400 ring-2 ring-red-100' : ''),
                    'placeholder' => 'ví dụ: +84 912 345 678',
                ]) ?>
                <?php if ($e = $firstError($customer, 'phone')) : ?>
                    <p class="text-sm font-medium text-red-600" role="alert"><?= h($e) ?></p>
                <?php endif; ?>
            </div>

            <div class="space-y-1.5">
                <label for="reg-address" class="block text-sm font-medium text-neutral-700">Địa chỉ <span class="text-neutral-400 font-normal">(tùy chọn)</span></label>
                <?= $this->Form->textarea('address', [
                    'id' => 'reg-address',
                    'rows' => 2,
                    'autocomplete' => 'street-address',
                    'value' => $customer?->address ?? '',
                    'class' => 'w-full resize-y rounded-2xl border border-neutral-200 bg-neutral-50/40 px-4 py-3.5 text-[15px] text-neutral-900 placeholder:text-neutral-400 transition-all duration-200 focus:border-primary-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary-100',
                    'placeholder' => 'Quận/huyện hoặc địa chỉ thư (giúp thông tin lớp học địa phương)',
                ]) ?>
                <?php if ($e = $firstError($customer, 'address')) : ?>
                    <p class="text-sm font-medium text-red-600" role="alert"><?= h($e) ?></p>
                <?php endif; ?>
            </div>

            <div class="space-y-1.5 pt-1">
                <label for="reg-email" class="block text-sm font-medium text-neutral-700">Email <span class="text-red-500" aria-hidden="true">*</span></label>
                <div class="relative">
                    <span class="pointer-events-none absolute left-4 top-1/2 z-10 -translate-y-1/2 text-neutral-400">
                        <?= $this->element('ui_icon', ['name' => 'envelope', 'class' => 'h-5 w-5']) ?>
                    </span>
                    <?= $this->Form->email('email', [
                        'id' => 'reg-email',
                        'required' => true,
                        'autocomplete' => 'email',
                        'class' => 'w-full rounded-2xl border border-neutral-200 bg-neutral-50/40 py-3.5 pl-12 pr-4 text-[15px] text-neutral-900 placeholder:text-neutral-400 transition-all duration-200 focus:border-primary-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary-100',
                        'placeholder' => 'ban@vidu.com',
                    ]) ?>
                </div>
                <?php
                foreach ($user->getError('email') ?? [] as $msg) :
                    $text = is_array($msg) ? (string) reset($msg) : (string) $msg;
                    if ($text !== '') :
                        ?>
                    <p class="text-sm font-medium text-red-600" role="alert"><?= h($text) ?></p>
                        <?php
                        break;
                    endif;
                endforeach;
                ?>
            </div>

            <div class="space-y-1.5">
                <label for="password" class="block text-sm font-medium text-neutral-700">Mật khẩu <span class="text-red-500" aria-hidden="true">*</span></label>
                <div class="relative">
                    <?= $this->Form->password('password', [
                        'id' => 'password',
                        'required' => true,
                        'autocomplete' => 'new-password',
                        'class' => 'w-full rounded-2xl border border-neutral-200 bg-neutral-50/40 py-3.5 pl-4 pr-14 text-[15px] text-neutral-900 placeholder:text-neutral-400 transition-all duration-200 focus:border-primary-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary-100',
                        'placeholder' => 'Tối thiểu 8 ký tự, bao gồm chữ hoa/thường, số, ký tự',
                    ]) ?>
                    <button type="button"
                            onclick="togglePassword('password', this)"
                            class="absolute right-3 top-1/2 -translate-y-1/2 rounded-xl p-2 text-neutral-400 transition-colors hover:bg-neutral-100 hover:text-neutral-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500"
                            aria-label="Hiện mật khẩu"
                            aria-pressed="false">
                        <span class="js-pwd-show block"><?= $this->element('ui_icon', ['name' => 'eye', 'class' => 'h-5 w-5']) ?></span>
                        <span class="js-pwd-hide hidden"><?= $this->element('ui_icon', ['name' => 'eye_slash', 'class' => 'h-5 w-5']) ?></span>
                    </button>
                </div>
                <?php if ($e = $firstError($user, 'password')) : ?>
                    <p class="text-sm font-medium text-red-600" role="alert"><?= h($e) ?></p>
                <?php endif; ?>
                <p class="text-xs text-neutral-500">Sử dụng ít nhất 8 ký tự với chữ hoa, chữ thường, số và ký tự.</p>
            </div>

            <div class="space-y-1.5">
                <label for="confirm_password" class="block text-sm font-medium text-neutral-700">Xác nhận mật khẩu <span class="text-red-500" aria-hidden="true">*</span></label>
                <div class="relative">
                    <?= $this->Form->password('confirm_password', [
                        'id' => 'confirm_password',
                        'required' => true,
                        'autocomplete' => 'new-password',
                        'class' => 'w-full rounded-2xl border border-neutral-200 bg-neutral-50/40 py-3.5 pl-4 pr-14 text-[15px] text-neutral-900 placeholder:text-neutral-400 transition-all duration-200 focus:border-primary-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary-100',
                        'placeholder' => 'Nhập lại mật khẩu',
                    ]) ?>
                    <button type="button"
                            onclick="togglePassword('confirm_password', this)"
                            class="absolute right-3 top-1/2 -translate-y-1/2 rounded-xl p-2 text-neutral-400 transition-colors hover:bg-neutral-100 hover:text-neutral-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500"
                            aria-label="Hiện mật khẩu"
                            aria-pressed="false">
                        <span class="js-pwd-show block"><?= $this->element('ui_icon', ['name' => 'eye', 'class' => 'h-5 w-5']) ?></span>
                        <span class="js-pwd-hide hidden"><?= $this->element('ui_icon', ['name' => 'eye_slash', 'class' => 'h-5 w-5']) ?></span>
                    </button>
                </div>
            </div>

            <div class="pt-2">
                <?= $this->Form->button('<span class="js-btn-label">Tạo tài khoản</span>', [
                    'class' => 'flex w-full items-center justify-center rounded-2xl bg-primary-600 py-4 text-[15px] font-semibold text-white shadow-md shadow-primary-900/10 transition-all duration-200 hover:bg-primary-700 hover:shadow-lg hover:shadow-primary-900/15 active:scale-[0.99] focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-70',
                    'id' => 'registerSubmitBtn',
                    'escapeTitle' => false,
                ]) ?>
            </div>

            <?= $this->Form->end() ?>

            <p class="mt-4 text-center text-sm text-neutral-600">
                Đã có tài khoản?
                <?php
                $loginUrl = ['action' => 'login'];
                if ($redirectTarget !== '') {
                    $loginUrl['?'] = ['redirect' => $redirectTarget];
                }
                echo $this->Html->link('Đăng nhập', $loginUrl, [
                    'class' => 'font-semibold text-primary-600 transition-colors hover:text-primary-700 focus:outline-none focus-visible:underline',
                ]);
                ?>
            </p>
        </div>
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
        btn.setAttribute('aria-label', 'Ẩn mật khẩu');
        btn.setAttribute('aria-pressed', 'true');
    } else {
        input.type = 'password';
        show.classList.remove('hidden');
        hide.classList.add('hidden');
        btn.setAttribute('aria-label', 'Hiện mật khẩu');
        btn.setAttribute('aria-pressed', 'false');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registerForm');
    const btn = document.getElementById('registerSubmitBtn');
    const emailInput = document.getElementById('reg-email');

    if (form && btn) {
        form.addEventListener('submit', function() {
            if (typeof form.reportValidity === 'function' && !form.reportValidity()) {
                return;
            }
            btn.disabled = true;
            btn.setAttribute('aria-busy', 'true');
            const label = btn.querySelector('.js-btn-label');
            if (label) {
                label.textContent = 'Đang tạo tài khoản…';
            }
        });
    }

    if (emailInput) {
        emailInput.addEventListener('blur', function() {
            if (this.value && !this.checkValidity()) {
                this.classList.add('border-red-400', 'ring-2', 'ring-red-100');
            } else {
                this.classList.remove('border-red-400', 'ring-2', 'ring-red-100');
            }
        });
    }
});
</script>

