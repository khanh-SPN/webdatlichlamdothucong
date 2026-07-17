<?php $this$this->assign('title', 'Đặt chỗ'); ?>

<main class="min-h-screen pt-16 md:pt-20 pb-20 bg-neutral-50">
    <div class="max-w-screen-2xl mx-auto px-3 lg:px-4">

        <!-- Header with animation -->
        <div class="text-center md:text-left mb-3 animate-fade-in">
            <h1 class="text-lg md:text-lg font-serif font-semibold text-neutral-800 tracking-tight">
                Quản lý Đặt chỗ
            </h1>
            <p class="mt-4 text-lg text-neutral-600">
                Xem lại và quản lý tất cả đặt chỗ hội thảo: phê duyệt, từ chối hoặc xem chi tiết.
            </p>
        </div>

        <!-- Table card -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-neutral-200/60 overflow-hidden transition-all duration-300 hover:shadow-2xl">

            <?php if (empty($bookings)): ?>

                <div class="py-4 text-center">
                    <p class="text-xl text-neutral-500 font-medium">
                        Không tìm thấy đặt chỗ nào vào lúc này.
                    </p>
                    <p class="mt-2 text-neutral-600">
                        Các đặt chỗ mới sẽ xuất hiện ở đây khi người dùng đặt một hội thảo.
                    </p>
                </div>

            <?php else: ?>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">

                        <thead class="bg-neutral-100/80 backdrop-blur-sm">
                            <tr class="border-b border-neutral-200">
                                <th class="px-3 py-5 text-lg font-semibold text-neutral-700">Người dùng</th>
                                <th class="px-3 py-5 text-lg font-semibold text-neutral-700">Hội thảo</th>
                                <th class="px-3 py-5 text-lg font-semibold text-neutral-700">Ngày</th>
                                <th class="px-3 py-5 text-lg font-semibold text-neutral-700">Trạng thái</th>
                                <th class="px-3 py-5 text-lg font-semibold text-neutral-700">Thanh toán</th>
                                <th class="px-3 py-5 text-lg font-semibold text-neutral-700 text-right pr-8">Hành động</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-neutral-100">
                            <?php foreach ($bookings as $b): ?>
                                <tr class="hover:bg-primary-50/30 transition-colors duration-200">
                                    <td class="px-3 py-5 text-neutral-800 font-medium">
                                        <?= h($b->Người dùng->email) ?>
                                    </td>
                                    <td class="px-3 py-5 text-neutral-700">
                                        <?= h($b->Hội thảo->Hội thảo_name) ?>
                                    </td>
                                    <td class="px-3 py-5 text-neutral-600">
                                        <?= h($b->booking_Ngày->format('d M Y')) ?>
                                    </td>
                                    <td class="px-3 py-5">
                                        <?php
                                            $Trạng tháiClass = match ($b->Trạng thái) {
                                                'pending'   => 'bg-amber-100 text-amber-800 border-amber-300',
                                                'confirmed' => 'bg-primary-100 text-primary-800 border-primary-300',
                                                'cancelled' => 'bg-red-100 text-red-800 border-red-300',
                                                default     => 'bg-gray-100 text-gray-700 border-gray-300',
                                            };
                                        ?>
                                        <span class="inline-flex px-4 py-1.5 rounded-full text-sm font-medium border <?= $Trạng tháiClass ?>">
                                            <?= ucfirst(h($b->Trạng thái)) ?>
                                        </span>
                                    </td>
                                    <td class="px-3 py-5 text-neutral-600">
                                        <?php
                                            // Get Thanh toán Trạng thái from hasMany relationship
                                            $Thanh toánTrạng thái = 'N/A';
                                            if (!empty($b->Thanh toáns)) {
                                                foreach ($b->Thanh toáns as $p) {
                                                    if ($p->Thanh toán_Trạng thái === 'paid') {
                                                        $Thanh toánTrạng thái = 'paid';
                                                        break;
                                                    }
                                                    $Thanh toánTrạng thái = $p->Thanh toán_Trạng thái;
                                                }
                                            }
                                            $Thanh toánClass = match ($Thanh toánTrạng thái) {
                                                'paid'       => 'text-primary-600 font-medium',
                                                'pending'    => 'text-amber-600',
                                                'failed'     => 'text-red-600',
                                                default      => 'text-neutral-500',
                                            };
                                        ?>
                                        <span class="<?= $Thanh toánClass ?>">
                                            <?= ucfirst(h($Thanh toánTrạng thái)) ?>
                                        </span>
                                    </td>
                                    <td class="px-3 py-5 text-right pr-8">
                                        <div class="flex items-center justify-end gap-3 flex-wrap">
                                            <?php if ($b->Trạng thái === 'pending'): ?>
                                                <?= $this->Html->link(
                                                    'Confirm',
                                                    ['action' => 'upNgàyBooking', $b->id, 'confirmed'],
                                                    ['class' => 'inline-flex items-center px-4 py-2 text-sm font-semibold rounded-lg bg-primary-500 text-white hover:bg-primary-600 transition-all duration-300 shadow-sm hover:shadow']
                                                ) ?>

                                                <?= $this->Html->link(
                                                    'Cancel',
                                                    ['action' => 'upNgàyBooking', $b->id, 'cancelled'],
                                                    ['class' => 'inline-flex items-center px-4 py-2 text-sm font-semibold rounded-lg bg-white border-2 border-red-400 text-red-600 hover:bg-red-50 transition-all duration-300']
                                                ) ?>
                                            <?php else: ?>
                                                <span class="text-sm text-neutral-400 italic">Processed</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>

                    </table>
                </div>

            <?php endif; ?>

        </div>

    </div>
</main>

