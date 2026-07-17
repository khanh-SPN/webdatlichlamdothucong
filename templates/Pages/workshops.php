<?php
$this$this->assign('title', 'Hội thảo');

/** @var array<string, int|null> $workshopIds */
$workshopIds = $workshopIds ?? [];

$wimg = function (string $name) {
    return $this->Url->build('/img/landing/' . $name);
};

$workshops = [
    [
        'id' => 'candle',
        'title' => 'Trải nghiệm làm nến',
        'line' => 'Tìm hiểu các nguyên tắc cơ bản của làm nến trong một môi trường bình tĩnh, có hướng dẫn. Bạn sẽ làm việc với sáp, hương thơm và màu sắc để tạo ra các mảnh của riêng mình, với tất cả vật liệu được cung cấp. Phù hợp cho người mới bắt đầu và độ tuổi 12\+.',
        'label' => 'Phổ biến nhất',
        'image' => $wimg('home-candle-workspace.png'),
        'imageAlt' => 'Vật liệu làm nến: sáp trong lọ, bấc và công cụ trên bàn gỗ',
        'moods' => ['thư giãn', 'người mới', 'xã hội'],
        'hover' => ['Duration' => '2 đến 2.5 giờ', 'Level' => 'Chào đón người mới', 'Includes' => 'Sáp, bấc, bình và hướng dẫn'],
        'featured' => true,
    ],
    [
        'id' => 'pottery',
        'title' => 'Hội thảo studio gốm',
        'line' => 'Khám phá các kỹ thuật xây dựng tay và kỹ năng gốm cơ bản trong một cài đặt studio thư giãn. Hội thảo này giới thiệu chuẩn bị đất sét, định hình và hoàn thiện, với hướng dẫn phù hợp cho người mới bắt đầu. Tất cả vật liệu được bao gồm. Phù hợp cho độ tuổi 12\+.',
        'label' => 'Nhịp độ chậm',
        'image' => $wimg('home-pottery-ceramics.png'),
        'imageAlt' => 'Gốm men tay và các mảnh gốm trên kệ trong studio',
        'moods' => ['thực hành', 'người mới', 'xã hội'],
        'hover' => ['Duration' => '2.5 đến 3 giờ', 'Level' => 'Không cần kinh nghiệm', 'Includes' => 'Đất sét, công cụ và chuẩn bị nung'],
        'featured' => false,
    ],
    [
        'id' => 'knitting',
        'title' => 'Hội thảo đan',
        'line' => 'Phát triển các kỹ năng đan thiết yếu bao gồm mũi, độ căng và mẫu đơn giản trong một cài đặt nhóm hỗ trợ. Hoàn hảo cho người mới bắt đầu muốn xây dựng sự tự tin và tạo mảnh đầu tiên của họ. Vật liệu được cung cấp. Phù hợp cho độ tuổi 12\+.',
        'label' => 'Lựa chọn linh hoạt',
        'image' => $wimg('home-knitting-pink.png'),
        'imageAlt' => 'Đôi tay đan vải sọc hồng với len trên bàn gỗ',
        'moods' => ['thư giãn', 'xã hội', 'người mới'],
        'hover' => ['Duration' => '2 giờ', 'Level' => 'Chào đón người mới thực sự', 'Includes' => 'Len, kim và thực hành có hướng dẫn'],
        'featured' => false,
    ],
];

$featured = null;
$others = [];
foreach ($workshops as $w) {
    if (!empty($w['featured'])) {
        $featured = $w;
    } else {
        $others[] = $w;
    }
}
$ordered = $featured !== null ? array_merge([$featured], $others) : $workshops;
?>

<div class="min-h-screen bg-studio-ivory text-ink-900">
    <!-- Catalog hero: image + gradient overlay, distinct from home split hero -->
    <div id="workshops" class="relative scroll-mt-20 overflow-hidden border-b border-neutral-800/20 bg-ink-950 text-white">
        <div class="pointer-events-none absolute inset-0" aria-hidden="true">
            <img
                src="<?= h($wimg('home-pottery-painted-cup.png')) ?>"
                alt=""
                class="h-full w-full object-cover object-center scale-105 sm:scale-100"
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
                <span class="text-white/90">Hội thảo</span>
            </nav>
            <h1 class="mt-5 max-w-3xl font-serif text-xl font-semibold leading-tight tracking-tight sm:text-2xl lg:text-[3rem]">
                Danh mục hội thảo
            </h1>
            <p class="mt-4 max-w-2xl text-sm leading-relaxed text-white/80 sm:text-base lg:text-lg">
                So sánh các hội thảo cạnh nhau, lọc theo cách bạn thích làm, sau đó đặt hoặc gửi yêu cầu nhanh. Mọi thứ thân thiện với người mới với vật liệu được cung cấp.
            </p>
            <div class="mt-4 flex flex-wrap items-center gap-3">
                <?= $this->Html->link(
                    '<span class="inline-flex items-center gap-2">Đặt hội thảo' . $this->element('ui_icon', ['name' => 'calendar_days', 'class' => 'h-4 w-4']) . '</span>',
                    '/booking',
                    ['class' => 'inline-flex items-center justify-center rounded-lg bg-primary-400 px-3 py-2.5 text-sm font-semibold text-ink-950 transition hover:bg-primary-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-ink-900', 'escape' => false]
                ) ?>
                <span class="inline-flex items-center gap-2 rounded-lg border border-white/20 bg-white/5 px-4 py-2 text-xs font-medium text-white/80">
                    <span class="h-1.5 w-1.5 rounded-full bg-primary-400" aria-hidden="true"></span>
                    Chỗ tiếp theo cuối tuần này
                </span>
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-screen-2xl px-3 pb-16 pt-10 md:px-4 md:pb-20 md:pt-12">
        <div class="lg:grid lg:grid-cols-12 lg:gap-5 xl:gap-14">
            <!-- Sidebar: filters + anchors (catalog UX, not on home) -->
            <aside class="mb-5 lg:col-span-3 lg:mb-0" aria-label="Bộ lọc hội thảo">
                <div class="space-y-4 lg:sticky lg:top-24">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.12em] text-neutral-500">mood</p>
                        <div class="mt-3 flex flex-col gap-2" role="group" aria-label="Filter workshops by mood">
                            <button type="button" data-mood="all" class="mood-chip w-full rounded-lg border border-primary-600 bg-primary-50 px-4 py-2.5 text-left text-sm font-semibold text-primary-900 transition hover:border-primary-500">
                                Tất cả hội thảo
                            </button>
                            <button type="button" data-mood="Thư giãn" class="mood-chip w-full rounded-lg border border-neutral-200 bg-white px-4 py-2.5 text-left text-sm font-medium text-neutral-700 transition hover:border-primary-300">
                                Thư giãn
                            </button>
                            <button type="button" data-mood="handson" class="mood-chip w-full rounded-lg border border-neutral-200 bg-white px-4 py-2.5 text-left text-sm font-medium text-neutral-700 transition hover:border-primary-300">
                                Thực hành
                            </button>
                            <button type="button" data-mood="Xã hội" class="mood-chip w-full rounded-lg border border-neutral-200 bg-white px-4 py-2.5 text-left text-sm font-medium text-neutral-700 transition hover:border-primary-300">
                                Xã hội
                            </button>
                            <button type="button" data-mood="beginner" class="mood-chip w-full rounded-lg border border-neutral-200 bg-white px-4 py-2.5 text-left text-sm font-medium text-neutral-700 transition hover:border-primary-300">
                                Thân thiện với người mới
                            </button>
                        </div>
                    </div>
                    <div class="border-t border-neutral-200 pt-6">
                        <p class="text-xs font-bold uppercase tracking-[0.12em] text-neutral-500">Nhảy đến</p>
                        <ul class="mt-3 space-y-1 text-sm">
                            <?php foreach ($ordered as $jw): ?>
                                <li>
                                    <a href="#workshop-<?= h($jw['id']) ?>" class="text-primary-700 underline-offset-2 transition hover:text-primary-900 hover:underline"><?= h($jw['title']) ?></a>
                                </li>
                            <?php endforeach; ?>
                            <li>
                                <a href="#passes" class="text-neutral-600 underline-offset-2 transition hover:text-ink-900 hover:underline">Vé nhiều lớp</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </aside>

            <!-- Main list: horizontal catalog rows -->
            <div class="lg:col-span-9">
                <div class="mb-3 flex flex-wrap gap-2 lg:hidden" role="group" aria-label="Filter workshops by mood">
                    <button type="button" data-mood="all" class="mood-chip rounded-full border border-primary-500 bg-primary-50 px-3 py-1.5 text-xs font-semibold text-primary-800">Tất cả</button>
                    <button type="button" data-mood="Thư giãn" class="mood-chip rounded-full border border-neutral-200 bg-white px-3 py-1.5 text-xs font-medium text-neutral-700">Thư giãn</button>
                    <button type="button" data-mood="handson" class="mood-chip rounded-full border border-neutral-200 bg-white px-3 py-1.5 text-xs font-medium text-neutral-700">Thực hành</button>
                    <button type="button" data-mood="Xã hội" class="mood-chip rounded-full border border-neutral-200 bg-white px-3 py-1.5 text-xs font-medium text-neutral-700">Xã hội</button>
                    <button type="button" data-mood="beginner" class="mood-chip rounded-full border border-neutral-200 bg-white px-3 py-1.5 text-xs font-medium text-neutral-700">Người mới</button>
                </div>

                <div class="space-y-5">
                    <?php foreach ($ordered as $w): ?>
                        <?php
                        $moodsAttr = h(implode(' ', $w['moods']));
                        ?>
                        <article
                            id="workshop-<?= h($w['id']) ?>"
                            data-workshop-card
                            data-moods="<?= $moodsAttr ?>"
                            class="group overflow-hidden rounded-xl border border-neutral-200/90 bg-white shadow-sm transition hover:border-primary-200/80 hover:shadow-md"
                        >
                            <div class="flex flex-col md:flex-row md:items-stretch md:min-h-[15rem]">
                                <div class="relative w-full shrink-0 overflow-hidden bg-neutral-200 md:w-[40%] md:self-stretch">
                                    <img
                                        src="<?= h($w['image']) ?>"
                                        alt="<?= h($w['imageAlt']) ?>"
                                        class="h-52 w-full object-cover transition duration-500 group-hover:scale-[1.03] md:h-full md:min-h-[15rem]"
                                        loading="lazy"
                                    >
                                    <span class="absolute left-3 top-3 rounded-md bg-white/95 px-2.5 py-1 text-xs font-bold uppercase tracking-wide text-primary-800 shadow-sm">
                                        <?= h($w['label']) ?>
                                    </span>
                                </div>
                                <div class="flex min-w-0 flex-1 flex-col justify-between p-3 md:p-4">
                                    <div class="min-w-0 space-y-5">
                                        <h2 class="font-serif text-xl font-semibold text-ink-900 md:text-lg"><?= h($w['title']) ?></h2>
                                        <p class="text-sm leading-relaxed text-neutral-600 md:text-[0.9375rem]"><?= h($w['line']) ?></p>
                                        <dl class="grid grid-cols-1 items-start gap-x-6 gap-y-3 border-t border-neutral-100 pt-4 text-sm sm:grid-cols-3">
                                            <?php foreach ($w['hover'] as $k => $v): ?>
                                                <div class="min-w-0">
                                                    <dt class="text-xs font-medium uppercase tracking-[0.05em] text-neutral-400"><?= h($k) ?></dt>
                                                    <dd class="mt-0.5 font-medium leading-snug text-ink-900"><?= $v ?></dd>
                                                </div>
                                            <?php endforeach; ?>
                                        </dl>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-x-3 gap-y-2 border-t border-neutral-100 pt-5">
                                        <?php
                                        $workshopId = $workshopIds[$w['id']] ?? null;
                                        $bookUrl = $workshopId !== null
                                            ? ['controller' => 'Bookings', 'action' => 'add', '?' => ['workshop_id' => $workshopId]]
                                            : '/booking';
                                        ?>
                                        <?= $this->Html->link(
                                            'Đặt ngay',
                                            $bookUrl,
                                            ['class' => 'inline-flex shrink-0 items-center justify-center rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold leading-none text-white transition hover:bg-primary-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-400']
                                        ) ?>
                                        <span class="inline-flex items-center text-xs font-medium leading-none text-neutral-500">Bao gồm vật liệu · Nhóm nhỏ</span>
                                    </div>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>

                <div id="passes" class="mt-4 scroll-mt-24">
                    <?= $this->Html->link(
                        '<div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between sm:gap-3">'
                        . '<div><p class="text-xs font-bold uppercase tracking-[0.12em] text-sage-600">Bundle</p><p class="mt-1 font-serif text-lg font-semibold text-ink-900">Vé nhiều lớp</p><p class="mt-1 text-sm text-neutral-600">Kết hợp làm nến, gốm và đan qua nhiều lần ghé thăm với mức giá tốt hơn.</p></div>'
                        . '<span class="inline-flex shrink-0 items-center gap-1 text-sm font-semibold text-primary-700">' . 'Xem trong đặt chỗ' . $this->element('ui_icon', ['name' => 'chevron_right', 'class' => 'h-5 w-5']) . '</span></div>',
                        '/booking',
                        ['class' => 'block rounded-xl border border-dashed border-primary-300/80 bg-gradient-to-br from-white to-primary-50/40 p-3 transition hover:border-primary-400 hover:shadow-sm md:p-4', 'escape' => false]
                    ) ?>
                </div>

                <section class="mt-3 rounded-2xl border border-neutral-200/80 bg-white p-3 shadow-sm md:p-4" aria-labelledby="materials-included-heading">
                    <div class="flex flex-wrap items-end justify-between gap-3">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.12em] text-neutral-500">Được bao gồm</p>
                            <h2 id="materials-included-heading" class="mt-2 font-serif text-lg font-semibold text-ink-900">Materials included</h2>
                            <p class="mt-2 max-w-2xl text-sm leading-relaxed text-neutral-600">
                                Mỗi hội thảo bao gồm công cụ, vật liệu và hướng dẫn của giáo viên cần thiết để hoàn thành dự án cốt lõi.
                                If you want to bring your own items, you’re welcome to—just check with the instructor first.
                            </p>
                        </div>
                        <?= $this->Html->link(
                            'Đặt hội thảo',
                            '/booking',
                            ['class' => 'inline-flex items-center justify-center rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-primary-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-400']
                        ) ?>
                    </div>

                    <div class="mt-4 grid gap-4 md:grid-cols-3">
                        <div class="rounded-xl border border-neutral-200/70 bg-neutral-50/50 p-5">
                            <h3 class="font-serif text-lg font-semibold text-ink-900">Candle making</h3>
                            <ul class="mt-3 space-y-2 text-sm leading-relaxed text-neutral-700">
                                <li><span class="font-medium text-ink-900">Sáp & bấc</span> được so khớp với kích thước bình để cháy sạch.</li>
                                <li><span class="font-medium text-ink-900">Hương thơm & màu sắc</span> với hướng dẫn sử dụng an toàn và mẹo pha trộn.</li>
                                <li><span class="font-medium text-ink-900">Công cụ & thiết bị an toàn</span> \(nhiệt kế, bình đổ, khuấy, tạp dề\).</li>
                            </ul>
                        </div>
                        <div class="rounded-xl border border-neutral-200/70 bg-neutral-50/50 p-5">
                            <h3 class="font-serif text-lg font-semibold text-ink-900">Pottery</h3>
                            <ul class="mt-3 space-y-2 text-sm leading-relaxed text-neutral-700">
                                <li><span class="font-medium text-ink-900">Clay & tools</span> for hand-building, shaping, and finishing.</li>
                                <li><span class="font-medium text-ink-900">Technique demos</span> plus one-on-one help while you work.</li>
                                <li><span class="font-medium text-ink-900">Thiết yếu studio</span> \(bề mặt làm việc, vật liệu dọn dẹp, cắt cơ bản\).</li>
                            </ul>
                        </div>
                        <div class="rounded-xl border border-neutral-200/70 bg-neutral-50/50 p-5">
                            <h3 class="font-serif text-lg font-semibold text-ink-900">Knitting</h3>
                            <ul class="mt-3 space-y-2 text-sm leading-relaxed text-neutral-700">
                                <li><span class="font-medium text-ink-900">Len & kim</span> được cung cấp trong studio để thực hành và lấy mẫu.</li>
                                <li><span class="font-medium text-ink-900">Hướng dẫn mẫu</span> từ lần cast-on đầu tiên đến khi hoàn thiện.</li>
                                <li><span class="font-medium text-ink-900">Khắc phục sự cố</span> giúp đỡ về độ căng, mũi rơi và đọc mẫu.</li>
                            </ul>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <?= $this->element('site_footer') ?>
</div>

<script>
(function () {
    const chips = document.querySelectorAll('.mood-chip');
    const cards = document.querySelectorAll('[data-workshop-card]');
    if (!chips.length || !cards.length) return;

    function setMood(mood) {
        chips.forEach((c) => {
            const m = c.getAttribute('data-mood');
            const on = m === mood;
            const isSidebar = c.classList.contains('w-full');
            if (isSidebar) {
                c.classList.toggle('border-primary-600', on);
                c.classList.toggle('bg-primary-50', on);
                c.classList.toggle('font-semibold', on);
                c.classList.toggle('font-medium', !on);
                c.classList.toggle('text-primary-900', on);
                c.classList.toggle('border-neutral-200', !on);
                c.classList.toggle('bg-white', !on);
                c.classList.toggle('text-neutral-700', !on);
            } else {
                c.classList.toggle('border-primary-500', on);
                c.classList.toggle('bg-primary-50', on);
                c.classList.toggle('text-primary-800', on);
                c.classList.toggle('font-semibold', on);
                c.classList.toggle('font-medium', !on);
                c.classList.toggle('border-neutral-200', !on);
                c.classList.toggle('bg-white', !on);
                c.classList.toggle('text-neutral-700', !on);
            }
        });
        cards.forEach((card) => {
            const moods = (card.getAttribute('data-moods') || '').trim().split(/\s+/).filter(Boolean);
            const match = mood === 'all' || moods.includes(mood);
            card.style.opacity = match ? '1' : '0.3';
            card.style.filter = match ? '' : 'grayscale(0.35)';
            card.style.pointerEvents = match ? '' : 'none';
        });
    }

    chips.forEach((chip) => {
        chip.addEventListener('click', () => setMood(chip.getAttribute('data-mood') || 'all'));
    });
})();
</script>







