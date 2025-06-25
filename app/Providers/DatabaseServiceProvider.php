<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Fix for MySQL key length issues - set default string length to 191
        // This prevents "Specified key was too long" errors in MySQL
        Schema::defaultStringLength(191);
    }
}
