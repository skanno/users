<?php
declare(strict_types=1);

namespace App\Controller;

use App\Util\Hash;
use App\Util\Token;
use Cake\Event\EventInterface;
use Cake\Http\Cookie\Cookie;
use Cake\Http\Exception\ForbiddenException;
use Cake\Mailer\MailerAwareTrait;
use DateTime;

const AUTO_LOGIN_KEY_COOKIE_NAME = 'auto_login_key';

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{
    use MailerAwareTrait;

    /**
     * Undocumented function
     *
     * @param \Cake\Event\EventInterface $event
     * @return void
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->Authentication->addUnauthenticatedActions([
            'add',
            'login',
            'forget',
            'resetPassword',
        ]);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $token = new Token($this->getRequest()->getQuery('token'));
        $checkSum = $this->getRequest()->getQuery('c');
        if (!$token->checkToken($checkSum)) {
            throw new ForbiddenException('URLが改竄されています。');
        }

        $tempUser = $this->getRequest()->getSession()->read(md5($token->get()));
        if (empty($tempUser)) {
            $tempUser = $this->fetchTable('TempUsers')->getTempUser($token->get());
            $this->getRequest()->getSession()->write(md5($token->get()), $tempUser);
        }

        if (empty($tempUser)) {
            $this->Flash->error('仮ユーザーに登録されていません。');
            $this->render('add_error');
        }

        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $userData = $this->request->getData();
            $userData['email'] = $this->request->getSession()->read('tempUser')->email;
            $user = $this->Users->patchEntity($user, $userData);
            if ($this->Users->save($user)) {
                $this->Flash->success('登録しました。');

                return $this->redirect(['action' => 'login']);
            }
            $this->Flash->error('登録できませんでした。');
        }
        $this->set(compact('tempUser'));
        $this->set(compact('user'));
    }

    public function login()
    {
        $this->request->allowMethod(['get', 'post']);
        $autoLoginKey = $this->request->getCookie(AUTO_LOGIN_KEY_COOKIE_NAME);
        if ($autoLoginKey != null) {
            $user = $this->Users->findByAutoLoginKey($autoLoginKey)->first();
            if ($user != null) {
                $this->Authentication->setIdentity($user);

                return $this->redirect(['action' => 'home']);
            }
        }

        $result = $this->Authentication->getResult();
        if ($result && $result->isValid()) {
            $user = $this->Authentication->getIdentity();
            $user->auto_login_key = Hash::generate();
            $this->Users->save($user);
            $this->response = $this->response->withCookie(Cookie::create(
                AUTO_LOGIN_KEY_COOKIE_NAME,
                $user->auto_login_key,
                ['expires' => new DateTime('+1 month')]
            ));

            $redirect = $this->request->getQuery('redirect', [
                'controller' => 'Users',
                'action' => 'home',
            ]);

            return $this->redirect($redirect);
        }
        if ($this->request->is('post') && !$result->isValid()) {
            $this->Flash->error('ログインに失敗しました。');
        }
    }

    public function forget()
    {
        $this->request->allowMethod(['get', 'post']);
        if ($this->request->isPost()) {
            $email = $this->request->getData('email');
            if ($this->Users->hasUser($email)) {
                $passwordForgetUsers = $this->fetchTable('PasswordForgetUsers');
                $passwordForgetUser = $passwordForgetUsers->newEmptyEntity();
                $passwordForgetUser->user_id = $this->Users->findByEmail($email)->firstOrFail()->id;

                if (!$passwordForgetUsers->save($passwordForgetUser)->hasErrors()) {
                    $this->getMailer('Users')->send('forgetPassword', [$email, $passwordForgetUser]);
                    $this->Flash->success('メールを送信しました。ご確認ください。');
                }
            } else {
                $this->Flash->error('登録されているメールアドレスではありません。');
            }
        }
    }

    public function resetPassword()
    {
        $token = new Token($this->getRequest()->getQuery('token'));
        $checkSum = $this->getRequest()->getQuery('c');
        if (!$token->checkToken($checkSum)) {
            throw new ForbiddenException('URLが改竄されています。');
        }

        $this->request->allowMethod(['get', 'post']);
        if ($this->request->isPost()) {
            $passwordForgetUser = $this->fetchTable('PasswordForgetUsers')
                ->getPasswordForgetUser($token->get());

            if (empty($passwordForgetUser)) {
                $this->Flash->error('有効期限切れです。');

                return $this->redirect(['action' => 'forget']);
            }

            $user = $this->Users->patchEntity(
                $passwordForgetUser->user,
                $this->request->getData()
            );
            if ($this->Users->save($user)) {
                $this->Flash->success('登録しました。');

                return $this->redirect(['action' => 'login']);
            }
            $this->Flash->error('登録できませんでした。');
        }
    }

    public function changePassword()
    {
        $this->request->allowMethod(['get', 'post']);
        if ($this->request->isPost()) {
            $user = $this->Authentication->getIdentity();
            if ($user->checkPassword($this->request->getData('password_current'))) {
                $user = $this->Users->patchEntity(
                    $user,
                    $this->request->getData()
                );
                if ($this->Users->save($user)) {
                    $this->Authentication->setIdentity($user);
                    $this->Flash->success('パスワードを変更しました。');

                    return $this->redirect(['action' => 'home']);
                }
            } else {
                $this->Flash->error('現在のパスワードが違います。');
            }
            $this->Flash->error('登録できませんでした。');
        }
    }

    public function logout()
    {
        $result = $this->Authentication->getResult();
        if ($result && $result->isValid()) {
            $user = $this->Authentication->getIdentity();
            $user->auto_login_key = null;
            $this->Users->save($user);
            $this->response = $this->response->withExpiredCookie(new Cookie(AUTO_LOGIN_KEY_COOKIE_NAME));
            $this->Authentication->logout();

            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }
    }

    public function home()
    {
    }

    public function withdrawal()
    {
        $this->request->allowMethod(['get', 'post']);
        $user = $this->Authentication->getIdentity();
        if ($this->request->isPost()) {
            if ($this->Users->delete($user)) {
                $this->Authentication->logout();
                $this->response = $this->response->withExpiredCookie(new Cookie(AUTO_LOGIN_KEY_COOKIE_NAME));
                $this->Flash->success('退会しました。');

                return $this->redirect(['action' => 'login']);
            } else {
                $this->Flash->error('退会に失敗しました。');
            }
        }
    }
}
