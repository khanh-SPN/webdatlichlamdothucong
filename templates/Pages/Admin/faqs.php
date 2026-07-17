<?php
$this$this->assign('title', 'Quản lý Câu hỏi thường gặp');
?>

<div class="py-5 px-3 lg:px-4 max-w-screen-2xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-5">
        <div>
            <h1 class="text-lg md:text-lg font-serif font-bold text-neutral-900 tracking-tighter">
                Quản lý Câu hỏi thường gặp
            </h1>
            <p class="text-xl text-neutral-600 font-medium mt-1">
                Quản lý tất cả câu hỏi thường gặp hiển thị trên trang web công khai.
            </p>
        </div>

        <button onclick="openModal()" 
                class="mt-3 md:mt-0 inline-flex items-center px-4 py-4 text-base font-semibold rounded-2xl bg-primary-600 text-white hover:bg-primary-700 transition-all shadow-lg hover:shadow-xl">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Thêm Câu hỏi thường gặp Mới
        </button>
    </div>

    <!-- Search and filter bar -->
    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow border border-neutral-100 p-5 mb-4 flex flex-col md:flex-row gap-4 items-center">
        <div class="relative flex-1">
            <input 
                type="text" 
                id="searchInput"
                placeholder="Tìm kiếm câu hỏi..." 
                class="w-full pl-12 pr-6 py-4 bg-white border border-neutral-200 rounded-2xl focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none text-lg placeholder:text-neutral-400"
            >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-neutral-400 absolute left-6 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 01-14 0 7 7 0 0114 0z" />
            </svg>
        </div>

        <select id="categoryFilter" class="px-3 py-4 bg-white border border-neutral-200 rounded-2xl focus:border-primary-500 outline-none text-lg">
            <option value="">Tất cả Danh mục</option>
            <?php 
            $categories = array_unique($faqs->extract('category')->toArray());
            foreach ($categories as $cat): 
                if (!empty($cat)): ?>
                    <option value="<?= h($cat) ?>"><?= h($cat) ?></option>
                <?php endif;
            endforeach; ?>
        </select>
    </div>

    <!-- Table -->
    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-2xl border border-neutral-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-max">
                <thead class="bg-neutral-50/90 border-b border-neutral-200 sticky top-0 z-10">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 uppercase tracking-[0.1em] w-2/5">Question</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 uppercase tracking-[0.1em]">Category</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-neutral-700 uppercase tracking-[0.1em] w-24">Order</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-neutral-700 uppercase tracking-[0.1em] w-40">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100" id="faqTableBody">
                    <?php if ($faqs->items()->isEmpty()): ?>
                        <tr>
                            <td colspan="4" class="px-4 py-4 text-center text-neutral-500 text-xl italic">
                                No FAQs yet. Click “Thêm Câu hỏi thường gặp Mới” to get started.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($faqs as $f): ?>
                            <tr class="faq-row hover:bg-primary-50/40 transition-colors group" 
                                data-question="<?= h(strtolower($f->question)) ?>"
                                data-category="<?= h($f->category) ?>">
                                <td class="px-4 py-7 font-medium text-neutral-900 group-hover:text-primary-700">
                                    <?= h($f->question) ?>
                                </td>
                                <td class="px-4 py-7">
                                    <?php if (!empty($f->category)): ?>
                                        <span class="inline-flex px-5 py-1.5 rounded-2xl text-sm font-medium bg-primary-100 text-primary-700 border border-primary-200">
                                            <?= h($f->category) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-neutral-400 italic">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-7 text-center font-semibold text-neutral-700">
                                    <?= $f->display_order ?>
                                </td>
                                <td class="px-4 py-7 text-center">
                                    <div class="flex items-center justify-center gap-3">
                                        <button onclick="editFaq(<?= $f->id ?>, '<?= h(addslashes($f->question)) ?>', '<?= h(addslashes($f->answer)) ?>', '<?= h(addslashes($f->category ?? '')) ?>', <?= $f->display_order ?>)" 
                                                class="px-3 py-2.5 text-sm font-medium rounded-2xl bg-amber-50 text-amber-700 hover:bg-amber-100 transition-all">
                                            Edit
                                        </button>
                                        <?= $this->Form->postLink(
                                            'Delete',
                                            ['action' => 'deleteFaq', $f->id],
                                            [
                                                'class' => 'px-3 py-2.5 text-sm font-medium rounded-2xl bg-red-50 text-red-700 hover:bg-red-100 transition-all',
                                                'confirm' => 'Are you sure you want to permanently delete this FAQ?'
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
    </div>

    <!-- Footer info -->
    <div class="mt-3 text-sm text-neutral-500 flex justify-between px-2">
        <p>Showing <?= $faqs->count() ?> FAQ<?= $faqs->count() !== 1 ? 's' : '' ?></p>
        <p>Last updated: <?= date('M d, Y • H:i') ?></p>
    </div>
</div>

<!-- ================= MODAL ================= -->
<div id="faqModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-md">
    <div class="bg-white/95 backdrop-blur-2xl rounded-2xl shadow-2xl w-full max-w-2xl mx-6 overflow-hidden">
        <div class="px-5 pt-8 pb-6 border-b flex items-center justify-between">
            <h2 id="modalTitle" class="text-xl font-serif font-semibold text-neutral-900">Thêm Câu hỏi thường gặp Mới</h2>
            <button onclick="closeModal()" class="text-lg text-neutral-400 hover:text-neutral-600">×</button>
        </div>

        <?= $this->Form->create(null, [
            'url' => ['controller' => 'Admin', 'action' => 'addFaq'],
            'id' => 'faqForm',
            'class' => 'p-5 space-y-4'
        ]) ?>

        <input type="hidden" name="id" id="faq_id">

        <!-- Question -->
        <div>
            <label class="block text-sm font-medium text-neutral-700 mb-2">Question</label>
            <input type="text" name="question" id="question" 
                   class="w-full px-3 py-5 rounded-2xl border border-neutral-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-100 outline-none text-lg">
        </div>

        <!-- Answer -->
        <div>
            <label class="block text-sm font-medium text-neutral-700 mb-2">Answer</label>
            <textarea name="answer" id="answer" rows="7"
                      class="w-full px-3 py-5 rounded-2xl border border-neutral-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-100 outline-none resize-y"></textarea>
        </div>

        <!-- Category + Display Order -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">Category</label>
                <input type="text" name="category" id="category" placeholder="e.g. Booking, Payment, Workshop"
                       class="w-full px-3 py-5 rounded-2xl border border-neutral-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-100 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">Display Order</label>
                <input type="number" name="display_order" id="order" min="0"
                       class="w-full px-3 py-5 rounded-2xl border border-neutral-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-100 outline-none">
            </div>
        </div>

        <div class="flex justify-end gap-4 pt-6">
            <button type="button" onclick="closeModal()" 
                    class="px-5 py-4 rounded-2xl border border-neutral-300 hover:bg-neutral-100 font-medium">
                Cancel
            </button>
            <button type="submit" 
                    class="px-5 py-4 rounded-2xl bg-primary-600 hover:bg-primary-700 text-white font-semibold transition-all">
                Save FAQ
            </button>
        </div>

        <?= $this->Form->end() ?>
    </div>
</div>

<?php
$addFaqUrl = $this->Url->build(['controller' => 'Admin', 'action' => 'addFaq']);
$editFaqBaseUrl = $this->Url->build(['controller' => 'Admin', 'action' => 'editFaq']);
?>
<!-- JavaScript -->
<script>
const ADD_FAQ_URL = '<?= $addFaqUrl ?>';
const EDIT_FAQ_BASE_URL = '<?= $editFaqBaseUrl ?>';

function openModal() {
    openAddModal();
}

function openAddModal() {
    document.getElementById('faqModal').classList.remove('hidden');
    document.getElementById('faqModal').classList.add('flex');
    document.getElementById('modalTitle').textContent = 'Thêm Câu hỏi thường gặp Mới';
    document.getElementById('faqForm').action = ADD_FAQ_URL;
    document.getElementById('faq_id').value = '';
    document.getElementById('question').value = '';
    document.getElementById('answer').value = '';
    document.getElementById('category').value = '';
    document.getElementById('order').value = '';
}

function closeModal() {
    const modal = document.getElementById('faqModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function editFaq(id, question, answer, category, order) {
    document.getElementById('faqModal').classList.remove('hidden');
    document.getElementById('faqModal').classList.add('flex');
    document.getElementById('modalTitle').textContent = 'Edit FAQ';
    document.getElementById('faqForm').action = EDIT_FAQ_BASE_URL + '/' + id;
    document.getElementById('faq_id').value = id;
    document.getElementById('question').value = question;
    document.getElementById('answer').value = answer;
    document.getElementById('category').value = category;
    document.getElementById('order').value = order;
}

// Live Search + Filter
document.getElementById('searchInput').addEventListener('keyup', filterTable);
document.getElementById('categoryFilter').addEventListener('change', filterTable);

function filterTable() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase().trim();
    const categoryFilter = document.getElementById('categoryFilter').value;
    
    document.querySelectorAll('.faq-row').forEach(row => {
        const question = row.getAttribute('data-question');
        const category = row.getAttribute('data-category');
        
        const matchSearch = !searchTerm || question.includes(searchTerm);
        const matchCategory = !categoryFilter || category === categoryFilter;
        
        row.style.display = (matchSearch && matchCategory) ? '' : 'none';
    });
}
</script>

