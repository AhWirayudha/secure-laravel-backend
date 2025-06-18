<?php

namespace App\Modules\User\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository
{
    /**
     * Get all users with filters and pagination.
     */
    public function getAllWithFilters(array $filters): LengthAwarePaginator
    {
        $query = User::query()->with(['roles', 'permissions']);

        // Apply filters
        $this->applyFilters($query, $filters);

        // Apply sorting
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        // Return paginated results
        return $query->paginate(
            $filters['per_page'] ?? 15,
            ['*'],
            'page',
            $filters['page'] ?? 1
        );
    }

    /**
     * Find user by ID.
     */
    public function findById(string $id): ?User
    {
        return User::with(['roles', 'permissions'])->find($id);
    }

    /**
     * Find trashed user by ID.
     */
    public function findTrashedById(string $id): ?User
    {
        return User::onlyTrashed()->with(['roles', 'permissions'])->find($id);
    }

    /**
     * Create a new user.
     */
    public function create(array $data): User
    {
        return User::create($data);
    }

    /**
     * Update user.
     */
    public function update(User $user, array $data): User
    {
        $user->update($data);
        return $user->fresh(['roles', 'permissions']);
    }

    /**
     * Delete user (soft delete).
     */
    public function delete(User $user): bool
    {
        return $user->delete();
    }

    /**
     * Get total user count.
     */
    public function getTotalCount(): int
    {
        return User::count();
    }

    /**
     * Get active user count.
     */
    public function getActiveCount(): int
    {
        return User::where('is_active', true)->count();
    }

    /**
     * Get inactive user count.
     */
    public function getInactiveCount(): int
    {
        return User::where('is_active', false)->count();
    }

    /**
     * Get verified user count.
     */
    public function getVerifiedCount(): int
    {
        return User::whereNotNull('email_verified_at')->count();
    }

    /**
     * Get unverified user count.
     */
    public function getUnverifiedCount(): int
    {
        return User::whereNull('email_verified_at')->count();
    }

    /**
     * Get users registered today count.
     */
    public function getRegisteredTodayCount(): int
    {
        return User::whereDate('created_at', today())->count();
    }

    /**
     * Get users registered this week count.
     */
    public function getRegisteredThisWeekCount(): int
    {
        return User::whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->count();
    }

    /**
     * Get users registered this month count.
     */
    public function getRegisteredThisMonthCount(): int
    {
        return User::whereMonth('created_at', now()->month)
                   ->whereYear('created_at', now()->year)
                   ->count();
    }

    /**
     * Apply filters to the query.
     */
    private function applyFilters(Builder $query, array $filters): void
    {
        // Search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Status filter
        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $isActive = $filters['status'] === 'active';
            $query->where('is_active', $isActive);
        }

        // Role filter
        if (!empty($filters['role'])) {
            $query->whereHas('roles', function ($q) use ($filters) {
                $q->where('name', $filters['role']);
            });
        }

        // Email verification filter
        if (isset($filters['verified'])) {
            if ($filters['verified']) {
                $query->whereNotNull('email_verified_at');
            } else {
                $query->whereNull('email_verified_at');
            }
        }

        // Date range filter
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }
    }
}
