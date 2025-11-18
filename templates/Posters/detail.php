<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Poster $poster
 */
?>
<?php $this->Search->printSearchForm() ?>
<div class="page_form corner">
    <h2><?= __('Poster') ?></h2>
    <dl>
        <dt><?= __('Id') ?></dt>
        <dd><?= $poster->id ?></dd>
        <dt><?= __('Title') ?></dt>
        <dd><?= h($poster->title) ?></dd>
        <dt><?= __('Authenticity') ?></dt>
        <dd><?= h($poster->authenticity) ?></dd>
        <dt><?= __('Client') ?></dt>
        <dd>
            <?php
            if (!empty($poster->clients)) {
                foreach ($poster->clients as $client) {
                    echo $this->Html->link($client->username, ['controller' => 'Clients', 'action' => 'view', $client->id]);
                    if (!empty($client->fullname)) {
                        echo ' (' . h($client->fullname) . ')';
                    }
                    echo '<br />';
                }
            }
            ?>
        </dd>
        <dt><?= __('Client Viewable') ?></dt>
        <dd><?= $poster->client_viewable ? "Yes" : "No" ?></dd>
        <dt><?= __('Public Viewable') ?></dt>
        <dd><?= $poster->public_viewable ? "Yes" : "No" ?></dd>
        <dt><?= __('Verso Image') ?></dt>
        <dd><?= $poster->verso ? "Yes" : "No" ?></dd>
        <dt><?= __('Description') ?></dt>
        <dd><?= $poster->description ?></dd>
        <dt><?= __('Procedures') ?></dt>
        <dd><?= $poster->procedures ?></dd>
        <dt><?= __('Conclusion') ?></dt>
        <dd><?= $poster->conclusion ?></dd>
        <dt><?= __('Created') ?></dt>
        <dd><?= h($poster->created) ?></dd>
        <dt><?= __('Modified') ?></dt>
        <dd><?= h($poster->modified) ?></dd>
    </dl>
</div>
<div class="actions">
    <ul>
        <li><?= $this->Html->link(__('List Posters'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('Edit Poster'), ['action' => 'edit', $poster->id]) ?></li>
        <li><?= $this->Html->link(__('Delete Poster'), ['action' => 'delete', $poster->id], ['confirm' => __('Are you sure you want to delete # {0}? This will also delete its images.', $poster->id)]) ?></li>
        <li><?= $this->Html->link(__('New Poster'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('View Formatted'), ['action' => 'formatted', $poster->id], ['target' => '_blank']) ?></li>
        <li><?= $this->Html->link(__('View PDF'), ['action' => 'pdf', $poster->id], ['target' => '_blank']) ?></li>
        <li><?= $this->Html->link(__('Add Image'), ['controller' => 'Images', 'action' => 'add', $poster->id]) ?></li>
        <li><?= $this->Html->link(__('Add Multiple Images'), ['controller' => 'Images', 'action' => 'addMultiple', $poster->id]) ?></li>
        <li><?= $this->Html->link(__('E-mail Client(s)'), ['action' => 'notify', $poster->id, 'ready']) ?></li>
    </ul>
</div>
<div class="related">
    <h3><?= __('Related Images') ?></h3>
    <?php if (!empty($poster->images)): ?>
    <table style="border:0px;">
        <tr>
            <td style="vertical-align:top; width: 550px; border: 0px;">
                <ol id="images" style="width:550px; text-align:center;">
                    <?php foreach ($poster->images as $image): ?>
                        <li id='<?= $image->id ?>' style="padding: 10px;">
                            <table style="border: 1px solid gray;">
                                <tr>
                                    <td style="width:200px; padding: 10px">
                                        <?= $this->Html->image(POSTER_IMAGE_DIR . 'small/' . $image->image_url) ?>
                                        <div class="actions" style="margin: 0 auto;">
                                            <?= $this->Html->link(__('View'), ['controller' => 'Images', 'action' => 'view', $image->id]) ?>
                                            <?= $this->Html->link(__('Edit'), ['controller' => 'Images', 'action' => 'edit', $image->id]) ?>
                                            <?= $this->Html->link(__('Delete'), ['controller' => 'Images', 'action' => 'delete', $image->id], ['confirm' => __('Are you sure you want to delete?')]) ?>
                                        </div>
                                    </td>
                                    <td style="text-align: left; vertical-align: top; padding: 10px; border-left: 1px solid gray;">
                                        <p style="font-size: 10px; width:250px;"><?= h($image->modified) ?></p>
                                        <p><?= nl2br(h($image->description)) ?></p>
                                    </td>
                                </tr>
                            </table>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </td>
            <td style="vertical-align:top; text-align:left; border: 0px; padding: 10px;">
                <p>Drag the Images on the left to order them. Click the button below to save their positions.</p>
                <br />
                <p>The order of the images determine where they appear when they are formatted.</p>
                <br />
                <div id="status"></div>
                <br />
                <?= $this->Form->create(null, ['url' => ['action' => 'savePositions']]) ?>
                <div id="positionFormContent"></div>
                <?= $this->Form->button('Save Positions', ['id' => 'savePositions']) ?>
                <?= $this->Form->end() ?>
            </td>
        </tr>
    </table>
    <?php else: ?>
        <p>No images yet.</p>
    <?php endif; ?>
</div>

