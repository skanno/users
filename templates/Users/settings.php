<?php
$this->assign('title', '設定');
?>
<?= $this->Html->link('パスワード変更', ['controller' => 'Users', 'action' => 'change-password']) ?><br>
<?= $this->Html->link('退会', ['controller' => 'Users', 'action' => 'withdrawal']) ?>
