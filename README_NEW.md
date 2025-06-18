# Secure Laravel Backend

A production-ready, secure Laravel backend starter template with OWASP best practices, modular architecture, and comprehensive security features.

## ✨ Features

### 🔐 Security Features
- **OWASP Security Best Practices** - Following OWASP Top 10 guidelines
- **Laravel Passport** - OAuth2 server implementation for API authentication
- **Security Headers** - Comprehensive security headers middleware
- **Rate Limiting** - Advanced rate limiting with Redis support
- **Input Validation** - Strict validation for all endpoints
- **SQL Injection Protection** - Eloquent ORM with prepared statements
- **XSS Protection** - Built-in Laravel XSS protection
- **CSRF Protection** - Cross-site request forgery protection
- **Secure Session Configuration** - Hardened session settings

### 🏗️ Architecture
- **Modular Structure** - Clean separation of concerns with app/Modules
- **Repository Pattern** - Data access layer abstraction
- **Service Layer** - Business logic separation
- **Resource Classes** - API response transformation
- **Custom Middleware** - Security and API versioning middleware

### 📊 Monitoring & Logging
- **Laravel Telescope** - Application debugging and monitoring
- **Sentry Integration** - Error tracking and performance monitoring
- **Comprehensive Logging** - Security, API, and application logs
- **Health Check Endpoints** - System status monitoring
- **API Logging** - Detailed API usage tracking

### 🧪 Testing
- **PHPUnit Tests** - Unit and feature tests
- **Laravel Dusk** - Browser automation testing
- **API Integration Tests** - Complete API workflow testing
- **Security Tests** - Authentication and authorization testing

### 🚀 DevOps
- **Docker Support** - Complete containerization with Sail
- **GitHub Actions** - CI/CD pipeline
- **Environment Management** - Secure environment configuration
- **Artisan Commands** - Custom maintenance and utility commands

## 📋 Requirements

- PHP >= 8.2
- Composer
- MySQL/PostgreSQL
- Redis (recommended)
- Docker (optional)

## 🚀 Quick Start

### Option 1: Using Setup Script (Recommended)

**Windows:**
```powershell
.\setup.ps1
```

**Linux/macOS:**
```bash
chmod +x setup.sh
./setup.sh
```

### Option 2: Manual Setup

1. **Clone and Install Dependencies**
```bash
git clone <repository-url>
cd secure-laravel-backend
composer install
```

2. **Environment Configuration**
```bash
cp .env.example .env
php artisan key:generate
```

3. **Database Setup**
```bash
php artisan migrate:fresh --seed
```

4. **Passport Setup**
```bash
php artisan passport:install
```

5. **Start Development Server**
```bash
php artisan serve
```

### Option 3: Docker Setup

1. **Start with Docker**
```bash
./vendor/bin/sail up -d
```

2. **Run Initial Setup**
```bash
./vendor/bin/sail artisan migrate:fresh --seed
./vendor/bin/sail artisan passport:install
```

## 📁 Project Structure

```
app/
├── Console/Commands/          # Custom Artisan commands
├── Http/
│   ├── Controllers/          # Base controllers
│   └── Middleware/           # Security and utility middleware
├── Models/                   # Eloquent models
├── Modules/                  # Modular application structure
│   ├── Auth/                # Authentication module
│   ├── User/                # User management module
│   └── MasterData/          # Master data module
└── Providers/               # Service providers

config/                      # Configuration files
├── security.php            # Security-specific configuration
├── api.php                 # API configuration
└── ...

database/
├── migrations/             # Database migrations
└── seeders/               # Database seeders

tests/
├── Feature/               # Feature tests
└── Unit/                  # Unit tests
```

## 🔧 Available Commands

### Composer Scripts
```bash
# Complete setup
composer run setup

# Run tests
composer run test
composer run test-coverage

# Generate API documentation
composer run docs

# System cleanup
composer run cleanup

# Fresh database with seeds
composer run fresh
```

### Artisan Commands
```bash
# Security audit
php artisan security:audit

# System cleanup
php artisan system:cleanup --all

# Generate API documentation
php artisan api:docs

# Health check
curl http://localhost:8000/api/health
```

## 🔌 API Endpoints

### Authentication
- `POST /api/v1/auth/register` - User registration
- `POST /api/v1/auth/login` - User login
- `GET /api/v1/auth/user` - Get authenticated user
- `POST /api/v1/auth/logout` - Logout user
- `POST /api/v1/auth/refresh` - Refresh token

### User Management
- `GET /api/v1/users` - List users (paginated)
- `POST /api/v1/users` - Create user
- `GET /api/v1/users/{id}` - Get user
- `PUT /api/v1/users/{id}` - Update user
- `DELETE /api/v1/users/{id}` - Delete user

### Master Data
- `GET /api/v1/master-data/roles` - List roles
- `GET /api/v1/master-data/permissions` - List permissions

### Health Check
- `GET /api/health` - Basic health check
- `GET /api/health/detailed` - Detailed system status

## 🔐 Security Configuration

### Rate Limiting
- Authentication endpoints: 5 requests per minute
- API endpoints: 100 requests per minute
- Configurable per environment

### Security Headers
- `X-Frame-Options: DENY`
- `X-Content-Type-Options: nosniff`
- `X-XSS-Protection: 1; mode=block`
- `Referrer-Policy: strict-origin-when-cross-origin`
- `Strict-Transport-Security` (HTTPS only)

### CORS Configuration
- Configurable allowed origins
- Secure defaults for production

## 📊 Monitoring & Logging

### Available Log Channels
- `security` - Security-related events
- `api` - API request/response logging
- `daily` - Application logs (rotated daily)

### Health Monitoring
- Database connectivity
- Cache functionality
- Queue status
- Redis connectivity (if configured)

## 🧪 Testing

### Run All Tests
```bash
php artisan test
```

### Run Specific Test Suites
```bash
# Feature tests
php artisan test --testsuite=Feature

# Unit tests
php artisan test --testsuite=Unit

# With coverage
php artisan test --coverage
```

### Browser Tests (Dusk)
```bash
php artisan dusk
```

## 🚀 Deployment

### Production Checklist
1. Run security audit: `php artisan security:audit`
2. Set `APP_ENV=production`
3. Set `APP_DEBUG=false`
4. Configure HTTPS
5. Set secure session settings
6. Configure proper CORS origins
7. Set up monitoring (Sentry)
8. Configure log rotation
9. Set up regular backups
10. Configure caching (Redis)

### Environment Variables
See `.env.example` for all available configuration options.

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add/update tests
5. Run the test suite
6. Submit a pull request

## 📄 License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## 🔒 Security

If you discover any security vulnerabilities, please send an e-mail to security@yourdomain.com. All security vulnerabilities will be promptly addressed.

## 📞 Support

For support and questions:
- Create an issue on GitHub
- Check the documentation in `docs/`
- Review the API documentation at `docs/API.md`

---

## 🎯 Next Steps

After setup, consider:
1. Customizing the modules for your specific use case
2. Adding additional security measures specific to your domain
3. Setting up monitoring and alerting
4. Configuring automated backups
5. Implementing additional API endpoints
6. Adding more comprehensive tests
7. Setting up staging environment
8. Configuring log aggregation
9. Implementing audit trails
10. Adding API rate limiting per user
