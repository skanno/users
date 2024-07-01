<?php
$this->assign('title', 'ユーザー登録');
?><?= $this->Form->create($user) ?>
<?php
    echo $this->Form->control('password', ['label' => 'パスワード']);
    echo $this->Form->control('password_confirm', ['type' => 'password', 'label' => 'パスワード(確認)']);
?>
<?= $this->Form->button('登録') ?>
<?= $this->Form->end() ?>
