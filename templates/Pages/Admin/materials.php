<?php
$this$this->assign('title', 'Quản lý Vật liệu');
?>

<div class="py-5 px-3 lg:px-4 max-w-screen-2xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-5">
        <div>
            <h1 class="text-xl md:text-lg font-serif font-bold text-neutral-900 mb-2">
                Vật liệu & Cung cấp
            </h1>
            <p class="text-lg text-neutral-600 font-serif">
                Quản lý vật liệu cần thiết cho mỗi hội thảo. Liên kết với nội dung hội thảo.
            </p>
        </div>
        <button 
            onclick="openModal()" 
            class="mt-3 md:mt-0 inline-flex items-center px-4 py-4 text-base font-semibold rounded-full bg-primary-500 text-white hover:bg-primary-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-[1.02]"
        >
            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Thêm Vật liệu Mới
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-2xl border border-neutral-200/50 overflow-hidden animate-fade-in">
        <div class="overflow-x-auto">
            <table class="w-full min-w-max">
                <thead class="bg-neutral-50/80 border-b border-neutral-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 uppercase tracking-[0.05em]">Tên</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 uppercase tracking-[0.05em]">Số lượng Cần thiết</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 uppercase tracking-[0.05em]">Hội thảo Liên kết</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-neutral-700 uppercase tracking-[0.05em]">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    <?php if ($materials->items()->isEmpty()): ?>
                        <tr>
                            <td colspan="4" class="px-4 py-4 text-center text-neutral-500 font-serif text-xl italic">
                                Chưa thêm vật liệu nào.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($materials as $m): ?>
                            <tr class="hover:bg-primary-50/40 transition-colors duration-200 group">
                                <td class="px-4 py-3 whitespace-nowrap text-neutral-900 font-medium group-hover:text-primary-700 transition-colors">
                                    <?= h($m->material_Tên) ?>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-neutral-700 text-center font-semibold">
                                    <?= h($m->quantity_required) ?>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="inline-flex px-5 py-2 rounded-full text-sm font-medium bg-primary-50 text-primary-800 border border-primary-200">
                                        <?= h($m->workshop->workshop_Tên ?? 'N/A') ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-4">
                                        <button 
                                            onclick="editMaterial(
                                                <?= $m->id ?>,
                                                '<?= h(addslashes($m->material_Tên)) ?>',
                                                '<?= h(addslashes($m->description ?? '')) ?>',
                                                <?= $m->quantity_required ?>,
                                                <?= $m->workshop_id ?? 'null' ?>
                                            )" 
                                            class="inline-flex items-center px-5 py-2 text-sm font-medium rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 transition-all shadow-sm hover:shadow"
                                        >
                                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </button>

                                        <?= $this->Form->postLink(
                                            'Delete',
                                            ['action' => 'deleteMaterial', $m->id],
                                            [
                                                'class' => 'inline-flex items-center px-5 py-2 text-sm font-medium rounded-lg bg-red-50 text-red-700 hover:bg-red-100 transition-all shadow-sm hover:shadow',
                                                'confirm' => 'Are you sure you want to delete this material?'
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

        <div class="px-4 py-5 bg-neutral-50/50 border-t border-neutral-200 text-sm text-neutral-600 flex justify-between items-center">
            <p>Showing <?= $materials->count() ?> material<?= $materials->count() !== 1 ? 's' : '' ?></p>
            <p class="text-right">Last updated: <?= date('M d, Y H:i') ?></p>
        </div>
    </div>
</div>

<!-- ================= MODAL (nâng cấp giao diện) ================= -->
<div id="materialModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity duration-300" style="display:none;">
    <div class="bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-neutral-200/50 w-full max-w-lg mx-6 p-5 relative animate-fade-in">
        <button onclick="closeModal()" class="absolute top-5 right-6 text-xl text-neutral-500 hover:text-neutral-800 transition-colors">
            ×
        </button>

        <h2 id="modalTitle" class="text-xl font-serif font-bold text-neutral-900 mb-4 text-center">
            Add Material
        </h2>

        <?= $this->Form->create(null, [
            'url' => ['controller' => 'Admin', 'action' => 'addMaterial'],
            'id' => 'materialForm',
            'class' => 'space-y-4'
        ]) ?>

        <input type="hidden" id="id" Tên="id">

        <!-- Tên -->
        <div class="relative">
            <input 
                type="text" 
                Tên="material_Tên" 
                id="Tên" 
                placeholder=" " 
                class="peer w-full px-5 py-5 bg-neutral-50/50 border border-neutral-300 rounded-xl text-neutral-900 placeholder-transparent focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-300"
            >
            <label class="absolute left-5 -top-3 px-2 bg-white text-sm font-serif text-neutral-600 pointer-events-none transition-all duration-300 peer-placeholder-shown:top-4 peer-placeholder-shown:text-base peer-focus:-top-3 peer-focus:text-sm peer-focus:text-primary-600">
                Material Tên
            </label>
        </div>

        <!-- Description -->
        <div class="relative">
            <textarea 
                Tên="description" 
                id="desc" 
                placeholder=" " 
                rows="4"
                class="peer w-full px-5 py-5 bg-neutral-50/50 border border-neutral-300 rounded-xl text-neutral-900 placeholder-transparent focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-300 resize-y min-h-[100px]"
            ></textarea>
            <label class="absolute left-5 -top-3 px-2 bg-white text-sm font-serif text-neutral-600 pointer-events-none transition-all duration-300 peer-placeholder-shown:top-4 peer-placeholder-shown:text-base peer-focus:-top-3 peer-focus:text-sm peer-focus:text-primary-600">
                Description
            </label>
        </div>

        <!-- Quantity -->
        <div class="relative">
            <input 
                type="number" 
                Tên="quantity_required" 
                id="qty" 
                placeholder=" " 
                min="1"
                class="peer w-full px-5 py-5 bg-neutral-50/50 border border-neutral-300 rounded-xl text-neutral-900 placeholder-transparent focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-300"
            >
            <label class="absolute left-5 -top-3 px-2 bg-white text-sm font-serif text-neutral-600 pointer-events-none transition-all duration-300 peer-placeholder-shown:top-4 peer-placeholder-shown:text-base peer-focus:-top-3 peer-focus:text-sm peer-focus:text-primary-600">
                Số lượng Cần thiết
            </label>
        </div>

        <!-- Workshop Select -->
        <div class="relative">
            <select 
                Tên="workshop_id" 
                id="workshop_id" 
                class="peer w-full px-5 py-5 bg-neutral-50/50 border border-neutral-300 rounded-xl text-neutral-900 focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-300 appearance-none"
            >
                <option value="">Select Hội thảo Liên kết</option>
                <?php foreach ($workshops as $id => $Tên): ?>
                    <option value="<?= $id ?>"><?= h($Tên) ?></option>
                <?php endforeach; ?>
            </select>
            <label class="absolute left-5 -top-3 px-2 bg-white text-sm font-serif text-neutral-600 pointer-events-none transition-all duration-300 peer-focus:-top-3 peer-focus:text-sm peer-focus:text-primary-600">
                Hội thảo Liên kết
            </label>
        </div>

        <!-- Submit -->
        <div class="text-center mt-5">
            <?= $this->Form->button('Save Material', [
                'class' => 'inline-flex items-center px-3 py-5 text-lg font-semibold rounded-full bg-primary-500 text-white hover:bg-primary-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-[1.02]'
            ]) ?>
        </div>

        <?= $this->Form->end() ?>
    </div>
</div>

<!-- ================= JS (giữ nguyên logic của bạn) ================= -->
<script>
function openModal(){
    document.getElementById('materialModal').style.display = 'flex';
    document.getElementById('materialForm').action = '<?= $this->Url->build(['controller' => 'Admin', 'action' => 'addMaterial']) ?>';
    // Reset form fields
    document.getElementById('id').value = '';
    document.getElementById('Tên').value = '';
    document.getElementById('desc').value = '';
    document.getElementById('qty').value = '';
    document.getElementById('workshop_id').value = '';
}

function closeModal(){
    document.getElementById('materialModal').style.display = 'none';
}

function editMaterial(id, Tên, desc, qty, workshop){
    openModal();
    document.getElementById('materialForm').action = '<?= $this->Url->build(['controller' => 'Admin', 'action' => 'editMaterial']) ?>/' + id;
    document.getElementById('id').value = id;
    document.getElementById('Tên').value = Tên;
    document.getElementById('desc').value = desc;
    document.getElementById('qty').value = qty;
    document.getElementById('workshop_id').value = workshop;
}

// Click outside modal to close
window.onclick = function(event) {
    let modal = document.getElementById('materialModal');
    if (event.target === modal) {
        modal.style.display = "none";
    }
};
</script>

<style>
/* Đảm bảo animation nhất quán */
.animate-fade-in {
    animation: fadeIn 0.6s ease-out forwards;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

