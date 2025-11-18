# View Migration Guide

## Overview

This guide helps you migrate all remaining views from CakePHP 1.2 format (`.ctp`) to CakePHP 5.0 format (`.php`).

## Key Changes

### 1. File Location
- **Old**: `form/app/views/{controller}/{action}.ctp`
- **New**: `cms/templates/{Controller}/{action}.php`

### 2. Helper Calls
- **Old**: `$html->link()`, `$form->input()`, `$session->flash()`
- **New**: `$this->Html->link()`, `$this->Form->control()`, `$this->Flash->render()`

### 3. Data Access
- **Old**: `$poster['Poster']['title']`
- **New**: `$poster->title` (entity object)

### 4. Variables
- **Old**: `$title_for_layout`, `$content_for_layout`, `$scripts_for_layout`
- **New**: `$this->fetch('title')`, `$this->fetch('content')`, `$this->fetch('script')`

### 5. Form Helpers
- **Old**: `$form->create()`, `$form->input()`, `$form->end()`
- **New**: `$this->Form->create()`, `$this->Form->control()`, `$this->Form->end()`

### 6. Pagination
- **Old**: `$paginator->prev()`, `$paginator->numbers()`
- **New**: `$this->Paginator->prev()`, `$this->Paginator->numbers()`

## Migration Checklist

### Layouts
- [x] `default.php` - Migrated
- [ ] `poster_form.php` - Needs migration
- [ ] `pdf.php` - Needs migration

### Posters Views
- [x] `index.php` - Migrated
- [x] `view.php` - Migrated
- [ ] `add.php` - Needs migration
- [ ] `edit.php` - Needs migration
- [ ] `detail.php` - Needs migration
- [ ] `formatted.php` - Needs migration
- [ ] `pdf.php` - Needs migration
- [ ] `notify.php` - Needs migration

### Users Views
- [x] `login.php` - Migrated
- [ ] `index.php` - Needs migration
- [ ] `add.php` - Needs migration
- [ ] `edit.php` - Needs migration
- [ ] `view.php` - Needs migration
- [ ] `register.php` - Needs migration
- [ ] `password.php` - Needs migration

### Images Views
- [ ] `index.php` - Needs migration
- [ ] `add.php` - Needs migration
- [ ] `add_multiple.php` - Needs migration
- [ ] `edit.php` - Needs migration
- [ ] `view.php` - Needs migration
- [ ] `full.php` - Needs migration

### Comments Views
- [ ] `index.php` - Needs migration
- [ ] `add.php` - Needs migration
- [ ] `edit.php` - Needs migration

### Clients Views
- [ ] `index.php` - Needs migration
- [ ] `add.php` - Needs migration
- [ ] `edit.php` - Needs migration
- [ ] `view.php` - Needs migration

### Tags Views
- [ ] `index.php` - Needs migration
- [ ] `add.php` - Needs migration
- [ ] `edit.php` - Needs migration

### Groups Views
- [ ] `index.php` - Needs migration
- [ ] `add.php` - Needs migration
- [ ] `edit.php` - Needs migration
- [ ] `view.php` - Needs migration

### Settings Views
- [ ] `index.php` - Needs migration
- [ ] `edit.php` - Needs migration
- [ ] `pdf.php` - Needs migration

## Common Patterns

### Form Creation
```php
// Old
<?php echo $form->create('User', array('url' => array('controller' => 'users', 'action' => 'login'))); ?>

// New
<?= $this->Form->create(null, ['url' => ['controller' => 'Users', 'action' => 'login']]) ?>
```

### Form Inputs
```php
// Old
<?php echo $form->input('User.username'); ?>

// New
<?= $this->Form->control('username', ['label' => 'Username']) ?>
```

### Links
```php
// Old
<?php echo $html->link('View', array('action' => 'view', $id)); ?>

// New
<?= $this->Html->link('View', ['action' => 'view', $id]) ?>
```

### Images
```php
// Old
<?php echo $html->image('path/to/image.jpg'); ?>

// New
<?= $this->Html->image('path/to/image.jpg') ?>
```

### Loops
```php
// Old
<?php foreach ($posters as $poster): ?>
    <?php echo $poster['Poster']['title']; ?>
<?php endforeach; ?>

// New
<?php foreach ($posters as $poster): ?>
    <?= h($poster->title) ?>
<?php endforeach; ?>
```

### Conditions
```php
// Old
<?php if (!empty($auth)): ?>
    <?php echo $auth['User']['username']; ?>
<?php endif; ?>

// New
<?php if (!empty($auth)): ?>
    <?= h($auth->username) ?>
<?php endif; ?>
```

## Next Steps

1. Copy all CSS, JS, and image files from `form/app/webroot/` to `cms/webroot/`
2. Migrate remaining views using the patterns above
3. Test each view after migration
4. Update any JavaScript that references old data structures

