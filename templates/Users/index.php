<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Datasource\ResultSetInterface<\App\Model\Entity\User> $users
 * @var array $groups
 */
?>
<style>
    .simple_table th, .simple_table td{
        padding: 0 15px;
    }
    .simple_table th{
        padding-bottom:10px;
    }
</style>
<div class="users index">
    <h2><?= __('Users') ?></h2>

    <div class="users form">
        <?= $this->Form->create(null, ['url' => ['action' => 'index']]) ?>
        <fieldset>
            <?php
            echo $this->Form->control('Search.username');
            echo $this->Form->control('group_id', ['options' => $groups]);
            echo $this->Form->button('Search');
            ?>
        </fieldset>
        <?= $this->Form->end() ?>
    </div>

    <div class="actions">
        <ul>
            <li><?= $this->Html->link(__('Create New User'), ['action' => 'add']) ?></li>
        </ul>
    </div>
    <div class="paging">
        <?= $this->Paginator->prev('<< ' . __('previous'), [], null, ['class' => 'disabled']) ?>
        | <?= $this->Paginator->numbers() ?>
        <?= $this->Paginator->next(__('next') . ' >>', [], null, ['class' => 'disabled']) ?>
    </div>
    <p>
        <?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} records out of {{count}} total')]) ?>
    </p>
    <table class="simple_table">
        <tr>
            <th><?= $this->Paginator->sort('id') ?></th>
            <th><?= $this->Paginator->sort('username') ?></th>
            <th><?= $this->Paginator->sort('group_id') ?></th>
            <th><?= $this->Paginator->sort('created') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
        <?php
        $i = 0;
        foreach ($users as $user):
            $class = null;
            if ($i++ % 2 == 0) {
                $class = ' class="altrow"';
            }
        ?>
            <tr<?= $class ?>>
                <td><?= $user->id ?></td>
                <td><?= h($user->username) ?></td>
                <td><?= h($user->group->name ?? '') ?></td>
                <td><?= h($user->created) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $user->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $user->id]) ?>
                    <?= $this->Html->link(__('Delete'), ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete # {0}?', $user->id)]) ?>
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
        <li><?= $this->Html->link(__('New User'), ['action' => 'add']) ?></li>
    </ul>
</div>

