# Simple Role Management API Test
Write-Host "=== PKTracker Role Management API Test ===" -ForegroundColor Green

# Get auth token
$loginBody = '{"email":"admin@admin.com","password":"Admin123!"}'
$headers = @{
    "Content-Type" = "application/json"
    "Accept" = "application/json"
}

$loginResponse = Invoke-RestMethod -Uri "http://localhost:8000/api/v1/auth/login" -Method POST -Body $loginBody -Headers $headers
$token = $loginResponse.access_token
Write-Host "Login successful" -ForegroundColor Green

$authHeaders = @{
    "Authorization" = "Bearer $token"
    "Accept" = "application/json"
    "Content-Type" = "application/json"
}

# Test 1: Get roles
Write-Host "Testing GET roles..." -ForegroundColor Yellow
try {
    $rolesResponse = Invoke-RestMethod -Uri "http://localhost:8000/api/v1/roles" -Method GET -Headers $authHeaders
    Write-Host "Success: Retrieved $($rolesResponse.data.Count) roles" -ForegroundColor Green
} catch {
    Write-Host "Error: $($_.Exception.Message)" -ForegroundColor Red
    if ($_.ErrorDetails) {
        Write-Host "Details: $($_.ErrorDetails.Message)" -ForegroundColor Red
    }
}

# Test 2: Create role
Write-Host "Testing POST role creation..." -ForegroundColor Yellow
$newRoleBody = '{"name":"test-role-123","description":"Test role"}'
try {
    $createResponse = Invoke-RestMethod -Uri "http://localhost:8000/api/v1/roles" -Method POST -Body $newRoleBody -Headers $authHeaders
    Write-Host "Success: Created role with ID $($createResponse.data.id)" -ForegroundColor Green
} catch {
    Write-Host "Error: $($_.Exception.Message)" -ForegroundColor Red
    if ($_.ErrorDetails) {
        Write-Host "Details: $($_.ErrorDetails.Message)" -ForegroundColor Red
    }
    
    # Check for rate limit
    if ($_.Exception.Message -like "*429*") {
        Write-Host "RATE LIMIT DETECTED!" -ForegroundColor Red
    }
}

# Test 3: Rate limit test
Write-Host "Testing rate limits with 5 rapid requests..." -ForegroundColor Yellow
for ($i = 1; $i -le 5; $i++) {
    try {
        Invoke-RestMethod -Uri "http://localhost:8000/api/v1/roles" -Method GET -Headers $authHeaders | Out-Null
        Write-Host "Request $i : OK" -ForegroundColor Green
    } catch {
        Write-Host "Request $i : ERROR - $($_.Exception.Message)" -ForegroundColor Red
        if ($_.Exception.Message -like "*429*") {
            Write-Host "Rate limit hit!" -ForegroundColor Red
        }
    }
    Start-Sleep -Milliseconds 50
}

Write-Host "Test complete" -ForegroundColor Green
