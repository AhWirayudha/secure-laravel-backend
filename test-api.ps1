# PKTracker API Quick Test Script (PowerShell)
# This script tests the basic API endpoints to verify everything is working

Write-Host "🚀 PKTracker API Quick Test" -ForegroundColor Green
Write-Host "==========================" -ForegroundColor Green
Write-Host ""

$BaseUrl = "http://127.0.0.1:8000"
$ApiVersion = "v1"

# Check if server is running
Write-Host "📡 Testing server connectivity..." -ForegroundColor Yellow
try {
    $healthCheck = Invoke-WebRequest -Uri "$BaseUrl/api/health" -Method GET -TimeoutSec 5 -ErrorAction Stop
    Write-Host "✅ Server is running" -ForegroundColor Green
} catch {
    Write-Host "❌ Server is not responding. Please start with: php artisan serve" -ForegroundColor Red
    exit 1
}

Write-Host ""

# Test health endpoint
Write-Host "🏥 Testing health endpoint..." -ForegroundColor Yellow
try {
    $healthResponse = Invoke-WebRequest -Uri "$BaseUrl/api/health" -Method GET -Headers @{"Accept"="application/json"}
    Write-Host "Response: $($healthResponse.Content)" -ForegroundColor Cyan
    Write-Host "✅ Health endpoint working" -ForegroundColor Green
} catch {
    Write-Host "❌ Health endpoint failed: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host ""

# Test basic API endpoint
Write-Host "🧪 Testing basic API endpoint..." -ForegroundColor Yellow
try {
    $testResponse = Invoke-WebRequest -Uri "$BaseUrl/api/test" -Method GET -Headers @{"Accept"="application/json"}
    Write-Host "Response: $($testResponse.Content)" -ForegroundColor Cyan
    Write-Host "✅ Basic API endpoint working" -ForegroundColor Green
} catch {
    Write-Host "❌ Basic API endpoint failed: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host ""

# Test authentication
Write-Host "🔐 Testing authentication..." -ForegroundColor Yellow
$loginBody = @{
    email = "admin@example.com"
    password = "password"
} | ConvertTo-Json

try {
    $loginResponse = Invoke-WebRequest -Uri "$BaseUrl/api/$ApiVersion/auth/login" -Method POST `
        -Headers @{"Accept"="application/json"; "Content-Type"="application/json"} `
        -Body $loginBody
    
    $loginData = $loginResponse.Content | ConvertFrom-Json
    Write-Host "Login Response: $($loginResponse.Content)" -ForegroundColor Cyan
    
    if ($loginData.access_token) {
        Write-Host "✅ Authentication successful" -ForegroundColor Green
        $token = $loginData.access_token
        $tokenPreview = $token.Substring(0, [Math]::Min(20, $token.Length))
        Write-Host "Token extracted: $tokenPreview..." -ForegroundColor Cyan
        
        # Test protected endpoint
        Write-Host ""
        Write-Host "🔒 Testing protected endpoint..." -ForegroundColor Yellow
        try {
            $userResponse = Invoke-WebRequest -Uri "$BaseUrl/api/$ApiVersion/auth/user" -Method GET `
                -Headers @{"Accept"="application/json"; "Authorization"="Bearer $token"}
            
            Write-Host "Protected endpoint response: $($userResponse.Content)" -ForegroundColor Cyan
            
            $userData = $userResponse.Content | ConvertFrom-Json
            if ($userData.email) {
                Write-Host "✅ Protected endpoint access successful" -ForegroundColor Green
            } else {
                Write-Host "❌ Protected endpoint access failed - no user data" -ForegroundColor Red
            }
        } catch {
            Write-Host "❌ Protected endpoint access failed: $($_.Exception.Message)" -ForegroundColor Red
        }
    } else {
        Write-Host "❌ Authentication failed - no token received" -ForegroundColor Red
    }
} catch {
    Write-Host "❌ Authentication failed: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host ""
Write-Host "🎉 Quick test completed!" -ForegroundColor Green
Write-Host ""
Write-Host "To use the full Postman collection:" -ForegroundColor Yellow
Write-Host "1. Import: postman/PKTracker_API_Collection.json" -ForegroundColor White
Write-Host "2. Import: postman/PKTracker_Development_Environment.json" -ForegroundColor White
Write-Host "3. Set 'PKTracker Development' as active environment" -ForegroundColor White
Write-Host "4. Start with 'Login - Admin User' request" -ForegroundColor White
