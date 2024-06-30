<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

/**
 * TempUsers Controller
 *
 * @property \App\Model\Table\TempUsersTable $TempUsers
 */
class TempUsersController extends AppController
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
        $this->Authentication->addUnauthenticatedActions(['add', 'doneSendMail']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->TempUsers->find();
        $tempUsers = $this->paginate($query);

        $this->set(compact('tempUsers'));
    }

    /**
     * View method
     *
     * @param string|null $id Temp User id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(string $id): void
    {
        $tempUser = $this->TempUsers->get($id, contain: []);
        $this->set(compact('tempUser'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $tempUser = $this->TempUsers->newEmptyEntity();
        if ($this->request->is('post')) {
            $tempUser = $this->TempUsers->patchEntity($tempUser, $this->request->getData());
            if ($this->TempUsers->save($tempUser)) {
                $this->Mailer->deliver(
                    '仮ユーザー登録のお知らせ',
                    $tempUser->email,
                    'send_temp_registration',
                    compact('tempUser')
                );

                $this->Flash->success('メールを送信しました。ご確認ください。');
                $this->request->getSession()->write('tempUser', $tempUser);

                return $this->redirect(['action' => 'doneSendMail']);
            }
            $this->Flash->error('仮ユーザー登録に失敗しました。');
        }
        $this->set(compact('tempUser'));
    }

    /**
     * doneSendMail method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function doneSendMail()
    {
        $tempUser = $this->request->getSession()->read('tempUser');

        $this->set('c', md5($tempUser->onetime_toke . gethostname()));
        $this->set(compact('tempUser'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Temp User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit(string $id)
    {
        $tempUser = $this->TempUsers->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $tempUser = $this->TempUsers->patchEntity($tempUser, $this->request->getData());
            if ($this->TempUsers->save($tempUser)) {
                $this->Flash->success(__('The temp user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The temp user could not be saved. Please, try again.'));
        }
        $this->set(compact('tempUser'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Temp User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete(string $id)
    {
        $this->request->allowMethod(['post', 'delete']);
        $tempUser = $this->TempUsers->get($id);
        if ($this->TempUsers->delete($tempUser)) {
            $this->Flash->success(__('The temp user has been deleted.'));
        } else {
            $this->Flash->error(__('The temp user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
