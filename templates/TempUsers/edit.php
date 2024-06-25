<?= $this->Form->create($tempUser) ?>
    <?php
        echo $this->Form->control('email');
        echo $this->Form->control('onetime_token');
    ?>
    <?= $this->Form->button(__('Submit')) ?>
<?= $this->Form->end() ?>
