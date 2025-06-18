<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

class ModuleServiceProvider extends ServiceProvider
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
        $this->loadModuleRoutes();
    }

    /**
     * Load routes from all modules
     */
    protected function loadModuleRoutes(): void
    {
        $modulesPath = app_path('Modules');
        
        if (!File::exists($modulesPath)) {
            return;
        }

        $modules = File::directories($modulesPath);

        foreach ($modules as $modulePath) {
            $routeFile = $modulePath . '/routes.php';
            
            if (File::exists($routeFile)) {
                $this->loadRoutesFrom($routeFile);
            }
        }
    }
}
