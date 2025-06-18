<?php

use App\Http\Controllers\HealthCheckController;
use App\Modules\Auth\Controllers\AuthController;
use App\Modules\User\Controllers\UserController;
use App\Modules\MasterData\Controllers\RoleController;
use App\Modules\MasterData\Controllers\PermissionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Health check routes
Route::get('/health', [HealthCheckController::class, 'index'])->name('api.health');
Route::get('/health/detailed', [HealthCheckController::class, 'detailed'])->name('api.health.detailed');

// API Version 1
Route::prefix('v1')->group(function () {
    
    // Authentication routes (public)
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register'])
            ->middleware(['rate_limit:auth,5,1'])
            ->name('api.auth.register');
            
        Route::post('/login', [AuthController::class, 'login'])
            ->middleware(['rate_limit:auth,5,1'])
            ->name('api.auth.login');
    });

    // Protected routes
    Route::middleware(['auth:api', 'rate_limit:api,100,1'])->group(function () {
        
        // Authentication routes (protected)
        Route::prefix('auth')->group(function () {
            Route::get('/user', [AuthController::class, 'user'])->name('api.auth.user');
            Route::post('/logout', [AuthController::class, 'logout'])->name('api.auth.logout');
            Route::post('/logout-all', [AuthController::class, 'logoutAll'])->name('api.auth.logout-all');
            Route::post('/refresh', [AuthController::class, 'refresh'])->name('api.auth.refresh');
        });

        // User management routes
        include app_path('Modules/User/routes.php');

        // Master data routes
        include app_path('Modules/MasterData/routes.php');
    });
});

// Fallback route for undefined API endpoints
Route::fallback(function () {
    return response()->json([
        'error' => 'Endpoint not found',
        'message' => 'The requested API endpoint does not exist.',
        'available_versions' => config('api.supported_versions', ['v1'])
    ], 404);
});
