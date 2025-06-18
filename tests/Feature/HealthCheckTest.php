<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HealthCheckTest extends TestCase
{
    /**
     * Test basic health check endpoint
     */
    public function test_basic_health_check(): void
    {
        $response = $this->get('/api/health');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'timestamp',
                'version',
                'environment',
            ])
            ->assertJson([
                'status' => 'ok',
                'environment' => 'testing',
            ]);
    }

    /**
     * Test detailed health check endpoint
     */
    public function test_detailed_health_check(): void
    {
        $response = $this->get('/api/health/detailed');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'timestamp',
                'version',
                'environment',
                'checks' => [
                    'app' => [
                        'status',
                        'message',
                        'details' => [
                            'php_version',
                            'laravel_version',
                            'memory_usage',
                            'memory_peak',
                        ]
                    ],
                    'database' => [
                        'status',
                        'message',
                    ],
                    'cache' => [
                        'status',
                        'message',
                    ],
                    'queue' => [
                        'status',
                        'message',
                    ],
                ]
            ])
            ->assertJson([
                'status' => 'ok',
                'environment' => 'testing',
                'checks' => [
                    'app' => [
                        'status' => 'ok',
                    ],
                ]
            ]);
    }

    /**
     * Test health check returns proper headers
     */
    public function test_health_check_headers(): void
    {
        $response = $this->get('/api/health');

        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'application/json')
            ->assertHeader('X-Response-Time');
    }
}
