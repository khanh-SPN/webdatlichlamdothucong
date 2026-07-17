<?php
$this->assign('title', 'Quên mật khẩu | Hội Nghệ Thuật Nến');
?>

<div class="relative isolate flex min-h-[calc(100dvh-5.5rem)] items-center justify-center overflow-hidden bg-gradient-to-br from-neutral-50 via-primary-50/35 to-neutral-100 py-3 px-4 sm:px-3">
    <div class="pointer-events-none absolute inset-0 overflow-hidden" aria-hidden="true">
        <div class="absolute -left-32 top-1/3 h-72 w-72 rounded-full bg-primary-200/30 blur-3xl"></div>
        <div class="absolute -right-20 bottom-1/3 h-72 w-72 rounded-full bg-primary-300/20 blur-3xl"></div>
        <div class="absolute left-1/2 top-0 h-px w-[min(100%,48rem)] -translate-x-1/2 bg-gradient-to-r from-transparent via-primary-200/80 to-transparent"></div>
    </div>

    <div class="relative z-10 w-full max-w-[420px] animate-fade-in-up">
        <div class="rounded-2xl border border-neutral-200/90 bg-white/95 p-4 shadow-lift shadow-neutral-900/[0.06] backdrop-blur-xl sm:p-5 md:p-11">

            <div class="text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-primary-100 to-primary-50 text-primary-600 ring-1 ring-primary-200/70">
                    <?= $this->element('ui_icon', ['name' => 'envelope', 'class' => 'h-7 w-7']) ?>
                </div>
                <h1 class="mt-3 font-serif text-xl font-light tracking-tight text-ink-900 sm:text-lg">
                    Đặt lại mật khẩu của bạn
                </h1>
                <p class="mt-2 text-[15px] leading-relaxed text-neutral-600">
                    Nhập email trên tài khoản của bạn. Chúng tôi sẽ gửi cho bạn một liên kết an toàn để chọn mật khẩu mới.
                </p>
            </div>

            <div class="mt-4 rounded-2xl border border-primary-100 bg-primary-50/50 px-4 py-3 text-sm leading-relaxed text-primary-900/90">
                <strong class="font-semibold text-primary-800">Mẹo:</strong> Kiểm tra thư rác nếu không có gì đến trong vài phút. Liên kết đặt lại hết hạn sau một giờ.
            </div>

            <?= $this->Form->create(null, ['class' => 'mt-4 space-y-3', 'id' => 'forgotForm']) ?>

            <div class="space-y-2">
                <label for="forgot-email" class="sr-only">Địa chỉ email</label>
                <div class="relative">
                    <span class="pointer-events-none absolute left-4 top-1/2 z-10 -translate-y-1/2 text-neutral-400">
                        <?= $this->element('ui_icon', ['name' => 'envelope', 'class' => 'h-5 w-5']) ?>
                    </span>
                    <?= $this->Form->email('email', [
                        'placeholder' => 'ban@vidu.com',
                        'class' => 'w-full rounded-2xl border border-neutral-200 bg-neutral-50/40 py-4 pl-12 pr-4 text-[15px] text-neutral-900 placeholder:text-neutral-400 transition-all duration-200 focus:border-primary-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary-100',
                        'required' => true,
                        'autocomplete' => 'email',
                        'id' => 'forgot-email',
                    ]) ?>
                </div>
            </div>

            <div>
                <?= $this->Form->button('<span class="js-btn-label">Gửi liên kết đặt lại</span>', [
                    'class' => 'flex w-full items-center justify-center rounded-2xl bg-primary-600 py-4 text-[15px] font-semibold text-white shadow-md shadow-primary-900/10 transition-all duration-200 hover:bg-primary-700 hover:shadow-lg hover:shadow-primary-900/15 active:scale-[0.99] focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-70',
                    'id' => 'forgotSubmitBtn',
                    'escapeTitle' => false,
                ]) ?>
            </div>

            <?= $this->Form->end() ?>

            <div class="mt-4 flex flex-col items-center gap-4 border-t border-neutral-100 pt-8 text-center text-sm">
                <?= $this->Html->link(
                    '<span class="inline-flex items-center gap-2 font-semibold text-neutral-700 transition-colors hover:text-primary-700">' .
                    $this->element('ui_icon', ['name' => 'chevron_left', 'class' => 'h-4 w-4']) .
                    '<span>Quay lại đăng nhập</span></span>',
                    ['action' => 'login'],
                    ['class' => 'inline-flex rounded-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2', 'escape' => false]
                ) ?>
                <p class="text-neutral-600">
                    Chưa có tài khoản?
                    <?= $this->Html->link('Đăng ký', ['action' => 'register'], [
                        'class' => 'font-semibold text-primary-600 transition-colors hover:text-primary-700',
                    ]) ?>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('forgotForm');
    const btn = document.getElementById('forgotSubmitBtn');
    const emailInput = document.getElementById('forgot-email');

    if (form && btn) {
        form.addEventListener('submit', function() {
            if (typeof form.reportValidity === 'function' && !form.reportValidity()) {
                return;
            }
            btn.disabled = true;
            btn.setAttribute('aria-busy', 'true');
            const label = btn.querySelector('.js-btn-label');
            if (label) {
                label.textContent = 'Đang gửi…';
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

