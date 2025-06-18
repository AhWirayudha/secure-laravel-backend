<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ApiLoggingMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        
        // Get request details
        $requestData = [
            'method' => $request->getMethod(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()->toISOString(),
        ];

        // Add user info if available
        if ($request->user()) {
            $requestData['user_id'] = $request->user()->id;
            $requestData['user_email'] = $request->user()->email;
        }

        // Process the request
        $response = $next($request);

        // Calculate response time
        $responseTime = round((microtime(true) - $startTime) * 1000, 2);

        // Log the API call
        $logData = array_merge($requestData, [
            'status_code' => $response->getStatusCode(),
            'response_time_ms' => $responseTime,
            'memory_usage' => memory_get_usage(true),
        ]);

        // Log to API channel
        Log::channel('api')->info('API Request', $logData);

        // Add response headers for monitoring
        $response->headers->set('X-Response-Time', $responseTime . 'ms');
        $response->headers->set('X-Request-ID', uniqid('req_', true));

        return $response;
    }
}
