<?php

namespace App\Modules\Auth\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Requests\LoginRequest;
use App\Modules\Auth\Requests\RegisterRequest;
use App\Modules\User\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Laravel\Passport\Token;

class AuthController extends Controller
{
    /**
     * Register a new user.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Assign default role
            $user->assignRole('user');

            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            $token->expires_at = now()->addDays(config('auth.token_lifetime', 30));
            $token->save();

            Log::info('User registered successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip(),
                'user_agent' => $request->header('User-Agent')
            ]);

            return response()->json([
                'message' => 'User registered successfully',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => $user->getRoleNames(),
                ],
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => $token->expires_at->toDateTimeString()
            ], 201);

        } catch (\Exception $e) {
            Log::error('User registration failed', [
                'email' => $request->email,
                'error' => $e->getMessage(),
                'ip' => $request->ip()
            ]);

            return response()->json([
                'error' => 'Registration failed',
                'message' => 'Unable to create user account'
            ], 500);
        }
    }

    /**
     * Login user and create token.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $rateLimitKey = 'login:' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            $retryAfter = RateLimiter::availableIn($rateLimitKey);
            
            Log::warning('Login rate limit exceeded', [
                'ip' => $request->ip(),
                'email' => $request->email,
                'retry_after' => $retryAfter
            ]);

            return response()->json([
                'error' => 'Too many login attempts',
                'message' => 'Please try again in ' . $retryAfter . ' seconds',
                'retry_after' => $retryAfter
            ], 429);
        }

        $credentials = $request->only('email', 'password');

        // Find user by email
        $user = User::where('email', $credentials['email'])->first();

        // Check if user exists and password is correct
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            RateLimiter::hit($rateLimitKey, 300); // 5 minutes
            
            Log::warning('Failed login attempt', [
                'email' => $request->email,
                'ip' => $request->ip(),
                'user_agent' => $request->header('User-Agent')
            ]);

            return response()->json([
                'error' => 'Invalid credentials',
                'message' => 'The provided credentials are incorrect'
            ], 401);
        }

        // Check if user is active
        if (!$user->is_active) {
            return response()->json([
                'error' => 'Account disabled',
                'message' => 'Your account has been disabled'
            ], 403);
        }

        RateLimiter::clear($rateLimitKey);

        // Create Passport token
        $tokenResult = $user->createToken('PKTracker API Token');
        $token = $tokenResult->accessToken;

        Log::info('User logged in successfully', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip(),
            'user_agent' => $request->header('User-Agent')
        ]);

        return response()->json([
            'message' => 'Login successful',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->getRoleNames(),
                'permissions' => $user->getAllPermissions()->pluck('name'),
            ],
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    /**
     * Get authenticated user details.
     */
    public function user(Request $request): JsonResponse
    {
        $user = $request->user();
        
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'roles' => $user->getRoleNames(),
                'permissions' => $user->getAllPermissions()->pluck('name'),
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]
        ]);
    }

    /**
     * Logout user (revoke token).
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        $token = $user->token();
        
        if ($token instanceof Token) {
            $token->revoke();
        }

        Log::info('User logged out', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip()
        ]);

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Logout from all devices (revoke all tokens).
     */
    public function logoutAll(Request $request): JsonResponse
    {
        $user = $request->user();
        
        // Revoke all tokens for the user
        $user->tokens()->update(['revoked' => true]);

        Log::info('User logged out from all devices', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip()
        ]);

        return response()->json([
            'message' => 'Successfully logged out from all devices'
        ]);
    }

    /**
     * Refresh token.
     */
    public function refresh(Request $request): JsonResponse
    {
        $user = $request->user();
        $oldToken = $user->token();
        
        if ($oldToken instanceof Token) {
            $oldToken->revoke();
        }

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->expires_at = now()->addDays(config('auth.token_lifetime', 30));
        $token->save();

        Log::info('Token refreshed', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip()
        ]);

        return response()->json([
            'message' => 'Token refreshed successfully',
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => $token->expires_at->toDateTimeString()
        ]);
    }
}
