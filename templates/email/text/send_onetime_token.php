<?php
use Cake\Routing\Router;
?>

ワンタイムトークンの期限は10分以内です。
<?= Router::fullBaseUrl() ?><?= $this->Url->build([
    'controller' => 'users',
    'action' => 'add',
    '?' => ['token' => $onetime_token],
]) ?>
