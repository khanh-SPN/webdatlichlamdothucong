<?php
$this$this->assign('title', 'Liên hệ');

$c = $siteCompany ?? null;
$emailRaw = (string)($c?->email ?? 'HoiNgheThuatNen@gmail.com');
$phoneRaw = (string)($c?->phone ?? '\+84 912 345 678');
$addressRaw = (string)($c?->address ?? '123 Đường Thủ Công, Thành Phố Sáng Tạo');
$phoneTel = preg_replace('/[^\d+]/', '', $phoneRaw);
if ($phoneTel === '') {
    $phoneTel = preg_replace('/\s+/', '', $phoneRaw);
}

$this->Html->scriptBlock(
    "document.addEventListener('DOMContentLoaded', function () {\n" .
    "    if (typeof window.openContactModal === 'function') {\n" .
    "        window.openContactModal();\n" .
    "    }\n" .
    "});",
    ['block' => true]
);
?>

<div class="bg-studio-ivory text-ink-900">
    <div id="contact" class="relative scroll-mt-20 overflow-hidden border-b border-neutral-200/80 bg-studio-ivory text-ink-900" aria-labelledby="contact-hero-heading">
        <div class="pointer-events-none absolute inset-0" aria-hidden="true">
            <img
                src="<?= h($this->Url->build('/img/landing/home-candle-workspace.png')) ?>"
                alt=""
                class="h-full w-full min-h-[280px] object-cover object-center scale-105 sm:scale-100 sm:min-h-0"
                loading="eager"
                fetchpriority="high"
                decoding="async"
            >
            <div class="absolute inset-0 bg-white/70"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-white/95 via-studio-ivory/88 to-primary-50/86"></div>
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_80%_60%_at_70%_20%,rgba(169,102,120,0.12),transparent_55%)]"></div>
        </div>
        <div class="relative z-10 mx-auto max-w-screen-2xl px-3 py-5 md:px-4 md:py-7">
            <nav class="text-xs font-medium text-black/70" aria-label="Breadcrumb">
                <?= $this->Html->link('Trang chủ', '/', ['class' => 'transition hover:text-black']) ?>
                <span class="mx-2 text-black/45" aria-hidden="true">/</span>
                <span class="text-black">Liên hệ</span>
            </nav>
            <h1 id="contact-hero-heading" class="mt-5 max-w-3xl font-serif text-xl font-semibold leading-tight tracking-tight text-black sm:text-2xl lg:text-[3rem]">
                Liên hệ với studio
            </h1>
            <p class="mt-4 max-w-2xl text-sm leading-relaxed text-black/80 sm:text-base lg:text-lg">
                Hỏi về hội thảo, đặt chỗ, buổi riêng, hoặc bất cứ điều gì bạn cần trước khi ghé thăm Hội Nghệ Thuật Nến.
            </p>
            <div class="mt-4 flex flex-wrap items-center gap-3">
                <button
                    type="button"
                    onclick="openContactModal()"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary-400 px-3 py-2.5 text-sm font-semibold text-white transition hover:bg-primary-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 focus-visible:ring-offset-white"
                >
                    <?= $this->element('ui_icon', ['name' => 'envelope', 'class' => 'h-4 w-4']) ?>
                    Gửi tin nhắn
                </button>
                <?= $this->Html->link(
                    'Đặt hội thảo',
                    '/booking',
                    ['class' => 'inline-flex items-center rounded-lg border border-black/20 bg-white/60 px-4 py-2 text-xs font-medium text-black transition hover:border-black/30 hover:bg-white/80']
                ) ?>
            </div>
        </div>
    </div>

    <section class="py-3 md:py-4 lg:py-20" aria-labelledby="contact-details-heading">
        <div class="mx-auto max-w-6xl px-3 lg:px-4">
            <div class="grid gap-5 md:grid-cols-3">
                <div class="rounded-2xl border border-neutral-200/80 bg-white p-3 shadow-sm md:p-7">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary-50 text-primary-700" aria-hidden="true">
                        <?= $this->element('ui_icon', ['name' => 'envelope', 'class' => 'h-5 w-5']) ?>
                    </span>
                    <h2 id="contact-details-heading" class="mt-5 text-base font-semibold text-ink-900">Email</h2>
                    <p class="mt-2 text-sm leading-relaxed text-neutral-600">Chúng tôi thường trả lời trong một ngày làm việc.</p>
                    <p class="mt-4">
                        <a href="mailto:<?= h($emailRaw) ?>" class="text-sm font-semibold text-primary-700 hover:text-primary-800"><?= h($emailRaw) ?></a>
                    </p>
                </div>

                <div class="rounded-2xl border border-neutral-200/80 bg-white p-3 shadow-sm md:p-7">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-sage-50 text-sage-600" aria-hidden="true">
                        <?= $this->element('ui_icon', ['name' => 'phone', 'class' => 'h-5 w-5']) ?>
                    </span>
                    <h2 class="mt-5 text-base font-semibold text-ink-900">Điện thoại</h2>
                    <p class="mt-2 text-sm leading-relaxed text-neutral-600">Gọi cho chúng tôi cho các câu hỏi đặt chỗ hoặc ghé thăm khẩn cấp.</p>
                    <p class="mt-4">
                        <a href="tel:<?= h($phoneTel) ?>" class="text-sm font-semibold text-primary-700 hover:text-primary-800"><?= h($phoneRaw) ?></a>
                    </p>
                </div>

                <div class="rounded-2xl border border-neutral-200/80 bg-white p-3 shadow-sm md:p-7">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary-50 text-primary-700" aria-hidden="true">
                        <?= $this->element('ui_icon', ['name' => 'building_office_2', 'class' => 'h-5 w-5']) ?>
                    </span>
                    <h2 class="mt-5 text-base font-semibold text-ink-900">Ghé thăm</h2>
                    <p class="mt-2 text-sm leading-relaxed text-neutral-600"><?= h($addressRaw) ?></p>
                    <p class="mt-4">
                        <?= $this->Html->link('Lập kế hoạch ghé thăm', '/visit', ['class' => 'text-sm font-semibold text-primary-700 hover:text-primary-800']) ?>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <?= $this->element('site_footer') ?>
</div>


