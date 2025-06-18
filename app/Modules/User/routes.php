<?php

use App\Modules\User\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| User Module Routes
|--------------------------------------------------------------------------
*/

Route::prefix('users')->middleware(['auth:api', 'rate_limit:api,100,1'])->group(function () {
    // Basic CRUD operations
    Route::get('/', [UserController::class, 'index'])->name('users.index');
    Route::post('/', [UserController::class, 'store'])->name('users.store');
    Route::get('/{user}', [UserController::class, 'show'])->name('users.show');
    Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
    Route::patch('/{user}', [UserController::class, 'update'])->name('users.patch');
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Additional user management operations
    Route::post('/{user}/restore', [UserController::class, 'restore'])
        ->name('users.restore')
        ->middleware('permission:manage-users');
    
    Route::delete('/{user}/force', [UserController::class, 'forceDelete'])
        ->name('users.force-delete')
        ->middleware('permission:manage-users');
    
    Route::patch('/{user}/toggle-status', [UserController::class, 'toggleStatus'])
        ->name('users.toggle-status')
        ->middleware('permission:manage-users');

    // Role management for users
    Route::post('/{user}/roles', [UserController::class, 'assignRoles'])
        ->name('users.assign-roles')
        ->middleware('permission:manage-roles');
    
    Route::delete('/{user}/roles', [UserController::class, 'removeRoles'])
        ->name('users.remove-roles')
        ->middleware('permission:manage-roles');
});
