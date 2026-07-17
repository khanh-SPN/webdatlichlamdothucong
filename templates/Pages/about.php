<?php
$this$this->assign('title', 'Về chúng tôi');
$c = $siteCompany ?? $company ?? null;
$companyName = h($c?->name ?? 'Hội Nghệ Thuật Nến');
$companyDesc = h($c?->description ?? ‘Được thành lập với niềm đam mê các công nghệ thủ công, Hội Nghệ Thuật Nến tập hợp các giảng viên chuyên gia và những người tạo tác tò mò trong một không gian ấm áp, hỗ trợ. Chúng tôi tin rằng sáng tạo là dành cho mọi người, cho dù bạn lần đầu tiên nắm lấy đất sét hoặc hoàn thiện các kỹ thuật nâng cao.’);
$heroLead = h($c?->description ?? ‘Không gian hội thảo sáng tạo cho gốm, đan, và các công nghệ thủ công, được hướng dẫn bởi các giảng viên có kinh nghiệm trong một studio yên tĩnh, chào đón.’);
$emailRaw = (string)($c?->email ?? 'HoiNgheThuatNen@gmail.com');
$phoneRaw = (string)($c?->phone ?? '\+84 912 345 678');
$email = h($emailRaw);
$phone = h($phoneRaw);
$address = h($c?->address ?? '123 Đường Thủ Công, Thành Phố Sáng Tạo');
$phoneTel = preg_replace('/[^\d+]/', '', $phoneRaw);
if ($phoneTel === '') {
    $phoneTel = preg_replace('/\s+/', '', $phoneRaw);
}

$aboutImg = fn (string $file): string => h($this->Url->build('/img/landing/' . $file));
?>

<div class="bg-studio-ivory text-ink-900">
    <!-- Hero: same pattern as workshops catalogue (dark image, accent CTA, status pill) -->
    <div id="about" class="relative scroll-mt-20 overflow-hidden border-b border-neutral-800/20 bg-ink-950 text-white" aria-labelledby="about-hero-heading">
        <div class="pointer-events-none absolute inset-0" aria-hidden="true">
            <img
                src="<?= $aboutImg('home-candle-workspace.png') ?>"
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
                <span class="text-white/90">Về chúng tôi</span>
            </nav>
            <h1 id="about-hero-heading" class="mt-5 max-w-3xl font-serif text-xl font-semibold leading-tight tracking-tight text-white sm:text-2xl lg:text-[3rem]">
                Về studio
            </h1>
            <p class="mt-4 max-w-2xl text-sm leading-relaxed text-white/80 sm:text-base lg:text-lg">
                <?= $heroLead ?>
            </p>
            <p class="mt-3 text-sm font-medium text-primary-200/95">
                <?= $companyName ?>
            </p>
            <div class="mt-4 flex flex-wrap items-center gap-3">
                <?= $this->Html->link(
                    '<span class="inline-flex items-center gap-2">Đặt hội thảo' . $this->element('ui_icon', ['name' => 'calendar_days', 'class' => 'h-4 w-4']) . '</span>',
                    '/booking',
                    ['class' => 'inline-flex items-center justify-center rounded-lg bg-primary-400 px-3 py-2.5 text-sm font-semibold text-ink-950 transition hover:bg-primary-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-ink-900', 'escape' => false]
                ) ?>
                <a
                    href="#about-content"
                    class="inline-flex items-center gap-2 rounded-lg border border-white/20 bg-white/5 px-4 py-2 text-xs font-medium text-white/80 transition hover:border-white/30 hover:bg-white/10"
                >
                    <span class="h-1.5 w-1.5 rounded-full bg-primary-400" aria-hidden="true"></span>
                    Câu chuyện của chúng tôi bên dưới
                </a>
            </div>
        </div>
    </div>

    <div class="py-3 md:py-4 lg:py-20" id="about-content">
        <div class="mx-auto max-w-6xl px-3 lg:px-4">
            <!-- At a glance -->
            <div class="mb-3 grid gap-4 sm:grid-cols-3 md:mb-16">
                <div class="flex gap-4 rounded-2xl border border-neutral-200/80 bg-white/80 px-5 py-4 shadow-sm backdrop-blur-sm">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary-50 text-primary-700" aria-hidden="true">
                        <?= $this->element('ui_icon', ['name' => 'users', 'class' => 'h-5 w-5']) ?>
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-ink-900">Nhóm nhỏ</p>
                        <p class="mt-0.5 text-xs leading-relaxed text-neutral-600">Không gian để đặt câu hỏi và nhận giúp đỡ thực tế.</p>
                    </div>
                </div>
                <div class="flex gap-4 rounded-2xl border border-neutral-200/80 bg-white/80 px-5 py-4 shadow-sm backdrop-blur-sm">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary-50 text-primary-700" aria-hidden="true">
                        <?= $this->element('ui_icon', ['name' => 'cube', 'class' => 'h-5 w-5']) ?>
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-ink-900">Bao gồm vật liệu</p>
                        <p class="mt-0.5 text-xs leading-relaxed text-neutral-600">Mọi thứ bạn cần đều có trên bàn khi bạn đến.</p>
                    </div>
                </div>
                <div class="flex gap-4 rounded-2xl border border-neutral-200/80 bg-white/80 px-5 py-4 shadow-sm backdrop-blur-sm">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary-50 text-primary-700" aria-hidden="true">
                        <?= $this->element('ui_icon', ['name' => 'academic_cap', 'class' => 'h-5 w-5']) ?>
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-ink-900">Giảng viên chuyên nghiệp</p>
                        <p class="mt-0.5 text-xs leading-relaxed text-neutral-600">Chào đón người mới thực sự: chúng tôi demo, bạn thực hành.</p>
                    </div>
                </div>
            </div>

            <!-- Story -->
            <section class="overflow-hidden rounded-2xl border border-neutral-200/80 bg-white shadow-soft" aria-labelledby="story-heading">
                <div class="grid gap-0 lg:grid-cols-12">
                    <div class="flex flex-col justify-center p-4 md:p-3 lg:col-span-5 lg:p-14">
                        <h2 id="story-heading" class="font-serif text-xl font-semibold tracking-tight text-ink-900 md:text-lg">
                            Câu chuyện của chúng tôi
                        </h2>
                        <p class="mt-2 text-sm font-medium text-primary-700">
                            <?= $companyName ?>
                        </p>
                        <p class="mt-3 text-base leading-relaxed text-neutral-600 md:text-lg">
                            <?= $companyDesc ?>
                        </p>
                        <p class="mt-4 text-sm text-neutral-500">
                            Curious what’s on offer?
                            <?= $this->Html->link('Duyệt danh mục hội thảo', '/workshops', ['class' => 'font-medium text-primary-700 underline decoration-primary-300 underline-offset-2 hover:text-primary-800']) ?>.
                        </p>
                    </div>

                    <div class="relative min-h-[260px] lg:col-span-7 lg:min-h-[400px]">
                        <img
                            src="<?= $aboutImg('home-pottery-ceramics.png') ?>"
                            alt="Gốm men tay và gốm được trưng bày trong studio"
                            class="absolute inset-0 h-full w-full object-cover object-center"
                            loading="lazy"
                            width="1600"
                            height="1067"
                        >
                        <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-ink-900/35 via-transparent to-transparent lg:bg-gradient-to-l lg:from-transparent lg:via-transparent lg:to-white/90"></div>
                    </div>
                </div>

                <div class="border-t border-neutral-100 bg-studio-mist/40 px-3 py-4 md:px-3 md:py-5">
                    <h3 class="text-xs font-semibold uppercase tracking-[0.12em] text-neutral-500">Thăm và liên hệ</h3>
                    <div class="mt-3 grid gap-4 sm:grid-cols-3">
                        <a href="mailto:<?= h($emailRaw) ?>" class="group flex items-start gap-3 rounded-2xl border border-neutral-200/80 bg-white p-4 shadow-sm transition hover:border-primary-200/80 hover:shadow-md">
                            <span class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-primary-50 text-primary-700" aria-hidden="true">
                                <?= $this->element('ui_icon', ['name' => 'envelope', 'class' => 'h-4 w-4']) ?>
                            </span>
                            <span class="min-w-0">
                                <span class="block text-xs font-medium text-neutral-500">Email</span>
                                <span class="mt-0.5 block truncate text-sm font-semibold text-ink-900 group-hover:text-primary-800"><?= $email ?></span>
                            </span>
                        </a>
                        <a href="tel:<?= h($phoneTel !== '' ? $phoneTel : preg_replace('/\s+/', '', $phoneRaw)) ?>" class="group flex items-start gap-3 rounded-2xl border border-neutral-200/80 bg-white p-4 shadow-sm transition hover:border-primary-200/80 hover:shadow-md">
                            <span class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-primary-50 text-primary-700" aria-hidden="true">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </span>
                            <span class="min-w-0">
                                <span class="block text-xs font-medium text-neutral-500">Phone</span>
                                <span class="mt-0.5 block text-sm font-semibold text-ink-900 group-hover:text-primary-800"><?= $phone ?></span>
                            </span>
                        </a>
                        <div class="flex items-start gap-3 rounded-2xl border border-neutral-200/80 bg-white p-4 shadow-sm">
                            <span class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-primary-50 text-primary-700" aria-hidden="true">
                                <?= $this->element('ui_icon', ['name' => 'building_office_2', 'class' => 'h-4 w-4']) ?>
                            </span>
                            <span class="min-w-0">
                                <span class="block text-xs font-medium text-neutral-500">Studio</span>
                                <span class="mt-0.5 block text-sm font-semibold leading-snug text-ink-900"><?= $address ?></span>
                            </span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Workshops -->
            <section class="mt-4 md:mt-24" aria-labelledby="workshops-heading">
                <div class="mx-auto max-w-2xl text-center lg:mx-0 lg:max-w-none lg:text-left">
                    <h2 id="workshops-heading" class="font-serif text-xl font-semibold tracking-tight text-ink-900 sm:text-2xl lg:text-3xl">
                        Chúng tôi dạy gì
                    </h2>
                    <p class="mt-4 text-base text-neutral-600 sm:text-lg lg:text-xl">
                        Candle making, pottery, and knitting in Nhóm nhỏ: skill building, creativity, and calm studio time.
                    </p>
                </div>

                <div class="mt-3 grid gap-4 md:grid-cols-3 md:gap-3 lg:gap-4">
                    <article class="group flex flex-col overflow-hidden rounded-2xl border border-neutral-200/80 bg-white shadow-soft transition duration-300 hover:border-primary-200/60 hover:shadow-md">
                        <div class="relative aspect-[16/10] overflow-hidden bg-neutral-100">
                            <img
                                src="<?= $aboutImg('home-candle-workspace.png') ?>"
                                alt="Bàn làm nến với sáp trong lọ, bình thủy tinh, cuộn bấc và công cụ"
                                class="h-full w-full object-cover transition duration-700 group-hover:scale-[1.03]"
                                loading="lazy"
                                width="1200"
                                height="800"
                            >
                        </div>
                        <div class="flex flex-1 flex-col p-7 md:p-4">
                            <h3 class="font-serif text-xl font-semibold text-ink-900">Candle making</h3>
                            <p class="mt-3 flex-1 text-sm leading-relaxed text-neutral-600 md:text-base">
                                Scent, wax, wicks, and pouring techniques, with guided sessions so you leave with candles you’re proud to light or gift.
                            </p>
                        </div>
                    </article>

                    <article class="group flex flex-col overflow-hidden rounded-2xl border border-neutral-200/80 bg-white shadow-soft transition duration-300 hover:border-primary-200/60 hover:shadow-md">
                        <div class="relative aspect-[16/10] overflow-hidden bg-neutral-100">
                            <img
                                src="<?= $aboutImg('home-pottery-painted-cup.png') ?>"
                                alt="Cốc gốm vẽ tay và các mảnh biscuit khác trên bàn studio"
                                class="h-full w-full object-cover transition duration-700 group-hover:scale-[1.03]"
                                loading="lazy"
                                width="1200"
                                height="800"
                            >
                        </div>
                        <div class="flex flex-1 flex-col p-7 md:p-4">
                            <h3 class="font-serif text-xl font-semibold text-ink-900">Pottery</h3>
                            <p class="mt-3 flex-1 text-sm leading-relaxed text-neutral-600 md:text-base">
                                Ném bàn xoay, xây dựng tay và men: từ lần chạm đầu tiên đến các mảnh hoàn thành, với bản demo và phản hồi dọc đường.
                            </p>
                        </div>
                    </article>

                    <article class="group flex flex-col overflow-hidden rounded-2xl border border-neutral-200/80 bg-white shadow-soft transition duration-300 hover:border-primary-200/60 hover:shadow-md">
                        <div class="relative aspect-[16/10] overflow-hidden bg-neutral-100">
                            <img
                                src="<?= $aboutImg('home-knitting-pink.png') ?>"
                                alt="Đôi tay đan vải sọc với len hồng mềm và kim kim loại trên gỗ"
                                class="h-full w-full object-cover transition duration-700 group-hover:scale-[1.03]"
                                loading="lazy"
                                width="1200"
                                height="800"
                            >
                        </div>
                        <div class="flex flex-1 flex-col p-7 md:p-4">
                            <h3 class="font-serif text-xl font-semibold text-ink-900">Knitting</h3>
                            <p class="mt-3 flex-1 text-sm leading-relaxed text-neutral-600 md:text-base">
                                Stitches, patterns, and design fundamentals so you can plan and finish pieces you’ll actually wear or gift.
                            </p>
                        </div>
                    </article>
                </div>

                <div class="mt-3 flex flex-col items-center justify-center gap-3 sm:flex-row sm:gap-4">
                    <?= $this->Html->link(
                        'Đặt hội thảo',
                        '/booking',
                        ['class' => 'inline-flex w-full items-center justify-center rounded-full bg-primary-600 px-4 py-2.5 text-base font-semibold text-white shadow-sm transition hover:bg-primary-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-400 focus-visible:ring-offset-2 sm:w-auto']
                    ) ?>
                    <?= $this->Html->link(
                        'Xem hội thảo',
                        '/workshops',
                        ['class' => 'inline-flex w-full items-center justify-center rounded-full border-2 border-primary-600 bg-white px-4 py-2.5 text-base font-semibold text-primary-700 transition hover:bg-primary-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-400 focus-visible:ring-offset-2 sm:w-auto']
                    ) ?>
                    <button
                        type="button"
                        onclick="openContactModal()"
                        class="inline-flex w-full items-center justify-center rounded-full border border-neutral-200 bg-white px-4 py-2.5 text-base font-semibold text-ink-900 shadow-sm transition hover:border-primary-200 hover:bg-studio-mist/80 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-400 focus-visible:ring-offset-2 sm:w-auto"
                    >
                        Đặt câu hỏi
                    </button>
                </div>

                <p class="mt-4 text-center text-sm text-neutral-500 lg:text-left">
                    Không chắc hội thảo nào phù hợp\?
                    <?= $this->Html->link('Đọc câu hỏi thường gặp', '/faqs', ['class' => 'font-medium text-primary-700 underline decoration-primary-300 underline-offset-2 hover:text-primary-800']) ?>
                    hoặc gửi cho chúng tôi một tin nhắn.
                </p>
            </section>
        </div>
    </div>

    <?= $this->element('site_footer') ?>
</div>



