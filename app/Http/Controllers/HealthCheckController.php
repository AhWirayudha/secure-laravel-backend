<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;

class HealthCheckController extends Controller
{
    /**
     * Basic health check endpoint
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'timestamp' => now()->toISOString(),
            'version' => config('app.version', '1.0.0'),
            'environment' => config('app.env'),
        ]);
    }

    /**
     * Detailed health check with dependencies
     */
    public function detailed(): JsonResponse
    {
        $checks = [
            'app' => $this->checkApplication(),
            'database' => $this->checkDatabase(),
            'cache' => $this->checkCache(),
            'queue' => $this->checkQueue(),
        ];

        // Add Redis check if configured
        if (config('database.redis.default.host')) {
            $checks['redis'] = $this->checkRedis();
        }

        $overall = collect($checks)->every(fn($check) => $check['status'] === 'ok') ? 'ok' : 'error';

        return response()->json([
            'status' => $overall,
            'timestamp' => now()->toISOString(),
            'version' => config('app.version', '1.0.0'),
            'environment' => config('app.env'),
            'checks' => $checks,
        ], $overall === 'ok' ? 200 : 503);
    }

    private function checkApplication(): array
    {
        try {
            return [
                'status' => 'ok',
                'message' => 'Application is running',
                'details' => [
                    'php_version' => PHP_VERSION,
                    'laravel_version' => app()->version(),
                    'memory_usage' => memory_get_usage(true),
                    'memory_peak' => memory_get_peak_usage(true),
                ]
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }

    private function checkDatabase(): array
    {
        try {
            DB::connection()->getPdo();
            $time = microtime(true);
            DB::select('SELECT 1');
            $responseTime = round((microtime(true) - $time) * 1000, 2);

            return [
                'status' => 'ok',
                'message' => 'Database connection successful',
                'details' => [
                    'connection' => config('database.default'),
                    'response_time_ms' => $responseTime,
                ]
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Database connection failed: ' . $e->getMessage(),
            ];
        }
    }

    private function checkCache(): array
    {
        try {
            $key = 'health_check_' . time();
            $value = 'test_value';
            
            Cache::put($key, $value, 60);
            $retrieved = Cache::get($key);
            Cache::forget($key);

            if ($retrieved === $value) {
                return [
                    'status' => 'ok',
                    'message' => 'Cache is working',
                    'details' => [
                        'driver' => config('cache.default'),
                    ]
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Cache read/write test failed',
                ];
            }
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Cache error: ' . $e->getMessage(),
            ];
        }
    }

    private function checkQueue(): array
    {
        try {
            $connection = Queue::connection();
            
            return [
                'status' => 'ok',
                'message' => 'Queue connection successful',
                'details' => [
                    'driver' => config('queue.default'),
                ]
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Queue connection failed: ' . $e->getMessage(),
            ];
        }
    }

    private function checkRedis(): array
    {
        try {
            $time = microtime(true);
            Redis::ping();
            $responseTime = round((microtime(true) - $time) * 1000, 2);

            return [
                'status' => 'ok',
                'message' => 'Redis connection successful',
                'details' => [
                    'response_time_ms' => $responseTime,
                ]
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Redis connection failed: ' . $e->getMessage(),
            ];
        }
    }
}
