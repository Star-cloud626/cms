<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Poster $poster
 * @var array $information
 */
?>
<div id="logo">
    <?= $this->Html->image('pm_logo.png', ['id' => 'logo']) ?>
</div>
<div id="contact_info">
    <?= nl2br(h($information['contact'] ?? '')) ?>
</div>

<div id="content">
    <div id="poster_title_description">
        <div id="poster_title"><?= nl2br(h($poster->title)) ?></div>
        <div id="poster_description"><?= nl2br(h($poster->description)) ?></div>
    </div>

    <!-- images info table -->
    <?php $index = 0; ?>
    <?php foreach ($poster->images as $image): ?>
    <div class="image_block">
        <div class="image_small">
            <?= $this->Html->link(
                $this->Html->image(
                    POSTER_IMAGE_DIR . 'small/' . $image->image_url,
                    ['alt' => $image->description ?: $poster->title]
                ),
                ['controller' => 'Images', 'action' => 'full', $image->id],
                ['escape' => false, 'target' => '_blank']
            ) ?>
        </div>
        <div class="image_description"><?= nl2br(h($image->description)) ?></div>
    </div>
    <?php
    $index++;
    if ($index % 2 == 0):
    ?>
        <div style="display: block; clear:both;"></div>
    <?php endif; ?>
    <?php endforeach; ?>
    <div style="display: block; clear:both;"></div>
    
    <!-- procedures -->
    <div id="procedures">
        <?php if (!empty($poster->procedures)): ?>
        <h1>Procedures</h1>
        <?= nl2br(h($poster->procedures)) ?>
        <?php endif; ?>
    </div>

    <!-- conclusion -->
    <div id="conclusion">
        <?php if (!empty($poster->conclusion)): ?>
        <h1>Conclusion</h1>
        <?= nl2br(h($poster->conclusion)) ?>
        <?php endif; ?>
    </div>

    <!-- authenticity -->
    <div id="authenticity">
        <?php if (!empty($poster->authenticity)): ?>
        <h1>Authenticity</h1>
        <?= h($poster->authenticity) ?>
        <?php endif; ?>
    </div>

    <!-- legal -->
    <div id="legal">
        <?php if (!empty($information['legal'])): ?>
        <h1>Legal</h1>
        <?= nl2br(h($information['legal'])) ?>
        <?php endif; ?>
    </div>
</div>

