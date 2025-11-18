<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Datasource\ResultSetInterface<\App\Model\Entity\Comment> $comments
 * @var int|null $userId
 */
?>
<h1>Recent Comments</h1>

<div class="paging">
    <?= $this->Paginator->prev('<< ' . __('previous'), [], null, ['class' => 'disabled']) ?>
    | <?= $this->Paginator->numbers() ?>
    <?= $this->Paginator->next(__('next') . ' >>', [], null, ['class' => 'disabled']) ?>
</div>

<?php $lastDate = null ?>
<?php foreach ($comments as $comment):
    $time = (int)strtotime($comment->timestamp);
    $newDate = date('Ymd', $time);
    if ($lastDate != $newDate) {
        $lastDate = $newDate;
        ?><h3><?= date('M j Y', $time) ?></h3><?php
    }
    ?>
    <div class="poster">
        <div class="poster_image">
            <?php
            if (!empty($comment->poster->images[0])) {
                echo $this->Html->link(
                    $this->Html->image(POSTER_IMAGE_DIR . 'small/' . $comment->poster->images[0]->image_url),
                    [
                        'controller' => 'Posters',
                        'action' => 'view',
                        $comment->poster->id
                    ],
                    ['escape' => false]
                );
            } else {
                echo '<br /><br /><br />(No images added.)<br />';
            }
            ?>
        </div>
        <div class="poster_text">
            <div class="poster_title">
                <?= $this->Html->link($comment->poster->title, [
                    'controller' => 'Posters',
                    'action' => 'view',
                    $comment->poster->id
                ]) ?>
            </div>
            From:
            <div style="color: white; display: inline">
                <?= h($comment->user->username) ?>
            </div>
            on <?= h($comment->timestamp) ?><br><br>
            <?= $comment->comments ?>
        </div>
    </div>
<?php endforeach; ?>

<div class="paging">
    <?= $this->Paginator->prev('<< ' . __('previous'), [], null, ['class' => 'disabled']) ?>
    | <?= $this->Paginator->numbers() ?>
    <?= $this->Paginator->next(__('next') . ' >>', [], null, ['class' => 'disabled']) ?>
</div>

