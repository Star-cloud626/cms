<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Client $client
 */
?>
<div class="page_form corner">
    <?= $this->Form->create($client, ['onSubmit' => 'return verifyPasswords("password","verify")']) ?>
    <fieldset>
        <legend><?= __('Add Client') ?></legend>
        <?php
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

