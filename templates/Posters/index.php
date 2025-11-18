<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Datasource\ResultSetInterface<\App\Model\Entity\Poster> $posters
 * @var array $sortOptions
 * @var array $searchData
 * @var int $group
 */

use App\Model\Table\GroupsTable;
?>
<div class="posters index">
    <?php $this->Search->printSearchForm(); ?>
    <div id="paging_container_top" class="corner" style="margin-top:10px; background-color: #11100e; text-align: center; padding: 10px;">
        <?php
        // Setup paginator
        $this->Paginator->setTemplates([
            'counter' => 'Page {{page}} / {{pages}}'
        ]);
        ?>
        <?= $this->Paginator->counter(['format' => 'Page {{page}} / {{pages}}']) ?>

        <div class="paging">
            <?= $this->Paginator->prev('<< ' . __('previous'), [], null, ['class' => 'disabled']) ?>
            | <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >>', [], null, ['class' => 'disabled']) ?>
        </div>
    </div>
<?php
$i = 0;
foreach ($posters as $poster):
    $class = null;
    if ($i++ % 2 == 0) {
        $class = ' class="altrow"';
    }
?>

<div class="poster corner">
    <div class="poster_image">
        <?php
        if (!empty($poster->images[0])):
            echo $this->Html->link(
                $this->Html->image(
                    POSTER_IMAGE_DIR . 'small/' . $poster->images[0]->image_url,
                    ['alt' => $poster->images[0]->description ?: $poster->title]
                ),
                ['action' => 'view', $poster->id],
                ['escape' => false]
            );
        else:
            echo '<br /><br /><br />(No images added.)<br />';
            if ($group <= GroupsTable::ADMIN):
                echo $this->Html->link("Add Image", ['controller' => 'Images', 'action' => 'add', $poster->id]);
            endif;
        endif;
        ?>
    </div>
    <div class="poster_text">
        <?php if ($group <= GroupsTable::ADMIN): ?>
        <div class="poster_views">
            Views: <?= $poster->views ?? 0 ?>
        </div>
        <?php endif; ?>
        <div class="poster_title">
            <?= $this->Html->link($poster->title, ['action' => 'view', $poster->id]) ?>
        </div>
        <p class="poster_para"><?= $this->Format->trimParagraph($poster->description) ?></p>
        <p class="poster_para"><?= $this->Format->trimParagraph($poster->conclusion) ?></p>
    </div>

    <table class="poster_info">
        <tr>
            <td style="width: 150px">
                <p>
                    <?= $this->Time->format($poster->created, 'H:i') ?><br/>
                    <?= $this->Time->format($poster->created, 'M jS Y') ?>
                </p>
            </td>
            <td style="width: 331px; text-align: center">
                <?php if ($group <= GroupsTable::ADMIN): ?>
                <p><?= ($poster->public_viewable ?? false) ? "Public" : "Private" ?></p>
                <?php
                if (!empty($poster->clients)) {
                    $client_text = [];
                    foreach ($poster->clients as $client) {
                        $client_text[] = $this->Html->link($client->username, ['controller' => 'Clients', 'action' => 'view', $client->id]);
                    }
                    echo implode(', ', $client_text);
                } else {
                    echo __('None');
                }
                ?>
                <?php endif; ?>
            </td>
            <td style="width: 170px; text-align: center">
                <?php $statusClass = 'status_' . strtolower($poster->authenticity ?? ''); ?>
                <p class="<?= $statusClass ?>"><?= h($poster->authenticity ?? '') ?></p>

                <div class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'formatted', $poster->id]) ?>
                    <?php if ($group <= GroupsTable::CLIENT): ?>
                        &nbsp;<?= $this->Html->link(__('PDF'), ['action' => 'pdf', $poster->id]) ?>
                    <?php endif; ?>
                    <?php if ($group <= GroupsTable::ADMIN): ?>
                        &nbsp;<?= $this->Html->link(__('Detail'), ['action' => 'detail', $poster->id]) ?>
                        &nbsp;<?= $this->Html->link(__('Edit'), ['action' => 'edit', $poster->id]) ?>
                        &nbsp;<?= $this->Html->link(__('Delete'), ['action' => 'delete', $poster->id], ['confirm' => __('Are you sure you want to delete # {0}? This will also delete its images.', $poster->id)]) ?>
                    <?php endif; ?>
                </div>
            </td>
        </tr>
    </table>
</div>
<?php endforeach; ?>
</div>
<div id="paging_container_bottom" class="corner" style="margin-top:10px; background-color: #11100e; text-align: center; padding: 10px;">
    <div class="paging">
        <?= $this->Paginator->prev('<< ' . __('previous'), [], null, ['class' => 'disabled']) ?>
        | <?= $this->Paginator->numbers() ?>
        <?= $this->Paginator->next(__('next') . ' >>', [], null, ['class' => 'disabled']) ?>
    </div>
</div>

