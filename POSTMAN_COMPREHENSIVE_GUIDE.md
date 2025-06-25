# 🚀 PKTracker API - Postman Testing Guide

## 📁 Collection Overview

This comprehensive Postman collection provides complete testing coverage for the PKTracker API built with Laravel 11 and Passport authentication.

## 🔧 Setup Instructions

### 1. Import Files into Postman

1. **Import Collection**:
   - Open Postman
   - Click "Import" button
   - Select `postman/PKTracker_API_Collection.json`

2. **Import Environment**:
   - Click "Import" button again
   - Select `postman/PKTracker_Development_Environment.json`
   - Set "PKTracker Development" as active environment

### 2. Start Laravel Server

```bash
cd c:\Internal\PKTracker\Backend\tracker-backend
php artisan serve --host=127.0.0.1 --port=8000
```

## 📋 Testing Sequence

### Phase 1: Basic Health Checks ✅

1. **Health Check - Basic**
   - Verifies API is running
   - Expected: 200 OK with status information

2. **Health Check - Detailed**
   - Comprehensive system status
   - Expected: 200 OK with detailed metrics

3. **API Test Endpoint**
   - Simple connectivity test
   - Expected: 200 OK with welcome message

### Phase 2: Authentication Flow 🔐

#### Step 1: Login as Admin
1. **Login - Admin User**
   - Uses: `admin@example.com` / `password`
   - Auto-saves access token to environment
   - Expected: 200 OK with user data and token

#### Step 2: Test Protected Endpoints
2. **Get Current User**
   - Tests token validation
   - Expected: 200 OK with user profile

3. **Refresh Token**
   - Tests token refresh mechanism
   - Expected: 200 OK with new token

#### Step 3: User Registration
4. **Register New User**
   - Creates test user account
   - Expected: 201 Created with user data

5. **Login - Test User**
   - Tests login with newly created user
   - Expected: 200 OK with token

### Phase 3: User Management 👥

#### CRUD Operations
1. **List All Users**
   - Basic user listing
   - Expected: 200 OK with user collection

2. **List Users with Filters**
   - Advanced filtering/sorting/pagination
   - Expected: 200 OK with filtered results

3. **Create New User**
   - Admin creates another user
   - Auto-saves user_id for subsequent tests
   - Expected: 201 Created

4. **Get User by ID**
   - Retrieve specific user
   - Expected: 200 OK with user details

5. **Update User**
   - Modify user information
   - Expected: 200 OK with updated data

#### Advanced User Operations
6. **Toggle User Status**
   - Enable/disable user account
   - Expected: 200 OK with status change

7. **Assign Roles to User**
   - Role-based access control
   - Expected: 200 OK with role assignment

8. **Remove Roles from User**
   - Role removal
   - Expected: 200 OK with confirmation

#### User Lifecycle Management
9. **Soft Delete User**
   - Marks user as deleted
   - Expected: 200 OK

10. **Restore Deleted User**
    - Undeletes user
    - Expected: 200 OK

11. **Force Delete User**
    - Permanent deletion
    - Expected: 200 OK

### Phase 4: Role Management 🔒

1. **List All Roles**
   - View available roles
   - Expected: 200 OK with roles collection

2. **Create New Role**
   - Define custom role
   - Auto-saves role_id
   - Expected: 201 Created

3. **Get Role by ID**
   - Retrieve specific role
   - Expected: 200 OK

4. **Update Role**
   - Modify role properties
   - Expected: 200 OK

5. **Delete Role**
   - Remove role
   - Expected: 200 OK

### Phase 5: Permission Management 🔑

1. **List All Permissions**
   - View system permissions
   - Expected: 200 OK

2. **Create New Permission**
   - Define custom permission
   - Auto-saves permission_id
   - Expected: 201 Created

3. **Get Permission by ID**
   - Retrieve specific permission
   - Expected: 200 OK

4. **Update Permission**
   - Modify permission
   - Expected: 200 OK

5. **Delete Permission**
   - Remove permission
   - Expected: 200 OK

### Phase 6: Error Testing 🧪

1. **401 - Unauthorized Access**
   - Access protected endpoint without token
   - Expected: 401 Unauthorized

2. **404 - Not Found**
   - Request non-existent resource
   - Expected: 404 Not Found

3. **422 - Validation Error**
   - Submit invalid data
   - Expected: 422 Unprocessable Entity

4. **Invalid API Endpoint**
   - Call non-existent endpoint
   - Expected: 404 with fallback message

### Phase 7: Session Management 🔓

1. **Logout Current Session**
   - Invalidate current token
   - Expected: 200 OK

2. **Logout All Sessions**
   - Invalidate all user tokens
   - Expected: 200 OK

## 🔄 Automated Features

### Token Management
- **Auto-Save Tokens**: Login requests automatically save tokens to environment
- **Auto-Use Tokens**: Protected endpoints automatically use saved tokens
- **Token Refresh**: Refresh endpoint updates stored token

### Variable Management
- **Dynamic IDs**: Created resources auto-save their IDs for subsequent tests
- **Environment Variables**: All configuration stored in environment
- **Global Scripts**: Automatic response time and content-type validation

## 📊 Response Validation

### Global Tests (Applied to All Requests)
- Response time under 2000ms
- Content-Type is application/json
- Error logging for failed requests

### Specific Validations
- Authentication: Token extraction and storage
- CRUD Operations: ID extraction for chaining
- Error Responses: Proper HTTP status codes

## 🛠 Configuration Variables

### Environment Variables
```
base_url: http://127.0.0.1:8000
api_version: v1
admin_email: admin@example.com
admin_password: password (secret)
access_token: (auto-populated)
user_id: (auto-populated)
role_id: (auto-populated)
permission_id: (auto-populated)
```

## 🔐 Security Testing

### Authentication Tests
- ✅ Valid credentials acceptance
- ✅ Invalid credentials rejection
- ✅ Token validation
- ✅ Token refresh mechanism
- ✅ Protected endpoint access control

### Authorization Tests
- ✅ Role-based access control
- ✅ Permission verification
- ✅ Resource ownership validation

## 📈 Performance Monitoring

### Response Time Tracking
- All requests monitored for response time
- Alerts if responses exceed 2000ms
- Performance data logged to console

### Error Monitoring
- Automatic error response logging
- Status code validation
- Detailed error message capture

## 🚦 Status Codes Reference

| Code | Meaning | When to Expect |
|------|---------|----------------|
| 200 | OK | Successful GET, PUT, PATCH |
| 201 | Created | Successful POST (create) |
| 401 | Unauthorized | Missing/invalid token |
| 403 | Forbidden | Insufficient permissions |
| 404 | Not Found | Resource doesn't exist |
| 422 | Unprocessable Entity | Validation errors |
| 500 | Internal Server Error | Server-side errors |

## 🔍 Troubleshooting

### Common Issues

1. **401 Unauthorized**
   - Run "Login - Admin User" first
   - Check if token is saved in environment
   - Verify server is running

2. **404 Not Found**
   - Ensure Laravel server is running on port 8000
   - Check route exists in Laravel
   - Verify API version in URL

3. **500 Internal Server Error**
   - Check Laravel logs: `storage/logs/laravel.log`
   - Verify database connection
   - Ensure migrations are run

4. **Network Error**
   - Verify server is running: `php artisan serve`
   - Check firewall settings
   - Confirm port 8000 is available

### Debug Mode
- Enable Postman Console for detailed request/response logs
- Check environment variables are properly set
- Verify collection variables are populated

## 📞 Support

For issues or questions:
1. Check Laravel logs in `storage/logs/laravel.log`
2. Verify environment configuration
3. Test individual endpoints manually
4. Review authentication flow

## 🎯 Next Steps

After successful testing:
1. Configure production environment
2. Set up automated testing pipeline
3. Implement monitoring and alerting
4. Document API for frontend team
