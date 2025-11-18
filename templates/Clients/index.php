<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Datasource\ResultSetInterface<\App\Model\Entity\Client> $clients
 */
?>
<style>
    .simple_table th, .simple_table td{
        padding: 0 20px;
    }
    .simple_table th{
        padding-bottom:10px;
    }
    form label {
        display: inline;
        margin-right: 10px;
    }
</style>
<div class="clients index">
    <h2><?= __('Clients') ?></h2>
    <div class="corner" style="margin: 20px 0px; padding: 15px; color: white; background-color: #11100E; width: 500px;">
        Search:
        <?= $this->Form->create(null, ['url' => ['action' => 'index']]) ?>
        <?= $this->Form->control('Search.username', ['label' => false]) ?>
        <?= $this->Form->button('Search') ?>
        <?= $this->Form->end() ?>
    </div>

    <div class="actions">
        <ul>
            <li><?= $this->Html->link(__('Create New Client'), ['action' => 'add']) ?></li>
        </ul>
    </div>

    <div class="paging">
        <?= $this->Paginator->prev('<< ' . __('previous'), [], null, ['class' => 'disabled']) ?>
        | <?= $this->Paginator->numbers() ?>
        <?= $this->Paginator->next(__('next') . ' >>', [], null, ['class' => 'disabled']) ?>
    </div>
    <p>
        <?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} records out of {{count}} total, starting on record {{start}}, ending on {{end}}')]) ?>
    </p>
    <table class="simple_table">
        <tr>
            <th><?= $this->Paginator->sort('id') ?></th>
            <th><?= $this->Paginator->sort('username') ?></th>
            <th><?= $this->Paginator->sort('created') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
        <?php
        $i = 0;
        foreach ($clients as $client):
            $class = null;
            if ($i++ % 2 == 0) {
                $class = ' class="altrow"';
            }
        ?>
            <tr<?= $class ?>>
                <td><?= $client->id ?></td>
                <td><?= $this->Html->link($client->username, ['action' => 'view', $client->id]) ?></td>
                <td><?= h($client->created) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $client->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $client->id]) ?>
                    <?= $this->Html->link(__('Delete'), ['action' => 'delete', $client->id], ['confirm' => __('Are you sure you want to delete # {0}?', $client->id)]) ?>
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
        <li><?= $this->Html->link(__('New Client'), ['action' => 'add']) ?></li>
    </ul>
</div>

