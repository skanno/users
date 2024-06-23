<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\TempUser> $tempUsers
 */
?>
<div class="tempUsers index content">
    <?= $this->Html->link(__('New Temp User'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Temp Users') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('email') ?></th>
                    <th><?= $this->Paginator->sort('onetime_token') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tempUsers as $tempUser): ?>
                <tr>
                    <td><?= $this->Number->format($tempUser->id) ?></td>
                    <td><?= h($tempUser->email) ?></td>
                    <td><?= h($tempUser->onetime_token) ?></td>
                    <td><?= h($tempUser->created) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $tempUser->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $tempUser->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $tempUser->id], ['confirm' => __('Are you sure you want to delete # {0}?', $tempUser->id)]) ?>
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
