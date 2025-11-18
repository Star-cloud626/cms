<?php
/**
 * @var \App\View\AppView $this
 * @var array $settings
 * @var \App\Model\Entity\User $user
 */
?>
<div class="view" style="position: relative">
    <h2><?= __('Settings') ?></h2>
    <div class="page_form corner" style="width: 410px; margin-top: 30px; float:right">
        <h2>Password</h2>
        <div class="actions">
            <ul>
                <li><?= $this->Html->link(__('Change Password'), ['controller' => 'Users', 'action' => 'password', $user->id]) ?></li>
            </ul>
        </div>
    </div>

    <div class="page_form corner" style="width: 410px; margin-top: 30px; float:right; clear: right">
        <h2>PDF</h2>
        Top Margin: <?= h($settings['pdf top margin'] ?? '') ?><br />
        Left Margin: <?= h($settings['pdf left margin'] ?? '') ?><br />
        Bottom Margin: <?= h($settings['pdf bottom margin'] ?? '') ?><br />
        Content Width: <?= h($settings['pdf content width'] ?? '') ?><br />
        <br />
        Front Font Size: <?= h($settings['pdf front font size'] ?? '') ?><br />
        Images Font Size: <?= h($settings['pdf images font size'] ?? '') ?><br />
        Legal Font Size: <?= h($settings['pdf legal font size'] ?? '') ?><br />
        <br />
        First Image Text: <?= h($settings['pdf images font size'] ?? '') ?><br />
        Second Image Text: <?= h($settings['pdf legal font size'] ?? '') ?><br />
        <div class="actions">
            <ul>
                <li><?= $this->Html->link(__('Edit'), ['action' => 'pdf']) ?></li>
            </ul>
        </div>
    </div>

    <?php $name = 'notification email'; ?>
    <div class="page_form corner" style="width: 410px; margin-top: 30px;">
        <h2><?= ucwords($name) ?></h2>
        <div style="width: 400px; padding: 10px;"><?= nl2br(h($settings[$name] ?? '')) ?></div>
        <div class="actions">
            <ul>
                <li><?= $this->Html->link(__('Edit'), ['action' => 'edit', $name]) ?></li>
            </ul>
        </div>
    </div>

    <?php $name = 'contact'; ?>
    <div class="page_form corner" style="width: 410px; margin-top: 30px;">
        <h2><?= ucwords($name) ?></h2>
        <div style="width: 400px; padding: 10px;"><?= nl2br(h($settings[$name] ?? '')) ?></div>
        <div class="actions">
            <ul>
                <li><?= $this->Html->link(__('Edit'), ['action' => 'edit', $name]) ?></li>
            </ul>
        </div>
    </div>

    <?php $name = 'legal'; ?>
    <div class="page_form corner" style="width: 880px; margin: 30px 0; float: left; clear: both">
        <h2><?= ucwords($name) ?></h2>
        <div style="width: 860px; padding: 10px;"><?= nl2br(h($settings[$name] ?? '')) ?></div>
        <div class="actions">
            <ul>
                <li><?= $this->Html->link(__('Edit'), ['action' => 'edit', $name]) ?></li>
            </ul>
        </div>
    </div>
</div>

