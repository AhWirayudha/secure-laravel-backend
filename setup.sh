#!/bin/bash

# Secure Laravel Backend Setup Script
# This script sets up the Laravel backend with all security features

echo "🚀 Setting up Secure Laravel Backend..."

# Check if composer is installed
if ! command -v composer &> /dev/null; then
    echo "❌ Composer is not installed. Please install Composer first."
    exit 1
fi

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    echo "❌ Docker is not installed. Please install Docker first."
    exit 1
fi

# Install PHP dependencies
echo "📦 Installing PHP dependencies..."
composer install

# Copy environment file
if [ ! -f .env ]; then
    echo "🔧 Setting up environment file..."
    cp .env.example .env
fi

# Generate application key
echo "🔑 Generating application key..."
php artisan key:generate

# Set permissions
echo "🔐 Setting up permissions..."
chmod -R 775 storage bootstrap/cache

# Start Docker containers
echo "🐳 Starting Docker containers..."
docker-compose up -d

# Wait for database to be ready
echo "⏳ Waiting for database to be ready..."
sleep 30

# Run migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# Install Passport
echo "🔐 Installing Laravel Passport..."
php artisan passport:install --force

# Seed the database
echo "🌱 Seeding database..."
php artisan db:seed --force

# Install and setup Laravel Telescope
echo "🔭 Setting up Laravel Telescope..."
php artisan telescope:install
php artisan migrate --force

# Clear and cache configuration
echo "🧹 Clearing and caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Install Node.js dependencies (if package.json exists)
if [ -f package.json ]; then
    echo "📦 Installing Node.js dependencies..."
    npm install
fi

echo "✅ Setup completed successfully!"
echo ""
echo "🌐 Your Laravel backend is now running at: http://localhost"
echo "📚 API Documentation: http://localhost/api/documentation"
echo "🔍 Telescope Dashboard: http://localhost/telescope"
echo "📊 Health Check: http://localhost/api/health"
echo ""
echo "👥 Default Users:"
echo "   Super Admin: super@admin.com / SuperAdmin123!"
echo "   Admin: admin@admin.com / Admin123!"
echo "   User: user@example.com / User123!"
echo ""
echo "🔧 To stop the application: docker-compose down"
echo "🔧 To restart the application: docker-compose up -d"
