<?php
$this->assign('title', 'パスワードを忘れた');
?>
<?= $this->Form->create() ?>
<?php
    echo $this->Form->control('email', ['label' => 'メールアドレス']);
?>
<?= $this->Form->button('送信') ?>
<?= $this->Form->end() ?>
