<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Poster $poster
 * @var array $watermarks
 */
$auth = $this->get('auth');
$group = $auth ? ($auth->get('group_id') ?? 4) : 4;
?>
<?php $this->Search->printSearchForm() ?>
<h2>Add Images</h2>
<table>
    <tbody>
        <tr>
            <td>
                <applet code="wjhk.jupload2.JUploadApplet"
                        archive="<?= $this->Url->build('/wjhk.jupload.jar') ?>"
                        width="500" height="600" alt=""
                        mayscript>
                    <param name="postURL"
                           value="<?= $this->Url->build(['action' => 'uploadOnly', $poster->id]) ?>" />
                    <param name="maxChunkSize" value="500000" />
                    <param name="uploadPolicy" value="PictureUploadPolicy" />
                    <param name="nbFilesPerRequest" value="1" />
                    <param name="pictureCompressionQuality" value="0.9" />
                    <param name="maxPicHeight" value="1024" />
                    <param name="maxPicWidth" value="1280" />
                    <param name="formData" value="uploadForm" />
                    <param name="debugLevel" value="0" />
                    Java 1.5 or higher plugin required.
                </applet>
            </td>
            <td style="vertical-align: top; padding: 15px">
                <p>Poster: <?= h($poster->title) ?></p>
                <br/>
                <form name="uploadForm">
                    <?= $this->Form->control('watermark', ['type' => 'select', 'options' => $watermarks, 'label' => false]) ?>
                </form>
                <br/>
                <p>Images will be resized to fit within 1280x1024. The aspect ratio will be kept the same.</p>
            </td>
        </tr>
    </tbody>
</table>
<div class="actions">
    <ul>
        <li><?= $this->Html->link(__('List Posters'), ['controller' => 'Posters', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Poster'), ['controller' => 'Posters', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('View Poster'), ['controller' => 'Posters', 'action' => 'view', $poster->id]) ?></li>
        <?php if ($group <= 2): ?>
        <li><?= $this->Html->link(__('Poster Detail'), ['controller' => 'Posters', 'action' => 'detail', $poster->id]) ?></li>
        <li><?= $this->Html->link(__('Edit Poster'), ['controller' => 'Posters', 'action' => 'edit', $poster->id]) ?></li>
        <?php endif; ?>
    </ul>
</div>

