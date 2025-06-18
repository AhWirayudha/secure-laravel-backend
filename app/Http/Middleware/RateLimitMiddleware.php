<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class RateLimitMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $key = 'global', int $maxAttempts = 60, int $decayMinutes = 1): Response
    {
        $rateLimitKey = $this->resolveRequestSignature($request, $key);
        
        if (RateLimiter::tooManyAttempts($rateLimitKey, $maxAttempts)) {
            $retryAfter = RateLimiter::availableIn($rateLimitKey);
            
            return response()->json([
                'error' => 'Too many requests',
                'message' => 'Rate limit exceeded. Please try again in ' . $retryAfter . ' seconds.',
                'retry_after' => $retryAfter
            ], 429, [
                'Retry-After' => $retryAfter,
                'X-RateLimit-Limit' => $maxAttempts,
                'X-RateLimit-Remaining' => 0,
            ]);
        }

        RateLimiter::hit($rateLimitKey, $decayMinutes * 60);

        $response = $next($request);

        // Add rate limit headers
        $remaining = RateLimiter::remaining($rateLimitKey, $maxAttempts);
        $response->headers->add([
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => $remaining,
        ]);

        return $response;
    }

    /**
     * Resolve the request signature for rate limiting.
     */
    protected function resolveRequestSignature(Request $request, string $key): string
    {
        $user = $request->user();
        $ip = $request->ip();
        
        switch ($key) {
            case 'api':
                return $user ? "api:user:{$user->id}" : "api:ip:{$ip}";
            case 'auth':
                return "auth:ip:{$ip}";
            case 'global':
                return $user ? "global:user:{$user->id}" : "global:ip:{$ip}";
            default:
                return "{$key}:ip:{$ip}";
        }
    }
}
