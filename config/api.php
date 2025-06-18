<?php

return [
    /*
    |--------------------------------------------------------------------------
    | API Configuration
    |--------------------------------------------------------------------------
    */

    'version' => env('API_VERSION', 'v1'),
    'prefix' => env('API_PREFIX', 'api'),
    
    /*
    |--------------------------------------------------------------------------
    | Supported API Versions
    |--------------------------------------------------------------------------
    */

    'supported_versions' => ['v1'],

    /*
    |--------------------------------------------------------------------------
    | API Rate Limiting
    |--------------------------------------------------------------------------
    */

    'rate_limit' => [
        'requests' => env('API_RATE_LIMIT', 1000),
        'window' => env('API_RATE_LIMIT_WINDOW', 60), // minutes
    ],

    /*
    |--------------------------------------------------------------------------
    | Response Configuration
    |--------------------------------------------------------------------------
    */

    'response' => [
        'include_timestamps' => true,
        'include_version' => true,
        'include_request_id' => true,
        'wrap_response' => true,
        'wrapper_key' => 'data',
    ],

    /*
    |--------------------------------------------------------------------------
    | Error Handling
    |--------------------------------------------------------------------------
    */

    'errors' => [
        'include_trace' => env('API_INCLUDE_ERROR_TRACE', false),
        'include_source' => env('API_INCLUDE_ERROR_SOURCE', false),
        'mask_internal_errors' => env('API_MASK_INTERNAL_ERRORS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Documentation Configuration
    |--------------------------------------------------------------------------
    */

    'documentation' => [
        'enabled' => env('API_DOCS_ENABLED', true),
        'path' => 'api/documentation',
        'swagger_version' => '3.0.0',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Configuration
    |--------------------------------------------------------------------------
    */

    'auth' => [
        'token_lifetime' => env('API_TOKEN_LIFETIME', 30), // days
        'refresh_token_lifetime' => env('API_REFRESH_TOKEN_LIFETIME', 90), // days
        'max_tokens_per_user' => env('MAX_TOKENS_PER_USER', 10),
        'require_email_verification' => env('API_REQUIRE_EMAIL_VERIFICATION', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Validation Configuration
    |--------------------------------------------------------------------------
    */

    'validation' => [
        'max_page_size' => 100,
        'default_page_size' => 15,
        'allow_include_parameter' => true,
        'allowed_includes' => [],
        'allow_sort_parameter' => true,
        'allowed_sorts' => [],
        'allow_filter_parameter' => true,
        'allowed_filters' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Caching Configuration
    |--------------------------------------------------------------------------
    */

    'cache' => [
        'enabled' => env('API_CACHE_ENABLED', true),
        'ttl' => env('API_CACHE_TTL', 3600), // seconds
        'key_prefix' => 'api:',
        'cache_authenticated_requests' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Transformation Configuration
    |--------------------------------------------------------------------------
    */

    'transformers' => [
        'namespace' => 'App\\Http\\Resources\\Api',
        'auto_discover' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | CORS Configuration
    |--------------------------------------------------------------------------
    */

    'cors' => [
        'paths' => ['api/*'],
        'allowed_methods' => explode(',', env('CORS_ALLOWED_METHODS', 'GET,POST,PUT,PATCH,DELETE,OPTIONS')),
        'allowed_origins' => explode(',', env('CORS_ALLOWED_ORIGINS', '*')),
        'allowed_origins_patterns' => [],
        'allowed_headers' => explode(',', env('CORS_ALLOWED_HEADERS', 'Content-Type,Authorization,X-Requested-With')),
        'exposed_headers' => explode(',', env('CORS_EXPOSED_HEADERS', '')),
        'max_age' => env('CORS_MAX_AGE', 86400),
        'supports_credentials' => env('CORS_SUPPORTS_CREDENTIALS', true),
    ],
];
