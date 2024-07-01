<?php
$this->assign('title', 'ユーザー仮登録');
?>
<?= $this->Form->create($tempUser) ?>
    メールアドレスを入力してください。
    <?= $this->Form->control('email', ['label' => false]) ?>
    <?= $this->Form->button('送信') ?>
<?= $this->Form->end() ?>
<?= $this->Html->link('ログイン', ['controller' => 'Users', 'action' => 'login']) ?>
