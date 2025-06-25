<?php

namespace App\Modules\MasterData\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MasterDataService
{
    /**
     * Get all roles with optional filters.
     */
    public function getAllRoles(array $filters = []): \Illuminate\Database\Eloquent\Collection
    {
        $query = Role::with(['permissions', 'users']);

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['guard_name'])) {
            $query->where('guard_name', $filters['guard_name']);
        }

        return $query->orderBy('name')->get();
    }

    /**
     * Get role by ID.
     */
    public function getRoleById(string $id): Role
    {
        $role = Role::with(['permissions', 'users'])->find($id);
        
        if (!$role) {
            throw new ModelNotFoundException('Role not found');
        }

        return $role;
    }

    /**
     * Create a new role.
     */
    public function createRole(array $data): Role
    {
        return DB::transaction(function () use ($data) {
            // Extract permissions if provided
            $permissions = $data['permissions'] ?? [];
            unset($data['permissions']);

            // Set default guard name
            $data['guard_name'] = $data['guard_name'] ?? 'api';

            // Create role
            $role = Role::create($data);

            // Assign permissions
            if (!empty($permissions)) {
                $role->givePermissionTo($permissions);
            }

            Log::info('Role created successfully', [
                'role_id' => $role->id,
                'role_name' => $role->name,
                'permissions' => $permissions,
                'created_by' => auth()->id(),
            ]);

            return $role->load(['permissions', 'users']);
        });
    }

    /**
     * Update role.
     */
    public function updateRole(string $id, array $data): Role
    {
        return DB::transaction(function () use ($id, $data) {
            $role = $this->getRoleById($id);

            // Extract permissions if provided
            $permissions = null;
            if (isset($data['permissions'])) {
                $permissions = $data['permissions'];
                unset($data['permissions']);
            }

            // Update role
            $role->update($data);

            // Update permissions if provided
            if ($permissions !== null) {
                $role->syncPermissions($permissions);
            }

            Log::info('Role updated successfully', [
                'role_id' => $role->id,
                'role_name' => $role->name,
                'updated_fields' => array_keys($data),
                'permissions' => $permissions,
                'updated_by' => auth()->id(),
            ]);

            return $role->load(['permissions', 'users']);
        });
    }

    /**
     * Delete role.
     */
    public function deleteRole(string $id): bool
    {
        $role = $this->getRoleById($id);

        // Prevent deletion of super-admin role
        if ($role->name === 'super-admin') {
            throw new \InvalidArgumentException('Cannot delete super-admin role');
        }

        // Check if role is assigned to users
        if ($role->users()->count() > 0) {
            throw new \InvalidArgumentException('Cannot delete role that is assigned to users');
        }

        $result = $role->delete();

        Log::warning('Role deleted', [
            'role_id' => $role->id,
            'role_name' => $role->name,
            'deleted_by' => auth()->id(),
        ]);

        return $result;
    }

    /**
     * Get all permissions with optional filters.
     */
    public function getAllPermissions(array $filters = []): \Illuminate\Database\Eloquent\Collection
    {
        $query = Permission::with(['roles', 'users']);

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['guard_name'])) {
            $query->where('guard_name', $filters['guard_name']);
        }

        return $query->orderBy('name')->get();
    }

    /**
     * Get permission by ID.
     */
    public function getPermissionById(string $id): Permission
    {
        $permission = Permission::with(['roles', 'users'])->find($id);
        
        if (!$permission) {
            throw new ModelNotFoundException('Permission not found');
        }

        return $permission;
    }

    /**
     * Create a new permission.
     */
    public function createPermission(array $data): Permission
    {
        // Set default guard name
        $data['guard_name'] = $data['guard_name'] ?? 'web';

        $permission = Permission::create($data);

        Log::info('Permission created successfully', [
            'permission_id' => $permission->id,
            'permission_name' => $permission->name,
            'created_by' => auth()->id(),
        ]);

        return $permission->load(['roles', 'users']);
    }

    /**
     * Update permission.
     */
    public function updatePermission(string $id, array $data): Permission
    {
        $permission = $this->getPermissionById($id);
        $permission->update($data);

        Log::info('Permission updated successfully', [
            'permission_id' => $permission->id,
            'permission_name' => $permission->name,
            'updated_fields' => array_keys($data),
            'updated_by' => auth()->id(),
        ]);

        return $permission->load(['roles', 'users']);
    }

    /**
     * Delete permission.
     */
    public function deletePermission(string $id): bool
    {
        $permission = $this->getPermissionById($id);

        // Check if permission is assigned to roles or users
        if ($permission->roles()->count() > 0 || $permission->users()->count() > 0) {
            throw new \InvalidArgumentException('Cannot delete permission that is assigned to roles or users');
        }

        $result = $permission->delete();

        Log::warning('Permission deleted', [
            'permission_id' => $permission->id,
            'permission_name' => $permission->name,
            'deleted_by' => auth()->id(),
        ]);

        return $result;
    }

    /**
     * Get master data statistics.
     */
    public function getMasterDataStatistics(): array
    {
        return [
            'total_roles' => Role::count(),
            'total_permissions' => Permission::count(),
            'roles_with_users' => Role::has('users')->count(),
            'permissions_with_roles' => Permission::has('roles')->count(),
        ];
    }
}
