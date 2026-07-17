<?php
$this$this->assign('title', 'Quản lý Người dùng');
?>

<div class="py-5 px-3 lg:px-4 max-w-screen-2xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-5">
        <div>
            <h1 class="text-lg md:text-lg font-serif font-bold text-neutral-900 mb-2">
                Quản lý Người dùng
            </h1>
            <p class="text-xl text-neutral-600 font-serif">
                Xem và quản lý tất cả tài khoản đã đăng ký trong hệ thống.
            </p>
        </div>
        
        <div class="mt-3 md:mt-0">
            <?= $this->Html->link('Thêm Người dùng Mới', 
                ['action' => 'addUser'], 
                ['class' => 'inline-flex items-center px-3 py-3 bg-amber-600 hover:bg-amber-700 text-white rounded-2xl font-medium transition-all']
            ) ?>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-2xl border border-neutral-200/50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-max">
                <thead class="bg-neutral-50 border-b border-neutral-200">
                    <tr>
                        <th class="px-3 py-5 text-left text-xs font-semibold text-neutral-700 uppercase tracking-[0.05em]">Email</th>
                        <th class="px-3 py-5 text-left text-xs font-semibold text-neutral-700 uppercase tracking-[0.05em]">Tên liên hệ</th>
                        <th class="px-3 py-5 text-left text-xs font-semibold text-neutral-700 uppercase tracking-[0.05em]">Điện thoại</th>
                        <th class="px-3 py-5 text-left text-xs font-semibold text-neutral-700 uppercase tracking-[0.05em] min-w-[10rem]">Địa chỉ</th>
                        <th class="px-3 py-5 text-left text-xs font-semibold text-neutral-700 uppercase tracking-[0.05em]">Vai trò</th>
                        <th class="px-3 py-5 text-center text-xs font-semibold text-neutral-700 uppercase tracking-[0.05em]">Thất bại</th>
                        <th class="px-3 py-5 text-center text-xs font-semibold text-neutral-700 uppercase tracking-[0.05em]">Trạng thái</th>
                        <th class="px-3 py-5 text-center text-xs font-semibold text-neutral-700 uppercase tracking-[0.05em]">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    <?php if ($users->isEmpty()): ?>
                        <tr>
                            <td colspan="8" class="px-4 py-4 text-center text-neutral-500 font-serif text-xl italic">
                                Không tìm thấy người dùng nào trong hệ thống.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $u): ?>
                            <?php
                            $isLocked = ($u->Thất bại_login_attempts >= 5 && $u->last_Thất bại_login);
                            $cust = $u->customer ?? null;
                            $contactName = $cust && trim((string) $cust->name) !== '' ? $cust->name : null;
                            $Điện thoại = $cust && trim((string) $cust->Điện thoại) !== '' ? $cust->Điện thoại : null;
                            $addr = $cust && trim((string) $cust->Địa chỉ) !== '' ? $cust->Địa chỉ : null;
                            ?>
                            <tr class="hover:bg-amber-50/30 transition-colors duration-200 group align-top">
                                <td class="px-3 py-5 text-neutral-900 font-medium">
                                    <a href="mailto:<?= h($u->Email) ?>" class="text-primary-700 hover:underline"><?= h($u->Email) ?></a>
                                </td>
                                <td class="px-3 py-5 text-neutral-800 text-sm max-w-[12rem]">
                                    <?php if ($contactName !== null) : ?>
                                        <?= h($contactName) ?>
                                    <?php else : ?>
                                        <span class="text-neutral-400">None</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-3 py-5 text-sm">
                                    <?php if ($Điện thoại !== null) : ?>
                                        <a href="tel:<?= h(preg_replace('/[^\d+]/', '', $Điện thoại)) ?>" class="text-primary-700 hover:underline whitespace-nowrap"><?= h($Điện thoại) ?></a>
                                    <?php else : ?>
                                        <span class="text-neutral-400">None</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-3 py-5 text-sm text-neutral-700 max-w-xs">
                                    <?php if ($addr !== null) : ?>
                                        <span class="line-clamp-2" title="<?= h($addr) ?>"><?= h($addr) ?></span>
                                    <?php else : ?>
                                        <span class="text-neutral-400">None</span>
                                    <?php endif; ?>
                                </td>

                                <td class="px-3 py-5 whitespace-nowrap">
                                    <span class="inline-flex px-5 py-2 rounded-full text-sm font-medium tracking-wide
                                        <?= $u->Vai trò === 'admin' 
                                            ? 'bg-amber-100 text-amber-800 border border-amber-200' 
                                            : 'bg-neutral-100 text-neutral-700 border border-neutral-200' ?>">
                                        <?= strtoupper(h($u->Vai trò)) ?>
                                    </span>
                                </td>

                                <td class="px-3 py-5 text-center font-mono text-sm">
                                    <?= $u->Thất bại_login_attempts ?>
                                </td>

                                <td class="px-3 py-5 text-center">
                                    <?php if ($isLocked): ?>
                                        <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-red-100 text-red-700 text-xs font-medium">
                                            <span class="w-2 h-2 bg-red-500 rounded-full mr-2 animate-pulse"></span>
                                            LOCKED
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex px-4 py-1.5 rounded-full bg-green-100 text-green-700 text-xs font-medium">
                                            Active
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td class="px-3 py-5 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-3">
                                        <!-- Unlock Button -->
                                        <?php if ($isLocked): ?>
                                            <?= $this->Html->link('Unlock', 
                                                ['action' => 'unlockUser', $u->id], 
                                                [
                                                    'class' => 'inline-flex items-center px-5 py-2 text-sm font-medium rounded-xl bg-green-600 hover:bg-green-700 text-white transition-all',
                                                    'onclick' => "return confirm('Unlock this user account?');"
                                                ]
                                            ) ?>
                                        <?php endif; ?>

                                        <!-- Delete Button -->
                                        <?= $this->Html->link('Delete', 
                                            ['action' => 'deleteUser', $u->id], 
                                            [
                                                'class' => 'inline-flex items-center px-5 py-2 text-sm font-medium rounded-xl bg-red-50 text-red-700 hover:bg-red-100 hover:text-red-800 transition-all',
                                                'onclick' => "return confirm('Are you sure you want to permanently delete this user? This action cannot be undone.');"
                                            ]
                                        ) ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Table Footer -->
        <div class="px-4 py-5 bg-neutral-50/70 border-t border-neutral-200 text-sm text-neutral-600 flex justify-between items-center">
            <p>Showing <?= $users->count() ?> user<?= $users->count() !== 1 ? 's' : '' ?></p>
            <p>Last updated: <?= date('M d, Y H:i') ?></p>
        </div>
    </div>
</div>

