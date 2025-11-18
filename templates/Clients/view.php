<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Client $client
 */
?>
<div class="page_form corner">
    <h2><?= __('Client') ?></h2>
    <dl>
        <dt><?= __('Id') ?></dt>
        <dd><?= $client->id ?></dd>
        <dt><?= __('Username') ?></dt>
        <dd><?= h($client->username) ?></dd>
        <dt><?= __('Full Name') ?></dt>
        <dd><?= h($client->fullname) ?></dd>
        <dt><?= __('E-mail address') ?></dt>
        <dd><?= h($client->email) ?></dd>
        <dt><?= __('Home Phone') ?></dt>
        <dd><?= h($client->home_phone) ?></dd>
        <dt><?= __('Work Phone') ?></dt>
        <dd><?= h($client->work_phone) ?></dd>
        <dt><?= __('Address') ?></dt>
        <dd><?= nl2br(h($client->address)) ?></dd>
        <dt><?= __('Created') ?></dt>
        <dd><?= h($client->created) ?></dd>
        <dt><?= __('Modified') ?></dt>
        <dd><?= h($client->modified) ?></dd>
    </dl>
</div>
<div class="actions">
    <ul>
        <li><?= $this->Html->link(__('Edit Client'), ['action' => 'edit', $client->id]) ?></li>
        <li><?= $this->Html->link(__('Delete Client'), ['action' => 'delete', $client->id], ['confirm' => __('Are you sure you want to delete # {0}?', $client->id)]) ?></li>
        <li><?= $this->Html->link(__('List Clients'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Client'), ['action' => 'add']) ?></li>
    </ul>
</div>

