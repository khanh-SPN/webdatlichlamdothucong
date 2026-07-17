<?php
$this$this->assign('title', 'Lượt ghé thăm của bạn');
$c = $siteCompany ?? $company ?? null;
$address = h($c?->address ?? '123 Đường Thủ Công, Thành Phố Sáng Tạo');
$email = h((string)($c?->email ?? 'HoiNgheThuatNen@gmail.com'));
$phoneDisplay = h((string)($c?->phone ?? '\+84 912 345 678'));
$addressRaw = (string)($c?->address ?? '123 Đường Thủ Công, Thành Phố Sáng Tạo');
$mapsUrl = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($addressRaw);

$visitImg = fn (string $file): string => h($this->Url->build('/img/landing/' . $file));
?>

<div class="bg-studio-ivory text-ink-900">
    <div id="visit-hero" class="relative scroll-mt-20 overflow-hidden border-b border-neutral-800/20 bg-ink-950 text-white" aria-labelledby="visit-hero-heading">
        <div class="pointer-events-none absolute inset-0" aria-hidden="true">
            <img
                src="<?= $visitImg('home-pottery-ceramics.png') ?>"
                alt=""
                class="h-full w-full min-h-[260px] object-cover object-center scale-105 sm:scale-100 sm:min-h-0"
                loading="eager"
                fetchpriority="high"
                decoding="async"
            >
            <div class="absolute inset-0 bg-ink-950/40"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-ink-950/92 via-ink-900/85 to-primary-950/88"></div>
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_100%_80%_at_100%_0%,rgba(169,102,120,0.12),transparent_50%)]"></div>
        </div>
        <div class="relative z-10 mx-auto max-w-screen-2xl px-3 py-3 md:px-4 md:py-4">
            <p class="text-xs font-semibold uppercase tracking-[0.15em] text-primary-200/90">Thông tin khách tham quan</p>
            <h1 id="visit-hero-heading" class="mt-3 max-w-2xl font-serif text-xl font-semibold leading-tight tracking-tight text-white sm:text-2xl lg:text-[3rem]">
                Trước khi bạn đến
            </h1>
            <p class="mt-4 max-w-xl text-sm leading-relaxed text-white/85 sm:text-base lg:text-lg">
                Hướng dẫn, mặc gì, và cách các buổi chạy. Đối với chính sách và hoàn tiền, hãy sử dụng
                <?= $this->Html->link('Câu hỏi thường gặp', '/faqs', ['class' => 'font-medium text-primary-200 underline decoration-primary-400/50 underline-offset-2 hover:text-white']) ?>.
            </p>
            <a
                href="#visit-practical"
                class="mt-4 inline-flex items-center gap-2 rounded-lg border border-white/25 bg-white/10 px-4 py-2.5 text-sm font-medium text-white/95 backdrop-blur-sm transition hover:border-white/40 hover:bg-white/15 focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-ink-900"
            >
                <?= $this->element('ui_icon', ['name' => 'chevron_down', 'class' => 'h-4 w-4 text-primary-200']) ?>
                Xem chi tiết thực tế
            </a>
        </div>
    </div>

    <section class="py-3 md:py-4 lg:py-20" id="visit-practical" aria-labelledby="visit-practical-heading">
        <div class="mx-auto max-w-6xl px-3 lg:px-4">
            <h2 id="visit-practical-heading" class="sr-only">Chi tiết thực tế cho lượt ghé thăm của bạn</h2>
            <div class="grid gap-5 md:grid-cols-2 lg:gap-3">
                <div class="rounded-2xl border border-neutral-200/80 bg-white p-3 shadow-sm md:p-7">
                    <div class="flex gap-4">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary-50 text-primary-700" aria-hidden="true">
                            <?= $this->element('ui_icon', ['name' => 'building_office_2', 'class' => 'h-5 w-5']) ?>
                        </span>
                        <div>
                            <h3 class="text-base font-semibold text-ink-900">Tìm chúng tôi</h3>
                            <p class="mt-2 text-sm font-medium leading-snug text-ink-900"><?= $address ?></p>
                            <p class="mt-3 text-sm text-neutral-600">
                                <span class="font-medium text-ink-800"><?= $phoneDisplay ?></span>
                                <span class="text-neutral-400" aria-hidden="true"> · </span>
                                <?= $email ?>
                            </p>
                            <div class="mt-5 overflow-hidden rounded-xl border border-neutral-200/80 bg-neutral-50">
                                <div id="visit-map" class="h-56 w-full md:h-64" aria-label="Bản đồ tương tác"></div>
                            </div>
                            <p class="mt-4">
                                <a href="<?= h($mapsUrl) ?>" rel="noopener noreferrer" target="_blank" class="inline-flex items-center gap-1.5 text-sm font-semibold text-primary-700 hover:text-primary-800">
                                    Mở trong Bản đồ
                                    <?= $this->element('ui_icon', ['name' => 'chevron_right', 'class' => 'h-4 w-4']) ?>
                                </a>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-neutral-200/80 bg-white p-3 shadow-sm md:p-7">
                    <div class="flex gap-4">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-sage-50 text-sage-600" aria-hidden="true">
                            <?= $this->element('ui_icon', ['name' => 'calendar_days', 'class' => 'h-5 w-5']) ?>
                        </span>
                        <div>
                            <h3 class="text-base font-semibold text-ink-900">Đến đúng giờ</h3>
                            <p class="mt-2 text-sm leading-relaxed text-neutral-600">
                                Nhắm đến khoảng <strong class="font-semibold text-ink-800">10 phút sớm</strong> để chúng tôi có thể check-in cho bạn và chỉ cho bạn nơi để đồ của bạn trước khi chúng tôi bắt đầu.
                            </p>
                            <p class="mt-3 text-sm leading-relaxed text-neutral-600">
                                Đến trễ\? Sử dụng
                                <button type="button" onclick="openLiên hệModal()" class="font-medium text-primary-700 underline decoration-primary-300 underline-offset-2 hover:text-primary-800">Liên hệ</button>
                                trong menu trên để liên hệ với chúng tôi.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-neutral-200/80 bg-white p-3 shadow-sm md:p-7">
                    <div class="flex gap-4">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary-50 text-primary-700" aria-hidden="true">
                            <?= $this->element('ui_icon', ['name' => 'cube', 'class' => 'h-5 w-5']) ?>
                        </span>
                        <div>
                            <h3 class="text-base font-semibold text-ink-900">Mang gì</h3>
                            <p class="mt-2 text-sm leading-relaxed text-neutral-600">
                                Vật liệu và công cụ được bao gồm cho các buổi làm nến, gốm và đan. Mặc quần áo bạn không ngại bị bụi hoặc bắn; giày đóng ngón hoạt động tốt quanh đất sét và sáp nóng.
                            </p>
                            <p class="mt-3 text-sm leading-relaxed text-neutral-600">
                                Một chai nước và một lớp nhẹ cho studio có điều hòa nhiệt độ của chúng tôi là những ý tưởng tốt.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-neutral-200/80 bg-white p-3 shadow-sm md:p-7">
                    <div class="flex gap-4">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-sage-50 text-sage-600" aria-hidden="true">
                            <?= $this->element('ui_icon', ['name' => 'users', 'class' => 'h-5 w-5']) ?>
                        </span>
                        <div>
                            <h3 class="text-base font-semibold text-ink-900">Trong phòng</h3>
                            <p class="mt-2 text-sm leading-relaxed text-neutral-600">
                                Nhóm giữ nhỏ để giáo viên có thể dành sự chú ý cho mọi người. Chụp ảnh là ổn khi họ tôn trọng khách khác; hỏi nếu bạn không chắc.
                            </p>
                            <p class="mt-3 text-sm leading-relaxed text-neutral-600">
                                Một số công việc \(như gốm\) có thể cần nung hoặc làm mát; giáo viên của bạn sẽ giải thích lấy hoặc vận chuyển khi áp dụng.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <p class="mx-auto mt-3 max-w-lg border-t border-neutral-200/80 pt-8 text-center text-sm leading-relaxed text-neutral-600">
                Khi bạn đã chọn hội thảo, chọn ngày và thanh toán trong
                <?= $this->Html->link('quy trình đặt chỗ', '/booking', ['class' => 'font-medium text-primary-700 underline decoration-primary-300 underline-offset-2 hover:text-primary-900']) ?>.
            </p>
        </div>
    </section>

    <?= $this->element('site_footer') ?>
</div>

<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
    crossorigin=""
>
<script
    src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
    crossorigin=""
></script>
<script>
(function () {
    var el = document.getElementById('visit-map');
    if (!el || typeof L === 'undefined') return;

    var address = <?= json_encode($addressRaw) ?>;
    var fallback = { lat: -37.8136, lon: 144.9631 }; // Hà Nội

    var map = L.map(el, { scrollWheelZoom: false });
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; Người đóng góp OpenStreetMap',
    }).addTo(map);

    function setMarker(lat, lon, label) {
        map.setView([lat, lon], 15);
        var m = L.marker([lat, lon]).addTo(map);
        if (label) m.bindPopup(label).openPopup();
    }

    // Best-effort client-side geocoding via Nominatim (no Google Maps dependency).
    fetch('https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' + encodeURIComponent(address), {
        headers: { 'Accept': 'application/json' },
    })
        .then(function (r) { return r.ok ? r.json() : []; })
        .then(function (rows) {
            if (rows && rows.length) {
                setMarker(parseFloat(rows[0].lat), parseFloat(rows[0].lon), address);
            } else {
                setMarker(fallback.lat, fallback.lon, address);
            }
        })
        .catch(function () {
            setMarker(fallback.lat, fallback.lon, address);
        });
})();
</script>


