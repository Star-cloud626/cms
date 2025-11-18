<?php
/**
 * Default layout
 *
 * @var \App\View\AppView $this
 */

use App\Model\Table\GroupsTable;

$title = $this->fetch('title') ?? 'Poster Mountain';
$metaDescription = $this->get('metaDescription');
$auth = $this->get('auth');
$group = $auth ? ($auth->get('group_id') ?? GroupsTable::PUB) : GroupsTable::PUB;
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <title>
        <?= __('Poster Mountain:') ?>
        <?= $title ?>
    </title>

    <?php if (!empty($title)): ?>
        <meta property="og:title" content="<?= h($title) ?>" />
    <?php endif; ?>

    <?php if (!empty($metaDescription)): ?>
        <meta name="description" content="<?= h($metaDescription) ?>" />
        <meta property="og:description" content="<?= h($metaDescription) ?>" />
    <?php endif; ?>

    <?php
    $poster = $this->get('poster');
    if (!empty($poster) && !empty($poster->images[0]->image_url)):
    ?>
        <meta property="og:image" content="<?=
            $this->Url->build([
                'controller' => 'img',
                'action' => POSTER_IMAGE_DIR,
                'small/' . $poster->images[0]->image_url
            ], ['fullBase' => true])
        ?>" />
    <?php endif; ?>

    <meta property="og:type" content="website" />

    <?php
    // JavaScript files
    echo $this->Html->script([
        'jquery/jquery-1.3.2.min',
        'jquery/jquery.form',
        'jquery/jquery.jeditable.mini',
        'jquery/jquery-ui-1.7.2.custom.min',
        'jquery/jquery.tools.min',
        'jquery/jquery.corner',
        'ck',
        'user_list',
        'forms',
        'jquery.AddIncSearch'
    ]);
    
    // CSS files
    echo $this->Html->css('cake.generic');
    echo $this->Html->css('cklayout');
    
    echo $this->fetch('script');
    ?>
</head>
<body>
    <div id="central_logo">
        <?= $this->Html->image('PMpage.jpg') ?>
    </div>
    <div id="menuback"></div>
    <div id="menufront">
        <?php
        $menuFile = ROOT . DS . '..' . DS . '_menufront.html';
        if (file_exists($menuFile)) {
            include $menuFile;
        }
        ?>
    </div>
    <div id="menutext">
        Interested in licensing or leasing images? Inquire at <a href="mailto:postermount@aol.com">postermount@aol.com</a>
    </div>
    <div id="divider">
        <table id="divider_content">
            <tr>
                <td id="divider_content_left">
                    <?= $this->Html->link('View Posters', ['controller' => 'Posters', 'action' => 'index']) ?>
                    <?php if ($auth && $group == GroupsTable::ADMIN): ?>
                        <?= $this->Html->link('New Poster', ['controller' => 'Posters', 'action' => 'add']) ?>
                        <?= $this->Html->link('Manage Users', ['controller' => 'Users', 'action' => 'index']) ?>
                    <?php endif; ?>
                </td>
                <td id="divider_content_right">
                    <?php if (empty($auth)): ?>
                        <?= $this->Html->link('Login', ['controller' => 'Users', 'action' => 'login']) ?>
                    <?php else: ?>
                        <?php if ($auth && $group <= GroupsTable::ADMIN): ?>
                            <?= $this->Html->link('Settings', ['controller' => 'Settings', 'action' => 'index']) ?>
                        <?php endif; ?>
                        <?php if ($auth && $group > GroupsTable::USER): ?>
                            <?= $this->Html->link('Change Password', ['controller' => 'Users', 'action' => 'password']) ?>
                        <?php endif; ?>
                        <?= $this->Html->link('Logout', ['controller' => 'Users', 'action' => 'logout']) ?>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </div>
    <div id="container">
        <div id="content">
            <?= $this->Flash->render() ?>
            <?= $this->Flash->render('auth') ?>

            <?= $this->fetch('content') ?>
        </div>
    </div>

    <script type="text/javascript">
    $(document).ready(function(){
        $('.corner').corner();
    });
    </script>
</body>
</html>
