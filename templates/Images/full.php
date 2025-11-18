<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Image $image
 * @var \App\Model\Entity\Poster $poster
 */
?>
<?= $this->Html->image(POSTER_IMAGE_DIR . 'big/' . $image->image_url, ['id' => 'full_image', 'style' => 'cursor: move;']) ?>
<div style="position: absolute; left: 10px; top: 0px;">
    <input id="resize_button" type="button" value="View Original Size" style="font-weight: bold; border: 1px solid gray; display: none; cursor: pointer" onclick="javascript: toggleFull();"/>
</div>
<script>
// Drag functionality would need to be implemented with jQuery UI or similar
</script>

