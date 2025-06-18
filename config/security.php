<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Security Headers Configuration
    |--------------------------------------------------------------------------
    |
    | This array contains the configuration for various security headers
    | that will be applied to HTTP responses.
    |
    */

    'headers' => [
        'enabled' => env('SECURE_HEADERS_ENABLED', true),

        'csp' => [
            'enabled' => env('CSP_ENABLED', true),
            'directives' => [
                'default-src' => "'self'",
                'script-src' => "'self' 'unsafe-inline' 'unsafe-eval'",
                'style-src' => "'self' 'unsafe-inline'",
                'img-src' => "'self' data: https:",
                'font-src' => "'self' data:",
                'connect-src' => "'self'",
                'media-src' => "'self'",
                'object-src' => "'none'",
                'child-src' => "'self'",
                'form-action' => "'self'",
                'base-uri' => "'self'",
                'manifest-src' => "'self'",
            ],
        ],

        'hsts' => [
            'enabled' => env('HSTS_ENABLED', true),
            'max_age' => env('HSTS_MAX_AGE', 31536000), // 1 year
            'include_subdomains' => env('HSTS_INCLUDE_SUBDOMAINS', true),
            'preload' => env('HSTS_PRELOAD', false),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting Configuration
    |--------------------------------------------------------------------------
    */

    'rate_limiting' => [
        'global' => [
            'max_attempts' => env('RATE_LIMIT_PER_MINUTE', 60),
            'decay_minutes' => 1,
        ],
        'api' => [
            'max_attempts' => env('RATE_LIMIT_API_PER_MINUTE', 100),
            'decay_minutes' => 1,
        ],
        'auth' => [
            'max_attempts' => env('RATE_LIMIT_LOGIN_PER_MINUTE', 5),
            'decay_minutes' => 1,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Input Validation Configuration
    |--------------------------------------------------------------------------
    */

    'validation' => [
        'password' => [
            'min_length' => 8,
            'require_uppercase' => true,
            'require_lowercase' => true,
            'require_numbers' => true,
            'require_symbols' => true,
            'require_uncompromised' => true,
        ],
        'sanitize_input' => true,
        'strip_tags' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | File Upload Security
    |--------------------------------------------------------------------------
    */

    'uploads' => [
        'max_file_size' => env('MAX_UPLOAD_SIZE', 10240), // KB
        'allowed_mime_types' => [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'application/pdf',
            'text/plain',
            'application/json',
        ],
        'forbidden_extensions' => [
            'php', 'phtml', 'php3', 'php4', 'php5', 'php7', 'phps',
            'asp', 'aspx', 'jsp', 'exe', 'bat', 'cmd', 'com', 'pif',
            'scr', 'vbs', 'js', 'jar', 'sh', 'py', 'pl', 'rb',
        ],
        'scan_uploads' => env('SCAN_UPLOADS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Session Security
    |--------------------------------------------------------------------------
    */

    'session' => [
        'secure_cookies' => env('SESSION_SECURE_COOKIE', false),
        'http_only' => true,
        'same_site' => 'strict',
        'regenerate_on_login' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | API Security
    |--------------------------------------------------------------------------
    */

    'api' => [
        'require_https' => env('API_REQUIRE_HTTPS', false),
        'token_lifetime_days' => env('API_TOKEN_LIFETIME', 30),
        'refresh_token_lifetime_days' => env('API_REFRESH_TOKEN_LIFETIME', 90),
        'max_tokens_per_user' => env('MAX_TOKENS_PER_USER', 10),
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging Security
    |--------------------------------------------------------------------------
    */

    'logging' => [
        'mask_sensitive_data' => true,
        'sensitive_fields' => [
            'password',
            'password_confirmation',
            'token',
            'secret',
            'key',
            'credit_card',
            'ssn',
            'social_security',
        ],
        'log_failed_attempts' => true,
        'log_successful_auth' => true,
        'max_log_size' => env('MAX_LOG_SIZE', 100), // MB
    ],

    /*
    |--------------------------------------------------------------------------
    | Monitoring and Alerting
    |--------------------------------------------------------------------------
    */

    'monitoring' => [
        'failed_login_threshold' => 10,
        'suspicious_activity_threshold' => 50,
        'alert_emails' => env('SECURITY_ALERT_EMAILS', ''),
        'enable_slack_alerts' => env('ENABLE_SLACK_ALERTS', false),
        'slack_webhook_url' => env('SLACK_WEBHOOK_URL', ''),
    ],
];
