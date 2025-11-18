<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Mailer\MailerAwareTrait;

/**
 * Comments Controller
 *
 * @property \App\Model\Table\CommentsTable $Comments
 * @property \App\Model\Table\PostersTable $Posters
 * @property \App\Model\Table\AccessTable $Access
 * @property \App\Model\Table\SettingsTable $Settings
 */
class CommentsController extends AppController
{
    use MailerAwareTrait;

    /**
     * Permissions array
     */
    protected array $permissions = [
        'index' => ['user'],
        'add' => ['user', 'client', 'public'],
        'edit' => ['user', 'client', 'public'],
        'delete' => ['user', 'client', 'public']
    ];

    /**
     * Initialize method
     */
    public function initialize(): void
    {
        parent::initialize();
        // In CakePHP 5.0, use fetchTable() instead of loadModel()
        $this->Posters = $this->fetchTable('Posters');
        $this->Access = $this->fetchTable('Access');
        $this->Settings = $this->fetchTable('Settings');
    }

    /**
     * Index method
     */
    public function index()
    {
        $sort = $this->request->getQuery('sort', 'Comments.timestamp');
        $direction = $this->request->getQuery('direction', 'DESC');
        
        $this->paginate = [
            'limit' => 50,
            'order' => [$sort => $direction],
            'contain' => ['Posters', 'Users']
        ];

        $query = $this->Comments->find()
            ->where(['Comments.poster_id >' => 0])
            ->distinct(['Comments.id']);

        $comments = $this->paginate($query);
        
        $user = $this->components()->has('Authentication') ? $this->Authentication->getIdentity() : null;
        $userId = $user ? $user->id : null;
        
        $this->set(compact('comments', 'userId'));
    }

    /**
     * Add method
     */
    public function add()
    {
        $user = $this->components()->has('Authentication') ? $this->Authentication->getIdentity() : null;
        
        if (!$user) {
            $this->Flash->error(__('You must be logged in to add a comment.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $posterId = $data['Comment']['poster_id'] ?? null;
            
            if (!$posterId) {
                $this->Flash->error(__('Invalid poster.'));
                return $this->redirect(['controller' => 'Posters', 'action' => 'index']);
            }

            // Check access
            $access = $this->Access->find()
                ->where([
                    'Access.poster_id' => $posterId,
                    'Access.user_id' => $user->id,
                    'Access.comment' => 1
                ])
                ->first();

            $canComment = $access || ($user->get('group_id') <= 3);

            if ($canComment) {
                $data['Comment']['user_id'] = $user->id;
                $comment = $this->Comments->newEmptyEntity($data['Comment']);
                
                if ($this->Comments->save($comment)) {
                    $poster = $this->Posters->get($posterId);
                    $this->_emailNotification($user, $poster, $data, 'add_comment');
                    $this->Flash->success(__('Comment added.'));
                } else {
                    $this->Flash->error(__('The data could not be saved. Please try again.'));
                }
            } else {
                $this->Flash->error(__('The data could not be saved. Please try again.'));
            }

            return $this->redirect(['controller' => 'Posters', 'action' => 'view', $posterId]);
        }
    }

    /**
     * Edit method
     */
    public function edit($id)
    {
        $user = $this->components()->has('Authentication') ? $this->Authentication->getIdentity() : null;
        
        if (!$user) {
            $this->Flash->error(__('You must be logged in to edit a comment.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        $comment = $this->Comments->get($id);
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $posterId = $data['Comment']['poster_id'] ?? $comment->poster_id;

            // Check access
            $access = $this->Access->find()
                ->where([
                    'Access.poster_id' => $posterId,
                    'Access.user_id' => $user->id,
                    'Access.comment' => 1
                ])
                ->first();

            $canEdit = ($access || $user->get('group_id') <= 3) && ($comment->user_id == $user->id);

            if ($canEdit) {
                $data['Comment']['user_id'] = $user->id;
                $comment = $this->Comments->patchEntity($comment, $data['Comment']);
                
                if ($this->Comments->save($comment)) {
                    $poster = $this->Posters->get($posterId);
                    $this->_emailNotification($user, $poster, $data, 'edit_comment');
                    $this->Flash->success(__('Comment updated.'));
                } else {
                    $this->Flash->error(__('The data could not be saved. Please try again.'));
                }
            } else {
                $this->Flash->error(__('The data could not be saved. Please try again.'));
            }

            return $this->redirect(['controller' => 'Posters', 'action' => 'view', $posterId]);
        }

        $this->set(compact('comment'));
    }

    /**
     * Delete method
     */
    public function delete($id)
    {
        $user = $this->components()->has('Authentication') ? $this->Authentication->getIdentity() : null;
        
        if (!$user) {
            $this->Flash->error(__('You must be logged in to delete a comment.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        $comment = $this->Comments->get($id);
        $posterId = $comment->poster_id;

        // Check access
        $access = $this->Access->find()
            ->where([
                'Access.poster_id' => $posterId,
                'Access.user_id' => $user->id,
                'Access.comment' => 1
            ])
            ->first();

        $canDelete = ($access || $user->get('group_id') <= 3) && ($comment->user_id == $user->id);

        if ($canDelete) {
            if ($this->Comments->delete($comment)) {
                $this->Flash->success(__('Comment deleted.'));
            } else {
                $this->Flash->error(__('Comment could not be deleted.'));
            }
        } else {
            $this->Flash->error(__('You do not have permission to delete this comment.'));
        }

        return $this->redirect(['controller' => 'Posters', 'action' => 'view', $posterId]);
    }

    /**
     * Email notification method
     */
    protected function _emailNotification($user, $poster, $comment, $template = 'add_comment')
    {
        $action = 'added a comment to a poster: ';
        if ($template == 'edit_comment') {
            $action = 'edited a comment on a poster: ';
        }

        $subject = 'Poster Mountain: '
            . $user->username
            . ' has ' . $action
            . $poster->title;

        $information = $this->Settings->find('list', [
            'keyField' => 'name',
            'valueField' => 'text'
        ])->toArray();

        $emailAddresses = $information['notification email'] ?? '';
        if (empty($emailAddresses)) {
            return; // No email addresses configured
        }

        $addresses = explode(',', $emailAddresses);
        
        // TODO: Implement email sending using CakePHP Mailer
        // For now, this is a placeholder
        foreach ($addresses as $address) {
            $address = trim($address);
            if (!empty($address)) {
                // Send email using Mailer
                // $this->getMailer('Comment')->send('notification', [$user, $poster, $comment, $subject]);
            }
        }
    }
}

