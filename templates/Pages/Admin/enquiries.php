<?php $this$this->assign('title', 'Yêu cầu Khách hàng'); ?>

<?php
use Cake\Utility\Text;
?>

<main class="min-h-screen pt-16 md:pt-20 pb-20 bg-neutral-50">
    <div class="max-w-screen-2xl mx-auto px-3 lg:px-4">

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-3 animate-fade-in">
            <div>
                <h1 class="text-lg font-serif font-semibold text-neutral-900 tracking-tighter">
                    Yêu cầu Khách hàng
                </h1>
                <p class="mt-3 text-xl text-neutral-600">
                    Quản lý câu hỏi và tin nhắn từ học viên tiềm năng
                </p>
            </div>

            <div class="mt-3 md:mt-0 bg-white/80 backdrop-blur-xl px-4 py-3 rounded-2xl shadow border border-neutral-200/60 text-center min-w-[200px]">
                <div class="text-lg font-bold text-primary-600"><?= $enquiries->count() ?></div>
                <div class="text-sm text-neutral-500 font-medium tracking-[0.1em] mt-1">TỔNG YÊU CẦU</div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-2xl border border-neutral-200/50 overflow-hidden">

            <?php if ($enquiries->isEmpty()): ?>
                <div class="py-24 text-center">
                    <p class="text-lg text-neutral-400 font-serif">Chưa có yêu cầu nào.</p>
                    <p class="mt-4 text-neutral-500">Tin nhắn mới sẽ tự động xuất hiện ở đây.</p>
                </div>
            <?php else: ?>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1100px] divide-y divide-neutral-100">
                        <thead class="bg-neutral-50/90 sticky top-0 z-10 backdrop-blur-sm border-b border-neutral-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 uppercase tracking-[0.05em] w-52">Tên</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 uppercase tracking-[0.05em] w-72">Email</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 uppercase tracking-[0.05em]">Chủ đề</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 uppercase tracking-[0.05em] w-[28rem]">Xem trước Tin nhắn</th>
                                <th class="px-4 py-3 text-center text-sm font-semibold text-neutral-700 uppercase tracking-[0.05em] w-32">Trạng thái</th>
                                <th class="px-3 py-3 text-center text-sm font-semibold text-neutral-700 uppercase tracking-[0.05em] w-32">Hành động</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-neutral-100">
                            <?php foreach ($enquiries as $e): ?>
                                <tr class="group hover:bg-primary-50/30 transition-colors duration-200">
                                    <td class="px-4 py-3 whitespace-nowrap font-medium text-neutral-900">
                                        <?= h(trim($e->first_Tên . ' ' . $e->last_Tên)) ?: 'N/A' ?>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-neutral-700">
                                        <?= h($e->Email) ?>
                                    </td>
                                    <td class="px-4 py-3 text-neutral-800">
                                        <div class="line-clamp-2 max-w-md" title="<?= h($e->Chủ đề) ?>">
                                            <?= h($e->Chủ đề) ?: 'No Chủ đề' ?>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-neutral-600 text-sm">
                                        <div class="line-clamp-3 max-w-md break-all" style="overflow-wrap:anywhere;" title="<?= h($e->message) ?>">
                                            <?php
                                            $msg = (string)($e->message ?? '');
                                            $limit = 100;
                                            $needsEllipsis = mb_strlen($msg) > $limit;
                                            $preview = $needsEllipsis ? mb_substr($msg, 0, $limit) : $msg;
                                            echo h($preview . ($needsEllipsis ? '...' : ''));
                                            ?>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <?php
                                            $Trạng tháiMap = [
                                                'pending' => ['label' => 'Pending', 'class' => 'bg-amber-100 text-amber-800 border-amber-300'],
                                                'replied' => ['label' => 'Replied', 'class' => 'bg-emerald-100 text-emerald-800 border-emerald-300'],
                                                'closed'  => ['label' => 'Closed',  'class' => 'bg-neutral-100 text-neutral-700 border-neutral-300'],
                                            ];
                                            $st = $Trạng tháiMap[$e->Trạng thái] ?? ['label' => ucfirst(h($e->Trạng thái)), 'class' => 'bg-neutral-100 text-neutral-700 border-neutral-300'];
                                        ?>
                                        <span class="inline-flex px-5 py-1.5 rounded-full text-xs font-medium border <?= $st['class'] ?>">
                                            <?= $st['label'] ?>
                                        </span>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button 
                                                onclick="openEnquiryModal(<?= $e->id ?>)" 
                                                class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold rounded-xl bg-white border border-neutral-200 hover:border-primary-300 hover:bg-primary-50 transition-all">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                View Full
                                            </button>

                                            <?php if ($e->Trạng thái === 'pending'): ?>
                                                <?= $this->Form->postLink(
                                                    'Mark Replied',
                                                    ['action' => 'markReplied', $e->id],
                                                    [
                                                        'class' => 'px-4 py-2 text-xs font-semibold rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition-all',
                                                        'confirm' => 'Mark this enquiry as replied?'
                                                    ]
                                                ) ?>
                                            <?php endif; ?>

                                            <?= $this->Form->postLink(
                                                'Delete',
                                                ['action' => 'deleteEnquiry', $e->id],
                                                [
                                                    'class' => 'px-4 py-2 text-xs font-semibold rounded-xl bg-red-50 text-red-700 hover:bg-red-100 transition-all',
                                                    'confirm' => 'Delete this enquiry permanently?'
                                                ]
                                            ) ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            <?php endif; ?>

            <!-- Footer -->
            <div class="px-4 py-5 bg-neutral-50/70 border-t border-neutral-100 text-sm text-neutral-500 flex justify-between items-center">
                <div>Showing <?= $enquiries->count() ?> enquiries • Sorted by newest</div>
                <div>Last updated: <?= date('d M Y • H:i') ?></div>
            </div>
        </div>
    </div>

    <!-- ================= MODALS ================= -->
    <?php foreach ($enquiries as $e): ?>
    <div id="modal-<?= $e->id ?>" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-black/40 backdrop-blur-md">
        <div class="bg-white/95 backdrop-blur-2xl rounded-2xl shadow-2xl border border-neutral-100 w-full max-w-2xl mx-4 overflow-hidden">
            
            <!-- Header -->
            <div class="px-5 pt-8 pb-6 border-b border-neutral-100 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-serif font-semibold text-neutral-900"><?= h($e->Chủ đề) ?: 'Enquiry without Chủ đề' ?></h2>
                    <p class="text-sm text-neutral-500 mt-1">
                        From <?= h(trim($e->first_Tên . ' ' . $e->last_Tên)) ?> • <?= $e->created ? $e->created->format('d M Y • H:i') : '' ?>
                    </p>
                </div>
                <button onclick="closeEnquiryModal(<?= $e->id ?>)" 
                        class="text-lg text-neutral-400 hover:text-neutral-600 transition-colors">×</button>
            </div>

            <!-- Content -->
            <div class="p-5 space-y-4">
                <div>
                    <div class="uppercase text-xs tracking-[0.1em] text-neutral-500 mb-2">Message</div>
                    <div class="prose prose-neutral max-w-none break-words text-neutral-700 leading-relaxed bg-neutral-50 rounded-2xl p-4 border border-neutral-100 whitespace-pre-wrap" style="overflow-wrap:anywhere;">
                        <?= nl2br(h($e->message)) ?: '<em class="text-neutral-400">No message content.</em>' ?>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <div class="text-neutral-500">Email</div>
                        <div class="font-medium"><?= h($e->Email) ?></div>
                    </div>
                    <div>
                        <div class="text-neutral-500">Phone</div>
                        <div class="font-medium"><?= h($e->phone ?: 'N/A') ?></div>
                    </div>
                </div>
            </div>

            <!-- Footer Hành động -->
            <div class="px-4 py-2 border-t border-neutral-100 bg-neutral-50/80 flex justify-end gap-4">
                <?php if ($e->Trạng thái === 'pending'): ?>
                    <?= $this->Form->postLink(
                        'Mark as Replied',
                        ['action' => 'markReplied', $e->id],
                        ['class' => 'px-4 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-2xl transition-all']
                    ) ?>
                <?php endif; ?>
                
                <button onclick="closeEnquiryModal(<?= $e->id ?>)" 
                        class="px-4 py-3.5 border border-neutral-300 hover:bg-neutral-100 font-medium rounded-2xl transition-all">
                    Close
                </button>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

</main>

<script>
function openEnquiryModal(id) {
    const modal = document.getElementById('modal-' + id);
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
}

function closeEnquiryModal(id) {
    const modal = document.getElementById('modal-' + id);
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }
}

// Click outside to close
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('fixed') && e.target.id.startsWith('modal-')) {
        const id = e.target.id.replace('modal-', '');
        closeEnquiryModal(id);
    }
});

// Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const openModal = document.querySelector('.fixed.flex');
        if (openModal) {
            const id = openModal.id.replace('modal-', '');
            closeEnquiryModal(id);
        }
    }
});
</script>

<style>
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .animate-fade-in {
        animation: fadeIn 0.6s ease-out forwards;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

