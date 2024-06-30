<?php
declare(strict_types=1);

use Cake\Core\Configure;
use Cake\I18n\DateTime;

$expireMinutes = ceil(Configure::read('user.token.expire', 0) / DateTime::SECONDS_PER_MINUTE);
$resetPasswordUrl = $this->Url->build([
    'controller' => 'users',
    'action' => 'reset_password',
    '?' => [
        'token' => $passwordForgetUser->token,
        'c' => $passwordForgetUser->check_sum,
    ],
], [
    'fullBase' => true,
    'escape' => false,
]);
?>

下記URLからパスワードを変更してください。
<?= $resetPasswordUrl ?>

期限は<?= $expireMinutes ?>分以内です。
