<?php

namespace Tests\Feature\Modules\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('passport:install');
        
        // Create roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);
    }

    public function test_admin_can_list_users(): void
    {
        // Create admin user
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $token = $admin->createToken('Test Token')->accessToken;

        // Create some test users
        User::factory(3)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v1/users');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'message',
                    'data' => [
                        'users' => [
                            '*' => [
                                'id',
                                'name',
                                'email',
                                'is_active',
                                'roles',
                            ]
                        ],
                        'statistics',
                    ],
                    'meta',
                ]);
    }

    public function test_admin_can_create_user(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $token = $admin->createToken('Test Token')->accessToken;

        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'roles' => ['user'],
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/v1/users', $userData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'message',
                    'data' => [
                        'id',
                        'name',
                        'email',
                        'roles',
                    ],
                ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }

    public function test_user_cannot_access_user_management(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $token = $user->createToken('Test Token')->accessToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v1/users');

        $response->assertStatus(403);
    }

    public function test_can_filter_users_by_status(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $token = $admin->createToken('Test Token')->accessToken;

        // Create active and inactive users
        User::factory()->create(['is_active' => true]);
        User::factory()->create(['is_active' => false]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v1/users?status=active');

        $response->assertStatus(200);
        
        $users = $response->json('data.users');
        foreach ($users as $user) {
            $this->assertTrue($user['is_active']);
        }
    }

    public function test_can_search_users(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $token = $admin->createToken('Test Token')->accessToken;

        User::factory()->create(['name' => 'John Doe']);
        User::factory()->create(['name' => 'Jane Smith']);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v1/users?search=John');

        $response->assertStatus(200);
        
        $users = $response->json('data.users');
        $this->assertCount(1, $users);
        $this->assertStringContainsString('John', $users[0]['name']);
    }
}
