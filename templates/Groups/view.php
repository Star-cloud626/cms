<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Group $group
 */
?>
<div class="groups view">
    <h2><?= __('Group') ?></h2>
    <dl>
        <dt><?= __('Id') ?></dt>
        <dd><?= $group->id ?></dd>
        <dt><?= __('Name') ?></dt>
        <dd><?= h($group->name) ?></dd>
        <dt><?= __('Created') ?></dt>
        <dd><?= h($group->created) ?></dd>
        <dt><?= __('Modified') ?></dt>
        <dd><?= h($group->modified) ?></dd>
    </dl>
</div>
<div class="actions">
    <ul>
        <li><?= $this->Html->link(__('Edit Group'), ['action' => 'edit', $group->id]) ?></li>
        <li><?= $this->Html->link(__('Delete Group'), ['action' => 'delete', $group->id], ['confirm' => __('Are you sure you want to delete # {0}?', $group->id)]) ?></li>
        <li><?= $this->Html->link(__('List Groups'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Group'), ['action' => 'add']) ?></li>
    </ul>
</div>

