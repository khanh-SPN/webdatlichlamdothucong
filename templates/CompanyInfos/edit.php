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
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $companyInfo->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $companyInfo->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Company Infos'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="companyInfos form content">
            <?= $this->Form->create($companyInfo) ?>
            <fieldset>
                <legend><?= __('Edit Company Info') ?></legend>
                <?php
                    echo $this->Form->control('name');
                    echo $this->Form->control('email');
                    echo $this->Form->control('phone');
                    echo $this->Form->control('address');
                    echo $this->Form->control('description');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
