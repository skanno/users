<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * TempUsers Controller
 *
 * @property \App\Model\Table\TempUsersTable $TempUsers
 */
class TempUsersController extends AppController
{
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
                    'ワンタイムトークンの送付',
                    $tempUser->email,
                    'send_onetime_token',
                    ['onetime_token' => $tempUser->onetime_token]
                );

                $this->Flash->success(__('The temp user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The temp user could not be saved. Please, try again.'));
        }
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
