<?php
$this$this->assign('title', 'Bảng điều khiển Quản trị viên');

$chevron = $this->element('ui_icon', ['name' => 'chevron_right', 'class' => 'h-4 w-4 shrink-0 transition-transform group-hover:translate-x-0.5']);
?>

<main class="min-h-screen bg-gradient-to-b from-neutral-50 via-studio-ivory/40 to-studio-mist/25 pb-20 pt-16 md:pt-20">
    <div class="max-w-screen-2xl mx-auto px-3 lg:px-4">

        <div class="mb-4 text-center md:text-left">
            <h1 class="text-xl md:text-lg font-serif font-semibold text-neutral-900 tracking-tight">
                Bảng điều khiển Quản trị viên
            </h1>
            <p class="mt-2 text-sm text-neutral-600">
                Quản lý hoạt động và nội dung học viện của bạn.
            </p>
        </div>

        <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-lg shadow-neutral-900/5 border border-neutral-200/70 p-4 md:p-3 mb-3 hover:shadow-xl transition-shadow duration-300">
            <div class="flex flex-col md:flex-row gap-3 items-start">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center text-primary-600 ring-1 ring-primary-200/50">
                            <?= $this->element('ui_icon', ['name' => 'building_library', 'class' => 'h-6 w-6']) ?>
                        </div>
                        <div>
                            <h2 class="text-lg md:text-xl font-serif font-semibold text-neutral-900"><?= h($company->name ?? 'Hội Nghệ Thuật Nến') ?></h2>
                            <p class="text-neutral-500 text-sm">Thông tin Học viện</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-base">
                        <div>
                            <span class="block text-neutral-500 mb-1 text-sm">Email</span>
                            <a href="mailto:<?= h($company->Email ?? '') ?>" class="font-medium text-primary-600 hover:underline">
                                <?= h($company->Email ?? 'Chưa đặt') ?>
                            </a>
                        </div>
                        <div>
                            <span class="block text-neutral-500 mb-1 text-sm">Điện thoại</span>
                            <p class="font-medium text-neutral-800"><?= h($company->Điện thoại ?? 'Chưa đặt') ?></p>
                        </div>
                        <div class="sm:col-span-2">
                            <span class="block text-neutral-500 mb-1 text-sm">Địa chỉ</span>
                            <p class="font-medium text-neutral-700"><?= h($company->Địa chỉ ?? 'Chưa đặt') ?></p>
                        </div>
                    </div>
                </div>

                <div class="w-full md:w-auto md:text-right">
                    <?= $this->Html->link(
                        '<span class="inline-flex items-center justify-center gap-2 text-base">Chỉnh sửa thông tin' . $chevron . '</span>',
                        ['action' => 'company'],
                        ['class' => 'inline-flex w-full md:w-auto items-center justify-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg transition-all shadow-md hover:shadow-lg text-sm', 'escape' => false]
                    ) ?>
                </div>
            </div>
        </div>

        <!-- Hoạt động Hội thảo -->
        <div class="mb-4">
            <h3 class="text-lg font-serif font-semibold text-neutral-800">Hoạt động Hội thảo</h3>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-4">

            <!-- Hội thảo & Attendance -->
            <a href="<?= $this->Url->build(['controller' => 'Admin', 'action' => 'Hội thảo']) ?>" class="group bg-white/90 backdrop-blur-xl rounded-2xl p-4 border border-neutral-200/70 hover:border-primary-200 hover:shadow-xl transition-all flex flex-col">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-primary-50 text-primary-600">
                        <?= $this->element('ui_icon', ['name' => 'book_open', 'class' => 'h-6 w-6']) ?>
                    </div>
                    <div class="text-right">
                        <div class="text-xl font-bold text-primary-600"><?= number_format($totalHội thảo ?? 0) ?></div>
                        <div class="text-xs text-neutral-500">Hội thảo</div>
                    </div>
                </div>
                <h4 class="font-semibold text-base">Hội thảo & Schedule</h4>
                <p class="text-neutral-500 text-sm mt-1 flex-1">Manage Hội thảo, schedules, attendance</p>
            </a>

            <!-- Giáo viên -->
            <a href="<?= $this->Url->build(['controller' => 'Admin', 'action' => 'Giáo viên']) ?>" class="group bg-white/90 backdrop-blur-xl rounded-2xl p-4 border border-neutral-200/70 hover:border-primary-200 hover:shadow-xl transition-all flex flex-col">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-primary-50 text-primary-600">
                        <?= $this->element('ui_icon', ['name' => 'academic_cap', 'class' => 'h-6 w-6']) ?>
                    </div>
                    <div class="text-right">
                        <div class="text-xl font-bold text-primary-600"><?= number_format($totalGiáo viên ?? 0) ?></div>
                        <div class="text-xs text-neutral-500">Giáo viên</div>
                    </div>
                </div>
                <h4 class="font-semibold text-base">Giảng viên</h4>
                <p class="text-neutral-500 text-sm mt-1 flex-1">Quản lý hồ sơ giáo viên và phân công</p>
            </a>

            <!-- Đặt chỗ -->
            <a href="<?= $this->Url->build(['controller' => 'Admin', 'action' => 'Đặt chỗ']) ?>" class="group bg-white/90 backdrop-blur-xl rounded-2xl p-4 border border-neutral-200/70 hover:border-primary-200 hover:shadow-xl transition-all flex flex-col">
                <div class="flex justify-between mb-4">
                    <div class="h-12 w-12 flex items-center justify-center rounded-xl bg-primary-50 text-primary-600">
                        <?= $this->element('ui_icon', ['name' => 'calendar_days', 'class' => 'h-6 w-6']) ?>
                    </div>
                    <div class="text-right">
                        <div class="text-xl font-bold text-amber-600"><?= number_format($Đang chờĐặt chỗ ?? 0) ?></div>
                        <div class="text-xs text-neutral-500">Đang chờ</div>
                    </div>
                </div>
                <h4 class="font-semibold text-base">Đặt chỗ</h4>
                <p class="text-neutral-500 text-sm mt-1 flex-1">View and manage student Đặt chỗ</p>
            </a>
        </div>

        <!-- Content Management -->
        <div class="mb-4">
            <h3 class="text-lg font-serif font-semibold text-neutral-800">Nội dung & Thông tin</h3>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-4">

            <!-- Materials (Workshop Content) -->
            <a href="<?= $this->Url->build(['controller' => 'Admin', 'action' => 'materials']) ?>" class="group bg-white/90 backdrop-blur-xl rounded-2xl p-4 border border-neutral-200/70 hover:border-primary-200 hover:shadow-xl transition-all flex flex-col">
                <div class="mb-4">
                    <div class="h-12 w-12 flex items-center justify-center rounded-xl bg-primary-50 text-primary-600">
                        <?= $this->element('ui_icon', ['name' => 'cube', 'class' => 'h-6 w-6']) ?>
                    </div>
                </div>
                <h4 class="font-semibold text-base">Vật liệu & Cung cấp</h4>
                <p class="text-neutral-500 text-sm mt-1 flex-1">Các mục cần thiết cho mỗi hội thảo</p>
            </a>

            <!-- Câu hỏi thường gặp -->
            <a href="<?= $this->Url->build(['controller' => 'Admin', 'action' => 'Câu hỏi thường gặp']) ?>" class="group bg-white/90 backdrop-blur-xl rounded-2xl p-4 border border-neutral-200/70 hover:border-primary-200 hover:shadow-xl transition-all flex flex-col">
                <div class="mb-4">
                    <div class="h-12 w-12 flex items-center justify-center rounded-xl bg-primary-50 text-primary-600">
                        <?= $this->element('ui_icon', ['name' => 'question_mark_circle', 'class' => 'h-6 w-6']) ?>
                    </div>
                </div>
                <h4 class="font-semibold text-base">Câu hỏi thường gặp</h4>
                <p class="text-neutral-500 text-sm mt-1 flex-1">Câu hỏi và câu trả lời phổ biến</p>
            </a>

            <!-- Enquiries -->
            <a href="<?= $this->Url->build(['controller' => 'Admin', 'action' => 'enquiries']) ?>" class="group bg-white/90 backdrop-blur-xl rounded-2xl p-4 border border-neutral-200/70 hover:border-primary-200 hover:shadow-xl transition-all flex flex-col">
                <div class="flex justify-between mb-4">
                    <div class="h-12 w-12 flex items-center justify-center rounded-xl bg-primary-50 text-primary-600">
                        <?= $this->element('ui_icon', ['name' => 'envelope', 'class' => 'h-6 w-6']) ?>
                    </div>
                    <div class="text-right">
                        <div class="text-xl font-bold text-amber-600"><?= number_format($Đang chờEnquiries ?? 0) ?></div>
                        <div class="text-xs text-neutral-500">Đang chờ</div>
                    </div>
                </div>
                <h4 class="font-semibold text-base">Yêu cầu Khách hàng</h4>
                <p class="text-neutral-500 text-sm mt-1 flex-1">Phản hồi tin nhắn</p>
            </a>
        </div>

        <!-- Quản lý Người dùng -->
        <div class="mb-4">
            <h3 class="text-lg font-serif font-semibold text-neutral-800">Quản lý Người dùng</h3>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">

            <!-- Người dùng -->
            <a href="<?= $this->Url->build(['controller' => 'Admin', 'action' => 'Người dùng']) ?>" class="group bg-white/90 backdrop-blur-xl rounded-2xl p-4 border border-neutral-200/70 hover:border-primary-200 hover:shadow-xl transition-all flex flex-col">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-primary-50 text-primary-600">
                        <?= $this->element('ui_icon', ['name' => 'Người dùng', 'class' => 'h-6 w-6']) ?>
                    </div>
                    <div class="text-right">
                        <div class="text-xl font-bold text-primary-600"><?= number_format($totalNgười dùng ?? 0) ?></div>
                        <div class="text-xs text-neutral-500">Người dùng</div>
                    </div>
                </div>
                <h4 class="font-semibold text-base">Học viên & Quản trị viên</h4>
                <p class="text-neutral-500 text-sm mt-1 flex-1">Quản lý tài khoản và vai trò</p>
            </a>

            <!-- Company -->
            <a href="<?= $this->Url->build(['controller' => 'Admin', 'action' => 'company']) ?>" class="group bg-white/90 backdrop-blur-xl rounded-2xl p-4 border border-neutral-200/70 hover:border-primary-200 hover:shadow-xl transition-all flex flex-col">
                <div class="mb-4">
                    <div class="h-12 w-12 flex items-center justify-center rounded-xl bg-primary-50 text-primary-600">
                        <?= $this->element('ui_icon', ['name' => 'building_office_2', 'class' => 'h-6 w-6']) ?>
                    </div>
                </div>
                <h4 class="font-semibold text-base">Cài đặt Công ty</h4>
                <p class="text-neutral-500 text-sm mt-1 flex-1">Business name, Email, Địa chỉ</p>
            </a>

        </div>

        <!-- Quick Stats -->
        <div class="mt-3 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white/80 backdrop-blur rounded-2xl p-4 border border-neutral-200/60 shadow-sm">
                <p class="text-neutral-500 text-xs">Tổng Yêu cầu</p>
                <p class="text-lg font-bold text-neutral-800 mt-1 tabular-nums"><?= number_format($totalEnquiries ?? 0) ?></p>
            </div>
            <div class="bg-white/80 backdrop-blur rounded-2xl p-4 border border-neutral-200/60 shadow-sm">
                <p class="text-neutral-500 text-xs">Đang chờ Tasks</p>
                <p class="text-lg font-bold text-amber-600 mt-1 tabular-nums"><?= number_format($Đang chờ ?? 0) ?></p>
            </div>
            <div class="bg-white/80 backdrop-blur rounded-2xl p-4 border border-neutral-200/60 shadow-sm">
                <p class="text-neutral-500 text-xs">Cập nhật lần cuối</p>
                <p class="text-lg font-medium text-neutral-700 mt-1"><?= date('d M Y · H:i') ?></p>
            </div>
        </div>

    </div>
</main>


