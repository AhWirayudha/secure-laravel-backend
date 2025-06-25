# PKTracker Backend - Laravel 11 + Passport + MySQL

A production-ready Laravel 11 backend with comprehensive security features, OWASP best practices, MySQL integration, and modern development tools. **All major issues resolved and fully functional.**

## 🎯 Project Status: ✅ PRODUCTION READY

**✅ Core Features Implemented & Tested:**
- Laravel 11.45.1 LTS with Passport OAuth2 authentication
- MySQL integration with automatic key length handling
- Spatie Permission system with roles and permissions
- Rate limiting with custom middleware (100 req/min)
- Complete API suite with User and Role management
- Comprehensive test suite and Postman collection
- All dependency conflicts resolved
- Production-ready configuration

**✅ Database Support:**
- **MySQL** (Primary) - Full production support with auto-configuration
- **SQLite** (Development) - Zero-config local development
- **PostgreSQL** (Enterprise) - Full compatibility

**✅ Authentication & Authorization:**
- Laravel Passport OAuth2 tokens ✅
- Role-based permissions (Spatie) ✅  
- API guard configuration ✅
- Rate limiting per user/role ✅

**✅ Testing & Documentation:**
- Postman collection with all endpoints ✅
- PowerShell test scripts for Windows ✅
- Complete API documentation ✅
- Troubleshooting guides ✅

## 🚀 Key Fixes & Improvements

**Database Integration:**
- ✅ MySQL "Specified key was too long" error completely resolved
- ✅ Failed jobs migration updated for MySQL compatibility
- ✅ UTF8MB4 charset with proper collation
- ✅ Multi-database support (MySQL/SQLite/PostgreSQL)

**Authentication & Authorization:**
- ✅ "Target class [permission] does not exist" error fixed
- ✅ Spatie Permission middleware properly registered
- ✅ Rate limiting middleware functional (100 req/min)
- ✅ Laravel Passport OAuth2 fully configured

**Laravel 11 Compatibility:**
- ✅ All dependency conflicts resolved
- ✅ spatie/laravel-cors removed (Laravel 11 built-in CORS)
- ✅ spatie/laravel-rate-limited-job-middleware updated to v2.8
- ✅ Service providers properly configured for Laravel 11

## � Table of Contents

- [🔐 Security Features](#-security-features)
- [🚀 Stack Components](#-stack-components)
- [📁 Project Structure](#-project-structure)
- [🛠️ Quick Start](#️-quick-start)
- [⚡ Recent Updates & Dependency Fixes](#-recent-updates--dependency-fixes)
- [🔧 Configuration](#-configuration)
- [🏗️ Service Providers Documentation](#️-service-providers-documentation)
- [🧪 Testing](#-testing)
- [📊 Monitoring](#-monitoring)
- [🚀 Deployment](#-deployment)
- [📚 API Documentation](#-api-documentation)
- [🔒 Security Best Practices](#-security-best-practices)
- [🤝 Contributing](#-contributing)
- [📝 Changelog](#-changelog)

## �🔐 Security Features

- **Laravel Passport** for OAuth2 API authentication
- **Rate Limiting** with Redis backend and spatie/laravel-rate-limited-job-middleware
- **CORS protection** with Laravel 11's built-in CORS middleware
- **Security headers** (CSP, HSTS, X-Frame-Options, etc.)
- **Input validation** and sanitization
- **SQL injection prevention** with prepared statements
- **XSS protection** with output encoding
- **CSRF protection** for web routes
- **Secure session management**
- **Environment-based configuration**

## 🚀 Stack Components

| Component | Tool | Version | Status |
|-----------|------|---------|--------|
| Framework | Laravel | 11.45.1 (LTS) | ✅ Production Ready |
| API Auth | Laravel Passport | ^12.0 | ✅ Fully Configured |
| Authorization | Spatie Permission | ^6.20.0 | ✅ All Issues Fixed |
| Rate Limiting | Custom + spatie/laravel-rate-limited-job-middleware | ^2.8 | ✅ 100 req/min Working |
| Database | MySQL / SQLite / PostgreSQL | All Supported | ✅ Auto-Configuration |
| MySQL Integration | DatabaseServiceProvider | Custom | ✅ Key Length Fixed |
| Logging | Monolog + Structured Logging | Built-in | ✅ Working |
| Testing | PHPUnit + Test Scripts + Postman | ^11.0 | ✅ Complete Suite |
| Containerization | Docker + Laravel Sail | ^1.26 | ✅ Available |
| Cache/Queue | File (dev) / Redis (prod) | - | ✅ Environment-based |
| CORS | Laravel Built-in CORS | Native | ✅ Laravel 11 Native |

## 📁 Project Structure

```
app/
├── Modules/
│   ├── User/
│   │   ├── Controllers/
│   │   │   ├── UserController.php
│   │   │   └── UserManagementController.php
│   │   ├── Models/
│   │   │   └── User.php
│   │   ├── Requests/
│   │   │   ├── CreateUserRequest.php
│   │   │   ├── UpdateUserRequest.php
│   │   │   └── UserFilterRequest.php
│   │   ├── Resources/
│   │   │   ├── UserResource.php
│   │   │   └── UserCollection.php
│   │   ├── Services/
│   │   │   └── UserService.php
│   │   ├── Repositories/
│   │   │   └── UserRepository.php
│   │   └── routes.php
│   ├── Auth/
│   │   ├── Controllers/
│   │   │   └── AuthController.php
│   │   ├── Requests/
│   │   │   ├── LoginRequest.php
│   │   │   └── RegisterRequest.php
│   │   ├── Services/
│   │   │   └── AuthService.php
│   │   └── routes.php
│   ├── MasterData/
│   │   ├── Controllers/
│   │   │   ├── RoleController.php
│   │   │   └── PermissionController.php
│   │   ├── Models/
│   │   │   ├── Role.php
│   │   │   └── Permission.php
│   │   ├── Services/
│   │   │   └── MasterDataService.php
│   │   └── routes.php
│   └── Common/
│       ├── Traits/
│       ├── Exceptions/
│       └── Helpers/
├── Http/
│   ├── Middleware/
│   │   ├── SecurityHeaders.php
│   │   ├── RateLimitMiddleware.php
│   │   └── ApiVersionMiddleware.php
│   └── Kernel.php
└── Providers/
    └── ModuleServiceProvider.php
```

## 🛠️ Quick Start

### Prerequisites

- PHP 8.2+ (tested with PHP 8.4.8)
- Composer
- Redis (for caching and queues)
- PostgreSQL or MySQL
- Docker (optional, for containerized setup)

> **Important:** Laravel 11 LTS is used for long-term stability and support until 2027.

> **Windows Users:** See [docs/WINDOWS_SETUP.md](docs/WINDOWS_SETUP.md) for detailed Windows installation instructions.

### Installation Options

#### Option 1: Windows Native Setup

1. **Automated Setup (Windows)**
   ```cmd
   setup.bat
   ```
   
2. **Manual Setup**
   ```powershell
   # Install dependencies
   composer install
   
   # Setup environment
   copy .env.example .env
   php artisan key:generate
   
   # Configure database in .env, then run:
   php artisan migrate --seed
   php artisan passport:install
   php artisan serve
   ```

3. **Quick API Test (Windows)**
   ```powershell
   # Test all functionality - everything should work!
   .\test-fixed.ps1
   
   # Test role management and rate limiting
   .\test-rate-limit.ps1
   ```

> **✅ Expected Results (All Working):** 
> - Health check: ✓ OK  
> - Login: ✓ `admin@admin.com` / `Admin123!`
> - Token generation: ✓ Bearer token received
> - Users endpoint: ✓ 7 users retrieved with permissions
> - Roles endpoint: ✓ 4 roles retrieved (admin, user, moderator, super admin)
> - Rate limiting: ✓ 100 req/min with proper headers
> - MySQL integration: ✓ No key length errors
> - Spatie permissions: ✓ All middleware working

> **Having Issues?** See [docs/WINDOWS_TROUBLESHOOTING.md](docs/WINDOWS_TROUBLESHOOTING.md) for solutions to common problems.

> **Composer Dependency Errors?** The project has been updated for Laravel 11 compatibility. If you encounter dependency conflicts, ensure you're using the latest `composer.json` which removes deprecated packages and updates package versions.

#### Option 2: Docker Setup (Cross-platform)

1. **Clone and setup**
   ```bash
   git clone <repository-url> secure-laravel-backend
   cd secure-laravel-backend
   composer install
   ```

2. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Start with Docker**
   ```bash
   docker-compose up -d
   # Wait 30 seconds for database initialization
   ```

4. **Setup database and authentication**
   ```bash
   php artisan migrate --seed
   php artisan passport:install
   php artisan telescope:install
   ```

#### Option 3: Local Setup with MySQL (Recommended)

1. **Prerequisites**
   - PHP 8.2+ with extensions: mbstring, xml, ctype, iconv, intl, pdo_mysql
   - MySQL 5.7+ or 8.0+ database server
   - Composer

2. **Clone and setup**
   ```bash
   git clone <repository-url> secure-laravel-backend
   cd secure-laravel-backend
   composer install
   ```

3. **Environment setup for MySQL**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure MySQL database in .env**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=tracker
   DB_USERNAME=root
   DB_PASSWORD=your_password
   
   # Auto-configured MySQL settings (handled by DatabaseServiceProvider)
   DB_CHARSET=utf8mb4
   DB_COLLATION=utf8mb4_unicode_ci
   DB_STRICT=true
   ```

5. **Create MySQL database**
   ```sql
   CREATE DATABASE tracker CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

6. **Setup database and authentication**
   ```bash
   php artisan migrate:fresh --seed
   php artisan passport:install
   php artisan serve
   ```

> **✅ MySQL Key Length Issue:** Automatically handled by our `DatabaseServiceProvider` - no manual configuration needed!
> **✅ All Issues Resolved:** The current version automatically handles all MySQL compatibility issues including key lengths, charset, and migration conflicts.

#### Option 4: Local Setup (Without Docker)

1. **Prerequisites**
   - PHP 8.2+ with extensions: mbstring, xml, ctype, iconv, intl, pdo_mysql, redis
   - MySQL/PostgreSQL database server
   - Redis server
   - Composer

2. **Clone and setup**
   ```bash
   git clone <repository-url> secure-laravel-backend
   cd secure-laravel-backend
   composer install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure database in .env**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=secure_backend
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   
   REDIS_HOST=127.0.0.1
   REDIS_PORT=6379
   ```

5. **Setup database and authentication**
   ```bash
   php artisan migrate --seed
   php artisan passport:install
   php artisan telescope:install
   ```

6. **Start the application**
   ```bash
   php artisan serve
   ```

## 🔧 Configuration

### Database Options

**🗄️ Flexible Database Support:**
The application supports multiple database systems with automatic configuration:

#### MySQL (Recommended for Production)
```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=tracker
DB_USERNAME=root
DB_PASSWORD=your_password

# Auto-configured MySQL settings
DB_CHARSET=utf8mb4
DB_COLLATION=utf8mb4_unicode_ci
DB_STRICT=true
```

#### SQLite (Great for Development)
```env
DB_CONNECTION=sqlite
# DB_DATABASE will default to database/database.sqlite
```

#### PostgreSQL (Enterprise Option)
```env
DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=tracker
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

> **💡 MySQL Key Length Handling:** Our `DatabaseServiceProvider` automatically handles MySQL's key length limitations by setting `Schema::defaultStringLength(191)` and updating migration files. No manual configuration needed - everything works out of the box!

### Environment Variables

```env
# Application
APP_NAME="Secure Laravel Backend"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database (Example: MySQL)
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=tracker
DB_USERNAME=sail
DB_PASSWORD=password

# Redis
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

# Queue
QUEUE_CONNECTION=redis
QUEUE_FAILED_DRIVER=database

# Passport
PASSPORT_PERSONAL_ACCESS_CLIENT_ID=1
PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET=your_secret
PASSPORT_PASSWORD_CLIENT_ID=2
PASSPORT_PASSWORD_CLIENT_SECRET=your_secret

# Sentry
SENTRY_LARAVEL_DSN=your-sentry-dsn

# Security
CORS_ALLOWED_ORIGINS=https://your-frontend.com
RATE_LIMIT_PER_MINUTE=60
```

### Security Headers

The application includes comprehensive security headers:

- **Content Security Policy (CSP)**
- **HTTP Strict Transport Security (HSTS)**
- **X-Frame-Options**
- **X-Content-Type-Options**
- **Referrer-Policy**
- **Permissions-Policy**

## 📝 Changelog

### Version 1.1.0 - December 2024

**MySQL Integration & Production Readiness**
- ✅ **Complete MySQL support** with automatic key length handling via `DatabaseServiceProvider`
- ✅ **Fixed "Specified key was too long" MySQL error** - automatically sets `Schema::defaultStringLength(191)`
- ✅ **Updated failed_jobs migration** - queue field changed from `text` to `string(191)` for MySQL compatibility
- ✅ **Multi-database flexibility** - seamless switching between MySQL, SQLite, and PostgreSQL
- ✅ **UTF8MB4 charset configuration** - full Unicode support with proper collation
- ✅ **Environment-based database setup** - automatic configuration based on DB_CONNECTION
- ✅ **Production-ready MySQL configuration** - compatible with MySQL 5.7+ and 8.0+
- ✅ **Laravel Passport integration** - complete OAuth2 setup with client credentials
- ✅ **Comprehensive testing suite** - PowerShell scripts and Postman collection for API testing

**Technical Improvements**
- New `DatabaseServiceProvider` for MySQL optimization
- Updated migration files for cross-database compatibility
- Enhanced error handling and troubleshooting documentation
- Complete removal of SQLite development dependencies for production
- Improved environment variable management for different database systems

### Version 1.0.0 - June 2025

**Laravel 11 LTS Migration & Dependency Updates**
- ✅ Migrated to Laravel 11.45.1 LTS for long-term stability
- ✅ Removed deprecated `spatie/laravel-cors` package (replaced with Laravel's built-in CORS)
- ✅ Updated `spatie/laravel-rate-limited-job-middleware` from v1.3 to v2.8
- ✅ Fixed all composer dependency conflicts
- ✅ Added proper Laravel 11 service providers and bootstrap configuration
- ✅ Enhanced security with Laravel 11's improved middleware stack
- ✅ Updated documentation with installation and troubleshooting guides

**Technical Improvements**
- Native CORS middleware configuration in `config/cors.php`
- Proper Laravel 11 application bootstrap structure
- All packages now compatible with PHP 8.2+ and Laravel 11
- Enhanced rate limiting with updated middleware
- Improved error handling and logging

### ⚡ Recent Updates & Fixes

**Laravel 11 + Passport + Permission System + MySQL Integration (December 2024)**

**🔧 Major Fixes Completed:**
- ✅ **MySQL "Specified key was too long" error RESOLVED** - Created `DatabaseServiceProvider` to handle key length limits automatically
- ✅ **Failed jobs migration fixed** - Updated queue field from `text` to `string(191)` for MySQL compatibility
- ✅ **Full MySQL integration** - UTF8MB4 charset, proper collation, and Laravel 11 compatibility
- ✅ **Database flexibility** - Easy switching between MySQL, SQLite, and PostgreSQL with automatic configuration
- ✅ **Fixed "Target class [permission] does not exist" error** - Updated Spatie Permission middleware namespace in `bootstrap/app.php`
- ✅ **Rate limiting fully functional** - Fixed middleware alias registration for Laravel 11
- ✅ **Role Management API working** - All CRUD operations for roles and permissions
- ✅ **Authentication & Authorization** - Laravel Passport + Spatie Permission integration complete
- ✅ **API Guard configuration** - Fixed User model path in `config/auth.php`
- ✅ **Removed Sanctum references** - Pure Passport implementation

**🛠️ Laravel 11 Compatibility:**
- ✅ **Removed deprecated spatie/laravel-cors** - Laravel 11 uses built-in CORS middleware
- ✅ **Updated spatie/laravel-rate-limited-job-middleware** from v1.3 to v2.8
- ✅ **All service providers** properly configured for Laravel 11
- ✅ **Dependency conflicts resolved** - All packages compatible with Laravel 11.45.1
- ✅ **Middleware registration** - Updated for Laravel 11's `bootstrap/app.php` pattern

**🔐 Authentication & Authorization:**
```bash
# Working endpoints verified:
✅ POST /api/v1/auth/login         # Laravel Passport authentication
✅ GET  /api/v1/users             # Protected with 'permission:view-users'
✅ GET  /api/v1/roles             # Protected with 'permission:view-roles'
✅ Rate limiting: 100 req/min     # Custom rate limiting working
```

**🎯 API Testing:**
- ✅ **Postman collection** - Complete API testing suite in `/postman/`
- ✅ **Test scripts** - PowerShell scripts for Windows testing
- ✅ **Admin user seeded** - `admin@admin.com` / `Admin123!`
- ✅ **7 users total** - Various roles and permissions configured

**🔄 Fixed Middleware Issues:**
```php
// bootstrap/app.php - Correct middleware registration for Laravel 11
$middleware->alias([
    'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
    'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
    'rate_limit' => \App\Http\Middleware\RateLimitMiddleware::class,
]);
```

**🗄️ Database & MySQL Integration:**
- ✅ **MySQL support** - Full MySQL integration with automatic key length fixes
- ✅ **DatabaseServiceProvider** - Custom service provider handles MySQL key length limits (`Schema::defaultStringLength(191)`)
- ✅ **Fixed migration issues** - Updated `failed_jobs` table queue field for MySQL compatibility  
- ✅ **Multi-database support** - SQLite (dev), MySQL (prod), PostgreSQL (enterprise) with easy switching
- ✅ **UTF8MB4 charset** - Full Unicode support with proper collation configuration
- ✅ **Spatie Permission tables** - Roles, permissions, and assignments seeded correctly
- ✅ **Guard configuration** - All permissions use 'api' guard for proper Passport integration
- ✅ **Automatic configuration** - Environment-based database setup with sensible defaults

**🔧 MySQL Setup Features:**
```bash
# Automatic MySQL key length handling (no manual config needed)
Schema::defaultStringLength(191);  # Prevents "Specified key was too long" errors

# Fixed table structures for MySQL compatibility
- failed_jobs.queue: text → string(191)
- Proper UTF8MB4 charset and collation 
- Compatible with MySQL 5.7+ and 8.0+
- Automatic environment-based configuration

# Environment variables auto-configured for MySQL
DB_CHARSET=utf8mb4
DB_COLLATION=utf8mb4_unicode_ci
DB_STRICT=true
```

**Key Changes:**
- Fixed middleware namespace: `Middlewares` → `Middleware` (singular)
- Updated auth provider: `App\Models\User` → `App\Modules\User\Models\User`
- Rate limiting: Custom middleware properly registered and functional
- Guard names: Default changed from 'web' to 'api' for role creation

## 🧪 Testing

### Unit Tests
```bash
php artisan test
# or
vendor/bin/phpunit
```

### Browser Tests (Dusk) - Optional
```bash
php artisan dusk
```

### Code Quality
```bash
./vendor/bin/pint
composer audit
```

## 📊 Monitoring

### Laravel Telescope
Access at: `http://localhost/telescope`

### Sentry Integration
Error tracking and performance monitoring configured.

## 🚀 Deployment

### Production Checklist

- [ ] Set `APP_DEBUG=false`
- [ ] Configure proper database credentials
- [ ] Set up Redis for caching and queues
- [ ] Configure CORS origins
- [ ] Set up SSL certificates
- [ ] Configure monitoring and logging
- [ ] Set up backup strategy
- [ ] Configure queue workers
- [ ] Set up supervisor for queue management

### CI/CD Pipeline

GitHub Actions workflow includes:
- Dependency installation
- Code linting (Laravel Pint)
- Static analysis (PHPStan)
- Unit tests (PHPUnit)
- Browser tests (Dusk)
- Security scanning
- Deployment to staging/production

## 📚 API Documentation

**🚀 Ready-to-Use APIs:**

### Authentication Endpoints
```http
POST /api/v1/auth/login
POST /api/v1/auth/register
POST /api/v1/auth/logout
GET  /api/v1/auth/user
```

### User Management (Protected)
```http
GET    /api/v1/users              # List all users (requires: view-users)
POST   /api/v1/users              # Create user (requires: create-users)
GET    /api/v1/users/{id}         # Get user (requires: view-users)  
PUT    /api/v1/users/{id}         # Update user (requires: edit-users)
DELETE /api/v1/users/{id}         # Delete user (requires: delete-users)
```

### Role Management (Protected)
```http
GET    /api/v1/roles              # List all roles (requires: view-roles)
POST   /api/v1/roles              # Create role (requires: create-roles)
GET    /api/v1/roles/{id}         # Get role (requires: view-roles)
PUT    /api/v1/roles/{id}         # Update role (requires: edit-roles)
DELETE /api/v1/roles/{id}         # Delete role (requires: delete-roles)
```

### Permission Management (Protected)
```http
GET    /api/v1/permissions        # List permissions (requires: view-permissions)
POST   /api/v1/permissions        # Create permission (requires: create-permissions)
```

**📁 Postman Collection:**
- Import `postman/PKTracker_API_Collection.json`
- Import `postman/PKTracker_Development_Environment.json`
- See `POSTMAN_TESTING_GUIDE.md` for detailed testing instructions

**🔑 Test Credentials:**
- **Admin:** `admin@admin.com` / `Admin123!`
- **Super Admin:** `super@admin.com` / `Admin123!` 
- **Moderator:** `moderator@example.com` / `Password123!`

**⚡ Rate Limiting:**
- Authentication: 5 attempts per minute per IP
- API endpoints: 100 requests per minute per user
- Headers: `X-RateLimit-Limit`, `X-RateLimit-Remaining`

### 🔧 Common Issues & Solutions

**❌ MySQL "Specified key was too long; max key length is 3072 bytes" - SOLVED ✅**
```bash
# FIXED AUTOMATICALLY in current version
# Our DatabaseServiceProvider automatically sets Schema::defaultStringLength(191)
# Updated failed_jobs migration to use string(191) instead of text for queue field
# No manual configuration needed - everything handled automatically!

# If upgrading from older version:
php artisan migrate:fresh --seed  # Apply all fixes
```

**❌ Failed jobs table migration error - SOLVED ✅**
```bash
# FIXED in database/migrations/2024_01_01_000002_create_failed_jobs_table.php
# Queue field changed from 'text' to 'string(191)' for MySQL compatibility
# Run: php artisan migrate:fresh --seed to apply the fix
```

**❌ "Target class [permission] does not exist" - SOLVED ✅**
```bash
# FIXED in bootstrap/app.php - middleware namespace corrected to singular
# Changed: \Spatie\Permission\Middlewares\ → \Spatie\Permission\Middleware\
# All Spatie Permission middleware now properly registered
```

**❌ Rate limit middleware not working - SOLVED ✅**
```bash
# FIXED in bootstrap/app.php - rate_limit middleware points to correct class
# 'rate_limit' => \App\Http\Middleware\RateLimitMiddleware::class
# Custom rate limiting (100 req/min) now functional
```

**❌ User model not found in auth - SOLVED ✅**
```bash
# FIXED in config/auth.php - uses correct User model path
# 'model' => App\Modules\User\Models\User::class
# API guard properly configured for Passport authentication
```

**❌ MySQL connection issues**
```bash
# Ensure MySQL service is running
# Check .env database credentials
# Create database: CREATE DATABASE tracker CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
# Run: php artisan migrate:fresh --seed
```

**❌ 401 Unauthorized on API calls**
```bash
# Ensure you're using Bearer token authentication:
# Authorization: Bearer YOUR_ACCESS_TOKEN
# Get token from: POST /api/v1/auth/login
```

**❌ Permission denied errors**
```bash
# Check user has required permissions:
# Admin user: admin@admin.com (password: Admin123!) has all permissions
# Super Admin: super@admin.com (password: Admin123!) has full system access
```

**❌ Composer dependency conflicts**
```bash
# RESOLVED in current version - all packages compatible with Laravel 11
# spatie/laravel-cors removed (Laravel 11 built-in CORS used)
# spatie/laravel-rate-limited-job-middleware updated to v2.8
# Run: composer install --no-dev for production
```

## 🔒 Security Best Practices

1. **Authentication**: OAuth2 with Passport
2. **Authorization**: Role-based access control
3. **Input Validation**: Comprehensive request validation
4. **Rate Limiting**: Per-user and global rate limits
5. **CORS**: Configurable cross-origin policies
6. **Security Headers**: Comprehensive header protection
7. **Logging**: Structured logging with sensitive data masking
8. **Error Handling**: Secure error responses

## 🤝 Contributing

**Before contributing, please ensure:**
- PHP 8.2+ compatibility
- Laravel 11 LTS compatibility
- All dependencies are compatible with Laravel 11
- Follow the existing code structure and security practices

**Steps:**
1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## 📄 License

This project is licensed under the MIT License.

## 🏗️ Service Providers Documentation

PKTracker uses Laravel 11's Service Provider architecture to organize authorization, events, and routing. This section explains how to use and modify each provider.

### AuthServiceProvider - Authorization & Policies

**Location:** `app/Providers/AuthServiceProvider.php`

#### Current Configuration

The AuthServiceProvider handles user authorization, policies, and API authentication for PKTracker:

```php
// User authorization gates
Gate::define('manage-system', function (User $user) {
    return $user->hasRole('admin');
});

Gate::define('manage-own-profile', function (User $user, User $targetUser) {
    return $user->id === $targetUser->id || $user->hasRole('admin');
});

Gate::define('view-tracking-data', function (User $user, $userId = null) {
    return $user->id === $userId || $user->hasPermission('view-all-tracking-data');
});
```

#### How to Use in Controllers

```php
class UserController extends Controller
{
    public function update(User $user)
    {
        // Using policies (recommended)
        $this->authorize('update', $user);
        
        // Using gates (alternative)
        if (! Gate::allows('manage-own-profile', $user)) {
            abort(403, 'Unauthorized');
        }
        
        // Your update logic here
    }
    
    public function viewAnalytics(User $user)
    {
        // Check if user can view tracking data
        $this->authorize('viewTrackingAnalytics', $user);
        
        // Return analytics data
    }
}
```

#### Adding New Policies

1. **Create a Policy:**
   ```bash
   php artisan make:policy PokemonPolicy --model=Pokemon
   ```

2. **Register in AuthServiceProvider:**
   ```php
   protected $policies = [
       User::class => UserPolicy::class,
       Pokemon::class => PokemonPolicy::class, // Add new policy
   ];
   ```

3. **Use in Controllers:**
   ```php
   $this->authorize('update', $pokemon);
   ```

#### Adding New Gates

```php
// In AuthServiceProvider boot() method
Gate::define('access-premium-pokemon', function (User $user) {
    return $user->hasRole(['premium', 'admin']);
});

Gate::define('moderate-community', function (User $user) {
    return $user->hasPermission('moderate-community');
});
```

### EventServiceProvider - Event Handling

**Location:** `app/Providers/EventServiceProvider.php`

#### Current Events & Listeners

The EventServiceProvider manages all application events for user actions, security, and PKTracker-specific features:

```php
protected $listen = [
    // Laravel Auth Events
    Registered::class => [
        SendEmailVerificationNotification::class,
        'App\Modules\User\Listeners\CreateUserProfile',
    ],
    
    Login::class => [
        'App\Modules\User\Listeners\UpdateLastLoginInfo',
    ],
    
    // PKTracker Custom Events
    'App\Modules\User\Events\UserProfileUpdated' => [
        'App\Modules\User\Listeners\InvalidateUserCache',
    ],
];
```

#### How to Use Events

**1. Fire Events in Your Code:**
```php
use App\Modules\User\Events\UserProfileUpdated;

class UserService
{
    public function updateProfile(User $user, array $data)
    {
        $originalData = $user->toArray();
        $user->update($data);
        
        // Fire event with changed fields
        $changedFields = array_keys($user->getChanges());
        event(new UserProfileUpdated($user, $changedFields));
        
        return $user;
    }
}
```

**2. Create New Events:**
```bash
php artisan make:event PokemonCaught
php artisan make:listener UpdateUserStats --event=PokemonCaught
```

**3. Register in EventServiceProvider:**
```php
'App\Modules\Pokemon\Events\PokemonCaught' => [
    'App\Modules\Pokemon\Listeners\UpdateUserStats',
    'App\Modules\Pokemon\Listeners\CheckAchievements',
],
```

#### Model Events (Automatic)

The EventServiceProvider also handles Eloquent model events:

```php
// In EventServiceProvider boot() method
User::creating(function ($user) {
    Log::info('User being created', ['email' => $user->email]);
});

User::updated(function ($user) {
    if ($user->wasChanged('email')) {
        // Email was changed, clear cache
        Cache::forget("user.email.{$user->getOriginal('email')}");
    }
});
```

### RouteServiceProvider - Routing & Rate Limiting

**Location:** `app/Providers/RouteServiceProvider.php`

#### Current Rate Limiting Configuration

PKTracker implements role-based rate limiting:

```php
RateLimiter::for('api', function (Request $request) {
    $user = $request->user();
    
    // Premium users get higher limits
    if ($user && $user->hasRole('premium')) {
        return Limit::perMinute(120)->by($user->id);
    }
    
    // Regular users
    if ($user) {
        return Limit::perMinute(60)->by($user->id);
    }
    
    // Guests
    return Limit::perMinute(30)->by($request->ip());
});
```

#### Model Bindings

The RouteServiceProvider configures automatic model resolution:

```php
// Automatic User model binding
Route::get('/users/{user}', function (User $user) {
    return $user; // $user is automatically resolved
});

// Custom binding for user by ID or email
Route::get('/users/{userIdentifier}', function (User $user) {
    return $user; // Resolves by ID or email
});

// Current user binding
Route::get('/my-profile', function (User $myProfile) {
    return $myProfile; // Returns authenticated user
});
```

#### Adding Custom Rate Limiters

```php
// In RouteServiceProvider boot() method
RateLimiter::for('pokemon-api', function (Request $request) {
    $user = $request->user();
    
    if ($user && $user->hasRole('premium')) {
        return Limit::perMinute(500)->by($user->id);
    }
    
    return Limit::perMinute(100)->by($user?->id ?: $request->ip());
});
```

**Use in routes:**
```php
Route::middleware(['throttle:pokemon-api'])->group(function () {
    Route::get('/pokemon', [PokemonController::class, 'index']);
    Route::post('/pokemon/catch', [PokemonController::class, 'catch']);
});
```

#### Route Patterns

Defined patterns ensure route parameters match expected formats:

```php
Route::pattern('id', '[0-9]+');                    // Numeric IDs only
Route::pattern('uuid', '[0-9a-f]{8}-[0-9a-f]{4}...'); // UUID format
Route::pattern('username', '[a-zA-Z0-9_]+');       // Valid usernames
Route::pattern('pokemonId', '[0-9]+');             // Pokemon IDs
```

### Creating New Modules

When adding new modules to PKTracker (e.g., Pokemon, Trading, etc.), follow this structure:

#### 1. Create Module Directory
```
app/Modules/Pokemon/
├── Controllers/
├── Models/
├── Policies/
├── Events/
├── Listeners/
├── Services/
└── routes.php
```

#### 2. Register Routes in RouteServiceProvider
```php
// Add to loadModuleRoutes() method
Route::middleware(['api', 'throttle:pokemon-api'])
    ->prefix('api/v1')
    ->group(base_path('app/Modules/Pokemon/routes.php'));
```

#### 3. Add Events to EventServiceProvider
```php
'App\Modules\Pokemon\Events\PokemonCaught' => [
    'App\Modules\Pokemon\Listeners\UpdateUserStats',
    'App\Modules\Analytics\Listeners\TrackPokemonData',
],
```

#### 4. Add Policies to AuthServiceProvider
```php
protected $policies = [
    User::class => UserPolicy::class,
    'App\Modules\Pokemon\Models\Pokemon' => 'App\Modules\Pokemon\Policies\PokemonPolicy',
];
```

### Example: Complete Pokemon Module Integration

**1. Pokemon Event:**
```php
// app/Modules/Pokemon/Events/PokemonCaught.php
class PokemonCaught
{
    public function __construct(
        public User $user,
        public Pokemon $pokemon,
        public array $catchData
    ) {}
}
```

**2. Pokemon Listener:**
```php
// app/Modules/Pokemon/Listeners/UpdateUserStats.php
class UpdateUserStats
{
    public function handle(PokemonCaught $event): void
    {
        $user = $event->user;
        $user->increment('pokemon_caught');
        $user->increment('total_experience', $event->catchData['experience']);
        
        Cache::forget("user.stats.{$user->id}");
    }
}
```

**3. Pokemon Controller:**
```php
// app/Modules/Pokemon/Controllers/PokemonController.php
class PokemonController extends Controller
{
    public function catch(CatchPokemonRequest $request)
    {
        $this->authorize('catch', Pokemon::class);
        
        $pokemon = $this->pokemonService->catchPokemon(
            auth()->user(),
            $request->validated()
        );
        
        event(new PokemonCaught(auth()->user(), $pokemon, $request->validated()));
        
        return new PokemonResource($pokemon);
    }
}
```

**4. Register Everything:**
```php
// In EventServiceProvider
'App\Modules\Pokemon\Events\PokemonCaught' => [
    'App\Modules\Pokemon\Listeners\UpdateUserStats',
],

// In RouteServiceProvider
Route::middleware(['api', 'throttle:pokemon-api'])
    ->prefix('api/v1')
    ->group(base_path('app/Modules/Pokemon/routes.php'));

// In AuthServiceProvider
Gate::define('catch-legendary-pokemon', function (User $user) {
    return $user->level >= 50 || $user->hasRole('premium');
});
```

### Best Practices

1. **Events:** Use events for side effects (logging, cache clearing, notifications)
2. **Policies:** Use policies for model-specific authorization
3. **Gates:** Use gates for simple permission checks
4. **Rate Limiting:** Implement different limits based on user roles
5. **Model Binding:** Use route model binding for automatic model resolution
6. **Patterns:** Define route patterns for parameter validation

### Debugging Tips

```bash
# View registered events
php artisan event:list

# View registered routes
php artisan route:list

# View registered policies
php artisan route:list --path=auth

# Clear route cache
php artisan route:clear

# Cache routes for production
php artisan route:cache
```

## 🔗 Quick Reference

### Common Service Provider Tasks

#### Authorization
```php
// In controllers
$this->authorize('update', $user);
Gate::allows('manage-system');

// In blade/API responses
@can('update', $user) ... @endcan
$user->can('update', $targetUser);
```

#### Events
```php
// Fire events
event(new UserProfileUpdated($user, $changes));

// Listen in models
static::created(function ($model) { ... });
```

#### Rate Limiting
```php
// Apply to routes
Route::middleware(['throttle:api'])->group(...);
Route::middleware(['throttle:pokemon-api'])->group(...);

// Custom limits
RateLimiter::for('custom', fn($request) => 
    Limit::perMinute(100)->by($request->user()->id)
);
```

#### Model Binding
```php
// In routes
Route::get('/users/{user}', ...);           // Auto-resolves User
Route::get('/users/{userIdentifier}', ...); // ID or email
Route::get('/my-profile', ...);             // Current user
```

### Artisan Commands

```bash
# Service Providers
php artisan make:provider CustomServiceProvider
php artisan make:policy PokemonPolicy --model=Pokemon

# Events & Listeners
php artisan make:event PokemonCaught
php artisan make:listener UpdateStats --event=PokemonCaught
php artisan event:list

# Routes & Caching
php artisan route:list
php artisan route:cache
php artisan route:clear

# Authorization
php artisan make:policy UserPolicy --model=User
```

### File Locations

```
📁 Service Providers
├── app/Providers/AuthServiceProvider.php     # Authorization & Policies
├── app/Providers/EventServiceProvider.php    # Events & Listeners
└── app/Providers/RouteServiceProvider.php    # Routes & Rate Limiting

📁 Module Structure (example: User)
├── app/Modules/User/
│   ├── Controllers/UserController.php
│   ├── Models/User.php
│   ├── Policies/UserPolicy.php
│   ├── Events/UserProfileUpdated.php
│   ├── Listeners/InvalidateUserCache.php
│   └── routes.php

📁 Configuration
├── config/auth.php          # Authentication config
├── config/cors.php          # CORS configuration
└── config/app.php          # Service provider registration
```
