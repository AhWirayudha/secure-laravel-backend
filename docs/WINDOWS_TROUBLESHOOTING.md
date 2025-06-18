# Windows Troubleshooting Guide

This guide addresses common issues when setting up the Secure Laravel Backend on Windows.

## Common Installation Issues

### 1. PHP Not Found

**Error:** `'php' is not recognized as an internal or external command`

**Solutions:**

**Option A: Add PHP to PATH**
1. Find your PHP installation directory (usually `C:\php`)
2. Press `Win + R`, type `sysdm.cpl`, press Enter
3. Click "Environment Variables"
4. Under "System Variables", find and select "Path", then click "Edit"
5. Click "New" and add your PHP directory path
6. Click "OK" to save and restart PowerShell

**Option B: Use full path**
```powershell
C:\php\php.exe --version
```

### 2. Composer Not Found

**Error:** `'composer' is not recognized as an internal or external command`

**Solutions:**

**Option A: Reinstall Composer**
1. Download the installer from [getcomposer.org](https://getcomposer.org/download/)
2. Run as Administrator
3. Ensure "Add to PATH" is checked during installation

**Option B: Use full path**
```powershell
php C:\composer\composer.phar install
```

### 3. PHP Extensions Missing

**Error:** `PHP extension ... is not loaded`

**Solution:**
1. Open `C:\php\php.ini` (copy from `php.ini-development` if it doesn't exist)
2. Find the extension lines and uncomment them:
   ```ini
   extension=curl
   extension=fileinfo
   extension=gd
   extension=mbstring
   extension=openssl
   extension=pdo_mysql
   extension=pdo_pgsql
   extension=redis
   extension=zip
   ```
3. Restart your web server/command prompt

### 4. Memory Limit Issues

**Error:** `Fatal error: Allowed memory size exhausted`

**Solutions:**

**Option A: Increase PHP memory limit**
Edit `php.ini`:
```ini
memory_limit = 2G
```

**Option B: Temporary fix**
```powershell
php -d memory_limit=2G artisan serve
```

### 5. Permission Issues

**Error:** `Permission denied` or `Access is denied`

**Solutions:**

**Option A: Run as Administrator**
1. Right-click PowerShell
2. Select "Run as Administrator"

**Option B: Check file permissions**
1. Right-click the project folder
2. Properties → Security
3. Ensure your user has Full Control

### 6. Port Already in Use

**Error:** `Address already in use` or port 8000 is busy

**Solutions:**

**Option A: Use different port**
```powershell
php artisan serve --port=8080
```

**Option B: Find what's using the port**
```powershell
netstat -ano | findstr :8000
# Kill the process if needed
taskkill /PID <process_id> /F
```

### 7. Database Connection Issues

**Error:** `SQLSTATE[HY000] [2002] Connection refused`

**Solutions:**

**Check database service:**
```powershell
# For PostgreSQL
Get-Service postgresql*

# For MySQL
Get-Service mysql*
```

**Start the service if stopped:**
```powershell
# PostgreSQL
Start-Service postgresql-x64-14  # adjust version number

# MySQL
Start-Service mysql80  # adjust version number
```

### 8. Redis Connection Issues

**Error:** `Connection refused` when connecting to Redis

**Solutions:**

**Option A: Start Redis with Docker**
```powershell
docker run -d --name redis -p 6379:6379 redis:alpine
```

**Option B: Install Redis for Windows**
1. Download from [GitHub releases](https://github.com/microsoftarchive/redis/releases)
2. Extract and run `redis-server.exe`

**Option C: Disable Redis temporarily**
Edit `.env`:
```env
CACHE_DRIVER=file
QUEUE_CONNECTION=database
SESSION_DRIVER=file
```

### 9. Artisan Commands Not Working

**Error:** Various artisan command failures

**Solutions:**

**Check PHP CLI:**
```powershell
php artisan --version
```

**Clear caches:**
```powershell
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

**Regenerate autoload:**
```powershell
composer dump-autoload
```

### 10. SSL/TLS Issues

**Error:** SSL certificate or HTTPS issues

**Solutions:**

**Disable SSL verification (development only):**
```powershell
composer config --global disable-tls true
composer config --global secure-http false
```

**Or set environment variable:**
```powershell
$env:COMPOSER_DISABLE_TLS=1
composer install
```

## Performance Optimization

### 1. Enable OPcache

Add to `php.ini`:
```ini
opcache.enable=1
opcache.enable_cli=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=4000
opcache.revalidate_freq=2
```

### 2. Increase PHP Limits

Add to `php.ini`:
```ini
max_execution_time = 300
max_input_vars = 3000
post_max_size = 32M
upload_max_filesize = 32M
```

## Getting Help

If you're still experiencing issues:

1. **Check Laravel logs:** `storage/logs/laravel.log`
2. **Enable debug mode:** Set `APP_DEBUG=true` in `.env`
3. **Check PHP error log:** Usually in `C:\php\logs\php_errors.log`
4. **Verify PHP configuration:** Run `php -i` or create a `phpinfo()` page

## Useful Commands for Debugging

```powershell
# Check PHP configuration
php -i

# List loaded extensions
php -m

# Check Laravel environment
php artisan env

# Check database connection
php artisan tinker
# Then: DB::connection()->getPdo();

# Check if routes are loaded
php artisan route:list

# Check configuration
php artisan config:show
```
