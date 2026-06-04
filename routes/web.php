<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Public booking tracking (customer can track by booking code)
Route::get('/tracking', function () {
    return view('tracking');
})->name('tracking');

Route::get('/tracking/search', function () {
    $booking = \App\Models\Booking::with(['customer', 'service', 'payment', 'statusLogs.updatedBy'])
        ->where('booking_code', request('code'))
        ->first();

    return view('tracking', compact('booking'));
})->name('tracking.search');

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Admin & Staff)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard — redirect to bookings index
    Route::get('/dashboard', [BookingController::class, 'index'])->name('dashboard');

    // Profile (Laravel Breeze default)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |----------------------------------------------------------------------
    | Booking Management
    |----------------------------------------------------------------------
    */
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', [BookingController::class, 'index'])->name('index');
        Route::get('/create', [BookingController::class, 'create'])->name('create');
        Route::post('/', [BookingController::class, 'store'])->name('store');
        Route::get('/{booking}', [BookingController::class, 'show'])->name('show');
        Route::patch('/{booking}/transition', [BookingController::class, 'transition'])->name('transition');
    });

    /*
    |----------------------------------------------------------------------
    | Payment Management
    |----------------------------------------------------------------------
    */
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/{booking}/create', [PaymentController::class, 'create'])->name('create');
        Route::post('/{booking}', [PaymentController::class, 'store'])->name('store');
        Route::patch('/{payment}/verify', [PaymentController::class, 'verify'])->name('verify');
    });

    /*
    |----------------------------------------------------------------------
    | Admin Only Routes
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin')->group(function () {

        // Service CRUD
        Route::resource('services', ServiceController::class)->except(['show']);

        // Promotion CRUD
        Route::resource('promotions', PromotionController::class)->except(['show']);
    });
});

require __DIR__.'/auth.php';
