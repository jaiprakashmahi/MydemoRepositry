<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrackingTimelineController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| This is your project's routes/web.php with Breeze's scaffolded routes
| kept intact -- the only change is the /dashboard route now points at
| TrackingTimelineController instead of the static Breeze welcome view.
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [TrackingTimelineController::class, 'index'])
    ->middleware(['auth', 'verified']) // drop 'verified' if you're not using email verification
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
