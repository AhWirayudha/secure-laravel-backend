<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (!config('security.headers.enabled', true)) {
            return $response;
        }

        // Content Security Policy
        if (config('security.headers.csp.enabled', true)) {
            $cspDirectives = [
                "default-src 'self'",
                "script-src 'self' 'unsafe-inline' 'unsafe-eval'",
                "style-src 'self' 'unsafe-inline'",
                "img-src 'self' data: https:",
                "font-src 'self' data:",
                "connect-src 'self'",
                "media-src 'self'",
                "object-src 'none'",
                "child-src 'self'",
                "form-action 'self'",
                "base-uri 'self'",
                "manifest-src 'self'"
            ];
            
            $response->headers->set('Content-Security-Policy', implode('; ', $cspDirectives));
        }

        // HTTP Strict Transport Security
        if (config('security.headers.hsts.enabled', true) && $request->secure()) {
            $maxAge = config('security.headers.hsts.max_age', 31536000);
            $hstsValue = "max-age={$maxAge}";
            
            if (config('security.headers.hsts.include_subdomains', true)) {
                $hstsValue .= '; includeSubDomains';
            }
            
            if (config('security.headers.hsts.preload', false)) {
                $hstsValue .= '; preload';
            }
            
            $response->headers->set('Strict-Transport-Security', $hstsValue);
        }

        // X-Frame-Options
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // X-Content-Type-Options
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // X-XSS-Protection
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Referrer Policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions Policy
        $permissionsDirectives = [
            'camera=()',
            'microphone=()',
            'geolocation=()',
            'interest-cohort=()',
            'payment=()',
            'usb=()',
            'magnetometer=()',
            'gyroscope=()',
            'fullscreen=(self)',
            'sync-xhr=()'
        ];
        $response->headers->set('Permissions-Policy', implode(', ', $permissionsDirectives));

        // Remove server information
        $response->headers->remove('Server');
        $response->headers->remove('X-Powered-By');

        // Cross-Origin-Embedder-Policy
        $response->headers->set('Cross-Origin-Embedder-Policy', 'require-corp');

        // Cross-Origin-Opener-Policy
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');

        // Cross-Origin-Resource-Policy
        $response->headers->set('Cross-Origin-Resource-Policy', 'same-origin');

        return $response;
    }
}
