<?php

use App\Modules\MasterData\Controllers\RoleController;
use App\Modules\MasterData\Controllers\PermissionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Master Data Module Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:api', 'rate_limit:api,100,1'])->group(function () {
    
    // Role management routes
    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('roles.index');
        Route::post('/', [RoleController::class, 'store'])->name('roles.store');
        Route::get('/{role}', [RoleController::class, 'show'])->name('roles.show');
        Route::put('/{role}', [RoleController::class, 'update'])->name('roles.update');
        Route::patch('/{role}', [RoleController::class, 'update'])->name('roles.patch');
        Route::delete('/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
    });

    // Permission management routes
    Route::prefix('permissions')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('permissions.index');
        Route::post('/', [PermissionController::class, 'store'])->name('permissions.store');
        Route::get('/{permission}', [PermissionController::class, 'show'])->name('permissions.show');
        Route::put('/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
        Route::patch('/{permission}', [PermissionController::class, 'update'])->name('permissions.patch');
        Route::delete('/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');
    });

});
