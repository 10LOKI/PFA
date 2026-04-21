<?php

use App\Http\Controllers\Admin\KycController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\CheckOutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishlistController;
use App\Models\Event;
use App\Models\User;
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

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markRead');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unreadCount');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Events
    Route::resource('events', EventController::class);

    // Event registration (student)
    Route::post('events/{event}/register', [EventRegistrationController::class, 'store'])->name('events.register');
    Route::delete('events/{event}/register', [EventRegistrationController::class, 'destroy'])->name('events.unregister');

    // Event wishlist (student)
    Route::post('events/{event}/wishlist', [WishlistController::class, 'store'])->name('events.wishlist');
    Route::delete('events/{event}/wishlist', [WishlistController::class, 'destroy'])->name('events.unwishlist');
    // Likes
    Route::post('events/{event}/like', [LikeController::class, 'store'])->name('events.like');
    Route::delete('events/{event}/like', [LikeController::class, 'destroy'])->name('events.unlike');

    // QR Check-in (partner)
    Route::post('events/{event}/checkin', [CheckInController::class, 'store'])->name('events.checkin');
    Route::post('events/{event}/checkout', [CheckOutController::class, 'store'])->name('events.checkout');
    Route::get('events/{event}/qr', [EventController::class, 'qr'])->name('events.qr');

    // Admin - Event approval
    Route::patch('events/{event}/approve', [EventController::class, 'approve'])->name('events.approve');
    Route::patch('events/{event}/reject', [EventController::class, 'reject'])->name('events.reject');

    // Rewards marketplace
    Route::get('rewards', [RewardController::class, 'index'])->name('rewards.index');
    Route::get('rewards/create', [RewardController::class, 'create'])->name('rewards.create');
    Route::post('rewards', [RewardController::class, 'store'])->name('rewards.store');
    Route::get('rewards/{reward}/edit', [RewardController::class, 'edit'])->name('rewards.edit');
    Route::put('rewards/{reward}', [RewardController::class, 'update'])->name('rewards.update');
    Route::delete('rewards/{reward}', [RewardController::class, 'destroy'])->name('rewards.destroy');
    Route::post('rewards/{reward}/redeem', [RewardController::class, 'redeem'])->name('rewards.redeem');

    // Wallet
    Route::get('wallet', [WalletController::class, 'index'])->name('wallet.index');

    // Leaderboard
    Route::get('leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');
    Route::get('leaderboard/city/{city}', [LeaderboardController::class, 'city'])->name('leaderboard.city');
    Route::get('leaderboard/establishment/{establishment}', [LeaderboardController::class, 'establishment'])->name('leaderboard.establishment');
    Route::get('leaderboard/weekly', [LeaderboardController::class, 'weekly'])->name('leaderboard.weekly');

    // Admin
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('kyc', [KycController::class, 'index'])->name('kyc.index');
        Route::patch('kyc/{partner}/approve', [KycController::class, 'approve'])->name('kyc.approve');
        Route::patch('kyc/{partner}/reject', [KycController::class, 'reject'])->name('kyc.reject');
    });
});

require __DIR__.'/auth.php';

// Debug route to test notifications - access as logged in user
Route::middleware('auth')->get('/debug-notification', function () {
    $event = Event::latest()->first();

    if (! $event) {
        return response()->json(['error' => 'No events found']);
    }

    // Get other users (not current user)
    $users = User::where('id', '!=', auth()->id())->get();

    // Save to custom notifications table
    foreach ($users as $user) {
        Notification::create([
            'user_id' => $user->id,
            'type' => 'event_created',
            'title' => 'Nouvel événement',
            'message' => $event->title,
            'link' => route('events.show', $event),
            'event_id' => $event->id,
            'read' => false,
        ]);
    }

    return response()->json([
        'success' => true,
        'message' => 'Notification sent to '.$users->count().' users',
        'event' => $event->title,
    ]);
});
