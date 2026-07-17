<?php
use Cake\Core\Configure;

$turnstileSiteKey = (string)(Configure::read('Captcha.turnstile.siteKey') ?? '');
$requireTurnstileToken = trim((string)(Configure::read('Captcha.turnstile.secretKey') ?? '')) !== '';
$showTurnstileUi = trim($turnstileSiteKey) !== '';

$this->Form->setTemplates([
    'inputContainer' => '<div class="cc-field">{{content}}</div>',
    'inputContainerError' => '<div class="cc-field">{{content}}{{error}}</div>',
    'label' => '<label{{attrs}}>{{text}}</label>',
    'input' => '<input type="{{type}}" name="{{name}}"{{attrs}}>',
    'textarea' => '<textarea name="{{name}}"{{attrs}}>{{value}}</textarea>',
]);
?>

<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>

<!-- Contact Modal -->
<div
    id="contact-modal"
    class="fixed inset-0 z-[110] hidden items-center justify-center bg-neutral-950/70 px-4 py-4 backdrop-blur-md transition-opacity duration-300 opacity-0 pointer-events-none sm:px-3"
    role="dialog"
    aria-modal="true"
    aria-labelledby="contact-modal-title"
    aria-describedby="contact-modal-desc"
>
    <div class="relative w-full max-w-lg max-h-[min(92vh,720px)] overflow-y-auto rounded-2xl border border-neutral-200/90 bg-white p-4 shadow-2xl shadow-neutral-900/20 sm:p-5 transform scale-[0.97] opacity-0 transition-all duration-300 ease-out">

        <button
            type="button"
            onclick="closeContactModal()"
            class="absolute right-4 top-4 flex h-10 w-10 items-center justify-center rounded-full text-neutral-500 transition-colors hover:bg-neutral-100 hover:text-neutral-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2"
            aria-label="Đóng biểu mẫu liên hệ"
        >
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <div class="pr-8">
            <p class="text-xs font-semibold uppercase tracking-[0.05em] text-primary-600">Liên hệ</p>
            <h2 id="contact-modal-title" class="mt-1 font-serif text-lg font-semibold tracking-tight text-ink-900 sm:text-xl">
                Liên hệ với chúng tôi
            </h2>
            <p id="contact-modal-desc" class="mt-2 text-base leading-relaxed text-neutral-600">
                Bạn có câu hỏi? Chúng tôi rất muốn nghe từ bạn.
            </p>
        </div>

        <?= $this->Form->create(null, [
            'url' => ['controller' => 'Enquiries', 'action' => 'add'],
            'id' => 'contact-enquiry-form',
            'class' => 'mt-4 space-y-5',
        ]) ?>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 sm:gap-x-5 sm:gap-y-0">
            <?= $this->Form->control('first_name', [
                'label' => 'Tên',
                'class' => 'cc-contact-input',
                'required' => true,
                'placeholder' => 'Tên của bạn',
            ]) ?>

            <?= $this->Form->control('last_name', [
                'label' => 'Họ',
                'class' => 'cc-contact-input',
                'required' => true,
                'placeholder' => 'Họ của bạn',
            ]) ?>
        </div>

        <?= $this->Form->control('email', [
            'label' => 'Địa chỉ email',
            'type' => 'email',
            'class' => 'cc-contact-input',
            'required' => true,
            'placeholder' => 'Email',
            'id' => 'cc-contact-email',
        ]) ?>
        <p id="cc-email-retry" class="cc-retry hidden" role="alert">Nhập email hợp lệ.</p>

        <?= $this->Form->control('phone', [
            'label' => 'Số điện thoại',
            'type' => 'tel',
            'class' => 'cc-contact-input',
            'required' => true,
            'inputmode' => 'tel',
            'autocomplete' => 'tel',
            'placeholder' => '+84 912 345 678',
            'id' => 'cc-contact-phone',
            'pattern' => '^\+?[0-9 ()-]{8,20}$',
            'title' => 'Sử dụng định dạng số điện thoại Việt Nam (ví dụ: +84 912 345 678)',
        ]) ?>
        <p id="cc-phone-retry" class="cc-retry hidden" role="alert">Nhập số điện thoại hợp lệ.</p>

        <?= $this->Form->control('subject', [
            'label' => 'Chủ đề',
            'class' => 'cc-contact-input',
            'required' => true,
            'placeholder' => 'Hỏi về hội thảo, lớp riêng, v.v.',
        ]) ?>

        <?= $this->Form->control('message', [
            'label' => 'Tin nhắn của bạn',
            'type' => 'textarea',
            'rows' => 5,
            'class' => 'cc-contact-input',
            'placeholder' => 'Cho chúng tôi biết thêm về những gì bạn đang tìm kiếm...',
        ]) ?>

        <section
            class="mt-2 space-y-4 border-t border-neutral-100 pt-6"
            <?= $showTurnstileUi ? 'aria-labelledby="cc-verify-heading"' : '' ?>
        >
            <?php if ($showTurnstileUi): ?>
            <div class="rounded-2xl border border-neutral-200/90 bg-gradient-to-b from-neutral-50/90 to-white p-5 shadow-sm ring-1 ring-neutral-900/[0.04] sm:p-3">
                <div class="flex gap-4">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary-500/10 text-primary-700" aria-hidden="true">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1 space-y-1">
                        <h3 id="cc-verify-heading" class="text-sm font-semibold leading-snug text-neutral-900">
                            Kiểm tra bảo mật nhanh
                        </h3>
                        <p class="text-sm leading-relaxed text-neutral-600">
                            Một bước nhanh giúp giữ hộp thư của chúng tôi an toàn khỏi bot để chúng tôi có thể trả lời tin nhắn thực của bạn.
                        </p>
                    </div>
                </div>

                <div class="mt-5 rounded-xl border border-dashed border-neutral-200 bg-white/80 px-3 py-4 sm:px-4">
                    <div id="cf-turnstile" class="flex min-h-[65px] items-center justify-center sm:justify-start"></div>
                    <p id="cf-turnstile-unconfigured" class="hidden text-center text-sm text-amber-900 sm:text-left">
                        <span class="font-medium">Verification isn't set up on this server.</span>
                        <span class="mt-1 block text-amber-800/90">Add <code class="rounded bg-amber-100/80 px-1 py-0.5 text-xs font-mono text-amber-950">Captcha.turnstile.siteKey</code> in app config.</span>
                    </p>
                </div>
            </div>

            <blockquote id="turnstile-message" class="hidden rounded-xl border border-amber-200/90 bg-amber-50/90 px-4 py-3 text-sm leading-relaxed text-amber-950"></blockquote>

            <p id="turnstile-error" class="hidden text-sm font-medium text-red-600" role="alert"></p>
            <?php endif; ?>

            <div class="space-y-3">
                <p id="submit-hint-pending" class="flex items-start gap-2.5 text-sm text-neutral-600">
                    <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-neutral-200/80 text-neutral-600" aria-hidden="true">
                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 12 12"><path d="M6 1a5 5 0 100 10A5 5 0 006 1zm0 1.5a.75.75 0 110 1.5.75.75 0 010-1.5zM5.25 5.25h1.5v3h-1.5v-3z"/></svg>
                    </span>
                    <span id="submit-hint-text">Hoàn thành kiểm tra ở trên, sau đó nhấn <span class="font-medium text-neutral-800">Gửi tin nhắn</span>.</span>
                </p>
                <p id="submit-hint-ready" class="hidden items-start gap-2.5 text-sm text-emerald-800">
                    <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-700" aria-hidden="true">
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </span>
                    <span>Bạn đã xác minh. Gửi tin nhắn của bạn bất cứ khi nào bạn sẵn sàng.</span>
                </p>

                <p id="cc-form-error" class="hidden rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700" role="alert"></p>

                <?= $this->Form->button(
                    '<span class="inline-flex items-center justify-center gap-2"><svg class="h-5 w-5 opacity-95" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg><span>Gửi tin nhắn</span></span>',
                    [
                        'class' => 'group relative w-full overflow-hidden rounded-xl bg-primary-600 py-3.5 text-base font-semibold text-white shadow-md shadow-primary-900/10 transition hover:bg-primary-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-[0.45] disabled:shadow-none',
                        'id' => 'submit-btn',
                        'disabled' => true,
                        'escapeTitle' => false,
                    ]
                ) ?>
            </div>
        </section>

        <?= $this->Form->end() ?>
    </div>
</div>

<script>
(function () {
    window.CC_TURNSTILE_SITEKEY = <?= json_encode($turnstileSiteKey) ?>;
    window.CC_TURNSTILE_REQUIRE_TOKEN = <?= json_encode($requireTurnstileToken) ?>;

    var turnstileMessageBlock = document.querySelector('#turnstile-message');
    var submitButton = document.getElementById('submit-btn');
    var submitHintPending = document.getElementById('submit-hint-pending');
    var submitHintReady = document.getElementById('submit-hint-ready');
    var formError = document.getElementById('cc-form-error');
    var ccTurnstileWidgetId = null;
    var verifiedOk = false;
    var contactForm = null;
    var emailInput = null;
    var phoneInput = null;
    var emailRetry = null;
    var phoneRetry = null;

    function setSubmitHints(on) {
        if (!submitHintPending || !submitHintReady) return;
        if (!window.CC_TURNSTILE_REQUIRE_TOKEN) {
            submitHintPending.classList.add('hidden');
            submitHintReady.classList.add('hidden');
            return;
        }
        if (!window.CC_TURNSTILE_SITEKEY) {
            submitHintPending.classList.add('hidden');
            submitHintReady.classList.add('hidden');
            return;
        }
        if (on) {
            submitHintPending.classList.add('hidden');
            submitHintReady.classList.remove('hidden');
            submitHintReady.classList.add('flex');
        } else {
            submitHintReady.classList.add('hidden');
            submitHintReady.classList.remove('flex');
            submitHintPending.classList.remove('hidden');
        }
    }

    function setSubmitEnabled(on) {
        if (!submitButton) return;
        if (on) {
            submitButton.removeAttribute('disabled');
        } else {
            submitButton.setAttribute('disabled', 'disabled');
        }
        setSubmitHints(on);
    }

    function setFormError(msg) {
        if (!formError) return;
        var text = (msg || '').trim();
        if (text) {
            formError.textContent = text;
            formError.classList.remove('hidden');
        } else {
            formError.textContent = '';
            formError.classList.add('hidden');
        }
    }

    function setSubmitOutlineInvalid(on) {
        if (!submitButton) return;
        submitButton.classList.toggle('cc-btn-invalid', Boolean(on));
    }

    function setRetry(input, retryEl) {
        if (!input || !retryEl) return;
        var invalid = !input.checkValidity();
        input.classList.toggle('is-invalid', invalid);
        retryEl.classList.toggle('hidden', !invalid);
        return !invalid;
    }

    function isValidAuPhone(value) {
        var v = (value || '').trim();
        if (!v) return false;
        if (!/^\+?[0-9 ()-]{8,20}$/.test(v)) return false;
        var digits = v.replace(/\D+/g, '');

        var nsn = '';
        if (digits.indexOf('84') === 0) {
            nsn = digits.slice(2);
        } else if (digits.indexOf('0') === 0) {
            nsn = digits.slice(1);
        } else {
            return false;
        }

        if (nsn.length !== 9) return false;
        return /^[35789]\d{8}$/.test(nsn);
    }

    function validateFields(showUi) {
        if (!emailInput || !phoneInput) return true;

        if (phoneInput) {
            phoneInput.setCustomValidity(isValidAuPhone(phoneInput.value) ? '' : 'Số điện thoại không hợp lệ');
        }

        var okEmail = showUi ? setRetry(emailInput, emailRetry) : emailInput.checkValidity();
        var okPhone = showUi ? setRetry(phoneInput, phoneRetry) : phoneInput.checkValidity();
        return Boolean(okEmail && okPhone);
    }

    function updateSubmitState() {
        var canClick = (!window.CC_TURNSTILE_REQUIRE_TOKEN || verifiedOk);
        setSubmitEnabled(canClick);
        setSubmitOutlineInvalid(false);
    }

    function hideTurnstileError() {
        var err = document.getElementById('turnstile-error');
        if (err) {
            err.textContent = '';
            err.classList.add('hidden');
        }
    }

    function showTurnstileError(msg) {
        var err = document.getElementById('turnstile-error');
        if (err) {
            err.textContent = msg;
            err.classList.remove('hidden');
        }
    }

    window.turnstileOnSuccess = function () {
        if (turnstileMessageBlock) turnstileMessageBlock.classList.add('hidden');
        hideTurnstileError();
        verifiedOk = true;
        updateSubmitState();
    };

    window.turnstileOnError = function () {
        if (turnstileMessageBlock) {
            turnstileMessageBlock.classList.remove('hidden');
            turnstileMessageBlock.textContent = 'Xác minh không thể tải. Vui lòng làm mới trang và thử lại.';
        }
        if (window.CC_TURNSTILE_REQUIRE_TOKEN) {
            verifiedOk = false;
            updateSubmitState();
        }
    };

    window.turnstileOnExpired = function () {
        if (turnstileMessageBlock) {
            turnstileMessageBlock.classList.remove('hidden');
            turnstileMessageBlock.textContent = 'Xác minh đã hết hạn. Vui lòng xác minh lại.';
        }
        if (window.CC_TURNSTILE_REQUIRE_TOKEN) {
            verifiedOk = false;
            updateSubmitState();
        }
    };

    window.turnstileOnTimeout = function () {
        if (turnstileMessageBlock) {
            turnstileMessageBlock.classList.remove('hidden');
            turnstileMessageBlock.textContent = 'Xác minh hết thời gian. Vui lòng xác minh lại.';
        }
        if (window.CC_TURNSTILE_REQUIRE_TOKEN) {
            verifiedOk = false;
            updateSubmitState();
        }
    };

    function renderTurnstile() {
        if (!window.CC_TURNSTILE_SITEKEY) {
            var miss = document.getElementById('cf-turnstile-unconfigured');
            if (miss && window.CC_TURNSTILE_REQUIRE_TOKEN) {
                miss.classList.remove('hidden');
            }
            if (window.CC_TURNSTILE_REQUIRE_TOKEN) {
                verifiedOk = false;
                updateSubmitState();
            } else {
                verifiedOk = true;
                updateSubmitState();
            }
            return;
        }

        if (!window.CC_TURNSTILE_REQUIRE_TOKEN) {
            verifiedOk = true;
            updateSubmitState();
        }

        if (typeof window.turnstile === 'undefined') {
            setTimeout(renderTurnstile, 50);
            return;
        }

        var host = document.getElementById('cf-turnstile');
        if (!host) return;

        if (ccTurnstileWidgetId !== null) {
            window.turnstile.reset(ccTurnstileWidgetId);
            verifiedOk = !window.CC_TURNSTILE_REQUIRE_TOKEN;
            updateSubmitState();
            return;
        }

        ccTurnstileWidgetId = window.turnstile.render('#cf-turnstile', {
            sitekey: window.CC_TURNSTILE_SITEKEY,
            theme: 'light',
            callback: window.turnstileOnSuccess,
            'error-callback': window.turnstileOnError,
            'expired-callback': window.turnstileOnExpired,
            'timeout-callback': window.turnstileOnTimeout,
        });
        if (window.CC_TURNSTILE_REQUIRE_TOKEN) {
            verifiedOk = false;
            updateSubmitState();
        } else {
            verifiedOk = true;
            updateSubmitState();
        }
    }

    window.openContactModal = function () {
        var modal = document.getElementById('contact-modal');
        if (!modal) return;

        modal.classList.remove('hidden', 'opacity-0', 'pointer-events-none');
        modal.classList.add('flex', 'opacity-100', 'pointer-events-auto');

        var inner = modal.querySelector('div.relative');
        if (inner) {
            inner.style.transform = 'scale(1)';
            inner.style.opacity = '1';
        }

        document.body.style.overflow = 'hidden';

        requestAnimationFrame(function () {
            setTimeout(renderTurnstile, 80);
        });
    };

    window.closeContactModal = function () {
        var modal = document.getElementById('contact-modal');
        if (!modal) return;

        if (ccTurnstileWidgetId !== null && window.turnstile) {
            try {
                window.turnstile.reset(ccTurnstileWidgetId);
            } catch (e) {}
        }
        verifiedOk = false;
        setFormError('');
        updateSubmitState();
        hideTurnstileError();
        if (turnstileMessageBlock) turnstileMessageBlock.classList.add('hidden');

        var inner = modal.querySelector('div.relative');
        if (inner) {
            inner.style.transform = 'scale(0.97)';
            inner.style.opacity = '0';
        }

        modal.classList.remove('flex', 'opacity-100', 'pointer-events-auto');
        modal.classList.add('opacity-0', 'pointer-events-none');

        setTimeout(function () {
            modal.classList.add('hidden');
            if (typeof window.__ccSyncBodyOverflow === 'function') {
                window.__ccSyncBodyOverflow();
            } else {
                document.body.style.overflow = 'auto';
            }
        }, 300);
    };

    document.addEventListener('DOMContentLoaded', function () {
        contactForm = document.getElementById('contact-enquiry-form');
        emailInput = document.getElementById('cc-contact-email');
        phoneInput = document.getElementById('cc-contact-phone');
        emailRetry = document.getElementById('cc-email-retry');
        phoneRetry = document.getElementById('cc-phone-retry');

        function wire(input, retryEl) {
            if (!input) return;
            ['blur', 'input'].forEach(function (evt) {
                input.addEventListener(evt, function () {
                    setRetry(input, retryEl);
                    setFormError('');
                    setSubmitOutlineInvalid(false);
                    updateSubmitState();
                });
            });
        }

        wire(emailInput, emailRetry);
        wire(phoneInput, phoneRetry);

        updateSubmitState();

        if (contactForm) {
            contactForm.addEventListener('submit', function (e) {
                var ok = validateFields(true);
                updateSubmitState();
                if (!ok) {
                    e.preventDefault();
                    setFormError('Vui lòng kiểm tra email và số điện thoại của bạn, sau đó thử lại.');
                    setSubmitOutlineInvalid(true);
                    return false;
                }
                setFormError('');
                setSubmitOutlineInvalid(false);

                if (!window.CC_TURNSTILE_REQUIRE_TOKEN) {
                    hideTurnstileError();
                    return;
                }
                var input = contactForm.querySelector('input[name="cf-turnstile-response"]');
                var token = input && input.value ? input.value.trim() : '';
                if (!token) {
                    e.preventDefault();
                    showTurnstileError('Vui lòng hoàn thành xác minh trước khi gửi tin nhắn của bạn.');
                    setSubmitOutlineInvalid(true);
                    return false;
                }
                hideTurnstileError();
                setSubmitOutlineInvalid(false);
            });
        }

        document.addEventListener('click', function (e) {
            var modal = document.getElementById('contact-modal');
            if (modal && e.target === modal) {
                window.closeContactModal();
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                var modal = document.getElementById('contact-modal');
                if (modal && modal.classList.contains('flex')) {
                    window.closeContactModal();
                }
            }
        });
    });
})();
</script>

<style>
    #contact-modal .cc-retry {
        margin-top: 0.35rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: #dc2626;
    }

    #contact-modal .cc-field {
        display: flex;
        flex-direction: column;
        gap: 0.375rem;
    }

    #contact-modal .cc-field > label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #171717;
        letter-spacing: 0.01em;
    }

    #contact-modal .cc-contact-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #d4d4d4;
        border-radius: 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        background: #ffffff;
        color: #171717;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    #contact-modal .cc-contact-input::placeholder {
        color: #737373;
    }

    #contact-modal .cc-field textarea {
        min-height: 7.5rem;
        resize: vertical;
    }

    #contact-modal .cc-contact-input:hover {
        border-color: #a3a3a3;
    }

    #contact-modal .cc-contact-input:focus {
        outline: none;
        border-color: #752f3f;
        box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.25);
    }

    #contact-modal .cc-contact-input.is-invalid {
        border-color: #f87171;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.12);
    }

    #contact-modal .error-message,
    #contact-modal .invalid-feedback {
        font-size: 0.8125rem;
        font-weight: 500;
        color: #dc2626;
        margin-top: 0.25rem;
    }

    #contact-modal #submit-btn.cc-btn-invalid {
        outline: 2px solid #dc2626;
        outline-offset: 2px;
    }
</style>
