<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Tag $tag
 */
?>
<div class="page_form corner" style="margin-bottom: 50px">
    <?= $this->Form->create($tag) ?>
    <fieldset>
        <legend><?= __('Add Tag') ?></legend>
        <?php
        echo $this->Form->control('tag');
        ?>
    </fieldset>
    <?= $this->Form->button('Submit') ?>
    <?= $this->Form->end() ?>
</div>
<div class="actions">
    <ul>
        <li><?= $this->Html->link(__('List Tags'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Posters'), ['controller' => 'Posters', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Poster'), ['controller' => 'Posters', 'action' => 'add']) ?></li>
    </ul>
</div>

