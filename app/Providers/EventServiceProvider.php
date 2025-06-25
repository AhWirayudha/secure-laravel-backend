<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        // Laravel Auth Events
        Registered::class => [
            SendEmailVerificationNotification::class,
            // 'App\Modules\User\Listeners\CreateUserProfile',
            // 'App\Modules\User\Listeners\SendWelcomeEmail',
        ],

        Login::class => [
            // 'App\Modules\User\Listeners\LogUserLogin',
            'App\Modules\User\Listeners\UpdateLastLoginInfo',
        ],

        Logout::class => [
            // 'App\Modules\User\Listeners\LogUserLogout',
        ],

        // PKTracker Custom Events (commented until implemented)
        /*
        'App\Modules\User\Events\UserProfileUpdated' => [
            'App\Modules\User\Listeners\InvalidateUserCache',
            'App\Modules\User\Listeners\LogProfileChange',
        ],

        'App\Modules\User\Events\UserSubscriptionChanged' => [
            'App\Modules\User\Listeners\UpdateUserPermissions',
            'App\Modules\User\Listeners\NotifyUserOfSubscriptionChange',
        ],

        // PKTracker Tracking Events (for future Pokemon tracking features)
        'App\Modules\Tracking\Events\PokemonCaught' => [
            'App\Modules\Tracking\Listeners\UpdateUserStats',
            'App\Modules\Tracking\Listeners\CheckAchievements',
            'App\Modules\Tracking\Listeners\NotifyFriends',
        ],

        'App\Modules\Tracking\Events\TrackingDataUpdated' => [
            'App\Modules\Tracking\Listeners\InvalidateCache',
            'App\Modules\Analytics\Listeners\UpdateAnalytics',
        ],

        // Security Events
        'App\Modules\Security\Events\SuspiciousActivity' => [
            'App\Modules\Security\Listeners\LogSecurityEvent',
            'App\Modules\Security\Listeners\NotifyAdmins',
        ],

        'App\Modules\Security\Events\AccountLocked' => [
            'App\Modules\Security\Listeners\SendAccountLockedEmail',
            'App\Modules\Security\Listeners\LogAccountLock',
        ],
        */
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        // 'App\Modules\User\Listeners\UserEventSubscriber',
        // 'App\Modules\Tracking\Listeners\TrackingEventSubscriber',
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        // PKTracker specific model events
        \App\Modules\User\Models\User::creating(function ($user) {
            \Log::info('User being created', ['email' => $user->email]);
        });

        \App\Modules\User\Models\User::created(function ($user) {
            \Log::info('User created successfully', ['id' => $user->id, 'email' => $user->email]);
        });

        \App\Modules\User\Models\User::updating(function ($user) {
            if ($user->isDirty('email')) {
                \Log::info('User email being changed', [
                    'id' => $user->id,
                    'old_email' => $user->getOriginal('email'),
                    'new_email' => $user->email
                ]);
            }
        });

        \App\Modules\User\Models\User::deleting(function ($user) {
            \Log::warning('User being deleted', ['id' => $user->id, 'email' => $user->email]);
        });
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
