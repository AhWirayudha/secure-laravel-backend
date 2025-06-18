# 🚀 Quick Start Guide

## Prerequisites

- **PHP 8.2+** with extensions: mbstring, xml, ctype, iconv, intl, pdo_mysql, redis
- **Composer** (latest version)
- **Docker & Docker Compose** (for containerized setup)
- **Node.js 16+** and npm (for frontend assets)
- **Git** (for version control)

## Installation Methods

### Method 1: Automated Setup (Recommended)

#### For Windows (PowerShell):
```powershell
# Run as Administrator
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
.\setup.ps1
```

#### For macOS/Linux (Bash):
```bash
chmod +x setup.sh
./setup.sh
```

### Method 2: Manual Setup

1. **Clone and Install Dependencies**
   ```bash
   git clone <repository-url> secure-laravel-backend
   cd secure-laravel-backend
   composer install
   npm install
   ```

2. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Docker Setup**
   ```bash
   docker-compose up -d
   # Wait 30 seconds for database initialization
   ```

4. **Database Setup**
   ```bash
   php artisan migrate --seed
   php artisan passport:install
   ```

5. **Final Configuration**
   ```bash
   php artisan telescope:install
   php artisan config:cache
   php artisan route:cache
   ```

## Quick Test

After installation, test your setup:

```bash
# Health check
curl http://localhost/api/health

# Register a test user
curl -X POST http://localhost/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "TestPassword123!",
    "password_confirmation": "TestPassword123!"
  }'
```

## Default Access

| Service | URL | Credentials |
|---------|-----|-------------|
| API | http://localhost/api/v1 | - |
| Telescope | http://localhost/telescope | - |
| Mailpit | http://localhost:8025 | - |
| Super Admin | - | super@admin.com / SuperAdmin123! |
| Admin | - | admin@admin.com / Admin123! |
| User | - | user@example.com / User123! |

## Development Commands

```bash
# Start development server
php artisan serve

# Run tests
php artisan test
vendor/bin/phpunit

# Code quality
./vendor/bin/pint
composer audit

# Queue worker
php artisan queue:work

# Schedule runner
php artisan schedule:work
```

## Production Deployment

1. **Environment Setup**
   ```bash
   APP_ENV=production
   APP_DEBUG=false
   ```

2. **Security Checklist**
   - [ ] Set strong database passwords
   - [ ] Configure SSL certificates
   - [ ] Set up proper CORS origins
   - [ ] Configure monitoring (Sentry)
   - [ ] Set up backup strategy
   - [ ] Configure queue workers with Supervisor

3. **Performance Optimization**
   ```bash
   php artisan optimize
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

## Troubleshooting

### Common Issues

1. **Permission Errors**
   ```bash
   sudo chown -R $USER:$USER storage bootstrap/cache
   chmod -R 775 storage bootstrap/cache
   ```

2. **Database Connection Issues**
   ```bash
   # Check Docker containers
   docker-compose ps
   
   # View logs
   docker-compose logs mysql
   ```

3. **Passport Issues**
   ```bash
   php artisan passport:keys
   php artisan passport:client --personal
   ```

### Support

- 📖 [Full Documentation](./docs/API.md)
- 🐛 [Issue Tracker](https://github.com/your-repo/issues)
- 💬 [Discussions](https://github.com/your-repo/discussions)

---

🎉 **Your secure Laravel backend is ready!** 

Visit http://localhost to get started.
