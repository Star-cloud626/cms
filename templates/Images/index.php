<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Datasource\ResultSetInterface<\App\Model\Entity\Image> $images
 */
?>
<div class="images index">
    <h2><?= __('Images') ?></h2>
    <p>
        <?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} records out of {{count}} total, starting on record {{start}}, ending on {{end}}')]) ?>
    </p>
    <table cellpadding="0" cellspacing="0">
        <tr>
            <th><?= $this->Paginator->sort('id') ?></th>
            <th><?= $this->Paginator->sort('poster_id') ?></th>
            <th><?= $this->Paginator->sort('image_url') ?></th>
            <th><?= $this->Paginator->sort('description') ?></th>
            <th><?= $this->Paginator->sort('modified') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
        <?php
        $i = 0;
        foreach ($images as $image):
            $class = null;
            if ($i++ % 2 == 0) {
                $class = ' class="altrow"';
            }
        ?>
            <tr<?= $class ?>>
                <td><?= $image->id ?></td>
                <td><?= $this->Html->link($image->poster->title, ['controller' => 'Posters', 'action' => 'view', $image->poster->id]) ?></td>
                <td><?= h($image->image_url) ?></td>
                <td><?= h($image->description) ?></td>
                <td><?= h($image->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $image->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $image->id]) ?>
                    <?= $this->Html->link(__('Delete'), ['action' => 'delete', $image->id], ['confirm' => __('Are you sure you want to delete # {0}?', $image->id)]) ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
<div class="paging">
    <?= $this->Paginator->prev('<< ' . __('previous'), [], null, ['class' => 'disabled']) ?>
    | <?= $this->Paginator->numbers() ?>
    <?= $this->Paginator->next(__('next') . ' >>', [], null, ['class' => 'disabled']) ?>
</div>
<div class="actions">
    <ul>
        <li><?= $this->Html->link(__('New Image'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Posters'), ['controller' => 'Posters', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Poster'), ['controller' => 'Posters', 'action' => 'add']) ?></li>
    </ul>
</div>

