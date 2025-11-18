<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\View\Helper;

/**
 * Search helper
 */
class SearchHelper extends Helper
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
    protected array $helpers = ['Html', 'Form'];

    /**
     * Print search form
     */
    public function printSearchForm(): void
    {
        $sortableColumns = ['id', 'title', 'authenticity', 'created', 'modified'];

        // Generate search options
        $sortOptions = [];
        foreach ($sortableColumns as $column) {
            $sortOptions[$column . ':asc'] = ucwords($column) . ' Ascending';
            $sortOptions[$column . ':desc'] = ucwords($column) . ' Descending';
        }

        $searchData = $this->getView()->get('searchData', []);
        ?>
        <div id="search_bar" class="corner">
        <?= $this->Form->create(null, ['url' => ['controller' => 'Posters', 'action' => 'index']]) ?>
        <?= $this->Form->hidden('Search.page', ['id' => 'search_form_page', 'value' => 1]) ?>
        <?php
        $inputOptions = ['label' => false];
        if (!empty($searchData['value']) && $searchData['value'] !== 'Enter Search Term') {
            $inputOptions['value'] = $searchData['value'];
        } else {
            $inputOptions['value'] = 'Enter Search Term';
            $inputOptions['class'] = 'show_default';
        }
        ?>
        <table id="search_form">
            <tr>
                <td><?= $this->Form->control('Search.value', $inputOptions) ?></td>
                <td>
                <?= $this->Form->control('Search.field', [
                    'options' => [
                        'Poster.title or Poster.description' => 'Title or Description',
                        'Poster.title' => 'Title',
                        'Tag.tag' => 'Tags',
                        'Poster.description' => 'Description',
                        'Poster.procedures' => 'Procedures',
                        'Poster.conclusion' => 'Conclusion',
                        'Client.client' => 'Client'
                    ],
                    'label' => false,
                    'default' => $searchData['field'] ?? null
                ]) ?>
                </td>
                <td>
                <?= $this->Form->control('Search.authenticity', [
                    'options' => [
                        '' => 'Authenticity',
                        'authentic' => 'authentic',
                        'pending' => 'pending',
                        'fake' => 'fake'
                    ],
                    'label' => false,
                    'default' => $searchData['authenticity'] ?? ''
                ]) ?>
                </td>
                <td>
                <?= $this->Form->control('Search.images', [
                    'options' => [
                        '' => 'Images',
                        'no' => 'No',
                        'yes' => 'Yes',
                    ],
                    'label' => false,
                    'default' => $searchData['images'] ?? ''
                ]) ?>
                </td>
                <td>
                    <?= $this->Form->control('Search.sort_selector', [
                        'options' => $sortOptions,
                        'default' => $searchData['sort_selector'] ?? 'created:desc',
                        'label' => false
                    ]) ?>
                </td>
                <td>
                <?= $this->Form->button('Search', [
                    'onClick' => 'document.getElementById("search_form_page").value=1; return true'
                ]) ?>
                </td>
            </tr>
        </table>
        <?= $this->Form->end() ?>
        </div>
        <?php
    }
}

