<?php
$this->assign('title', 'Đăng nhập | Hội Nghệ Thuật Nến');
?>

<div class="relative isolate flex min-h-[calc(100dvh-5.5rem)] items-center justify-center overflow-hidden bg-gradient-to-br from-neutral-50 via-primary-50/35 to-neutral-100 py-3 px-4 sm:px-3">
    <div class="pointer-events-none absolute inset-0 overflow-hidden" aria-hidden="true">
        <div class="absolute -left-32 top-1/4 h-72 w-72 rounded-full bg-primary-200/30 blur-3xl"></div>
        <div class="absolute -right-24 bottom-1/4 h-80 w-80 rounded-full bg-primary-300/20 blur-3xl"></div>
        <div class="absolute left-1/2 top-0 h-px w-[min(100%,48rem)] -translate-x-1/2 bg-gradient-to-r from-transparent via-primary-200/80 to-transparent"></div>
    </div>

    <div class="relative z-10 w-full max-w-[420px] animate-fade-in-up">
        <div class="rounded-2xl border border-neutral-200/90 bg-white/95 p-4 shadow-lift shadow-neutral-900/[0.06] backdrop-blur-xl sm:p-5 md:p-11">

            <div class="text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-primary-100 to-primary-50 text-primary-600 ring-1 ring-primary-200/70">
                    <?= $this->element('ui_icon', ['name' => 'sparkles', 'class' => 'h-7 w-7']) ?>
                </div>
                <h1 class="mt-3 font-serif text-xl font-light tracking-tight text-ink-900 sm:text-lg">
                    Chào mừng trở lại
                </h1>
                <p class="mt-2 text-[15px] leading-relaxed text-neutral-600">
                    Đăng nhập để quản lý đặt chỗ và tiếp tục hành trình sáng tạo của bạn.
                </p>
            </div>

            <div id="auth-alert" class="<?= !empty($lockMessage) ? '' : 'hidden' ?> mt-4 rounded-2xl border border-red-100 bg-red-50/90 px-4 py-3 text-sm font-medium text-red-800" role="alert" aria-live="polite">
                <?php if (!empty($lockMessage)) : ?>
                    <?= h($lockMessage) ?>
                <?php endif; ?>
            </div>

            <?= $this->Form->create(null, ['class' => 'mt-4 space-y-3', 'id' => 'loginForm']) ?>
            <?php
            $redirectTarget = $redirectTarget ?? '';
            if ($redirectTarget !== '') {
                echo $this->Form->hidden('redirect', ['value' => $redirectTarget]);
            }
            ?>

            <div class="space-y-2">
                <label for="email" class="sr-only">Email</label>
                <div class="relative">
                    <span class="pointer-events-none absolute left-4 top-1/2 z-10 -translate-y-1/2 text-neutral-400">
                        <?= $this->element('ui_icon', ['name' => 'envelope', 'class' => 'h-5 w-5']) ?>
                    </span>
                    <?= $this->Form->email('email', [
                        'placeholder' => 'ban@vidu.com',
                        'class' => 'w-full rounded-2xl border border-neutral-200 bg-neutral-50/40 py-4 pl-12 pr-4 text-[15px] text-neutral-900 placeholder:text-neutral-400 transition-all duration-200 focus:border-primary-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary-100',
                        'required' => true,
                        'autocomplete' => 'email',
                        'id' => 'email',
                    ]) ?>
                </div>
            </div>

            <div class="space-y-2">
                <div class="flex items-center justify-between gap-3 px-0.5">
                    <label for="password" class="text-sm font-medium text-neutral-700">Mật khẩu</label>
                    <?= $this->Html->link(
                        'Quên mật khẩu?',
                        ['action' => 'forgotPassword'],
                        ['class' => 'text-sm font-semibold text-primary-600 transition-colors hover:text-primary-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 rounded-sm']
                    ) ?>
                </div>
                <div class="relative">
                    <?= $this->Form->password('password', [
                        'placeholder' => 'Nhập mật khẩu của bạn',
                        'class' => 'w-full rounded-2xl border border-neutral-200 bg-neutral-50/40 py-4 pl-4 pr-14 text-[15px] text-neutral-900 placeholder:text-neutral-400 transition-all duration-200 focus:border-primary-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary-100',
                        'required' => true,
                        'autocomplete' => 'current-password',
                        'id' => 'password',
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
            </div>

            <div>
                <?= $this->Form->button('<span class="js-btn-label">Đăng nhập</span>', [
                    'class' => 'flex w-full items-center justify-center rounded-2xl bg-primary-600 py-4 text-[15px] font-semibold text-white shadow-md shadow-primary-900/10 transition-all duration-200 hover:bg-primary-700 hover:shadow-lg hover:shadow-primary-900/15 active:scale-[0.99] focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-70',
                    'id' => 'submitBtn',
                    'escapeTitle' => false,
                ]) ?>
            </div>

            <?= $this->Form->end() ?>

            <p class="mt-4 text-center text-sm text-neutral-600">
                Mới đến với Hội Nghệ Thuật Nến?
                <?php
                $registerUrl = ['action' => 'register'];
                if ($redirectTarget !== '') {
                    $registerUrl['?'] = ['redirect' => $redirectTarget];
                }
                echo $this->Html->link('Tạo tài khoản', $registerUrl, [
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
    const form = document.getElementById('loginForm');
    const submitBtn = document.getElementById('submitBtn');
    const emailInput = document.getElementById('email');

    if (form && submitBtn) {
        form.addEventListener('submit', function() {
            if (typeof form.reportValidity === 'function' && !form.reportValidity()) {
                return;
            }
            submitBtn.disabled = true;
            submitBtn.setAttribute('aria-busy', 'true');
            const label = submitBtn.querySelector('.js-btn-label');
            if (label) {
                label.textContent = 'Đang đăng nhập…';
            } else {
                submitBtn.textContent = 'Đang đăng nhập…';
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

