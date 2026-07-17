<?php
/**
 * @var \App\View\AppView $this
 */
use Cake\Core\Configure;

if (Configure::read('debug')) {
    $this->setLayout('dev_error');
    $this->assign('title', 'Cấm truy cập');
    $this->assign('templateName', 'error403.php');
} else {
    $this->setLayout('landing');
    $this->assign('title', 'Cấm truy cập');
}
?>

<?php if (!Configure::read('debug')) : ?>
<div class="mx-auto max-w-2xl px-3 py-7 md:py-20 text-neutral-800 text-center">
<?php endif; ?>

<div class="flex justify-center">
    <div class="mb-3 font-serif font-semibold tracking-tight text-red-700 text-7xl sm:text-8xl md:text-9xl">
        403
    </div>
</div>

<h2 class="font-serif font-semibold text-ink-900 text-xl sm:text-lg md:text-lg"><?= __d('cake', 'Access denied') ?></h2>
<p class="mt-4 text-neutral-600 leading-relaxed"><?= __d('cake', 'You do not have permission to access this page.') ?></p>

<?php if (!Configure::read('debug')) : ?>
</div>
<?php endif; ?>

