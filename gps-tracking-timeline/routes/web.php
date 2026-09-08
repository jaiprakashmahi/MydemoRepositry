<?php

use App\Http\Controllers\TrackingTimelineController;
use Illuminate\Support\Facades\Route;

// Add this line into your project's routes/web.php
Route::get('/tracking-timeline', [TrackingTimelineController::class, 'index'])
    ->name('tracking.timeline');
