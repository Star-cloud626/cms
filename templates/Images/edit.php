<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Image $image
 */
?>
<div class="corner page_form">
    <?= $this->Form->create($image) ?>
    <fieldset>
        <legend><?= __('Edit Image') ?></legend>
        <?php
        echo $this->Form->control('id');
        if ($image->image_url) {
            echo $this->Html->image(POSTER_IMAGE_DIR . 'small/' . $image->image_url);
        }
        echo $this->Form->control('description');
        ?>
    </fieldset>
    <?= $this->Form->button('Submit') ?>
    <?= $this->Form->end() ?>
</div>
<div class="actions">
    <ul>
        <li><?= $this->Html->link(__('Delete'), ['action' => 'delete', $image->id], ['confirm' => __('Are you sure you want to delete # {0}?', $image->id)]) ?></li>
        <li><?= $this->Html->link(__('List Posters'), ['controller' => 'Posters', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Poster'), ['controller' => 'Posters', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('Back to Poster'), ['controller' => 'Posters', 'action' => 'view', $image->poster_id]) ?></li>
        <li><?= $this->Html->link(__('Back to Detail'), ['controller' => 'Posters', 'action' => 'detail', $image->poster_id]) ?></li>
    </ul>
</div>

