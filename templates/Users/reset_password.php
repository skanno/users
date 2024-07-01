<?php
$this->assign('title', 'パスワード再設定');
?>
<?= $this->Form->create() ?>
<?php
    echo $this->Form->control('password', ['label' => 'パスワード']);
    echo $this->Form->control('password_confirm', ['type' => 'password', 'label' => 'パスワード(確認)']);
?>
<?= $this->Form->button('登録') ?>
<?= $this->Form->end() ?>
