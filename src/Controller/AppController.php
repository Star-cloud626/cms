<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\EventInterface;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/5/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    /**
     * Permissions array - define which actions are allowed for which groups
     * '*' means public access
     * Empty array means only authenticated users
     * Array of group names means only those groups
     */
    protected array $permissions = [];

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Flash');
        
        // Load Authentication component if plugin is installed
        try {
            if (class_exists('Authentication\Controller\Component\AuthenticationComponent')) {
                $this->loadComponent('Authentication.Authentication');
            }
        } catch (\Exception $e) {
            // Authentication plugin not available - continue without it
        }
        
        // Load Authorization component if plugin is installed
        try {
            if (class_exists('Authorization\Controller\Component\AuthorizationComponent')) {
                $this->loadComponent('Authorization.Authorization');
            }
        } catch (\Exception $e) {
            // Authorization plugin not available - continue without it
        }
        
        // RequestHandler component was removed in CakePHP 4.0+
        // Use $this->request->is('ajax') or $this->request->is('json') directly instead

        // Define IMAGE_PATH constant if not already defined
        if (!defined('IMAGE_PATH')) {
            define('IMAGE_PATH', WWW_ROOT . 'img' . DS);
        }
        if (!defined('POSTER_IMAGE_DIR')) {
            define('POSTER_IMAGE_DIR', 'detail/');
        }
    }

    /**
     * Before filter callback
     *
     * @param \Cake\Event\EventInterface $event Event
     * @return \Cake\Http\Response|null|void
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        // Don't cache dynamic content
        // In CakePHP 5.0, withNoCache() was removed - use withHeader() instead
        $this->response = $this->response
            ->withHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->withHeader('Pragma', 'no-cache')
            ->withHeader('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');

        // Handle permissions
        $action = $this->request->getParam('action');
        $user = null;
        
        // Get user if Authentication component is loaded
        if ($this->components()->has('Authentication')) {
            $user = $this->Authentication->getIdentity();
        }
        
        if (!empty($this->permissions[$action])) {
            if ($this->permissions[$action] === '*') {
                // Public access - no authorization needed
                if ($this->components()->has('Authorization')) {
                    $this->Authorization->skipAuthorization();
                }
            } elseif (is_array($this->permissions[$action])) {
                // Check if user's group is in allowed groups
                if ($user) {
                    $group = $user->group ?? null;
                    $groupName = $group ? $group->name : null;
                    if (!in_array($groupName, $this->permissions[$action])) {
                        $this->Flash->error('You do not have permission to access this page.');
                        return $this->redirect(['controller' => 'Posters', 'action' => 'index']);
                    }
                } else {
                    $this->Flash->error('You must be logged in to access this page.');
                    return $this->redirect(['controller' => 'Users', 'action' => 'login']);
                }
            }
        }

        // Check if user is activated (already checked in findAuth, but double-check)
        if ($user && !($user->activated ?? false)) {
            $this->Flash->error('Login failed. Make sure you have typed in your username and password correctly and that you have activated your account.');
            if ($this->components()->has('Authentication')) {
                $this->Authentication->logout();
            }
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        // Set user data for views
        $this->set('auth', $user);
    }

    /**
     * Check if user is authorized
     *
     * @param \App\Model\Entity\User|null $user User entity
     * @return bool
     */
    public function isAuthorized($user = null)
    {
        // Admins have access to everything by default
        if ($user && ($user->group->name ?? null) === 'administrator') {
            return true;
        }

        $action = $this->request->getParam('action');
        if (!empty($this->permissions[$action])) {
            if ($this->permissions[$action] === '*') {
                return true;
            }
            if (is_array($this->permissions[$action])) {
                $groupName = $user->group->name ?? null;
                return in_array($groupName, $this->permissions[$action]);
            }
        }

        return false;
    }
}
