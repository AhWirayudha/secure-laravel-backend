<?php

namespace App\Modules\User\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\User\Events\UserProfileUpdated;
use App\Modules\User\Models\User;
use App\Modules\User\Requests\UpdateUserRequest;
use App\Modules\User\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class UserProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Update user profile - Example using Gates, Policies, and Events
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        // Using the policy we created
        $this->authorize('update', $user);
        
        // Alternative: Using gates
        // if (! Gate::allows('manage-own-profile', $user)) {
        //     abort(403, 'Unauthorized to update this profile');
        // }

        // Track what fields are being changed
        $originalData = $user->only(['name', 'email']);
        
        // Update the user
        $user->update($request->validated());
        
        // Get changed fields
        $changedFields = array_keys($user->getChanges());
        
        // Fire event if there were changes
        if (!empty($changedFields)) {
            event(new UserProfileUpdated($user, $changedFields));
        }

        return response()->json([
            'message' => 'Profile updated successfully',
            'data' => new UserResource($user),
            'changed_fields' => $changedFields
        ]);
    }

    /**
     * Get current user's profile
     */
    public function show(): JsonResponse
    {
        $user = auth()->user();
        
        // Using gate to check premium access
        $hasPremiumAccess = Gate::allows('api-premium-access');
        
        $userData = new UserResource($user);
        
        return response()->json([
            'data' => $userData,
            'premium_access' => $hasPremiumAccess
        ]);
    }

    /**
     * View tracking analytics (PKTracker specific)
     */
    public function trackingAnalytics(User $user): JsonResponse
    {
        // Using policy method
        $this->authorize('viewTrackingAnalytics', $user);
        
        // Mock tracking data
        $analytics = [
            'total_pokemon_caught' => 150,
            'favorite_type' => 'Electric',
            'activity_streak' => 7,
            'level' => 25
        ];

        return response()->json([
            'user' => new UserResource($user),
            'analytics' => $analytics
        ]);
    }

    /**
     * Access premium features
     */
    public function premiumFeatures(): JsonResponse
    {
        $user = auth()->user();
        
        // Using policy
        if (!$user->can('accessPremiumFeatures', User::class)) {
            return response()->json([
                'message' => 'Premium subscription required',
                'upgrade_url' => '/api/v1/subscription/upgrade'
            ], 403);
        }

        $premiumFeatures = [
            'advanced_analytics' => true,
            'unlimited_storage' => true,
            'priority_support' => true,
            'custom_themes' => true
        ];

        return response()->json([
            'premium_features' => $premiumFeatures
        ]);
    }
}
