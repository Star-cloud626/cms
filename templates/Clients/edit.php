<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Client $client
 */
?>
<div class="page_form">
    <?= $this->Form->create($client, ['onSubmit' => 'return verifyPasswords("password","verify")']) ?>
    <fieldset>
        <legend><?= __('Edit Client') ?></legend>
        <?php
        echo $this->Form->control('id');
        echo $this->Form->control('username');
        echo $this->Form->control('password', ['id' => 'password']);
        echo $this->Form->control('verify', ['id' => 'verify', 'label' => 'Verify Password', 'type' => 'password']);
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
        <li><?= $this->Html->link(__('Delete'), ['action' => 'delete', $client->id], ['confirm' => __('Are you sure you want to delete # {0}?', $client->id)]) ?></li>
        <li><?= $this->Html->link(__('List Client'), ['action' => 'index']) ?></li>
    </ul>
</div>

