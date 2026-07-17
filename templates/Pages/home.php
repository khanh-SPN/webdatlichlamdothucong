<?php
$this->assign('title', 'Trang chủ');

$landingImg = function (string $name) {
    return $this->Url->build('/img/landing/' . $name);
};

$heroCarouselSlides = [
    [
        'file' => 'home-workshop-classroom.png',
        'alt' => 'Warm workshop room with individual stations and soft lighting',
    ],
    [
        'file' => 'home-pottery-ceramics.png',
        'alt' => 'Hand glazed ceramics and pottery pieces on the studio shelf',
    ],
    [
        'file' => 'home-knitting-pink.png',
        'alt' => 'Hands knitting pink ribbed fabric with yarn and wooden needles on the table',
    ],
    [
        'file' => 'home-yarn-crochet-display.png',
        'alt' => 'Crocheted yarn character and soft fibres on display in the studio',
    ],
];

$studioTestimonials = [
    [
        'quote' => 'T�i bu?c v�o v?i lo l?ng v? s�p n�ng v� bu?c ra v?i ba c�y n?n thom t�i d� t?ng l�m qu�. M?o an to�n v� nh?p d? ho�n h?o cho ngu?i m?i b?t d?u ho�n to�n.',
        'name' => 'Priya N.',
        'role' => 'Gi?i thi?u l�m n?n, Th�ng 3 nam 2026',
        'tags' => ['T?t cho ngu?i m?i b?t d?u', 'B?u kh�ng kh� thu gi�n'],
    ],
    [
        'quote' => 'Centering clay felt impossible until the instructor broke it into tiny steps. I finally understood what “even pressure” means, and my first mug actually stands upright.',
        'name' => 'Leo K.',
        'role' => 'Gốm người mới bắt đầu, Tháng 2 năm 2026',
        'tags' => ['Thực hành', 'Studio đẹp'],
    ],
    [
        'quote' => 'Nhóm đan đã sửa các cạnh không đều của tôi trong một buổi chiều. Tôi thích rằng chúng ta có thể chậm lại, xé lại và thử lại mà không ai thở dài nhìn đồng hồ.',
        'name' => 'Hannah W.',
        'role' => 'Cơ bản đan, Tháng 1 năm 2026',
        'tags' => ['Xã hội', 'Thân thiện với người mới'],
    ],
];
?>

<div class="bg-studio-ivory text-ink-900">

    <!-- Split editorial hero -->
    <section
        class="relative -mt-[4.75rem] pt-[4.75rem] sm:-mt-20 sm:pt-20 md:-mt-[5.25rem] md:pt-[5.25rem] min-h-[min(82vh,840px)] overflow-hidden border-b border-neutral-200/60"
        aria-labelledby="hero-heading"
    >
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_70%_50%_at_100%_0%,rgba(117,47,63,0.09),transparent_55%)]" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -left-24 top-1/3 h-72 w-72 rounded-full bg-sage-100/50 blur-3xl" aria-hidden="true"></div>
        <div class="pointer-events-none absolute right-0 bottom-0 h-96 w-96 rounded-full bg-primary-100/40 blur-3xl" aria-hidden="true"></div>

        <div class="relative z-10 mx-auto grid max-w-7xl gap-10 px-6 py-10 lg:grid-cols-2 lg:items-center lg:gap-14 lg:px-8 lg:py-12">
            <div class="animate-fade-in-up max-w-xl">
                <p class="text-[0.6875rem] font-semibold uppercase tracking-[0.28em] text-primary-600">
                    Trải nghiệm studio sáng tạo
                </p>
                <h1 id="hero-heading" class="mt-4 font-serif text-4xl font-semibold leading-[1.08] tracking-tight text-ink-900 sm:text-5xl lg:text-[3.25rem]">
                    Tạo ra điều ý nghĩa bằng đôi tay của bạn
                </h1>
                <p class="mt-6 text-base leading-relaxed text-neutral-600 sm:text-lg">
                    Khám phá các hội thảo làm nến, gốm và đan được thiết kế cho người mới bắt đầu, những người yêu thích và bất kỳ ai muốn một cách chậm hơn, sáng tạo hơn để dành thời gian.
                </p>
                <div class="mt-8 flex flex-wrap items-center gap-3">
                    <?= $this->Html->link(
                        '<span class="inline-flex items-center gap-2">Đặt hội thảo' . $this->element('ui_icon', ['name' => 'calendar_days', 'class' => 'h-4 w-4']) . '</span>',
                        '/booking',
                        ['class' => 'inline-flex items-center justify-center rounded-full bg-primary-600 px-7 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-400 focus-visible:ring-offset-2 focus-visible:ring-offset-studio-ivory', 'escape' => false]
                    ) ?>
                    <?= $this->Html->link(
                        'Khám phá hội thảo',
                        '/workshops',
                        ['class' => 'inline-flex items-center justify-center rounded-full border border-neutral-300/90 bg-white/70 px-7 py-3 text-sm font-semibold text-ink-900 backdrop-blur-sm transition hover:border-primary-300 hover:bg-white']
                    ) ?>
                </div>
            </div>

            <div class="relative mx-auto w-full max-w-lg animate-fade-in lg:max-w-none lg:justify-self-end" style="animation-delay: 0.12s">
                <div class="absolute -right-4 top-8 z-20 hidden rounded-2xl border border-white/60 bg-white/90 px-4 py-2 text-xs font-semibold uppercase tracking-wider text-primary-700 shadow-soft backdrop-blur-md sm:block">
                    Thân thiện với người mới
                </div>
                <div class="absolute -left-6 bottom-16 z-20 hidden max-w-[9rem] rounded-2xl border border-white/50 bg-white/85 p-3 text-sm font-medium leading-snug text-neutral-700 shadow-soft backdrop-blur-md sm:block">
                    Bao gồm vật liệu cho mọi hội thảo
                </div>
                <div
                    id="hero-image-carousel"
                    class="relative aspect-[4/5] overflow-hidden rounded-3xl border border-neutral-200/80 shadow-lift"
                    data-hero-carousel
                    aria-roledescription="carousel"
                    aria-label="Studio photos"
                >
                    <p id="hero-carousel-status" class="sr-only" aria-live="polite"></p>
                    <div class="relative h-full min-h-0 w-full">
                        <?php foreach ($heroCarouselSlides as $i => $slide): ?>
                            <img
                                data-hero-slide="<?= (int)$i ?>"
                                src="<?= h($landingImg($slide['file'])) ?>"
                                alt="<?= h($slide['alt']) ?>"
                                aria-hidden="<?= $i === 0 ? 'false' : 'true' ?>"
                                class="absolute inset-0 h-full w-full object-cover transition-opacity duration-700 ease-out <?= $i === 0
                                    ? 'z-10 opacity-100'
                                    : 'z-0 opacity-0 pointer-events-none' ?>"
                                loading="<?= $i === 0 ? 'eager' : 'lazy' ?>"
                                decoding="async"
                            >
                        <?php endforeach; ?>
                    </div>
                    <div class="pointer-events-none absolute inset-0 z-[1] bg-gradient-to-t from-ink-900/20 via-transparent to-transparent" aria-hidden="true"></div>
                </div>
                <div class="absolute -bottom-6 right-0 z-10 w-[52%] overflow-hidden rounded-2xl border border-white/70 shadow-lift sm:-right-4 sm:w-[46%]">
                    <div class="aspect-[4/3] bg-neutral-100">
                        <img
                            src="<?= h($landingImg('home-candle-workspace.png')) ?>"
                            alt="Candle jars, melted wax, and wicking tools arranged on the craft bench"
                            class="h-full w-full object-cover transition duration-700 hover:scale-105"
                            loading="lazy"
                            decoding="async"
                        >
                    </div>
                </div>
                <div class="pointer-events-none absolute -top-6 left-1/4 h-24 w-24 rounded-full bg-sage-200/30 blur-2xl" aria-hidden="true"></div>
            </div>
        </div>

        <a href="#studio-intro" class="absolute bottom-5 left-1/2 z-10 flex -translate-x-1/2 flex-col items-center gap-1 text-[0.65rem] font-semibold uppercase tracking-[0.2em] text-neutral-400 transition hover:text-primary-600" aria-label="Cuộn đến giới thiệu studio">
            <span class="hidden sm:inline">Cuộn</span>
            <svg class="h-5 w-5 animate-float opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
            </svg>
        </a>
    </section>

    <!-- Brand intro -->
    <section id="studio-intro" class="border-b border-neutral-200/50 py-16 md:py-20" aria-labelledby="intro-heading">
        <div class="mx-auto grid max-w-7xl gap-12 px-6 lg:grid-cols-2 lg:items-start lg:gap-16 lg:px-8">
            <div class="space-y-6">
                <div class="grid grid-cols-2 gap-4 sm:max-w-xl">
                    <div class="overflow-hidden rounded-3xl border border-neutral-200/80 shadow-sm">
                        <img src="<?= h($landingImg('home-yarn-amigurumi.png')) ?>" alt="Handmade yarn doll with buttons and craft supplies on the table" class="aspect-[4/5] h-full w-full object-cover" loading="lazy" decoding="async">
                    </div>
                    <div class="overflow-hidden rounded-3xl border border-neutral-200/80 shadow-sm">
                        <img src="<?= h($landingImg('home-yarn-crochet-display.png')) ?>" alt="Crocheted yarn character among soft studio lighting" class="aspect-[4/5] h-full w-full object-cover" loading="lazy" decoding="async">
                    </div>
                </div>
                <div class="max-w-xl">
                    <h2 id="intro-heading" class="font-serif text-3xl font-semibold tracking-tight text-ink-900 md:text-4xl">
                        Một studio yên tĩnh, xúc giác cho những người làm hiện đại
                    </h2>
                    <p class="mt-5 max-w-prose text-neutral-600 leading-relaxed">
                        Hội Nghệ Thuật Nến là một không gian nhóm nhỏ ấm áp nơi bạn học bằng cách làm, được hướng dẫn và không vội vàng, được thiết kế để bạn không bao giờ cảm thấy lạc lõng tại bàn làm việc.
                    </p>
                    <p class="mt-4 text-xs text-neutral-500">
                        Những người yêu thích len thường chuyển từ đan phẳng sang amigurumi vui nhộn, tất cả đều được chào đón trong cùng một vòng tròn không vội vàng.
                    </p>
                </div>
            </div>
            <div>
                <ul class="grid gap-5 lg:auto-rows-fr">
                    <li class="flex min-h-[8.75rem] flex-col justify-center rounded-3xl border border-neutral-200/70 bg-white/85 px-6 py-6 shadow-sm backdrop-blur-sm md:px-7">
                        <span class="text-base font-semibold text-ink-900 md:text-lg">Hội thảo nhóm nhỏ</span>
                        <p class="mt-2 text-sm leading-relaxed text-neutral-600 md:text-base">Không gian để đặt câu hỏi và nhận giúp đỡ thực tế.</p>
                    </li>
                    <li class="flex min-h-[8.75rem] flex-col justify-center rounded-3xl border border-neutral-200/70 bg-white/85 px-6 py-6 shadow-sm backdrop-blur-sm md:px-7">
                        <span class="text-base font-semibold text-ink-900 md:text-lg">Bao gồm tất cả vật liệu</span>
                        <p class="mt-2 text-sm leading-relaxed text-neutral-600 md:text-base">Đến sẵn sàng để làm, chúng tôi cung cấp những gì bạn cần.</p>
                    </li>
                    <li class="flex min-h-[8.75rem] flex-col justify-center rounded-3xl border border-neutral-200/70 bg-white/85 px-6 py-6 shadow-sm backdrop-blur-sm md:px-7">
                        <span class="text-base font-semibold text-ink-900 md:text-lg">Hướng dẫn thân thiện với người mới</span>
                        <p class="mt-2 text-sm leading-relaxed text-neutral-600 md:text-base">Nhịp độ và bản demo được xây dựng cho người lần đầu và người quay lại.</p>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Workshops teaser -->
    <section class="border-b border-neutral-200/50 py-14 md:py-16" aria-labelledby="workshops-teaser-heading">
        <div class="mx-auto flex max-w-7xl flex-col items-start justify-between gap-8 px-6 md:flex-row md:items-center lg:px-8">
            <div class="max-w-xl">
                <h2 id="workshops-teaser-heading" class="font-serif text-2xl font-semibold tracking-tight text-ink-900 md:text-3xl">
                    Làm nến, gốm và đan
                </h2>
                <p class="mt-3 text-neutral-600 leading-relaxed">
                    Xem danh sách đầy đủ của chúng tôi: bộ lọc tâm trạng, chi tiết hội thảo và vé nhiều hội thảo trên trang hội thảo.
                </p>
            </div>
            <?= $this->Html->link(
                '<span class="inline-flex items-center gap-2">Xem tất cả hội thảo' . $this->element('ui_icon', ['name' => 'chevron_right', 'class' => 'h-5 w-5']) . '</span>',
                '/workshops',
                ['class' => 'inline-flex shrink-0 items-center justify-center rounded-full bg-primary-600 px-7 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-400 focus-visible:ring-offset-2', 'escape' => false]
            ) ?>
        </div>
        <div class="mx-auto mt-10 max-w-7xl px-6 lg:px-8">
            <p class="text-[0.65rem] font-semibold uppercase tracking-[0.22em] text-primary-600">Nhìn nhanh</p>
            <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <a href="<?= h($this->Url->build('/workshops#workshop-candle')) ?>" class="group overflow-hidden rounded-2xl border border-neutral-200/80 bg-white shadow-sm transition hover:border-primary-200 hover:shadow-md">
                    <div class="aspect-[4/3] overflow-hidden bg-neutral-100">
                        <img src="<?= h($landingImg('home-candle-workspace.png')) ?>" alt="" class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]" loading="lazy">
                    </div>
                    <p class="px-4 py-3 text-sm font-semibold text-ink-900">Làm nến</p>
                </a>
                <a href="<?= h($this->Url->build('/workshops#workshop-pottery')) ?>" class="group overflow-hidden rounded-2xl border border-neutral-200/80 bg-white shadow-sm transition hover:border-primary-200 hover:shadow-md">
                    <div class="aspect-[4/3] overflow-hidden bg-neutral-100">
                        <img src="<?= h($landingImg('home-pottery-ceramics.png')) ?>" alt="" class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]" loading="lazy">
                    </div>
                    <p class="px-4 py-3 text-sm font-semibold text-ink-900">Gốm</p>
                </a>
                <a href="<?= h($this->Url->build('/workshops#workshop-knitting')) ?>" class="group overflow-hidden rounded-2xl border border-neutral-200/80 bg-white shadow-sm transition hover:border-primary-200 hover:shadow-md">
                    <div class="aspect-[4/3] overflow-hidden bg-neutral-100">
                        <img src="<?= h($landingImg('home-knitting-pink.png')) ?>" alt="" class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]" loading="lazy">
                    </div>
                    <p class="px-4 py-3 text-sm font-semibold text-ink-900">Đan</p>
                </a>
                <div class="overflow-hidden rounded-2xl border border-neutral-200/80 bg-white shadow-sm">
                    <div class="aspect-[4/3] overflow-hidden bg-neutral-100">
                        <img src="<?= h($landingImg('home-workshop-classroom.png')) ?>" alt="Phòng hội thảo ấm áp với các trạm riêng và ánh sáng mềm" class="h-full w-full object-cover" loading="lazy">
                    </div>
                    <p class="px-4 py-3 text-sm font-semibold text-ink-900">Trạm của riêng bạn</p>
                    <p class="px-4 pb-3 text-xs leading-snug text-neutral-500">Không gian bàn được thiết lập trước khi bạn đến để bạn có thể ổn định và bắt đầu.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Value strip -->
    <section class="border-y border-neutral-200/60 bg-white py-14 md:py-16" aria-labelledby="value-heading">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <h2 id="value-heading" class="sr-only">Tại sao những người làm chọn chúng tôi</h2>
            <div class="grid gap-px overflow-hidden rounded-3xl border border-neutral-200/80 bg-neutral-200/60 shadow-sm md:grid-cols-2 lg:grid-cols-4">
                <?php
                $values = [
                    ['Được hướng dẫn bởi các giáo viên có kinh nghiệm', 'Bản demo kiên nhẫn, kiến thức thủ công thực sự và không gian để hỏi bất cứ điều gì.'],
                    ['Bao gồm vật liệu và công cụ', 'Đất sét, sáp, len, men: chúng tôi trang bị bàn để bạn có thể tập trung vào việc làm.'],
                    ['Định dạng thân thiện với người mới', 'Hội thảo được nhịp độ cho người lần đầu mà không nói ai xuống.'],
                    ['Bầu không khí studio ấm áp, chào đón', 'Ánh sáng mềm, năng lượng không vội vàng và một căn phòng cảm thấy như một nơi nghỉ dưỡng.'],
                ];
                foreach ($values as $pair):
                ?>
                    <div class="bg-white px-6 py-8 md:px-8">
                        <div class="h-px w-10 bg-primary-400/70" aria-hidden="true"></div>
                        <h3 class="mt-5 font-serif text-lg font-semibold text-ink-900"><?= h($pair[0]) ?></h3>
                        <p class="mt-3 text-sm leading-relaxed text-neutral-600"><?= h($pair[1]) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Booking journey: interactive diagram -->
    <section class="py-16 md:py-24" aria-labelledby="journey-heading">
        <style>
            #booking-journey-diagram .journey-tab-active {
                transform: scale(1.02);
                box-shadow: 0 10px 26px -8px rgba(15, 23, 42, 0.16);
            }
            @media (min-width: 768px) {
                #booking-journey-diagram .journey-tab {
                    border-left-width: 3px;
                    border-left-style: solid;
                    border-left-color: transparent;
                }
                #booking-journey-diagram .journey-tab.journey-tab-active {
                    transform: translateX(2px) scale(1.01);
                    border-left-color: #752f3f;
                }
            }
            @media (prefers-reduced-motion: reduce) {
                #booking-journey-diagram .journey-tab-active { transform: none; }
                #booking-journey-diagram .journey-tab { animation: none !important; opacity: 1 !important; }
                #booking-journey-diagram [data-journey-visual],
                #booking-journey-diagram [data-journey-copy] { transition-duration: 0.01ms !important; }
            }
        </style>
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <h2 id="journey-heading" class="font-serif text-3xl font-semibold tracking-tight text-ink-900 md:text-4xl">
                Từ đặt chỗ đến làm
            </h2>
            <p class="mt-3 max-w-2xl text-neutral-600">
                Một đường dẫn thẳng từ sự tò mò đến đất sét \(sáp hoặc len\) trong tay bạn.
            </p>

            <?php
            $journeySteps = [
                ['Chọn hội thảo của bạn', 'Duyệt hội thảo theo craft và tốc độ của bạn.', '01'],
                ['Đặt chỗ của bạn', 'Chọn hội thảo yêu thích của bạn và xác nhận trực tuyến.', '02'],
                ['Đến và tạo ra', 'Bước vào studio sẵn sàng sử dụng và tận hưởng trải nghiệm.', '03'],
            ];
            $journeyImages = [
                $landingImg('home-pottery-painted-cup.png'),
                $landingImg('home-knitting-cozy.png'),
                $landingImg('home-workshop-classroom.png'),
            ];
            $journeyAlts = [
                'Cốc gốm vẽ tay giữa các mảnh biscuit trong studio',
                'Đôi tay đan vải sọc với len trên bàn gỗ',
                'Phòng hội thảo với các bàn riêng sẵn sàng cho lớp có hướng dẫn',
            ];
            ?>

            <div id="booking-journey-diagram" class="mx-auto mt-8 max-w-3xl lg:max-w-4xl" data-journey-root>
                <div class="grid items-start gap-6 md:grid-cols-[minmax(0,11.5rem)_1fr] md:gap-8 lg:grid-cols-[minmax(0,13rem)_1fr]">
                    <!-- Image left -->
                    <div class="mx-auto w-full max-w-[200px] md:mx-0 md:max-w-none">
                        <div class="relative aspect-[4/5] w-full overflow-hidden rounded-2xl border border-neutral-200/90 bg-neutral-200 shadow-md ring-1 ring-black/[0.04]">
                            <?php foreach ($journeyImages as $i => $src): ?>
                                <img
                                    src="<?= h($src) ?>"
                                    alt="<?= h($journeyAlts[$i]) ?>"
                                    width="520"
                                    height="650"
                                    loading="<?= $i === 0 ? 'eager' : 'lazy' ?>"
                                    decoding="async"
                                    data-journey-visual="<?= (int)$i ?>"
                                    class="absolute inset-0 h-full w-full object-cover transition-[opacity,transform] duration-700 ease-out <?= $i === 0 ? 'opacity-100 scale-100' : 'opacity-0 scale-[1.03]' ?>"
                                >
                            <?php endforeach; ?>
                            <div class="pointer-events-none absolute inset-x-0 bottom-0 h-1/3 bg-gradient-to-t from-ink-950/35 to-transparent" aria-hidden="true"></div>
                        </div>
                    </div>

                    <!-- Steps + copy right -->
                    <div class="min-w-0 md:pt-0.5">
                        <div
                            class="mb-4 flex flex-wrap gap-2 md:mb-3 md:flex-col md:flex-nowrap md:gap-1.5"
                            role="tablist"
                            aria-label="Các bước Từ đặt chỗ đến làm"
                        >
                            <?php foreach ($journeySteps as $i => $s): ?>
                                <button
                                    type="button"
                                    id="journey-tab-<?= (int)$i ?>"
                                    role="tab"
                                    aria-selected="<?= $i === 0 ? 'true' : 'false' ?>"
                                    aria-controls="journey-panel-<?= (int)$i ?>"
                                    tabindex="<?= $i === 0 ? '0' : '-1' ?>"
                                    data-journey-tab="<?= (int)$i ?>"
                                    style="animation: fadeIn 0.55s cubic-bezier(0.22, 1, 0.36, 1) <?= (int)$i * 120 ?>ms both;"
                                    class="journey-tab group relative z-10 flex w-full min-w-0 flex-col items-start gap-0.5 rounded-xl border border-neutral-200/80 bg-white/80 px-3 py-2 text-left shadow-sm transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] hover:border-primary-200/50 hover:bg-white <?= $i === 0
                                        ? 'border-primary-200/90 bg-white journey-tab-active'
                                        : '' ?>"
                                >
                                    <span class="text-[0.6rem] font-bold uppercase tracking-[0.24em] text-primary-600"><?= h($s[2]) ?></span>
                                    <span class="text-xs font-semibold leading-snug text-ink-900 sm:text-[0.8125rem]"><?= h($s[0]) ?></span>
                                </button>
                            <?php endforeach; ?>
                        </div>

                        <div class="relative min-h-[7.5rem]" aria-live="polite">
                            <?php foreach ($journeySteps as $i => $s): ?>
                                <div
                                    id="journey-panel-<?= (int)$i ?>"
                                    role="tabpanel"
                                    aria-labelledby="journey-tab-<?= (int)$i ?>"
                                    data-journey-copy="<?= (int)$i ?>"
                                    aria-hidden="<?= $i === 0 ? 'false' : 'true' ?>"
                                    class="absolute inset-x-0 top-0 transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] <?= $i === 0
                                        ? 'z-10 translate-y-0 opacity-100'
                                        : 'z-0 translate-y-5 opacity-0 pointer-events-none' ?>"
                                >
                                    <p class="text-[0.6rem] font-semibold uppercase leading-snug tracking-[0.18em] text-primary-600">
                                        <?= h($journeyAlts[$i]) ?>
                                    </p>
                                    <h3 class="mt-1.5 font-serif text-lg font-semibold tracking-tight text-ink-900 sm:text-xl">
                                        <?= h($s[0]) ?>
                                    </h3>
                                    <p class="mt-2 text-xs leading-relaxed text-neutral-600 sm:text-sm">
                                        <?= h($s[1]) ?>
                                    </p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Studio atmosphere -->
    <section class="border-t border-neutral-200/60 bg-studio-mist/50 py-16 md:py-24" aria-labelledby="atmosphere-heading">
        <div class="mx-auto grid max-w-7xl items-center gap-12 px-6 lg:grid-cols-2 lg:gap-16 lg:px-8">
            <div>
                <h2 id="atmosphere-heading" class="font-serif text-3xl font-semibold tracking-tight text-ink-900 md:text-4xl">
                    Hơn là một hội thảo, đó là một sự sáng tạo lại
                </h2>
                <p class="mt-6 text-neutral-600 leading-relaxed">
                    Bước xa khỏi màn hình và thông báo trong vài giờ. Làm việc bằng đôi tay của bạn, chia sẻ bàn với những người cũng đang học, và ổn định vào nhịp điệu chậm hơn. Studio của chúng tôi thoải mái và xã hội mà không cảm thấy đông đúc, được làm cho sự sáng tạo xúc giác và cuộc trò chuyện thực.
                </p>
            </div>
            <div class="relative grid grid-cols-12 gap-3">
                <div class="col-span-7 overflow-hidden rounded-3xl border border-neutral-200/80 shadow-soft">
                    <img src="<?= h($landingImg('home-pottery-painted-cup.png')) ?>" alt="Cốc gốm vẽ tay với chi tiết men đầy màu sắc trên bàn" class="aspect-[4/5] h-full w-full object-cover" loading="lazy">
                </div>
                <div class="col-span-5 flex flex-col gap-3 pt-8">
                    <div class="overflow-hidden rounded-2xl border border-neutral-200/80 shadow-sm">
                        <img src="<?= h($landingImg('home-knitting-cozy.png')) ?>" alt="Đan với kim gỗ, len và len trên chăn mềm" class="aspect-square w-full object-cover" loading="lazy">
                    </div>
                    <div class="overflow-hidden rounded-2xl border border-neutral-200/80 shadow-sm">
                        <img src="<?= h($landingImg('home-candle-workspace.png')) ?>" alt="Lọ nến, sáp và vật liệu bấc trên bàn thủ công" class="aspect-[5/4] w-full object-cover" loading="lazy">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials: rotating carousel -->
    <section class="py-16 md:py-24" aria-labelledby="testimonials-heading">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <h2 id="testimonials-heading" class="font-serif text-3xl font-semibold tracking-tight text-ink-900 md:text-4xl">
                Tiếng nói từ studio
            </h2>
            <p class="mt-3 max-w-xl text-neutral-600">
                Lời nói thật từ những người đã ngồi tại bàn của chúng tôi, chia sẻ cho bất kỳ ai vẫn đang quyết định.
            </p>

            <div
                class="mx-auto mt-10 max-w-3xl"
                data-testimonial-carousel
            >
                <p id="testimonial-carousel-status" class="sr-only" aria-live="polite"></p>
                <div class="relative min-h-[22rem] md:min-h-[21rem]">
                    <?php foreach ($studioTestimonials as $i => $t): ?>
                        <?php
                        $first = preg_split('/\s+/u', trim((string) $t['name']), 2)[0] ?? '';
                        $initial = $first !== '' ? mb_strtoupper(mb_substr($first, 0, 1)) : '?';
                        ?>
                        <figure
                            data-testimonial-slide="<?= (int)$i ?>"
                            aria-hidden="<?= $i === 0 ? 'false' : 'true' ?>"
                            class="absolute inset-0 flex flex-col rounded-3xl border border-neutral-200/80 bg-white p-7 shadow-soft transition-all duration-700 ease-out md:p-10 <?= $i === 0
                                ? 'z-10 translate-y-0 opacity-100'
                                : 'z-0 translate-y-4 opacity-0 pointer-events-none' ?>"
                        >
                            <blockquote class="font-serif text-xl font-normal leading-snug text-ink-900 md:text-2xl md:leading-tight">
                                “<?= h($t['quote']) ?>”
                            </blockquote>
                            <figcaption class="mt-6 flex flex-wrap items-center gap-3 border-t border-neutral-100 pt-6 md:mt-8">
                                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-primary-100 text-sm font-bold text-primary-800 ring-2 ring-white shadow-sm" aria-hidden="true"><?= h($initial) ?></span>
                                <div class="min-w-0">
                                    <cite class="not-italic text-sm font-semibold text-ink-900"><?= h($t['name']) ?></cite>
                                    <p class="text-xs text-neutral-500"><?= h($t['role']) ?></p>
                                </div>
                            </figcaption>
                            <?php if (!empty($t['tags'])): ?>
                                <div class="mt-4 flex flex-wrap gap-2">
                                    <?php foreach ($t['tags'] as $tag): ?>
                                        <span class="rounded-full bg-studio-mist px-3 py-1 text-xs font-medium text-neutral-600"><?= h($tag) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </figure>
                    <?php endforeach; ?>
                </div>

                <div class="mt-8 flex justify-center" role="group" aria-label="Chọn lời chứng thực">
                    <div class="flex items-center justify-center gap-2">
                        <?php foreach ($studioTestimonials as $i => $t): ?>
                            <button
                                type="button"
                                data-testimonial-dot="<?= (int)$i ?>"
                                aria-label="Hiển thị trích dẫn từ <?= h($t['name']) ?>"
                                <?= $i === 0 ? 'aria-current="true"' : '' ?>
                                class="testimonial-dot h-2 rounded-full transition-all duration-300 ease-out focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 <?= $i === 0
                                    ? 'w-8 bg-primary-600'
                                    : 'w-2 bg-neutral-300 hover:bg-neutral-400' ?>"
                            ></button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section class="border-t border-neutral-200/60 bg-white py-16 md:py-24" id="faq" aria-labelledby="faq-heading">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="grid gap-12 lg:grid-cols-12 lg:gap-16">
                <div class="lg:col-span-4">
                    <h2 id="faq-heading" class="font-serif text-3xl font-semibold tracking-tight text-ink-900 md:text-4xl">
                        Câu hỏi, đã trả lời
                    </h2>
                    <p class="mt-4 text-neutral-600 leading-relaxed">
                        Đặt chỗ, vật liệu, thời gian: những điều thiết yếu, không có thuật ngữ.
                    </p>
                    <?= $this->Html->link(
                        'Xem tất cả câu hỏi thường gặp',
                        '/faqs',
                        ['class' => 'mt-8 inline-flex items-center justify-center rounded-full border border-neutral-300 bg-studio-ivory px-6 py-2.5 text-sm font-semibold text-ink-900 transition hover:border-primary-400 hover:bg-white']
                    ) ?>
                </div>
                <div class="lg:col-span-8">
                    <div class="space-y-3" id="faq-accordion">
                        <?php if (!empty($featuredFaqs)): ?>
                            <?php foreach ($featuredFaqs as $faq): ?>
                                <div class="faq-item overflow-hidden rounded-2xl border border-neutral-200/80 bg-studio-ivory/60">
                                    <button
                                        type="button"
                                        onclick="toggleFaq(this)"
                                        class="faq-question flex w-full items-center justify-between gap-4 px-5 py-5 text-left transition hover:bg-white/80 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-400 md:px-6"
                                    >
                                        <span class="font-medium text-ink-900"><?= h($faq->question) ?></span>
                                        <span class="faq-icon inline-flex shrink-0 text-neutral-400">
                                            <?= $this->element('ui_icon', ['name' => 'chevron_down', 'class' => 'h-5 w-5 transition-transform duration-300 ease-out']) ?>
                                        </span>
                                    </button>
                                    <div class="faq-answer max-h-0 overflow-hidden transition-all duration-300 ease-out px-5 md:px-6">
                                        <div class="border-t border-neutral-200/80 pb-5 pt-4 text-sm leading-relaxed text-neutral-600 md:pb-6">
                                            <?= nl2br(h($faq->answer)) ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-sm text-neutral-500">Chưa có câu hỏi thường gặp nào.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?= $this->element('site_footer') ?>
</div>

<script>
(function () {
    const root = document.getElementById('booking-journey-diagram');
    if (!root) {
        return;
    }
    const tabs = Array.from(root.querySelectorAll('[data-journey-tab]'));
    const imgs = Array.from(root.querySelectorAll('[data-journey-visual]'));
    const panels = Array.from(root.querySelectorAll('[data-journey-copy]'));

    function applyJourneyStep(idx) {
        const i = Math.max(0, Math.min(idx, tabs.length - 1));
        tabs.forEach((tab, j) => {
            const on = j === i;
            tab.setAttribute('aria-selected', on ? 'true' : 'false');
            tab.tabIndex = on ? 0 : -1;
            tab.classList.toggle('journey-tab-active', on);
            tab.classList.toggle('border-primary-200/90', on);
            tab.classList.toggle('bg-white', on);
            tab.classList.toggle('border-neutral-200/80', !on);
            tab.classList.toggle('bg-white/80', !on);
        });
        imgs.forEach((img, j) => {
            const on = j === i;
            img.classList.toggle('opacity-100', on);
            img.classList.toggle('opacity-0', !on);
            img.classList.toggle('scale-100', on);
            img.classList.toggle('scale-[1.03]', !on);
        });
        panels.forEach((panel, j) => {
            const on = j === i;
            panel.setAttribute('aria-hidden', on ? 'false' : 'true');
            panel.classList.toggle('z-10', on);
            panel.classList.toggle('z-0', !on);
            panel.classList.toggle('opacity-100', on);
            panel.classList.toggle('opacity-0', !on);
            panel.classList.toggle('translate-y-0', on);
            panel.classList.toggle('translate-y-5', !on);
            panel.classList.toggle('pointer-events-none', !on);
        });
    }

    tabs.forEach((tab, i) => {
        tab.addEventListener('click', () => applyJourneyStep(i));
        tab.addEventListener('keydown', (e) => {
            let next = i;
            if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
                e.preventDefault();
                next = (i + 1) % tabs.length;
            } else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
                e.preventDefault();
                next = (i - 1 + tabs.length) % tabs.length;
            } else if (e.key === 'Home') {
                e.preventDefault();
                next = 0;
            } else if (e.key === 'End') {
                e.preventDefault();
                next = tabs.length - 1;
            } else {
                return;
            }
            applyJourneyStep(next);
            tabs[next].focus();
        });
    });
})();

(function () {
    const root = document.querySelector('[data-hero-carousel]');
    if (!root) {
        return;
    }
    const slides = Array.from(root.querySelectorAll('[data-hero-slide]'));
    const dots = Array.from(root.querySelectorAll('[data-hero-dot]'));
    const statusEl = document.getElementById('hero-carousel-status');
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    let idx = 0;
    let timer = null;
    const everyMs = 5000;

    function announce() {
        if (!statusEl || !slides[idx]) {
            return;
        }
        const alt = slides[idx].getAttribute('alt') || '';
        statusEl.textContent = 'Studio image ' + (idx + 1) + ' of ' + slides.length + (alt ? ': ' + alt : '');
    }

    function show(i) {
        idx = (i + slides.length) % slides.length;
        slides.forEach((el, j) => {
            const on = j === idx;
            el.setAttribute('aria-hidden', on ? 'false' : 'true');
            el.classList.toggle('z-10', on);
            el.classList.toggle('opacity-100', on);
            el.classList.toggle('pointer-events-none', !on);
            el.classList.toggle('z-0', !on);
            el.classList.toggle('opacity-0', !on);
        });
        dots.forEach((d, j) => {
            const on = j === idx;
            if (on) {
                d.setAttribute('aria-current', 'true');
            } else {
                d.removeAttribute('aria-current');
            }
            d.classList.toggle('w-8', on);
            d.classList.toggle('bg-white', on);
            d.classList.toggle('w-2', !on);
            d.classList.toggle('bg-white/50', !on);
            d.classList.toggle('hover:bg-white/80', !on);
        });
        announce();
    }

    function stop() {
        if (timer !== null) {
            clearInterval(timer);
            timer = null;
        }
    }

    function start() {
        stop();
        if (reduceMotion || slides.length < 2) {
            return;
        }
        timer = window.setInterval(() => show(idx + 1), everyMs);
    }

    dots.forEach((d, i) => {
        d.addEventListener('click', () => {
            show(i);
            start();
        });
    });

    root.addEventListener('mouseenter', stop);
    root.addEventListener('mouseleave', start);
    root.addEventListener('focusin', stop);
    root.addEventListener('focusout', () => {
        window.setTimeout(() => {
            if (!root.contains(document.activeElement)) {
                start();
            }
        }, 0);
    });

    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            stop();
        } else {
            start();
        }
    });

    show(0);
    start();
})();

(function () {
    const root = document.querySelector('[data-testimonial-carousel]');
    if (!root) {
        return;
    }
    const slides = Array.from(root.querySelectorAll('[data-testimonial-slide]'));
    const dots = Array.from(root.querySelectorAll('[data-testimonial-dot]'));
    const statusEl = document.getElementById('testimonial-carousel-status');
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    let idx = 0;
    let timer = null;
    const everyMs = 5000;

    function announce() {
        if (!statusEl || !slides[idx]) {
            return;
        }
        const name = slides[idx].querySelector('cite');
        const n = name ? name.textContent.trim() : '';
        statusEl.textContent = 'Showing testimonial ' + (idx + 1) + ' of ' + slides.length + (n ? ': ' + n : '');
    }

    function show(i) {
        idx = (i + slides.length) % slides.length;
        slides.forEach((el, j) => {
            const on = j === idx;
            el.setAttribute('aria-hidden', on ? 'false' : 'true');
            el.classList.toggle('z-10', on);
            el.classList.toggle('translate-y-0', on);
            el.classList.toggle('opacity-100', on);
            el.classList.toggle('pointer-events-none', !on);
            el.classList.toggle('z-0', !on);
            el.classList.toggle('translate-y-4', !on);
            el.classList.toggle('opacity-0', !on);
        });
        dots.forEach((d, j) => {
            const on = j === idx;
            if (on) {
                d.setAttribute('aria-current', 'true');
            } else {
                d.removeAttribute('aria-current');
            }
            d.classList.toggle('w-8', on);
            d.classList.toggle('bg-primary-600', on);
            d.classList.toggle('w-2', !on);
            d.classList.toggle('bg-neutral-300', !on);
            d.classList.toggle('hover:bg-neutral-400', !on);
        });
        announce();
    }

    function stop() {
        if (timer !== null) {
            clearInterval(timer);
            timer = null;
        }
    }

    function start() {
        stop();
        if (reduceMotion || slides.length < 2) {
            return;
        }
        timer = window.setInterval(() => show(idx + 1), everyMs);
    }

    dots.forEach((d, i) => {
        d.addEventListener('click', () => {
            show(i);
            start();
        });
    });

    root.addEventListener('mouseenter', stop);
    root.addEventListener('mouseleave', start);
    root.addEventListener('focusin', stop);
    root.addEventListener('focusout', () => {
        window.setTimeout(() => {
            if (!root.contains(document.activeElement)) {
                start();
            }
        }, 0);
    });

    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            stop();
        } else {
            start();
        }
    });

    show(0);
    start();
})();

function toggleFaq(button) {
    const item = button.closest('.faq-item');
    const answer = item.querySelector('.faq-answer');
    const iconWrap = button.querySelector('.faq-icon');
    const chevron = iconWrap && iconWrap.querySelector('svg');
    const isOpen = answer.style.maxHeight && answer.style.maxHeight !== '0px';

    document.querySelectorAll('#faq-accordion .faq-answer').forEach((ans) => {
        if (ans !== answer) {
            ans.style.maxHeight = '0px';
            const otherChevron = ans.closest('.faq-item').querySelector('.faq-icon svg');
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









