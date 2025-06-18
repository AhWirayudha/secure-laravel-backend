# Secure Laravel Backend Starter

A production-ready Laravel backend with comprehensive security features, OWASP best practices, and modern development tools.

## 🔐 Security Features

- **Laravel Passport** for OAuth2 API authentication
- **Rate Limiting** with Redis backend
- **CORS protection** with configurable origins
- **Security headers** (CSP, HSTS, X-Frame-Options, etc.)
- **Input validation** and sanitization
- **SQL injection prevention** with prepared statements
- **XSS protection** with output encoding
- **CSRF protection** for web routes
- **Secure session management**
- **Environment-based configuration**

## 🚀 Stack Components

| Component | Tool |
|-----------|------|
| API Auth | Laravel Passport |
| Background Jobs | Redis Queue + Supervisor |
| Logging | Monolog + Structured Logging |
| Monitoring | Laravel Telescope + Sentry |
| Testing | PHPUnit + Laravel Dusk |
| Containerization | Docker + Laravel Sail |
| CI/CD | GitHub Actions |
| Rate Limiting | Throttle Middleware + Redis |
| Cache | Redis |
| Database | PostgreSQL/MySQL |

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

- PHP 8.2+
- Composer
- Redis (for caching and queues)
- PostgreSQL or MySQL
- Docker (optional, for containerized setup)

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

> **Having Issues?** See [docs/WINDOWS_TROUBLESHOOTING.md](docs/WINDOWS_TROUBLESHOOTING.md) for solutions to common problems.

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

API documentation is available at `/api/documentation` when running the application.

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

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## 📄 License

This project is licensed under the MIT License.
