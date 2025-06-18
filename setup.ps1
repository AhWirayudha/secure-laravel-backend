# Secure Laravel Backend Setup Script (PowerShell)
# This script sets up the Laravel backend with all security features

param(
    [Parameter(Mandatory=$false)]
    [ValidateSet("docker", "local")]
    [string]$SetupType = "docker"
)

Write-Host "🚀 Setting up Secure Laravel Backend ($SetupType mode)..." -ForegroundColor Green

# Check if composer is installed
if (!(Get-Command composer -ErrorAction SilentlyContinue)) {
    Write-Host "❌ Composer is not installed. Please install Composer first." -ForegroundColor Red
    exit 1
}

# Install PHP dependencies
Write-Host "📦 Installing PHP dependencies..." -ForegroundColor Yellow
composer install

# Copy environment file
if (!(Test-Path .env)) {
    Write-Host "🔧 Setting up environment file..." -ForegroundColor Yellow
    Copy-Item .env.example .env
}

# Generate application key
Write-Host "🔑 Generating application key..." -ForegroundColor Yellow
php artisan key:generate

if ($SetupType -eq "docker") {
    # Check if Docker is installed
    if (!(Get-Command docker -ErrorAction SilentlyContinue)) {
        Write-Host "❌ Docker is not installed. Please install Docker first or use -SetupType local." -ForegroundColor Red
        exit 1
    }

    # Start Docker containers
    Write-Host "🐳 Starting Docker containers..." -ForegroundColor Yellow
    docker-compose up -d

    # Wait for database to be ready
    Write-Host "⏳ Waiting for database to be ready..." -ForegroundColor Yellow
    Start-Sleep -Seconds 30
} else {
    Write-Host "📝 Please configure your local database and Redis in .env file" -ForegroundColor Yellow
    Write-Host "   Example configuration:" -ForegroundColor White
    Write-Host "   DB_CONNECTION=mysql" -ForegroundColor Gray
    Write-Host "   DB_HOST=127.0.0.1" -ForegroundColor Gray
    Write-Host "   DB_DATABASE=secure_backend" -ForegroundColor Gray
    Write-Host "   REDIS_HOST=127.0.0.1" -ForegroundColor Gray
    Write-Host ""
    Write-Host "Press any key to continue after configuring your .env file..." -ForegroundColor Yellow
    Read-Host
}

# Run migrations
Write-Host "🗄️ Running database migrations..." -ForegroundColor Yellow
php artisan migrate --force

# Install Passport
Write-Host "🔐 Installing Laravel Passport..." -ForegroundColor Yellow
php artisan passport:install --force

# Seed the database
Write-Host "🌱 Seeding database..." -ForegroundColor Yellow
php artisan db:seed --force

# Install and setup Laravel Telescope
Write-Host "🔭 Setting up Laravel Telescope..." -ForegroundColor Yellow
php artisan telescope:install
php artisan migrate --force

# Clear and cache configuration
Write-Host "🧹 Clearing and caching configuration..." -ForegroundColor Yellow
php artisan config:cache
php artisan route:cache
php artisan view:cache

Write-Host "✅ Setup completed successfully!" -ForegroundColor Green
Write-Host ""

if ($SetupType -eq "docker") {
    Write-Host "🌐 Your Laravel backend is now running at: http://localhost" -ForegroundColor Cyan
} else {
    Write-Host "🌐 Start your Laravel backend with: php artisan serve" -ForegroundColor Cyan
    Write-Host "   Then access at: http://localhost:8000" -ForegroundColor Cyan
}

Write-Host "📚 API Documentation: http://localhost/api/documentation" -ForegroundColor Cyan
Write-Host "🔍 Telescope Dashboard: http://localhost/telescope" -ForegroundColor Cyan
Write-Host "📊 Health Check: http://localhost/api/health" -ForegroundColor Cyan
Write-Host ""
Write-Host "👥 Default Users:" -ForegroundColor Yellow
Write-Host "   Super Admin: super@admin.com / SuperAdmin123!" -ForegroundColor White
Write-Host "   Admin: admin@admin.com / Admin123!" -ForegroundColor White
Write-Host "   User: user@example.com / User123!" -ForegroundColor White
Write-Host ""

if ($SetupType -eq "docker") {
    Write-Host "🔧 To stop the application: docker-compose down" -ForegroundColor Magenta
    Write-Host "🔧 To restart the application: docker-compose up -d" -ForegroundColor Magenta
} else {
    Write-Host "🔧 To start the application: php artisan serve" -ForegroundColor Magenta
    Write-Host "🔧 For production: configure a web server (Nginx/Apache)" -ForegroundColor Magenta
}
