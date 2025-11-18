<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Poster $poster
 * @var array $clients
 * @var array $commenters
 * @var string $subject
 * @var string $message
 */
?>
<?= $this->Form->create(null, ['url' => ['action' => 'notify']]) ?>
<?= $this->Form->control('id', ['type' => 'hidden', 'value' => $poster->id]) ?>
<h2>Notify</h2>
<table style="width:100%; margin-bottom: 30px">
    <tbody>
        <tr>
            <th><h3>Clients</h3></th>
            <th><h3>Commenters</h3></th>
        </tr>
        <tr>
            <td style="vertical-align: top">
                <table>
                    <tbody>
                        <?php
                        if (!empty($clients)) {
                            foreach ($clients as $user) {
                                ?>
                                <tr><td>
                                    <input type="checkbox" name="User[id][]" id="<?= $user->id ?>" value="<?= $user->id ?>" />
                                    <label style="display: inline" for="<?= $user->id ?>"><?= h($user->username) ?></label>
                                </td></tr>
                                <?php
                            }
                        } else {
                            ?><tr><td>None.</td></tr><?php
                        }
                        ?>
                    </tbody>
                </table>
            </td>
            <td style="vertical-align: top">
                <table>
                    <tbody>
                        <?php
                        if (!empty($commenters)) {
                            foreach ($commenters as $user) {
                                ?>
                                <tr><td>
                                    <input type="checkbox" name="User[id][]" id="<?= $user->id ?>" value="<?= $user->id ?>" />
                                    <label style="display: inline" for="<?= $user->id ?>"><?= h($user->username) ?></label>
                                </td></tr>
                                <?php
                            }
                        } else {
                            ?><tr><td>None.</td></tr><?php
                        }
                        ?>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
<?= $this->Form->control('subject', ['type' => 'text', 'value' => $subject ?? '', 'style' => 'width:100%']) ?>
<?= $this->Form->control('message', ['type' => 'textarea', 'value' => $message ?? '', 'style' => 'width:100%; height: 250px;']) ?>
<?= $this->Form->button('Send Notification') ?>
<?= $this->Form->end() ?>

