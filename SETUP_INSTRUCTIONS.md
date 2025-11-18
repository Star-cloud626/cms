# Setup Instructions for CakePHP 5.0 Migration

## Step 1: Install Dependencies

First, you need to install the Authentication and Authorization plugins:

```bash
cd cms
composer install
```

This will install:
- `cakephp/authentication` - For user authentication
- `cakephp/authorization` - For authorization/permissions

## Step 2: Verify Plugin Installation

After running `composer install`, verify that the plugins are installed:

```bash
ls vendor/cakephp/authentication
ls vendor/cakephp/authorization
```

## Step 3: Clear Cache

Clear the CakePHP cache:

```bash
# On Windows (PowerShell)
Remove-Item -Recurse -Force tmp\cache\*

# On Linux/Mac
rm -rf tmp/cache/*
```

## Step 4: Database Setup

The database configuration is already set in `config/app_local.php`. Make sure:
- The database `postermo_forma` exists
- The user `postermo_forma` has access
- All tables from the old database are present

## Step 5: Test the Application

1. Start your web server
2. Navigate to `http://localhost/cms`
3. You should see the Posters index page

## Common Issues

### "Authentication plugin not found"
- Make sure you ran `composer install`
- Check that `vendor/cakephp/authentication` exists
- Clear cache: `rm -rf tmp/cache/*`

### "Class not found" errors
- Run `composer dump-autoload`
- Clear cache

### Database connection errors
- Check `config/app_local.php` database settings
- Verify database credentials
- Ensure MySQL/MariaDB is running

## Next Steps

After setup is complete, you'll need to:
1. Migrate remaining controllers (Images, Comments, Tags, etc.)
2. Migrate views from `.ctp` to new template format
3. Copy assets (CSS, JS, images) to `webroot/`
4. Test all functionality

## Password Migration Note

**Important**: The old CakePHP 1.2 used a different password hashing method. You may need to:
1. Reset all user passwords, OR
2. Create a custom password hasher that supports both old and new formats during migration

See `src/Model/Table/UsersTable.php` for password hashing implementation.

