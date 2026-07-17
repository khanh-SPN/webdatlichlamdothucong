<?php
/**
 * @var \App\View\AppView $this
 * @var string $message
 * @var string $url
 */
use Cake\Core\Configure;

if (Configure::read('debug')) {
    $this->setLayout('dev_error');
    $this->assign('title', $message);
    $this->assign('templateName', 'error400.php');
    $this->start('file');
    echo $this->element('auto_table_warning');
    $this->end();
} else {
    $this->setLayout('landing');
    $this->assign('title', 'Trang không tìm thấy');
}
?>
<?php if (!Configure::read('debug')) : ?>
<div class="mx-auto max-w-2xl px-3 py-7 md:py-20 text-neutral-800 text-center">
<?php endif; ?>

<?php if (!Configure::read('debug')) : ?>
<div class="mb-3 font-serif font-semibold tracking-tight text-neutral-900 text-7xl sm:text-8xl md:text-9xl">
    404
</div>
<?php endif; ?>

<h2 class="font-serif font-semibold text-ink-900 text-xl sm:text-lg md:text-lg"><?= Configure::read('debug') ? h($message) : __d('cake', 'Page not found') ?></h2>
<p class="mt-4 text-neutral-600 leading-relaxed">
    <span class="font-semibold text-ink-900"><?= __d('cake', 'Error') ?>: </span>
    <?= __d('cake', 'The requested address {0} was not found on this server.', "<strong class=\"text-ink-900\">'{$url}'</strong>") ?>
</p>
<?php if (!Configure::read('debug')) : ?>
</div>
<?php endif; ?>

