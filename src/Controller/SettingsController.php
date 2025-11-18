<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Settings Controller
 *
 * @property \App\Model\Table\SettingsTable $Settings
 */
class SettingsController extends AppController
{
    /**
     * Permissions array
     */
    protected array $permissions = [
        'index' => [],
        'edit' => [],
        'pdf' => []
    ];

    /**
     * Index method
     */
    public function index()
    {
        $settings = $this->Settings->find('list', [
            'keyField' => 'name',
            'valueField' => 'text'
        ])->toArray();
        
        $user = $this->components()->has('Authentication') ? $this->Authentication->getIdentity() : null;
        
        $this->set(compact('settings', 'user'));
    }

    /**
     * Edit method
     */
    public function edit($name = null)
    {
        $settings = $this->Settings->find('list', [
            'keyField' => 'name',
            'valueField' => 'id'
        ])->toArray();
        
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $name = $data['Setting']['name'] ?? $name;
        }
        
        if (!$name || !array_key_exists($name, $settings)) {
            $this->Flash->error(__('Invalid Field.'));
            return $this->redirect(['action' => 'index']);
        }
        
        $setting = $this->Settings->get($settings[$name]);
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $setting->text = $data['Setting']['text'] ?? $setting->text;
            
            if ($this->Settings->save($setting)) {
                $this->Flash->success(__('The Setting has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Setting could not be saved. Please, try again.'));
        }
        
        $this->set(compact('setting'));
    }

    /**
     * PDF method
     */
    public function pdf()
    {
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            
            if (!empty($data['Setting'])) {
                $error = false;
                $ids = $this->Settings->find('list', [
                    'keyField' => 'name',
                    'valueField' => 'id'
                ])->toArray();
                
                foreach ($data['Setting'] as $name => $text) {
                    if (empty($ids[$name])) {
                        continue;
                    }
                    
                    $entry = $this->Settings->get($ids[$name]);
                    $entry->text = $text;
                    
                    if (!$this->Settings->save($entry)) {
                        $error = true;
                        break;
                    }
                }

                if ($error) {
                    $this->Flash->error(__('The Setting could not be saved. Please, try again.'));
                } else {
                    $this->Flash->success(__('The Settings have been saved.'));
                    return $this->redirect(['action' => 'index']);
                }
            }
        }
        
        $settings = $this->Settings->find('list', [
            'keyField' => 'name',
            'valueField' => 'text'
        ])->toArray();
        
        $this->set(compact('settings'));
    }
}

