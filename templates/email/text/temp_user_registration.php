<?php
declare(strict_types=1);

use Cake\Core\Configure;
use Cake\I18n\DateTime;

$expireMinutes = ceil(Configure::read('user.token.expire', 0) / DateTime::SECONDS_PER_MINUTE);
$registerUrl = $this->Url->build([
    'controller' => 'users',
    'action' => 'add',
    '?' => [
        'token' => $tempUser->token,
        'c' => $tempUser->check_sum,
    ],
], [
    'fullBase' => true,
    'escape' => false,
]);
?>

下記URLから会員登録を続けてください。
<?= $registerUrl ?>

期限は<?= $expireMinutes ?>分以内です。
