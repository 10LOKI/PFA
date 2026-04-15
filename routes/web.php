<?php

use App\Http\Controllers\Admin\KycController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'));

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/student', [DashboardController::class, 'student'])->name('dashboard.student');
    Route::get('/dashboard/partner', [DashboardController::class, 'partner'])->name('dashboard.partner');
    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
});

Route::middleware('auth')->group(function () {
    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');

    // Messages
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{conversation}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::post('/messages/start', [MessageController::class, 'start'])->name('messages.start');

    // Profile
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
    Route::get('events/{event}/qr', [EventController::class, 'qr'])->name('events.qr');

    // Rewards marketplace
    Route::get('rewards', [RewardController::class, 'index'])->name('rewards.index');
    Route::post('rewards/{reward}/redeem', [RewardController::class, 'redeem'])->name('rewards.redeem');

    // Admin
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('kyc', [KycController::class, 'index'])->name('kyc.index');
        Route::patch('kyc/{partner}/approve', [KycController::class, 'approve'])->name('kyc.approve');
        Route::patch('kyc/{partner}/reject', [KycController::class, 'reject'])->name('kyc.reject');
    });
});

require __DIR__.'/auth.php';
