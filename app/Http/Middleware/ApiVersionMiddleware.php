<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiVersionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $version = 'v1'): Response
    {
        // Set API version in request for later use
        $request->attributes->set('api_version', $version);
        
        // Validate API version
        $supportedVersions = config('api.supported_versions', ['v1']);
        
        if (!in_array($version, $supportedVersions)) {
            return response()->json([
                'error' => 'Unsupported API version',
                'message' => "API version '{$version}' is not supported. Supported versions: " . implode(', ', $supportedVersions),
                'supported_versions' => $supportedVersions
            ], 400);
        }

        $response = $next($request);

        // Add API version to response headers
        $response->headers->set('X-API-Version', $version);
        
        return $response;
    }
}
