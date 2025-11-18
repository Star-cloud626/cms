<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var array $groups
 */
?>
<div class="users form">
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __('Edit User') ?></legend>
        <?php
        echo $this->Form->control('id');
        echo $this->Form->control('username');
        echo $this->Form->control('group_id', ['options' => $groups]);
        echo $this->Form->control('new_password', [
            'id' => 'password',
            'type' => 'password',
            'label' => 'New Password'
        ]);
        echo $this->Form->control('verify', [
            'id' => 'verify',
            'label' => 'Verify Password',
            'type' => 'password'
        ]);
        echo $this->Form->control('fullname');
        echo $this->Form->control('email');
        echo $this->Form->control('home_phone');
        echo $this->Form->control('work_phone');
        echo $this->Form->control('address');
        ?>
    </fieldset>
    <?= $this->Form->button('Submit') ?>
    <?= $this->Form->end() ?>
</div>
<div class="actions">
    <ul>
        <li><?= $this->Html->link(__('Delete'), ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete # {0}?', $user->id)]) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['action' => 'index']) ?></li>
    </ul>
</div>

