<?php

namespace App\Providers;

use App\Modules\User\Models\User;
use App\Modules\User\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        // Add more model-policy mappings here
        // 'App\Modules\Pokemon\Models\Pokemon' => 'App\Modules\Pokemon\Policies\PokemonPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // PKTracker specific authorization gates
        
        // Admin-only actions
        Gate::define('manage-system', function (User $user) {
            return $user->hasRole('admin');
        });

        // User can manage their own profile
        Gate::define('manage-own-profile', function (User $user, User $targetUser) {
            return $user->id === $targetUser->id || $user->hasRole('admin');
        });

        // PKTracker specific: User can view their own tracking data
        Gate::define('view-tracking-data', function (User $user, $userId = null) {
            if ($userId === null) return true; // View own data
            return $user->id === $userId || $user->hasPermission('view-all-tracking-data');
        });

        // API rate limiting based on user role
        Gate::define('api-premium-access', function (User $user) {
            return $user->hasRole(['premium', 'admin']);
        });

        // Passport token lifetimes for PKTracker
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));
    }
}
