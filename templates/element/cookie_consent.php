<?php
/**
 * Cookie consent banner (shown by JS until user decides).
 *
 * @var \App\View\AppView $this
 */
?>
<section
    id="cookie-consent"
    class="fixed inset-x-0 bottom-0 z-[120] hidden border-t border-neutral-200/70 bg-white/90 px-4 py-4 backdrop-blur-xl supports-[backdrop-filter]:bg-white/75"
    role="region"
    aria-label="Cookie consent"
    aria-hidden="true"
>
    <div class="mx-auto flex w-full max-w-screen-2xl flex-col gap-3 sm:flex-row sm:items-center sm:justify-between sm:gap-4">
        <div class="min-w-0">
            <p class="text-sm font-semibold text-neutral-900">Chúng tôi sử dụng cookie</p>
            <p class="mt-1 text-sm leading-relaxed text-neutral-600">
                Chúng tôi sử dụng cookie thiết yếu để trang web này hoạt động. Với sự cho phép của bạn, chúng tôi cũng có thể sử dụng cookie để cải thiện trải nghiệm của bạn.
                <button type="button" class="font-semibold text-primary-700 underline underline-offset-2 hover:text-primary-800" onclick="openPrivacyModal()">
                    Tìm hiểu thêm
                </button>
            </p>
        </div>
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-end sm:gap-3">
            <button
                type="button"
                data-cookie-consent-action="reject"
                class="inline-flex items-center justify-center rounded-full border border-neutral-300 bg-white px-4 py-2 text-sm font-semibold text-neutral-800 shadow-sm transition hover:bg-neutral-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-400 focus-visible:ring-offset-2"
            >
                Từ chối cookie không thiết yếu
            </button>
            <button
                type="button"
                data-cookie-consent-action="accept"
                class="inline-flex items-center justify-center rounded-full bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm shadow-primary-900/10 transition hover:bg-primary-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-400 focus-visible:ring-offset-2"
            >
                Chấp nhận tất cả cookie
            </button>
        </div>
    </div>
</section>

