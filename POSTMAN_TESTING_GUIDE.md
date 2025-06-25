# PKTracker Backend - Postman Testing Guide

## Server Status
✅ **Server Running:** http://127.0.0.1:8000
✅ **Database:** SQLite with seeded data
✅ **Authentication:** Laravel Passport OAuth2

## Quick Test Sequence

### 1. Health Check (Test if server is working)
```
GET http://127.0.0.1:8000/api/health

Expected Response:
{
    "status": "ok",
    "timestamp": "2025-06-25T04:10:11+00:00",
    "version": "1.0.0"
}
```

### 2. User Registration
```
POST http://127.0.0.1:8000/api/v1/auth/register
Content-Type: application/json

{
    "name": "Test User",
    "email": "test@pktracker.com",
    "password": "TestUser123!",
    "password_confirmation": "TestUser123!"
}

Expected: User created with access token
```

### 3. User Login
```
POST http://127.0.0.1:8000/api/v1/auth/login
Content-Type: application/json

{
    "email": "admin@admin.com",
    "password": "Admin123!"
}

Expected Response:
{
    "message": "Login successful",
    "user": {...},
    "access_token": "...",
    "token_type": "Bearer"
}
```

### 4. Get Current User (Requires Bearer Token)
```
GET http://127.0.0.1:8000/api/v1/auth/user
Authorization: Bearer {your_access_token}

Expected: Current user details with roles/permissions
```

### 5. List Users (Admin Only)
```
GET http://127.0.0.1:8000/api/v1/users
Authorization: Bearer {admin_access_token}

Expected: Paginated list of users
```

### 6. Get User Roles
```
GET http://127.0.0.1:8000/api/v1/roles
Authorization: Bearer {admin_access_token}

Expected: List of available roles (super-admin, admin, moderator, user)
```

### 7. Get Permissions
```
GET http://127.0.0.1:8000/api/v1/permissions
Authorization: Bearer {admin_access_token}

Expected: List of all permissions
```

## Test Users & Credentials

| Role | Email | Password | Permissions |
|------|-------|----------|-------------|
| Super Admin | `super@admin.com` | `SuperAdmin123!` | All permissions |
| Admin | `admin@admin.com` | `Admin123!` | User/Role management, API access |
| Moderator | `moderator@example.com` | `Moderator123!` | View users, API access |
| User | `user@example.com` | `User123!` | Basic API access |

## Authentication Flow for Postman

1. **Login Request:**
   - Method: POST
   - URL: `http://127.0.0.1:8000/api/v1/auth/login`
   - Headers: `Content-Type: application/json`
   - Body: `{"email": "admin@admin.com", "password": "Admin123!"}`

2. **Copy the `access_token` from response**

3. **For subsequent requests:**
   - Headers: `Authorization: Bearer {paste_access_token_here}`

## Rate Limiting (Test These)

- **Guest users:** 30 requests/minute
- **Authenticated users:** 60 requests/minute  
- **Premium users:** 120 requests/minute
- **Admin API:** 300 requests/minute

## PKTracker Service Providers in Action

### Authorization Tests
```
# Test different user access levels
GET /api/v1/users (as admin) ✅ Should work
GET /api/v1/users (as user) ❌ Should be forbidden
```

### Event System Tests
```
# Profile updates should trigger cache invalidation
PUT /api/v1/users/{id} (update email)
# Check logs for UserProfileUpdated event
```

### Route Model Binding Tests
```
# These should auto-resolve User models
GET /api/v1/users/1
GET /api/v1/users/admin@admin.com (if implemented)
```

## Error Testing

### Test Invalid Credentials
```
POST /api/v1/auth/login
{
    "email": "wrong@email.com",
    "password": "wrongpassword"
}

Expected: 401 Unauthorized
```

### Test Permission Denied
```
GET /api/v1/users
# Without admin token

Expected: 403 Forbidden
```

### Test Rate Limiting
```
# Make 31 requests quickly to /api/health as guest
Expected: 429 Too Many Requests
```

## Custom PKTracker Endpoints

These are available and ready for testing:

1. **User Profile Management**
2. **Role-based Access Control**
3. **Permission System**
4. **OAuth2 Authentication**
5. **Rate Limiting by User Role**
6. **Comprehensive Logging**

## Next Steps for Pokemon Features

Once basic authentication is working, you can extend with:

1. **Pokemon Module:** Tracking, catching, analytics
2. **Trading System:** User-to-user trading
3. **Achievements:** Gamification features
4. **Real-time Features:** WebSocket support
5. **Mobile API:** Optimized endpoints for mobile apps

## Debugging Tips

1. **Check Laravel Logs:** `storage/logs/laravel.log`
2. **Use Telescope:** `http://127.0.0.1:8000/telescope`
3. **API Documentation:** Available via routes
4. **Database:** SQLite file at `database/database.sqlite`

## Production Considerations

Before deploying to production:

1. Change `APP_ENV=production` in `.env`
2. Set up proper MySQL/PostgreSQL database
3. Configure Redis for caching/sessions
4. Set up proper CORS origins
5. Configure Sentry for error tracking
6. Set up SSL certificates
7. Configure proper rate limiting
8. Set up queue workers for background jobs
