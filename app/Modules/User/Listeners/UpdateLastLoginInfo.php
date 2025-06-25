<?php

namespace App\Modules\User\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;

class UpdateLastLoginInfo
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $user = $event->user;
        
        // Update login information
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => request()->ip(),
            'login_count' => $user->login_count + 1,
        ]);
        
        Log::info('User login tracked', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'login_count' => $user->login_count
        ]);
    }
}
