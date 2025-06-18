# API Documentation

## Overview

The Secure Laravel Backend provides a comprehensive REST API with OAuth2 authentication, role-based access control, and comprehensive security features.

## Base URL

```
Production: https://your-domain.com/api/v1
Development: http://localhost/api/v1
```

## Authentication

This API uses Laravel Passport for OAuth2 authentication. Include the access token in the Authorization header:

```
Authorization: Bearer {access_token}
```

## Response Format

All API responses follow a consistent format:

```json
{
    "data": {
        // Response data
    },
    "meta": {
        "version": "v1",
        "timestamp": "2024-01-01T00:00:00Z",
        "request_id": "uuid"
    }
}
```

## Error Responses

Errors follow RFC 7807 Problem Details format:

```json
{
    "error": "Error Type",
    "message": "Human readable error message",
    "details": {
        // Additional error details
    },
    "timestamp": "2024-01-01T00:00:00Z"
}
```

## Rate Limiting

Rate limits are applied per endpoint:

- **Global**: 60 requests per minute
- **API Endpoints**: 100 requests per minute
- **Authentication**: 5 requests per minute

Rate limit headers are included in responses:

```
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 59
```

## Authentication Endpoints

### Register User

```http
POST /api/v1/auth/register
```

**Request Body:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "SecurePassword123!",
    "password_confirmation": "SecurePassword123!"
}
```

**Response:**
```json
{
    "message": "User registered successfully",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "roles": ["user"]
    },
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...",
    "token_type": "Bearer",
    "expires_at": "2024-02-01T00:00:00Z"
}
```

### Login User

```http
POST /api/v1/auth/login
```

**Request Body:**
```json
{
    "email": "john@example.com",
    "password": "SecurePassword123!"
}
```

**Response:**
```json
{
    "message": "Login successful",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "roles": ["user"],
        "permissions": ["access-api"]
    },
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...",
    "token_type": "Bearer",
    "expires_at": "2024-02-01T00:00:00Z"
}
```

### Get User Details

```http
GET /api/v1/auth/user
Authorization: Bearer {access_token}
```

**Response:**
```json
{
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "email_verified_at": "2024-01-01T00:00:00Z",
        "roles": ["user"],
        "permissions": ["access-api"],
        "created_at": "2024-01-01T00:00:00Z",
        "updated_at": "2024-01-01T00:00:00Z"
    }
}
```

### Logout

```http
POST /api/v1/auth/logout
Authorization: Bearer {access_token}
```

**Response:**
```json
{
    "message": "Successfully logged out"
}
```

### Refresh Token

```http
POST /api/v1/auth/refresh
Authorization: Bearer {access_token}
```

**Response:**
```json
{
    "message": "Token refreshed successfully",
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...",
    "token_type": "Bearer",
    "expires_at": "2024-02-01T00:00:00Z"
}
```

## Security Features

### Password Requirements

Passwords must meet the following criteria:
- Minimum 8 characters
- At least one uppercase letter
- At least one lowercase letter
- At least one number
- At least one special character
- Not found in common password databases

### Rate Limiting

Rate limiting is implemented at multiple levels:
- IP-based limiting for anonymous users
- User-based limiting for authenticated users
- Endpoint-specific limits
- Progressive backoff for failed attempts

### Security Headers

All responses include comprehensive security headers:
- Content Security Policy (CSP)
- HTTP Strict Transport Security (HSTS)
- X-Frame-Options
- X-Content-Type-Options
- Referrer-Policy
- Permissions-Policy

### Input Validation

All input is validated and sanitized:
- SQL injection prevention
- XSS protection
- Input length limits
- Type validation
- Format validation

## HTTP Status Codes

- `200` - OK
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Unprocessable Entity
- `429` - Too Many Requests
- `500` - Internal Server Error

## Pagination

List endpoints support pagination:

```http
GET /api/v1/users?page=1&per_page=15
```

**Response:**
```json
{
    "data": [...],
    "meta": {
        "current_page": 1,
        "per_page": 15,
        "total": 100,
        "last_page": 7,
        "from": 1,
        "to": 15
    },
    "links": {
        "first": "http://localhost/api/v1/users?page=1",
        "last": "http://localhost/api/v1/users?page=7",
        "prev": null,
        "next": "http://localhost/api/v1/users?page=2"
    }
}
```

## Filtering and Sorting

List endpoints support filtering and sorting:

```http
GET /api/v1/users?filter[name]=john&sort=-created_at
```

## Monitoring and Logging

All API requests are logged and monitored:
- Request/response logging
- Performance monitoring
- Error tracking with Sentry
- Security event logging
- Rate limit monitoring
