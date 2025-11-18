<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Poster $poster
 * @var array $clients
 * @var array $tags
 * @var array $publicUsers
 */
?>
<script type="text/javascript">
function fillClients(){
    <?php
    if (!empty($poster->clients)) {
        $index = 0;
        foreach ($poster->clients as $client) {
            echo "clientList.items[$index] = {'id':{$client->id}, 'name':'{$client->username}' };\n";
            $index++;
        }
    }
    ?>
}
function fillCommenters(){
    <?php
    $index = 0;
    if (!empty($poster->access)) {
        foreach ($poster->access as $commenter) {
            if (!$commenter->comment) {
                continue;
            }
            $id = $commenter->user_id;
            $name = $publicUsers[$commenter->user_id] ?? '';
            if ($name) {
                echo "commenterList.items[$index] = {'id':{$id}, 'name':'{$name}' };\n";
                $index++;
            }
        }
    }
    ?>
}
function fillTags(){
    <?php
    if (!empty($poster->tags)) {
        $index = 0;
        foreach ($poster->tags as $tag) {
            echo "tagList.items[$index] = {'id':{$tag->id}, 'name':'{$tag->tag}' };\n";
            $index++;
        }
    }
    ?>
}
</script>
<?php $this->Search->printSearchForm(); ?>
<div class="page_form corner">
    <?= $this->Form->create($poster) ?>
    <table>
        <tr>
            <td>
                <h2><?= __('Edit Poster') ?></h2>
                <?php
                echo $this->Form->control('id');
                echo $this->Form->control('title', ['label' => false]);
                echo $this->Form->control('public_viewable', ['label' => 'Make this Poster Public']);
                echo $this->Form->control('verso', ['label' => 'Verso Image']);
                ?>
            </td>
            <td>
                <?php
                $authOptions = ['' => 'none', 'pending' => 'pending', 'authentic' => 'authentic', 'fake' => 'fake'];
                $authValue = $poster->authenticity ?? '';
                echo $this->Form->radio('authenticity', $authOptions, ['value' => $authValue]);
                ?>
            </td>
        </tr>
        <tr>
            <td>
                <fieldset style="margin-bottom: 50px">
                    <legend>Tags</legend>
                    <p>Add a tag to this poster:</p>
                    <div style="position: relative; display: inline">
                        <?= $this->Form->control('Tag.id_select', [
                            'type' => 'select',
                            'options' => $tags,
                            'id' => 'tag_id_select',
                            'style' => 'display:inline',
                            'label' => false,
                            'div' => false
                        ]) ?>
                    </div>
                    <?= $this->Form->button('Add', ['id' => 'tag_list_add', 'style' => 'display:inline; width: auto;']) ?>
                    <br />
                    <br />
                    <h2>Tags added to this poster:</h2>
                    <div id="tag_list"></div>
                </fieldset>
            </td>
            <td></td>
        </tr>
        <tr>
            <td>
                <fieldset style="margin-bottom: 50px">
                    <legend>Clients</legend>
                    <p>Add a client to this poster:</p>
                    <div style="position: relative; display: inline">
                        <?= $this->Form->control('Client.id_select', [
                            'type' => 'select',
                            'options' => $clients,
                            'id' => 'client_id_select',
                            'style' => 'display:inline',
                            'label' => false,
                            'div' => false
                        ]) ?>
                    </div>
                    <?= $this->Form->button('Add', ['id' => 'client_list_add', 'style' => 'display:inline; width: auto;']) ?>
                    <br />
                    <br />
                    <h2>Clients added to this poster:</h2>
                    <div id="client_list"></div>
                </fieldset>
            </td>
            <td>
                <fieldset style="margin-bottom: 50px">
                    <legend>Commenters</legend>
                    <p>Add a commenter to this poster:</p>
                    <div style="position: relative; display: inline">
                        <?= $this->Form->control('Commenter.id_select', [
                            'type' => 'select',
                            'options' => $publicUsers,
                            'id' => 'commenter_id_select',
                            'style' => 'display:inline',
                            'label' => false,
                            'div' => false
                        ]) ?>
                    </div>
                    <?= $this->Form->button('Add', ['id' => 'commenter_list_add', 'style' => 'display:inline; width: auto;']) ?>
                    <br />
                    <br />
                    <h2>Commenters added to this poster:</h2>
                    <div id="commenter_list"></div>
                </fieldset>
            </td>
        </tr>
    </table>
    <div style="width: 500px; margin: 20px auto">
        <?php
        echo $this->Form->control('description');
        echo $this->Form->control('procedures');
        echo $this->Form->control('conclusion');
        echo $this->Form->control('notify', [
            'label' => 'Notify Clients/Commenters after Saving.',
            'type' => 'checkbox'
        ]);
        ?>
        <?= $this->Form->button('Save Poster') ?>
    </div>
    <?= $this->Form->end() ?>
</div>
<div class="actions" style="margin-top:25px">
    <ul>
        <li><?= $this->Html->link(__('Delete'), ['action' => 'delete', $poster->id], ['confirm' => __('Are you sure you want to delete # {0}?', $poster->id)]) ?></li>
        <li><?= $this->Html->link(__('List Posters'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('View Tags'), ['controller' => 'Tags', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Tag'), ['controller' => 'Tags', 'action' => 'add']) ?></li>
    </ul>
</div>

