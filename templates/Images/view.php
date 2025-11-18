<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Image $image
 */
?>
<div class="images view">
    <h2><?= __('Image') ?></h2>
    <dl>
        <dt><?= __('Id') ?></dt>
        <dd><?= $image->id ?></dd>
        <dt><?= __('Poster') ?></dt>
        <dd>
            <?= $this->Html->link($image->poster->title, ['controller' => 'Posters', 'action' => 'view', $image->poster->id]) ?>
        </dd>
        <dt><?= __('Image') ?></dt>
        <dd>
            <a href="<?= $this->Url->build('/img/detail/big/' . $image->image_url) ?>">
                <?= $this->Html->image(POSTER_IMAGE_DIR . 'small/' . $image->image_url) ?>
            </a>
        </dd>
        <dt><?= __('Description') ?></dt>
        <dd><?= h($image->description) ?></dd>
        <dt><?= __('Modified') ?></dt>
        <dd><?= h($image->modified) ?></dd>
    </dl>
</div>
<div class="actions">
    <ul>
        <li><?= $this->Html->link(__('Edit Image'), ['action' => 'edit', $image->id]) ?></li>
        <li><?= $this->Html->link(__('Delete Image'), ['action' => 'delete', $image->id], ['confirm' => __('Are you sure you want to delete # {0}?', $image->id)]) ?></li>
        <li><?= $this->Html->link(__('List Images'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Image'), ['action' => 'add', $image->poster_id]) ?></li>
        <li><?= $this->Html->link(__('List Posters'), ['controller' => 'Posters', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Poster'), ['controller' => 'Posters', 'action' => 'add']) ?></li>
    </ul>
</div>
<h2><?= __('Original Size Image') ?></h2>
<?= $this->Html->image(POSTER_IMAGE_DIR . 'big/' . $image->image_url) ?>

