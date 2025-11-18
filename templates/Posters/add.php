<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Poster $poster
 * @var array $clients
 * @var array $watermarks
 */
?>
<?= $this->Html->script('user_list') ?>
<?= $this->Html->script('jquery.AddIncSearch') ?>
<?php $this->Search->printSearchForm(); ?>
<div class="page_form corner">
    <?= $this->Form->create(null, ['type' => 'file', 'onSubmit' => 'return verifyPasswords("password","verify")', 'style' => 'width:auto']) ?>
    <table>
        <tbody>
            <tr>
                <td>
                    <h2>Add Poster</h2>
                    <?php
                    $authOptions = ['' => 'none', 'pending' => 'pending', 'authentic' => 'authentic', 'fake' => 'fake'];
                    $authValue = $poster->authenticity ?? '';
                    ?>
                    <?= $this->Form->control('title', [
                        'label' => false,
                        'class' => 'show_default',
                        'value' => 'Enter Poster Title',
                        'default' => $poster->title ?? 'Enter Poster Title'
                    ]) ?>
                    <br />
                    <br />
                    <?= $this->Form->radio('authenticity', $authOptions, ['value' => $authValue]) ?>
                </td>
                <td>
                    <h2>Clients</h2>
                    <p>Select an existing client or create a new one on the next page.</p>
                    <div class="input">
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
                        <?= $this->Form->button('Add to List', ['id' => 'client_list_add', 'style' => 'display:inline; width: auto;']) ?>
                    </div>
                    <?php
                    echo $this->Form->control('add_new_client', ['type' => 'checkbox', 'id' => 'add_new_client']);
                    echo $this->Form->control('client_viewable', ['label' => 'Allow clients to view this poster', 'checked' => true]);
                    echo $this->Form->control('public_viewable', ['label' => 'Make this Poster Public']);
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <h2>Add an Image</h2>
                    <?= $this->Form->control('Image.image_file', ['type' => 'file', 'label' => false]) ?>
                    <br />
                    <br />
                    <?= $this->Form->control('Image.watermark', ['type' => 'select', 'options' => $watermarks, 'label' => false]) ?>
                    <?= $this->Form->control('verso', ['label' => 'Poster has Verso Image.']) ?>
                </td>
                <td>
                    <h2>Clients added to this poster:</h2>
                    <div id="client_list"></div>
                </td>
            </tr>
            <tr>
                <td>
                    <?php
                    echo $this->Form->control('description');
                    echo $this->Form->control('procedures');
                    echo $this->Form->control('conclusion');
                    echo $this->Form->control('notify', [
                        'label' => 'Notify Clients after Saving.',
                        'type' => 'checkbox'
                    ]);
                    ?>
                </td>
                <td>
                    <div id="client_form" style="padding:0; display: none">
                        <fieldset>
                            <legend><?= __('Add Client') ?></legend>
                            <?php
                            echo $this->Form->control('Client.username');
                            echo $this->Form->control('Client.password', ['id' => 'password']);
                            echo $this->Form->control('Client.verify', ['id' => 'verify', 'label' => 'Verify Password', 'type' => 'password']);
                            echo $this->Form->control('Client.fullname');
                            echo $this->Form->control('Client.email');
                            echo $this->Form->control('Client.home_phone');
                            echo $this->Form->control('Client.work_phone');
                            echo $this->Form->control('Client.address');
                            ?>
                        </fieldset>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    <div style="width: 500px; margin:25px auto;">
        <?= $this->Form->button('Submit') ?>
    </div>
    <?= $this->Form->end() ?>
</div>

