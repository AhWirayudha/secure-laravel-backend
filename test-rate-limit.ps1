# Test rate limiting with role endpoints
Write-Host "=== Rate Limiting Test ===" -ForegroundColor Green

# Get auth token
$loginBody = '{"email":"admin@admin.com","password":"Admin123!"}'
$headers = @{ "Content-Type" = "application/json"; "Accept" = "application/json" }
$loginResponse = Invoke-RestMethod -Uri "http://localhost:8000/api/v1/auth/login" -Method POST -Body $loginBody -Headers $headers
$token = $loginResponse.access_token
$authHeaders = @{ "Authorization" = "Bearer $token"; "Accept" = "application/json" }

Write-Host "Making 20 rapid requests to test rate limiting..." -ForegroundColor Yellow

$successCount = 0
$errorCount = 0
$rateLimitHit = $false

for ($i = 1; $i -le 20; $i++) {
    try {
        $response = Invoke-RestMethod -Uri "http://localhost:8000/api/v1/roles" -Method GET -Headers $authHeaders
        $successCount++
        Write-Host "Request $i : SUCCESS" -ForegroundColor Green
        
        # Check rate limit headers if they exist
        # Note: PowerShell Invoke-RestMethod doesn't easily show response headers
        
    } catch {
        $errorCount++
        if ($_.Exception.Message -like "*429*") {
            $rateLimitHit = $true
            Write-Host "Request $i : RATE LIMITED (429)" -ForegroundColor Red
        } else {
            Write-Host "Request $i : ERROR - $($_.Exception.Message)" -ForegroundColor Yellow
        }
    }
    
    # Small delay to avoid overwhelming
    Start-Sleep -Milliseconds 10
}

Write-Host "`nResults:" -ForegroundColor Cyan
Write-Host "  Successful requests: $successCount" -ForegroundColor Green
Write-Host "  Failed requests: $errorCount" -ForegroundColor Red
Write-Host "  Rate limit triggered: $rateLimitHit" -ForegroundColor Yellow

if ($rateLimitHit) {
    Write-Host "`n✓ Rate limiting is working!" -ForegroundColor Green
} else {
    Write-Host "`n? Rate limiting may not have been triggered (limit might be high)" -ForegroundColor Yellow
}

Write-Host "`nTesting with higher frequency..." -ForegroundColor Yellow
Write-Host "Making 10 requests with no delay..." -ForegroundColor Yellow

$rapidSuccess = 0
$rapidErrors = 0
$rapidRateLimit = $false

for ($i = 1; $i -le 10; $i++) {
    try {
        Invoke-RestMethod -Uri "http://localhost:8000/api/v1/roles" -Method GET -Headers $authHeaders | Out-Null
        $rapidSuccess++
        Write-Host "Rapid $i : OK" -ForegroundColor Green
    } catch {
        $rapidErrors++
        if ($_.Exception.Message -like "*429*") {
            $rapidRateLimit = $true
            Write-Host "Rapid $i : RATE LIMITED" -ForegroundColor Red
        } else {
            Write-Host "Rapid $i : ERROR" -ForegroundColor Yellow
        }
    }
}

Write-Host "`nRapid test results:" -ForegroundColor Cyan
Write-Host "  Success: $rapidSuccess, Errors: $rapidErrors, Rate limited: $rapidRateLimit" -ForegroundColor White

Write-Host "`n=== Test Complete ===" -ForegroundColor Green
