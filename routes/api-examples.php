<?php

// Example routes that use the RouteServiceProvider configurations

use Illuminate\Support\Facades\Route;
use App\Modules\User\Controllers\UserProfileController;
use App\Modules\User\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| PKTracker API Routes Examples
|--------------------------------------------------------------------------
*/

// Routes using model bindings from RouteServiceProvider
Route::middleware(['auth:api'])->group(function () {
    
    // Uses Route::model('user', User::class) binding
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
    
    // Uses Route::bind('userIdentifier') - can accept ID or email
    Route::get('/users/find/{userIdentifier}', [UserController::class, 'findByIdOrEmail']);
    
    // Uses Route::bind('myProfile') - automatically gets current user
    Route::get('/my-profile', [UserProfileController::class, 'show']);
    Route::put('/my-profile', [UserProfileController::class, 'update']);
    
    // Premium features with specific throttling
    Route::middleware(['throttle:tracking-api'])->group(function () {
        Route::get('/users/{user}/analytics', [UserProfileController::class, 'trackingAnalytics']);
        Route::get('/premium-features', [UserProfileController::class, 'premiumFeatures']);
    });
});

// Admin routes with higher rate limits
Route::middleware(['auth:api', 'role:admin', 'throttle:admin-api'])->group(function () {
    Route::get('/admin/users', [UserController::class, 'adminIndex']);
    Route::post('/admin/users/{user}/suspend', [UserController::class, 'suspend']);
});

// Routes with pattern constraints from RouteServiceProvider
Route::middleware(['auth:api'])->group(function () {
    // These routes will only match the patterns defined in RouteServiceProvider
    Route::get('/tracking/{pokemonId}', function ($pokemonId) {
        // $pokemonId is guaranteed to be numeric due to Route::pattern('pokemonId', '[0-9]+')
        return "Pokemon ID: {$pokemonId}";
    });
    
    Route::get('/users/username/{username}', function ($username) {
        // $username matches pattern [a-zA-Z0-9_]+
        return "Username: {$username}";
    });
});

// Heavy operations with limited rate limiting
Route::middleware(['auth:api', 'throttle:heavy-operations'])->group(function () {
    Route::post('/export/user-data', [UserController::class, 'exportData']);
    Route::post('/import/pokemon-data', [UserController::class, 'importPokemonData']);
});
