<?php
/**
 * Poster Form Layout
 *
 * @var \App\View\AppView $this
 */

$title = $this->fetch('title') ?? 'Poster Mountain';
$metaDescription = $this->get('metaDescription');
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <title>
        <?= __('Poster Mountain:') ?>
        <?= $title ?>
    </title>

    <?php if (!empty($metaDescription)): ?>
        <meta name="description" content="<?= h($metaDescription) ?>" />
    <?php endif; ?>

    <?php
    // JavaScript files
    echo $this->Html->script([
        'jquery/jquery-1.3.2.min',
        'jquery/jquery.form',
        'jquery/jquery.jeditable.mini',
        'jquery/jquery-ui-1.7.2.custom.min',
        'jquery/jquery.tools.min',
        'jquery/jquery.corner'
    ]);
    
    // CSS files
    echo $this->Html->css('formatted');
    
    echo $this->fetch('script');
    ?>
</head>
<body>
    <div id="container">
        <?= $this->Flash->render() ?>
        <?= $this->Flash->render('auth') ?>

        <?= $this->fetch('content') ?>
    </div>
</body>
</html>

