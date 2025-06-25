# Fixed test script for PKTracker API
Write-Host "=== PKTracker API Auth Test ===" -ForegroundColor Green

# Test health endpoint
Write-Host "1. Testing Health Endpoint..." -ForegroundColor Yellow
try {
    $healthResponse = Invoke-RestMethod -Uri "http://localhost:8000/api/health" -Method GET
    Write-Host "Health check successful" -ForegroundColor Green
} catch {
    Write-Host "Health check failed: $($_.Exception.Message)" -ForegroundColor Red
    exit 1
}

# Test login
Write-Host "2. Testing Login..." -ForegroundColor Yellow
$loginBody = '{"email":"admin@admin.com","password":"Admin123!"}'
$headers = @{
    "Content-Type" = "application/json"
    "Accept" = "application/json"
}

try {
    $loginResponse = Invoke-RestMethod -Uri "http://localhost:8000/api/v1/auth/login" -Method POST -Body $loginBody -Headers $headers
    Write-Host "Login successful" -ForegroundColor Green
    $token = $loginResponse.access_token
    Write-Host "Token: $($token.Substring(0, 50))..." -ForegroundColor Cyan
    Write-Host "User: $($loginResponse.user.name)" -ForegroundColor Cyan
} catch {
    Write-Host "Login failed: $($_.Exception.Message)" -ForegroundColor Red
    if ($_.ErrorDetails) {
        Write-Host "Details: $($_.ErrorDetails.Message)" -ForegroundColor Red
    }
    exit 1
}

# Test simple auth endpoint
Write-Host "3. Testing Simple Auth Endpoint..." -ForegroundColor Yellow
$authHeaders = @{
    "Authorization" = "Bearer $token"
    "Accept" = "application/json"
}

try {
    $authResponse = Invoke-RestMethod -Uri "http://localhost:8000/api/test-auth" -Method GET -Headers $authHeaders
    Write-Host "Simple auth successful" -ForegroundColor Green
    Write-Host "User: $($authResponse.user.name)" -ForegroundColor Cyan
    Write-Host "Guard: $($authResponse.guard)" -ForegroundColor Cyan
} catch {
    Write-Host "Simple auth failed: $($_.Exception.Message)" -ForegroundColor Red
    if ($_.ErrorDetails) {
        Write-Host "Details: $($_.ErrorDetails.Message)" -ForegroundColor Red
    }
}

# Test users endpoint
Write-Host "4. Testing Users Endpoint..." -ForegroundColor Yellow
try {
    $usersResponse = Invoke-RestMethod -Uri "http://localhost:8000/api/v1/users" -Method GET -Headers $authHeaders
    Write-Host "Users endpoint successful" -ForegroundColor Green
    Write-Host "Users retrieved: $($usersResponse.data.data.Count)" -ForegroundColor Cyan
} catch {
    Write-Host "Users endpoint failed: $($_.Exception.Message)" -ForegroundColor Red
    if ($_.ErrorDetails) {
        Write-Host "Details: $($_.ErrorDetails.Message)" -ForegroundColor Red
    }
}

Write-Host "Test Complete" -ForegroundColor Green
