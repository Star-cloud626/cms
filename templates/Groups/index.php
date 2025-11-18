<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Datasource\ResultSetInterface<\App\Model\Entity\Group> $groups
 */
?>
<div class="groups index">
    <h2><?= __('Groups') ?></h2>
    <p>
        <?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} records out of {{count}} total, starting on record {{start}}, ending on {{end}}')]) ?>
    </p>
    <table cellpadding="0" cellspacing="0">
        <tr>
            <th><?= $this->Paginator->sort('id') ?></th>
            <th><?= $this->Paginator->sort('name') ?></th>
            <th><?= $this->Paginator->sort('created') ?></th>
            <th><?= $this->Paginator->sort('modified') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
        <?php
        $i = 0;
        foreach ($groups as $group):
            $class = null;
            if ($i++ % 2 == 0) {
                $class = ' class="altrow"';
            }
        ?>
            <tr<?= $class ?>>
                <td><?= $group->id ?></td>
                <td><?= h($group->name) ?></td>
                <td><?= h($group->created) ?></td>
                <td><?= h($group->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $group->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $group->id]) ?>
                    <?= $this->Html->link(__('Delete'), ['action' => 'delete', $group->id], ['confirm' => __('Are you sure you want to delete # {0}?', $group->id)]) ?>
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
        <li><?= $this->Html->link(__('New Group'), ['action' => 'add']) ?></li>
    </ul>
</div>

