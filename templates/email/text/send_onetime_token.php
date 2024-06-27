<?php
declare(strict_types=1);

use Cake\Core\Configure;
use Cake\I18n\DateTime;
use Cake\Utility\Security;

$expireMinutes = ceil(Configure::read('user.onetime_token.expire', 0) / DateTime::SECONDS_PER_MINUTE);
$registerUrl = $this->Url->build([
    'controller' => 'users',
    'action' => 'add',
    '?' => [
        'token' => $onetime_token,
        'c' => md5(Security::getSalt() . $onetime_token),
    ],
], [
    'fullBase' => true,
    'escape' => false,
]);
?>

ワンタイムトークンの期限は<?= $expireMinutes ?>分以内です。
<?= $registerUrl ?>
