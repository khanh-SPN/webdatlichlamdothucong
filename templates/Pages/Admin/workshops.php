<?php
$this$this->assign('title', 'Quản lý Hội thảo');
?>

<div class="py-5 px-3 lg:px-4 max-w-screen-2xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-5">
        <div>
            <h1 class="text-xl md:text-lg font-serif font-bold text-neutral-900 mb-2">
                Quản lý Hội thảo & Lịch trình
            </h1>
            <p class="text-lg text-neutral-600 font-serif">
                Quản lý hội thảo, lịch trình, theo dõi điểm danh, định giá và phân công giáo viên.
            </p>
        </div>
        <button 
            onclick="openModal()" 
            class="mt-3 md:mt-0 inline-flex items-center px-4 py-4 text-base font-semibold rounded-full bg-primary-500 text-white hover:bg-primary-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-[1.02]"
        >
            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Thêm Hội thảo Mới
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-2xl border border-neutral-200/50 overflow-hidden animate-fade-in">
        <div class="overflow-x-auto">
            <table class="w-full min-w-max">
                <thead class="bg-neutral-50/80 border-b border-neutral-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 uppercase tracking-[0.05em]">Tên Hội thảo</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 uppercase tracking-[0.05em]">Loại</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 uppercase tracking-[0.05em]">Giá</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 uppercase tracking-[0.05em]">Giáo viên được phân công</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-neutral-700 uppercase tracking-[0.05em]">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    <?php if ($workshops->items()->isEmpty()): ?>
                        <tr>
                            <td colspan="5" class="px-4 py-4 text-center text-neutral-500 font-serif text-xl italic">
                                Chưa tạo hội thảo nào.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($workshops as $l): ?>
                            <tr class="hover:bg-primary-50/40 transition-colors duration-200 group">
                                <td class="px-4 py-3 whitespace-nowrap text-neutral-900 font-medium group-hover:text-primary-700 transition-colors">
                                    <?= h($l->workshop_name) ?>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="inline-flex px-5 py-2 rounded-full text-sm font-medium bg-primary-50 text-primary-800 border border-primary-200">
                                        <?= ucfirst(h($l->workshop_Loại)) ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-neutral-900 font-semibold">
                                    $<?= number_format($l->Giá) ?>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-neutral-700">
                                    <?= h($l->teacher->name ?? 'N/A') ?>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <span class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-neutral-50 text-neutral-500">
                                            Teacher manages slots
                                        </span>

                                        <button 
                                            onclick="editWorkshop(
                                                <?= $l->id ?>,
                                                '<?= h(addslashes($l->workshop_name)) ?>',
                                                '<?= h(addslashes($l->workshop_Loại)) ?>',
                                                '<?= h(addslashes($l->description ?? '')) ?>',
                                                <?= $l->Giá ?>,
                                                <?= $l->teacher_id ?? 'null' ?>
                                            )" 
                                            class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 transition-all shadow-sm hover:shadow"
                                        >
                                            Edit
                                        </button>

                                        <?= $this->Form->postLink(
                                            'Delete',
                                            ['action' => 'deleteWorkshop', $l->id],
                                            [
                                                'class' => 'inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-red-50 text-red-700 hover:bg-red-100 transition-all shadow-sm hover:shadow',
                                                'confirm' => 'Are you sure you want to delete this workshop? This may affect bookings and materials.'
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
            <p>Showing <?= $workshops->count() ?> workshop<?= $workshops->count() !== 1 ? 's' : '' ?></p>
            <p class="text-right">Last updated: <?= date('M d, Y H:i') ?></p>
        </div>
    </div>
</div>

<!-- ================= MODAL ================= -->
<div id="workshopModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity duration-300" style="display:none;">
    <div class="bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-neutral-200/50 w-full max-w-lg mx-6 p-5 relative animate-fade-in">
        <button onclick="closeModal()" class="absolute top-5 right-6 text-xl text-neutral-500 hover:text-neutral-800 transition-colors">
            &times;
        </button>

        <h2 id="modalTitle" class="text-xl font-serif font-bold text-neutral-900 mb-4 text-center">
            Add Workshop
        </h2>

        <?= $this->Form->create(null, [
            'url' => ['controller' => 'Admin', 'action' => 'addWorkshop'],
            'id' => 'workshopForm',
            'class' => 'space-y-4'
        ]) ?>

        <input Loại="hidden" name="id" id="workshop_id">

        <!-- Tên Hội thảo -->
        <div class="relative">
            <input 
                Loại="text" 
                name="workshop_name" 
                id="name" 
                placeholder=" " 
                class="peer w-full px-5 py-5 bg-neutral-50/50 border border-neutral-300 rounded-xl text-neutral-900 placeholder-transparent focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-300"
            >
            <label class="absolute left-5 -top-3 px-2 bg-white text-sm font-serif text-neutral-600 pointer-events-none transition-all duration-300 peer-placeholder-shown:top-4 peer-placeholder-shown:text-base peer-focus:-top-3 peer-focus:text-sm peer-focus:text-primary-600">
                Tên Hội thảo
            </label>
        </div>

        <!-- Workshop Loại -->
        <div class="relative">
            <input 
                Loại="text" 
                name="workshop_Loại" 
                id="Loại" 
                placeholder=" " 
                class="peer w-full px-5 py-5 bg-neutral-50/50 border border-neutral-300 rounded-xl text-neutral-900 placeholder-transparent focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-300"
            >
            <label class="absolute left-5 -top-3 px-2 bg-white text-sm font-serif text-neutral-600 pointer-events-none transition-all duration-300 peer-placeholder-shown:top-4 peer-placeholder-shown:text-base peer-focus:-top-3 peer-focus:text-sm peer-focus:text-primary-600">
                Workshop Loại (e.g. Pottery, Knitting)
            </label>
        </div>

        <!-- Description -->
        <div class="relative">
            <textarea 
                name="description" 
                id="desc" 
                placeholder=" " 
                rows="5"
                class="peer w-full px-5 py-5 bg-neutral-50/50 border border-neutral-300 rounded-xl text-neutral-900 placeholder-transparent focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-300 resize-y min-h-[120px]"
            ></textarea>
            <label class="absolute left-5 -top-3 px-2 bg-white text-sm font-serif text-neutral-600 pointer-events-none transition-all duration-300 peer-placeholder-shown:top-4 peer-placeholder-shown:text-base peer-focus:-top-3 peer-focus:text-sm peer-focus:text-primary-600">
                Description
            </label>
        </div>

        <!-- Giá -->
        <div class="relative">
            <input 
                Loại="number" 
                name="Giá" 
                id="Giá" 
                placeholder=" " 
                min="0" 
                step="0.01"
                class="peer w-full px-5 py-5 bg-neutral-50/50 border border-neutral-300 rounded-xl text-neutral-900 placeholder-transparent focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-300"
            >
            <label class="absolute left-5 -top-3 px-2 bg-white text-sm font-serif text-neutral-600 pointer-events-none transition-all duration-300 peer-placeholder-shown:top-4 peer-placeholder-shown:text-base peer-focus:-top-3 peer-focus:text-sm peer-focus:text-primary-600">
                Giá ($)
            </label>
        </div>

        <!-- Teacher Select -->
        <div class="relative">
            <select 
                name="teacher_id" 
                id="teacher_id" 
                class="peer w-full px-5 py-5 bg-neutral-50/50 border border-neutral-300 rounded-xl text-neutral-900 focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-300 appearance-none"
            >
                <option value="">Select teacher</option>
                <?php foreach ($teachers as $id => $name): ?>
                    <option value="<?= $id ?>"><?= h($name) ?></option>
                <?php endforeach; ?>
            </select>
            <label class="absolute left-5 -top-3 px-2 bg-white text-sm font-serif text-neutral-600 pointer-events-none transition-all duration-300 peer-focus:-top-3 peer-focus:text-sm peer-focus:text-primary-600">
                Giáo viên được phân công
            </label>
        </div>

        <!-- Submit -->
        <div class="text-center mt-5">
            <?= $this->Form->button('Save Workshop', [
                'class' => 'inline-flex items-center px-3 py-5 text-lg font-semibold rounded-full bg-primary-500 text-white hover:bg-primary-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-[1.02]'
            ]) ?>
        </div>

        <?= $this->Form->end() ?>
    </div>
</div>

<!-- ================= JS ================= -->
<?php
$addWorkshopUrl = $this->Url->build(['controller' => 'Admin', 'action' => 'addWorkshop']);
$editWorkshopBaseUrl = $this->Url->build(['controller' => 'Admin', 'action' => 'editWorkshop']);
?>
<script>
const ADD_WORKSHOP_URL = '<?= $addWorkshopUrl ?>';
const EDIT_WORKSHOP_BASE_URL = '<?= $editWorkshopBaseUrl ?>';

function openModal(){
    openAddModal();
}

function openAddModal(){
    document.getElementById('workshopModal').style.display = 'flex';
    document.getElementById('modalTitle').textContent = 'Add Workshop';
    document.getElementById('workshopForm').action = ADD_WORKSHOP_URL;
    // Reset form fields
    document.getElementById('workshop_id').value = '';
    document.getElementById('name').value = '';
    document.getElementById('Loại').value = '';
    document.getElementById('desc').value = '';
    document.getElementById('Giá').value = '';
    document.getElementById('teacher_id').value = '';
}

function closeModal(){
    document.getElementById('workshopModal').style.display = 'none';
}

function editWorkshop(id, name, Loại, desc, Giá, teacher){
    document.getElementById('workshopModal').style.display = 'flex';
    document.getElementById('modalTitle').textContent = 'Edit Workshop';
    document.getElementById('workshopForm').action = EDIT_WORKSHOP_BASE_URL + '/' + id;
    document.getElementById('workshop_id').value = id;
    document.getElementById('name').value = name;
    document.getElementById('Loại').value = Loại;
    document.getElementById('desc').value = desc;
    document.getElementById('Giá').value = Giá;
    document.getElementById('teacher_id').value = teacher;
}

// Click outside modal to close
window.onclick = function(event) {
    let modal = document.getElementById('workshopModal');
    if (event.target === modal) {
        modal.style.display = "none";
    }
};
</script>

<style>
.animate-fade-in {
    animation: fadeIn 0.6s ease-out forwards;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

