<?php

namespace App\Modules\User\Services;

use App\Models\User;
use App\Modules\User\Repositories\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserService
{
    public function __construct(
        private UserRepository $userRepository
    ) {}

    /**
     * Get all users with filtering and pagination.
     */
    public function getAllUsers(array $filters): LengthAwarePaginator
    {
        return $this->userRepository->getAllWithFilters($filters);
    }

    /**
     * Get user by ID.
     */
    public function getUserById(string $id): User
    {
        $user = $this->userRepository->findById($id);
        
        if (!$user) {
            throw new ModelNotFoundException('User not found');
        }

        return $user;
    }

    /**
     * Create a new user.
     */
    public function createUser(array $data): User
    {
        return DB::transaction(function () use ($data) {
            // Hash password
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            // Extract roles if provided
            $roles = $data['roles'] ?? ['user'];
            unset($data['roles']);

            // Create user
            $user = $this->userRepository->create($data);

            // Assign roles
            if (!empty($roles)) {
                $user->assignRole($roles);
            }

            Log::info('User created successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'roles' => $roles,
                'created_by' => auth()->id(),
            ]);

            return $user->load(['roles', 'permissions']);
        });
    }

    /**
     * Update user.
     */
    public function updateUser(string $id, array $data): User
    {
        return DB::transaction(function () use ($id, $data) {
            $user = $this->getUserById($id);

            // Hash password if provided
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            // Extract roles if provided
            $roles = null;
            if (isset($data['roles'])) {
                $roles = $data['roles'];
                unset($data['roles']);
            }

            // Update user
            $user = $this->userRepository->update($user, $data);

            // Update roles if provided
            if ($roles !== null) {
                $user->syncRoles($roles);
            }

            Log::info('User updated successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'updated_fields' => array_keys($data),
                'updated_by' => auth()->id(),
            ]);

            return $user->load(['roles', 'permissions']);
        });
    }

    /**
     * Delete user (soft delete).
     */
    public function deleteUser(string $id): bool
    {
        $user = $this->getUserById($id);

        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            throw new \InvalidArgumentException('You cannot delete your own account');
        }

        $result = $this->userRepository->delete($user);

        Log::info('User deleted successfully', [
            'user_id' => $user->id,
            'email' => $user->email,
            'deleted_by' => auth()->id(),
        ]);

        return $result;
    }

    /**
     * Restore soft-deleted user.
     */
    public function restoreUser(string $id): User
    {
        $user = $this->userRepository->findTrashedById($id);
        
        if (!$user) {
            throw new ModelNotFoundException('Deleted user not found');
        }

        $user->restore();

        Log::info('User restored successfully', [
            'user_id' => $user->id,
            'email' => $user->email,
            'restored_by' => auth()->id(),
        ]);

        return $user->load(['roles', 'permissions']);
    }

    /**
     * Permanently delete user.
     */
    public function forceDeleteUser(string $id): bool
    {
        $user = $this->userRepository->findTrashedById($id);
        
        if (!$user) {
            throw new ModelNotFoundException('Deleted user not found');
        }

        $result = $user->forceDelete();

        Log::warning('User permanently deleted', [
            'user_id' => $user->id,
            'email' => $user->email,
            'force_deleted_by' => auth()->id(),
        ]);

        return $result;
    }

    /**
     * Toggle user active status.
     */
    public function toggleUserStatus(string $id): User
    {
        $user = $this->getUserById($id);

        // Prevent self-deactivation
        if ($user->id === auth()->id()) {
            throw new \InvalidArgumentException('You cannot deactivate your own account');
        }

        $newStatus = !$user->is_active;
        $user = $this->userRepository->update($user, ['is_active' => $newStatus]);

        Log::info('User status toggled', [
            'user_id' => $user->id,
            'email' => $user->email,
            'new_status' => $newStatus ? 'active' : 'inactive',
            'updated_by' => auth()->id(),
        ]);

        return $user;
    }

    /**
     * Assign roles to user.
     */
    public function assignRoles(string $id, array $roles): User
    {
        $user = $this->getUserById($id);
        $user->assignRole($roles);

        Log::info('Roles assigned to user', [
            'user_id' => $user->id,
            'email' => $user->email,
            'roles' => $roles,
            'assigned_by' => auth()->id(),
        ]);

        return $user->load(['roles', 'permissions']);
    }

    /**
     * Remove roles from user.
     */
    public function removeRoles(string $id, array $roles): User
    {
        $user = $this->getUserById($id);
        $user->removeRole($roles);

        Log::info('Roles removed from user', [
            'user_id' => $user->id,
            'email' => $user->email,
            'roles' => $roles,
            'removed_by' => auth()->id(),
        ]);

        return $user->load(['roles', 'permissions']);
    }

    /**
     * Get user statistics.
     */
    public function getUserStatistics(): array
    {
        return [
            'total_users' => $this->userRepository->getTotalCount(),
            'active_users' => $this->userRepository->getActiveCount(),
            'inactive_users' => $this->userRepository->getInactiveCount(),
            'verified_users' => $this->userRepository->getVerifiedCount(),
            'unverified_users' => $this->userRepository->getUnverifiedCount(),
            'users_registered_today' => $this->userRepository->getRegisteredTodayCount(),
            'users_registered_this_week' => $this->userRepository->getRegisteredThisWeekCount(),
            'users_registered_this_month' => $this->userRepository->getRegisteredThisMonthCount(),
        ];
    }
}
