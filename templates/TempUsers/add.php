<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\TempUser $tempUser
 */
?>
<div class="row">
    <div class="column">
        <div class="tempUsers form content">
            <?= $this->Form->create($tempUser) ?>
            <fieldset>
                <legend><?= __('Add Temp User') ?></legend>
                <?= $this->Form->control('email') ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
