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

// Test route to verify basic functionality
Route::get('/test', function () {
    return response()->json(['message' => 'PKTracker API is working!']);
});

// Test authenticated route
Route::middleware(['auth:api'])->get('/test-auth', function () {
    return response()->json([
        'message' => 'Authentication working!',
        'user' => auth()->user(),
        'guard' => auth()->guard()->name ?? 'unknown'
    ]);
});

// Test role creation route for debugging
Route::middleware(['auth:api'])->post('/test-role', function (Illuminate\Http\Request $request) {
    try {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        $role = \Spatie\Permission\Models\Role::create([
            'name' => $request->name,
            'guard_name' => 'api'
        ]);
        
        return response()->json([
            'message' => 'Role created successfully',
            'role' => $role
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

// Health check routes
Route::get('/health', [HealthCheckController::class, 'index'])->name('api.health');
Route::get('/health/detailed', [HealthCheckController::class, 'detailed'])->name('api.health.detailed');

// API Version 1
Route::prefix('v1')->group(function () {
    
    // Authentication routes (public)
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register'])
            ->name('api.auth.register');
            
        Route::post('/login', [AuthController::class, 'login'])
            ->name('api.auth.login');
    });

    // Protected routes
    Route::middleware(['auth:api'])->group(function () {
        
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
