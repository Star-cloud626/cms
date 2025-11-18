<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

/**
 * Clients Controller
 *
 * @property \App\Model\Table\ClientsTable $Clients
 */
class ClientsController extends AppController
{
    /**
     * Permissions array
     */
    protected array $permissions = [
        'add' => [],
        'delete' => [],
        'edit' => [],
        'index' => [],
        'view' => []
    ];

    /**
     * Initialize method
     */
    public function initialize(): void
    {
        parent::initialize();
    }

    /**
     * Index method
     */
    public function index()
    {
        $conditions = [];
        
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            if (!empty($data['Search']['username'])) {
                $conditions['Clients.username LIKE'] = "%{$data['Search']['username']}%";
            }
        }

        $this->paginate = [
            'limit' => 50,
            'order' => ['Clients.username' => 'ASC']
        ];

        $query = $this->Clients->find()
            ->where(['Clients.group_id' => \App\Model\Table\GroupsTable::CLIENT])
            ->where($conditions);

        $clients = $this->paginate($query);
        $this->set(compact('clients'));
    }

    /**
     * View method
     */
    public function view($id = null)
    {
        if (!$id) {
            $this->Flash->error(__('Invalid Client.'));
            return $this->redirect(['action' => 'index']);
        }
        
        $client = $this->Clients->get($id, [
            'contain' => ['Groups', 'Posters']
        ]);
        
        $this->set(compact('client'));
    }

    /**
     * Add method
     */
    public function add()
    {
        $client = $this->Clients->newEmptyEntity();
        
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            
            // Set group id to client
            $data['group_id'] = \App\Model\Table\GroupsTable::CLIENT;
            
            // Set activated since this account is created by someone else
            $data['activated'] = 1;

            $client = $this->Clients->patchEntity($client, $data);
            
            if ($this->Clients->save($client)) {
                $this->Flash->success(__('The Client has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Client could not be saved. Please, try again.'));
        }

        $this->set(compact('client'));
    }

    /**
     * Edit method
     */
    public function edit($id = null)
    {
        if (!$id && empty($this->request->getData())) {
            $this->Flash->error(__('Invalid Client'));
            return $this->redirect(['action' => 'index']);
        }

        $client = $this->Clients->get($id);
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            
            // Do not save empty password
            if (empty($data['password'])) {
                unset($data['password']);
            }

            $client = $this->Clients->patchEntity($client, $data);
            
            if ($this->Clients->save($client)) {
                $this->Flash->success(__('The Client has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Client could not be saved. Please, try again.'));
        }

        // Clear password for display
        $client->password = '';
        $this->set(compact('client'));
    }

    /**
     * Delete method
     */
    public function delete($id = null)
    {
        if (!$id) {
            $this->Flash->error(__('Invalid id for Client'));
            return $this->redirect(['action' => 'index']);
        }

        $client = $this->Clients->get($id);
        if ($this->Clients->delete($client)) {
            $this->Flash->success(__('Client deleted'));
        } else {
            $this->Flash->error(__('The client could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}

