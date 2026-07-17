<?php
/**
 * @var \App\View\AppView $this
 * @var string $message
 * @var string $url
 */
use Cake\Core\Configure;
use Cake\Error\Debugger;

if (Configure::read('debug')) {
    $this->setLayout('dev_error');
    $this->assign('title', $message);
    $this->assign('templateName', 'error500.php');
    $this->start('file');
    ?>
<?php if ($error instanceof Error) : ?>
    <?php $file = $error->getFile() ?>
    <?php $line = $error->getLine() ?>
    <strong>Error in: </strong>
    <?= $this->Html->link(sprintf('%s, line %s', Debugger::trimPath($file), $line), Debugger::editorUrl($file, $line)); ?>
<?php endif; ?>
<?php
    echo $this->element('auto_table_warning');
    $this->end();
} else {
    $this->setLayout('landing');
    $this->assign('title', 'Đã xảy ra sự cố');
}
?>
<?php if (!Configure::read('debug')) : ?>
<div class="mx-auto max-w-2xl px-3 py-7 md:py-20 text-neutral-800 text-center">
<?php endif; ?>

<?php if (!Configure::read('debug')) : ?>
<div class="flex justify-center">
    <div class="mb-3 font-serif font-semibold tracking-tight text-amber-700 text-7xl sm:text-8xl md:text-9xl">
        500
    </div>
</div>
<?php endif; ?>

<h2 class="font-serif font-semibold text-ink-900 text-xl sm:text-lg md:text-lg"><?= __d('cake', 'An Internal Error Has Occurred.') ?></h2>
<p class="mt-4 text-neutral-600 leading-relaxed">
    <?= __d('cake', 'Please try again later.') ?>
</p>
<?php if (!Configure::read('debug')) : ?>
</div>
<?php endif; ?>

