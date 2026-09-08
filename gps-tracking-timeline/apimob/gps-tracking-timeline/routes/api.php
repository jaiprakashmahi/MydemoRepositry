<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LocationLogController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| All routes here are automatically prefixed with /api and go through
| Laravel's 'api' middleware group (stateless, throttle:api = 60 req/min
| by default). Add these into your project's routes/api.php.
*/

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Extra throttle on top of the default api throttle: a device pinging
    // every minute plus occasional batch syncs comfortably fits under 30/min.
    Route::middleware('throttle:30,1')->group(function () {
        Route::post('/location-logs', [LocationLogController::class, 'store']);
        Route::post('/location-logs/batch', [LocationLogController::class, 'storeBatch']);
    });
});
