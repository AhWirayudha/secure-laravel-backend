<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User management
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            'manage-users',

            // Role management
            'view-roles',
            'create-roles',
            'edit-roles',
            'delete-roles',
            'manage-roles',

            // Permission management
            'view-permissions',
            'create-permissions',
            'edit-permissions',
            'delete-permissions',
            'manage-permissions',

            // System administration
            'view-logs',
            'manage-system',
            'view-analytics',
            'manage-settings',

            // API access
            'access-api',
            'admin-api',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'api']);
        }

        // Create roles and assign permissions
        $superAdminRole = Role::create(['name' => 'super-admin', 'guard_name' => 'api']);
        $superAdminRole->givePermissionTo(Permission::all());

        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'api']);
        $adminRole->givePermissionTo([
            'view-users', 'create-users', 'edit-users',
            'view-roles', 'create-roles', 'edit-roles',
            'view-permissions',
            'view-logs', 'view-analytics',
            'access-api', 'admin-api',
        ]);

        $moderatorRole = Role::create(['name' => 'moderator', 'guard_name' => 'api']);
        $moderatorRole->givePermissionTo([
            'view-users', 'edit-users',
            'view-roles',
            'view-permissions',
            'access-api',
        ]);

        $userRole = Role::create(['name' => 'user', 'guard_name' => 'api']);
        $userRole->givePermissionTo([
            'access-api',
        ]);
    }
}
