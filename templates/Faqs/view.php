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
            <?= $this->Html->link(__('Edit Faq'), ['action' => 'edit', $faq->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Faq'), ['action' => 'delete', $faq->id], ['confirm' => __('Are you sure you want to delete # {0}?', $faq->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Faqs'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Faq'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="faqs view content">
            <h3><?= h($faq->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Category') ?></th>
                    <td><?= h($faq->category) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($faq->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Display Order') ?></th>
                    <td><?= $faq->display_order === null ? '' : $this->Number->format($faq->display_order) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($faq->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($faq->modified) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Question') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($faq->question)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Answer') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($faq->answer)); ?>
                </blockquote>
            </div>
        </div>
    </div>
</div>