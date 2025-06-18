<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SystemCleanup extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'system:cleanup 
                          {--logs : Clean old log files}
                          {--cache : Clear application cache}
                          {--sessions : Clean expired sessions}
                          {--temp : Clean temporary files}
                          {--all : Run all cleanup tasks}';

    /**
     * The console command description.
     */
    protected $description = 'Clean up system files, cache, and database records';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting system cleanup...');

        if ($this->option('all') || $this->option('logs')) {
            $this->cleanupLogs();
        }

        if ($this->option('all') || $this->option('cache')) {
            $this->clearCache();
        }

        if ($this->option('all') || $this->option('sessions')) {
            $this->cleanupSessions();
        }

        if ($this->option('all') || $this->option('temp')) {
            $this->cleanupTempFiles();
        }

        $this->info('System cleanup completed!');

        return Command::SUCCESS;
    }

    private function cleanupLogs(): void
    {
        $this->info('Cleaning up old log files...');

        $logPath = storage_path('logs');
        $files = glob($logPath . '/*.log');
        $cleaned = 0;

        foreach ($files as $file) {
            if (filemtime($file) < strtotime('-30 days')) {
                unlink($file);
                $cleaned++;
            }
        }

        $this->info("Cleaned {$cleaned} old log files.");
    }

    private function clearCache(): void
    {
        $this->info('Clearing application cache...');

        Cache::flush();
        $this->call('config:clear');
        $this->call('route:clear');
        $this->call('view:clear');

        $this->info('Application cache cleared.');
    }

    private function cleanupSessions(): void
    {
        $this->info('Cleaning up expired sessions...');

        try {
            $deleted = DB::table('sessions')
                ->where('last_activity', '<', now()->subDays(30)->timestamp)
                ->delete();

            $this->info("Cleaned {$deleted} expired sessions.");
        } catch (\Exception $e) {
            $this->warn('Could not clean sessions: ' . $e->getMessage());
        }
    }

    private function cleanupTempFiles(): void
    {
        $this->info('Cleaning up temporary files...');

        $tempPaths = [
            storage_path('app/temp'),
            storage_path('framework/cache'),
            storage_path('framework/sessions'),
            storage_path('framework/views'),
        ];

        $cleaned = 0;

        foreach ($tempPaths as $path) {
            if (is_dir($path)) {
                $files = glob($path . '/*');
                foreach ($files as $file) {
                    if (is_file($file) && filemtime($file) < strtotime('-7 days')) {
                        unlink($file);
                        $cleaned++;
                    }
                }
            }
        }

        $this->info("Cleaned {$cleaned} temporary files.");
    }
}
