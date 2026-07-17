<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\CompanyInfo> $companyInfos
 */
?>
<div class="companyInfos index content">
    <?= $this->Html->link(__('New Company Info'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Company Infos') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th><?= $this->Paginator->sort('email') ?></th>
                    <th><?= $this->Paginator->sort('phone') ?></th>
                    <th><?= $this->Paginator->sort('address') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($companyInfos as $companyInfo): ?>
                <tr>
                    <td><?= $this->Number->format($companyInfo->id) ?></td>
                    <td><?= h($companyInfo->name) ?></td>
                    <td><?= h($companyInfo->email) ?></td>
                    <td><?= h($companyInfo->phone) ?></td>
                    <td><?= h($companyInfo->address) ?></td>
                    <td><?= h($companyInfo->created) ?></td>
                    <td><?= h($companyInfo->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $companyInfo->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $companyInfo->id]) ?>
                        <?= $this->Form->postLink(
                            __('Delete'),
                            ['action' => 'delete', $companyInfo->id],
                            [
                                'method' => 'delete',
                                'confirm' => __('Are you sure you want to delete # {0}?', $companyInfo->id),
                            ]
                        ) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>