<?php
/**
 * Site-wide footer: single source for marketing pages.
 *
 * @var \App\View\AppView $this
 */
?>
<footer class="border-t border-neutral-800/40 bg-gradient-to-b from-ink-900 to-ink-950 text-neutral-300" role="contentinfo">
    <?php
    $c = $siteCompany ?? null;
    $footerEmail = h((string)($c?->email ?? 'HoiNgheThuatNen@gmail.com'));
    $footerPhone = h((string)($c?->phone ?? '+84 912 345 678'));
    ?>
    <div class="mx-auto max-w-screen-2xl px-3 py-7 lg:px-4 lg:py-4">
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            <div>
                <p class="font-script text-lg text-primary-400">Hội Nghệ Thuật</p>
                <p class="font-serif text-lg font-semibold text-white">Nến</p>
                <p class="mt-4 max-w-xs text-sm leading-relaxed text-neutral-400">
                    Các hội thảo sáng tạo tại Việt Nam: làm nến, gốm và đan trong một studio ấm áp, nhóm nhỏ.
                </p>
            </div>
            <div>
                <h3 class="text-xs font-semibold uppercase tracking-[0.12em] text-neutral-500">Khám phá</h3>
                <ul class="mt-5 space-y-3 text-sm">
                    <li><?= $this->Html->link('Trang chủ', '/', ['class' => 'text-neutral-300 transition hover:text-white']) ?></li>
                    <li><?= $this->Html->link('Về chúng tôi', '/pages/about', ['class' => 'text-neutral-300 transition hover:text-white']) ?></li>
                    <li><?= $this->Html->link('Hội thảo', '/workshops', ['class' => 'text-neutral-300 transition hover:text-white']) ?></li>
                    <li><?= $this->Html->link('Thăm của bạn', '/visit', ['class' => 'text-neutral-300 transition hover:text-white']) ?></li>
                    <li><?= $this->Html->link('Câu hỏi thường gặp', '/faqs', ['class' => 'text-neutral-300 transition hover:text-white']) ?></li>
                </ul>
            </div>
            <div>
                <h3 class="text-xs font-semibold uppercase tracking-[0.12em] text-neutral-500">Hội thảo</h3>
                <ul class="mt-5 space-y-3 text-sm">
                    <li><?= $this->Html->link('Làm nến', '/workshops#workshop-candle', ['class' => 'text-neutral-300 transition hover:text-white']) ?></li>
                    <li><?= $this->Html->link('Gốm', '/workshops#workshop-pottery', ['class' => 'text-neutral-300 transition hover:text-white']) ?></li>
                    <li><?= $this->Html->link('Đan', '/workshops#workshop-knitting', ['class' => 'text-neutral-300 transition hover:text-white']) ?></li>
                    <li><?= $this->Html->link('Vé nhiều lớp', '/workshops', ['class' => 'text-neutral-300 transition hover:text-white']) ?></li>
                </ul>
            </div>
            <div>
                <h3 class="text-xs font-semibold uppercase tracking-[0.12em] text-neutral-500">Liên hệ</h3>
                <ul class="mt-5 space-y-4 text-sm">
                    <li>
                        <a href="mailto:<?= $footerEmail ?>" class="font-medium text-white transition hover:text-primary-300"><?= $footerEmail ?></a>
                    </li>
                    <li>
                        <a href="tel:<?= preg_replace('/\s+/', '', $footerPhone) ?>" class="text-neutral-300 transition hover:text-white"><?= $footerPhone ?></a>
                    </li>
                    <li>
                        <button type="button" onclick="openContactModal()" class="border-0 bg-transparent p-0 text-left text-primary-400 transition hover:text-primary-300">
                            Gửi tin nhắn
                        </button>
                    </li>
                </ul>
            </div>
        </div>
        <div class="mt-3 flex flex-col items-center justify-between gap-4 border-t border-neutral-800/80 pt-8 text-xs text-neutral-500 md:flex-row">
            <p>&copy; <?= date('Y') ?> Hội Nghệ Thuật Nến. Đã đăng ký bản quyền.</p>
            <div class="flex flex-wrap items-center justify-center gap-3">
                <button type="button" onclick="openPrivacyModal()" class="font-inherit cursor-pointer border-0 bg-transparent p-0 text-neutral-400 transition hover:text-neutral-200">Chính sách bảo mật</button>
                <button type="button" onclick="openTermsModal()" class="font-inherit cursor-pointer border-0 bg-transparent p-0 text-neutral-400 transition hover:text-neutral-200">Điều khoản dịch vụ</button>
                <a href="mailto:HoiNgheThuatNen@gmail.com" class="text-neutral-400 transition hover:text-neutral-200">Email</a>
            </div>
            <p class="text-neutral-600">Được tạo bởi Nexify (Team 112)</p>
        </div>
    </div>
</footer>

