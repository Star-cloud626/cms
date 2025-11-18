# Install Authentication and Authorization Plugins

## Quick Install

Run these commands in your terminal:

```bash
cd cms
composer require cakephp/authentication
composer require cakephp/authorization
```

Or install both at once:

```bash
cd cms
composer require cakephp/authentication cakephp/authorization
```

## What This Does

- Installs the `cakephp/authentication` plugin (v3.0.0 or higher)
- Installs the `cakephp/authorization` plugin (v3.0.0 or higher)
- Updates `composer.json` and `composer.lock`
- Downloads dependencies to `vendor/` directory

## After Installation

1. Clear the cache:
   ```bash
   # Windows PowerShell
   Remove-Item -Recurse -Force tmp\cache\*
   
   # Linux/Mac
   rm -rf tmp/cache/*
   ```

2. The application should now work with authentication!

## Troubleshooting

### "composer: command not found"
- Install Composer first: https://getcomposer.org/download/

### "Class not found" errors after installation
- Clear cache: `rm -rf tmp/cache/*`
- Run: `composer dump-autoload`

### Still getting authentication errors
- Make sure you're in the `cms` directory when running composer
- Check that `vendor/cakephp/authentication` directory exists
- Verify `composer.json` includes the plugins in `require` section

