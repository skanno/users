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
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $tempUser->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $tempUser->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Temp Users'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="tempUsers form content">
            <?= $this->Form->create($tempUser) ?>
            <fieldset>
                <legend><?= __('Edit Temp User') ?></legend>
                <?php
                    echo $this->Form->control('email');
                    echo $this->Form->control('onetime_token');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
