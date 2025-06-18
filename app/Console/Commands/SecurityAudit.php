<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class SecurityAudit extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'security:audit';

    /**
     * The console command description.
     */
    protected $description = 'Run a comprehensive security audit of the application';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Security Audit...');
        $this->newLine();

        $issues = [];

        // Check environment configuration
        $issues = array_merge($issues, $this->checkEnvironmentSecurity());
        
        // Check database security
        $issues = array_merge($issues, $this->checkDatabaseSecurity());
        
        // Check file permissions
        $issues = array_merge($issues, $this->checkFilePermissions());
        
        // Check configuration security
        $issues = array_merge($issues, $this->checkConfigurationSecurity());
        
        // Check middleware configuration
        $issues = array_merge($issues, $this->checkMiddlewareSecurity());

        // Display results
        $this->displayResults($issues);

        return empty($issues) ? Command::SUCCESS : Command::FAILURE;
    }

    private function checkEnvironmentSecurity(): array
    {
        $this->info('Checking Environment Security...');
        $issues = [];

        // Check if APP_DEBUG is false in production
        if (config('app.env') === 'production' && config('app.debug')) {
            $issues[] = 'APP_DEBUG should be false in production environment';
        }

        // Check if APP_KEY is set
        if (empty(config('app.key'))) {
            $issues[] = 'APP_KEY is not set';
        }

        // Check if HTTPS is enforced
        if (config('app.env') === 'production' && !config('app.url') || !str_starts_with(config('app.url'), 'https://')) {
            $issues[] = 'HTTPS should be enforced in production (APP_URL should start with https://)';
        }

        // Check session security
        if (config('session.secure') !== true && config('app.env') === 'production') {
            $issues[] = 'SESSION_SECURE should be true in production';
        }

        return $issues;
    }

    private function checkDatabaseSecurity(): array
    {
        $this->info('Checking Database Security...');
        $issues = [];

        // Check if database password is set
        if (empty(config('database.connections.mysql.password'))) {
            $issues[] = 'Database password is not set';
        }

        // Check for default database credentials
        $defaultCredentials = ['root', 'admin', 'test', 'password', '123456'];
        $dbPassword = config('database.connections.mysql.password');
        
        if (in_array($dbPassword, $defaultCredentials)) {
            $issues[] = 'Database is using a default/weak password';
        }

        return $issues;
    }

    private function checkFilePermissions(): array
    {
        $this->info('Checking File Permissions...');
        $issues = [];

        $criticalPaths = [
            '.env' => 0600,
            'storage' => 0755,
            'bootstrap/cache' => 0755,
        ];

        foreach ($criticalPaths as $path => $expectedPerms) {
            $fullPath = base_path($path);
            
            if (File::exists($fullPath)) {
                $currentPerms = fileperms($fullPath) & 0777;
                
                if ($path === '.env' && $currentPerms !== $expectedPerms) {
                    $issues[] = "{$path} has incorrect permissions. Should be " . decoct($expectedPerms);
                }
            }
        }

        return $issues;
    }

    private function checkConfigurationSecurity(): array
    {
        $this->info('Checking Configuration Security...');
        $issues = [];

        // Check CORS configuration
        $corsConfig = config('cors');
        if (empty($corsConfig) || (isset($corsConfig['allowed_origins']) && in_array('*', $corsConfig['allowed_origins']))) {
            $issues[] = 'CORS is configured to allow all origins (*) - this should be restricted in production';
        }

        // Check rate limiting
        if (!config('security.rate_limiting.enabled', true)) {
            $issues[] = 'Rate limiting is disabled - this should be enabled for security';
        }

        // Check security headers
        $securityHeaders = config('security.headers', []);
        $requiredHeaders = ['X-Frame-Options', 'X-Content-Type-Options', 'X-XSS-Protection'];
        
        foreach ($requiredHeaders as $header) {
            if (!isset($securityHeaders[$header])) {
                $issues[] = "Security header {$header} is not configured";
            }
        }

        return $issues;
    }

    private function checkMiddlewareSecurity(): array
    {
        $this->info('Checking Middleware Configuration...');
        $issues = [];

        // Check if security middleware is registered
        $kernel = app(\Illuminate\Contracts\Http\Kernel::class);
        $middleware = $kernel->getMiddleware();
        
        $requiredMiddleware = [
            \App\Http\Middleware\SecurityHeaders::class,
        ];

        foreach ($requiredMiddleware as $middlewareClass) {
            if (!in_array($middlewareClass, $middleware)) {
                $issues[] = "Security middleware {$middlewareClass} is not registered globally";
            }
        }

        return $issues;
    }

    private function displayResults(array $issues): void
    {
        $this->newLine();
        
        if (empty($issues)) {
            $this->info('✅ Security Audit Passed! No issues found.');
        } else {
            $this->error('❌ Security Audit Failed! Issues found:');
            $this->newLine();
            
            foreach ($issues as $index => $issue) {
                $this->line(($index + 1) . '. ' . $issue);
            }
            
            $this->newLine();
            $this->warn('Please address these security issues before deploying to production.');
        }
        
        $this->newLine();
        $this->info('Security audit completed.');
    }
}
