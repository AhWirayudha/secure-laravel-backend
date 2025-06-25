#!/bin/bash

# PKTracker API Quick Test Script
# This script tests the basic API endpoints to verify everything is working

echo "🚀 PKTracker API Quick Test"
echo "=========================="
echo ""

BASE_URL="http://127.0.0.1:8000"
API_VERSION="v1"

# Check if server is running
echo "📡 Testing server connectivity..."
if curl -s "$BASE_URL/api/health" > /dev/null; then
    echo "✅ Server is running"
else
    echo "❌ Server is not responding. Please start with: php artisan serve"
    exit 1
fi

echo ""

# Test health endpoint
echo "🏥 Testing health endpoint..."
HEALTH_RESPONSE=$(curl -s -H "Accept: application/json" "$BASE_URL/api/health")
echo "Response: $HEALTH_RESPONSE"
echo ""

# Test basic API endpoint
echo "🧪 Testing basic API endpoint..."
TEST_RESPONSE=$(curl -s -H "Accept: application/json" "$BASE_URL/api/test")
echo "Response: $TEST_RESPONSE"
echo ""

# Test authentication
echo "🔐 Testing authentication..."
LOGIN_RESPONSE=$(curl -s -X POST \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password"}' \
  "$BASE_URL/api/$API_VERSION/auth/login")

echo "Login Response: $LOGIN_RESPONSE"

# Extract token (basic extraction - may need adjustment based on response format)
if echo "$LOGIN_RESPONSE" | grep -q "access_token"; then
    echo "✅ Authentication successful"
    TOKEN=$(echo "$LOGIN_RESPONSE" | sed -n 's/.*"access_token":"\([^"]*\)".*/\1/p')
    echo "Token extracted: ${TOKEN:0:20}..."
    
    # Test protected endpoint
    echo ""
    echo "🔒 Testing protected endpoint..."
    USER_RESPONSE=$(curl -s -H "Accept: application/json" \
      -H "Authorization: Bearer $TOKEN" \
      "$BASE_URL/api/$API_VERSION/auth/user")
    echo "Protected endpoint response: $USER_RESPONSE"
    
    if echo "$USER_RESPONSE" | grep -q "email"; then
        echo "✅ Protected endpoint access successful"
    else
        echo "❌ Protected endpoint access failed"
    fi
else
    echo "❌ Authentication failed"
fi

echo ""
echo "🎉 Quick test completed!"
echo ""
echo "To use the full Postman collection:"
echo "1. Import: postman/PKTracker_API_Collection.json"
echo "2. Import: postman/PKTracker_Development_Environment.json"
echo "3. Set 'PKTracker Development' as active environment"
echo "4. Start with 'Login - Admin User' request"
