# Windows Setup Guide

This guide will help you set up the Secure Laravel Backend on Windows.

## Prerequisites Installation

### 1. Install PHP 8.2+

**Option A: Download from php.net (Recommended)**
1. Go to [https://www.php.net/downloads.php](https://www.php.net/downloads.php)
2. Download "Thread Safe" version for your architecture (x64 for most systems)
3. Extract to `C:\php`
4. Add `C:\php` to your system PATH:
   - Press `Win + X` and select "System"
   - Click "Advanced system settings"
   - Click "Environment Variables"
   - Under "System Variables", find "Path" and click "Edit"
   - Click "New" and add `C:\php`
   - Click "OK" to save

**Option B: Using Chocolatey**
```powershell
# Install Chocolatey first if you don't have it
Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://community.chocolatey.org/install.ps1'))

# Install PHP
choco install php
```

### 2. Configure PHP

1. Copy `C:\php\php.ini-development` to `C:\php\php.ini`
2. Edit `php.ini` and uncomment/enable these extensions:
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

### 3. Install Composer

**Option A: Download installer**
1. Go to [https://getcomposer.org/download/](https://getcomposer.org/download/)
2. Download and run `Composer-Setup.exe`
3. Follow the installation wizard

**Option B: Using Chocolatey**
```powershell
choco install composer
```

### 4. Install Redis

**Option A: Using Docker (Recommended)**
```powershell
# Install Docker Desktop first from https://www.docker.com/products/docker-desktop
docker run -d --name redis -p 6379:6379 redis:alpine
```

**Option B: Windows Release**
1. Download from [https://github.com/microsoftarchive/redis/releases](https://github.com/microsoftarchive/redis/releases)
2. Extract and run `redis-server.exe`

### 5. Install Database

**PostgreSQL (Recommended)**
1. Download from [https://www.postgresql.org/download/windows/](https://www.postgresql.org/download/windows/)
2. Run the installer and follow the setup wizard
3. Remember your password for the `postgres` user

**MySQL Alternative**
1. Download from [https://dev.mysql.com/downloads/installer/](https://dev.mysql.com/downloads/installer/)
2. Run the installer and follow the setup wizard

## Verify Installation

Open PowerShell as Administrator and run:

```powershell
php --version
composer --version
```

You should see version information for both PHP and Composer.

## Project Setup

1. **Clone or navigate to the project directory:**
   ```powershell
   cd "C:\Users\AH Wirayudha\workspace\secure-laravel-backend"
   ```

2. **Install PHP dependencies:**
   ```powershell
   composer install
   ```

3. **Set up environment file:**
   ```powershell
   copy .env.example .env
   ```

4. **Generate application key:**
   ```powershell
   php artisan key:generate
   ```

5. **Configure your database in `.env`:**
   ```env
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=secure_laravel
   DB_USERNAME=postgres
   DB_PASSWORD=your_password_here
   ```

6. **Run migrations and seeders:**
   ```powershell
   php artisan migrate --seed
   ```

7. **Install Passport:**
   ```powershell
   php artisan passport:install
   ```

8. **Start the development server:**
   ```powershell
   php artisan serve
   ```

Your API will be available at `http://localhost:8000`

## Testing the Setup

1. **Health Check:**
   ```powershell
   curl http://localhost:8000/api/health
   ```

2. **Run Tests:**
   ```powershell
   php artisan test
   ```

## Common Issues

### PHP Extensions Missing
If you get errors about missing extensions, ensure they are enabled in `php.ini`:
```ini
extension=curl
extension=mbstring
extension=openssl
extension=pdo_pgsql
```

### Composer Memory Issues
If Composer runs out of memory:
```powershell
php -d memory_limit=2G C:\composer\composer.phar install
```

### Port Already in Use
If port 8000 is busy, use a different port:
```powershell
php artisan serve --port=8080
```

## Using Docker Alternative

If you prefer using Docker on Windows:

1. **Install Docker Desktop** from [https://www.docker.com/products/docker-desktop](https://www.docker.com/products/docker-desktop)

2. **Run the project with Docker:**
   ```powershell
   docker-compose up -d
   ```

3. **Access the container:**
   ```powershell
   docker-compose exec app bash
   ```

This will give you a Linux environment where all dependencies are pre-installed.
