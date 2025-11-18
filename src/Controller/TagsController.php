<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Tags Controller
 *
 * @property \App\Model\Table\TagsTable $Tags
 */
class TagsController extends AppController
{
    /**
     * Permissions array
     */
    protected array $permissions = [
        'index' => [],
        'add' => [],
        'edit' => [],
        'delete' => []
    ];

    /**
     * Index method
     */
    public function index()
    {
        $this->paginate = [
            'limit' => 50
        ];

        $tags = $this->paginate($this->Tags->find());
        $this->set(compact('tags'));
    }

    /**
     * Add method
     */
    public function add()
    {
        $tag = $this->Tags->newEmptyEntity();
        
        if ($this->request->is('post')) {
            $tag = $this->Tags->patchEntity($tag, $this->request->getData());
            
            if ($this->Tags->save($tag)) {
                $this->Flash->success(__('The Tag has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Tag could not be saved. Please, try again.'));
        }
        
        $posters = $this->Tags->Posters->find('list')->toArray();
        $this->set(compact('tag', 'posters'));
    }

    /**
     * Edit method
     */
    public function edit($id = null)
    {
        if (!$id && empty($this->request->getData())) {
            $this->Flash->error(__('Invalid Tag'));
            return $this->redirect(['action' => 'index']);
        }

        $tag = $this->Tags->get($id);
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $tag = $this->Tags->patchEntity($tag, $this->request->getData());
            
            if ($this->Tags->save($tag)) {
                $this->Flash->success(__('The Tag has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Tag could not be saved. Please, try again.'));
        }
        
        $posters = $this->Tags->Posters->find('list')->toArray();
        $this->set(compact('tag', 'posters'));
    }

    /**
     * Delete method
     */
    public function delete($id = null)
    {
        if (!$id) {
            $this->Flash->error(__('Invalid id for Tag'));
            return $this->redirect(['action' => 'index']);
        }

        $tag = $this->Tags->get($id);
        if ($this->Tags->delete($tag)) {
            $this->Flash->success(__('Tag deleted'));
        } else {
            $this->Flash->error(__('The tag could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}

