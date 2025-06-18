# 🚀 Quick Reference - Secure Laravel Backend

## ⚡ Essential Commands

### Setup
```bash
composer install                    # Install dependencies
cp .env.example .env                # Copy environment file
php artisan key:generate            # Generate app key
php artisan migrate:fresh --seed    # Setup database
php artisan passport:install        # Setup OAuth2
php artisan serve                   # Start dev server
```

### Testing
```bash
php artisan test                    # Run all tests
php artisan test --coverage         # With coverage
php artisan test --filter=UserTest  # Specific test
```

### Maintenance
```bash
php artisan security:audit         # Security check
php artisan system:cleanup --all   # Clean system
php artisan api:docs               # Generate API docs
```

## 🔌 Quick API Reference

### Authentication
```bash
POST /api/v1/auth/register         # Register user
POST /api/v1/auth/login           # Login user  
GET  /api/v1/auth/user            # Current user
POST /api/v1/auth/logout          # Logout
```

### Users (Protected)
```bash
GET    /api/v1/users              # List users
POST   /api/v1/users              # Create user
GET    /api/v1/users/{id}         # Show user
PUT    /api/v1/users/{id}         # Update user
DELETE /api/v1/users/{id}         # Delete user
```

### Health Check
```bash
GET /api/health                   # Basic health
GET /api/health/detailed          # Detailed status
```

## 📁 Module Structure

```
app/Modules/YourModule/
├── Controllers/        # HTTP logic
├── Models/            # Database models
├── Requests/          # Validation
├── Resources/         # API responses
├── Services/          # Business logic
├── Repositories/      # Data access
└── routes.php         # Module routes
```

## 🛡️ Security Features

- ✅ OAuth2 Authentication (Passport)
- ✅ Rate Limiting (5/min auth, 100/min API)
- ✅ Security Headers (OWASP)
- ✅ Input Validation
- ✅ CORS Protection
- ✅ SQL Injection Prevention
- ✅ XSS Protection

## 🔧 Configuration Files

- `config/security.php` - Security settings
- `config/api.php` - API configuration
- `.env` - Environment variables

## 🐳 Docker Commands

```bash
docker-compose up -d              # Start services
docker-compose logs -f app        # View logs
docker-compose exec app bash     # Access container
```

## 📊 Project Stats

- **Security**: OWASP-compliant
- **Architecture**: Modular
- **Testing**: PHPUnit + Feature tests
- **Documentation**: Comprehensive
- **Platform**: Cross-platform
- **Status**: Production-ready ✅
