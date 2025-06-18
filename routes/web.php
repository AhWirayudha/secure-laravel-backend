<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return response()->json([
        'message' => 'Secure Laravel Backend API',
        'version' => config('app.version', '1.0.0'),
        'documentation' => url('/api/documentation'),
        'health_check' => url('/api/health'),
        'environment' => app()->environment(),
        'timestamp' => now()->toDateTimeString(),
    ]);
});

Route::get('/status', function () {
    return response()->json([
        'status' => 'operational',
        'services' => [
            'database' => 'ok',
            'redis' => 'ok',
            'queue' => 'ok',
        ],
        'timestamp' => now()->toDateTimeString(),
    ]);
});
