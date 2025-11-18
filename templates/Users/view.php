<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div class="users view">
    <h2><?= __('User') ?></h2>
    <dl>
        <dt><?= __('Id') ?></dt>
        <dd><?= $user->id ?></dd>
        <dt><?= __('Username') ?></dt>
        <dd><?= h($user->username) ?></dd>
        <dt><?= __('Group Id') ?></dt>
        <dd><?= $user->group_id ?></dd>
        <dt><?= __('Created') ?></dt>
        <dd><?= h($user->created) ?></dd>
        <dt><?= __('Modified') ?></dt>
        <dd><?= h($user->modified) ?></dd>
    </dl>
</div>
<div class="actions">
    <ul>
        <li><?= $this->Html->link(__('Edit User'), ['action' => 'edit', $user->id]) ?></li>
        <li><?= $this->Html->link(__('Delete User'), ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete # {0}?', $user->id)]) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['action' => 'add']) ?></li>
    </ul>
</div>

