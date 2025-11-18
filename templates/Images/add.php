<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Image $image
 * @var \App\Model\Entity\Poster $poster
 * @var array $watermarks
 */
$auth = $this->get('auth');
$group = $auth ? ($auth->get('group_id') ?? 4) : 4;
?>
<?php $this->Search->printSearchForm() ?>
<div class="page_form corner">
    <?= $this->Form->create($image, ['type' => 'file']) ?>
    <h2><?= __('Add Image') ?></h2>
    <h3><?= __('Adding to') ?>: <?= h($poster->title) ?></h3>
    <?php
    echo $this->Form->control('poster_id', ['type' => 'hidden', 'value' => $poster->id]);
    echo $this->Form->control('image_file', ['type' => 'file', 'label' => false]);
    ?>
    <br /><br />
    <?php
    echo $this->Form->control('watermark', ['type' => 'select', 'options' => $watermarks, 'label' => false]);
    echo $this->Form->control('description');
    ?>
    <?= $this->Form->button('Submit') ?>
    <?= $this->Form->end() ?>
</div>
<div class="actions">
    <ul>
        <li><?= $this->Html->link(__('Add Multiple Images'), ['controller' => 'Images', 'action' => 'addMultiple', $poster->id]) ?></li>
        <li><?= $this->Html->link(__('List Posters'), ['controller' => 'Posters', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Poster'), ['controller' => 'Posters', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('View Poster'), ['controller' => 'Posters', 'action' => 'view', $poster->id]) ?></li>
        <?php if ($group <= 2): ?>
        <li><?= $this->Html->link(__('Poster Detail'), ['controller' => 'Posters', 'action' => 'detail', $poster->id]) ?></li>
        <li><?= $this->Html->link(__('Edit Poster'), ['controller' => 'Posters', 'action' => 'edit', $poster->id]) ?></li>
        <?php endif; ?>
    </ul>
</div>

