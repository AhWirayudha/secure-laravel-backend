# Secure Laravel Backend

A production-ready Laravel backend with comprehensive security features, OWASP best practices, and modern development tools.

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
| Framework | Laravel | 11.45.1 (LTS) | ✅ Working |
| API Auth | Laravel Passport | ^12.0 | ✅ Working |
| Authorization | Spatie Permission | ^6.20.0 | ✅ Working |
| Rate Limiting | Custom + spatie/laravel-rate-limited-job-middleware | ^2.8 | ✅ Working |
| Logging | Monolog + Structured Logging | - | ✅ Working |
| Monitoring | Laravel Telescope (Optional) | ^5.0 | ⚠️ Optional |
| Testing | PHPUnit + Test Scripts | ^11.0 | ✅ Working |
| Containerization | Docker + Laravel Sail | ^1.26 | ✅ Available |
| Database | SQLite (dev) / PostgreSQL/MySQL | - | ✅ Working |
| Cache/Queue | File (dev) / Redis (prod) | - | ✅ Working |
| CORS | Laravel Built-in CORS | Native | ✅ Working |

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
   # Test basic functionality
   .\test-fixed.ps1
   
   # Test role management and rate limiting
   .\test-rate-limit.ps1
   ```

> **✅ Expected Results:** 
> - Health check: ✓ OK
> - Login: ✓ `admin@admin.com` / `Admin123!`
> - Users endpoint: ✓ 7 users retrieved
> - Roles endpoint: ✓ 4 roles retrieved
> - Rate limiting: ✓ Working (100 req/min)

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

#### Option 2: Local Setup (Without Docker)

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

### Environment Variables

```env
# Application
APP_NAME="Secure Laravel Backend"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=secure_backend
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
PASSPORT_PRIVATE_KEY=
PASSPORT_PUBLIC_KEY=

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

### Version 1.0.0 - June 25, 2025

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

**Laravel 11 + Passport + Permission System (June 2025)**

**🔧 Major Fixes Completed:**
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

**🗄️ Database & Seeding:**
- ✅ **SQLite for development** - Simplified local testing
- ✅ **Spatie Permission tables** - Roles, permissions, and assignments seeded
- ✅ **Guard configuration** - All permissions use 'api' guard

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

**❌ "Target class [permission] does not exist"**
```bash
# Fixed in current version - middleware properly registered in bootstrap/app.php
# If you see this error, ensure you're using the latest bootstrap/app.php
```

**❌ Rate limit middleware not working**
```bash
# Fixed in current version - rate_limit middleware points to correct class
# Verify in bootstrap/app.php: rate_limit => \App\Http\Middleware\RateLimitMiddleware::class
```

**❌ User model not found in auth**
```bash
# Fixed in current version - config/auth.php uses correct User model path
# Should be: App\Modules\User\Models\User::class
```

**❌ 401 Unauthorized on API calls**
```bash
# Ensure you're using Bearer token authentication:
# Authorization: Bearer YOUR_ACCESS_TOKEN
```

**❌ Permission denied errors**
```bash
# Check user has required permissions:
# Admin user: admin@admin.com has all permissions
# Super Admin: super@admin.com has full system access
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
