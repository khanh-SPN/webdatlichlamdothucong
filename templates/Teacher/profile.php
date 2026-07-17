<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Teacher $instructor
 */
$this$this->assign('title', 'Hồ sơ Giáo viên');
?>
<div class="rounded-2xl border border-neutral-200/70 bg-white/90 p-4 shadow-lg shadow-neutral-900/5 backdrop-blur-xl md:p-3">
    <h1 class="font-serif text-xl font-semibold text-neutral-900 md:text-lg">Hồ sơ</h1>
    <p class="mt-1 text-sm text-neutral-600">Cập nhật tiểu sử công khai, chuyên môn và ảnh của bạn. Email tài khoản của bạn vẫn đồng bộ với hồ sơ quản trị viên.</p>

    <!-- Status notification -->
    <div id="saveStatus" class="mt-4 hidden rounded-xl border px-3 py-2 text-sm transition-all duration-300">
        <div id="saveStatusContent" class="flex items-center gap-2">
            <svg id="saveStatusIcon" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"></svg>
            <span id="saveStatusText"></span>
        </div>
    </div>

    <div class="mt-3 flex flex-col gap-3 md:flex-row md:items-start">
        <div class="shrink-0">
            <?php if (!empty($instructor->photo)): ?>
                <img id="photoPreview" src="<?= h('/' . ltrim((string) $instructor->photo, '/')) ?>" alt="" class="h-24 w-24 rounded-xl border border-neutral-200 object-cover shadow-md">
            <?php else: ?>
                <div id="photoPreview" class="flex h-24 w-24 items-center justify-center rounded-xl border border-dashed border-neutral-300 bg-neutral-50 text-sm text-neutral-500">
                    Không có ảnh
                </div>
            <?php endif; ?>
        </div>
        <div class="min-w-0 flex-1">
            <?= $this->Form->create($instructor, ['type' => 'file', 'id' => 'Hồ sơForm', 'class' => 'space-y-4']) ?>
            <?= $this->Form->control('specialization', [
                'label' => 'Chuyên môn',
                'class' => 'w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-900 focus:border-primary-500 focus:outline-none focus:ring-1 focus:ring-primary-200',
            ]) ?>
            <?= $this->Form->control('bio', [
                'type' => 'textarea',
                'label' => 'Tiểu sử',
                'rows' => 4,
                'class' => 'w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-900 focus:border-primary-500 focus:outline-none focus:ring-1 focus:ring-primary-200',
            ]) ?>
            <?= $this->Form->control('photo_file', [
                'type' => 'file',
                'label' => 'Ảnh mới \(JPEG, PNG hoặc WebP\)',
                'id' => 'photoFileInput',
                'class' => 'block w-full text-xs text-neutral-600 file:mr-3 file:rounded-md file:border-0 file:bg-primary-50 file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-primary-800 hover:file:bg-primary-100',
            ]) ?>
            <?php if (!empty($instructor->photo)): ?>
                <label class="flex items-center gap-2 text-xs text-neutral-700">
                    <?= $this->Form->checkbox('remove_photo', ['value' => '1']) ?>
                    Remove current photo
                </label>
            <?php endif; ?>
            <div class="flex items-center gap-3 pt-2">
                <?= $this->Form->button('Save Hồ sơ', [
                    'id' => 'saveButton',
                    'class' => 'inline-flex rounded-full bg-primary-600 px-5 py-2 text-xs font-semibold text-white shadow-sm hover:bg-primary-700 disabled:opacity-50 disabled:cursor-not-allowed',
                ]) ?>
                <span id="savingIndicator" class="hidden text-xs text-neutral-600 flex items-center gap-1.5">
                    <svg class="h-3 w-3 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    <span>Saving...</span>
                </span>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const Hồ sơForm = document.getElementById('Hồ sơForm');
    const photoFileInput = document.getElementById('photoFileInput');
    const saveButton = document.getElementById('saveButton');
    const savingIndicator = document.getElementById('savingIndicator');
    const saveStatus = document.getElementById('saveStatus');
    const saveStatusText = document.getElementById('saveStatusText');
    const saveStatusIcon = document.getElementById('saveStatusIcon');
    const photoPreview = document.getElementById('photoPreview');

    // Photo preview on file select
    photoFileInput?.addEventListener('change', function(e) {
        const file = e.target.files?.[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                if (photoPreview instanceof HTMLImageElement) {
                    photoPreview.src = event.target?.result || '';
                    photoPreview.classList.remove('hidden');
                } else {
                    photoPreview.innerHTML = `<img src="${event.target?.result || ''}" alt="" class="h-20 w-20 rounded-xl border border-neutral-200 object-cover shadow-md">`;
                }
            };
            reader.readAsDataURL(file);
        }
    });

    // Form submission
    Hồ sơForm?.addEventListener('submit', function(e) {
        e.preventDefault();

        // Show saving state
        savingIndicator.classList.remove('hidden');
        saveButton.disabled = true;
        saveStatus.classList.add('hidden');

        const formData = new FormData(Hồ sơForm);

        fetch(Hồ sơForm.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json().catch(() => ({ status: 'error', message: 'Invalid response' })))
        .then(data => {
            savingIndicator.classList.add('hidden');
            saveStatus.classList.remove('hidden');

            if (data.status === 'ok') {
                // Show success message
                saveStatus.classList.remove('border-red-200', 'bg-red-50', 'text-red-950');
                saveStatus.classList.add('border-green-200', 'bg-green-50', 'text-green-950');

                saveStatusIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>';
                saveStatusIcon.classList.remove('text-red-600');
                saveStatusIcon.classList.add('text-green-600');

                saveStatusText.textContent = '✓ Hồ sơ saved successfully! Reloading...';

                // Reload page after 1 second to show updated data
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                // Show error from server
                saveStatus.classList.remove('border-green-200', 'bg-green-50', 'text-green-950');
                saveStatus.classList.add('border-red-200', 'bg-red-50', 'text-red-950');

                saveStatusIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>';
                saveStatusIcon.classList.remove('text-green-600');
                saveStatusIcon.classList.add('text-red-600');

                saveStatusText.textContent = '✗ ' + (data.message || 'Error saving Hồ sơ. Please try again.');

                saveButton.disabled = false;
            }
        })
        .catch(error => {
            savingIndicator.classList.add('hidden');
            saveStatus.classList.remove('hidden');
            saveStatus.classList.remove('border-green-200', 'bg-green-50', 'text-green-950');
            saveStatus.classList.add('border-red-200', 'bg-red-50', 'text-red-950');

            saveStatusIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>';
            saveStatusIcon.classList.remove('text-green-600');
            saveStatusIcon.classList.add('text-red-600');

            saveStatusText.textContent = '✗ Network error. Please try again.';

            saveButton.disabled = false;
        });
    });
});
</script>


