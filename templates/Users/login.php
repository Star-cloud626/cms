<?php
/**
 * @var \App\View\AppView $this
 */
?>
<div class="page_form corner" style="width: 400px;">
    <h2>Login</h2>
    <?php
    echo $this->Form->create(null, ['url' => ['controller' => 'Users', 'action' => 'login']]);
    echo $this->Form->control('username', ['label' => 'Username']);
    echo $this->Form->control('password', ['label' => 'Password']);
    echo $this->Form->button('Login');
    echo $this->Form->end();
    ?>
</div>

