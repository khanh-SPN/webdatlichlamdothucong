<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CompanyInfo $companyInfo
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Company Info'), ['action' => 'edit', $companyInfo->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Company Info'), ['action' => 'delete', $companyInfo->id], ['confirm' => __('Are you sure you want to delete # {0}?', $companyInfo->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Company Infos'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Company Info'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="companyInfos view content">
            <h3><?= h($companyInfo->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($companyInfo->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Email') ?></th>
                    <td><?= h($companyInfo->email) ?></td>
                </tr>
                <tr>
                    <th><?= __('Phone') ?></th>
                    <td><?= h($companyInfo->phone) ?></td>
                </tr>
                <tr>
                    <th><?= __('Address') ?></th>
                    <td><?= h($companyInfo->address) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($companyInfo->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($companyInfo->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($companyInfo->modified) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Description') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($companyInfo->description)); ?>
                </blockquote>
            </div>
        </div>
    </div>
</div>