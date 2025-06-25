# Test script for Role Management API
Write-Host "=== PKTracker Role Management API Test ===" -ForegroundColor Green

# Test login first
Write-Host "1. Getting Authentication Token..." -ForegroundColor Yellow
$loginBody = '{"email":"admin@admin.com","password":"Admin123!"}'
$headers = @{
    "Content-Type" = "application/json"
    "Accept" = "application/json"
}

try {
    $loginResponse = Invoke-RestMethod -Uri "http://localhost:8000/api/v1/auth/login" -Method POST -Body $loginBody -Headers $headers
    Write-Host "Login successful" -ForegroundColor Green
    $token = $loginResponse.access_token
    Write-Host "Token obtained" -ForegroundColor Cyan
} catch {
    Write-Host "Login failed: $($_.Exception.Message)" -ForegroundColor Red
    if ($_.ErrorDetails) {
        Write-Host "Details: $($_.ErrorDetails.Message)" -ForegroundColor Red
    }
    exit 1
}

# Setup auth headers
$authHeaders = @{
    "Authorization" = "Bearer $token"
    "Accept" = "application/json"
    "Content-Type" = "application/json"
}

# Test 1: Get all roles
Write-Host "`n2. Testing GET /api/v1/roles..." -ForegroundColor Yellow
try {
    $rolesResponse = Invoke-RestMethod -Uri "http://localhost:8000/api/v1/roles" -Method GET -Headers $authHeaders
    Write-Host "✓ Roles retrieved successfully" -ForegroundColor Green
    Write-Host "Roles count: $($rolesResponse.data.Count)" -ForegroundColor Cyan
    
    # Display first few roles
    if ($rolesResponse.data.Count -gt 0) {
        Write-Host "Sample roles:" -ForegroundColor Cyan
        $rolesResponse.data | Select-Object -First 3 | ForEach-Object {
            Write-Host "  - $($_.name) (ID: $($_.id))" -ForegroundColor White
        }
    }
} catch {
    Write-Host "✗ Failed to get roles: $($_.Exception.Message)" -ForegroundColor Red
    if ($_.ErrorDetails) {
        Write-Host "Details: $($_.ErrorDetails.Message)" -ForegroundColor Red
    }
}

# Test 2: Create a new role
Write-Host "`n3. Testing POST /api/v1/roles (Create Role)..." -ForegroundColor Yellow
$newRoleBody = @{
    name = "test-role-$(Get-Date -Format 'HHmmss')"
    description = "Test role created by API test"
    permissions = @("view-users")
} | ConvertTo-Json

try {
    $createResponse = Invoke-RestMethod -Uri "http://localhost:8000/api/v1/roles" -Method POST -Body $newRoleBody -Headers $authHeaders
    Write-Host "✓ Role created successfully" -ForegroundColor Green
    $newRoleId = $createResponse.data.id
    Write-Host "New role ID: $newRoleId" -ForegroundColor Cyan
} catch {
    Write-Host "✗ Failed to create role: $($_.Exception.Message)" -ForegroundColor Red
    if ($_.ErrorDetails) {
        Write-Host "Details: $($_.ErrorDetails.Message)" -ForegroundColor Red
        
        # Check if it's a rate limit error
        if ($_.Exception.Message -like "*429*" -or $_.ErrorDetails.Message -like "*rate*limit*") {
            Write-Host "⚠️  RATE LIMIT DETECTED!" -ForegroundColor Red
        }
    }
    $newRoleId = $null
}

# Test 3: Get specific role (if created)
if ($newRoleId) {
    Write-Host "`n4. Testing GET /api/v1/roles/$newRoleId..." -ForegroundColor Yellow
    try {
        $roleResponse = Invoke-RestMethod -Uri "http://localhost:8000/api/v1/roles/$newRoleId" -Method GET -Headers $authHeaders
        Write-Host "✓ Role retrieved successfully" -ForegroundColor Green
        Write-Host "Role name: $($roleResponse.data.name)" -ForegroundColor Cyan
    } catch {
        Write-Host "✗ Failed to get role: $($_.Exception.Message)" -ForegroundColor Red
        if ($_.ErrorDetails) {
            Write-Host "Details: $($_.ErrorDetails.Message)" -ForegroundColor Red
        }
    }
}

# Test 4: Test rate limiting by making rapid requests
Write-Host "`n5. Testing Rate Limiting (10 rapid requests)..." -ForegroundColor Yellow
$rateLimitErrors = 0
for ($i = 1; $i -le 10; $i++) {
    try {
        $start = Get-Date
        Invoke-RestMethod -Uri "http://localhost:8000/api/v1/roles" -Method GET -Headers $authHeaders | Out-Null
        $end = Get-Date
        $duration = ($end - $start).TotalMilliseconds
        Write-Host "Request $i`: ✓ ($([math]::Round($duration, 0))ms)" -ForegroundColor Green
        Start-Sleep -Milliseconds 100
    } catch {
        $rateLimitErrors++
        Write-Host "Request $i`: ✗ $($_.Exception.Message)" -ForegroundColor Red
        if ($_.Exception.Message -like "*429*" -or $_.ErrorDetails.Message -like "*rate*limit*") {
            Write-Host "  Rate limit hit on request $i" -ForegroundColor Red
        }
    }
}

if ($rateLimitErrors -gt 0) {
    Write-Host "`n⚠️  Rate limit errors detected: $rateLimitErrors out of 10 requests" -ForegroundColor Red
} else {
    Write-Host "`n✓ No rate limit errors in 10 requests" -ForegroundColor Green
}

Write-Host "`n=== Test Complete ===" -ForegroundColor Green
