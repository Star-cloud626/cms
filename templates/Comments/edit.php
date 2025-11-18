<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Comment $comment
 */
?>
<script type="text/javascript" src="<?= $this->Url->build('/ckeditor/ckeditor.js') ?>"></script>

<?= $this->Form->create($comment, ['url' => ['controller' => 'Comments', 'action' => 'edit', $comment->id]]) ?>

<textarea cols="80" id="Comment_comments" name="Comment[comments]" rows="10" style="align:center;"><?= h($comment->comments) ?></textarea>
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

<?= $this->Form->hidden('Comment.poster_id', ['value' => $comment->poster_id]) ?>
<?= $this->Form->button('Edit Comment') ?>
<?= $this->Form->end() ?>

