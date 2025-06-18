<?php

// Model Aliases for backward compatibility
if (!class_exists('App\Models\User')) {
    class_alias('App\Modules\User\Models\User', 'App\Models\User');
}

// Additional helper functions
if (!function_exists('format_bytes')) {
    /**
     * Format bytes to human readable format
     */
    function format_bytes($bytes, $precision = 2) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}

if (!function_exists('generate_api_key')) {
    /**
     * Generate a secure API key
     */
    function generate_api_key($length = 32) {
        return bin2hex(random_bytes($length));
    }
}

if (!function_exists('mask_email')) {
    /**
     * Mask email address for security
     */
    function mask_email($email) {
        $parts = explode('@', $email);
        $name = $parts[0];
        $domain = $parts[1];
        
        $masked_name = substr($name, 0, 2) . str_repeat('*', strlen($name) - 2);
        
        return $masked_name . '@' . $domain;
    }
}
