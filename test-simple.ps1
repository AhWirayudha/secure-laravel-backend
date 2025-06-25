# Simple test script for PKTracker API
Write-Host "=== PKTracker API Simple Test ===" -ForegroundColor Green

# Test health endpoint
Write-Host "1. Testing Health Endpoint..." -ForegroundColor Yellow
try {
    $healthResponse = Invoke-RestMethod -Uri "http://localhost:8000/api/health" -Method GET
    Write-Host "Health check successful" -ForegroundColor Green
    Write-Host "Status: $($healthResponse.status)" -ForegroundColor Cyan
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
    $token = $loginResponse.data.access_token
    Write-Host "Token obtained" -ForegroundColor Cyan
} catch {
    Write-Host "Login failed: $($_.Exception.Message)" -ForegroundColor Red
    if ($_.ErrorDetails) {
        Write-Host "Details: $($_.ErrorDetails.Message)" -ForegroundColor Red
    }
    exit 1
}

# Test users endpoint
Write-Host "3. Testing Users Endpoint..." -ForegroundColor Yellow
$authHeaders = @{
    "Authorization" = "Bearer $token"
    "Accept" = "application/json"
}

try {
    $usersResponse = Invoke-RestMethod -Uri "http://localhost:8000/api/v1/users" -Method GET -Headers $authHeaders
    Write-Host "Users endpoint successful" -ForegroundColor Green
} catch {
    Write-Host "Users endpoint failed: $($_.Exception.Message)" -ForegroundColor Red
    if ($_.ErrorDetails) {
        Write-Host "Details: $($_.ErrorDetails.Message)" -ForegroundColor Red
    }
}

Write-Host "Test Complete" -ForegroundColor Green
