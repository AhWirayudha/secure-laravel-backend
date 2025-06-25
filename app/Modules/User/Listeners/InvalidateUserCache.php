<?php

namespace App\Modules\User\Listeners;

use App\Modules\User\Events\UserProfileUpdated;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class InvalidateUserCache
{
    /**
     * Handle the event.
     */
    public function handle(UserProfileUpdated $event): void
    {
        $user = $event->user;
        
        // Clear user-specific cache
        Cache::forget("user.{$user->id}");
        Cache::forget("user.profile.{$user->id}");
        Cache::forget("user.permissions.{$user->id}");
        
        // If email changed, clear email-based cache
        if (in_array('email', $event->changedFields)) {
            Cache::forget("user.email.{$user->email}");
        }
        
        Log::info('User cache invalidated after profile update', [
            'user_id' => $user->id,
            'changed_fields' => $event->changedFields
        ]);
    }
}
