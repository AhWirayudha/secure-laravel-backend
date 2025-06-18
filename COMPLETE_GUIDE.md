# 📚 Complete Project Guide - Secure Laravel Backend

## 🎯 Project Overview

This is a **production-ready, secure Laravel backend** with **modular architecture** following OWASP security best practices. The project implements a clean, scalable structure perfect for API-first applications.

### 🔑 Key Features
- ✅ **Modular Architecture** - Organized in feature modules
- ✅ **Security-First** - OWASP Top 10 protection
- ✅ **OAuth2 Authentication** - Laravel Passport implementation
- ✅ **Role-Based Access Control** - Spatie permissions
- ✅ **Rate Limiting & Throttling** - Redis-backed
- ✅ **Comprehensive Testing** - PHPUnit with feature tests
- ✅ **Docker Support** - Full containerization
- ✅ **CI/CD Ready** - GitHub Actions pipeline

---

## 📁 Project Structure Deep Dive

```
secure-laravel-backend/
├── 🏗️ app/
│   ├── 📦 Modules/                    # MODULAR ARCHITECTURE
│   │   ├── 🔐 Auth/                   # Authentication Module
│   │   │   ├── Controllers/           # AuthController
│   │   │   ├── Requests/             # LoginRequest, RegisterRequest
│   │   │   └── routes.php            # Auth-specific routes
│   │   ├── 👤 User/                   # User Management Module
│   │   │   ├── Controllers/           # UserController
│   │   │   ├── Models/               # User model (modular)
│   │   │   ├── Requests/             # CRUD validation
│   │   │   ├── Resources/            # API response formatting
│   │   │   ├── Services/             # Business logic
│   │   │   ├── Repositories/         # Data access layer
│   │   │   └── routes.php            # User routes
│   │   └── 📊 MasterData/             # Master Data Module
│   │       ├── Controllers/           # Role & Permission controllers
│   │       ├── Models/               # Role, Permission models
│   │       ├── Resources/            # API resources
│   │       ├── Services/             # Master data services
│   │       └── routes.php            # Master data routes
│   ├── 🛡️ Http/
│   │   ├── Controllers/              # Base controllers
│   │   │   └── HealthCheckController.php
│   │   ├── Middleware/               # Security middleware
│   │   │   ├── SecurityHeaders.php   # OWASP headers
│   │   │   ├── RateLimitMiddleware.php
│   │   │   ├── ApiVersionMiddleware.php
│   │   │   └── ApiLoggingMiddleware.php
│   │   └── Kernel.php                # Middleware registration
│   ├── 🔧 Console/Commands/           # Artisan commands
│   │   ├── GenerateApiDocs.php       # Auto-generate API docs
│   │   ├── SystemCleanup.php         # Maintenance tasks
│   │   └── SecurityAudit.php         # Security audit
│   ├── 🎛️ Providers/
│   │   └── ModuleServiceProvider.php  # Module auto-loading
│   └── 🆘 Support/
│       └── helpers.php               # Helper functions & aliases
├── ⚙️ config/                        # Laravel configurations
│   ├── security.php                 # Custom security config
│   ├── api.php                      # API-specific settings
│   └── ... (all Laravel configs)
├── 🗄️ database/
│   ├── migrations/                  # Database schema
│   └── seeders/                     # Sample data
├── 🧪 tests/                        # Comprehensive testing
│   └── Feature/                     # API & integration tests
├── 📚 docs/                         # Documentation
│   ├── API.md                       # API documentation
│   ├── WINDOWS_SETUP.md            # Windows setup guide
│   └── WINDOWS_TROUBLESHOOTING.md  # Troubleshooting
└── 🚀 DevOps Files
    ├── docker-compose.yml           # Container orchestration
    ├── Dockerfile                   # App containerization
    ├── setup.bat / setup.ps1        # Windows setup scripts
    └── .github/workflows/           # CI/CD pipeline
```

---

## 🏗️ Modular Architecture Explained

### Why Modular?
- **🔄 Scalability** - Add new modules without affecting existing code
- **🧩 Maintainability** - Each module is self-contained
- **👥 Team Collaboration** - Teams can work on different modules
- **🧪 Testing** - Isolated testing per module
- **📦 Reusability** - Modules can be reused across projects

### Module Structure Pattern
Each module follows this consistent structure:
```
ModuleName/
├── Controllers/     # Handle HTTP requests
├── Models/          # Database models (optional)
├── Requests/        # Form validation
├── Resources/       # API response formatting
├── Services/        # Business logic
├── Repositories/    # Data access (optional)
└── routes.php       # Module-specific routes
```

---

## 🔐 Security Features Deep Dive

### 1. Authentication & Authorization
```php
// OAuth2 with Laravel Passport
POST /api/v1/auth/login     # Get access token
POST /api/v1/auth/register  # User registration
GET  /api/v1/auth/user      # Get authenticated user
POST /api/v1/auth/logout    # Revoke token
```

### 2. Security Headers
All API responses include OWASP security headers:
- `X-Frame-Options: DENY`
- `X-Content-Type-Options: nosniff`
- `X-XSS-Protection: 1; mode=block`
- `Referrer-Policy: strict-origin-when-cross-origin`
- `Strict-Transport-Security` (HTTPS only)

### 3. Rate Limiting
```php
// Authentication endpoints: 5 requests/minute
// API endpoints: 100 requests/minute
// Configurable per environment
```

### 4. Input Validation
Every endpoint has dedicated Request classes:
```php
app/Modules/User/Requests/
├── CreateUserRequest.php   # User creation validation
├── UpdateUserRequest.php   # User update validation
└── UserFilterRequest.php   # Search/filter validation
```

---

## 📡 API Endpoints Guide

### Health Check
```bash
GET /api/health           # Basic health check
GET /api/health/detailed  # Detailed system status
```

### Authentication
```bash
POST /api/v1/auth/register  # Register new user
POST /api/v1/auth/login     # User login
GET  /api/v1/auth/user      # Get current user
POST /api/v1/auth/logout    # Logout user
POST /api/v1/auth/refresh   # Refresh token
```

### User Management (Protected)
```bash
GET    /api/v1/users        # List users (paginated)
POST   /api/v1/users        # Create user
GET    /api/v1/users/{id}   # Show user
PUT    /api/v1/users/{id}   # Update user
DELETE /api/v1/users/{id}   # Delete user
```

### Master Data
```bash
GET /api/v1/master-data/roles       # List all roles
GET /api/v1/master-data/permissions # List all permissions
```

---

## 🧪 Testing Strategy

### Test Structure
```
tests/Feature/
├── HealthCheckTest.php        # Health endpoint tests
├── ApiIntegrationTest.php     # Full API workflow tests
└── Modules/
    └── User/
        └── UserControllerTest.php  # User module tests
```

### Running Tests
```bash
# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage

# Run specific test suite
php artisan test --testsuite=Feature

# Run specific test
php artisan test --filter=UserControllerTest
```

---

## 🛠️ Custom Artisan Commands

### Security & Maintenance
```bash
# Run security audit
php artisan security:audit

# System cleanup (logs, cache, temp files)
php artisan system:cleanup --all

# Generate API documentation
php artisan api:docs

# Available composer scripts
composer run test           # Run tests
composer run setup          # Complete setup
composer run fresh          # Fresh DB with seeds
composer run cleanup        # System cleanup
```

---

## 🎛️ Configuration Files

### Security Configuration (`config/security.php`)
```php
return [
    'rate_limiting' => [
        'enabled' => true,
        'auth_attempts' => 5,      # Auth endpoint limits
        'api_requests' => 100,     # API endpoint limits
    ],
    'headers' => [
        'X-Frame-Options' => 'DENY',
        'X-Content-Type-Options' => 'nosniff',
        // ... more security headers
    ],
    'cors' => [
        'allowed_origins' => ['https://yourdomain.com'],
    ],
];
```

### API Configuration (`config/api.php`)
```php
return [
    'version' => 'v1',
    'pagination' => [
        'per_page' => 15,
        'max_per_page' => 100,
    ],
    'cache_ttl' => 3600,
];
```

---

## 🐳 Docker Usage

### Development Setup
```bash
# Start all services
docker-compose up -d

# View logs
docker-compose logs -f app

# Access app container
docker-compose exec app bash

# Run artisan commands in container
docker-compose exec app php artisan migrate
```

### Services Included
- **app** - Laravel application
- **postgres** - PostgreSQL database
- **redis** - Redis for caching/queues
- **nginx** - Web server (optional)

---

## 🚀 Deployment Guide

### Pre-deployment Checklist
```bash
# 1. Run security audit
php artisan security:audit

# 2. Run all tests
php artisan test

# 3. Check environment config
php artisan config:show

# 4. Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Environment Setup
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=pgsql
DB_HOST=your-db-host
DB_DATABASE=your-db-name
DB_USERNAME=your-db-user
DB_PASSWORD=your-secure-password

# Redis
REDIS_HOST=your-redis-host
REDIS_PASSWORD=your-redis-password

# Security
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=strict
```

---

## 📈 Monitoring & Logging

### Log Channels
```php
// Security events
Log::channel('security')->info('User login attempt', $data);

// API requests
Log::channel('api')->info('API request', $requestData);

// Application logs
Log::info('General application event', $data);
```

### Health Monitoring
The health check endpoints provide:
- Database connectivity
- Cache functionality
- Queue status
- Redis connectivity
- Memory usage
- Response times

---

## 🔄 Development Workflow

### Adding a New Module

1. **Create Module Structure**
```bash
mkdir -p app/Modules/YourModule/{Controllers,Models,Requests,Resources,Services}
```

2. **Create Controller**
```php
<?php
namespace App\Modules\YourModule\Controllers;

use App\Http\Controllers\Controller;

class YourModuleController extends Controller
{
    // Your controller logic
}
```

3. **Create Routes**
```php
// app/Modules/YourModule/routes.php
<?php
use Illuminate\Support\Facades\Route;
use App\Modules\YourModule\Controllers\YourModuleController;

Route::middleware(['auth:api'])->group(function () {
    Route::apiResource('your-module', YourModuleController::class);
});
```

4. **Auto-loading** - The `ModuleServiceProvider` automatically loads your routes!

### Adding API Endpoints

1. **Create Request Validation**
```php
// app/Modules/YourModule/Requests/YourRequest.php
<?php
namespace App\Modules\YourModule\Requests;

use Illuminate\Foundation\Http\FormRequest;

class YourRequest extends FormRequest
{
    public function rules()
    {
        return [
            'field' => 'required|string|max:255',
        ];
    }
}
```

2. **Create API Resource**
```php
// app/Modules/YourModule/Resources/YourResource.php
<?php
namespace App\Modules\YourModule\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class YourResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'field' => $this->field,
            'created_at' => $this->created_at,
        ];
    }
}
```

---

## 🎯 Best Practices

### 1. Security
- Always validate input with Request classes
- Use rate limiting on sensitive endpoints
- Log security events
- Never expose sensitive data in API responses
- Use HTTPS in production

### 2. Code Organization
- Keep controllers thin - move logic to Services
- Use Repository pattern for complex data access
- Create Resource classes for API responses
- Write tests for all new features

### 3. Performance
- Use database indexing
- Implement caching where appropriate
- Optimize database queries
- Use pagination for large datasets

### 4. Monitoring
- Monitor API response times
- Set up error tracking (Sentry)
- Regular security audits
- Monitor resource usage

---

## 🆘 Common Tasks

### User Management
```bash
# Create admin user
php artisan db:seed --class=UserSeeder

# Assign role to user
php artisan tinker
>>> $user = App\Models\User::find(1);
>>> $user->assignRole('admin');
```

### Database Management
```bash
# Fresh migration with seeds
php artisan migrate:fresh --seed

# Create new migration
php artisan make:migration create_your_table

# Create seeder
php artisan make:seeder YourSeeder
```

### Cache Management
```bash
# Clear all caches
php artisan optimize:clear

# Cache config for production
php artisan config:cache
php artisan route:cache
```

---

## 📞 Getting Help

### Documentation
- **Main README** - Overview and quick start
- **API Documentation** - `docs/API.md`
- **Windows Setup** - `docs/WINDOWS_SETUP.md`
- **Troubleshooting** - `docs/WINDOWS_TROUBLESHOOTING.md`

### Debugging
- Check logs in `storage/logs/`
- Use `php artisan tinker` for interactive debugging
- Enable debug mode: `APP_DEBUG=true` (development only)
- Use Laravel Telescope for request debugging

### Common Commands
```bash
# Check application status
php artisan about

# List all routes
php artisan route:list

# Check environment
php artisan env

# Run health check
curl http://localhost:8000/api/health
```

---

## 🎉 Congratulations!

You now have a **production-ready, secure Laravel backend** with:
- ✅ Modular architecture for scalability
- ✅ Comprehensive security features
- ✅ Full API functionality
- ✅ Testing suite
- ✅ Docker support
- ✅ CI/CD pipeline
- ✅ Extensive documentation

**This backend is ready to power your next API-first application!** 🚀
