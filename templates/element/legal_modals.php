<?php
/**
 * Privacy Policy and Terms of Service dialogs (opened from footer links).
 */
?>

<!-- Privacy Policy -->
<div
    id="privacy-modal"
    class="fixed inset-0 z-[112] hidden items-center justify-center bg-neutral-950/70 px-4 py-4 backdrop-blur-md transition-opacity duration-300 opacity-0 pointer-events-none sm:px-3"
    role="dialog"
    aria-modal="true"
    aria-labelledby="privacy-modal-title"
>
    <div class="relative w-full max-w-2xl max-h-[min(92vh,760px)] overflow-y-auto rounded-2xl border border-neutral-200/90 bg-white p-4 shadow-2xl shadow-neutral-900/20 sm:p-5 transform scale-[0.97] opacity-0 transition-all duration-300 ease-out">
        <button
            type="button"
            onclick="closePrivacyModal()"
            class="absolute right-4 top-4 flex h-10 w-10 items-center justify-center rounded-full text-neutral-500 transition-colors hover:bg-neutral-100 hover:text-neutral-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2"
            aria-label="Close privacy policy"
        >
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <p class="text-xs font-semibold uppercase tracking-[0.05em] text-primary-600">Pháp lý</p>
        <h2 id="privacy-modal-title" class="mt-1 text-lg font-bold tracking-tight text-neutral-900 sm:text-xl">
            Chính sách Bảo mật
        </h2>
        <p class="mt-1 text-sm text-neutral-500">Cập nhật lần cuối: Tháng 4 năm 2026</p>

        <div class="mt-4 space-y-5 text-sm leading-relaxed text-neutral-600">
            <section>
                <h3 class="text-base font-semibold text-neutral-900">Tổng quan</h3>
                <p class="mt-2">
                    CandleCraft Academy (“we”, “us”) respects your privacy. This policy describes how we collect, use, and protect personal information when you use our website, book workshops, or contact us.
                </p>
            </section>
            <section>
                <h3 class="text-base font-semibold text-neutral-900">Thông tin chúng tôi thu thập</h3>
                <ul class="mt-2 list-disc space-y-1 pl-5">
                    <li>Chi tiết liên hệ bạn cung cấp (tên, email, điện thoại) khi bạn hỏi hoặc đặt chỗ.</li>
                    <li>Thông tin đặt chỗ và điểm danh liên quan đến các hội thảo bạn đăng ký.</li>
                    <li>Dữ liệu kỹ thuật như loại trình duyệt và khu vực xấp xỉ, nếu có, để giữ trang web an toàn.</li>
                </ul>
            </section>
            <section>
                <h3 class="text-base font-semibold text-neutral-900">Cách chúng tôi sử dụng thông tin</h3>
                <p class="mt-2">
                    Chúng tôi sử dụng thông tin này để phản hồi các yêu cầu, quản lý đặt chỗ, gửi tin nhắn liên quan đến dịch vụ, và cải thiện các hội thảo và trang web của chúng tôi. Chúng tôi không bán dữ liệu cá nhân của bạn.
                </p>
            </section>
            <section>
                <h3 class="text-base font-semibold text-neutral-900">Cookie và công nghệ tương tự</h3>
                <p class="mt-2">
                    Chúng tôi có thể sử dụng cookie hoặc bộ nhớ cục bộ khi cần thiết cho bảo mật, sở thích, hoặc phân tích. Bạn có thể kiểm soát cookie thông qua cài đặt trình duyệt của mình.
                </p>
            </section>
            <section>
                <h3 class="text-base font-semibold text-neutral-900">Lưu trữ và bảo mật</h3>
                <p class="mt-2">
                    Chúng tôi chỉ giữ dữ liệu cá nhân lâu như cần thiết cho các mục đích trên hoặc theo yêu cầu của pháp luật, và chúng tôi sử dụng các biện pháp hợp lý để bảo vệ nó.
                </p>
            </section>
            <section>
                <h3 class="text-base font-semibold text-neutral-900">Lựa chọn của bạn</h3>
                <p class="mt-2">
                    Bạn có thể yêu cầu truy cập hoặc sửa đổi thông tin cá nhân của mình, hoặc đặt câu hỏi về chính sách này, bằng cách liên hệ với chúng tôi tại chi tiết được hiển thị trong chân trang.
                </p>
            </section>
        </div>
    </div>
</div>

<!-- Terms of Service -->
<div
    id="terms-modal"
    class="fixed inset-0 z-[112] hidden items-center justify-center bg-neutral-950/70 px-4 py-4 backdrop-blur-md transition-opacity duration-300 opacity-0 pointer-events-none sm:px-3"
    role="dialog"
    aria-modal="true"
    aria-labelledby="terms-modal-title"
>
    <div class="relative w-full max-w-2xl max-h-[min(92vh,760px)] overflow-y-auto rounded-2xl border border-neutral-200/90 bg-white p-4 shadow-2xl shadow-neutral-900/20 sm:p-5 transform scale-[0.97] opacity-0 transition-all duration-300 ease-out">
        <button
            type="button"
            onclick="closeTermsModal()"
            class="absolute right-4 top-4 flex h-10 w-10 items-center justify-center rounded-full text-neutral-500 transition-colors hover:bg-neutral-100 hover:text-neutral-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2"
            aria-label="Close terms of service"
        >
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <p class="text-xs font-semibold uppercase tracking-[0.05em] text-primary-600">Legal</p>
        <h2 id="terms-modal-title" class="mt-1 text-lg font-bold tracking-tight text-neutral-900 sm:text-xl">
            Terms of Service
        </h2>
        <p class="mt-1 text-sm text-neutral-500">Last updated: April 2026</p>

        <div class="mt-4 space-y-5 text-sm leading-relaxed text-neutral-600">
            <section>
                <h3 class="text-base font-semibold text-neutral-900">Agreement</h3>
                <p class="mt-2">
                    By using this website or booking a workshop with CandleCraft Academy, you agree to these terms. If you do not agree, please do not use our services.
                </p>
            </section>
            <section>
                <h3 class="text-base font-semibold text-neutral-900">Workshops and bookings</h3>
                <p class="mt-2">
                    Workshop descriptions, schedules, and prices are offered in good faith and may change. A booking is confirmed only when we communicate confirmation according to our booking process (including any payment or deposit rules stated at checkout or by email).
                </p>
            </section>
            <section>
                <h3 class="text-base font-semibold text-neutral-900">Conduct and safety</h3>
                <p class="mt-2">
                    Participants are expected to follow staff instructions and venue rules. We may refuse service or ask someone to leave if behaviour risks safety or disrupts the workshop.
                </p>
            </section>
            <section>
                <h3 class="text-base font-semibold text-neutral-900">Cancellations and changes</h3>
                <p class="mt-2">
                    Cancellation, refund, and reschedule policies depend on the specific workshop and will be communicated at booking or in your confirmation. We may need to cancel or reschedule workshops due to low enrolment, instructor availability, or force majeure; in those cases we will offer reasonable alternatives where possible.
                </p>
            </section>
            <section>
                <h3 class="text-base font-semibold text-neutral-900">Limitation of liability</h3>
                <p class="mt-2">
                    Creative workshops involve materials and tools. You participate at your own risk to the extent permitted by law. We are not liable for indirect or consequential loss except where such exclusion is not allowed by applicable law.
                </p>
            </section>
            <section>
                <h3 class="text-base font-semibold text-neutral-900">Intellectual property</h3>
                <p class="mt-2">
                    Site content, branding, and class materials remain our property or our licensors’ property unless otherwise stated. You may not copy or redistribute them without permission.
                </p>
            </section>
            <section>
                <h3 class="text-base font-semibold text-neutral-900">Changes</h3>
                <p class="mt-2">
                    We may update these terms from time to time. Continued use of the site after changes constitutes acceptance of the updated terms.
                </p>
            </section>
        </div>
    </div>
</div>

<script>
(function () {
    var TRANS_MS = 300;

    function syncBodyOverflow() {
        var contact = document.getElementById('contact-modal');
        var privacy = document.getElementById('privacy-modal');
        var terms = document.getElementById('terms-modal');
        var open =
            (contact && contact.classList.contains('flex')) ||
            (privacy && privacy.classList.contains('flex')) ||
            (terms && terms.classList.contains('flex'));
        document.body.style.overflow = open ? 'hidden' : '';
    }

    function clearCloseTimer(modal) {
        if (!modal) return;
        var raw = modal.getAttribute('data-cc-close-timer');
        if (raw) {
            clearTimeout(parseInt(raw, 10));
            modal.removeAttribute('data-cc-close-timer');
        }
    }

    /** Instant hide (no animation). Used when switching between the two legal modals. */
    function forceHideModal(modal) {
        if (!modal) return;
        clearCloseTimer(modal);
        var inner = modal.querySelector('div.relative');
        if (inner) {
            inner.style.removeProperty('transform');
            inner.style.removeProperty('opacity');
        }
        modal.classList.remove('flex', 'opacity-100', 'pointer-events-auto');
        modal.classList.add('hidden', 'opacity-0', 'pointer-events-none');
        modal.setAttribute('aria-hidden', 'true');
    }

    function showModal(modal) {
        if (!modal) return;
        clearCloseTimer(modal);

        modal.classList.remove('hidden');
        modal.classList.add('flex', 'opacity-0', 'pointer-events-none');
        modal.removeAttribute('aria-hidden');

        var inner = modal.querySelector('div.relative');
        if (inner) {
            inner.style.transform = 'scale(0.97)';
            inner.style.opacity = '0';
        }

        requestAnimationFrame(function () {
            requestAnimationFrame(function () {
                modal.classList.remove('opacity-0', 'pointer-events-none');
                modal.classList.add('opacity-100', 'pointer-events-auto');
                if (inner) {
                    inner.style.transform = 'scale(1)';
                    inner.style.opacity = '1';
                }
                syncBodyOverflow();
            });
        });
    }

    function hideModal(modal) {
        if (!modal || !modal.classList.contains('flex')) return;
        clearCloseTimer(modal);

        var inner = modal.querySelector('div.relative');
        if (inner) {
            inner.style.transform = 'scale(0.97)';
            inner.style.opacity = '0';
        }

        modal.classList.remove('opacity-100', 'pointer-events-auto');
        modal.classList.add('opacity-0', 'pointer-events-none');

        var tid = window.setTimeout(function () {
            modal.removeAttribute('data-cc-close-timer');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
            modal.setAttribute('aria-hidden', 'true');
            if (inner) {
                inner.style.removeProperty('transform');
                inner.style.removeProperty('opacity');
            }
            syncBodyOverflow();
        }, TRANS_MS);
        modal.setAttribute('data-cc-close-timer', String(tid));
    }

    window.__ccSyncBodyOverflow = syncBodyOverflow;

    window.openPrivacyModal = function () {
        forceHideModal(document.getElementById('terms-modal'));
        showModal(document.getElementById('privacy-modal'));
    };

    window.closePrivacyModal = function () {
        hideModal(document.getElementById('privacy-modal'));
    };

    window.openTermsModal = function () {
        forceHideModal(document.getElementById('privacy-modal'));
        showModal(document.getElementById('terms-modal'));
    };

    window.closeTermsModal = function () {
        hideModal(document.getElementById('terms-modal'));
    };

    document.addEventListener('DOMContentLoaded', function () {
        var pm0 = document.getElementById('privacy-modal');
        var tm0 = document.getElementById('terms-modal');
        if (pm0) pm0.setAttribute('aria-hidden', 'true');
        if (tm0) tm0.setAttribute('aria-hidden', 'true');

        document.addEventListener('click', function (e) {
            var p = document.getElementById('privacy-modal');
            var t = document.getElementById('terms-modal');
            if (p && e.target === p) window.closePrivacyModal();
            if (t && e.target === t) window.closeTermsModal();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key !== 'Escape') return;
            var p = document.getElementById('privacy-modal');
            var t = document.getElementById('terms-modal');
            if (p && p.classList.contains('flex')) {
                e.preventDefault();
                window.closePrivacyModal();
                return;
            }
            if (t && t.classList.contains('flex')) {
                e.preventDefault();
                window.closeTermsModal();
            }
        }, true);
    });
})();
</script>

