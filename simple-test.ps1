#!/usr/bin/env pwsh

Write-Host "=== PKTracker API Simple Test ===" -ForegroundColor Green

# Test health endpoint
Write-Host "`n1. Testing Health Endpoint..." -ForegroundColor Yellow
try {
    $healthResponse = Invoke-RestMethod -Uri "http://localhost:8000/api/health" -Method GET
    Write-Host "✓ Health check successful" -ForegroundColor Green
    Write-Host "Status: $($healthResponse.status)" -ForegroundColor Cyan
} catch {
    Write-Host "✗ Health check failed: $($_.Exception.Message)" -ForegroundColor Red
    exit 1
}

# Test login
Write-Host "`n2. Testing Login..." -ForegroundColor Yellow
$loginBody = @{
    email = "admin@admin.com"
    password = "Admin123!"
} | ConvertTo-Json

$headers = @{
    "Content-Type" = "application/json"
    "Accept" = "application/json"
}

try {
    $loginResponse = Invoke-RestMethod -Uri "http://localhost:8000/api/v1/auth/login" -Method POST -Body $loginBody -Headers $headers
    Write-Host "✓ Login successful" -ForegroundColor Green
    $token = $loginResponse.data.access_token
    Write-Host "Token obtained: $($token.Substring(0, 50))..." -ForegroundColor Cyan
} catch {
    Write-Host "✗ Login failed: $($_.Exception.Message)" -ForegroundColor Red
    if ($_.ErrorDetails) {
        Write-Host "Details: $($_.ErrorDetails.Message)" -ForegroundColor Red
    }
    exit 1
}

# Test users endpoint
Write-Host "`n3. Testing Users Endpoint..." -ForegroundColor Yellow
$authHeaders = @{
    "Authorization" = "Bearer $token"
    "Accept" = "application/json"
}

try {
    $usersResponse = Invoke-RestMethod -Uri "http://localhost:8000/api/v1/users" -Method GET -Headers $authHeaders
    Write-Host "✓ Users endpoint successful" -ForegroundColor Green
    Write-Host "Users count: $($usersResponse.data.data.Count)" -ForegroundColor Cyan
} catch {
    Write-Host "✗ Users endpoint failed: $($_.Exception.Message)" -ForegroundColor Red
    if ($_.ErrorDetails) {
        Write-Host "Details: $($_.ErrorDetails.Message)" -ForegroundColor Red
    }
    
    # Try to get more detailed error info
    try {
        $errorResponse = $_.ErrorDetails.Message | ConvertFrom-Json
        Write-Host "Error response: $($errorResponse | ConvertTo-Json -Depth 3)" -ForegroundColor Red
    } catch {
        Write-Host "Could not parse error details" -ForegroundColor Red
    }
}

Write-Host "`n=== Test Complete ===" -ForegroundColor Green
