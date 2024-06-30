<?php
declare(strict_types=1);

namespace App\Controller;

use App\Util\Token;
use Cake\Event\EventInterface;
use Cake\Http\Exception\ForbiddenException;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{
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
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Users->find();
        $users = $this->paginate($query);

        $this->set(compact('users'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, contain: []);
        $this->set(compact('user'));
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
        $result = $this->Authentication->getResult();
        if ($result && $result->isValid()) {
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
                    $this->Mailer->deliver(
                        'パスワード変更のお知らせ',
                        $email,
                        'forget',
                        compact('passwordForgetUser')
                    );
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
            $passwordForgetUsers = $this->fetchTable('PasswordForgetUsers')
                ->findByToken($token->get())
                ->contain(['Users'])
                ->firstOrFail();
            $user = $this->Users->patchEntity(
                $passwordForgetUsers->user,
                $this->request->getData()
            );
            if ($this->Users->save($user)) {
                $this->Flash->success('登録しました。');

                return $this->redirect(['action' => 'login']);
            }
            $this->Flash->error('登録できませんでした。');
        }
    }

    public function logout()
    {
        $result = $this->Authentication->getResult();
        if ($result && $result->isValid()) {
            $this->Authentication->logout();

            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }
    }

    public function home()
    {
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
