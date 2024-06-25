<?= $this->Form->create($user) ?>
<?= $tempUser->email ?>
<?php
    echo $this->Form->control('password');
    echo $this->Form->control('password_confirm');
    echo $this->Form->control('onetime_token', ['value' => $tempUser->onetime_token]);
?>
<?= $this->Form->button(__('Submit')) ?>
<?= $this->Form->end() ?>
