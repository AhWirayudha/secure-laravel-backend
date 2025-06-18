<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Passport\Passport;

class ApiIntegrationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Run migrations and seeders
        $this->artisan('migrate:fresh');
        $this->artisan('passport:install');
        $this->artisan('db:seed');
    }

    /**
     * Test complete user registration and authentication flow
     */
    public function test_complete_auth_flow(): void
    {
        // Test registration
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'Test123!@#',
            'password_confirmation' => 'Test123!@#',
        ];

        $registerResponse = $this->postJson('/api/v1/auth/register', $userData);
        
        $registerResponse->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'created_at',
                        'updated_at',
                    ],
                    'access_token',
                    'token_type',
                ],
                'message'
            ]);

        // Test login
        $loginResponse = $this->postJson('/api/v1/auth/login', [
            'email' => $userData['email'],
            'password' => $userData['password'],
        ]);

        $loginResponse->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'user',
                    'access_token',
                    'token_type',
                ],
                'message'
            ]);

        $token = $loginResponse->json('data.access_token');

        // Test authenticated user endpoint
        $userResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/v1/auth/user');

        $userResponse->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                ]
            ]);

        // Test logout
        $logoutResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/v1/auth/logout');

        $logoutResponse->assertStatus(200);
    }

    /**
     * Test user management endpoints
     */
    public function test_user_management_endpoints(): void
    {
        // Create admin user with permissions
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        
        Passport::actingAs($admin);

        // Test list users
        $listResponse = $this->getJson('/api/v1/users');
        $listResponse->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'created_at',
                    ]
                ],
                'links',
                'meta'
            ]);

        // Test create user
        $newUserData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'Test123!@#',
        ];

        $createResponse = $this->postJson('/api/v1/users', $newUserData);
        $createResponse->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                ]
            ]);

        $userId = $createResponse->json('data.id');

        // Test show user
        $showResponse = $this->getJson("/api/v1/users/{$userId}");
        $showResponse->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $userId,
                    'name' => $newUserData['name'],
                    'email' => $newUserData['email'],
                ]
            ]);

        // Test update user
        $updateData = [
            'name' => 'Updated Name',
        ];

        $updateResponse = $this->putJson("/api/v1/users/{$userId}", $updateData);
        $updateResponse->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $userId,
                    'name' => 'Updated Name',
                ]
            ]);

        // Test delete user
        $deleteResponse = $this->deleteJson("/api/v1/users/{$userId}");
        $deleteResponse->assertStatus(200);

        // Verify user is deleted
        $this->assertDatabaseMissing('users', ['id' => $userId]);
    }

    /**
     * Test master data endpoints
     */
    public function test_master_data_endpoints(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        
        Passport::actingAs($admin);

        // Test roles endpoint
        $rolesResponse = $this->getJson('/api/v1/master-data/roles');
        $rolesResponse->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'guard_name',
                    ]
                ]
            ]);

        // Test permissions endpoint
        $permissionsResponse = $this->getJson('/api/v1/master-data/permissions');
        $permissionsResponse->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'guard_name',
                    ]
                ]
            ]);
    }

    /**
     * Test API rate limiting
     */
    public function test_api_rate_limiting(): void
    {
        // Test auth rate limiting
        for ($i = 0; $i < 6; $i++) {
            $response = $this->postJson('/api/v1/auth/login', [
                'email' => 'test@example.com',
                'password' => 'wrongpassword',
            ]);

            if ($i < 5) {
                $response->assertStatus(401); // Unauthorized
            } else {
                $response->assertStatus(429); // Too Many Requests
            }
        }
    }

    /**
     * Test API versioning
     */
    public function test_api_versioning(): void
    {
        $response = $this->getJson('/api/health');
        
        $response->assertStatus(200)
            ->assertHeader('X-API-Version', 'v1');
    }

    /**
     * Test security headers are present
     */
    public function test_security_headers(): void
    {
        $response = $this->getJson('/api/health');

        $response->assertStatus(200)
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'DENY')
            ->assertHeader('X-XSS-Protection', '1; mode=block')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }
}
