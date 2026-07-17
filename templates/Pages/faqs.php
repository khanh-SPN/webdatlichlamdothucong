<?php
$this$this->assign('title', 'Câu Hỏi Thường Gặp \| Hội Nghệ Thuật Nến');
?>

<div class="bg-studio-ivory text-ink-900">
    <!-- Hero: same pattern as workshops catalogue (dark image, accent CTA, status pill) -->
    <div id="faq-hero" class="relative scroll-mt-20 overflow-hidden border-b border-neutral-800/20 bg-ink-950 text-white" aria-labelledby="faq-hero-heading">
        <div class="pointer-events-none absolute inset-0" aria-hidden="true">
            <img
                src="<?= h($this->Url->build('/img/landing/home-candle-workspace.png')) ?>"
                alt=""
                class="h-full w-full min-h-[280px] object-cover object-center scale-105 sm:scale-100 sm:min-h-0"
                loading="eager"
                fetchpriority="high"
                decoding="async"
            >
            <div class="absolute inset-0 bg-ink-950/32"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-ink-950/90 via-ink-900/82 to-primary-950/86"></div>
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_110%_90%_at_0%_0%,rgba(15,23,42,0.55),transparent_55%)]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_80%_60%_at_70%_20%,rgba(169,102,120,0.14),transparent_55%)]"></div>
        </div>
        <div class="relative z-10 mx-auto max-w-screen-2xl px-3 py-5 md:px-4 md:py-7">
            <nav class="text-xs font-medium text-white/62" aria-label="Breadcrumb">
                <?= $this->Html->link('Trang chủ', '/', ['class' => 'transition hover:text-white']) ?>
                <span class="mx-2 text-white/35" aria-hidden="true">/</span>
                <span class="text-white/90">Câu hỏi thường gặp</span>
            </nav>
            <h1 id="faq-hero-heading" class="mt-5 max-w-3xl font-serif text-xl font-semibold leading-tight tracking-tight text-white sm:text-2xl lg:text-[3rem]">
                Câu hỏi thường gặp
            </h1>
            <p class="mt-4 max-w-2xl text-sm leading-relaxed text-white/80 sm:text-base lg:text-lg">
                Đặt chỗ, thanh toán và hội thảo: mở một danh mục, sau đó mở rộng một câu hỏi. Nhắn tin cho chúng tôi nếu bạn không thể tìm thấy những gì bạn cần.
            </p>
            <div class="mt-4 flex flex-wrap items-center gap-3">
                <button
                    type="button"
                    onclick="openContactModal()"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary-400 px-3 py-2.5 text-sm font-semibold text-white transition hover:bg-primary-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-ink-900"
                >
                    <?= $this->element('ui_icon', ['name' => 'envelope', 'class' => 'h-4 w-4']) ?>
                    Liên hệ với chúng tôi
                </button>
                <a
                    href="#faq-section"
                    class="inline-flex items-center gap-2 rounded-lg border border-white/20 bg-white/5 px-4 py-2 text-xs font-medium text-white/80 transition hover:border-white/30 hover:bg-white/10"
                >
                    <span class="h-1.5 w-1.5 rounded-full bg-primary-400" aria-hidden="true"></span>
                    Câu trả lời bên dưới
                </a>
            </div>
        </div>
    </div>

    <div class="py-3 md:py-4 lg:py-20" id="faq-section">
        <div class="mx-auto max-w-6xl px-3 lg:px-4">
            <div class="mx-auto mb-5 max-w-2xl text-center lg:mx-0 lg:max-w-none lg:text-left">
                <h2 class="font-serif text-xl font-semibold tracking-tight text-ink-900 md:text-lg">
                    Mọi thứ bạn muốn biết
                </h2>
                <p class="mt-3 text-neutral-600 md:text-lg">
                    Câu trả lời nhanh về hội thảo, truy cập studio và chính sách.
                </p>
            </div>

            <div class="space-y-4">
                <div class="space-y-4" id="faq-accordion">

                    <?php if (!empty($groupedFaqs)): ?>

                        <?php
                        $categoryMap = [
                            'booking' => 'Đặt chỗ',
                            'payment' => 'Thanh toán',
                            'general' => 'Hội thảo và studio',
                            'policy' => 'Chính sách',
                        ];
                        ?>

                        <?php foreach ($groupedFaqs as $category => $faqs): ?>
                            <details class="group overflow-hidden rounded-2xl border border-neutral-200/80 bg-white shadow-soft transition-shadow open:shadow-md" <?= $category === array_key_first($groupedFaqs) ? 'open' : '' ?>>
                                <summary class="flex cursor-pointer list-none items-center justify-between gap-3 px-5 py-4 font-semibold text-ink-900 marker:hidden sm:px-7 sm:py-5 [&::-webkit-details-marker]:hidden">
                                    <span class="text-left text-sm sm:text-base"><?= h($categoryMap[$category] ?? ucfirst((string)$category)) ?></span>
                                    <span class="shrink-0 text-neutral-400 transition group-open:rotate-180" aria-hidden="true">
                                        <?= $this->element('ui_icon', ['name' => 'chevron_down', 'class' => 'h-5 w-5']) ?>
                                    </span>
                                </summary>
                                <div class="space-y-2 border-t border-neutral-100 bg-studio-mist/30 px-3 pb-4 pt-3 sm:px-5 sm:pb-5">
                                    <?php foreach ($faqs as $faq): ?>
                                        <div class="faq-item overflow-hidden rounded-2xl border border-neutral-200/70 bg-white">
                                            <button
                                                type="button"
                                                onclick="toggleFaq(this)"
                                                class="faq-question flex w-full items-center justify-between gap-3 rounded-t-2xl bg-studio-mist/50 px-4 py-3.5 text-left transition-colors hover:bg-studio-mist/70 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-400 focus-visible:ring-offset-2 sm:px-5"
                                            >
                                                <span class="pr-2 text-sm font-semibold text-ink-900 sm:text-base"><?= h($faq->question) ?></span>
                                                <span class="faq-icon inline-flex shrink-0 text-neutral-400">
                                                    <?= $this->element('ui_icon', ['name' => 'chevron_down', 'class' => 'h-4 w-4 transition-transform duration-300 ease-out']) ?>
                                                </span>
                                            </button>
                                            <div class="faq-answer max-h-0 overflow-hidden transition-all duration-300 ease-out px-4 sm:px-5">
                                                <div class="-mx-4 rounded-b-2xl border-t border-neutral-100/80 bg-white px-4 pb-4 pt-3 text-sm leading-relaxed text-neutral-600 sm:-mx-5 sm:px-5">
                                                    <?= nl2br(h($faq->answer)) ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </details>
                        <?php endforeach; ?>

                    <?php else: ?>

                        <div class="rounded-2xl border border-neutral-200/80 bg-white py-7 text-center shadow-soft">
                            <p class="text-sm text-neutral-600">Không có câu hỏi thường gặp nào vào lúc này.</p>
                        </div>

                    <?php endif; ?>

                </div>

                <div class="mt-3 rounded-2xl border border-neutral-200/80 bg-gradient-to-br from-primary-50/70 to-white p-4 text-center shadow-soft md:p-5">
                    <h3 class="font-serif text-lg font-semibold text-ink-900 sm:text-xl">
                        Vẫn bị kẹt\?
                    </h3>
                    <p class="mx-auto mt-2 max-w-md text-sm text-neutral-600">
                        Nhắn tin cho studio. Chúng tôi thường trả lời trong một ngày.
                    </p>
                    <button
                        type="button"
                        onclick="openContactModal()"
                        class="mt-3 inline-flex items-center justify-center rounded-full bg-primary-600 px-4 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-400 focus-visible:ring-offset-2"
                    >
                        Liên hệ với chúng tôi
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?= $this->element('site_footer') ?>
</div>

<script>
function toggleFaq(button) {
    const item = button.closest('.faq-item');
    if (!item) return;
    const answer = item.querySelector('.faq-answer');
    const iconWrap = button.querySelector('.faq-icon');
    const chevron = iconWrap && iconWrap.querySelector('svg');
    const isOpen = answer.style.maxHeight && answer.style.maxHeight !== '0px';

    document.querySelectorAll('#faq-accordion .faq-answer').forEach((ans) => {
        if (ans !== answer) {
            ans.style.maxHeight = '0px';
            const otherItem = ans.closest('.faq-item');
            const otherChevron = otherItem && otherItem.querySelector('.faq-icon svg');
            if (otherChevron) otherChevron.classList.remove('rotate-180');
        }
    });

    if (isOpen) {
        answer.style.maxHeight = '0px';
        if (chevron) chevron.classList.remove('rotate-180');
    } else {
        answer.style.maxHeight = answer.scrollHeight + 'px';
        if (chevron) chevron.classList.add('rotate-180');
    }
}
</script>


