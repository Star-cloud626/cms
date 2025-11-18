<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Poster $poster
 * @var array<\App\Model\Entity\Comment> $comments
 */

use App\Model\Table\GroupsTable;

$auth = $this->get('auth');
$group = $auth ? ($auth->get('group_id') ?? GroupsTable::PUB) : GroupsTable::PUB;
?>
<style type="text/css">
    .poster_table{
        margin-bottom: 40px;
    }
    .poster_table td{
        padding: 15px;
        vertical-align: top;
    }
</style>
<?php $this->Search->printSearchForm(); ?>
<h3><?= h($poster->title) ?></h3>
<table class="poster_table">
    <tr>
        <td rowspan="3" style="width: 200px">
            <?php
            if (!empty($poster->images[0])):
                echo $this->Html->link(
                    $this->Html->image(
                        POSTER_IMAGE_DIR . 'small/' . $poster->images[0]->image_url,
                        ['alt' => $poster->images[0]->description ?: $poster->title]
                    ),
                    ['action' => 'formatted', $poster->id],
                    ['escape' => false]
                );
            else:
                echo '(No image added)';
            endif;
            ?>
        </td>
        <td><?= $poster->description ?></td>
    </tr>
    <tr>
        <td><?= $poster->procedures ?></td>
    </tr>
    <tr>
        <td><?= $poster->conclusion ?></td>
    </tr>
</table>
<?php
if ($auth && !empty($comments) && count($comments) > 0):
?>
    <div style="width: 700px; margin: 0 auto 25px auto; padding: 5px">
        <h3>Comments</h3>
    </div>
    <?php
    foreach ($comments as $comment):
    ?>
        <div class="corner" style="background-color: #111; width: 680px; margin: 0 auto 25px auto; padding: 15px">
            <div style="position:relative; right: 0px; padding-bottom: 1px">
                <div style="font-weight: bold; display: inline"><?= h($comment->user->username) ?></div>
                <div style="position:absolute; right: 0px; top: 2px; display: inline; font-size: 10px"><?= $this->Time->nice($comment->timestamp) ?></div>
                <?php
                if ($comment->user_id == $auth->id):
                    echo "<span style=\"padding-left:10px\">" . $this->Html->link(__('Edit'), ['controller' => 'Comments', 'action' => 'edit', $comment->id]) . "</span>";
                    echo "<span style=\"padding-left:10px\">" . $this->Html->link(__('Delete'), ['controller' => 'Comments', 'action' => 'delete', $comment->id]) . "</span>";
                endif;
                ?>
            </div>
            <p style="margin-top: 10px"><?= $comment->comments ?></p>
        </div>
    <?php
    endforeach;
endif;

$canComment = false;
if ($auth) {
    $access = $this->get('access');
    $canComment = ($access && $access->comment) || ($group <= GroupsTable::USER);
}

if ($canComment):
?>
    <div style="background-color: #111; width: 700px; margin: 0 auto 25px auto; padding: 5px">
        <script type="text/javascript" src="<?= $this->Url->build('/ckeditor/ckeditor.js') ?>"></script>

        <?= $this->Form->create(null, ['url' => ['controller' => 'Comments', 'action' => 'add'], 'style' => 'width: 690px']) ?>
        <textarea cols="80" id="Comment_comments" name="Comment[comments]" rows="10" style="align:center;"></textarea>
        <script type="text/javascript">
            //<![CDATA[
            CKEDITOR.replace('Comment_comments', {
                filebrowserBrowseUrl: '<?= $this->Url->build('/ckfinder/ckfinder.html') ?>',
                filebrowserImageBrowseUrl: '<?= $this->Url->build('/ckfinder/ckfinder.html?Type=Images') ?>',
                filebrowserFlashBrowseUrl: '<?= $this->Url->build('/ckfinder/ckfinder.html?Type=Flash') ?>',
                filebrowserUploadUrl: '<?= $this->Url->build('/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files') ?>',
                filebrowserImageUploadUrl: '<?= $this->Url->build('/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images') ?>',
                filebrowserFlashUploadUrl: '<?= $this->Url->build('/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash') ?>',
                toolbar: [['Source', '-', 'Bold', 'Italic', 'Underline', 'Strike', '-', 'Link', '-', 'Image']]
            });
            //]]>
        </script>
        <?= $this->Form->hidden('Comment.poster_id', ['value' => $poster->id]) ?>
        <?= $this->Form->button('Add Comment', ['style' => 'margin-top: 5px']) ?>
        <?= $this->Form->end() ?>
    </div>
<?php endif; ?>

