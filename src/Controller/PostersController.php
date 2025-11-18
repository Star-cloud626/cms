<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\ORM\Query\SelectQuery;

/**
 * Posters Controller
 *
 * @property \App\Model\Table\PostersTable $Posters
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\ClientsTable $Clients
 * @property \App\Model\Table\TagsTable $Tags
 * @property \App\Model\Table\AccessTable $Access
 * @property \App\Model\Table\CommentsTable $Comments
 */
class PostersController extends AppController
{
    /**
     * Permissions array
     */
    protected array $permissions = [
        'index' => '*',
        'view' => '*',
        'detail' => [],
        'add' => [],
        'edit' => [],
        'delete' => [],
        'savePositions' => [],
        'pdf' => ['user', 'client'],
        'notify' => [],
        'formatted' => '*'
    ];

    /**
     * Initialize method
     */
    public function initialize(): void
    {
        parent::initialize();
        // In CakePHP 5.0, use fetchTable() instead of loadModel()
        $this->Users = $this->fetchTable('Users');
        $this->Tags = $this->fetchTable('Tags');
        $this->Access = $this->fetchTable('Access');
        $this->Comments = $this->fetchTable('Comments');
        $this->Clients = $this->fetchTable('Clients');
        
        // Allow unauthenticated access to public actions
        if ($this->components()->has('Authentication')) {
            $this->Authentication->allowUnauthenticated(['index', 'view', 'formatted']);
        }
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
        $user = null;
        try {
            if ($this->components()->has('Authentication')) {
                $user = $this->Authentication->getIdentity();
            }
        } catch (\Exception $e) {
            // Authentication not available
        }
        $filters = [];
        $searchData = [];

        // Handle search form submission
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            if (!empty($data['Search'])) {
                $searchData = $data['Search'];
                $this->request->getSession()->write('Search', $searchData);
            }
        } else {
            // Get search data from session or query params
            $searchData = $this->request->getSession()->read('Search') ?? [];
            if (!empty($this->request->getQuery('search'))) {
                $searchData = array_merge($searchData, $this->request->getQuery('search'));
            }
        }

        // Apply search filters
        if (!empty($searchData['value']) && $searchData['value'] !== 'Enter Search Term') {
            if (!empty($searchData['field'])) {
                if ($searchData['field'] === 'Client.client') {
                    $filters[] = function ($exp, $q) use ($searchData) {
                        return $exp->or([
                            'Clients.username LIKE' => "%{$searchData['value']}%",
                            'Clients.fullname LIKE' => "%{$searchData['value']}%"
                        ]);
                    };
                } elseif (strpos($searchData['field'], ' or ') !== false) {
                    $pieces = explode(' or ', $searchData['field']);
                    $filters[] = function ($exp, $q) use ($pieces, $searchData) {
                        $orConditions = [];
                        foreach ($pieces as $piece) {
                            $orConditions[] = ["{$piece} LIKE" => "%{$searchData['value']}%"];
                        }
                        return $exp->or($orConditions);
                    };
                } else {
                    $filters["Posters.{$searchData['field']} LIKE"] = "%{$searchData['value']}%";
                }
            }
        }

        if (!empty($searchData['authenticity'])) {
            $filters['Posters.authenticity'] = $searchData['authenticity'];
        }

        if (!empty($searchData['images'])) {
            if ($searchData['images'] === 'no') {
                $filters['Posters.images_count'] = 0;
            } elseif ($searchData['images'] === 'yes') {
                $filters['Posters.images_count >'] = 0;
            }
        }

        // Apply visibility filters based on user group
        if (empty($user)) {
            $filters['Posters.public_viewable'] = 1;
        } elseif (($user->group_id ?? null) == 3) { // Client
            // Clients can only see their own posters
            $filters['Clients.id'] = $user->id;
            $filters['Posters.client_viewable'] = 1;
        } elseif (($user->group_id ?? null) == 4) { // Public user
            $filters[] = function ($exp, $q) {
                return $exp->or([
                    'Posters.public_viewable' => 1,
                    'Access.comment' => 1
                ]);
            };
        }
        // Admins and regular users can see all

        // Build query with joins
        $query = $this->Posters->find()
            ->contain(['Users', 'Images', 'Tags']);

        // Add client join for admins and users
        if (!empty($user) && in_array($user->group_id ?? null, [1, 2])) {
            // Join through posters_clients and users tables
            $query->leftJoinWith('Clients');
        } elseif (!empty($user) && ($user->group_id ?? null) == 4) {
            $query->leftJoinWith('Access', function ($q) use ($user) {
                return $q->where(['Access.user_id' => $user->id]);
            });
        }

        // Apply filters
        if (!empty($filters)) {
            $query->where($filters);
        }

        // Apply sorting
        $sort = $this->request->getQuery('sort', 'created');
        $direction = $this->request->getQuery('direction', 'desc');
        
        // Handle sort selector format (field:direction)
        if (!empty($searchData['sort_selector'])) {
            list($sort, $direction) = explode(':', $searchData['sort_selector']);
        } elseif (!empty($searchData['sort'])) {
            $sort = $searchData['sort'];
            $direction = $searchData['direction'] ?? 'desc';
        }

        $query->order([$sort => $direction]);

        // Paginate
        $this->paginate = [
            'limit' => 50,
            'order' => [$sort => $direction]
        ];

        $posters = $this->paginate($query);

        // Sort options for view
        $sortableColumns = ['id', 'title', 'authenticity', 'created', 'modified'];
        if (!empty($user) && in_array($user->group_id ?? null, [1, 2])) {
            $sortableColumns[] = 'client';
        }

        $sortOptions = [];
        foreach ($sortableColumns as $column) {
            $sortOptions["{$column}:asc"] = ucwords($column) . ' Ascending';
            $sortOptions["{$column}:desc"] = ucwords($column) . ' Descending';
        }

        $this->set(compact('posters', 'sortOptions', 'searchData'));
        $this->set('group', $user ? ($user->group_id ?? \App\Model\Table\GroupsTable::PUB) : \App\Model\Table\GroupsTable::PUB);
    }

    /**
     * View method
     */
    public function view($id = null)
    {
        if (!$id) {
            $this->Flash->error(__('Invalid Poster.'));
            return $this->redirect(['action' => 'index']);
        }

        $user = $this->components()->has('Authentication') ? $this->Authentication->getIdentity() : null;
        
        $poster = $this->Posters->get($id, [
            'contain' => ['Users', 'Images', 'Comments' => ['Users'], 'Tags', 'Clients']
        ]);

        if (empty($poster)) {
            $this->Flash->error(__('Invalid Poster.'));
            return $this->redirect(['action' => 'index']);
        }

        // Update view count
        $poster->views = ($poster->views ?? 0) + 1;
        $this->Posters->save($poster);

        // Load access if user is logged in
        if ($user) {
            $access = $this->Access->find()
                ->where(['poster_id' => $id, 'user_id' => $user->id])
                ->first();
            $poster->set('access', $access);
        }

        // Load comments
        $comments = $this->Comments->find()
            ->contain(['Users'])
            ->where(['poster_id' => $id])
            ->order(['timestamp' => 'DESC'])
            ->toArray();

        $this->set(compact('poster', 'comments'));
    }

    /**
     * Detail method (admin view)
     */
    public function detail($id = null)
    {
        if (!$id) {
            $this->Flash->error(__('Invalid Poster.'));
            return $this->redirect(['action' => 'index']);
        }

        $poster = $this->Posters->get($id, [
            'contain' => ['Users', 'Images', 'Comments', 'Tags', 'Clients', 'Access']
        ]);

        if (empty($poster)) {
            $this->Flash->error(__('Invalid Poster.'));
            return $this->redirect(['action' => 'index']);
        }

        $this->set(compact('poster'));
    }

    /**
     * Formatted method (formatted view for printing)
     */
    public function formatted($id = null)
    {
        if (!$id) {
            $this->Flash->error(__('Invalid Poster.'));
            return $this->redirect(['action' => 'index']);
        }

        $user = $this->components()->has('Authentication') ? $this->Authentication->getIdentity() : null;
        $poster = $this->Posters->get($id, [
            'contain' => ['Users', 'Images', 'Tags', 'Clients']
        ]);

        if (empty($poster)) {
            $this->Flash->error(__('Invalid Poster.'));
            return $this->redirect(['action' => 'index']);
        }

        // Check permissions
        $viewable = false;
        if (empty($user)) {
            $viewable = $poster->public_viewable ?? false;
        } elseif (($user->group_id ?? null) >= 3) {
            // Check if client owns this poster
            if (!empty($poster->clients)) {
                foreach ($poster->clients as $client) {
                    if ($client->id == $user->id) {
                        $viewable = true;
                        break;
                    }
                }
            }
        } else {
            // Admin or user
            $viewable = true;
        }

        if (!$viewable) {
            if (empty($user)) {
                $this->Flash->error(__('You must login to view that entry.'));
            } else {
                $this->Flash->error(__('You are not authorized to view that entry.'));
            }
            $this->request->getSession()->write('Poster.loginRedirect', [
                'controller' => 'Posters',
                'action' => 'formatted',
                $id
            ]);
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        // Update view count
        $poster->views = ($poster->views ?? 0) + 1;
        $this->Posters->save($poster);

        $this->viewBuilder()->setLayout('poster_form');
        $this->set(compact('poster'));
    }

    /**
     * Add method
     */
    public function add()
    {
        $poster = $this->Posters->newEmptyEntity();
        
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            
            // Set user_id from authenticated user
            $user = $this->components()->has('Authentication') ? $this->Authentication->getIdentity() : null;
            if ($user) {
                $data['user_id'] = $user->id;
            }

            $poster = $this->Posters->patchEntity($poster, $data, [
                'associated' => ['Images', 'Tags', 'Clients']
            ]);

            if ($this->Posters->save($poster)) {
                $this->Flash->success(__('The poster has been saved.'));
                return $this->redirect(['action' => 'view', $poster->id]);
            }
            $this->Flash->error(__('The poster could not be saved. Please, try again.'));
        }

        $users = $this->Users->find('list')->toArray();
        $tags = $this->Tags->find('list')->toArray();
        $clients = $this->Clients->find('list')
            ->where(['Clients.group_id' => \App\Model\Table\GroupsTable::CLIENT])
            ->toArray();

        $this->set(compact('poster', 'users', 'tags', 'clients'));
    }

    /**
     * Edit method
     */
    public function edit($id = null)
    {
        if (!$id && empty($this->request->getData())) {
            $this->Flash->error(__('Invalid Poster'));
            return $this->redirect(['action' => 'index']);
        }

        $poster = $this->Posters->get($id, [
            'contain' => ['Images', 'Tags', 'Clients']
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $poster = $this->Posters->patchEntity($poster, $this->request->getData(), [
                'associated' => ['Images', 'Tags', 'Clients']
            ]);

            if ($this->Posters->save($poster)) {
                $this->Flash->success(__('The poster has been saved.'));
                return $this->redirect(['action' => 'view', $id]);
            }
            $this->Flash->error(__('The poster could not be saved. Please, try again.'));
        }

        $users = $this->Users->find('list')->toArray();
        $tags = $this->Tags->find('list')->toArray();
        $clients = $this->Clients->find('list')
            ->where(['Clients.group_id' => \App\Model\Table\GroupsTable::CLIENT])
            ->toArray();

        $this->set(compact('poster', 'users', 'tags', 'clients'));
    }

    /**
     * Delete method
     */
    public function delete($id = null)
    {
        if (!$id) {
            $this->Flash->error(__('Invalid id for Poster'));
            return $this->redirect(['action' => 'index']);
        }

        $poster = $this->Posters->get($id);
        if ($this->Posters->delete($poster)) {
            $this->Flash->success(__('Poster deleted'));
        } else {
            $this->Flash->error(__('The poster could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Save positions method (for image ordering)
     */
    public function savePositions()
    {
        $this->request->allowMethod(['post']);
        
        $data = $this->request->getData();
        if (!empty($data['positions'])) {
            $imagesTable = $this->getTableLocator()->get('Images');
            foreach ($data['positions'] as $position => $imageId) {
                $image = $imagesTable->get($imageId);
                $image->position = $position;
                $imagesTable->save($image);
            }
            $this->Flash->success(__('Image positions saved.'));
        }

        return $this->redirect($this->referer());
    }

    /**
     * PDF method
     */
    public function pdf($id = null)
    {
        if (!$id) {
            $this->Flash->error(__('Invalid Poster.'));
            return $this->redirect(['action' => 'index']);
        }

        $poster = $this->Posters->get($id, [
            'contain' => ['Users', 'Images', 'Tags']
        ]);

        // Generate PDF (requires PDF library)
        // This is a placeholder - implement PDF generation as needed
        $this->viewBuilder()->setLayout('pdf');
        $this->set(compact('poster'));
    }

    /**
     * Notify method
     */
    public function notify($id = null)
    {
        $this->request->allowMethod(['post']);
        
        if (!$id) {
            $this->Flash->error(__('Invalid Poster.'));
            return $this->redirect(['action' => 'index']);
        }

        $poster = $this->Posters->get($id, [
            'contain' => ['Clients']
        ]);

        // Send notification email to clients
        // This is a placeholder - implement email notification as needed
        $this->Flash->success(__('Notification sent.'));
        
        return $this->redirect(['action' => 'view', $id]);
    }
}

