<?php
$this->assign('title', '退会');
?>
<?= $this->Form->create() ?>
<div>退会します。よろしいですか？</div>
<?= $this->Form->button('退会') ?>
<?= $this->Form->end() ?>
