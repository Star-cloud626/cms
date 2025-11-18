<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Setting $setting
 */
?>
<div class="page_form corner">
    <?= $this->Form->create($setting) ?>
    <fieldset>
        <legend><?= __('Edit ' . ucwords($setting->name)) ?></legend>
        <?php
        echo $this->Form->control('name', ['type' => 'hidden']);
        echo $this->Form->control('text', [
            'label' => false,
            'rows' => '20'
        ]);
        ?>
    </fieldset>
    <?= $this->Form->button('Submit') ?>
    <?= $this->Form->end() ?>
</div>

