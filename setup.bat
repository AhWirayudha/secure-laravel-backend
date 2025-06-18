@echo off
echo ================================================
echo    Secure Laravel Backend - Windows Setup
echo ================================================
echo.

REM Check if PHP is installed
php --version >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] PHP is not installed or not in PATH
    echo Please install PHP 8.2+ and add it to your PATH
    echo See docs/WINDOWS_SETUP.md for installation instructions
    pause
    exit /b 1
)

REM Check if Composer is installed
composer --version >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] Composer is not installed or not in PATH
    echo Please install Composer and add it to your PATH
    echo See docs/WINDOWS_SETUP.md for installation instructions
    pause
    exit /b 1
)

echo [OK] PHP and Composer are installed
echo.

REM Install dependencies
echo Installing PHP dependencies...
composer install
if %errorlevel% neq 0 (
    echo [ERROR] Failed to install dependencies
    pause
    exit /b 1
)

REM Copy environment file
if not exist .env (
    echo Copying environment file...
    copy .env.example .env
    echo [INFO] Please configure your database settings in .env
    echo.
)

REM Generate application key
echo Generating application key...
php artisan key:generate
if %errorlevel% neq 0 (
    echo [ERROR] Failed to generate application key
    pause
    exit /b 1
)

echo.
echo ================================================
echo                Setup Complete!
echo ================================================
echo.
echo Next steps:
echo 1. Configure your database settings in .env
echo 2. Run: php artisan migrate --seed
echo 3. Run: php artisan passport:install
echo 4. Run: php artisan serve
echo.
echo For detailed instructions, see docs/WINDOWS_SETUP.md
echo.
pause
