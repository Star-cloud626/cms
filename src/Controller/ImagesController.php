<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

/**
 * Images Controller
 *
 * @property \App\Model\Table\ImagesTable $Images
 * @property \App\Model\Table\PostersTable $Posters
 */
class ImagesController extends AppController
{
    /**
     * Permissions array
     */
    protected array $permissions = [
        'add' => [],
        'addMultiple' => [],
        'uploadOnly' => [],
        'delete' => [],
        'edit' => [],
        'index' => [],
        'view' => ['user'],
        'full' => ['user', 'client', 'public']
    ];

    /**
     * Initialize method
     */
    public function initialize(): void
    {
        parent::initialize();
        // In CakePHP 5.0, use fetchTable() instead of loadModel()
        $this->Posters = $this->fetchTable('Posters');
        $this->loadComponent('ImageUpload');
        
        // Allow unauthenticated access to full image view
        if ($this->components()->has('Authentication')) {
            $this->Authentication->allowUnauthenticated(['full']);
        }
    }

    /**
     * Index method
     */
    public function index()
    {
        $this->paginate = [
            'limit' => 50,
            'contain' => ['Posters']
        ];

        $images = $this->paginate($this->Images->find());
        $this->set(compact('images'));
    }

    /**
     * View method
     */
    public function view($id = null)
    {
        if (!$id) {
            $this->Flash->error(__('Invalid Image.'));
            return $this->redirect(['action' => 'index']);
        }
        
        $image = $this->Images->get($id, [
            'contain' => ['Posters']
        ]);
        
        $this->set(compact('image'));
    }

    /**
     * Full method (full size image view)
     */
    public function full($id = null)
    {
        if (!$id) {
            $this->Flash->error(__('Invalid Image.'));
            return $this->redirect(['action' => 'index']);
        }

        $image = $this->Images->get($id, [
            'contain' => ['Posters']
        ]);

        $poster = $this->Posters->get($image->poster_id, [
            'contain' => ['Clients']
        ]);

        // Check permissions
        $user = $this->components()->has('Authentication') ? $this->Authentication->getIdentity() : null;
        $viewable = false;
        
        if (empty($user)) {
            $viewable = $poster->public_viewable ?? false;
        } elseif ($user->get('group_id') >= 3) {
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
                'controller' => 'Images',
                'action' => 'full',
                $id
            ]);
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        $this->viewBuilder()->setLayout('poster_form');
        $this->set(compact('image', 'poster'));
    }

    /**
     * Add method
     */
    public function add($posterId = null)
    {
        if ($posterId === null && empty($this->request->getData())) {
            $this->Flash->error(__('Please select a poster before adding an image.'));
            return $this->redirect(['controller' => 'Posters', 'action' => 'index']);
        }

        $image = $this->Images->newEmptyEntity();
        
        if ($this->request->is('post')) {
            try {
                $data = $this->request->getData();
                $files = $this->request->getUploadedFiles();
                
                if (!empty($data['poster_id'])) {
                    $posterId = $data['poster_id'];
                }
                
                $watermark = $data['Image']['watermark'] ?? null;
                
                // Handle file upload
                if (!empty($files['Image']['image_file'])) {
                    $fileData = $files['Image']['image_file'];
                    
                    // Upload image and get filename
                    $imageUrl = $this->ImageUpload->uploadImageAndThumbnail(
                        $fileData->toArray(),
                        2000, // max image size
                        200,  // thumbnail size
                        'detail',
                        false, // not square
                        $watermark
                    );
                    
                    if ($imageUrl) {
                        $data['Image']['image_url'] = $imageUrl;
                        $data['Image']['file'] = $imageUrl; // For compatibility
                    }
                }
                
                // Get position (find minimum position and subtract 1)
                $existingImages = $this->Images->find()
                    ->where(['poster_id' => $posterId])
                    ->toArray();
                
                $position = 0;
                foreach ($existingImages as $existingImage) {
                    $position = min($position, $existingImage->position ?? 0);
                }
                $position = $position - 1;
                
                $data['Image']['position'] = $position;
                $data['Image']['poster_id'] = $posterId;
                
                $image = $this->Images->patchEntity($image, $data['Image']);
                
                if ($this->Images->save($image)) {
                    $this->Flash->success(__('The Image has been saved.'));
                    return $this->redirect(['action' => 'add', $posterId]);
                }
                $this->Flash->error(__('The Image could not be saved. It is possible that the image file is too large or the filetype is incorrect. Please, try again.'));
            } catch (\Exception $e) {
                $this->Flash->error($e->getMessage());
            }
        }

        $poster = $this->Posters->get($posterId);
        $watermarks = $this->ImageUpload->getAvailableWatermarks();
        $this->set(compact('poster', 'image', 'watermarks'));
    }

    /**
     * Add multiple method
     */
    public function addMultiple($posterId = null)
    {
        if ($posterId === null) {
            $this->Flash->error(__('Please select a poster before adding an image.'));
            return $this->redirect(['controller' => 'Posters', 'action' => 'index']);
        }

        $poster = $this->Posters->get($posterId);
        $this->set(compact('poster'));
    }

    /**
     * Upload only method (AJAX)
     */
    public function uploadOnly($posterId)
    {
        $this->request->allowMethod(['post']);
        $this->autoRender = false;
        
        $watermark = $this->request->getData('watermark');
        $files = $this->request->getUploadedFiles();
        
        if (empty($watermark) || empty($posterId) || empty($files['File0'])) {
            echo "ERROR: No data given.\n";
            exit;
        }

        try {
            // Upload image
            $fileData = $files['File0']->toArray();
            $imageUrl = $this->ImageUpload->uploadImageAndThumbnail(
                $fileData,
                2000,
                200,
                'detail',
                false,
                $watermark
            );
            
            if (empty($imageUrl)) {
                echo "ERROR: Upload failed.\n";
                exit;
            }
            
            // Get last position
            $lastImage = $this->Images->find()
                ->where(['poster_id' => $posterId])
                ->order(['position' => 'DESC'])
                ->first();
            
            $position = 0;
            if ($lastImage) {
                $position = ($lastImage->position ?? 0) + 1;
            }

            $image = $this->Images->newEmptyEntity([
                'poster_id' => $posterId,
                'image_url' => $imageUrl,
                'file' => $imageUrl,
                'position' => $position
            ]);
            
            if ($this->Images->save($image)) {
                echo "SUCCESS\n";
            } else {
                echo "ERROR: Unknown error\n";
            }
        } catch (\Exception $e) {
            echo "ERROR: " . $e->getMessage() . "\n";
        }
        
        exit;
    }

    /**
     * Edit method
     */
    public function edit($id = null)
    {
        if (!$id && empty($this->request->getData())) {
            $this->Flash->error(__('Invalid Image'));
            return $this->redirect(['action' => 'index']);
        }

        $image = $this->Images->get($id);
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $image = $this->Images->patchEntity($image, $this->request->getData());
            
            if ($this->Images->save($image)) {
                $this->Flash->success(__('The Image has been saved.'));
                return $this->redirect($this->referer());
            }
            $this->Flash->error(__('The Image could not be saved. Please, try again.'));
        }
        
        $posters = $this->Posters->find('list')->toArray();
        $this->set(compact('image', 'posters'));
    }

    /**
     * Delete method
     */
    public function delete($id = null)
    {
        if (!$id) {
            $this->Flash->error(__('Invalid id for Image'));
            return $this->redirect($this->referer());
        }

        $image = $this->Images->get($id);
        $posterId = $image->poster_id;
        
        $imageUrl = $image->image_url;
        if ($this->Images->delete($image)) {
            // Delete image file
            if ($imageUrl) {
                $this->ImageUpload->deleteImage($imageUrl, 'detail');
            }
            $this->Flash->success(__('Image deleted'));
            return $this->redirect(['controller' => 'Posters', 'action' => 'detail', $posterId]);
        }
        
        return $this->redirect(['controller' => 'Posters', 'action' => 'index']);
    }
}

