<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

/**
 * Groups Controller
 *
 * @property \App\Model\Table\GroupsTable $Groups
 */
class GroupsController extends AppController
{
    /**
     * Initialize method
     */
    public function initialize(): void
    {
        parent::initialize();
    }

    /**
     * Before filter
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
    }

    /**
     * Index method
     */
    public function index()
    {
        $this->paginate = [
            'limit' => 50
        ];

        $groups = $this->paginate($this->Groups->find());
        $this->set(compact('groups'));
    }

    /**
     * View method
     */
    public function view($id = null)
    {
        if (!$id) {
            $this->Flash->error(__('Invalid Group.'));
            return $this->redirect(['action' => 'index']);
        }
        
        $group = $this->Groups->get($id, [
            'contain' => ['Users']
        ]);
        
        $this->set(compact('group'));
    }

    /**
     * Add method
     */
    public function add()
    {
        $group = $this->Groups->newEmptyEntity();
        
        if ($this->request->is('post')) {
            $group = $this->Groups->patchEntity($group, $this->request->getData());
            
            if ($this->Groups->save($group)) {
                $this->Flash->success(__('The Group has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Group could not be saved. Please, try again.'));
        }
        
        $this->set(compact('group'));
    }

    /**
     * Edit method
     */
    public function edit($id = null)
    {
        if (!$id && empty($this->request->getData())) {
            $this->Flash->error(__('Invalid Group'));
            return $this->redirect(['action' => 'index']);
        }

        $group = $this->Groups->get($id);
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $group = $this->Groups->patchEntity($group, $this->request->getData());
            
            if ($this->Groups->save($group)) {
                $this->Flash->success(__('The Group has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Group could not be saved. Please, try again.'));
        }
        
        $this->set(compact('group'));
    }

    /**
     * Delete method
     */
    public function delete($id = null)
    {
        if (!$id) {
            $this->Flash->error(__('Invalid id for Group'));
            return $this->redirect(['action' => 'index']);
        }

        $group = $this->Groups->get($id);
        if ($this->Groups->delete($group)) {
            $this->Flash->success(__('Group deleted'));
        } else {
            $this->Flash->error(__('The group could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}

