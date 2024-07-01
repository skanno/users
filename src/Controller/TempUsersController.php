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
}
