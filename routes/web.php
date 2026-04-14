<?php

use App\Http\Controllers\Admin\KycController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Events
    Route::resource('events', EventController::class);

    // Event registration (student)
    Route::post('events/{event}/register', [EventRegistrationController::class, 'store'])->name('events.register');
    Route::delete('events/{event}/register', [EventRegistrationController::class, 'destroy'])->name('events.unregister');

    // QR Check-in (partner)
    Route::post('events/{event}/checkin', [CheckInController::class, 'store'])->name('events.checkin');

    // Admin
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('kyc', [KycController::class, 'index'])->name('kyc.index');
        Route::patch('kyc/{partner}/approve', [KycController::class, 'approve'])->name('kyc.approve');
        Route::patch('kyc/{partner}/reject', [KycController::class, 'reject'])->name('kyc.reject');
    });
});

require __DIR__.'/auth.php';
