<?php
$this->assign('title', 'ログイン');
?>
<?= $this->Flash->render() ?>
<?= $this->Form->create() ?>
    <?= $this->Form->control('email', ['required' => true]) ?>
    <?= $this->Form->control('password', ['required' => true]) ?>
<?= $this->Form->submit('ログイン'); ?>
<?= $this->Form->end() ?>
<?= $this->Html->link('パスワードを忘れた', ['controller' => 'Users', 'action' => 'forget']) ?><br>
<?= $this->Html->link('ユーザー登録', ['controller' => 'TempUsers', 'action' => 'add']) ?>
