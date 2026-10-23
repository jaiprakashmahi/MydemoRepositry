<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboard;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\PublicPropertyController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;



// ===== Public =====
//Route::get('/', [HomeController::class, 'index'])->name('home');

// ===== Public Dynamic Homepage =====
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('public.search');
Route::get('/properties/{property:slug}', [HomeController::class, 'showProperty'])->name('public.property.show');
Route::get('/category/{category:slug}', [HomeController::class, 'categoryProperties'])->name('public.category.show');

require __DIR__ . '/auth.php';

// ===== Post-login redirect =====
Route::get('/redirect', function () {
    return auth()->user()->isAdmin()
        ? redirect()->route('admin.dashboard')
        : redirect()->route('customer.dashboard');
})->middleware('auth')->name('redirect');

// ============================================================
// ================ ADMIN ROUTES ==============================
// ============================================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // ===== Property Management =====
    Route::patch('properties/{property}/toggle-status',   [PropertyController::class, 'toggleStatus'])->name('properties.toggle-status');
    Route::patch('properties/{property}/toggle-featured', [PropertyController::class, 'toggleFeatured'])->name('properties.toggle-featured');
    Route::patch('properties/{property}/approve',         [PropertyController::class, 'approve'])->name('properties.approve');
    Route::patch('properties/{property}/reject',          [PropertyController::class, 'reject'])->name('properties.reject');
    Route::delete('property-images/{image}',              [PropertyController::class, 'deleteImage'])->name('properties.delete-image');
    Route::delete('property-documents/{document}',        [PropertyController::class, 'deleteDocument'])->name('properties.delete-document');
    Route::resource('properties', PropertyController::class);

    // ===== Categories =====
    Route::patch('categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
    Route::resource('categories', CategoryController::class);

    // ===== Users =====
    Route::patch('users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
    Route::patch('users/{user}/make-admin',    [UserController::class, 'makeAdmin'])->name('users.make-admin');
    Route::patch('users/{user}/make-customer', [UserController::class, 'makeCustomer'])->name('users.make-customer');
    Route::resource('users', UserController::class);
});

// ============================================================
// ================ CUSTOMER ROUTES ===========================
// ============================================================
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [CustomerDashboard::class, 'index'])->name('dashboard');

    // ===== My Properties =====
    Route::get('/properties', [CustomerDashboard::class, 'myProperties'])->name('properties.index');
    Route::get('/properties/create', [CustomerDashboard::class, 'createProperty'])->name('properties.create');
    Route::post('/properties', [CustomerDashboard::class, 'storeProperty'])->name('properties.store');
    Route::get('/properties/{property}/edit', [CustomerDashboard::class, 'editProperty'])->name('properties.edit');
    Route::put('/properties/{property}', [CustomerDashboard::class, 'updateProperty'])->name('properties.update');
    Route::delete('/properties/{property}', [CustomerDashboard::class, 'destroyProperty'])->name('properties.destroy');
    Route::get('/properties/{property}', [CustomerDashboard::class, 'showProperty'])->name('properties.show');

    // ===== Profile =====
    Route::get('/profile', [CustomerDashboard::class, 'profile'])->name('profile');
    Route::put('/profile', [CustomerDashboard::class, 'updateProfile'])->name('profile.update');
});

// ============================================================
// ================ PUBLIC POST PROPERTY (auth) ===============
// ============================================================
Route::middleware(['auth'])->group(function () {
    Route::get('/post-property', [PublicPropertyController::class, 'index'])->name('public.post-property');
    Route::post('/post-property', [PublicPropertyController::class, 'store'])->name('public.post-property.store');
    Route::get('/post-property/success/{property}', [PublicPropertyController::class, 'success'])->name('public.post-property.success');
    Route::get('/my-properties', [PublicPropertyController::class, 'myProperties'])->name('public.my-properties');
});
