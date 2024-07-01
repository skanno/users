<?php
declare(strict_types=1);

namespace App\Mailer;

use Cake\Mailer\Mailer;

/**
 * Users mailer.
 */
class UsersMailer extends Mailer
{
    /**
     * Mailer's name.
     *
     * @var string
     */
    public static string $name = 'Users';

    public function tempUserRegistration($tempUser)
    {
        $this
            ->setTo($tempUser->email)
            ->setSubject('仮ユーザー登録のお知らせ')
            ->setViewVars(compact('tempUser'));
    }

    public function forgetPassword($email, $passwordForgetUser)
    {
        $this
            ->setTo($email)
            ->setSubject('パスワード変更のお知らせ')
            ->setViewVars(compact('passwordForgetUser'));
    }
}
