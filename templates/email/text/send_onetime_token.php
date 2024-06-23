<?php
declare(strict_types=1);

use Cake\Core\Configure;
use Cake\I18n\DateTime;
use Cake\Routing\Router;

$expireMinutes = ceil(Configure::read('user.onetime_token.expire', 0) / DateTime::SECONDS_PER_MINUTE);
$registerUrl = Router::fullBaseUrl() . $this->Url->build([
    'controller' => 'users',
    'action' => 'add',
    '?' => ['token' => $onetime_token],
]);
?>

ワンタイムトークンの期限は<?= $expireMinutes ?>分以内です。
<?= $registerUrl ?>
