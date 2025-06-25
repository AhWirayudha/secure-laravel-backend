<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use App\Modules\User\Models\User;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        // PKTracker specific rate limiters
        RateLimiter::for('api', function (Request $request) {
            $user = $request->user();
            
            // Premium users get higher rate limits
            if ($user && $user->hasRole('premium')) {
                return Limit::perMinute(120)->by($user->id);
            }
            
            // Regular authenticated users
            if ($user) {
                return Limit::perMinute(60)->by($user->id);
            }
            
            // Guest users (limited)
            return Limit::perMinute(30)->by($request->ip());
        });

        // PKTracker specific: Tracking API rate limiter
        RateLimiter::for('tracking-api', function (Request $request) {
            $user = $request->user();
            
            if ($user && $user->hasRole(['premium', 'admin'])) {
                return Limit::perMinute(200)->by($user->id);
            }
            
            return Limit::perMinute(100)->by($user?->id ?: $request->ip());
        });

        // Heavy operations rate limiter
        RateLimiter::for('heavy-operations', function (Request $request) {
            return Limit::perMinute(10)->by($request->user()?->id ?: $request->ip());
        });

        // Admin operations rate limiter
        RateLimiter::for('admin-api', function (Request $request) {
            return Limit::perMinute(300)->by($request->user()?->id ?: $request->ip());
        });

        // PKTracker Model Bindings
        $this->configureModelBindings();

        $this->routes(function () {
            // NOTE: API routes are now handled by bootstrap/app.php
            // We don't need to register them again here to avoid conflicts
            
            // Admin API routes (commented until admin.php exists)
            /*
            Route::middleware(['api', 'auth:api', 'role:admin', 'throttle:admin-api'])
                ->prefix('api/v1/admin')
                ->group(base_path('routes/admin.php'));
            */

            // Web routes are handled by bootstrap/app.php
            // Route::middleware('web')
            //     ->group(base_path('routes/web.php'));

            // Module-specific routes (if needed for additional modules)
            // $this->loadModuleRoutes();
        });
    }

    /**
     * Configure model bindings for PKTracker
     */
    protected function configureModelBindings(): void
    {
        // User model binding
        Route::model('user', User::class);
        
        // Custom binding for user by email or ID
        Route::bind('userIdentifier', function ($value) {
            return User::where('id', $value)
                ->orWhere('email', $value)
                ->firstOrFail();
        });

        // PKTracker specific: Bind user to current authenticated user's context
        Route::bind('myProfile', function () {
            return request()->user();
        });

        // Route patterns for PKTracker
        Route::pattern('id', '[0-9]+');
        Route::pattern('uuid', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');
        Route::pattern('username', '[a-zA-Z0-9_]+');
        
        // Future Pokemon-related patterns
        Route::pattern('pokemonId', '[0-9]+');
        Route::pattern('pokemonName', '[a-zA-Z0-9\-]+');
    }

    /**
     * Load module-specific routes
     */
    protected function loadModuleRoutes(): void
    {
        // Check if module route files exist before loading
        $userRoutesPath = base_path('app/Modules/User/routes.php');
        if (file_exists($userRoutesPath)) {
            Route::middleware(['api', 'throttle:api'])
                ->prefix('api/v1')
                ->group($userRoutesPath);
        }

        $masterDataRoutesPath = base_path('app/Modules/MasterData/routes.php');
        if (file_exists($masterDataRoutesPath)) {
            Route::middleware(['api', 'throttle:api'])
                ->prefix('api/v1')
                ->group($masterDataRoutesPath);
        }

        // Future module routes can be added here
        // Route::middleware(['api', 'throttle:tracking-api'])
        //     ->prefix('api/v1')
        //     ->group(base_path('app/Modules/Pokemon/routes.php'));
    }
}
