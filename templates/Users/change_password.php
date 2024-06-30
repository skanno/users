<?= $this->Form->create() ?>
<?php
    echo $this->Form->control('password_current', ['type' => 'password', 'label' => '現在のパスワード']);
    echo $this->Form->control('password', ['label' => '新しいパスワード']);
    echo $this->Form->control('password_confirm', ['type' => 'password', 'label' => '新しいパスワード(確認)']);
?>
<?= $this->Form->button('登録') ?>
<?= $this->Form->end() ?>
