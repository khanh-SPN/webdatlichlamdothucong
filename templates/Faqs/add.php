<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Faq $faq
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Faqs'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="faqs form content">
            <?= $this->Form->create($faq) ?>
            <fieldset>
                <legend><?= __('Add Faq') ?></legend>
                <?php
                    echo $this->Form->control('question');
                    echo $this->Form->control('answer');
                    echo $this->Form->control('category');
                    echo $this->Form->control('display_order');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
