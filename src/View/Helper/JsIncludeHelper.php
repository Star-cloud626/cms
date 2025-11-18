<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\Event\EventInterface;
use Cake\View\Helper;

/**
 * JsInclude helper
 */
class JsIncludeHelper extends Helper
{
    /**
     * Default configuration.
     *
     * @var array<string, mixed>
     */
    protected array $_defaultConfig = [];

    /**
     * Helpers used by this helper
     *
     * @var array
     */
    protected array $helpers = ['Html'];

    /**
     * Page script path
     *
     * @var string
     */
    protected string $_pageScriptPath = 'page_specific';

    /**
     * Before render callback
     * 
     * @param \Cake\Event\EventInterface $event Event
     * @param string $viewFile View file
     * @return void
     */
    public function beforeRender(EventInterface $event, string $viewFile): void
    {
        $controller = $this->getView()->getRequest()->getParam('controller');
        $action = $this->getView()->getRequest()->getParam('action');

        $scriptPath = $this->_pageScriptPath . '/' . strtolower($controller) . '_' . strtolower($action) . '.js';
        $fullPath = WWW_ROOT . 'js' . DS . $scriptPath;

        if (file_exists($fullPath)) {
            // Add to script block - Html->script with block option handles this automatically
            $this->Html->script($scriptPath, ['block' => true]);
        }
    }
}

