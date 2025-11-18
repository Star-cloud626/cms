<?php
/**
 * @var \App\View\AppView $this
 * @var array $settings
 */
?>
<div class="page_form corner">
    <?= $this->Form->create(null, ['url' => ['action' => 'pdf']]) ?>
    <fieldset>
        <legend><?= __('PDF') ?></legend>
        Top Margin: <?= $this->Form->control('Setting.pdf top margin', ['label' => false, 'value' => $settings['pdf top margin'] ?? '']) ?><br />
        Left Margin: <?= $this->Form->control('Setting.pdf left margin', ['label' => false, 'value' => $settings['pdf left margin'] ?? '']) ?><br />
        Bottom Margin: <?= $this->Form->control('Setting.pdf bottom margin', ['label' => false, 'value' => $settings['pdf bottom margin'] ?? '']) ?><br />
        Content Width: <?= $this->Form->control('Setting.pdf content width', ['label' => false, 'value' => $settings['pdf content width'] ?? '']) ?><br />
        <br />
        Front Font Size: <?= $this->Form->control('Setting.pdf front font size', ['label' => false, 'value' => $settings['pdf front font size'] ?? '']) ?><br />
        Images Font Size: <?= $this->Form->control('Setting.pdf images font size', ['label' => false, 'value' => $settings['pdf images font size'] ?? '']) ?><br />
        Legal Font Size: <?= $this->Form->control('Setting.pdf legal font size', ['label' => false, 'value' => $settings['pdf legal font size'] ?? '']) ?><br />
    </fieldset>
    <?= $this->Form->button('Submit') ?>
    <?= $this->Form->end() ?>
</div>

