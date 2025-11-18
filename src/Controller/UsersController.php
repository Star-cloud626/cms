<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Mailer\MailerAwareTrait;
use Cake\Routing\Router;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\GroupsTable $Groups
 */
class UsersController extends AppController
{
    use MailerAwareTrait;

    /**
     * Permissions array
     */
    protected array $permissions = [
        'login' => '*',
        'logout' => '*',
        'activate' => '*',
        'register' => '*',
        'password' => ['user', 'client', 'public']
    ];

    /**
     * Initialize method
     */
    public function initialize(): void
    {
        parent::initialize();
        // In CakePHP 5.0, use fetchTable() instead of loadModel()
        $this->Groups = $this->fetchTable('Groups');
        
        // Allow unauthenticated access to login, register, and activate actions
        if ($this->components()->has('Authentication')) {
            $this->Authentication->allowUnauthenticated(['login', 'register', 'activate']);
        }
    }

    /**
     * Before filter
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        // Allow login, logout, register, and activate without authentication
        if ($this->components()->has('Authentication')) {
            $this->Authentication->allowUnauthenticated(['login', 'logout', 'register', 'activate']);
        }
    }

    /**
     * Login method
     */
    public function login()
    {
        $this->request->allowMethod(['get', 'post']);
        
        if (!$this->components()->has('Authentication')) {
            $this->Flash->error('Authentication plugin is not installed. Please run: composer require cakephp/authentication');
            return;
        }
        
        if ($this->request->is('post')) {
            $result = $this->Authentication->getResult();
            if ($result->isValid()) {
                $user = $this->Authentication->getIdentity();
                // Load group information
                $group = $this->Users->Groups->get($user->group_id);
                $user->set('group', $group);
                
                $redirect = $this->request->getQuery('redirect', [
                    'controller' => 'Posters',
                    'action' => 'index'
                ]);
                return $this->redirect($redirect);
            }
            $this->Flash->error('Invalid username or password');
        }
    }

    /**
     * Logout method
     */
    public function logout()
    {
        $this->Flash->success('You are now logged out.');
        if ($this->components()->has('Authentication')) {
            $this->Authentication->logout();
        }
        return $this->redirect(['controller' => 'Posters', 'action' => 'index']);
    }

    /**
     * Index method
     */
    public function index()
    {
        $filters = [];
        $filters['Users.group_id'] = \App\Model\Table\GroupsTable::USER;
        
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            if (!empty($data['User']['group_id'])) {
                $filters['Users.group_id'] = $data['User']['group_id'];
            }
            if (!empty($data['Search']['username'])) {
                $filters['Users.username LIKE'] = "%{$data['Search']['username']}%";
            }
        } else {
            // Set defaults
            $this->set('defaultGroupId', 2);
        }

        $groups = $this->Groups->find('list')->toArray();
        $this->set(compact('groups'));

        $query = $this->Users->find()
            ->contain(['Groups'])
            ->where($filters)
            ->order(['Users.username' => 'ASC']);

        $this->paginate = [
            'limit' => 50,
            'order' => ['Users.username' => 'ASC']
        ];

        $users = $this->paginate($query);
        $this->set(compact('users'));
    }

    /**
     * View method
     */
    public function view($id = null)
    {
        if (!$id) {
            $this->Flash->error(__('Invalid User.'));
            return $this->redirect(['action' => 'index']);
        }
        
        $user = $this->Users->get($id, [
            'contain' => ['Groups', 'Posters', 'Access', 'Comments']
        ]);
        $this->set(compact('user'));
    }

    /**
     * Add method
     */
    public function add()
    {
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            $user->activated = 1;
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The User has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The User could not be saved. Please, try again.'));
        }
        $groups = $this->Groups->find('list')->toArray();
        $this->set(compact('user', 'groups'));
    }

    /**
     * Edit method
     */
    public function edit($id = null)
    {
        if (!$id && empty($this->request->getData())) {
            $this->Flash->error(__('Invalid User'));
            return $this->redirect(['action' => 'index']);
        }

        $user = $this->Users->get($id);
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            
            // Only save the password if it is changed
            if (!empty($data['new_password'])) {
                if ($data['new_password'] == $data['verify']) {
                    $user->password = $data['new_password'];
                } else {
                    $this->Flash->error('Password fields do not match.');
                    $groups = $this->Groups->find('list')->toArray();
                    $this->set(compact('user', 'groups'));
                    return;
                }
            } else {
                // Don't update password if not provided
                unset($data['password']);
            }

            $user = $this->Users->patchEntity($user, $data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The User has been saved.'));
                // Don't redirect automatically - allow user to continue editing
            } else {
                $this->Flash->error(__('The User could not be saved. Please, try again.'));
            }
        } else {
            // Clear password for display
            $user->password = '';
        }

        $groups = $this->Groups->find('list')->toArray();
        $this->set(compact('user', 'groups'));
    }

    /**
     * Delete method
     */
    public function delete($id = null)
    {
        if (!$id) {
            $this->Flash->error(__('Invalid id for User'));
            return $this->redirect(['action' => 'index']);
        }

        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('User deleted'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Register method
     */
    public function register()
    {
        $user = $this->Users->newEmptyEntity();
        
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            
            // Assign to public group
            $data['group_id'] = \App\Model\Table\GroupsTable::PUB;
            
            // Create activation key
            $data['activation_key'] = md5(microtime(true));
            $data['activated'] = 0;

            $user = $this->Users->patchEntity($user, $data);
            if ($this->Users->save($user)) {
                // Send activation email
                try {
                    $this->getMailer('User')->send('activation', [$user, $data['activation_key']]);
                } catch (\Exception $e) {
                    // Log error but don't fail registration
                }

                $this->Flash->success('The account has been created. You will receive an e-mail with activation instructions.');
                return $this->redirect(['controller' => 'Posters', 'action' => 'index']);
            } else {
                $this->Flash->error('The account could not be created. Please, try again.');
                // Clear password fields
                $user->password = '';
            }
        }
        
        $this->set(compact('user'));
    }

    /**
     * Activate method
     */
    public function activate($id = null, $key = null)
    {
        if (!empty($id) && !empty($key)) {
            $user = $this->Users->get($id);
            if ($user && $user->activation_key === $key) {
                $user->activated = 1;
                if ($this->Users->save($user)) {
                    $this->Flash->success('The account has been activated. You may now log in.');
                    return $this->redirect(['action' => 'login']);
                }
            }
        }
        $this->Flash->error('Invalid activation link.');
        return $this->redirect(['controller' => 'Posters', 'action' => 'index']);
    }

    /**
     * Password method
     */
    public function password()
    {
        if (!$this->components()->has('Authentication')) {
            $this->Flash->error('Authentication plugin is not installed.');
            return $this->redirect(['action' => 'login']);
        }
        
        $currentUser = $this->Authentication->getIdentity();
        if (!$currentUser) {
            $this->Flash->error('You must be logged in to change your password.');
            return $this->redirect(['action' => 'login']);
        }

        $user = $this->Users->get($currentUser->id);
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            
            if ($data['password'] != $data['re-type_password']) {
                $this->Flash->error(__('The passwords do not match.'));
            } elseif (strlen($data['password']) < 5) {
                $this->Flash->error(__('The passwords must be at least 5 characters long.'));
            } else {
                $user->password = $data['password'];
                if ($this->Users->save($user)) {
                    $this->Flash->success(__('The data has been saved'));
                    return $this->redirect(['controller' => 'Posters', 'action' => 'index']);
                } else {
                    $this->Flash->error(__('The password could not be saved. Please, try again.'));
                }
            }
        }
        
        $user->password = '';
        $this->set(compact('user'));
    }
}

