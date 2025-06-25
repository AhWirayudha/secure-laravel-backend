<?php

namespace App\Modules\User\Policies;

use App\Modules\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any users.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view-users');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        // Users can view their own profile, or if they have view-users permission
        return $user->id === $model->id || $user->hasPermission('view-users');
    }

    /**
     * Determine whether the user can create users.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('create-users');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        // Users can update their own profile, or if they have edit-users permission
        return $user->id === $model->id || $user->hasPermission('edit-users');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // Users cannot delete themselves, only admins can delete users
        return $user->id !== $model->id && $user->hasPermission('delete-users');
    }

    /**
     * PKTracker specific: Can user access premium features?
     */
    public function accessPremiumFeatures(User $user): bool
    {
        return $user->hasRole(['premium', 'admin']) || $user->hasPermission('access-premium');
    }

    /**
     * PKTracker specific: Can user view tracking analytics?
     */
    public function viewTrackingAnalytics(User $user, User $model): bool
    {
        return $user->id === $model->id || $user->hasPermission('view-all-tracking-data');
    }
}
