<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\TempUser $tempUser
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Temp User'), ['action' => 'edit', $tempUser->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Temp User'), ['action' => 'delete', $tempUser->id], ['confirm' => __('Are you sure you want to delete # {0}?', $tempUser->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Temp Users'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Temp User'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="tempUsers view content">
            <h3><?= h($tempUser->email) ?></h3>
            <table>
                <tr>
                    <th><?= __('Email') ?></th>
                    <td><?= h($tempUser->email) ?></td>
                </tr>
                <tr>
                    <th><?= __('Onetime Token') ?></th>
                    <td><?= h($tempUser->onetime_token) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($tempUser->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($tempUser->created) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
