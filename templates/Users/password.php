<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div class="page_form corner" style="width: 500px">
    <?= $this->Form->create($user, ['url' => ['action' => 'password']]) ?>
    <fieldset>
        <legend><?= __('Edit Password') ?></legend>
        <?php
        echo $this->Form->control('id', ['type' => 'hidden']);
        echo $this->Form->control('password', ['label' => 'New Password']);
        echo $this->Form->control('verify', ['type' => 'password', 'label' => 'Re-type password']);
        ?>
    </fieldset>
    <?= $this->Form->button('Submit') ?>
    <?= $this->Form->end() ?>
</div>

