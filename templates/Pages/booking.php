<?php
$this$this->assign('title', 'Đặt workshop Sáng Tạo Của Bạn');
$identity = $this->request->getAttribute('identity');

$bookingLoginUrl = '/users/login?redirect=' . urlencode($this->request->getRequestTarget());

$bookingRegisterUrl = '/users/register?redirect=' . urlencode($this->request->getRequestTarget());

?>

<div class="bg-studio-ivory text-ink-900">
    <!-- Editorial intro: matches home shell (ivory, serif, soft accents). No full bleed photo hero. -->
    <section
        class="relative -mt-[4.75rem] border-b border-neutral-200/60 pt-[4.75rem] sm:-mt-20 sm:pt-20 md:-mt-[5.25rem] md:pt-[5.25rem]"
        aria-labelledby="booking-hero-heading"
    >
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_65%_45%_at_90%_0%,rgba(117,47,63,0.08),transparent_50%)]" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -left-20 top-1/4 h-56 w-56 rounded-full bg-primary-100/35 blur-3xl" aria-hidden="true"></div>
        <div class="relative z-10 mx-auto max-w-screen-2xl px-3 pb-10 pt-8 md:px-4 md:pb-12 md:pt-10">
            <nav class="text-xs font-medium text-neutral-500" aria-label="Breadcrumb">
                <?= $this->Html->link('Trang chủ', '/', ['class' => 'transition hover:text-primary-700']) ?>
                <span class="mx-2 text-neutral-300" aria-hidden="true">/</span>
                <span class="text-ink-900">Đặt</span>
            </nav>
            <p class="mt-3 text-xs font-semibold uppercase tracking-[0.18em] text-primary-600">
                Đặt chỗ
            </p>
            <h1 id="booking-hero-heading" class="mt-3 max-w-2xl font-serif text-xl font-semibold leading-[1.1] tracking-tight text-ink-900 sm:text-2xl lg:text-[3rem]">
                Đặt hội thảo sáng tạo của bạn
            </h1>
            <p class="mt-4 max-w-xl text-base leading-relaxed text-neutral-600 sm:text-lg lg:text-xl">
                Chọn một hoặc nhiều workshop với ngày, sau đó thanh toán. Nhóm nhỏ và vật liệu được bao gồm.
            </p>
            <p class="mt-2 text-xs font-medium text-neutral-500">
                Đặt nhiều hội thảo trong một lần thanh toán.
            </p>
            <a
                href="#booking-form"
                class="mt-3 inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.15em] text-neutral-400 transition hover:text-primary-600"
            >
                <span>Bắt đầu đặt chỗ</span>
                <svg class="h-4 w-4 animate-float" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                </svg>
            </a>
        </div>
    </section>

    <div class="py-6 md:py-8" id="booking-form">
    <div class="mx-auto max-w-screen-2xl px-3 lg:px-4">

        <?php if ($identity): ?>
            <div class="mb-3 flex flex-wrap items-center gap-3 rounded-2xl border border-primary-200/60 bg-primary-50/50 px-4 py-3 text-sm text-neutral-800 md:px-5">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-primary-100 text-primary-700" aria-hidden="true">
                    <?= $this->element('ui_icon', ['name' => 'sparkles', 'class' => 'h-4 w-4']) ?>
                </span>
                <span>Đã đăng nhập với tư cách <strong class="font-semibold text-ink-900"><?= h($identity->email ?? 'member') ?></strong>. Hoàn thành các bước bên dưới để tiếp tục thanh toán an toàn.</span>
            </div>
        <?php else: ?>
            <div class="mb-3 flex flex-col gap-3 rounded-2xl border border-amber-200/70 bg-amber-50/70 px-4 py-4 text-sm text-amber-950 sm:flex-row sm:items-center sm:justify-between sm:gap-4 md:px-5">
                <p class="text-amber-950/90">Đăng nhập để xác nhận và thanh toán. Bạn có thể chọn workshop và date trước.</p>
                <div class="flex flex-wrap gap-2">
                    <?= $this->Html->link(
                        'Đăng nhập',
                        $bookingLoginUrl,
                        ['class' => 'inline-flex items-center justify-center rounded-full bg-ink-900 px-5 py-2 text-xs font-semibold text-white transition hover:bg-ink-800']
                    ) ?>
                    <?= $this->Html->link(
                        'Tạo tài khoản',
                        $bookingRegisterUrl,
                        ['class' => 'inline-flex items-center justify-center rounded-full border border-amber-300/80 bg-white px-5 py-2 text-xs font-semibold text-amber-950 transition hover:bg-amber-100/50']
                    ) ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="grid gap-4 lg:grid-cols-12 lg:gap-5 lg:items-start">

            <!-- Form -->
            <div class="lg:col-span-7">
                <div class="overflow-hidden rounded-2xl border border-neutral-200/70 bg-white shadow-[0_20px_50px_-12px_rgba(15,23,42,0.08)] ring-1 ring-neutral-900/[0.03]">
                    <!-- Progress -->
                    <div class="border-b border-neutral-100 bg-gradient-to-b from-neutral-50/90 to-white px-5 py-5 sm:px-4 sm:py-3" aria-label="tiến độ đặt chỗ">
                        <div class="flex flex-wrap items-end justify-between gap-2">
                            <p class="text-xs font-bold uppercase tracking-[0.15em] text-neutral-400">Các bước</p>
                            <p id="booking-step-hint" class="text-xs font-medium text-neutral-500" aria-live="polite">Chọn workshop của bạn</p>
                        </div>
                        <div class="mt-3 h-1.5 overflow-hidden rounded-full bg-neutral-200/80">
                            <div id="booking-progress-fill" class="h-full w-0 rounded-full bg-gradient-to-r from-primary-500 to-primary-400 transition-all duration-500 ease-out"></div>
                        </div>
                        <div class="mt-3 grid grid-cols-2 gap-2 sm:gap-4">
                            <div class="flex flex-col items-center gap-2 text-center">
                                <span id="booking-step-1" class="booking-step-pill flex h-11 w-11 shrink-0 items-center justify-center rounded-full border-2 border-neutral-200 bg-white shadow-sm transition-all">
                                    <svg class="booking-step-icon hidden h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span class="booking-step-num text-xs font-bold text-neutral-500">1</span>
                                </span>
                                <span class="text-xs font-semibold uppercase tracking-[0.05em] text-neutral-500">Chọn workshop</span>
                            </div>
                            <div class="flex flex-col items-center gap-2 text-center">
                                <span id="booking-step-2" class="booking-step-pill flex h-11 w-11 shrink-0 items-center justify-center rounded-full border-2 border-neutral-200 bg-white shadow-sm transition-all">
                                    <svg class="booking-step-icon hidden h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span class="booking-step-num text-xs font-bold text-neutral-500">2</span>
                                </span>
                                <span class="text-xs font-semibold uppercase tracking-[0.05em] text-neutral-500">Thanh toán</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 md:p-4 md:pt-7">
                        <?= $this->Form->create(null, ['class' => 'space-y-3 md:space-y-4', 'id' => 'booking-main-form']) ?>

                        <div class="rounded-2xl border border-neutral-100 bg-neutral-50/40 p-5 md:p-3">
                            <div class="mb-4 flex items-center justify-between gap-2">
                                <div class="flex items-center gap-2">
                                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-white text-xs font-bold text-primary-700 shadow-sm ring-1 ring-neutral-200/80">1</span>
                                    <span class="text-xs font-bold uppercase tracking-[0.12em] text-neutral-500">Chọn workshop</span>
                                </div>
                                <button
                                    type="button"
                                    id="booking-add-row"
                                    class="inline-flex items-center gap-1.5 rounded-full border border-primary-200 bg-white px-3 py-1.5 text-xs font-semibold text-primary-700 transition hover:bg-primary-50"
                                >
                                    <span>+</span>
                                    <span>Thêm workshop khác</span>
                                </button>
                            </div>
                            <p class="text-xs leading-relaxed text-neutral-500">
                                Chọn một hoặc nhiều workshop với date của họ. Bạn có thể thêm tối đa 6 workshop.
                            </p>

                            <div id="booking-rows-container" class="mt-4 space-y-4">
                                <!-- Dynamic rows will be inserted here -->
                            </div>

                            <p id="booking-rows-error" class="hidden mt-3 text-xs font-semibold text-red-600" role="alert"></p>
                        </div>

                        <div id="booking-payment-block" class="hidden space-y-4 rounded-2xl border border-neutral-100 bg-neutral-50/40 p-5 md:p-3">
                            <div class="flex items-center gap-2">
                                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-white text-xs font-bold text-primary-700 shadow-sm ring-1 ring-neutral-200/80">2</span>
                                <span class="text-xs font-bold uppercase tracking-[0.12em] text-neutral-500">thanh toán</span>
                            </div>
                            <?= $this->Form->hidden('thanh toán_method', ['value' => 'stripe']) ?>
                            <div class="rounded-2xl border border-primary-200/70 bg-white p-4 shadow-sm ring-1 ring-primary-500/[0.06] md:p-5">
                                <p class="text-sm font-semibold text-ink-900">Thanh toán with card (Stripe)</p>
                                <p class="mt-2 text-xs leading-relaxed text-neutral-600">
                                    Checkout is handled by Stripe. When you continue, you will open Stripe’s secure page to enter your card and complete thanh toán, then you will be sent back here.
                                </p>
                                <ul class="mt-3 space-y-1.5 text-xs text-neutral-600">
                                    <li class="flex gap-2">
                                        <span class="mt-0.5 text-primary-600" aria-hidden="true">✓</span>
                                        <span>Encrypted card processing; we do not store your full card Chi tiết.</span>
                                    </li>
                                    <li class="flex gap-2">
                                        <span class="mt-0.5 text-primary-600" aria-hidden="true">✓</span>
                                        <span>Đặt chỗ được xác nhận after thanh toán succeeds on Stripe.</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="pt-1">
                            <?php if ($identity): ?>
                                <?= $this->Form->button(
                                    'Tiếp tục thanh toán Stripe',
                                    [
                                        'class' => 'flex w-full items-center justify-center gap-2 rounded-2xl bg-primary-600 px-3 py-4 text-base font-semibold text-white shadow-lg shadow-primary-900/10 transition hover:bg-primary-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-400 focus-visible:ring-offset-2 active:scale-[0.99]',
                                    ]
                                ) ?>
                            <?php else: ?>
                                <?= $this->Html->link(
                                    'Đăng nhập để tiếp tục',
                                    $bookingLoginUrl,
                                    [
                                        'class' => 'flex w-full cursor-pointer items-center justify-center gap-2 rounded-2xl border-2 border-primary-600 bg-white px-3 py-4 text-base font-semibold text-primary-700 transition hover:bg-primary-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-400 focus-visible:ring-offset-2',
                                    ]
                                ) ?>
                                <p class="mt-3 text-center text-xs text-neutral-500">
                                    Sau khi đăng nhập you will return here to finish. New?
                                    <?= $this->Html->link('Create an account', $bookingRegisterUrl, ['class' => 'font-medium text-primary-700 underline decoration-primary-300 underline-offset-2 hover:text-primary-800']) ?>
                                </p>
                            <?php endif; ?>
                        </div>

                        <?= $this->Form->end() ?>
                    </div>

                    <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2 border-t border-neutral-100 bg-neutral-50/50 px-4 py-2.5 text-center text-xs text-neutral-500 md:justify-between md:px-4 md:text-left">
                        <span class="inline-flex items-center gap-1.5">
                            <span class="h-1.5 w-1.5 rounded-full bg-primary-500" aria-hidden="true"></span>
                            Secure checkout (Stripe)
                        </span>
                        <span>Email confirmation after thanh toán</span>
                    </div>
                </div>
            </div>

            <!-- Sidebar: short tips, scannable -->
            <aside class="lg:col-span-5 lg:sticky lg:top-28" aria-label="booking tips">
                <div class="space-y-4">
                    <!-- Tóm tắt đơn hàng -->
                    <div class="rounded-[1.5rem] border border-primary-200/70 bg-gradient-to-br from-primary-50/50 to-white p-5 shadow-[0_12px_40px_-12px_rgba(15,23,42,0.06)] md:p-3">
                        <div class="flex items-center justify-between gap-2">
                            <h3 class="font-serif text-base font-semibold text-ink-900 md:text-lg">Tóm tắt đơn hàng</h3>
                            <span id="booking-item-count" class="flex h-6 min-w-[1.5rem] items-center justify-center rounded-full bg-primary-100 px-2 text-xs font-bold text-primary-700">0</span>
                        </div>
                        <div id="booking-summary-items" class="mt-4 space-y-3">
                            <p class="text-sm text-neutral-500 italic">Chưa chọn hội thảo nào</p>
                        </div>
                        <div class="mt-4 border-t border-primary-200/50 pt-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-ink-900">subtotal</span>
                                <span id="booking-subtotal-price" class="font-serif text-lg font-semibold text-ink-900">$0</span>
                            </div>
                        </div>

                        <div id="booking-discount-block" class="hidden mt-3">
                            <div class="flex items-center justify-between rounded-lg bg-green-50 px-3 py-2">
                                <span class="text-xs font-semibold text-green-800">Giảm price tự động \(giảm 20% cho 2\+ workshop\)</span>
                                <span id="booking-discount-amount" class="font-serif text-sm font-bold text-green-700">-$0</span>
                            </div>
                        </div>

                        <div class="mt-4 border-t border-primary-200/50 pt-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-bold text-ink-900">total</span>
                                <span id="booking-total-price" class="font-serif text-lg font-bold text-primary-600">$0</span>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[1.5rem] border border-neutral-200/70 bg-white p-5 shadow-[0_12px_40px_-12px_rgba(15,23,42,0.06)] md:p-3">
                        <h3 class="font-serif text-base font-semibold text-ink-900 md:text-lg">Nhìn nhanh</h3>
                        <ul class="mt-4 space-y-3.5 text-sm leading-relaxed text-neutral-600">
                            <li class="flex gap-3">
                                <span class="mt-0.5 text-primary-600" aria-hidden="true"><?= $this->element('ui_icon', ['name' => 'lock_open', 'class' => 'h-5 w-5']) ?></span>
                                <span>Mô tả và price cập nhật ngay khi bạn chọn workshop.</span>
                            </li>
                            <li class="flex gap-3">
                                <span class="mt-0.5 text-primary-600" aria-hidden="true"><?= $this->element('ui_icon', ['name' => 'calendar_days', 'class' => 'h-5 w-5']) ?></span>
                                <span>Danh sách và lịch đồng bộ. Thay đổi workshop bất cứ lúc nào để làm mới date.</span>
                            </li>
                            <li class="flex gap-3">
                                <span class="mt-0.5 text-primary-600" aria-hidden="true"><?= $this->element('ui_icon', ['name' => 'question_mark_circle', 'class' => 'h-5 w-5']) ?></span>
                                <span>Câu hỏi\? Trò chuyện với trợ lý studio hoặc xem <?= $this->Html->link('FAQs', '/faqs', ['class' => 'font-medium text-primary-700 underline decoration-primary-300 underline-offset-2 hover:text-primary-800']) ?>.</span>
                            </li>
                        </ul>
                    </div>
                    <div class="rounded-[1.5rem] border border-dashed border-primary-200/80 bg-gradient-to-br from-primary-50/40 to-white p-5 md:p-3">
                        <p class="text-sm leading-relaxed text-neutral-700">
                            <strong class="text-ink-900">Đang duyệt\?</strong>
                            <?= $this->Html->link('So sánh workshop', '/workshops', ['class' => 'font-medium text-primary-700 underline decoration-primary-300 underline-offset-2 hover:text-primary-800']) ?>, then come back. Chat links can prefill this form for you.
                        </p>
                    </div>
                </div>
            </aside>
        </div>

        <?php if ($identity !== null): ?>
        <!-- booking history (signed-in users only) -->
        <section class="mt-20 md:mt-28" aria-labelledby="booking-history-heading">
            <div class="mb-5 flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                <div>
                    <h2 id="booking-history-heading" class="font-serif text-xl font-semibold tracking-tight text-ink-900 md:text-lg">
                        Đặt chỗ của bạn
                    </h2>
                </div>
            </div>

            <?php if (empty($bookingGroups)): ?>
                <div class="rounded-2xl border border-neutral-200/80 bg-white p-5 text-center shadow-soft md:p-14">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-primary-50 text-primary-600">
                        <?= $this->element('ui_icon', ['name' => 'calendar_days', 'class' => 'h-7 w-7']) ?>
                    </div>
                    <p class="mt-3 font-medium text-ink-900">Chưa có đặt chỗ nào</p>
                    <p class="mx-auto mt-2 max-w-md text-sm text-neutral-600">
                        Khi bạn hoàn thành đặt chỗ workshop, nó sẽ hiển thị ở đây với trạng thái và chi tiết thanh toán.
                    </p>
                </div>
            <?php else: ?>
                <div class="grid gap-3 md:grid-cols-2">
                    <?php foreach ($bookingGroups as $idx => $group): ?>
                        <?php
                        $count = count($group['bookings']);
                        $paymentClass = $group['paymentStatus'] === 'paid'
                            ? 'bg-primary-50 text-primary-900 ring-primary-100'
                            : 'bg-orange-50 text-orange-900 ring-orange-100';
                        $groupId = 'booking-group-' . $idx;
                        ?>
                        <article class="flex flex-col rounded-2xl border border-neutral-200/80 bg-white p-3 shadow-soft transition hover:border-primary-200/60 hover:shadow-md md:p-7">
                            <!-- Header -->
                            <div class="flex flex-wrap items-start justify-between gap-3 border-b border-neutral-100 pb-4">
                                <div class="min-w-0 flex-1">
                                    <h3 class="font-serif text-lg font-semibold text-ink-900 md:text-xl">
                                        <?= $count ?> <?= $count === 1 ? 'workshop' : 'workshops' ?>
                                        <?php if ($group['discountPercent'] > 0): ?>
                                            <span class="text-sm font-normal text-green-600">(<?= $group['discountPercent'] ?>% off)</span>
                                        <?php endif; ?>
                                    </h3>
                                    <p class="mt-1 text-sm text-neutral-500">
                                        booked <?= $group['created'] instanceof \dateTimeInterface ? $group['created']->format('l, j M Y') : h((string)$group['created']) ?>
                                    </p>
                                </div>
                                <span class="inline-flex shrink-0 rounded-full px-3 py-1 text-xs font-semibold ring-1 <?= h($paymentClass) ?>">
                                    <?= $group['paymentStatus'] === 'paid' ? 'paid' : 'Unpaid' ?>
                                </span>
                            </div>

                            <!-- price -->
                            <div class="mt-4 flex items-center justify-between">
                                <div>
                                    <?php if ($group['discountAmount'] > 0): ?>
                                        <p class="text-sm text-neutral-500 line-through">$<?= number_format($group['totalprice']) ?></p>
                                        <p class="text-lg font-bold text-green-600">$<?= number_format($group['finalprice']) ?></p>
                                    <?php else: ?>
                                        <p class="text-lg font-bold text-ink-900">$<?= number_format($group['totalprice']) ?></p>
                                    <?php endif; ?>
                                </div>
                                <button type="button" onclick="document.getElementById('<?= $groupId ?>').classList.toggle('hidden')" class="inline-flex items-center gap-1 text-sm font-medium text-primary-600 hover:text-primary-700 transition">
                                    <span>Chi tiết</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </button>
                            </div>

                            <!-- Chi tiết -->
                            <div id="<?= $groupId ?>" class="hidden mt-4 space-y-4 border-t border-neutral-100 pt-4">
                                <!-- workshop List -->
                                <div class="space-y-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-neutral-500">workshops</p>
                                    <?php foreach ($group['bookings'] as $b): ?>
                                        <?php
                                        $bdRaw = $b->booking_date;
                                        $dateDisplay = $bdRaw instanceof \dateTimeInterface
                                            ? $bdRaw->format('l, j M Y')
                                            : h((string)$bdRaw);
                                        $qty = $b->quantity ?? 1;
                                        $chỗLabel = $qty == 1 ? '1 chỗ' : $qty . ' chỗ';
                                        $itemprice = ($b->workshop->price ?? 0) * $qty;
                                        ?>
                                        <div class="flex items-start justify-between rounded-lg bg-neutral-50 p-3">
                                            <div class="min-w-0 flex-1">
                                                <p class="font-medium text-neutral-900"><?= h($b->workshop->workshop_name ?? 'workshop') ?></p>
                                                <p class="mt-1 text-xs text-neutral-600">
                                                    <?= h($dateDisplay) ?> • <?= $chỗLabel ?> @ $<?= number_format((float)($b->workshop->price ?? 0), 2) ?>/chỗ
                                                </p>
                                            </div>
                                            <p class="ml-2 shrink-0 text-right font-semibold text-neutral-900">
                                                $<?= number_format($itemprice, 2) ?>
                                            </p>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <!-- price Breakdown -->
                                <div class="rounded-lg border border-neutral-200/50 bg-neutral-50/50 p-3 space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-neutral-600">subtotal</span>
                                        <span class="text-neutral-900">$<?= number_format($group['totalprice'], 2) ?></span>
                                    </div>
                                    <?php if ($group['discountAmount'] > 0): ?>
                                        <div class="flex justify-between text-sm font-medium text-green-600">
                                            <span>Discount (<?= $group['discountPercent'] ?>%)</span>
                                            <span>-$<?= number_format($group['discountAmount'], 2) ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <div class="border-t border-neutral-200 pt-2 flex justify-between text-sm font-bold text-ink-900">
                                        <span>total</span>
                                        <span>$<?= number_format($group['finalprice'], 2) ?></span>
                                    </div>
                                </div>

                                <!-- Trạng thái thanh toán -->
                                <div class="rounded-lg bg-white p-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-neutral-500">Trạng thái thanh toán</p>
                                    <div class="mt-2 flex items-center gap-2">
                                        <?php if ($group['paymentStatus'] === 'paid'): ?>
                                            <span class="inline-flex h-2 w-2 rounded-full bg-green-600"></span>
                                            <span class="text-sm font-medium text-green-700">paid</span>
                                        <?php else: ?>
                                            <span class="inline-flex h-2 w-2 rounded-full bg-orange-500"></span>
                                            <span class="text-sm font-medium text-orange-700">Thanh toán đang chờ</span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($group['created'] instanceof \dateTimeInterface): ?>
                                        <p class="mt-2 text-xs text-neutral-500">
                                            Đặt vào date <?= $group['created']->format('M j, Y') ?> at <?= $group['created']->format('g:i A') ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="mt-4 flex flex-wrap items-center gap-2 pt-4 border-t border-neutral-100">
                                <?php if ($group['paymentStatus'] !== 'paid' && $group['checkoutGroup']): ?>
                                    <?= $this->Html->link(
                                        'Thanh toán total',
                                        ['action' => 'paymentGroup', $group['checkoutGroup']],
                                        ['class' => 'inline-flex items-center justify-center rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-primary-700']
                                    ) ?>
                                <?php endif; ?>
                                <?php foreach ($group['bookings'] as $b): ?>
                                    <?php if ($b->status === 'pending'): ?>
                                        <?= $this->Form->postLink(
                                            'Hủy #' . $b->id,
                                            ['action' => 'cancel', $b->id],
                                            [
                                                'confirm' => 'Hủy đặt chỗ này\?',
                                                'class' => 'inline-flex items-center justify-center rounded-lg border border-red-200 bg-white px-3 py-2 text-sm font-semibold text-red-700 transition hover:bg-red-50',
                                            ]
                                        ) ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
        <?php endif; ?>
    </div>
    </div>

    <?= $this->element('site_footer') ?>
</div>

<script>
(function () {
    var workshopsData = <?= json_encode($workshops->toArray()) ?>;
    // Base URL built from CakePHP - handles subdirectory deployments correctly
    var BASE_URL = <?= json_encode($this->Url->build('/', ['fullBase' => false])) ?>;
    // remove trailing slash for consistency
    if (BASE_URL.endsWith('/')) BASE_URL = BASE_URL.slice(0, -1);
    var bookingRows = [];
    var maxRows = 6;
    var rowCounter = 0;

    function ucfirst(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    // Cache for workshop slots
    var workshopSlotsCache = {};
    var currentRowSlots = {};

    function pad2(n) {
        return String(n).padStart(2, '0');
    }

    // NEW: Fetch available slots from API (replaces generateAvailabledates)
    function fetchAvailableSlots(workshopId, callback) {
        // Check cache first
        if (workshopSlotsCache[workshopId]) {
            callback(workshopSlotsCache[workshopId]);
            return;
        }

        fetch(BASE_URL + '/bookings/slots?workshop_id=' + encodeURIComponent(workshopId))
            .then(function(response) { return response.json(); })
            .then(function(data) {
                if (data.error) {
                    callback({ error: data.error, slots: [] });
                    return;
                }
                // Cache the result
                workshopSlotsCache[workshopId] = data;
                callback(data);
            })
            .catch(function(err) {
                console.error('Failed to fetch slots:', err);
                callback({ error: 'Không thể tải date có sẵn', slots: [] });
            });
    }

    // OLD: Kept for pass-type workshops only
    function generateAvailabledates(workshopId, workshopType) {
        var dates = [];
        var now = new date();
        now.setHours(0, 0, 0, 0);
        var maxdate = new date(now.getTime() + 120 * 24 * 60 * 60 * 1000);

        for (var d = new date(now); d <= maxdate; d.setdate(d.getdate() + 1)) {
            var ymd = d.getFullYear() + '-' + pad2(d.getMonth() + 1) + '-' + pad2(d.getdate());
            var dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            var dayName = dayNames[d.getDay()];
            var monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            var label = dayName + ', ' + monthNames[d.getMonth()] + ' ' + d.getdate();
            dates.push({ value: ymd, label: label });
        }
        return dates;
    }

    function createBookingRow(index) {
        var rowId = 'booking-row-' + index;
        var row = document.createElement('div');
        row.id = rowId;
        row.className = 'booking-row rounded-2xl border border-neutral-200/70 bg-white p-4 shadow-sm';
        row.dataset.rowIndex = index;

        var header = document.createElement('div');
        header.className = 'mb-3 flex items-center justify-between';
        header.innerHTML = '<span class="text-xs font-semibold text-neutral-500">workshop ' + (index + 1) + '</span>';

        if (index > 0) {
            var removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'text-xs font-semibold text-red-600 transition hover:text-red-800';
            removeBtn.textContent = 'remove';
            removeBtn.addEventListener('click', function () {
                removeBookingRow(index);
            });
            header.appendChild(removeBtn);
        }

        row.appendChild(header);

        // Row 1: workshop and date
        var row1 = document.createElement('div');
        row1.className = 'grid gap-4 sm:grid-cols-2';

        var workshopDiv = document.createElement('div');
        var workshopLabel = document.createElement('label');
        workshopLabel.className = 'mb-1.5 block text-xs font-semibold text-ink-900';
        workshopLabel.textContent = 'workshop';
        workshopLabel.htmlFor = 'row-' + index + '-workshop';
        workshopDiv.appendChild(workshopLabel);

        var workshopSelect = document.createElement('select');
        workshopSelect.id = 'row-' + index + '-workshop';
        workshopSelect.name = 'row_' + index + '_workshop_id';
        workshopSelect.className = 'w-full appearance-none rounded-xl border border-neutral-200/90 bg-white py-3 pl-3 pr-10 text-sm text-ink-900 shadow-sm transition focus:border-primary-400 focus:outline-none focus:ring-3 focus:ring-primary-100/80';
        workshopSelect.innerHTML = '<option value="">Chọn hội thảo…</option>';
        for (var i = 0; i < workshopsData.length; i++) {
            var l = workshopsData[i];
            workshopSelect.innerHTML += '<option value="' + l.id + '" data-type="' + (l.workshop_type || '') + '" data-price="' + (l.price || 0) + '" data-desc="' + h(l.Mô tả || '') + '">' + h(l.workshop_name) + '</option>';
        }
        workshopSelect.addEventListener('change', function () {
            onRowWorkshopChange(index);
        });
        workshopDiv.appendChild(workshopSelect);
        row1.appendChild(workshopDiv);

        var dateDiv = document.createElement('div');
        var dateLabel = document.createElement('label');
        dateLabel.className = 'mb-1.5 block text-xs font-semibold text-ink-900';
        dateLabel.textContent = 'date';
        dateLabel.htmlFor = 'row-' + index + '-date';
        dateDiv.appendChild(dateLabel);

        var dateSelect = document.createElement('select');
        dateSelect.id = 'row-' + index + '-date';
        dateSelect.name = 'row_' + index + '_booking_date';
        dateSelect.disabled = true;
        dateSelect.className = 'w-full appearance-none rounded-xl border border-neutral-200/90 bg-white py-3 pl-3 pr-10 text-sm text-ink-900 shadow-sm transition focus:border-primary-400 focus:outline-none focus:ring-3 focus:ring-primary-100/80 disabled:cursor-not-allowed disabled:bg-neutral-50 disabled:text-neutral-400';
        dateSelect.innerHTML = '<option value="">Choose date…</option>';
        dateSelect.addEventListener('change', function () {
            onRowDateChange(index);
            refreshBookingStepRail();
        });
        dateDiv.appendChild(dateSelect);
        row1.appendChild(dateDiv);
        row.appendChild(row1);

        // Row 2: quantity selector (full width)
        var row2 = document.createElement('div');
        row2.className = 'mt-3';

        var qtyDiv = document.createElement('div');
        var qtyLabel = document.createElement('label');
        qtyLabel.className = 'mb-1.5 block text-xs font-semibold text-ink-900';
        qtyLabel.textContent = 'Number of chỗ';
        qtyLabel.htmlFor = 'row-' + index + '-quantity';
        qtyDiv.appendChild(qtyLabel);

        var qtySelect = document.createElement('select');
        qtySelect.id = 'row-' + index + '-quantity';
        qtySelect.name = 'row_' + index + '_quantity';
        qtySelect.className = 'w-full sm:w-1/3 appearance-none rounded-xl border border-neutral-200/90 bg-white py-3 pl-3 pr-10 text-sm text-ink-900 shadow-sm transition focus:border-primary-400 focus:outline-none focus:ring-3 focus:ring-primary-100/80';
        for (var q = 1; q <= 10; q++) {
            qtySelect.innerHTML += '<option value="' + q + '">' + q + ' chỗ' + (q > 1 ? 's' : '') + '</option>';
        }
        qtySelect.addEventListener('change', function () {
            updateOrderSummary();
            // validate quantity against available chỗ
            validateQuantity(index).then(function(valid) {
                if (!valid) {
                    // Validation error displayed by validateQuantity
                }
            });
        });
        qtyDiv.appendChild(qtySelect);
        row2.appendChild(qtyDiv);
        row.appendChild(row2);

        var previewEl = document.createElement('div');
        previewEl.id = 'row-' + index + '-preview';
        previewEl.className = 'hidden mt-3 overflow-hidden rounded-xl border border-primary-200/50 bg-gradient-to-br from-white to-primary-50/30 shadow-sm transition-all duration-300';
        previewEl.innerHTML = '<div class="flex flex-col gap-3 p-4 sm:flex-row sm:items-start sm:justify-between sm:gap-4 sm:p-4"><div class="min-w-0 flex-1"><div class="flex items-center gap-2"><h4 class="row-preview-name font-serif text-base font-semibold text-ink-900"></h4><span class="row-preview-type inline-flex rounded-full bg-primary-100 px-2 py-0.5 text-xs font-bold uppercase tracking-[0.05em] text-primary-700"></span></div><p class="row-preview-desc mt-2 text-xs leading-relaxed text-neutral-600 line-clamp-2"></p></div><div class="shrink-0 text-left sm:text-right"><p class="text-xs font-bold uppercase tracking-[0.05em] text-neutral-400">price</p><p class="row-preview-price font-serif text-lg font-bold text-primary-600"></p></div></div>';
        row.appendChild(previewEl);

        var errorEl = document.createElement('p');
        errorEl.id = 'row-' + index + '-error';
        errorEl.className = 'hidden mt-2 text-xs font-semibold text-red-600';
        row.appendChild(errorEl);

        return row;
    }

    function h(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function addBookingRow() {
        if (bookingRows.length >= maxRows) return;

        var index = bookingRows.length;
        var row = createBookingRow(index);
        var container = document.getElementById('booking-rows-container');
        if (!container) return;

        row.style.opacity = '0';
        row.style.transform = 'translateY(-10px)';
        container.appendChild(row);

        requestAnimationFrame(function () {
            row.style.transition = 'opacity 0.3s ease-out, transform 0.3s ease-out';
            row.style.opacity = '1';
            row.style.transform = 'translateY(0)';
        });

        bookingRows.push({ index: index, element: row });

        refreshAddRowButton();
    }

    function removeBookingRow(index) {
        var row = document.getElementById('booking-row-' + index);
        if (!row) return;

        row.style.transition = 'opacity 0.2s ease-out, transform 0.2s ease-out';
        row.style.opacity = '0';
        row.style.transform = 'translateX(20px)';

        setTimeout(function () {
            var container = document.getElementById('booking-rows-container');
            if (container) {
                container.removeChild(row);
            }

            bookingRows = bookingRows.filter(function (r) { return r.index !== index; });

            refreshAddRowButton();
            refreshBookingStepRail();
            updateOrderSummary();
        }, 200);
    }

    function updateAddButton() {
        var btn = document.getElementById('booking-add-row');
        if (!btn) return;
        btn.disabled = bookingRows.length >= maxRows;
        if (bookingRows.length >= maxRows) {
            btn.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            btn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }

    function refreshAddRowButton() {
        updateAddButton();
    }

    function onRowWorkshopChange(rowIndex) {
        var workshopSelect = document.getElementById('row-' + rowIndex + '-workshop');
        var dateSelect = document.getElementById('row-' + rowIndex + '-date');
        var previewEl = document.getElementById('row-' + rowIndex + '-preview');
        var errorEl = document.getElementById('row-' + rowIndex + '-error');

        if (!workshopSelect || !dateSelect) return;

        var opt = workshopSelect.options[workshopSelect.selectedIndex];
        var workshopId = opt.value;
        var workshopType = opt.dataset.type || '';
        var workshopPrice = opt.dataset.price || '0';
        var workshopDesc = opt.dataset.desc || '';

        dateSelect.innerHTML = '<option value="">Choose date…</option>';
        dateSelect.disabled = !workshopId;

        if (errorEl) {
            errorEl.classList.add('hidden');
        }

        // Update preview
        if (previewEl) {
            if (workshopId) {
                previewEl.classList.remove('hidden');
                var nameEl = previewEl.querySelector('.row-preview-name');
                var typeEl = previewEl.querySelector('.row-preview-type');
                var priceEl = previewEl.querySelector('.row-preview-price');
                var descEl = previewEl.querySelector('.row-preview-desc');
                if (nameEl) nameEl.textContent = opt.textContent.split('(')[0].trim();
                if (typeEl) typeEl.textContent = workshopType ? ucfirst(workshopType) : '';
                if (priceEl) {
                    var price = parseInt(workshopPrice, 10);
                    priceEl.textContent = isNaN(price) ? '' : ('$' + price.toLocaleString());
                }
                if (descEl) descEl.textContent = workshopDesc;
            } else {
                previewEl.classList.add('hidden');
            }
        }

        if (workshopId) {
            var isPass = (workshopType || '').toLowerCase().includes('pass');
            
            if (isPass) {
                // Pass-type workshops: show all dates
                var dates = generateAvailabledates(workshopId, workshopType);
                for (var i = 0; i < dates.length; i++) {
                    dateSelect.innerHTML += '<option value="' + dates[i].value + '">' + h(dates[i].label) + '</option>';
                }
                dateSelect.disabled = false;
            } else {
                // workshop: fetch slots from API
                dateSelect.innerHTML = '<option value="">Loading available dates...</option>';
                dateSelect.disabled = true;
                
                fetchAvailableSlots(workshopId, function(data) {
                    dateSelect.innerHTML = '<option value="">Choose date...</option>';
                    dateSelect.disabled = false;
                    
                    if (data.error || !data.slots || data.slots.length === 0) {
                        dateSelect.innerHTML = '<option value="">Không có buổi nào có sẵn</option>';
                        if (errorEl) {
                            errorEl.textContent = data.error || 'Không có buổi nào có sẵn cho hội thảo này. Vui lòng kiểm tra lại sau hoặc chọn một hội thảo khác.';
                            errorEl.classList.remove('hidden');
                        }
                        // Store empty slots for this row
                        currentRowSlots[rowIndex] = {};
                    } else {
                        // Store slots lookup for this row
                        currentRowSlots[rowIndex] = {};
                        
                        for (var i = 0; i < data.slots.length; i++) {
                            var slot = data.slots[i];
                            currentRowSlots[rowIndex][slot.date] = slot;
                            
                            var label = slot.day_name + ', ' + slot.date + ' · ' + slot.time_label + ' (' + slot.available + ' chỗ)';
                            var option = document.createElement('option');
                            option.value = slot.date;
                            option.textContent = label;
                            option.dataset.slotId = slot.id;
                            option.dataset.available = slot.available;
                            dateSelect.appendChild(option);
                        }
                        
                        if (errorEl) {
                            errorEl.classList.add('hidden');
                        }
                    }
                });
            }
        }

        refreshBookingStepRail();
        updateOrderSummary();
    }

    function onRowDateChange(rowIndex) {
        var workshopSelect = document.getElementById('row-' + rowIndex + '-workshop');
        var dateSelect = document.getElementById('row-' + rowIndex + '-date');
        var qtySelect = document.getElementById('row-' + rowIndex + '-quantity');
        var errorEl = document.getElementById('row-' + rowIndex + '-error');

        if (!workshopSelect || !dateSelect || !qtySelect) return;

        var workshopId = workshopSelect.value;
        var dateValue = dateSelect.value;

        if (!workshopId || !dateValue) return;

        // Fetch available chỗ (REALTIME from database)
        fetch(BASE_URL + '/bookings/chỗ?workshop_id=' + encodeURIComponent(workshopId) + '&date=' + encodeURIComponent(dateValue))
            .then(function(response) { return response.json(); })
            .then(function(data) {
                // Handle error cases (capacity not set, etc.)
                if (data.error) {
                    if (errorEl) {
                        errorEl.textContent = data.message || data.error;
                        errorEl.classList.remove('hidden');
                    }
                    // Disable quantity selection
                    qtySelect.innerHTML = '<option value="">Không có sẵn</option>';
                    qtySelect.disabled = true;
                    return;
                }

                // Check if fully booked
                if (data.available <= 0) {
                    if (errorEl) {
                        errorEl.textContent = data.message || 'No chỗ available';
                        errorEl.classList.remove('hidden');
                    }
                    qtySelect.innerHTML = '<option value="">Fully booked</option>';
                    qtySelect.disabled = true;
                    return;
                }

                // Enable quantity selection
                qtySelect.disabled = false;
                var available = data.available || 10;
                var currentQty = parseInt(qtySelect.value, 10) || 1;

                // Giới hạn số lượng hiện tại tại chỗ có sẵn
                if (currentQty > available) {
                    currentQty = available;
                }

                // Update quantity dropdown options
                qtySelect.innerHTML = '';
                for (var q = 1; q <= available; q++) {
                    var option = document.createElement('option');
                    option.value = q;
                    option.textContent = q + ' chỗ' + (q > 1 ? 's' : '');
                    if (q === currentQty) {
                        option.selected = true;
                    }
                    qtySelect.appendChild(option);
                }

                // Show warning if no chỗ available or quantity was reduced
                if (available <= 0) {
                    if (errorEl) {
                        errorEl.textContent = 'No chỗ available for this date.';
                        errorEl.classList.remove('hidden');
                    }
                } else if (parseInt(qtySelect.value, 10) > available) {
                    // Cập nhật bắt buộc lên tối đa có sẵn
                    qtySelect.value = available;
                    if (errorEl) {
                        errorEl.textContent = 'Đã điều chỉnh tối đa chỗ có sẵn (' + available + ').';
                        errorEl.classList.remove('hidden');
                        setTimeout(function() { errorEl.classList.add('hidden'); }, 3000);
                    }
                }
            })
            .catch(function(err) {
                // Silent fail - keep default options
            });
    }

    function refreshPaymentBlockVisibility() {
        var block = document.getElementById('booking-payment-block');
        if (!block) return;
        var hasValidSelection = false;
        for (var i = 0; i < bookingRows.length; i++) {
            var r = bookingRows[i];
            var workshopSel = document.getElementById('row-' + r.index + '-workshop');
            var dateSel = document.getElementById('row-' + r.index + '-date');
            if (workshopSel && workshopSel.value && dateSel && dateSel.value) {
                hasValidSelection = true;
                break;
            }
        }
        block.classList.toggle('hidden', !hasValidSelection);
    }

    var appliedDiscount = 20; // Auto 20% discount for 2+ workshops

    function updateOrderSummary() {
        var summaryEl = document.getElementById('booking-summary-items');
        var countEl = document.getElementById('booking-item-count');
        var subtotalEl = document.getElementById('booking-subtotal-price');
        var totalEl = document.getElementById('booking-total-price');
        var discountEl = document.getElementById('booking-discount-block');
        var discountAmountEl = document.getElementById('booking-discount-amount');
        if (!summaryEl || !countEl || !totalEl || !subtotalEl) return;

        var items = [];
        var subtotal = 0;
        var totalSeats = 0;

        for (var i = 0; i < bookingRows.length; i++) {
            var r = bookingRows[i];
            var workshopSel = document.getElementById('row-' + r.index + '-workshop');
            var dateSel = document.getElementById('row-' + r.index + '-date');
            var qtySel = document.getElementById('row-' + r.index + '-quantity');
            if (workshopSel && workshopSel.value) {
                var opt = workshopSel.options[workshopSel.selectedIndex];
                var price = parseInt(opt.dataset.price || '0', 10);
                var name = opt.textContent.split('(')[0].trim();
                var dateStr = dateSel && dateSel.value ? dateSel.options[dateSel.selectedIndex].textContent : 'Chọn ngày';
                var qty = qtySel ? parseInt(qtySel.value, 10) : 1;
                totalSeats += qty;
                items.push({ name: name, date: dateStr, price: price, quantity: qty, hasdate: !!dateSel && !!dateSel.value });
                subtotal += price * qty;
            }
        }

        countEl.textContent = items.length;
        subtotalEl.textContent = '$' + subtotal.toLocaleString();

        // Calculate discount - auto 20% for 2+ total chỗ
        var discountAmount = 0;
        if (totalSeats >= 2) {
            discountAmount = Math.round(subtotal * 0.2);
        }

        if (discountAmountEl) {
            discountAmountEl.textContent = '-$' + discountAmount.toLocaleString();
        }

        var total = subtotal - discountAmount;
        totalEl.textContent = '$' + total.toLocaleString();

        // Show discount block when 2+ total chỗ
        if (discountEl) {
            discountEl.classList.toggle('hidden', totalSeats < 2);
        }

        if (items.length === 0) {
            summaryEl.innerHTML = '<p class="text-sm text-neutral-500 italic">Chưa chọn hội thảo nào</p>';
        } else {
            summaryEl.innerHTML = '';
            for (var j = 0; j < items.length; j++) {
                var it = items[j];
                var itemDiv = document.createElement('div');
                itemDiv.className = 'flex items-start justify-between gap-2 text-sm' + (it.hasdate ? '' : ' opacity-60');
                var qtyLabel = it.quantity > 1 ? ' (x' + it.quantity + ')' : '';
                itemDiv.innerHTML = '<div class="min-w-0 flex-1"><p class="font-medium text-ink-900 truncate">' + h(it.name) + qtyLabel + '</p><p class="text-xs text-neutral-500 truncate">' + h(it.date) + '</p></div><p class="shrink-0 font-semibold text-primary-600">$' + (it.price * it.quantity).toLocaleString() + '</p>';
                summaryEl.appendChild(itemDiv);
            }
        }
    }

    function refreshBookingStepRail() {
        var hasValidSelection = false;
        for (var i = 0; i < bookingRows.length; i++) {
            var r = bookingRows[i];
            var workshopSel = document.getElementById('row-' + r.index + '-workshop');
            var dateSel = document.getElementById('row-' + r.index + '-date');
            if (workshopSel && workshopSel.value && dateSel && dateSel.value) {
                hasValidSelection = true;
                break;
            }
        }

        var fill = document.getElementById('booking-progress-fill');
        if (fill) {
            fill.style.width = hasValidSelection ? '100%' : '0%';
        }

        var hint = document.getElementById('booking-step-hint');
        if (hint) {
            hint.textContent = hasValidSelection ? 'Tiếp tục đến Stripe để thanh toán' : 'Chọn ít nhất một lớp với ngày';
        }

        var done =
            'booking-step-pill flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-primary-600 text-white shadow-md shadow-primary-600/20 ring-4 ring-primary-100/50 transition-all';
        var todo =
            'booking-step-pill flex h-11 w-11 shrink-0 items-center justify-center rounded-full border-2 border-neutral-200 bg-white text-neutral-400 shadow-sm transition-all';

        for (var s = 1; s <= 2; s++) {
            var el = document.getElementById('booking-step-' + s);
            if (!el) continue;
            var num = el.querySelector('.booking-step-num');
            var icon = el.querySelector('.booking-step-icon');
            var ok = s === 1 ? bookingRows.length > 0 : hasValidSelection;
            el.className = ok ? done : todo;
            if (num) {
                num.classList.toggle('hidden', ok);
            }
            if (icon) {
                icon.classList.toggle('hidden', !ok);
            }
        }

        refreshPaymentBlockVisibility();
    }

    function validateRows() {
        var errorEl = document.getElementById('booking-rows-error');
        var validItems = [];

        // Clear main error at start
        if (errorEl) errorEl.classList.add('hidden');

        // Debug: log bookingRows length
        console.log('validateRows: bookingRows.length =', bookingRows.length);

        for (var i = 0; i < bookingRows.length; i++) {
            var r = bookingRows[i];
            console.log('validateRows: checking row', r.index, r);

            var workshopSel = document.getElementById('row-' + r.index + '-workshop');
            var dateSel = document.getElementById('row-' + r.index + '-date');
            var qtySel = document.getElementById('row-' + r.index + '-quantity');
            var rowError = document.getElementById('row-' + r.index + '-error');

            console.log('validateRows: elements', {workshop: workshopSel?.value, date: dateSel?.value, qty: qtySel?.value});

            if (rowError) rowError.classList.add('hidden');

            if (!workshopSel || !workshopSel.value) {
                if (rowError) {
                    rowError.textContent = 'Vui lòng chọn hội thảo.';
                    rowError.classList.remove('hidden');
                }
                return false;
            }

            if (!dateSel || !dateSel.value) {
                if (rowError) {
                    rowError.textContent = 'Vui lòng chọn ngày.';
                    rowError.classList.remove('hidden');
                }
                return false;
            }

            var quantity = qtySel && qtySel.value ? parseInt(qtySel.value, 10) : 1;
            if (isNaN(quantity) || quantity < 1) quantity = 1;
            
            // Get slot_id from selected option dataset
            var selectedOption = dateSel.options[dateSel.selectedIndex];
            var slotId = selectedOption ? selectedOption.dataset.slotId : null;
            
            validItems.push({
                workshop_id: workshopSel.value,
                booking_date: dateSel.value,
                slot_id: slotId,
                quantity: quantity
            });
        }

        if (validItems.length === 0) {
            if (errorEl) {
                errorEl.textContent = 'Vui lòng thêm ít nhất một hội thảo.';
                errorEl.classList.remove('hidden');
            }
            return false;
        }

        if (errorEl) errorEl.classList.add('hidden');
        return validItems;
    }

    // Initialize: add first row
    addBookingRow();

    // Add row button
    var addRowBtn = document.getElementById('booking-add-row');
    if (addRowBtn) {
        addRowBtn.addEventListener('click', function () {
            addBookingRow();
        });
    }

    // quantity validation - re-check when quantity changes
    function validateQuantity(rowIndex) {
        var workshopSelect = document.getElementById('row-' + rowIndex + '-workshop');
        var dateSelect = document.getElementById('row-' + rowIndex + '-date');
        var qtySelect = document.getElementById('row-' + rowIndex + '-quantity');
        var errorEl = document.getElementById('row-' + rowIndex + '-error');

        if (!workshopSelect || !dateSelect || !qtySelect) return Promise.resolve(true);

        var workshopId = workshopSelect.value;
        var dateValue = dateSelect.value;
        var selectedQty = parseInt(qtySelect.value, 10) || 1;

        if (!workshopId || !dateValue) return Promise.resolve(true);

        // Kiểm tra xem số lượng có vượt quá số có sẵn không
        return fetch(BASE_URL + '/bookings/chỗ?workshop_id=' + encodeURIComponent(workshopId) + '&date=' + encodeURIComponent(dateValue))
            .then(function(response) { return response.json(); })
            .then(function(data) {
                if (data.error) return true; // Để máy chủ xử lý

                var available = data.available || 0;
                if (selectedQty > available) {
                    if (errorEl) {
                        errorEl.textContent = 'Only ' + available + ' chỗ available. Please select ' + available + ' or fewer.';
                        errorEl.classList.remove('hidden');
                    }
                    // Đặt lại tối đa có sẵn
                    qtySelect.value = Math.max(1, available);
                    updateOrderSummary();
                    return false;
                }
                return true;
            })
            .catch(function() { return true; });
    }

    // Form submission
    var form = document.getElementById('booking-main-form');
    var isSubmitting = false;
    if (form) {
        form.addEventListener('submit', function (e) {
            if (isSubmitting) return; // Đã xác nhận và đang gửi
            e.preventDefault();

            var validItems = validateRows();
            if (!validItems) {
                return;
            }

            // validate quantities before submit
            var validations = validItems.map(function(it, idx) {
                return validateQuantity(bookingRows[idx].index);
            });

            Promise.all(validations).then(function(results) {
                if (results.some(function(r) { return !r; })) {
                    return; // Xác nhận thất bại, không gửi
                }

                // Xóa các đầu vào ẩn cũ
                var old = form.querySelectorAll('input[data-booking-row="1"]');
                for (var i = 0; i < old.length; i++) {
                    old[i].remove();
                }

                // Thêm các đầu vào ẩn mới cho các mục hợp lệ
                for (var j = 0; j < validItems.length; j++) {
                    var it = validItems[j];
                    var h1 = document.createElement('input');
                    h1.type = 'hidden';
                    h1.name = 'items[' + j + '][workshop_id]';
                    h1.value = it.workshop_id;
                    h1.dataset.bookingRow = '1';
                    form.appendChild(h1);

                    var h2 = document.createElement('input');
                    h2.type = 'hidden';
                    h2.name = 'items[' + j + '][booking_date]';
                    h2.value = it.booking_date;
                    h2.dataset.bookingRow = '1';
                    form.appendChild(h2);

                    // MỚI: Bao gồm slot_id cho đặt chỗ dựa trên slot
                    var hSlot = document.createElement('input');
                    hSlot.type = 'hidden';
                    hSlot.name = 'items[' + j + '][slot_id]';
                    hSlot.value = it.slot_id || '';
                    hSlot.dataset.bookingRow = '1';
                    form.appendChild(hSlot);

                    var h3 = document.createElement('input');
                    h3.type = 'hidden';
                    h3.name = 'items[' + j + '][quantity]';
                    h3.value = it.quantity;
                    h3.dataset.bookingRow = '1';
                    form.appendChild(h3);
                }

                // Bây giờ gửi biểu mẫu
                isSubmitting = true;
                form.submit();
            });
        });
    }
})();
</script>















