<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<script type="text/javascript">
function verify(){
    if(document.getElementById('password').value != document.getElementById('verify_password').value){
        alert('Passwords do not match.');
        return false;
    }
    if(document.getElementById('email').value != document.getElementById('verify_email').value){
        alert('E-mail addresses do not match.');
        return false;
    }
    return true;
}
</script>
<div class="page_form corner">
    <?= $this->Form->create($user, ['url' => ['action' => 'register'], 'onSubmit' => 'javascript: return verify();']) ?>
    <fieldset>
        <legend><?= __('Register') ?></legend>
        <table>
            <tbody>
                <tr>
                    <td>
                        <?php
                        echo $this->Form->control('username');
                        echo $this->Form->control('fullname');
                        echo $this->Form->control('password', ['id' => 'password']);
                        echo $this->Form->control('verify_password', ['type' => 'password', 'id' => 'verify_password', 'label' => 'Verify Password']);
                        echo $this->Form->control('email', ['id' => 'email']);
                        echo $this->Form->control('verify_email', ['id' => 'verify_email', 'label' => 'Verify Email']);
                        echo $this->Form->control('home_phone', ['label' => 'Primary Phone']);
                        ?>
                    </td>
                    <td style="padding:20px;vertical-align:top">
                        Please provide a valid e-mail address.
                        Once you complete this form,
                        you will be asked to activate your account via e-mail.
                    </td>
                </tr>
            </tbody>
        </table>
    </fieldset>
    <?= $this->Form->button('Submit') ?>
    <?= $this->Form->end() ?>
</div>

