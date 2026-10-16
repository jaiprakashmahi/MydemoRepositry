<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PropertyController;

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
// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth routes (Laravel default)
require __DIR__ . '/auth.php';

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

    // ===== Category CRUD =====
    Route::patch('categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])
        ->name('categories.toggle-status');
    Route::resource('categories', CategoryController::class);

    // ===== Properties =====
    Route::patch('properties/{property}/toggle-status',   [PropertyController::class, 'toggleStatus'])->name('properties.toggle-status');
    Route::patch('properties/{property}/toggle-featured', [PropertyController::class, 'toggleFeatured'])->name('properties.toggle-featured');
    Route::delete('property-images/{image}',   [PropertyController::class, 'deleteImage'])->name('properties.delete-image');
    Route::delete('property-documents/{document}', [PropertyController::class, 'deleteDocument'])->name('properties.delete-document');
    Route::resource('properties', PropertyController::class);
});



// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
